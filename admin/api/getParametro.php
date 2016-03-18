<?php
 	//require_once('../../api/config/mysql.php');
	require_once('../../api/config/oracle.php');


	$db  = new dbConnect();
	$dbh = $db->conectardb();

	$q = 'SELECT *
		from proyred.parametro
		where ESTADOPARAM=1 order by NOMBREPARAM';
	$stmt = $dbh->prepare($q);
	$stmt->execute();
	$r = $stmt->fetchAll(PDO::FETCH_ASSOC);

	//var_dump($r);
	echo json_encode($r);

?>