<?php

	//require_once('../../api/config/mysql.php');
	require_once('../../api/config/oracle.php');
	

	$db  = new dbConnect();
	$dbh = $db->conectardb();


 	$rspta = json_decode(file_get_contents("php://input"));
	$etiqueta= $rspta->et->etiqueta;
	//var_dump($etiqueta);


	$q = 'INSERT INTO proyred.MAESTRO (ETIQUETA) 
				values (:etiqueta)';
		
		$stmt = $dbh->prepare($q);
		$stmt->bindParam(':etiqueta',  $etiqueta, PDO::PARAM_STR);

		$valor = $stmt->execute();
		echo json_encode($valor);

?>