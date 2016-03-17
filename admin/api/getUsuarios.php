<?php
	//require_once('../../api/config/mysql.php');
	require_once('../../api/config/oracle.php');


	$db  = new dbConnect();
	$dbh = $db->conectardb();

	$q = 'SELECT us.IDUSUARIO, us.LOGINUS, us.NOMBREUS, us.APELLIDO, us.LDAP, us.EMAIL, us.ESTADO, us.IDAREA, ar.NOMBREAREA
			FROM proyred.usuario us
			LEFT JOIN proyred.AREA ar on us.IDAREA=ar.IDAREA';
	$stmt = $dbh->prepare($q);
	$stmt->execute();
	$r = $stmt->fetchAll(PDO::FETCH_ASSOC);

	//var_dump($r);
	echo json_encode($r);

?>