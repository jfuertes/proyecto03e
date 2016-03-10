<?php

	//require_once('../../api/config/mysql.php');
	require_once('../../usuario/api/config/oracle.php');
	

	$db  = new dbConnect();
	$dbh = $db->conectardb();


 	$rspta = json_decode(file_get_contents("php://input"));
	$etiqueta= $rspta->et->ETIQUETA;
	$IDPARAMETRO= $rspta->idpa;
	//var_dump($etiqueta);

	$q = 'SELECT max(IDMAESTRO) +1 as IDMAESTRO from proyred.MAESTRO';
	$stmt = $dbh->prepare($q);
	$stmt->execute();
	$r=$stmt->fetch(PDO::FETCH_ASSOC);

	$IDMAESTRO=$r['IDMAESTRO'];

	$q = 'INSERT INTO proyred.MAESTRO (IDMAESTRO, IDPARAMETRO, ETIQUETA) 
				values (:IDMAESTRO, :IDPARAMETRO, :etiqueta)';
		
		$stmt = $dbh->prepare($q);
		$stmt->bindParam(':IDMAESTRO',  $IDMAESTRO, PDO::PARAM_STR);
		$stmt->bindParam(':IDPARAMETRO',  $IDPARAMETRO, PDO::PARAM_STR);
		$stmt->bindParam(':etiqueta',  $etiqueta, PDO::PARAM_STR);

		$valor = $stmt->execute();
		echo json_encode($valor);

?>