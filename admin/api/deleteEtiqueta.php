<?php

	//require_once('../../api/config/mysql.php');
	require_once('../../api/config/oracle.php');
	

	$db  = new dbConnect();
	$dbh = $db->conectardb();


 	$rspta = json_decode(file_get_contents("php://input"));
	$IDMAESTRO= $rspta->idma;
	
	//var_dump($etiqueta);


	$q = 'DELETE from  proyred.MAESTRO 
				where  IDMAESTRO= :IDMAESTRO';
		
		$stmt = $dbh->prepare($q);
		$stmt->bindParam(':IDMAESTRO',  $IDMAESTRO, PDO::PARAM_STR);


		$valor = $stmt->execute();
		echo json_encode($valor);

?>