<?php
  //require_once('../../api/config/mysql.php');
	require_once('../../api/config/oracle.php');

	$cont=0;
	$db  = new dbConnect();
	$dbh = $db->conectardb();

 	$rspta = json_decode(file_get_contents("php://input"));
	$NOMBREPROY= $rspta->pro->NOMBREPROY;
	$CODPROYECTO= $rspta->pro->CODPROYECTO;
	$IDPROYMACRO= $rspta->pm->idProy;
	
	//$IDVALORES={};
	//$IDVALOR=$rspta->pro->valores;
	$pro=$rspta->pro;
	$par=$rspta->pa;
	$ESTADOPROY=1;
	//var_dump($IDVALORES);
	
//var_dump($par);

	$q = 'SELECT max(IDPROYECTO) +1 as IDPROYECTO from proyred.proyecto';
	$stmt = $dbh->prepare($q);
	$stmt->execute();
	$r=$stmt->fetch(PDO::FETCH_ASSOC);

	$IDPROYECTO=$r['IDPROYECTO'];


	$q = 'INSERT INTO proyred.PROYECTO (IDPROYECTO, NOMBREPROY, ESTADOPROY, IDPROYMACRO, CODPROYECTO)
				VALUES (:IDPROYECTO, :NOMBREPROY, :ESTADOPROY, :IDPROYMACRO, :CODPROYECTO) ';
	$stmt = $dbh->prepare($q);
	$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
	$stmt->bindParam(':NOMBREPROY',  $NOMBREPROY, PDO::PARAM_STR);
	$stmt->bindParam(':ESTADOPROY',  $ESTADOPROY, PDO::PARAM_STR);
	$stmt->bindParam(':IDPROYMACRO',  $IDPROYMACRO, PDO::PARAM_STR);
	$stmt->bindParam(':CODPROYECTO',  $CODPROYECTO, PDO::PARAM_STR);
	$valor = $stmt->execute();
		echo json_encode($valor);

//encontrar el maximo del ID valor
	$q = 'SELECT max(IDVALOR) +1 as IDVALOR from proyred.VALOR';
	$stmt = $dbh->prepare($q);
	$stmt->execute();
	$r=$stmt->fetch(PDO::FETCH_ASSOC);

	$IDVALOR=$r['IDVALOR'];
//var_dump($IDVALOR);

	foreach ($par as $key => $value) {
		$IDPARAMETRO=$value->IDPARAMETRO;
		$nameparam=$value->NOMBREPARAM;
//echo "comienza el valor";
		//var_dump($pro->$nameparam);
		//var_dump($pro->$key);
		//var_dump($par[$cont]);
//echo "termina el valor";
		if($value->IDTIPODATO=="1"){
			$valoractual= floatval($pro->$nameparam);

			$q = 'INSERT INTO proyred.VALOR (IDVALOR, IDPARAMETRO, IDPROYECTO, VALORNUMBER)
				VALUES (:IDVALOR, :IDPARAMETRO, :IDPROYECTO, '.$valoractual.')';
			$stmt = $dbh->prepare($q);
			$stmt->bindParam(':IDVALOR',  $IDVALOR, PDO::PARAM_STR);
			$stmt->bindParam(':IDPARAMETRO',  $IDPARAMETRO, PDO::PARAM_STR);
			$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
			//$stmt->bindParam(':VALORNUMBER',  $valoractual, PDO::PARAM_STR);
			$valor = $stmt->execute();

			echo json_encode($valor);

		}
		else if($value->IDTIPODATO=="2"){
			
			$valoractual= floatval($pro->$nameparam);

			$q = 'INSERT INTO proyred.VALOR (IDVALOR, IDPARAMETRO, IDPROYECTO, VALORNUMBER)
				VALUES (:IDVALOR, :IDPARAMETRO, :IDPROYECTO, '.$valoractual.')';
			$stmt = $dbh->prepare($q);
			$stmt->bindParam(':IDVALOR',  $IDVALOR, PDO::PARAM_STR);
			$stmt->bindParam(':IDPARAMETRO',  $IDPARAMETRO, PDO::PARAM_STR);
			$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
			//$stmt->bindParam(':VALORNUMBER',  $valoractual, PDO::PARAM_STR);
			$valor = $stmt->execute();

			echo json_encode($valor);
		}

		else if($value->IDTIPODATO=="3"){

			$q = 'INSERT INTO proyred.VALOR (IDVALOR, IDPARAMETRO, IDPROYECTO, VALORSTR)
				VALUES (:IDVALOR, :IDPARAMETRO, :IDPROYECTO, :VALORSTR)';
			$stmt = $dbh->prepare($q);
			$stmt->bindParam(':IDVALOR',  $IDVALOR, PDO::PARAM_STR);
			$stmt->bindParam(':IDPARAMETRO',  $IDPARAMETRO, PDO::PARAM_STR);
			$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
			$stmt->bindParam(':VALORSTR', $pro->$nameparam, PDO::PARAM_STR);
			$valor = $stmt->execute();
			echo json_encode($valor);
		}

		else{

			list($fecha, $hora) = split("T", $pro->$nameparam);
			list($year, $month, $day) = split("-", $fecha);
			$valoractual= $day."-".$month."-".$year;
			$q = 'INSERT INTO proyred.VALOR (IDVALOR, IDPARAMETRO, IDPROYECTO, VALORDATE)
				VALUES (:IDVALOR, :IDPARAMETRO, :IDPROYECTO, '.$valoractual.')';
			$stmt = $dbh->prepare($q);
			$stmt->bindParam(':IDVALOR',  $IDVALOR, PDO::PARAM_STR);
			$stmt->bindParam(':IDPARAMETRO',  $IDPARAMETRO, PDO::PARAM_STR);
			$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
			//$stmt->bindParam(':VALORDATE', $pro->$nameparam, PDO::PARAM_STR);
			$valor = $stmt->execute();
			echo json_encode($valor);

			}
		# code...
			$IDVALOR++;
	}

?>