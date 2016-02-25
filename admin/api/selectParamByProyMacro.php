<?php
  require_once('../../api/config/mysql.php');


	$db  = new dbConnect();
	$dbh = $db->conectardb();

	$rspta = json_decode(file_get_contents("php://input"));
	$IDPROYMACRO= $rspta->pm->idProy;
	$q = 'SELECT pmp.IDPARAMETRO, par.IDTIPODATO, par.NOMBREPARAM, par.USAMAESTROPARAM, par.ESTADOPARAM
			from pmparametro pmp
			INNER JOIN parametro par on pmp.IDPARAMETRO = par.IDPARAMETRO
			where pmp.IDPROYMACRO= :IDPROYMACRO;';

	$stmt = $dbh->prepare($q);
	$stmt->bindParam(':IDPROYMACRO',  $IDPROYMACRO, PDO::PARAM_STR);
	$stmt->execute();
	$r = $stmt->fetchAll(PDO::FETCH_ASSOC);

	//var_dump($r);
	echo json_encode($r);

?>