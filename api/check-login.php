<?php
header('Content-Type: application/json');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//include_once 'controlador.php';
    require_once('../api/config/oracle.php');

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
                session_start();
                $_SESSION['IDUSUARIO']=$rx[0]['IDUSUARIO'];
                $_SESSION['login']=$rx[0]['LOGINUS'];
                $_SESSION['nombreus']=$rx[0]['NOMBREUS'];
                $_SESSION['apellidous']=$rx[0]['APELLIDO'];
                $_SESSION['emailus']=$rx[0]['EMAIL'];
                echo "{\"acceso\":\"true\",\"url\":\"main.html\"}";
            }else{
                if($rx[0]['CLAVE']==$pass){
                    session_start();
                    $_SESSION['IDUSUARIO']=$rx[0]['IDUSUARIO'];
                    $_SESSION['login']=$rx[0]['LOGINUS'];
                    $_SESSION['nombreus']=$rx[0]['NOMBREUS'];
                    $_SESSION['apellidous']=$rx[0]['APELLIDO'];
                    $_SESSION['emailus']=$rx[0]['EMAIL'];
                    echo "{\"acceso\":\"true\",\"url\":\"usuario/index.php\"}";
                }else{
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