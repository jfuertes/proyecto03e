<?php
	require_once('../../../api/config/oracle.php');
	
	$db  = new dbConnect();
	$dbh = $db->conectardb();
	session_start();

	$rspta = json_decode(file_get_contents("php://input"), true);
	$success="";
	$error="";
	

	//var_dump($rspta);

	foreach ($rspta['va'] as $v) {
	
		$IDPROYECTO = isset($v['id del proyecto'])?$v['id del proyecto']:null;
		//echo $IDPROYECTO;
		$CODPROY = isset($v['codigo del proyecto'])?$v['codigo del proyecto']:null;
		$NAMEPROY = isset($v['Nombre del proyecto'])?$v['Nombre del proyecto']:null;
		$COMENTARIO = isset($v['Ultimo comentario'])?$v['Ultimo comentario']:null;

		$ESTADOPROY=1;
		$nuevoproyecto=0;

		if($CODPROY == null || $NAMEPROY == null || $COMENTARIO == null){
			$error.="Error: Se encontro una fila sin código ni nombre de proyecto\n";
		}
		else{
			if($IDPROYECTO == null){
				$nuevoproyecto=1;

				$q = 'SELECT max(IDPROYECTO) + 1 as IDPROYECTO from proyred.proyecto';
				$stmt = $dbh->prepare($q);
				$stmt->execute();
				$r=$stmt->fetch(PDO::FETCH_ASSOC);

				$IDPROYECTO=$r['IDPROYECTO'];

				$q = 'INSERT INTO proyred.PROYECTO (IDPROYECTO, NOMBREPROY, ESTADOPROY, IDPROYMACRO, CODPROYECTO)
							VALUES (:IDPROYECTO, :NOMBREPROY, :ESTADOPROY, :IDPROYMACRO, :CODPROYECTO) ';
				$stmt = $dbh->prepare($q);
				$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
				$stmt->bindParam(':NOMBREPROY',  $NAMEPROY, PDO::PARAM_STR);
				$stmt->bindParam(':ESTADOPROY',  $ESTADOPROY, PDO::PARAM_STR);

				$stmt->bindParam(':IDPROYMACRO',  $rspta['pm']['idProy'], PDO::PARAM_STR);
				$stmt->bindParam(':CODPROYECTO',  $CODPROY, PDO::PARAM_STR);
				$valor = $stmt->execute();
				
				if($valor==true){
					$success.="Se creó el proyecto: $NAMEPROY.\n";
				}
				else{
					$error.="Error: No fue posible crear el proyecto $NAMEPROY\n";
				}
			}

			// Insertando comentario
			//var_dump($COMENTARIO);
			if($COMENTARIO!=""){
				//Calculando id siguiente comentario:
				$q = 'SELECT max(IDCOMENTARIO) +1 as IDCOMENTARIO from proyred.COMENTARIO';
				$stmt = $dbh->prepare($q);
				$stmt->execute();
				$r=$stmt->fetch(PDO::FETCH_ASSOC);

				$IDCOMENTARIO=$r['IDCOMENTARIO'];
				$IDUSUARIO= $_SESSION['IDUSUARIO'];
				$LOGINUS= $_SESSION['login'];
				// Insertando nuevo comentario
				$q = 'INSERT INTO proyred.COMENTARIO (IDCOMENTARIO, IDPROYECTO, IDUSUARIO, FECHACREACION, COMENT)
						VALUES(:IDCOMENTARIO, :IDPROYECTO, :IDUSUARIO, CURRENT_TIMESTAMP, :COMENT)';
				$stmt = $dbh->prepare($q);
				$stmt->bindParam(':IDCOMENTARIO',  $IDCOMENTARIO, PDO::PARAM_STR);
				$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
				$stmt->bindParam(':IDUSUARIO',  $IDUSUARIO, PDO::PARAM_STR);
				$stmt->bindParam(':COMENT',  $COMENTARIO, PDO::PARAM_STR);
				$resultado = $stmt->execute();

				if($resultado==true){
					$success.="Comentario agregado.\n";
				}
				else{
					$error.=" Error: Error a tratar de insertar comentario";
				}
			}
			else{
				$error.=" Alerta: No se ingresó comentario para ser agregado";
			}
		
			foreach ($rspta['pa'] as $pa) {
				$error=0;
				$IDVALOR=null;
					//calculando idvalor
				if($nuevoproyecto==0){
					$q = 'SELECT IDVALOR from proyred.valor 
						where IDPARAMETRO=:IDPARAMETRO and IDPROYECTO=:IDPROYECTO';
					$stmt = $dbh->prepare($q);
					$stmt->bindParam(':IDPARAMETRO',  $pa['IDPARAMETRO'], PDO::PARAM_STR);
					$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
					$stmt->execute();
					$r=$stmt->fetch(PDO::FETCH_ASSOC);
					$IDVALOR=$r['IDVALOR'];
				}

				if($IDVALOR==null || $nuevoproyecto==1){
					$q = 'SELECT max(IDVALOR) +1 as IDVALOR from proyred.valor';
					$stmt = $dbh->prepare($q);
					$stmt->execute();
					$r=$stmt->fetch(PDO::FETCH_ASSOC);

					$IDVALOR=$r['IDVALOR'];
				}

				if($pa['TIPOPMPARAM']=="V"){
					$success.=" El parametro ".$pa['NOMBREPARAM']." es solo de visualizacion por lo que ningun cambio en el sera efectivo.\n";
				}

				else{//verificar que sea principal en Tabla pmparametro
					/*echo $v[$pa['NOMBREPARAM']];
					echo $pa['IDTIPODATO'];
					var_dump($esunaetiqueta) ;
					echo "-------------";*/
					if($pa['USAMAESTROPARAM']=="1"){
					$esunaetiqueta=false;//usa etiquetas
						//var_dump($pa);
						$q = 'SELECT ETIQUETA from proyred.MAESTRO 
							where IDPARAMETRO=:IDPARAMETRO and LOWER(ETIQUETA)=LOWER(:ETIQUETA)';
							$stmt = $dbh->prepare($q);
							$stmt->bindParam(':IDPARAMETRO',  $pa['IDPARAMETRO'], PDO::PARAM_STR);
							$stmt->bindParam(':ETIQUETA',  $v[$pa['NOMBREPARAM']], PDO::PARAM_STR);
							$stmt->execute();
							$r=$stmt->fetch(PDO::FETCH_ASSOC);
							//echo "================";
							//echo $v[$pa['NOMBREPARAM']];
							//var_dump($r);
							//echo "================";
							if ($r != false){
								$v[$pa['NOMBREPARAM']]=$r['ETIQUETA'];
								$esunaetiqueta=true;
							}
							else{

								//$success.="ERROR: Se encontro error al actualizar el valor ".$pa['NOMBREPARAM'].": $nombre_parametro del proyecto ".$IDPROYECTO." dicho valor solo acepta valores definidos como etiquetas.\n";
							}
							
					}
					else{
						$esunaetiqueta=true;
					}
					
					if(($pa['IDTIPODATO']==1 || $pa['IDTIPODATO']==2) && $esunaetiqueta== true){
						//echo "######entro";
						$nombrevalor="VALORNUMBER";
						$entró=false;

						if ($v[$pa['NOMBREPARAM']]==null || $v[$pa['NOMBREPARAM']]=="NaN" || is_numeric($v[$pa['NOMBREPARAM']]!=true)){
							$valoractual=null;
						
							$q= "MERGE INTO PROYRED.VALOR vl
								USING( SELECT :IDVALOR IDVALOR, :IDPARAMETRO IDPARAMETRO, :IDPROYECTO IDPROYECTO, '".$valoractual."' $nombrevalor FROM dual) src
								ON( vl.IDVALOR= src.IDVALOR)
								WHEN MATCHED THEN

								UPDATE SET $nombrevalor = src.$nombrevalor 
								WHEN NOT MATCHED THEN
								INSERT( IDVALOR, IDPARAMETRO, IDPROYECTO, $nombrevalor) 
								VALUES(src.IDVALOR, src.IDPARAMETRO, src.IDPROYECTO, src.$nombrevalor)";

								$entro=true;
								$error=1;
						}
						else  if($v[$pa['NOMBREPARAM']]!=null && is_numeric($v[$pa['NOMBREPARAM']])){
							$valoractual=$v[$pa['NOMBREPARAM']];
							$q= "MERGE INTO PROYRED.VALOR vl
								USING( SELECT :IDVALOR IDVALOR, :IDPARAMETRO IDPARAMETRO, :IDPROYECTO IDPROYECTO, ".$valoractual." $nombrevalor FROM dual) src
								ON( vl.IDVALOR= src.IDVALOR)
								WHEN MATCHED THEN

								UPDATE SET $nombrevalor = src.$nombrevalor 
								WHEN NOT MATCHED THEN
								INSERT( IDVALOR, IDPARAMETRO, IDPROYECTO, $nombrevalor) 
								VALUES(src.IDVALOR, src.IDPARAMETRO, src.IDPROYECTO, src.$nombrevalor)";

								$entro=true;
						}
						
						if($entro==true){
							$stmt = $dbh->prepare($q);
							$stmt->bindParam(':IDVALOR',  $IDVALOR, PDO::PARAM_STR);
							$stmt->bindParam(':IDPARAMETRO',  $pa['IDPARAMETRO'], PDO::PARAM_STR);
							$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
							//$stmt->bindParam(':VALORSTR',  $v[$pa['NOMBREPARAM']], PDO::PARAM_STR);
							$valor = $stmt->execute();	
						}
						
						
						if($valor!=true) $error=1;

					}
					else if($pa['IDTIPODATO']==3 && $esunaetiqueta== true){
						//echo "######entro";
						$nombrevalor="VALORSTR";
						$q= "MERGE INTO PROYRED.VALOR vl
						USING( SELECT :IDVALOR IDVALOR, :IDPARAMETRO IDPARAMETRO, :IDPROYECTO IDPROYECTO, :VALORSTR $nombrevalor FROM dual) src
						ON( vl.IDVALOR= src.IDVALOR)
						WHEN MATCHED THEN

						UPDATE SET $nombrevalor = src.$nombrevalor 
						WHEN NOT MATCHED THEN
						INSERT( IDVALOR, IDPARAMETRO, IDPROYECTO, $nombrevalor) 
						VALUES(src.IDVALOR, src.IDPARAMETRO, src.IDPROYECTO, src.$nombrevalor)";

						$stmt = $dbh->prepare($q);
						$stmt->bindParam(':IDVALOR',  $IDVALOR, PDO::PARAM_STR);
						$stmt->bindParam(':IDPARAMETRO',  $pa['IDPARAMETRO'], PDO::PARAM_STR);
						$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
						$stmt->bindParam(':VALORSTR',  $v[$pa['NOMBREPARAM']], PDO::PARAM_STR);

						$valor = $stmt->execute();

						//echo json_encode($valor);
						if($valor!=true) $error=1;
					}
					else if($pa['IDTIPODATO']==4 && $esunaetiqueta== true){
						//echo "######entro";
						//manipular fecha
						if($v[$pa['NOMBREPARAM']]!=null){
							
							list($day, $month, $year) = split("/", $v[$pa['NOMBREPARAM']]);
							$date = date_create("$year-$month-$day", timezone_open("America/Lima"));
							$valoractual=date_format($date, 'd-m-Y');
							$nombrevalor="VALORDATE";
						}
						else{
							$nombrevalor="VALORDATE";
							$valoractual=null;
						}
						$q= "MERGE INTO PROYRED.VALOR vl
							USING( SELECT :IDVALOR IDVALOR, :IDPARAMETRO IDPARAMETRO, :IDPROYECTO IDPROYECTO, '".$valoractual."' ".$nombrevalor." FROM dual) src
							ON( vl.IDVALOR= src.IDVALOR)
							WHEN MATCHED THEN

							UPDATE SET $nombrevalor = src.$nombrevalor 
							WHEN NOT MATCHED THEN
							INSERT( IDVALOR, IDPARAMETRO, IDPROYECTO, $nombrevalor) 
							VALUES(src.IDVALOR, src.IDPARAMETRO, src.IDPROYECTO, src.$nombrevalor)";

							$stmt = $dbh->prepare($q);
							$stmt->bindParam(':IDVALOR',  $IDVALOR, PDO::PARAM_STR);
							$stmt->bindParam(':IDPARAMETRO',  $pa['IDPARAMETRO'], PDO::PARAM_STR);
							$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
							//$stmt->bindParam(':VALORSTR',  $v[$pa['NOMBREPARAM']], PDO::PARAM_STR);
							$valor = $stmt->execute();
							//echo json_encode($valor);
							if($valor!=true) $error=1;
						}
						//echo $esunaetiqueta;
						$nombre_parametro=$v[$pa['NOMBREPARAM']];
						if(	$esunaetiqueta== false){
							$success.="ERROR: Se encontro error al actualizar el valor ".$pa['NOMBREPARAM'].": $nombre_parametro del proyecto ".$IDPROYECTO." ,El valor ingresado no se encuentra en la lista de etiuetas.\n";
						}
						else if($error==1){
							$success.=" ERROR: Se encontro error al actualizar el valor ".$pa['NOMBREPARAM'].": $nombre_parametro del proyecto ".$IDPROYECTO."\n";
						}
						else {
							$success.=" Se creó y actualizó con exito el valor ".$pa['NOMBREPARAM'].": $nombre_parametro del proyecto".$IDPROYECTO."\n";
						}

						$error=0;
						
						//acaba for each
					}
				}
			}
		}

	$respuesta=array('success' => $success, 'error' => $error);

	echo json_encode($respuesta);
?>