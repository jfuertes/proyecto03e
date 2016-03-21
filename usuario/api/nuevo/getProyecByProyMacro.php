<?php
  require_once('../../../api/config/oracle.php');

	$db  = new dbConnect();
	$dbh = $db->conectardb();

	session_start();

	$rspta = json_decode(file_get_contents("php://input"));

	$IDPROYMACRO = isset($rspta->pm->idProy)?$rspta->pm->idProy:null;
	$IDMODULO = isset($rspta->pm->idMod)?$rspta->pm->idMod:null;

	$Proyectos = array();

	// Seleccionando los proyectos del proyecto macro y módulo escogidos
	$q = 'SELECT IDPROYECTO, NOMBREPROY, ESTADOPROY, CODPROYECTO
			 from proyred.PROYECTO  where IDPROYMACRO= :IDPROYMACRO and ESTADOPROY=1 order by NOMBREPROY';

	$stmt = $dbh->prepare($q);
	$stmt->bindParam(':IDPROYMACRO',  $IDPROYMACRO, PDO::PARAM_STR);
	$stmt->execute();
	$r = $stmt->fetchAll(PDO::FETCH_ASSOC);

	array_push($Proyectos, $r);

	// Seleccionando los parámetros del proyecto macro y módulo escogidos
	$q = 'SELECT p.idparametro,p.nombreparam ,pm.orden,pm.idproymacro,p.idtipodato,tp.nombretipodato,p.usamaestroparam, pm.TIPOPMPARAM
            from proyred.pmparametro pm
            inner join proyred.parametro p on pm.idparametro=p.idparametro            
            inner join proyred.tipodato tp on p.idtipodato=tp.idtipodato
            where pm.idproymacro=:IDPROYMACRO and pm.idmodulo=:IDMODULO and pm.ESTADOPMPARAMETRO=1
            order by pm.orden, pm.idparametro';

	$stmt = $dbh->prepare($q);
	$stmt->bindParam(':IDPROYMACRO',  $IDPROYMACRO, PDO::PARAM_STR);
	$stmt->bindParam(':IDMODULO',  $IDMODULO, PDO::PARAM_STR);
	$stmt->execute();
	$r = $stmt->fetchAll(PDO::FETCH_ASSOC);

	array_push($Proyectos, $r);

	// Seleccionando los valores de todos los proyectos de dicho proyecto macro
	$q = "SELECT v.idvalor,p.idparametro,p.nombreparam ,pm.orden, pm.idproymacro, p.idtipodato, p.usamaestroparam, py.IDPROYECTO, py.NOMBREPROY, pm.TIPOPMPARAM,
            case
              when p.idtipodato=1 then to_char(v.valornumber,'999,999')
              when p.idtipodato=2 then to_char(v.valornumber,'999,999.999')
              when p.idtipodato=3 then v.valorstr
              when p.idtipodato=4 then to_char(v.valordate,'DD/MM/YYYY')
            END val
		FROM proyred.parametro p
		inner join proyred.pmparametro pm on  p.idparametro=pm.idparametro
		inner join proyred.proyecto py on pm.idproymacro=py.idproymacro
		left join proyred.valor v on p.idparametro=v.idparametro and py.idproyecto=v.idproyecto
		where pm.IDPROYMACRO=:IDPROYMACRO and pm.idmodulo=:IDMODULO and pm.ESTADOPMPARAMETRO=1
		order by py.IDPROYECTO,pm.orden, pm.idparametro";

	$stmt = $dbh->prepare($q);
	$stmt->bindParam(':IDPROYMACRO',  $IDPROYMACRO, PDO::PARAM_STR);
	$stmt->bindParam(':IDMODULO',  $IDMODULO, PDO::PARAM_STR);
	$stmt->execute();
	$r = $stmt->fetchAll(PDO::FETCH_ASSOC);

	array_push($Proyectos, $r);

	// Obteniendo las etiquetas de las tablas maestras de los parámetros escogidos
	$q = 'SELECT ma.ETIQUETA, ma.IDPARAMETRO
			FROM proyred.MAESTRO ma
			INNER JOIN proyred.PARAMETRO pa on ma.IDPARAMETRO=pa.IDPARAMETRO
			INNER JOIN proyred.PMPARAMETRO pm on pa.IDPARAMETRO=pm.IDPARAMETRO
			where pm.IDPROYMACRO= :IDPROYMACRO and pm.IDMODULO=:IDMODULO
			order by ma.IDPARAMETRO, ma.ETIQUETA';

		$stmt = $dbh->prepare($q);
		$stmt->bindParam(':IDPROYMACRO', $IDPROYMACRO, PDO::PARAM_INT);
		$stmt->bindParam(':IDMODULO', $IDMODULO, PDO::PARAM_INT);
		$stmt->execute();
		$r = $stmt->fetchAll(PDO::FETCH_ASSOC);

	array_push($Proyectos, $r);


	// Buscando el privilegio del usuario sobre el proyecto y módulo
	if($_SESSION['type']=="ADMIN"){
		$r= [];
		$r['PRIVILEGIO']="RW";
	}
	else{
		$q= 'SELECT acc.PRIVILEGIO
			 FROM proyred.ACCESO acc
			where acc.IDUSUARIO=:IDUSUARIO and acc.IDPROYMACRO=:IDPROYMACRO and acc.IDMODULO=:IDMODULO';
		
		$stmt = $dbh->prepare($q);
		$stmt->bindParam(':IDUSUARIO', $_SESSION['IDUSUARIO'], PDO::PARAM_INT);
		$stmt->bindParam(':IDPROYMACRO', $IDPROYMACRO, PDO::PARAM_INT);
		$stmt->bindParam(':IDMODULO', $IDMODULO, PDO::PARAM_INT);
		$stmt->execute();

		$r = $stmt->fetch(PDO::FETCH_ASSOC);
	}
	array_push($Proyectos, $r);

	//var_dump($r);
	echo json_encode($Proyectos);
?>