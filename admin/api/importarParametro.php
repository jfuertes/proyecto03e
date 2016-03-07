<?php
	//require_once('../../api/config/mysql.php');
	require_once('../../api/config/oracle.php');

	

	$db  = new dbConnect();
	$dbh = $db->conectardb();

	$rspta = json_decode(file_get_contents("php://input"), true);

	var_dump($rspta);

	foreach ($rspta['pa'] as $v) {
	
		$NOMBREPARAM= $v['Nombre Parámetro'];
		$IDTIPODATO= intval($v['Tipo de dato']);
		$USAMAESTROPARAM = $v['tabla maestra'];
			if($USAMAESTROPARAM=="Si") $USAMAESTROPARAM=1;
			else $USAMAESTROPARAM=0;
		$ESTADOPARAM= $v['Estado'];
			if($ESTADOPARAM=="Activo") $ESTADOPARAM=1;
			else $ESTADOPARAM=0;
		$IDMODULO= intval($v['Módulo']);
		$IDPROYMACRO=intval($rspta['pm']);
		$ORDEN=intval($v['orden']);

		/*var_dump($NOMBREPARAM);
		var_dump($IDTIPODATO);
		var_dump($USAMAESTROPARAM);
		var_dump($ESTADOPARAM);
		var_dump($IDMODULO);
		var_dump($IDPROYMACRO);
		var_dump($ORDEN);
*/

		$q = 'SELECT max(IDPARAMETRO) +1 as ID_PARAMETRO from proyred.parametro';
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
			//echo json_encode($valor);

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
			//echo json_encode($valor);

	}
?>