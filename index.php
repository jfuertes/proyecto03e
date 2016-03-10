<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1">        
        <title>Sistema de Gesti&oacute;n de Proyectos</title>
        <script src="js/angular.min.js"></script>
        <script src="js/app.js"></script>
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
        </style>
    </head>
    <body ng-app="main" ng-controller="ControllerLogin as ctrlLogin">
        <div class="container">
            <form class="login-form" ng-submit="ctrlLogin.postForm()">
                <h2>Acceso</h2>
                <label for="inputUsuario" class="sr-only">Login:</label>
                <input type="text" id="inputUsuario" class="form-control" placeholder="Login" required autofocus ng-model="ctrlLogin.inputData.login" >                
                <label for="inputClave" class="sr-only">Password:</label>
                <input type="password" id="inputClave" class="form-control" placeholder="Password" required ng-model="ctrlLogin.inputData.clave" >
                <br>
                <div class="alert alert-danger" role="alert" ng-show="errorMsg">{{errorMsg}}</div>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Acceder</button>
            </form>
        </div>
    </body>
</html>
