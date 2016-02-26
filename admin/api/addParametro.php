<?php
	//require_once('../../api/config/mysql.php');
	require_once('../../api/config/oracle.php');
	

	$db  = new dbConnect();
	$dbh = $db->conectardb();


 	$rspta = json_decode(file_get_contents("php://input"));
	$NOMBREPARAM= $rspta->pa->NOMBREPARAM;
	$IDTIPODATO= $rspta->pa->IDTIPODATO;
	$USAMAESTROPARAM= $rspta->pa->USAMAESTROPARAM;
	$ESTADOPARAM= $rspta->pa->ESTADOPARAM;
	$IDMODULO= $rspta->pa->IDMODULO;
	$IDPROYMACRO=$rspta->pm;
	$ORDEN=0;
	
	//var_dump($etiqueta);


	$q = 'SELECT max(IDPARAMETRO) +1 as id_parametro from proyred.parametro';
	$stmt = $dbh->prepare($q);
	$stmt->execute();
	$r=$stmt->fetch(PDO::FETCH_ASSOC);

	$IDPARAMETRO=$r['ID_PARAMETRO'];

	$q = 'INSERT INTO proyred.parametro (IDPARAMETRO, NOMBREPARAM, IDTIPODATO, USAMAESTROPARAM, ESTADOPARAM) 
				values (:IDPARAMETRO, :NOMBREPARAM, :IDTIPODATO, :USAMAESTROPARAM, :ESTADOPARAM)';
		
		$stmt = $dbh->prepare($q);
		$stmt->bindParam(':IDPARAMETRO',  $IDPARAMETRO, PDO::PARAM_STR);
		$stmt->bindParam(':NOMBREPARAM',  $NOMBREPARAM, PDO::PARAM_STR);
		$stmt->bindParam(':IDTIPODATO',  $IDTIPODATO, PDO::PARAM_STR);
		$stmt->bindParam(':USAMAESTROPARAM',  $USAMAESTROPARAM, PDO::PARAM_STR);
		$stmt->bindParam(':ESTADOPARAM',  $ESTADOPARAM, PDO::PARAM_STR);

		$valor = $stmt->execute();
		echo json_encode($valor);

	//$IDPARAMETRO=$dbh->lastInsertId();


	$q = 'INSERT INTO proyred.pmparametro (IDPROYMACRO, IDPARAMETRO, ESTADOPMPARAMETRO, ORDEN, IDMODULO) 
				values (:IDPROYMACRO, :IDPARAMETRO, :ESTADOPMPARAMETRO, :ORDEN, :IDMODULO )';
		
		$stmt = $dbh->prepare($q);
		$stmt->bindParam(':IDPROYMACRO',  $IDPROYMACRO, PDO::PARAM_STR);
		$stmt->bindParam(':IDPARAMETRO',  $IDPARAMETRO, PDO::PARAM_STR);
		$stmt->bindParam(':ESTADOPMPARAMETRO',  $ESTADOPARAM, PDO::PARAM_STR);
		$stmt->bindParam(':ORDEN',  $ORDEN, PDO::PARAM_STR);
		$stmt->bindParam(':IDMODULO',  $IDMODULO, PDO::PARAM_STR);

		$valor = $stmt->execute();
		echo json_encode($valor);

?>