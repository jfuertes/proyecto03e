<?php
    session_start();
    if(isset($_SESSION['login'])){    
          header('location: usuario/index.php');
          break;
        //echo "entro";
        //echo "acceso correcto".$_SESSION['login'];
    }else{
        //echo "salio";
      
        //break;
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1">        
        <title>Sistema de Gesti&oacute;n de Proyectos</title>
        <script src="vendor/angular/1.3.15/angular.min.js"></script>
        <script src="app/app.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style type="text/css">
            .login-form{
                max-width: 400px;
                margin: 0 auto;
            }
            #inputUsuario{
                margin-bottom: -1px;
                border-bottom-right-radius: 0;
                border-bottom-left-radius: 0;
            }
            #inputClave{                
                border-bottom-right-radius: 0;
                border-bottom-left-radius: 0;
            }
            #contenedor{
                background: #005F9F;
                height: 350px
            }
            #logo{
                height: auto;
                margin-bottom: 30px;
            }
            #acceso{
                color: white;
            }
            #form{
                margin-top: 40px;
            }
            #sistema{
                color:white;
                margin-top: 120px;
            }
            #footer{
                background: #DDD;
                color: black;
            }
        </style>
    </head>
    <body ng-app="main" ng-controller="ControllerLogin as ctrlLogin">
    </br>
        <div class="col-lg-12 col-lg-offset-1" id="logo"><img src="img/entel_logo.png" width="30%"></div>
        <div class="col-lg-12" id="contenedor">
        <div class="col-lg-offset-2 col-lg-4" id="sistema" ><h3>Sistema de Seguimiento RollOut</h3></div>
        <div id="form" class="col-lg-offset-2 col-lg-4">
            <form class="login-form" ng-submit="ctrlLogin.postForm()">
                <h2 id="acceso">Acceso</h2>
                <label for="inputUsuario" class="sr-only">Login:</label>
                <input type="text" id="inputUsuario" class="form-control" placeholder="Login" required autofocus ng-model="ctrlLogin.inputData.login" >                
                <label for="inputClave" class="sr-only">Password:</label>
                <input type="password" id="inputClave" class="form-control" placeholder="Password" required ng-model="ctrlLogin.inputData.clave" >
                <br>
                <div class="alert alert-danger" role="alert" ng-show="errorMsg">{{errorMsg}}</div>
                <button class="btn btn-lg btn-default btn-block" type="submit">Acceder</button>
            </form>
        </div>
        </div>
        <div class="col-lg-12" id="footer">Gerencia de Aseguramiento de la calidad y NOC</div>
    </body>
</html>
