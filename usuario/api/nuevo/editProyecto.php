<?php
	require_once('../../../api/config/oracle.php');

	$db  = new dbConnect();
	$dbh = $db->conectardb();
	session_start();

 	$rspta = json_decode(file_get_contents("php://input"));

	$NOMBREPROY = isset($rspta->pro->NOMBREPROY)?$rspta->pro->NOMBREPROY:null;
	$CODPROYECTO = isset($rspta->pro->CODPROYECTO)?$rspta->pro->CODPROYECTO:null;
	$IDPROYECTO = isset($rspta->pro->IDPROYECTO)?$rspta->pro->IDPROYECTO:null;
	$ESTADOPROY = isset($rspta->pro->ESTADOPROY)?$rspta->pro->ESTADOPROY:null;
	$COMENT = isset($rspta->pro->COMENTARIO)?$rspta->pro->COMENTARIO:null;
	$VALORES = isset($rspta->pro->valores)?$rspta->pro->valores:null;
	
	if($NOMBREPROY!=NULL && $CODPROYECTO!=NULL && $IDPROYECTO!=NULL && $ESTADOPROY!=NULL && $VALORES!=NULL){
		$q = 'UPDATE proyred.PROYECTO 
					SET NOMBREPROY=:NOMBREPROY, CODPROYECTO=:CODPROYECTO, ESTADOPROY=:ESTADOPROY
					WHERE IDPROYECTO=:IDPROYECTO';
		$stmt = $dbh->prepare($q);
		$stmt->bindParam(':NOMBREPROY',  $NOMBREPROY, PDO::PARAM_STR);
		$stmt->bindParam(':CODPROYECTO',  $CODPROYECTO, PDO::PARAM_STR);
		$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
		$stmt->bindParam(':ESTADOPROY',  $ESTADOPROY, PDO::PARAM_STR);
		$resultado1 = $stmt->execute();
		
		if($resultado1){
			// Creación nuevo comentario
			if($COMENT!=""){
				//Calculando id siguiente comentario:
				$q = 'SELECT max(IDCOMENTARIO) +1 as IDCOMENTARIO from proyred.COMENTARIO';
				$stmt = $dbh->prepare($q);
				$stmt->execute();
				$r=$stmt->fetch(PDO::FETCH_ASSOC);
	
				$IDCOMENTARIO=$r['IDCOMENTARIO'];
				$IDUSUARIO= $_SESSION['IDUSUARIO'];
				// Insertando nuevo comentario
				$q = 'INSERT INTO proyred.COMENTARIO (IDCOMENTARIO, IDPROYECTO, IDUSUARIO, FECHACREACION, COMENT)
						VALUES(:IDCOMENTARIO, :IDPROYECTO, :IDUSUARIO, CURRENT_TIMESTAMP, :COMENT)';
				$stmt = $dbh->prepare($q);
				$stmt->bindParam(':IDCOMENTARIO',  $IDCOMENTARIO, PDO::PARAM_STR);
				$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
				$stmt->bindParam(':IDUSUARIO',  $IDUSUARIO, PDO::PARAM_STR);
				$stmt->bindParam(':COMENT',  $COMENT, PDO::PARAM_STR);
				$resultado2 = $stmt->execute();
			}

			foreach ($VALORES as $key => $value) {
				//var_dump($value->IDVALOR);
				//Si el valor no existe en la bdd, lo creamos
				if($value->IDVALOR==NULL){
					//Calculando id siguiente comentario:
					$q = 'SELECT max(IDVALOR) +1 as IDVALOR from proyred.VALOR';
					$stmt = $dbh->prepare($q);
					$stmt->execute();
					$r=$stmt->fetch(PDO::FETCH_ASSOC);

					$value->IDVALOR=$r['IDVALOR'];
				}

				if($value->IDTIPODATO=="1" || $value->IDTIPODATO=="2"){

					if($value->IDTIPODATO=="1") $valoractual= intval($value->VAL);
					elseif($value->IDTIPODATO=="2") $valoractual= floatval($value->VAL);

					$q= "MERGE INTO PROYRED.VALOR vl
						USING( SELECT :IDVALOR IDVALOR, :IDPARAMETRO IDPARAMETRO, :IDPROYECTO IDPROYECTO, ".$valoractual." VALORNUMBER FROM dual) src
						ON( vl.IDVALOR= src.IDVALOR)
						WHEN MATCHED THEN

						UPDATE SET VALORNUMBER = src.VALORNUMBER 
						WHEN NOT MATCHED THEN
						INSERT( IDVALOR, IDPARAMETRO, IDPROYECTO, VALORNUMBER) 
						VALUES(src.IDVALOR, src.IDPARAMETRO, src.IDPROYECTO, src.VALORNUMBER)";


					$stmt = $dbh->prepare($q);
					//$stmt->bindParam(':VALORNUMBER', $valoractual, PDO::PARAM_INT);
					$stmt->bindParam(':IDVALOR',  $value->IDVALOR, PDO::PARAM_STR);
					$stmt->bindParam(':IDPARAMETRO',  $value->IDPARAMETRO, PDO::PARAM_STR);
					$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
					$valor = $stmt->execute();

					if($valor==false){
						$mensaje=array('Error' => "Error: Se encontró un error al actualizar el valor ".$value->NOMBREPARA.": ".$value->VAL);
						echo json_encode($mensaje);
						break;
					}
				}
				else if($value->IDTIPODATO=="3"){
					
					$q= "MERGE INTO PROYRED.VALOR vl
					USING( SELECT :IDVALOR IDVALOR, :IDPARAMETRO IDPARAMETRO, :IDPROYECTO IDPROYECTO, :VALORSTR VALORSTR FROM dual) src
					ON( vl.IDVALOR= src.IDVALOR)
					WHEN MATCHED THEN

					UPDATE SET VALORSTR = src.VALORSTR 
					WHEN NOT MATCHED THEN
					INSERT( IDVALOR, IDPARAMETRO, IDPROYECTO, VALORSTR) 
					VALUES(src.IDVALOR, src.IDPARAMETRO, src.IDPROYECTO, src.VALORSTR)";

					$stmt = $dbh->prepare($q);
					$stmt->bindParam(':IDVALOR',  $value->IDVALOR, PDO::PARAM_STR);
					$stmt->bindParam(':IDPARAMETRO',  $value->IDPARAMETRO, PDO::PARAM_STR);
					$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
					$stmt->bindParam(':VALORSTR', $value->VAL, PDO::PARAM_STR);

					$valor = $stmt->execute();

					if($valor==false){
						$mensaje=array('Error' => "Error: Se encontró un error al actualizar el valor ".$value->NOMBREPARA.": ".$value->VAL);
						echo json_encode($mensaje);
						break;
					}
				}
				else{
					$valorTmp=isset($value->VAL)?$value->VAL:"";
					if($valorTmp!=""){
						$tmp = split("T", $valorTmp);
	
						if(sizeof($tmp)==1){
							list($year, $month, $day) = split("/", $valorTmp);
							$date = date_create("$year-$month-$day", timezone_open("America/Lima"));
						}
						else{
							list($fecha, $hora) = split("T", $valorTmp);
							list($year, $month, $day) = split("-", $fecha);
							$date = date_create("$year-$month-$day", timezone_open("America/Lima"));
						}
						
						$valoractual=date_format($date, 'd-m-Y');
	
						$q= "MERGE INTO PROYRED.VALOR vl
							USING( SELECT :IDVALOR IDVALOR, :IDPARAMETRO IDPARAMETRO, :IDPROYECTO IDPROYECTO, '".$valoractual."' VALORDATE FROM dual) src
							ON( vl.IDVALOR= src.IDVALOR)
							WHEN MATCHED THEN
	
							UPDATE SET VALORDATE = src.VALORDATE
							WHEN NOT MATCHED THEN
							INSERT( IDVALOR, IDPARAMETRO, IDPROYECTO,VALORDATE) 
							VALUES(src.IDVALOR, src.IDPARAMETRO, src.IDPROYECTO, src.VALORDATE)";
	
						$stmt = $dbh->prepare($q);
						$stmt->bindParam(':IDVALOR',  $value->IDVALOR, PDO::PARAM_STR);
						$stmt->bindParam(':IDPARAMETRO',  $value->IDPARAMETRO, PDO::PARAM_STR);
						$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
	
						$valor = $stmt->execute();
						if($valor==false){
							$mensaje=array('Error' => "Error: Se encontró un error al actualizar el valor ".$value->NOMBREPARA.": ".$value->VAL);
							echo json_encode($mensaje);
							break;
						}
					}
					
				}
			}

			$mensaje=array('success' => " Proyecto '$NOMBREPROY' actualizado correctamente");
		}
		else{
			$mensaje=array('Error' => 'Error: Se encontró un error al actualizar el proyecto: Nombre: $NOMBREPROY, Código: $CODPROYECTO, ID:IDPROYECTO, Estado: $ESTADOPROY');
		}
	}
	else{
		$mensaje=array('Error' => 'Error: No se actualizó el proyecto, ingreso incorrecto: Nombre: $NOMBREPROY, Código: $CODPROYECTO, ID:IDPROYECTO, Estado: $ESTADOPROY');
	}
	
	echo json_encode($mensaje);
?>