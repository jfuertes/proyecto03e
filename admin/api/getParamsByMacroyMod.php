<?php
  require_once('../../api/config/oracle.php');

	$db  = new dbConnect();
	$dbh = $db->conectardb();

	$rspta = json_decode(file_get_contents("php://input"));
	$IDPROYMACRO= $rspta->pm->idProy;
	$IDMODULO= $rspta->pm->idMod;
	//var_dump($IDMODULO);
	//var_dump($IDPROYMACRO);
	//var_dump($IDPROYMACRO);
	$q = 'SELECT p.idparametro,p.nombreparam ,pm.orden,pm.idproymacro,p.idtipodato,tp.nombretipodato,p.usamaestroparam
            from proyred.pmparametro pm
            inner join proyred.parametro p on pm.idparametro=p.idparametro            
            inner join proyred.tipodato tp on p.idtipodato=tp.idtipodato
            where pm.idproymacro=:IDPROYMACRO and pm.idmodulo=:IDMODULO
            order by pm.orden';

	$stmt = $dbh->prepare($q);
	$stmt->bindParam(':IDPROYMACRO',  $IDPROYMACRO, PDO::PARAM_STR);
	$stmt->bindParam(':IDMODULO',  $IDMODULO, PDO::PARAM_STR);
	$stmt->execute();
	$r = $stmt->fetchAll(PDO::FETCH_ASSOC);

	//var_dump($r);
	echo json_encode($r);
?>   

