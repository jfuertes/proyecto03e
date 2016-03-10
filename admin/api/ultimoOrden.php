<?php
	require_once('../../usuario/api/config/oracle.php');

	$db  = new dbConnect();
	$dbh = $db->conectardb();

	$idProyMacro = $_POST['id'];

	$q = 'SELECT max(orden) + 1 as ORDEN
			FROM PROYRED.PMPARAMETRO
			WHERE IDPROYMACRO=:idProyMacro';

	$stmt = $dbh->prepare($q);
	$stmt->bindParam(':idProyMacro',  $idProyMacro, PDO::PARAM_STR);
	$stmt->execute();
	$r = $stmt->fetch(PDO::FETCH_ASSOC);

	echo json_encode($r);
?>