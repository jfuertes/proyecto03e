<?php
  //require_once('../../api/config/mysql.php');
	require_once('../../api/config/oracle.php');

	$cont=0;
	$db  = new dbConnect();
	$dbh = $db->conectardb();

 	$rspta = json_decode(file_get_contents("php://input"));
	$NOMBREPROY= $rspta->pro->NOMBREPROY;
	$CODPROYECTO= $rspta->pro->CODPROYECTO;
	$IDPROYECTO= $rspta->pro->IDPROYECTO;
	$ESTADOPROY= $rspta->pro->ESTADOPROY;
	//$IDVALORES={};
	$IDVALOR=$rspta->pro->valores;
	$pro=$rspta->pro;
	$par=$rspta->pa;
	//var_dump($IDVALORES);
	
//var_dump($NOMBREPROY);
//var_dump($IDPROYECTO);
//echo "============";
	$q = 'UPDATE proyred.PROYECTO 
				SET NOMBREPROY=:NOMBREPROY, CODPROYECTO=:CODPROYECTO, ESTADOPROY=:ESTADOPROY
				WHERE IDPROYECTO=:IDPROYECTO';
	$stmt = $dbh->prepare($q);
	$stmt->bindParam(':NOMBREPROY',  $NOMBREPROY, PDO::PARAM_STR);
	$stmt->bindParam(':CODPROYECTO',  $CODPROYECTO, PDO::PARAM_STR);
	$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
	$stmt->bindParam(':ESTADOPROY',  $ESTADOPROY, PDO::PARAM_STR);
	$valor = $stmt->execute();
		echo json_encode($valor);

	foreach ($IDVALOR as $key => $value) {
		//var_dump($value);
		//var_dump($pro->$key);
		///echo "valor: ".($pro->$key);
		// ", tipo de dato: ".($par[$cont]->IDTIPODATO);

		if($par[$cont]->IDTIPODATO=="1"){
			$valoractual= floatval($pro->$key);

			$q = 'UPDATE proyred.VALOR 
				SET VALORNUMBER='.$valoractual.'
				WHERE IDVALOR='.$value;
			$stmt = $dbh->prepare($q);
			//$stmt->bindParam(':VALORNUMBER',  $valoractual, PDO::PARAM_STR);
			//$stmt->bindParam(':IDVALOR',  $value, PDO::PARAM_STR);
			$valor = $stmt->execute();
			echo $value;
			echo json_encode($valor);

			//echo json_encode($valor);

		}
		else if($par[$cont]->IDTIPODATO=="2"){
			
			$valoractual= floatval($pro->$key);
			//var_dump($valoractual);
			//var_dump($value);
			$q = 'UPDATE proyred.VALOR 
				SET VALORNUMBER='.$valoractual.'
				WHERE IDVALOR='.$value;
			$stmt = $dbh->prepare($q);
			//$stmt->bindParam(':VALORNUMBER',  $valoractual, PDO::PARAM_STR);
			//$stmt->bindParam(':IDVALOR',  $value, PDO::PARAM_STR);
			$valor = $stmt->execute();
			echo $value;
			echo json_encode($valor);
		}

		else if($par[$cont]->IDTIPODATO=="3"){
			$q = 'UPDATE proyred.VALOR 
				SET VALORSTR=:VALORSTR
				WHERE IDVALOR=:IDVALOR';
			$stmt = $dbh->prepare($q);
			$stmt->bindParam(':VALORSTR',  $pro->$key, PDO::PARAM_STR);
			$stmt->bindParam(':IDVALOR',  $value, PDO::PARAM_STR);
			$valor = $stmt->execute();
			echo $value;
			echo json_encode($valor);
		}

		else{
			//formato de oracle para fecha 12/21/2015
			//$value.
			list($fecha, $hora) = split("T", $pro->$key);
			list($year, $month, $day) = split("-", $fecha);
			$valoractual= $day."-".$month."-".$year;
			//$valoractual = strtotime($valoractual);
			echo $value;
			$q = "UPDATE proyred.VALOR 
				SET VALORDATE='".$valoractual."'
				WHERE IDVALOR=".$value;
			$stmt = $dbh->prepare($q);
			//$stmt->bindParam(':VALORDATE',  $pro->$key, PDO::PARAM_STR);
			//$stmt->bindParam(':IDVALOR',  $value, PDO::PARAM_STR);
			$valor = $stmt->execute();
			echo $value;
			echo json_encode($valor);

			}
		# code...
			$cont++;

	}

?>