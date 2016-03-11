<?php
  require_once('../../api/config/oracle.php');

	$db  = new dbConnect();
	$dbh = $db->conectardb();

	$rspta = json_decode(file_get_contents("php://input"));
	$IDUSUARIO= $rspta->iduser;
	$q = 'SELECT acc.IDACCESO, pm.NOMBREPROYMACRO, mo.NOMBREMODULO, TIPOUS, PRIVILEGIO
from proyred.ACCESO acc
INNER JOIN proyred.PROYMACRO pm on acc.IDPROYMACRO= pm.IDPROYMACRO
INNER JOIN proyred.MODULO mo on acc.IDMODULO= mo.IDMODULO
			   where acc.IDUSUARIO= :IDUSUARIO';

	$stmt = $dbh->prepare($q);
	$stmt->bindParam(':IDUSUARIO',  $IDUSUARIO, PDO::PARAM_STR);
	$stmt->execute();
	$r = $stmt->fetchAll(PDO::FETCH_ASSOC);

	//var_dump($r);
	echo json_encode($r);
?>	