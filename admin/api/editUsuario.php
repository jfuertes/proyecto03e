<?php
	//require_once('../../api/config/mysql.php');
	require_once('../../api/config/oracle.php');
	

	$db  = new dbConnect();
	$dbh = $db->conectardb();


 	$rspta = json_decode(file_get_contents("php://input"));
	$IDUSUARIO= $rspta->ue->IDUSUARIO;
	$LOGINUS= $rspta->ue->LOGINUS;
	$NOMBREUS= $rspta->ue->NOMBREUS;
	$APELLIDO= $rspta->ue->APELLIDO;
	$LDAP= $rspta->ue->LDAP;
	$EMAIL= $rspta->ue->EMAIL;
	
	//var_dump($etiqueta);

	$q = 'UPDATE proyred.usuario
			SET LOGINUS=:LOGINUS, NOMBREUS=:NOMBREUS, APELLIDO=:APELLIDO, LDAP=:LDAP, EMAIL=:EMAIL
			WHERE IDUSUARIO=:IDUSUARIO';
		
		$stmt = $dbh->prepare($q);
		$stmt->bindParam(':LOGINUS',  $LOGINUS, PDO::PARAM_STR);
		$stmt->bindParam(':IDUSUARIO',  $IDUSUARIO, PDO::PARAM_STR);
		$stmt->bindParam(':NOMBREUS',  $NOMBREUS, PDO::PARAM_STR);
		$stmt->bindParam(':APELLIDO',  $APELLIDO, PDO::PARAM_STR);
		$stmt->bindParam(':LDAP',  $LDAP, PDO::PARAM_STR);
		$stmt->bindParam(':EMAIL',  $EMAIL, PDO::PARAM_STR);

		$valor = $stmt->execute();
		echo json_encode($valor);


?>