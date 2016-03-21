<?php
	require_once('../../../api/config/oracle.php');

	$cont=0;
	$db  = new dbConnect();
	$dbh = $db->conectardb();

 	$rspta = json_decode(file_get_contents("php://input"));

	$NOMBREPROY = isset($rspta->pro->NOMBREPROY)?$rspta->pro->NOMBREPROY:null;
	$CODPROYECTO = isset($rspta->pro->CODPROYECTO)?$rspta->pro->CODPROYECTO:null;
	$IDPROYMACRO = isset($rspta->pm->idProy)?$rspta->pm->idProy:null;
	$VALORES = isset($rspta->pro->valores)?$rspta->pro->valores:null;
	$ESTADOPROY=isset($rspta->pro->ESTADOPROY)?$rspta->pro->ESTADOPROY:null;
	

	if($NOMBREPROY!=null && $CODPROYECTO!=null && $IDPROYMACRO!=null && $ESTADOPROY!=null){

		// Calculando el siguiente id de proyecto
		$q = 'SELECT max(IDPROYECTO) +1 as IDPROYECTO from proyred.proyecto';
		$stmt = $dbh->prepare($q);
		$stmt->execute();
		$r=$stmt->fetch(PDO::FETCH_ASSOC);

		$IDPROYECTO=$r['IDPROYECTO'];

		//Insertando en tabla proyecto
		$q = 'INSERT INTO proyred.PROYECTO (IDPROYECTO, NOMBREPROY, ESTADOPROY, IDPROYMACRO, CODPROYECTO)
					VALUES (:IDPROYECTO, :NOMBREPROY, :ESTADOPROY, :IDPROYMACRO, :CODPROYECTO) ';
		$stmt = $dbh->prepare($q);
		$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
		$stmt->bindParam(':NOMBREPROY',  $NOMBREPROY, PDO::PARAM_STR);
		$stmt->bindParam(':ESTADOPROY',  $ESTADOPROY, PDO::PARAM_STR);
		$stmt->bindParam(':IDPROYMACRO',  $IDPROYMACRO, PDO::PARAM_STR);
		$stmt->bindParam(':CODPROYECTO',  $CODPROYECTO, PDO::PARAM_STR);
		$valor = $stmt->execute();


		if($VALORES!=NULL && $valor){
			//Calculando el siguiente id de valor
			$q = 'SELECT max(IDVALOR) +1 as IDVALOR from proyred.VALOR';
			$stmt = $dbh->prepare($q);
			$stmt->execute();
			$r=$stmt->fetch(PDO::FETCH_ASSOC);

			$IDVALOR=$r['IDVALOR'];
			
			foreach ($VALORES as $key => $value) {
				$IDPARAMETRO=$value->IDPARAMETRO;
				$VALOR=isset($value->val)?$value->val:'';

				if($value->IDTIPODATO=="1"){
					$valoractual= intval($VALOR);

					$q = 'INSERT INTO proyred.VALOR (IDVALOR, IDPARAMETRO, IDPROYECTO, VALORNUMBER)
						VALUES (:IDVALOR, :IDPARAMETRO, :IDPROYECTO, :VALORNUMBER)';
					$stmt = $dbh->prepare($q);
					$stmt->bindParam(':IDVALOR',  $IDVALOR, PDO::PARAM_STR);
					$stmt->bindParam(':IDPARAMETRO',  $IDPARAMETRO, PDO::PARAM_STR);
					$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
					$stmt->bindParam(':VALORNUMBER',  $valoractual, PDO::PARAM_STR);
					$valor = $stmt->execute();
				}
				else if($value->IDTIPODATO=="2"){
					$valoractual= floatval($VALOR);

					$q = 'INSERT INTO proyred.VALOR (IDVALOR, IDPARAMETRO, IDPROYECTO, VALORNUMBER)
						VALUES (:IDVALOR, :IDPARAMETRO, :IDPROYECTO, '.$valoractual.')';
					$stmt = $dbh->prepare($q);
					$stmt->bindParam(':IDVALOR',  $IDVALOR, PDO::PARAM_STR);
					$stmt->bindParam(':IDPARAMETRO',  $IDPARAMETRO, PDO::PARAM_STR);
					$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
					//$stmt->bindParam(':VALORNUMBER',  $valoractual, PDO::PARAM_STR);
					$valor = $stmt->execute();
				}

				else if($value->IDTIPODATO=="3"){

					$q = 'INSERT INTO proyred.VALOR (IDVALOR, IDPARAMETRO, IDPROYECTO, VALORSTR)
						VALUES (:IDVALOR, :IDPARAMETRO, :IDPROYECTO, :VALORSTR)';
					$stmt = $dbh->prepare($q);
					$stmt->bindParam(':IDVALOR',  $IDVALOR, PDO::PARAM_STR);
					$stmt->bindParam(':IDPARAMETRO',  $IDPARAMETRO, PDO::PARAM_STR);
					$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
					$stmt->bindParam(':VALORSTR', $VALOR, PDO::PARAM_STR);
					$valor = $stmt->execute();
				}

				else{

					$date = date_create($VALOR);
					$valoractual=date_format($date, 'd-m-Y');

					$q = 'INSERT INTO proyred.VALOR (IDVALOR, IDPARAMETRO, IDPROYECTO, VALORDATE)
						VALUES (:IDVALOR, :IDPARAMETRO, :IDPROYECTO, :VALORDATE)';
					$stmt = $dbh->prepare($q);
					$stmt->bindParam(':IDVALOR',  $IDVALOR, PDO::PARAM_STR);
					$stmt->bindParam(':IDPARAMETRO',  $IDPARAMETRO, PDO::PARAM_STR);
					$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
					$stmt->bindParam(':VALORDATE', $valoractual, PDO::PARAM_STR);
					$valor = $stmt->execute();

				}
				$IDVALOR++;
			}
			$mensaje=array('success' => " Proyecto '$NOMBREPROY' creado exitosamente");
		}
		else{
			$mensaje=array('Error' => 'Error: Se encontró un error al crear el proyecto: Nombre: $NOMBREPROY, Código: $CODPROYECTO, Estado: $ESTADOPROY');
		}
	}
	else{
		$mensaje=array('Error' => 'Error: No se creó el proyecto, ingreso incorrecto: Nombre: $NOMBREPROY, Código: $CODPROYECTO, Estado: $ESTADOPROY');
	}

	echo json_encode($mensaje);
?>