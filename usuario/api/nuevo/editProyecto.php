<?php
	require_once('../../../api/config/oracle.php');

	$db  = new dbConnect();
	$dbh = $db->conectardb();

 	$rspta = json_decode(file_get_contents("php://input"));

	$NOMBREPROY = isset($rspta->pro->NOMBREPROY)?$rspta->pro->NOMBREPROY:null;
	$CODPROYECTO = isset($rspta->pro->CODPROYECTO)?$rspta->pro->CODPROYECTO:null;
	$IDPROYECTO = isset($rspta->pro->IDPROYECTO)?$rspta->pro->IDPROYECTO:null;
	$ESTADOPROY = isset($rspta->pro->ESTADOPROY)?$rspta->pro->ESTADOPROY:null;
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
			foreach ($VALORES as $key => $value) {
				if($value->IDTIPODATO=="1" || $value->IDTIPODATO=="2"){
					
					if($value->IDTIPODATO=="1") $valoractual= intval($value->VAL);
					elseif($value->IDTIPODATO=="2") $valoractual= floatval($value->VAL);

					$q = 'UPDATE proyred.VALOR 
						SET VALORNUMBER='.$valoractual.'
						WHERE IDVALOR=:IDVALOR';
					$stmt = $dbh->prepare($q);
					//$stmt->bindParam(':VALORNUMBER', $valoractual, PDO::PARAM_INT);
					$stmt->bindParam(':IDVALOR',  $value->IDVALOR, PDO::PARAM_STR);
					$valor = $stmt->execute();

					if($valor==false){
						$mensaje=array('Error' => "Error: Se encontró un error al actualizar el valor ".$value->NOMBREPARA.": ".$value->VAL);
						echo json_encode($mensaje);
						break;
					}
				}
				else if($value->IDTIPODATO=="3"){
					$q = 'UPDATE proyred.VALOR 
						SET VALORSTR=:VALORSTR
						WHERE IDVALOR=:IDVALOR';
					$stmt = $dbh->prepare($q);
					$stmt->bindParam(':VALORSTR', $value->VAL, PDO::PARAM_STR);
					$stmt->bindParam(':IDVALOR',  $value->IDVALOR, PDO::PARAM_STR);
					$valor = $stmt->execute();

					if($valor==false){
						$mensaje=array('Error' => "Error: Se encontró un error al actualizar el valor ".$value->NOMBREPARA.": ".$value->VAL);
						echo json_encode($mensaje);
						break;
					}
				}
				else{
					$date = date_create($value->VAL);
					$valoractual=date_format($date, 'd-m-Y');
					//var_dump($value->VAL);
					$q = 'UPDATE proyred.VALOR 
						SET VALORDATE=:VALORDATE
						WHERE IDVALOR=:IDVALOR';
					$stmt = $dbh->prepare($q);
					$stmt->bindParam(':VALORDATE', $valoractual, PDO::PARAM_STR);
					$stmt->bindParam(':IDVALOR',  $value->IDVALOR, PDO::PARAM_STR);

					$valor = $stmt->execute();

					if($valor==false){
						$mensaje=array('Error' => "Error: Se encontró un error al actualizar el valor ".$value->NOMBREPARA.": ".$value->VAL);
						echo json_encode($mensaje);
						break;
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