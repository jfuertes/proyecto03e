<?php
	//require_once('../../api/config/mysql.php');
	require_once('../../api/config/oracle.php');

	

	$db  = new dbConnect();
	$dbh = $db->conectardb();

	$rspta = json_decode(file_get_contents("php://input"), true);

	var_dump($rspta);

	foreach ($rspta['va'] as $v) {
	
		$IDPROYECTO= $v['id del proyecto'];
		$CODPROY= $v['codigo del proyecto'];
		$NAMEPROY= $v['nombre del proyecto'];
		$ESTADOPROY=1;
		$nuevoproyecto=0;

		if($IDPROYECTO == null){
			$nuevoproyecto=1;

			$q = 'SELECT max(IDPROYECTO) +1 as IDPROYECTO from proyred.proyecto';
			$stmt = $dbh->prepare($q);
			$stmt->execute();
			$r=$stmt->fetch(PDO::FETCH_ASSOC);

			$IDPROYECTO=$r['IDPROYECTO'];


			$q = 'INSERT INTO proyred.PROYECTO (IDPROYECTO, NOMBREPROY, ESTADOPROY, IDPROYMACRO, CODPROYECTO)
						VALUES (:IDPROYECTO, :NOMBREPROY, :ESTADOPROY, :IDPROYMACRO, :CODPROYECTO) ';
			$stmt = $dbh->prepare($q);
			$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
			$stmt->bindParam(':NOMBREPROY',  $NAMEPROY, PDO::PARAM_STR);
			$stmt->bindParam(':ESTADOPROY',  $ESTADOPROY, PDO::PARAM_STR);
			$stmt->bindParam(':IDPROYMACRO',  $pm->idProy, PDO::PARAM_STR);
			$stmt->bindParam(':CODPROYECTO',  $CODPROY, PDO::PARAM_STR);
			$valor = $stmt->execute();
				echo json_encode($valor);
		}

		foreach ($rspta['pa'] as $pa) {


				///calculando idvalor
			if($nuevoproyecto==0){
				$q = 'SELECT IDVALOR from proyred.valor 
					where IDPARAMETRO=:IDPARAMETRO and IDPROYECTO=:IDPROYECTO';
				$stmt = $dbh->prepare($q);
				$stmt->bindParam(':IDPARAMETRO',  $pa['IDPARAMETRO'], PDO::PARAM_STR);
				$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
				$stmt->execute();
				$r=$stmt->fetch(PDO::FETCH_ASSOC);
				$IDVALOR=$r['IDVALOR'];
			}
			else{
				$q = 'SELECT max(IDVALOR) +1 as IDVALOR from proyred.valor';
				$stmt = $dbh->prepare($q);
				$stmt->execute();
				$r=$stmt->fetch(PDO::FETCH_ASSOC);

				$IDVALOR=$r['IDVALOR'];

				}
			//ya se tiene el idvalor
			if($pa['IDTIPODATO']==1 || $pa['IDTIPODATO']==2){
				$nombrevalor="VALORNUMBER";
			}
			else if($pa['IDTIPODATO']==3){
				$nombrevalor="VALORSTR";
			}
			else{
				$nombrevalor="VALORDATE";
			}

			$q= "MERGE INTO PROYRED.VALOR vl
			  USING( SELECT :IDVALOR IDVALOR, :IDPARAMETRO IDPARAMETRO, :IDPROYECTO IDPROYECTO, :VALORSTR ".$nombrevalor." FROM dual) src
			     ON( vl.IDVALOR= src.IDVALOR)
			 WHEN MATCHED THEN
			   UPDATE SET VALORSTR = src.VALORSTR 
			 WHEN NOT MATCHED THEN
			   INSERT( IDVALOR, IDPARAMETRO, IDPROYECTO, VALORSTR) 
			     VALUES(src.IDVALOR, src.IDPARAMETRO, src.IDPROYECTO, src.VALORSTR)";
			$stmt->bindParam(':IDVALOR',  $IDVALOR, PDO::PARAM_STR);
			$stmt->bindParam(':IDPARAMETRO',  $pa['IDPARAMETRO'], PDO::PARAM_STR);
			$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
			$stmt->bindParam(':VALORSTR',  $v[$pa['NOMBREPARAM']], PDO::PARAM_STR);
			$valor = $stmt->execute();
				echo json_encode($valor);

		}
			//echo json_encode($valor);

	}
?>