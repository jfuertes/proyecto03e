<?php
	//require_once('../../api/config/mysql.php');
	require_once('../../usuario/api/config/oracle.php');
	
	$db  = new dbConnect();
	$dbh = $db->conectardb();

 	$rspta = json_decode(file_get_contents("php://input"));
	//var_dump($rspta);
	$LOGINUS= $rspta->nu->LOGINUS;
	$NOMBREUS= $rspta->nu->NOMBREUS;
	$APELLIDO= $rspta->nu->APELLIDO;
	$LDAP= $rspta->nu->LDAP;
	$NOMBREPARAM = isset($rspta->nu->IDAREA)?$rspta->nu->IDAREA:'';
	$EMAIL= $rspta->nu->EMAIL;
	$ESTADO= 1;

	if($LDAP=='NO'){
		$CLAVE= md5($rspta->nu->CLAVE);
	}
	else{
		$CLAVE= '';
	}
	
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

		$valor = $stmt->execute();
		echo json_encode($valor);


?>