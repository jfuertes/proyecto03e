<?php
  require_once('../../api/config/oracle.php');

	$db  = new dbConnect();
	$dbh = $db->conectardb();

	$rspta = json_decode(file_get_contents("php://input"));
	$IDPARAMETRO= $rspta->idpa;
	$q = 'SELECT IDPARAMETRO, ETIQUETA, IDMAESTRO
			 from proyred.maestro ma where IDPARAMETRO= :IDPARAMETRO';

	$stmt = $dbh->prepare($q);
	$stmt->bindParam(':IDPARAMETRO',  $IDPARAMETRO, PDO::PARAM_STR);
	$stmt->execute();
	$r = $stmt->fetchAll(PDO::FETCH_ASSOC);

	//var_dump($r);
	echo json_encode($r);
?>