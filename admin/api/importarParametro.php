<?php
	//require_once('../../api/config/mysql.php');
	require_once('../../api/config/oracle.php');

	$db  = new dbConnect();
	$dbh = $db->conectardb();

	$rspta = json_decode(file_get_contents("php://input"), true);
	$success="";
	$error="";
	//var_dump($rspta);

	foreach ($rspta['pa'] as $v) {
		

		$NOMBREPARAM = isset($v['Nombre parametro'])?$v['Nombre parametro']:NULL;
		
		$MAESTRO = isset($v['Tabla maestra'])?$v['Tabla maestra']:NULL;
			if(strtolower($MAESTRO)=="si") $USAMAESTROPARAM='1';
			elseif(strtolower($MAESTRO)=="no") $USAMAESTROPARAM='0';
			else{
				$USAMAESTROPARAM=NULL;
				$error.=" Error al crear el parámetro '$NOMBREPARAM', El valor de Maestro debe ser 'Si' o 'No'.\n";
			}
		$ESTADOPARAM = isset($v['Estado'])?$v['Estado']:NULL;
			if(strtolower($ESTADOPARAM)=="activo") $ESTADOPARAM='1';
			elseif(strtolower($ESTADOPARAM)=="inactivo") $ESTADOPARAM='0';
			else{
				$ESTADOPARAM=NULL;
				$error.=" Error al crear el parámetro '$NOMBREPARAM', Estado ingresado invalido. \n";
			}
		$IDPROYMACRO = isset($rspta['pm'])?$rspta['pm']:NULL;
		$ORDEN = isset($v['Orden'])?$v['Orden']:NULL;
			if($ORDEN>=0) $OK = 1;
			else{
				$ORDEN=NULL;
				$error.=" Error al crear el parámetro '$NOMBREPARAM', Orden ingresado invalido. \n";
			}

		//Tipo de Dato
		$NOMBRETIPODATO = isset($v['Tipo de dato'])?$v['Tipo de dato']:NULL;

			//Obteniendo el IDTIPODATO a partir del NOMBRETIPODATO
			$q = 'SELECT IDTIPODATO from proyred.TIPODATO WHERE NOMBRETIPODATO=:NOMBRETIPODATO';
			$stmt = $dbh->prepare($q);
			$stmt->bindParam(':NOMBRETIPODATO',  $NOMBRETIPODATO, PDO::PARAM_STR);
			$stmt->execute();
			$r=$stmt->fetch(PDO::FETCH_ASSOC);

			if (isset($r['IDTIPODATO'])){
					$IDTIPODATO=$r['IDTIPODATO'];
			}
			else{
				$IDTIPODATO=NULL;
				$error.=" Error al crear el parámetro '$NOMBREPARAM', Tipo de Dato ingresado invalido. \n";
			}
		

		// Modulo
		$NOMBREMODULO = isset($v['Modulo'])?$v['Modulo']:NULL;
		$NOMBREMODULO = isset($v['Módulo'])?$v['Modulo']:$NOMBREMODULO;

			//Obteniendo el IDMODULO a partir del NOMBREMODULO
			$q = 'SELECT IDMODULO from proyred.MODULO WHERE NOMBREMODULO=:NOMBREMODULO';
			$stmt = $dbh->prepare($q);
			$stmt->bindParam(':NOMBREMODULO',  $NOMBREMODULO, PDO::PARAM_STR);
			$stmt->execute();
			$r=$stmt->fetch(PDO::FETCH_ASSOC);

			if (isset($r['IDMODULO'])){
					$IDMODULO=$r['IDMODULO'];
			}
			else{
				$IDMODULO=NULL;
				$error.=" Error al crear el parámetro '$NOMBREPARAM', Módulo ingresado invalido.\n";
			}

		
		/*var_dump($NOMBREPARAM);
		var_dump($IDTIPODATO);
		var_dump($USAMAESTROPARAM);
		var_dump($ESTADOPARAM);
		var_dump($IDMODULO);
		var_dump($IDPROYMACRO);
		var_dump($ORDEN);*/



		
		if($NOMBREPARAM!=NULL && $IDTIPODATO!=NULL && $USAMAESTROPARAM!=NULL && $ESTADOPARAM!=NULL && $IDMODULO!=NULL && $IDPROYMACRO!=NULL){

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
				$error.=" El parámetro '$NOMBREPARAM' ya existe.\n";
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

					$success.=" Se creó el parámetro: $NOMBREPARAM.\n";
			}
		}
		else{
			$error.=" No se cargó el parámetro con valores: Nombre: $NOMBREPARAM, Tipo: $NOMBRETIPODATO, Maestro: $MAESTRO, Módulo: $NOMBREMODULO, Orden: $ORDEN, Proy Macro:$IDPROYMACRO.\n";
		}
	}

	$rpta=array('success' => $success, 'error' => $error);

	echo json_encode($rpta);
?>