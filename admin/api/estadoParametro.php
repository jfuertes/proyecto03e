<?php
	require_once('../../usuario/api/config/oracle.php');

	$db  = new dbConnect();
	$dbh = $db->conectardb();

	$idParam = $_POST['idParam'];
	$idProyMacro = $_POST['idProyMAcro'];
	$estado = $_POST['estado'];

	$q = 'UPDATE proyred.PMPARAMETRO
		SET ESTADOPMPARAMETRO=:estado
		WHERE IDPROYMACRO=:idProyMacro and IDPARAMETRO=:idParam';

	$stmt = $dbh->prepare($q);
	$stmt->bindParam(':estado',  $estado, PDO::PARAM_STR);
	$stmt->bindParam(':idProyMacro',  $idProyMacro, PDO::PARAM_STR);
	$stmt->bindParam(':idParam',  $idParam, PDO::PARAM_STR);
	$valor= $stmt->execute();

	echo json_encode($valor);
?>