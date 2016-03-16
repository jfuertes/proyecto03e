<?php
	//require_once('../../api/config/mysql.php');
	require_once('../../api/config/oracle.php');

	
	$db  = new dbConnect();
	$dbh = $db->conectardb();

	$rspta = json_decode(file_get_contents("php://input"), true);

	//var_dump($rspta);

	foreach ($rspta['va'] as $v) {
		echo "##############cadafila\n";
	
		$IDPROYECTO= $v['id del proyecto'];
		//echo $IDPROYECTO;
		$CODPROY= $v['codigo del proyecto'];
		$NAMEPROY= $v['Nombre del proyecto'];
		$ESTADOPROY=1;
		$nuevoproyecto=0;
		if($CODPROY == null && $NAMEPROY == null){
			echo "vacio";

		}
		else{

			
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

				$stmt->bindParam(':IDPROYMACRO',  $rspta['pm']['idProy'], PDO::PARAM_STR);
				$stmt->bindParam(':CODPROYECTO',  $CODPROY, PDO::PARAM_STR);
				$valor = $stmt->execute();
				echo json_encode($valor);

			}

			foreach ($rspta['pa'] as $pa) {
				echo "nuevo parametro";


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
					echo "===========";
					echo $pa['IDPARAMETRO'];
					echo  $IDPROYECTO;
					echo "===========";
				}
				//var_dump($IDVALOR);
				//var_dump($nuevoproyecto);
				if($IDVALOR==NULL || $nuevoproyecto==1){
					$q = 'SELECT max(IDVALOR) +1 as IDVALOR from proyred.valor';
					$stmt = $dbh->prepare($q);
					$stmt->execute();
					$r=$stmt->fetch(PDO::FETCH_ASSOC);

					$IDVALOR=$r['IDVALOR'];

					//echo "||||nuevo valor|||||";
				}
				//echo "parametro: ".$pa['IDPARAMETRO']."\n";
				//echo "proyecto:  $IDPROYECTO\n";
				//echo "IDVALOR:  $IDVALOR\n";


				//ya se tiene el idvalor
				if($pa['IDTIPODATO']==1 || $pa['IDTIPODATO']==2){
					$nombrevalor="VALORNUMBER";

					if ($v[$pa['NOMBREPARAM']]==null || $v[$pa['NOMBREPARAM']]=="NaN"){
						$valoractual=null;
					
					$q= "MERGE INTO PROYRED.VALOR vl
				  			USING( SELECT :IDVALOR IDVALOR, :IDPARAMETRO IDPARAMETRO, :IDPROYECTO IDPROYECTO, '".$valoractual."' $nombrevalor FROM dual) src
				     		ON( vl.IDVALOR= src.IDVALOR)
				 			WHEN MATCHED THEN

							UPDATE SET $nombrevalor = src.$nombrevalor 
							WHEN NOT MATCHED THEN
							INSERT( IDVALOR, IDPARAMETRO, IDPROYECTO, $nombrevalor) 
							VALUES(src.IDVALOR, src.IDPARAMETRO, src.IDPROYECTO, src.$nombrevalor)";
				 }
					else  if($v[$pa['NOMBREPARAM']]!=null ){
						$valoractual=$v[$pa['NOMBREPARAM']];
						$q= "MERGE INTO PROYRED.VALOR vl
								USING( SELECT :IDVALOR IDVALOR, :IDPARAMETRO IDPARAMETRO, :IDPROYECTO IDPROYECTO, ".$valoractual." $nombrevalor FROM dual) src
								ON( vl.IDVALOR= src.IDVALOR)
								WHEN MATCHED THEN

								UPDATE SET $nombrevalor = src.$nombrevalor 
								WHEN NOT MATCHED THEN
								INSERT( IDVALOR, IDPARAMETRO, IDPROYECTO, $nombrevalor) 
								VALUES(src.IDVALOR, src.IDPARAMETRO, src.IDPROYECTO, src.$nombrevalor)";
					}
					
				 //$q= 'INSERT INTO PROYRED.VALOR (IDVALOR, IDPARAMETRO, IDPROYECTO, $nombrevalor) 
				 //    VALUES(:IDVALOR, :IDPARAMETRO, :IDPROYECTO, :VALORSTR)';

				//var_dump($q);
				echo " id valor: ".$IDVALOR;
				echo "idparametro ".$pa['IDPARAMETRO'];
				echo "idproyecto:".$IDPROYECTO;
				echo "valor :  ".$valoractual;
				//var_dump($valoractual);

				

				$stmt = $dbh->prepare($q);
				$stmt->bindParam(':IDVALOR',  $IDVALOR, PDO::PARAM_STR);
				$stmt->bindParam(':IDPARAMETRO',  $pa['IDPARAMETRO'], PDO::PARAM_STR);
				$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
				//$stmt->bindParam(':VALORSTR',  $v[$pa['NOMBREPARAM']], PDO::PARAM_STR);
				$valor = $stmt->execute();

				echo json_encode($valor);

				}
				else if($pa['IDTIPODATO']==3){
					$nombrevalor="VALORSTR";
					$q= "MERGE INTO PROYRED.VALOR vl
						USING( SELECT :IDVALOR IDVALOR, :IDPARAMETRO IDPARAMETRO, :IDPROYECTO IDPROYECTO, :VALORSTR $nombrevalor FROM dual) src
						ON( vl.IDVALOR= src.IDVALOR)
						WHEN MATCHED THEN

						UPDATE SET $nombrevalor = src.$nombrevalor 
						WHEN NOT MATCHED THEN
						INSERT( IDVALOR, IDPARAMETRO, IDPROYECTO, $nombrevalor) 
						VALUES(src.IDVALOR, src.IDPARAMETRO, src.IDPROYECTO, src.$nombrevalor)";

				 //$q= 'INSERT INTO PROYRED.VALOR (IDVALOR, IDPARAMETRO, IDPROYECTO, $nombrevalor) 
				 //    VALUES(:IDVALOR, :IDPARAMETRO, :IDPROYECTO, :VALORSTR)';

				//var_dump($q);
					echo " id valor: ".$IDVALOR;
				echo "idparametro ".$pa['IDPARAMETRO'];
				echo "idproyecto:".$IDPROYECTO;
				echo "valor :  ".$v[$pa['NOMBREPARAM']];
				//var_dump($v[$pa['NOMBREPARAM']]);

				$stmt = $dbh->prepare($q);
				$stmt->bindParam(':IDVALOR',  $IDVALOR, PDO::PARAM_STR);
				$stmt->bindParam(':IDPARAMETRO',  $pa['IDPARAMETRO'], PDO::PARAM_STR);
				$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
				$stmt->bindParam(':VALORSTR',  $v[$pa['NOMBREPARAM']], PDO::PARAM_STR);
				$valor = $stmt->execute();

				echo json_encode($valor);

				}
				else{

					//manipular fecha
					if($v[$pa['NOMBREPARAM']]!=null){

						list($day, $month, $year) = split("/", $v[$pa['NOMBREPARAM']]);
						$month=$month;
					$valoractual= $day."-".$month."-".$year;
					echo "valor actual: $valoractual";
						$nombrevalor="VALORDATE";
					}
					else{
						$nombrevalor="VALORDATE";
						$valoractual=null;

					}

					
					$q= "MERGE INTO PROYRED.VALOR vl
				  			USING( SELECT :IDVALOR IDVALOR, :IDPARAMETRO IDPARAMETRO, :IDPROYECTO IDPROYECTO, '".$valoractual."' ".$nombrevalor." FROM dual) src
				    		ON( vl.IDVALOR= src.IDVALOR)
							WHEN MATCHED THEN

				   			UPDATE SET $nombrevalor = src.$nombrevalor 
				 			WHEN NOT MATCHED THEN
			   				INSERT( IDVALOR, IDPARAMETRO, IDPROYECTO, $nombrevalor) 
				     		VALUES(src.IDVALOR, src.IDPARAMETRO, src.IDPROYECTO, src.$nombrevalor)";

					 //$q= 'INSERT INTO PROYRED.VALOR (IDVALOR, IDPARAMETRO, IDPROYECTO, $nombrevalor) 
					 //    VALUES(:IDVALOR, :IDPARAMETRO, :IDPROYECTO, :VALORSTR)';

					echo " id valor: ".$IDVALOR;
					echo "idparametro ".$pa['IDPARAMETRO'];
					echo "idproyecto:".$IDPROYECTO;
					echo "valor :  ".$v[$pa['NOMBREPARAM']];
					//var_dump($v[$pa['NOMBREPARAM']]);

					$stmt = $dbh->prepare($q);
					$stmt->bindParam(':IDVALOR',  $IDVALOR, PDO::PARAM_STR);
					$stmt->bindParam(':IDPARAMETRO',  $pa['IDPARAMETRO'], PDO::PARAM_STR);
					$stmt->bindParam(':IDPROYECTO',  $IDPROYECTO, PDO::PARAM_STR);
					//$stmt->bindParam(':VALORSTR',  $v[$pa['NOMBREPARAM']], PDO::PARAM_STR);
					$valor = $stmt->execute();

					echo json_encode($valor);

				}
			}
		}	//echo json_encode($valor);

	}
?>