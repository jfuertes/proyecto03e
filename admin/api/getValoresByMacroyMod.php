<?php
  require_once('../../usuario/api/config/oracle.php');

	$db  = new dbConnect();
	$dbh = $db->conectardb();

	$rspta = json_decode(file_get_contents("php://input"));
		$IDPROYMACRO= $rspta->pm->idProy;
	$IDMODULO= $rspta->pm->idMod;
	//var_dump($IDPROYMACRO);
	$q = "SELECT v.idvalor,p.idparametro,p.nombreparam ,pm.orden,pm.idproymacro,p.idtipodato,p.usamaestroparam, py.IDPROYECTO, py.NOMBREPROY,
            case
              when p.idtipodato=1 then to_char(v.valornumber,'999,999')
              when p.idtipodato=2 then to_char(v.valornumber,'999,999.999')
              when p.idtipodato=3 then v.valorstr
              when p.idtipodato=4 then to_char(v.valordate,'DD/MM/YYYY')
            END val
            FROM proyred.valor v
            
            inner join proyred.parametro p on v.idparametro=p.idparametro    
            inner join proyred.pmparametro pm on  p.idparametro=pm.idparametro
            inner join proyred.proyecto py on v.idproyecto=py.idproyecto
            where pm.IDPROYMACRO=:IDPROYMACRO and pm.idmodulo=:IDMODULO
            order by py.IDPROYECTO,pm.orden";

	$stmt = $dbh->prepare($q);
	$stmt->bindParam(':IDPROYMACRO',  $IDPROYMACRO, PDO::PARAM_STR);
	$stmt->bindParam(':IDMODULO',  $IDMODULO, PDO::PARAM_STR);
	$stmt->execute();
	$r = $stmt->fetchAll(PDO::FETCH_ASSOC);

	//var_dump($r);
	echo json_encode($r);
?>