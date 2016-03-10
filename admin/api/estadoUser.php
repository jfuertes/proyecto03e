<?php
	require_once('../../usuario/api/config/oracle.php');

	$db  = new dbConnect();
	$dbh = $db->conectardb();

	$iduser = $_POST['iduser'];
	//$idProyMacro = $_POST['idProyMAcro'];
	$estado = $_POST['estado'];

	$q = 'UPDATE proyred.USUARIO
		SET ESTADO=:estado
		WHERE IDUSUARIO=:IDUSUARIO';

	$stmt = $dbh->prepare($q);
	$stmt->bindParam(':estado',  $estado, PDO::PARAM_STR);
	$stmt->bindParam(':IDUSUARIO',  $iduser, PDO::PARAM_STR);
	//$stmt->bindParam(':idParam',  $idParam, PDO::PARAM_STR);
	$valor= $stmt->execute();

	echo json_encode($valor);
?>