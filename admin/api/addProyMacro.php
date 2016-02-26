<?php
	//require_once('../../api/config/mysql.php');
	require_once('../../api/config/oracle.php');	

	$db  = new dbConnect();
	$dbh = $db->conectardb();


 	$rspta = json_decode(file_get_contents("php://input"));
	$nombre= $rspta->pm->NOMBREPROYMACRO;
	$estado= 1;
	//var_dump($etiqueta);

	$q = 'SELECT max(IDPROYMACRO)+1 as ID_PROYMACRO from proyred.proymacro';
	$stmt = $dbh->prepare($q);
	$stmt->execute();
	$r = $stmt->fetch(PDO::FETCH_ASSOC);

	$IDPROYMACRO = $r['ID_PROYMACRO'];

	$q = 'INSERT INTO proyred.proymacro (IDPROYMACRO, NOMBREPROYMACRO, ESTADOPM) 
				values (:IDPROYMACRO, :NOMBREPROYMACRO, :ESTADOPM )';
	
		$stmt = $dbh->prepare($q);
		$stmt->bindParam(':IDPROYMACRO',  $IDPROYMACRO, PDO::PARAM_STR);
		$stmt->bindParam(':NOMBREPROYMACRO',  $nombre, PDO::PARAM_STR);
		$stmt->bindParam(':ESTADOPM',  $estado, PDO::PARAM_STR);

		$valor = $stmt->execute();
		echo json_encode($valor);

?>