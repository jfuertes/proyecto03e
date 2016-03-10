<?php
	//require_once('../../api/config/mysql.php');
	require_once('../../usuario/api/config/oracle.php');

	$db  = new dbConnect();
	$dbh = $db->conectardb();

	$rspta = json_decode(file_get_contents("php://input"), true);
	$mensaje="";
	//var_dump($rspta);

	foreach ($rspta['pa'] as $v) {
		

		$NOMBREPARAM = isset($v['Nombre Parámetro'])?$v['Nombre Parámetro']:NULL;
		$IDTIPODATO = isset($v['Tipo de dato'])?$v['Tipo de dato']:NULL;
		$USAMAESTROPARAM = isset($v['Tabla maestra'])?$v['Tabla maestra']:NULL;
			if(strtolower($USAMAESTROPARAM)=="si") $USAMAESTROPARAM=1;
			elseif(strtolower($USAMAESTROPARAM)=="no") $USAMAESTROPARAM=0;
		$ESTADOPARAM = isset($v['Estado'])?$v['Estado']:NULL;
			if(strtolower($ESTADOPARAM)=="activo") $ESTADOPARAM=1;
			elseif(strtolower($ESTADOPARAM)=="inactivo") $ESTADOPARAM=0;
		$IDMODULO = isset($v['Módulo'])?$v['Módulo']:NULL;
		$IDPROYMACRO = isset($rspta['pm'])?$rspta['pm']:NULL;
		$ORDEN = isset($v['Orden'])?$v['Orden']:NULL;

/*
		var_dump($NOMBREPARAM);
		var_dump($IDTIPODATO);
		var_dump($USAMAESTROPARAM);
		var_dump($ESTADOPARAM);
		var_dump($IDMODULO);
		var_dump($IDPROYMACRO);
		var_dump($ORDEN);*/
		
		if($NOMBREPARAM!=NULL && $IDTIPODATO!=NULL && $USAMAESTROPARAM>=0 && $ESTADOPARAM>=0 && $IDMODULO!=NULL && $IDPROYMACRO!=NULL){

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
				$mensaje.=" El parámetro '$NOMBREPARAM' ya existe. ";
			}
			else{
				//Calculando el siguiente id de parámetro
				$q = 'SELECT max(IDPARAMETRO) +1 as ID_PARAMETRO from proyred.parametro';
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

				$q = 'INSERT INTO proyred.pmparametro (IDPROYMACRO, IDPARAMETRO, ESTADOPMPARAMETRO, ORDEN, IDMODULO) 
							values (:IDPROYMACRO, :IDPARAMETRO, :ESTADOPMPARAMETRO, :ORDEN, :IDMODULO )';
					
					$stmt = $dbh->prepare($q);
					$stmt->bindParam(':IDPROYMACRO',  $IDPROYMACRO, PDO::PARAM_STR);
					$stmt->bindParam(':IDPARAMETRO',  $IDPARAMETRO, PDO::PARAM_STR);
					$stmt->bindParam(':ESTADOPMPARAMETRO',  $ESTADOPARAM, PDO::PARAM_STR);
					$stmt->bindParam(':ORDEN',  $ORDEN, PDO::PARAM_STR);
					$stmt->bindParam(':IDMODULO',  $IDMODULO, PDO::PARAM_STR);

					$valor = $stmt->execute();

					$mensaje.=" Se creó el parámetro: $NOMBREPARAM. ";
			}
		}
		else{
			$mensaje.=" No se cargó el parámetro con valores: Nombre: $NOMBREPARAM, Tipo: $IDTIPODATO, Maestro: $USAMAESTROPARAM, Módulo: $IDMODULO, Proy Macro:$IDPROYMACRO. ";
		}
	}
	$rpta=array('success' => $mensaje);

	echo json_encode($rpta);
?>