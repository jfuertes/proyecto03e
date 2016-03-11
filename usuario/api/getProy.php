<?php
require_once('../../api/config/oracle.php');

	$db = new dbConnect();
	$dbh = $db->conectardb();

	$variable=1;
	$q= 'SELECT * FROM mindtec_entel_proyectos.PROYMACRO where IDPROYMACRO=:id';
	
	$stmt = $dbh->prepare($q);
	$stmt->bindParam(':id', $variable, PDO::PARAM_INT);
	$stmt->execute();
	
	$r = $stmt->fetch(PDO::FETCH_ASSOC);
	
	echo json_encode($r);


?>