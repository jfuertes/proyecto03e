<?php
  require_once('../api/config/oracle.php');

	$db  = new dbConnect();
	$dbh = $db->conectardb();

	$rspta = json_decode(file_get_contents("php://input"));
		$params= $rspta->params;
		//var_dump($rspta);
	//var_dump($IDPROYMACRO);
		$salida=(object)[];
		foreach ($params as $key => $value) {
			$nameparam=$value->NOMBREPARAM;
			$salida->$nameparam=[];
		}
		//var_dump($salida);
		//echo "00000000000000000000000000000000";
foreach ($params as $key => $value) {

	if($value->USAMAESTROPARAM =="1"){
			$q = 'SELECT ETIQUETA from proyred.maestro
					where IDPARAMETRO= :IDPARAMETRO';
			$stmt = $dbh->prepare($q);
			$stmt->bindParam(':IDPARAMETRO',  $value->IDPARAMETRO, PDO::PARAM_STR);
			$stmt->execute();
			$r = $stmt->fetchAll(PDO::FETCH_ASSOC);
			//var_dump( $r);
			//echo "------------";
			$nameparam=$value->NOMBREPARAM;
			foreach ($r as $key => $value) {
				# code...
				array_push($salida->$nameparam, $value["ETIQUETA"]);
			}
			
	}

	else{
		array_push($salida->$nameparam, null);
	}
	# code...
}
	//var_dump($r);
//echo "======================";
echo json_encode($salida);
	//echo json_encode($r);
?>