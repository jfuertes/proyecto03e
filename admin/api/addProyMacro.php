<?php

require_once('../../api/config/mysql.php');
	

	$db  = new dbConnect();
	$dbh = $db->conectardb();


 	$rspta = json_decode(file_get_contents("php://input"));
	$nombre= $rspta->pm->NOMBREPROYMACRO;
	$estado= 1;
	//var_dump($etiqueta);


	$q = 'INSERT INTO proymacro (`NOMBREPROYMACRO`, `ESTADOPM`) 
				values (:NOMBREPROYMACRO, :ESTADOPM )';
		
		$stmt = $dbh->prepare($q);
		$stmt->bindParam(':NOMBREPROYMACRO',  $nombre, PDO::PARAM_STR);
		$stmt->bindParam(':ESTADOPM',  $estado, PDO::PARAM_STR);

		$valor = $stmt->execute();
		echo json_encode($valor);

?>