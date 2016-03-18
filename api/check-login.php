<?php
header('Content-Type: application/json');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//include_once 'controlador.php';
    require_once('config/oracle.php');
    include_once('ldap v2.php');

    $db  = new dbConnect();
    $dbh = $db->conectardb();

   
if(isset($_POST['login']) && isset($_POST['clave'])){  
//echo "entro al login y clave";  

        //$cx = new controlador();
        $login=$_POST['login'];
        $pass=md5($_POST['clave']);
        $csql="select * from proyred.usuario where upper(loginus)=upper('$login')";
        $stmt = $dbh->prepare($csql);
        $stmt->execute();
        $rx = $stmt->fetchAll(PDO::FETCH_ASSOC);

   // var_dump($rx);
      //  echo $rx[0]['IDAREA'];
      //  echo $pass;
    
        if(count($rx)==1){
            if($rx[0]['LDAP']=='SI'){
                //acceso a webservice 
                $responseWS=true;//respuesta del WS
                //$userData  = checkLDAP($login, $_POST['clave']); 
                //if (!array_key_exists('error', $userData)){
                if ($responseWS){
                    session_start();
                    $_SESSION['IDUSUARIO']=$rx[0]['IDUSUARIO'];
                    $_SESSION['login']=$rx[0]['LOGINUS'];
                    $_SESSION['nombreus']=$rx[0]['NOMBREUS'];
                    $_SESSION['apellidous']=$rx[0]['APELLIDO'];
                    $_SESSION['emailus']=$rx[0]['EMAIL'];
                    
                     $_SESSION['type']="usuario";
                    //                  

                   $tipo='ADMIN';
                    $q= 'SELECT IDPROYMACRO, IDMODULO from proyred.ACCESO where IDUSUARIO=:IDUSUARIO and TIPOUS=:TIPOUS';
                    
                    $stmt = $dbh->prepare($q);
                    $stmt->bindParam(':IDUSUARIO', $_SESSION['IDUSUARIO'], PDO::PARAM_INT);
                    $stmt->bindParam(':TIPOUS', $tipo, PDO::PARAM_INT);
                    $stmt->execute();
                    
                    $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    //var_dump($r);
                    if (sizeof($r)>0){
                         $_SESSION['acceso']=$r;
                         $_SESSION['type']="admin";
                    }
                    echo "{\"acceso\":\"true\",\"url\":\"usuario\"}";
                    
                }
                else{
                    echo "{\"acceso\":\"false\"}";
                }
            }else{
                if($rx[0]['CLAVE']==$pass && $rx[0]['IDAREA']!=null){
                    session_start();
                    $_SESSION['IDUSUARIO']=$rx[0]['IDUSUARIO'];
                    $_SESSION['login']=$rx[0]['LOGINUS'];
                    $_SESSION['nombreus']=$rx[0]['NOMBREUS'];
                    $_SESSION['apellidous']=$rx[0]['APELLIDO'];
                    $_SESSION['emailus']=$rx[0]['EMAIL'];
                    $_SESSION['type']="usuario";
                    //                  
                     $tipo='ADMIN';
                    $q= 'SELECT IDPROYMACRO, IDMODULO from proyred.ACCESO where IDUSUARIO=:IDUSUARIO and TIPOUS=:TIPOUS';
                    
                    $stmt = $dbh->prepare($q);
                    $stmt->bindParam(':IDUSUARIO', $_SESSION['IDUSUARIO'], PDO::PARAM_INT);
                    $stmt->bindParam(':TIPOUS', $tipo, PDO::PARAM_INT);
                    $stmt->execute();
                    
                    $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if (sizeof($r)>0){
                         $_SESSION['acceso']=$r;
                         $_SESSION['type']="admin";
                    }
                    
                    echo "{\"acceso\":\"true\",\"url\":\"usuario\"}";

                }
                else if ($rx[0]['CLAVE']==$pass && $rx[0]['IDAREA']==null){
                     session_start();
                    $_SESSION['IDUSUARIO']=$rx[0]['IDUSUARIO'];
                    $_SESSION['login']=$rx[0]['LOGINUS'];
                    $_SESSION['nombreus']=$rx[0]['NOMBREUS'];
                    $_SESSION['apellidous']=$rx[0]['APELLIDO'];
                    $_SESSION['emailus']=$rx[0]['EMAIL'];
                    $_SESSION['type']="ADMIN";
                
                    echo "{\"acceso\":\"true\",\"url\":\"admin\"}";

                }
                else{
                    echo "{\"acceso\":\"false\"}";
                }

            }
        }else{
            echo "{\"acceso\":\"false\"}";
        }            
}else{
    echo "{\"acceso\":\"false\"}";
}
?>