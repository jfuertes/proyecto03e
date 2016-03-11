<?php

	require_once('../../api/config/oracle.php');


	$db  = new dbConnect();
	$dbh = $db->conectardb();

	$id = $_POST['id'];
	$estado = $_POST['estado'];

	$q = 'UPDATE proyred.proymacro
		SET ESTADOPM=:estado
		WHERE IDPROYMACRO=:id';

	$stmt = $dbh->prepare($q);
	$stmt->bindParam(':estado',  $estado, PDO::PARAM_STR);
	$stmt->bindParam(':id',  $id, PDO::PARAM_STR);
	$valor= $stmt->execute();

	echo json_encode($valor);
?>