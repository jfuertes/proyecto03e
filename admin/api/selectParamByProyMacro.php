<?php
	//require_once('../../api/config/mysql.php');
	require_once('../../api/config/oracle.php');

	$db  = new dbConnect();
	$dbh = $db->conectardb();

	$rspta = json_decode(file_get_contents("php://input"));
	$IDPROYMACRO= $rspta->pm->idProy;
	
	$q = 'SELECT pmp.IDPARAMETRO , par.NOMBREPARAM, mo.IDMODULO, mo.NOMBREMODULO, td.NOMBRETIPODATO, par.ESTADOPARAM,  pmp.ORDEN, par.USAMAESTROPARAM, pm.NOMBREPROYMACRO
		from proyred.pmparametro pmp
		INNER JOIN proyred.parametro par on pmp.IDPARAMETRO = par.IDPARAMETRO
		INNER JOIN proyred.modulo mo on mo.IDMODULO = pmp.IDMODULO
		INNER JOIN proyred.tipodato td on td.IDTIPODATO = par.IDTIPODATO
		INNER JOIN proyred.proymacro pm on pm.IDPROYMACRO = pmp.IDPROYMACRO
			where pmp.IDPROYMACRO= :IDPROYMACRO';


	$stmt = $dbh->prepare($q);
	$stmt->bindParam(':IDPROYMACRO',  $IDPROYMACRO, PDO::PARAM_STR);
	$stmt->execute();
	$r = $stmt->fetchAll(PDO::FETCH_ASSOC);

	//var_dump($r);
	echo json_encode($r);

?>