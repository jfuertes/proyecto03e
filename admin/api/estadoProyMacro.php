<?php
	require_once('../../api/config/oracle.php');

	$db  = new dbConnect();
	$dbh = $db->conectardb();

	$id = $_POST['id'];
	$estado = $_POST['estado'];

	$q = 'INSERT INTO tb_info_adicional (id_nino, peso, talla, temperatura, hemoglobina, fecha_medicion, username, nutrientes, observaciones, created_at)
		VALUES (:id_nino, :peso, :talla, :temperatura, :hemoglobina, :fecha_medicion, :username, :nutrientes, :observaciones, CURRENT_TIMESTAMP)';


	$q = 'UPDATE proyred.proymacro
		SET ESTADOPM=:estado
		WHERE IDPROYMACRO=:id';

	$stmt = $dbh->prepare($q);
	$stmt->bindParam(':estado',  $estado, PDO::PARAM_STR);
	$stmt->bindParam(':id',  $id, PDO::PARAM_STR);
	$valor= $stmt->execute();

	echo json_encode($valor);
?>