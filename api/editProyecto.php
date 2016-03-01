<?php
  //require_once('../../api/config/mysql.php');
	require_once('../api/config/oracle.php');


	$db  = new dbConnect();
	$dbh = $db->conectardb();

 	$rspta = json_decode(file_get_contents("php://input"));
	$NOMBREPROY= $rspta->pro->NOMBREPROY;
	$CODPROYECTO= $rspta->pro->CODPROYECTO;
	$IDPROYECTO= $rspta->pro->IDPROYECTO;
	
//var_dump($NOMBREPROY);
//var_dump($CODPROYECTO);

	$q = 'UPDATE proyred.PROYECTO 
				SET NOMBREPROY=:NOMBREPROY, CODPROYECTO=:CODPROYECTO
				WHERE IDPROYECTO=:IDPROYECTO';
	$stmt = $dbh->prepare($q);
	$stmt->bindParam(':NOMBREPROY',  $NOMBREPROY, PDO::PARAM_STR);
	$stmt->bindParam(':CODPROYECTO',  $CODPROYECTO, PDO::PARAM_STR);
	$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
	$valor = $stmt->execute();
		

	echo json_encode($valor);

?>