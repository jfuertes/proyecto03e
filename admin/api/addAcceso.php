<?php

	//require_once('../../api/config/mysql.php');
	require_once('../../api/config/oracle.php');
	

	$db  = new dbConnect();
	$dbh = $db->conectardb();


 	$rspta = json_decode(file_get_contents("php://input"));
	$acc= $rspta->acc;
	$IDUSER= $rspta->idpa;
	//var_dump($etiqueta);
	//verificar que acceso no este repetido
	$q = 'SELECT * from proyred.ACCESO where IDPROYMACRO= :IDPROYMACRO
			 and  IDMODULO=:IDMODULO and IDUSUARIO=:IDUSUARIO ';
		$stmt = $dbh->prepare($q);
		$stmt->bindParam(':IDPROYMACRO',  $acc->IDPROYMACRO, PDO::PARAM_STR);
		$stmt->bindParam(':IDMODULO',  $acc->IDMODULO, PDO::PARAM_STR);
		$stmt->bindParam(':IDUSUARIO',  $IDUSER, PDO::PARAM_STR);

		$stmt->execute();
		$valor = $stmt->fetchAll(PDO::FETCH_ASSOC);

		/*echo $acc->IDPROYMACRO;
		echo $acc->IDMODULO;
		echo $acc->TIPOUS;
		echo $acc->PRIVILEGIO;
		var_dump($valor);*/
		if(count($valor)!=0){
				$rpta=array('Error' => 'Error: ya se encuentra registrado ese acceso, primero eliminelo.');
		}
		else{
			$q = 'SELECT max(IDACCESO) +1 as IDACCESO from proyred.ACCESO';
				$stmt = $dbh->prepare($q);
				$stmt->execute();
				$r=$stmt->fetch(PDO::FETCH_ASSOC);

				$IDACCESO=$r['IDACCESO'];

			$q = 'INSERT INTO proyred.ACCESO (IDACCESO, IDPROYMACRO, IDMODULO, IDUSUARIO, TIPOUS, PRIVILEGIO) 
				values (:IDACCESO, :IDPROYMACRO, :IDMODULO, :IDUSUARIO, :TIPOUS, :PRIVILEGIO)';
				
				$stmt = $dbh->prepare($q);
				$stmt->bindParam(':IDACCESO',  $IDACCESO, PDO::PARAM_STR);
				$stmt->bindParam(':IDPROYMACRO',  $acc->IDPROYMACRO, PDO::PARAM_STR);
				$stmt->bindParam(':IDMODULO',  $acc->IDMODULO, PDO::PARAM_STR);
				$stmt->bindParam(':IDUSUARIO',  $IDUSER, PDO::PARAM_STR);
				$stmt->bindParam(':TIPOUS',  $acc->TIPOUS, PDO::PARAM_STR);
				$stmt->bindParam(':PRIVILEGIO',  $acc->PRIVILEGIO, PDO::PARAM_STR);

				$valor = $stmt->execute();
				if($valor=true){
					$rpta=array('success' => 'El área fue creada exitosamente');
				}
				else{
					$rpta=array('Error' => 'Error: Intentelo mas tarde la base de datos no responde.');
				}
			
		}


echo json_encode($rpta);
	

?>