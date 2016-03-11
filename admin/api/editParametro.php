<?php
	//require_once('../../api/config/mysql.php');
	require_once('../../api/config/oracle.php');
	

	$db  = new dbConnect();
	$dbh = $db->conectardb();


 	$rspta = json_decode(file_get_contents("php://input"));
	$IDPARAMETRO= $rspta->pa->IDPARAMETRO;
	$NOMBREPARAM= $rspta->pa->NOMBREPARAM;
	$IDTIPODATO= $rspta->pa->IDTIPODATO;
	$USAMAESTROPARAM= $rspta->pa->USAMAESTROPARAM;
	$ESTADOPARAM= $rspta->pa->ESTADOPMPARAMETRO;
	$IDMODULO= $rspta->pa->IDMODULO;
	$IDPROYMACRO=$rspta->pa->IDPROYMACRO;
	$ORDEN= $rspta->pa->ORDEN;
	
	//var_dump($etiqueta);

	$q = 'UPDATE proyred.parametro
			SET NOMBREPARAM=:NOMBREPARAM, IDTIPODATO=:IDTIPODATO, USAMAESTROPARAM=:USAMAESTROPARAM, ESTADOPARAM=:ESTADOPARAM
			WHERE IDPARAMETRO=:IDPARAMETRO';
		
		$stmt = $dbh->prepare($q);
		$stmt->bindParam(':IDPARAMETRO',  $IDPARAMETRO, PDO::PARAM_STR);
		$stmt->bindParam(':NOMBREPARAM',  $NOMBREPARAM, PDO::PARAM_STR);
		$stmt->bindParam(':IDTIPODATO',  $IDTIPODATO, PDO::PARAM_STR);
		$stmt->bindParam(':USAMAESTROPARAM',  $USAMAESTROPARAM, PDO::PARAM_STR);
		$stmt->bindParam(':ESTADOPARAM',  $ESTADOPARAM, PDO::PARAM_STR);

		$valor = $stmt->execute();
		echo json_encode($valor);


	$q = 'UPDATE proyred.pmparametro
			SET ESTADOPMPARAMETRO=:ESTADOPARAM, ORDEN=:ORDEN, IDMODULO=:IDMODULO
			WHERE IDPROYMACRO=:IDPROYMACRO and IDPARAMETRO=:IDPARAMETRO';
		
		$stmt = $dbh->prepare($q);

		$stmt->bindParam(':ESTADOPARAM',  $ESTADOPARAM, PDO::PARAM_STR);
		$stmt->bindParam(':ORDEN',  $ORDEN, PDO::PARAM_STR);
		$stmt->bindParam(':IDMODULO',  $IDMODULO, PDO::PARAM_STR);
		$stmt->bindParam(':IDPROYMACRO',  $IDPROYMACRO, PDO::PARAM_STR);
		$stmt->bindParam(':IDPARAMETRO',  $IDPARAMETRO, PDO::PARAM_STR);

		$valor = $stmt->execute();
		echo json_encode($valor);

?>