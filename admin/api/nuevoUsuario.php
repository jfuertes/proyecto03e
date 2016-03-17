<?php
	//require_once('../../api/config/mysql.php');
	require_once('../../api/config/oracle.php');
	
	$db  = new dbConnect();
	$dbh = $db->conectardb();

 	$rspta = json_decode(file_get_contents("php://input"));
	//var_dump($rspta);
	$LOGINUS= $rspta->nu->LOGINUS;
	$NOMBREUS= $rspta->nu->NOMBREUS;
	$APELLIDO= $rspta->nu->APELLIDO;
	$LDAP= $rspta->nu->LDAP;
	$IDAREA = isset($rspta->nu->IDAREA)?$rspta->nu->IDAREA:'';
	$EMAIL= $rspta->nu->EMAIL;
	$ESTADO= 1;

	if($LDAP=='NO'){
		$CLAVE= md5($rspta->nu->CLAVE);
	}
	else{
		$CLAVE= '';
	}
	
	//Verificando si el usuario ya existe
	$q = 'SELECT 1 as RESULTADO
			FROM proyred.USUARIO
			where LOWER(LOGINUS) = LOWER(:LOGINUS)';
	$stmt = $dbh->prepare($q);
	$stmt->bindParam(':LOGINUS',  $LOGINUS, PDO::PARAM_STR);
	$stmt->execute();
	$r=$stmt->fetch(PDO::FETCH_ASSOC);

	//var_dump($r);
	if (isset($r['RESULTADO'])) {
		$rpta=array('Error' => 'Error: El código de usuario ingresado ya existe.');
	}
	else{

		//obteniendo último id de usuario
		$q = 'SELECT max(IDUSUARIO) +1 as IDUSUARIO from proyred.USUARIO';
		$stmt = $dbh->prepare($q);
		$stmt->execute();
		$r=$stmt->fetch(PDO::FETCH_ASSOC);

		$IDUSUARIO=$r['IDUSUARIO'];
		//////////////////////////////////

		$q = 'INSERT into proyred.usuario (IDUSUARIO, LOGINUS, NOMBREUS, APELLIDO, LDAP, EMAIL, ESTADO, IDAREA, CLAVE)
				VALUES (:IDUSUARIO, :LOGINUS, :NOMBREUS, :APELLIDO, :LDAP, :EMAIL, :ESTADO, :IDAREA, :CLAVE)';
			
			$stmt = $dbh->prepare($q);
			$stmt->bindParam(':LOGINUS',  $LOGINUS, PDO::PARAM_STR);
			$stmt->bindParam(':IDUSUARIO',  $IDUSUARIO, PDO::PARAM_STR);
			$stmt->bindParam(':NOMBREUS',  $NOMBREUS, PDO::PARAM_STR);
			$stmt->bindParam(':APELLIDO',  $APELLIDO, PDO::PARAM_STR);
			$stmt->bindParam(':LDAP',  $LDAP, PDO::PARAM_INT);
			$stmt->bindParam(':IDAREA',  $IDAREA, PDO::PARAM_INT);
			$stmt->bindParam(':ESTADO',  $ESTADO, PDO::PARAM_INT);
			$stmt->bindParam(':EMAIL',  $EMAIL, PDO::PARAM_STR);
			$stmt->bindParam(':CLAVE',  $CLAVE, PDO::PARAM_STR);

			$stmt->execute();
			$rpta=array('success' => 'El usuario fue creado exitosamente');
	}
	echo json_encode($rpta);

?>