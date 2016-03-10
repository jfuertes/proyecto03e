<?php

	//require_once('../../api/config/mysql.php');
	require_once('../../usuario/api/config/oracle.php');
	

	$db  = new dbConnect();
	$dbh = $db->conectardb();


 	$rspta = json_decode(file_get_contents("php://input"));
	$idacceso= $rspta->idacceso;
	
	//var_dump($etiqueta);


	$q = 'DELETE from  proyred.ACCESO 
				where  IDACCESO= :IDACCESO';
		
		$stmt = $dbh->prepare($q);
		$stmt->bindParam(':IDACCESO',  $idacceso, PDO::PARAM_STR);


		$valor = $stmt->execute();
		echo json_encode($valor);

?>