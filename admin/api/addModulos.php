<?php

require_once('../../api/config/oracle.php');

	$db = new dbConnect();
	$dbh = $db->conectardb();
	session_start();
	//echo $_SESSION['IDUSUARIO'];
	//echo "ola";
	$IDMODULO = $_POST['actual'];
	$ProyMacro = $_POST['idproymacro'];
	//echo $_SESSION['IDUSUARIO'];


	if($_SESSION['type']=="ADMIN"){
		$q= 'SELECT * 
			 FROM proyred.MODULO 
			where IDMODULO!=:IDMODULO';
		//($q);
		$stmt = $dbh->prepare($q);
		$stmt->bindParam(':IDMODULO', $IDMODULO, PDO::PARAM_INT);
		$stmt->execute();
	}
	else{
		$q= 'SELECT DISTINCT acc.IDMODULO, mo.NOMBREMODULO
			 FROM proyred.ACCESO acc
			inner join proyred.MODULO mo on acc.IDMODULO=mo.IDMODULO
			where acc.IDUSUARIO=:IDUSUARIO and acc.IDPROYMACRO=:IDPROYMACRO and acc.IDMODULO!=:IDMODULO';
		//($q);
		$stmt = $dbh->prepare($q);
		$stmt->bindParam(':IDUSUARIO', $_SESSION['IDUSUARIO'], PDO::PARAM_INT);
		$stmt->bindParam(':IDPROYMACRO', $ProyMacro, PDO::PARAM_INT);
		$stmt->bindParam(':IDMODULO', $IDMODULO, PDO::PARAM_INT);
		$stmt->execute();
	}
	
	
	$r = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	echo json_encode($r);


?>