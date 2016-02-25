<?php

require_once('../../api/config/mysql.php');
	

	$db  = new dbConnect();
	$dbh = $db->conectardb();


 	$rspta = json_decode(file_get_contents("php://input"));
	$NOMBREPARAM= $rspta->pa->NOMBREPARAM;
	$IDTIPODATO= $rspta->pa->IDTIPODATO;
	$USAMAESTROPARAM= $rspta->pa->USAMAESTROPARAM;
	$ESTADOPARAM= $rspta->pa->ESTADOPARAM;
	
	//var_dump($etiqueta);


	$q = 'INSERT INTO parametro (`NOMBREPARAM`, `IDTIPODATO`, `USAMAESTROPARAM`, `ESTADOPARAM`) 
				values (:NOMBREPARAM, :IDTIPODATO, :USAMAESTROPARAM, :ESTADOPARAM )';
		
		$stmt = $dbh->prepare($q);
		$stmt->bindParam(':NOMBREPARAM',  $NOMBREPARAM, PDO::PARAM_STR);
		$stmt->bindParam(':IDTIPODATO',  $IDTIPODATO, PDO::PARAM_STR);
		$stmt->bindParam(':USAMAESTROPARAM',  $USAMAESTROPARAM, PDO::PARAM_STR);
		$stmt->bindParam(':ESTADOPARAM',  $ESTADOPARAM, PDO::PARAM_STR);

		$valor = $stmt->execute();
		echo json_encode($valor);

?>