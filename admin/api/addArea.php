<?php
	//require_once('../../api/config/mysql.php');
	require_once('../../api/config/oracle.php');

	$db  = new dbConnect();
	$dbh = $db->conectardb();


 	$rspta = json_decode(file_get_contents("php://input"));
	$nombre= $rspta->ar->NOMBREAREA;
	//var_dump($etiqueta);

	$q = 'SELECT max(IDAREA)+1 as ID_AREA from proyred.area';
	$stmt = $dbh->prepare($q);
	$stmt->execute();
	$r = $stmt->fetch(PDO::FETCH_ASSOC);

	$IDAREA = $r['ID_AREA'];

	$q = 'INSERT INTO proyred.area (IDAREA, NOMBREAREA) 
				values (:IDAREA, :NOMBREAREA )';
	
		$stmt = $dbh->prepare($q);
		$stmt->bindParam(':IDAREA',  $IDAREA, PDO::PARAM_STR);
		$stmt->bindParam(':NOMBREAREA',  $nombre, PDO::PARAM_STR);

		$valor = $stmt->execute();
		echo json_encode($valor);

?>