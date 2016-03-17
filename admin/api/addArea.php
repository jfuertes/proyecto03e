<?php
	//require_once('../../api/config/mysql.php');
	require_once('../../api/config/oracle.php');

	$db  = new dbConnect();
	$dbh = $db->conectardb();


 	$rspta = json_decode(file_get_contents("php://input"));
	$nombre= $rspta->ar->NOMBREAREA;
	//var_dump($etiqueta);


	$q = 'SELECT 1 as RESULTADO
			FROM proyred.AREA
			where LOWER(NOMBREAREA) = LOWER(:NOMBREAREA)';
	$stmt = $dbh->prepare($q);
	$stmt->bindParam(':NOMBREAREA',  $nombre, PDO::PARAM_STR);
	$stmt->execute();
	$r=$stmt->fetch(PDO::FETCH_ASSOC);

	//var_dump($r);
	if (isset($r['RESULTADO'])) {
		$rpta=array('Error' => 'Error: El nombre del área ya existe.');
	}
	else{

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

			$stmt->execute();
			$rpta=array('success' => 'El área fue creada exitosamente');
	}
	echo json_encode($rpta);
?>