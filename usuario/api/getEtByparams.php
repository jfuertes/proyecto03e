<?php
  require_once('../../api/config/oracle.php');

	$db  = new dbConnect();
	$dbh = $db->conectardb();

	$rspta = json_decode(file_get_contents("php://input"));
	$params= $rspta->params;
	$salida=(object)[];

	foreach ($params as $key => $value) {
		$nameparam=$value->NOMBREPARAM;
		$salida->$nameparam=[];
	}
	foreach ($params as $key => $value) {
		if($value->USAMAESTROPARAM =="1"){
				$q = 'SELECT ETIQUETA from proyred.maestro
						where IDPARAMETRO= :IDPARAMETRO order by ETIQUETA';
				$stmt = $dbh->prepare($q);
				$stmt->bindParam(':IDPARAMETRO',  $value->IDPARAMETRO, PDO::PARAM_STR);
				$stmt->execute();
				$r = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$nameparam=$value->NOMBREPARAM;
				foreach ($r as $key => $value) {
					array_push($salida->$nameparam, $value["ETIQUETA"]);
				}
		}

		else{
			$nameparam=$value->NOMBREPARAM;
		}
	}

	echo json_encode($salida);
?>