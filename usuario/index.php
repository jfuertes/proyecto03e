<?php
    session_start();
    if(isset($_SESSION['login'])){    
        //echo "entro";
        //echo "acceso correcto".$_SESSION['login'];
    }else{
        //echo "salio";
        header('location:../index.php');
        break;
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Gestión de Proyectos de RED</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0">
    <link rel="stylesheet" href="../vendor/font-awesome/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="../css/style.css">

    <!-- DataTables CSS -->
    <link href="../vendor/datatables/media/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <!--<link rel="stylesheet" href="/vendor/datatables/media/css/datatables.bootstrap.min.css">-->
    <link rel="stylesheet" href="../vendor/angular-datatables-master/src/plugins/bootstrap/datatables.bootstrap.css">
    
    <link href="../css/sb-admin.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/main.css">
    <!-- bower:css -->
    <link rel="stylesheet" href="../css/import.css">
    <!-- endbuild -->
    <!-- DataTables CSS buttons -->
    <link rel="stylesheet" href="../vendor/angular-datatables-master/vendor/datatables-buttons/css/buttons.dataTables.css">

</head>
<body ng-app="entelApp">
  <main>
     

     <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#/">
                    <img alt="Brand" src="../img/entel.gif">  Gestión de Proyectos de Red
                </a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
                <li class="dropdown">
                    <a href="/#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i><?php echo " ".$_SESSION['nombreus']." ".$_SESSION['apellidous']; ?><b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php
                            if( $_SESSION['type']=="admin" || $_SESSION['type']=="ADMIN"){
                                echo '<li><a href="../admin/"><i class="fa fa-fw fa-dashboard"></i> Administracion</a></li>';
                            }
                        ?>
                        <li class="divider"></li>
                        <!--li>
                            <div ng-controller="cabecera"><a href="#/" ng-click="logout()"><i class="fa fa-fw fa-power-off"></i> Log Out</a></div>
                        </li-->
                        <li>
                            <a href="#/" ng-controller="cabecera" ng-click="logout()" ><i class="fa fa-fw fa-power-off"></i> Salir</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li>
                        <a href="#/proyectos2"><i class="fa fa-fw fa-dashboard"></i> Proyectos</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">
            <div class="ng-view"></div>
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->>
    
    </main>

    <script src="../vendor/jquery-2.1.4.min.js"></script>
    <!-- angular -->
    <!-- DataTables JavaScript -->
    <script src="../vendor/datatables/media/js/jquery.dataTables.min.js"></script>

    <script src="../vendor/angular/1.3.15/angular.min.js"></script>
    <script src="../vendor/angular-touch/angular-touch.min.js"></script>

    <script src="../vendor/angular/1.3.15/angular-animate.min.js"></script>
    <script src="../vendor/angular/1.3.15/angular-resource.min.js"></script>
    <script src="../vendor/angular/1.3.15/angular-route.min.js"></script>
    <script src="../vendor/angular/ui-select/dist/select.js"></script>
    <script src='../vendor/angular/1.3.15/i18n/angular-locale_es-pe.js'></script>

    <!-- DataTables JavaScript -->
    
    <script src="../vendor/bootstrap/bootstrap.min.js"></script>
    <script src="../vendor/angular-bootstrap/ui-bootstrap-tpls-1.2.4.min.js"></script>
    
    <script src="../vendor/angular-datatables-master/src/plugins/bootstrap/angular-datatables.bootstrap.js"></script>
    <script src="../vendor/angular-datatables-master/src/plugins/bootstrap/angular-datatables.bootstrap.options.js"></script>
    <script src="../vendor/angular-datatables-master/src/plugins/bootstrap/angular-datatables.bootstrap.tabletools.js"></script>
    <script src="../vendor/angular-datatables-master/src/plugins/bootstrap/angular-datatables.bootstrap.colvis.js"></script>
    <!-- -->
    <script src="../vendor/angular-datatables-master/dist/angular-datatables.min.js"></script>
    <script src="../vendor/datatables/media/js/dataTables.bootstrap.min.js"></script>

    <script src="../vendor/angular-datatables-master/vendor/datatables-buttons/js/dataTables.buttons.js"></script>
    <script src="../vendor/angular-datatables-master/dist/plugins/buttons/angular-datatables.buttons.min.js"></script>

    <script src="../vendor/angular-datatables-master/vendor/datatables-buttons/js/buttons.colVis.js"></script>
    <script src="../vendor/angular-datatables-master/vendor/datatables-buttons/js/buttons.print.js"></script>
    <script src="../vendor/angular-datatables-master/vendor/datatables-buttons/js/buttons.html5.js"></script>
    <script src="../vendor/angular-datatables-master/vendor/datatables-buttons/js/buttons.flash.js"></script>

    <!-- build:js scripts/vendor.js -->
    <script src="../vendor/angular-csv-import/dist/angular-csv-import.js"></script>
    <!-- endbuild -->

    <script src="app/controllers.js"></script>
    <script src="app/app.js"></script>


</body>
</html>