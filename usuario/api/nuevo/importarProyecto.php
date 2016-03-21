<?php
	require_once('../../../api/config/oracle.php');
	
	$db  = new dbConnect();
	$dbh = $db->conectardb();

	$rspta = json_decode(file_get_contents("php://input"), true);
	$success="";
	$error="";

	//var_dump($rspta);

	foreach ($rspta['va'] as $v) {
	
		$IDPROYECTO = isset($v['id del proyecto'])?$v['id del proyecto']:null;
		//echo $IDPROYECTO;
		$CODPROY = isset($v['codigo del proyecto'])?$v['codigo del proyecto']:null;
		$NAMEPROY = isset($v['Nombre del proyecto'])?$v['Nombre del proyecto']:null;

		$ESTADOPROY=1;
		$nuevoproyecto=0;

		if($CODPROY == null || $NAMEPROY == null){
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
		
			foreach ($rspta['pa'] as $pa) {
				$error=0;
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

				if($IDVALOR==NULL || $nuevoproyecto==1){
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
					if($pa['IDTIPODATO']==1 || $pa['IDTIPODATO']==2){
						$nombrevalor="VALORNUMBER";

						if ($v[$pa['NOMBREPARAM']]==null || $v[$pa['NOMBREPARAM']]=="NaN"){
							$valoractual=null;
						
							$q= "MERGE INTO PROYRED.VALOR vl
								USING( SELECT :IDVALOR IDVALOR, :IDPARAMETRO IDPARAMETRO, :IDPROYECTO IDPROYECTO, '".$valoractual."' $nombrevalor FROM dual) src
								ON( vl.IDVALOR= src.IDVALOR)
								WHEN MATCHED THEN

								UPDATE SET $nombrevalor = src.$nombrevalor 
								WHEN NOT MATCHED THEN
								INSERT( IDVALOR, IDPARAMETRO, IDPROYECTO, $nombrevalor) 
								VALUES(src.IDVALOR, src.IDPARAMETRO, src.IDPROYECTO, src.$nombrevalor)";
						}
						else  if($v[$pa['NOMBREPARAM']]!=null ){
							$valoractual=$v[$pa['NOMBREPARAM']];
								$q= "MERGE INTO PROYRED.VALOR vl
									USING( SELECT :IDVALOR IDVALOR, :IDPARAMETRO IDPARAMETRO, :IDPROYECTO IDPROYECTO, ".$valoractual." $nombrevalor FROM dual) src
									ON( vl.IDVALOR= src.IDVALOR)
									WHEN MATCHED THEN

									UPDATE SET $nombrevalor = src.$nombrevalor 
									WHEN NOT MATCHED THEN
									INSERT( IDVALOR, IDPARAMETRO, IDPROYECTO, $nombrevalor) 
									VALUES(src.IDVALOR, src.IDPARAMETRO, src.IDPROYECTO, src.$nombrevalor)";
						}
						
						$stmt = $dbh->prepare($q);
						$stmt->bindParam(':IDVALOR',  $IDVALOR, PDO::PARAM_STR);
						$stmt->bindParam(':IDPARAMETRO',  $pa['IDPARAMETRO'], PDO::PARAM_STR);
						$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
						//$stmt->bindParam(':VALORSTR',  $v[$pa['NOMBREPARAM']], PDO::PARAM_STR);
						$valor = $stmt->execute();
						
						if($valor!=true) $error=1;

					}
					else if($pa['IDTIPODATO']==3){
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
					else{
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
						$nombre_parametro=$v[$pa['NOMBREPARAM']];
						
						if($error==1){
							$error.="Se encontro error al actualizar el valor ".$pa['NOMBREPARAM'].": $nombre_parametro del proyecto ".$IDPROYECTO."\n";
						}
						else{
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