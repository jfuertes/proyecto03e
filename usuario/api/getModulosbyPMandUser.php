<?php

require_once('config/oracle.php');

	$db = new dbConnect();
	$dbh = $db->conectardb();
	session_start();
	//echo $_SESSION['IDUSUARIO'];
	//echo "ola";
	$rspta = json_decode(file_get_contents("php://input"));
		$ProyMacro= $rspta->ProyMacro;
	$q= 'SELECT DISTINCT acc.IDMODULO, mo.NOMBREMODULO
		 FROM proyred.ACCESO acc
		inner join proyred.MODULO mo on acc.IDMODULO=mo.IDMODULO
		where acc.IDUSUARIO=:IDUSUARIO and acc.IDPROYMACRO=:IDPROYMACRO';
	
	$stmt = $dbh->prepare($q);
	$stmt->bindParam(':IDUSUARIO', $_SESSION['IDUSUARIO'], PDO::PARAM_INT);
	$stmt->bindParam(':IDPROYMACRO', $ProyMacro, PDO::PARAM_INT);
	$stmt->execute();
	
	$r = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	echo json_encode($r);


?>