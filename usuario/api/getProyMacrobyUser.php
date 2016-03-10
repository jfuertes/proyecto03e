<?php

require_once('config/oracle.php');

	$db = new dbConnect();
	$dbh = $db->conectardb();
	session_start();
	//echo $_SESSION['IDUSUARIO'];
	//echo "ola";
	$q= 'SELECT DISTINCT acc.IDPROYMACRO, pm.NOMBREPROYMACRO, pm.ESTADOPM
		 FROM proyred.ACCESO acc
		inner join proyred.PROYMACRO pm on acc.IDPROYMACRO=pm.IDPROYMACRO
		where acc.IDUSUARIO=:IDUSUARIO';
	
	$stmt = $dbh->prepare($q);
	$stmt->bindParam(':IDUSUARIO', $_SESSION['IDUSUARIO'], PDO::PARAM_INT);
	$stmt->execute();
	
	$r = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	echo json_encode($r);


?>