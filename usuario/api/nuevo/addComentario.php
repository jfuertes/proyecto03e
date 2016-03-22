<?php
	require_once('../../../api/config/oracle.php');

	$db  = new dbConnect();
	$dbh = $db->conectardb();
	session_start();

 	$rspta = json_decode(file_get_contents("php://input"));

	$IDPROYECTO = isset($rspta->pro->IDPROYECTO)?$rspta->pro->IDPROYECTO:null;
	$COMENT = isset($rspta->com)?$rspta->com:null;

	if($IDPROYECTO!=NULL && $COMENT!=NULL){
		// Creación nuevo comentario
		if($COMENT!=""){
			//Calculando id siguiente comentario:
			$q = 'SELECT max(IDCOMENTARIO) +1 as IDCOMENTARIO from proyred.COMENTARIO';
			$stmt = $dbh->prepare($q);
			$stmt->execute();
			$r=$stmt->fetch(PDO::FETCH_ASSOC);

			$IDCOMENTARIO=$r['IDCOMENTARIO'];
			$IDUSUARIO= $_SESSION['IDUSUARIO'];
			$LOGINUS= $_SESSION['login'];
			// Insertando nuevo comentario
			$q = 'INSERT INTO proyred.COMENTARIO (IDCOMENTARIO, IDPROYECTO, IDUSUARIO, FECHACREACION, COMENT)
					VALUES(:IDCOMENTARIO, :IDPROYECTO, :IDUSUARIO, CURRENT_TIMESTAMP, :COMENT)';
			$stmt = $dbh->prepare($q);
			$stmt->bindParam(':IDCOMENTARIO',  $IDCOMENTARIO, PDO::PARAM_STR);
			$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
			$stmt->bindParam(':IDUSUARIO',  $IDUSUARIO, PDO::PARAM_STR);
			$stmt->bindParam(':COMENT',  $COMENT, PDO::PARAM_STR);
			$resultado = $stmt->execute();

			if($resultado==true){
				$mensaje=array('success' => "Comentario agregado.", 'IDCOMENTARIO' => $IDCOMENTARIO, 'LOGINUS' => $LOGINUS);
			}
		}
		else{
			$mensaje=array('Error' => 'Error: Mensaje ingresado vacío.');
		}
	}
	else{
		$mensaje=array('Error' => 'Error: Se encontró un error al agregar comentari. Por favor contacte al administrador del sistema');
	}
	
	echo json_encode($mensaje);
?>