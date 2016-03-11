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
	$ORDEN= $rspta->pa->ORDEN;
	
	//var_dump($etiqueta);

	// Buscando si el nombre del parámetro ya existe
	$q = 'SELECT 1 as RESULTADO
			FROM proyred.parametro pa
			INNER JOIN proyred.pmparametro pm on pa.IDPARAMETRO=pm.IDPARAMETRO
			where pm.IDPROYMACRO=:IDPROYMACRO and LOWER(pa.NOMBREPARAM) = LOWER(:NOMBREPARAM)';
	$stmt = $dbh->prepare($q);
	$stmt->bindParam(':IDPROYMACRO',  $IDPROYMACRO, PDO::PARAM_STR);
	$stmt->bindParam(':NOMBREPARAM',  $NOMBREPARAM, PDO::PARAM_STR);
	$stmt->execute();
	$r=$stmt->fetch(PDO::FETCH_ASSOC);

	//var_dump($r);
	if (isset($r['RESULTADO'])) {
		$rpta=array('Error' => 'Error: El nombre del parámetro ya existe.');
	}
	else{

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

		if ($valor) {
			$q = 'INSERT INTO proyred.pmparametro (IDPROYMACRO, IDPARAMETRO, ESTADOPMPARAMETRO, ORDEN, IDMODULO) 
					values (:IDPROYMACRO, :IDPARAMETRO, :ESTADOPMPARAMETRO, :ORDEN, :IDMODULO )';
			
			$stmt = $dbh->prepare($q);
			$stmt->bindParam(':IDPROYMACRO',  $IDPROYMACRO, PDO::PARAM_STR);
			$stmt->bindParam(':IDPARAMETRO',  $IDPARAMETRO, PDO::PARAM_STR);
			$stmt->bindParam(':ESTADOPMPARAMETRO',  $ESTADOPARAM, PDO::PARAM_STR);
			$stmt->bindParam(':ORDEN',  $ORDEN, PDO::PARAM_STR);
			$stmt->bindParam(':IDMODULO',  $IDMODULO, PDO::PARAM_STR);

			$valor2 = $stmt->execute();
			//echo json_encode($valor);

			if ($valor2) {
				$rpta=array('success' => 'El parámetro fue creado exitosamente');
			}
			else{
				$rpta=array('Error' => 'Error: Se encontró un error al crear el parámetro. Favor contactarse con el administrador del sistema.');
			}
		}
		else{
			$rpta=array('Error' => 'Error: Se encontró un error al crear el parámetro. Favor contactarse con el administrador del sistema.');
		}
		
	}
	echo json_encode($rpta);
?>