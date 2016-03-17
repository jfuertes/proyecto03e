<?php

require_once('../../api/config/oracle.php');

	$db = new dbConnect();
	$dbh = $db->conectardb();
	session_start();
	//echo $_SESSION['IDUSUARIO'];
	//echo "ola";
	$rspta = json_decode(file_get_contents("php://input"));
		$idProy= $rspta->pm->idProy;
		$idMod= $rspta->pm->idMod;
//echo $idProy;
//echo $idMod;
//echo $_SESSION['IDUSUARIO'];
if($_SESSION['type']=="ADMIN"){
		$r= [];
		$r['PRIVILEGIO']="RW";
		

}
else{
	$q= 'SELECT acc.PRIVILEGIO
		 FROM proyred.ACCESO acc
		
		where acc.IDUSUARIO=:IDUSUARIO and acc.IDPROYMACRO=:IDPROYMACRO and acc.IDMODULO=:IDMODULO';
	
	$stmt = $dbh->prepare($q);
	$stmt->bindParam(':IDUSUARIO', $_SESSION['IDUSUARIO'], PDO::PARAM_INT);
	$stmt->bindParam(':IDPROYMACRO', $idProy, PDO::PARAM_INT);
	$stmt->bindParam(':IDMODULO', $idMod, PDO::PARAM_INT);
	$stmt->execute();
	
	$r = $stmt->fetch(PDO::FETCH_ASSOC);
	}

	echo json_encode($r);


?>