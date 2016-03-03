<?php
  //require_once('../../api/config/mysql.php');
	require_once('../api/config/oracle.php');

	$cont=0;
	$db  = new dbConnect();
	$dbh = $db->conectardb();

 	$rspta = json_decode(file_get_contents("php://input"));
	$NOMBREPROY= $rspta->pro->NOMBREPROY;
	$CODPROYECTO= $rspta->pro->CODPROYECTO;
	$IDPROYECTO= $rspta->pro->IDPROYECTO;
	//$IDVALORES={};
	$IDVALOR=$rspta->pro->valores;
	$pro=$rspta->pro;
	$par=$rspta->pa;
	//var_dump($IDVALORES);
	
//var_dump($NOMBREPROY);
var_dump($IDPROYECTO);
echo "============";
	$q = 'UPDATE proyred.PROYECTO 
				SET NOMBREPROY=:NOMBREPROY, CODPROYECTO=:CODPROYECTO
				WHERE IDPROYECTO=:IDPROYECTO';
	$stmt = $dbh->prepare($q);
	$stmt->bindParam(':NOMBREPROY',  $NOMBREPROY, PDO::PARAM_STR);
	$stmt->bindParam(':CODPROYECTO',  $CODPROYECTO, PDO::PARAM_STR);
	$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
	$valor = $stmt->execute();
		echo json_encode($valor);

	foreach ($IDVALOR as $key => $value) {
		var_dump($pro->$key);
		var_dump($par[$cont]);
		var_dump($value);
		if($par[$cont]->IDTIPODATO=="1"){
			$valoractual= intval($pro->$key);
			$q = 'UPDATE proyred.VALOR 
				SET VALORNUMBER=:VALORSTR
				WHERE IDVALOR=:IDVALOR';
			$stmt = $dbh->prepare($q);
			$stmt->bindParam(':VALORSTR',  $valoractual, PDO::PARAM_STR);
			$stmt->bindParam(':IDVALOR',  $value, PDO::PARAM_STR);
			$valor = $stmt->execute();
			echo json_encode($valor);
			
		}
		else if($par[$cont]->IDTIPODATO=="2"){
			
			$valoractual= floatval($pro->$key);
			$q = 'UPDATE proyred.VALOR 
				SET VALORNUMBER=:VALORSTR
				WHERE IDVALOR=:IDVALOR';
			$stmt = $dbh->prepare($q);
			$stmt->bindParam(':VALORSTR',  $valoractual, PDO::PARAM_STR);
			$stmt->bindParam(':IDVALOR',  $value, PDO::PARAM_STR);
			$valor = $stmt->execute();
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
			echo json_encode($valor);
		}

		else{
			$q = 'UPDATE proyred.VALOR 
				SET VALORDATE=:VALORSTR
				WHERE IDVALOR=:IDVALOR';
			$stmt = $dbh->prepare($q);
			$stmt->bindParam(':VALORSTR',  $pro->$key, PDO::PARAM_STR);
			$stmt->bindParam(':IDVALOR',  $value, PDO::PARAM_STR);
			$valor = $stmt->execute();
			echo json_encode($valor);

			}
		# code...
			$cont++;
	}

?>