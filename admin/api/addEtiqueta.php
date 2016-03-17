<?php

	//require_once('../../api/config/mysql.php');
	require_once('../../api/config/oracle.php');
	

	$db  = new dbConnect();
	$dbh = $db->conectardb();


 	$rspta = json_decode(file_get_contents("php://input"));
	$etiqueta= $rspta->et->ETIQUETA;
	$IDPARAMETRO= $rspta->idpa;
	//var_dump($etiqueta);

	// Verificando que no exista la etiqueta
	$q = 'SELECT 1 as RESULTADO
			FROM proyred.MAESTRO
			where LOWER(ETIQUETA) = LOWER(:ETIQUETA) and IDPARAMETRO=:IDPARAMETRO';
	$stmt = $dbh->prepare($q);
	$stmt->bindParam(':ETIQUETA',  $etiqueta, PDO::PARAM_STR);
	$stmt->bindParam(':IDPARAMETRO',  $IDPARAMETRO, PDO::PARAM_STR);
	$stmt->execute();
	$r=$stmt->fetch(PDO::FETCH_ASSOC);

	
	if (isset($r['RESULTADO'])) {
		$rpta=array('Error' => 'Error: La etiqueta ingresada ya existe.');
	}
	else{

		$q = 'SELECT max(IDMAESTRO) +1 as IDMAESTRO from proyred.MAESTRO';
		$stmt = $dbh->prepare($q);
		$stmt->execute();
		$r=$stmt->fetch(PDO::FETCH_ASSOC);

		$IDMAESTRO=$r['IDMAESTRO'];

		$q = 'INSERT INTO proyred.MAESTRO (IDMAESTRO, IDPARAMETRO, ETIQUETA) 
					values (:IDMAESTRO, :IDPARAMETRO, :etiqueta)';
			
			$stmt = $dbh->prepare($q);
			$stmt->bindParam(':IDMAESTRO',  $IDMAESTRO, PDO::PARAM_STR);
			$stmt->bindParam(':IDPARAMETRO',  $IDPARAMETRO, PDO::PARAM_STR);
			$stmt->bindParam(':etiqueta',  $etiqueta, PDO::PARAM_STR);

			$stmt->execute();
			$rpta=array('success' => 'La etiqueta fue creada exitosamente');
	}
	echo json_encode($rpta);

?>