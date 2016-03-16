<?php

require_once('../../api/config/oracle.php');

	$db = new dbConnect();
	$dbh = $db->conectardb();
	session_start();
	//echo $_SESSION['type'];
	//echo "ola";
	if( $_SESSION['type']=="ADMIN"){
		$q= 'SELECT DISTINCT  pm.IDPROYMACRO, pm.NOMBREPROYMACRO, pm.ESTADOPM
			 FROM proyred.PROYMACRO pm';
		
		$stmt = $dbh->prepare($q);
		//$stmt->bindParam(':IDUSUARIO', $_SESSION['IDUSUARIO'], PDO::PARAM_INT);
		$stmt->execute();
		
		$r = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		echo json_encode($r);
		
	}
	else{
		$q= 'SELECT DISTINCT acc.IDPROYMACRO, pm.NOMBREPROYMACRO, pm.ESTADOPM
			 FROM proyred.ACCESO acc
			inner join proyred.PROYMACRO pm on acc.IDPROYMACRO=pm.IDPROYMACRO
			where acc.IDUSUARIO=:IDUSUARIO and pm.ESTADOPM !=0';
		
		$stmt = $dbh->prepare($q);
		$stmt->bindParam(':IDUSUARIO', $_SESSION['IDUSUARIO'], PDO::PARAM_INT);
		$stmt->execute();
		
		$r = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		echo json_encode($r);
	}

?>