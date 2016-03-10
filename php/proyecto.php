<?php
    include dirname(__FILE__) . '/controlador.php';
    $cx=new controlador();
    $idproymacro = null;
    
    if(isset($_GET['opc'])){
        $opc=$_GET['opc'];
        if($opc=='new'){
            $idproymacro=$_GET['idproymacro'];
            $error=false;
            if(isset($_POST['accion'])){
                $accion=$_POST['accion'];
                if($accion=='Nuevo'){
                    $error=false;
                    $cod = $_POST['txtcodigo'];
                    $nombre = $_POST['txtnomproy'];
                    if($cod !='' && $nombre !=''){
                        $rex = $cx->buscar_proyecto_x_codigo($cod, $idproymacro);
                        $nc=count($rex);
                        if($nc==0){
                            //print "<table>";
                            $vals = array();
                            foreach($_POST as $key => $value){
                                //print "<tr><td>$key</td><td>$value</td></tr>";
                                if(strpos($key,'idpar')!== false){
                                    $vals[$key] = $value;
                                }
                            }
                            //print "</table>";
                            $rpta = $cx->registrar_proyecto($cod, $nombre, $idproymacro, $vals);
                            if($rpta['error']!=''){
                                $error = true;
                                $errmsg = $rpta['error'];
                            }
                        }else{
                            $error = true;
                            $errmsg = "El Código $cod ya existe en otro proyecto";
                        }
                    }
                }
            }
        }else if($opc=='edit'){
            $idproy = $_GET['idproy'];
            if(isset($_POST['accion'])){
                $accion=$_POST['accion'];
                if($accion=='Editar'){
                    $cod = $_POST['txtcodigo'];
                    $nombre = $_POST['txtnomproy'];
                    $vals = array();
                    foreach($_POST as $key => $value){                        
                        if(strpos($key,'idval')!== false){
                            $vals[$key] = $value;
                        }
                    }
                    $rpta = $cx->actualizar_proyecto($idproy, $cod, $nombre, $vals);
                }
            }
            $rex = $cx->buscar_proyecto_x_id($idproy);
            $nc=count($rex);
            if($nc==1){
                //echo "proyectos encontrados:" . $nc;
                $idproymacro = $rex[0]['IDPROYMACRO'];
                $cod = $rex[0]['CODPROYECTO'];
                $nombre = $rex[0]['NOMBREPROY'];
                $error=false;
            }else{
                 $error = true;
                 $errmsg = "El al obtener el proyecto con ID:$idproy";
            }
        }
    }else{
        $error=true;
    }        
    
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1">  
        <title>Sistema de Gesti&oacute;n de Proyectos</title>                
        <script src="http://10.0.17.11/Entel/usweb/js/jquery-1.11.1.js"></script>
        <script src="http://10.0.17.11/Entel/usweb/js/jquery-ui-1.10.4.min.js"></script>
        <script src="http://10.0.17.11/Entel/usweb/bootstrap/js/bootstrap.min.js" type="text/javascript" ></script>               
        <script src="http://10.0.17.11/Entel/usweb/bootstrap-datetimepicker-master/build/js/moment.js" type="text/javascript" ></script>
        <script src="http://10.0.17.11/Entel/usweb/bootstrap-datetimepicker-master/build/js/bootstrap-datetimepicker.min.js"
        <script src="http://10.0.17.11/Entel/usweb/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
        <script src="../bootstrap/js/bootbox.js" ></script>
                        
        <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">    
        <link href="http://10.0.17.11/Entel/usweb/bootstrap-datetimepicker-master/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
        <link href="http://10.0.17.11/Entel/usweb/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
        <link href="http://10.0.17.11/Entel/usweb/css/ui-lightness/jquery-ui-1.10.4.min.css" rel="stylesheet">
        
        
        <style type="text/css">
        /* Sticky footer styles
            -------------------------------------------------- */
            html,
            body {
              height: 100%;
              /* The html and body elements cannot have any padding or margin. */
            }

            /* Wrapper for page content to push down footer */
            #wrap {
              min-height: 100%;
              height: auto !important;
              height: 100%;
              /* Negative indent footer by its height */
              margin: 0 auto -60px;
              /* Pad bottom by footer height */
              padding: 0 0 60px;
            }

            /* Set the fixed height of the footer here */
            #footer {
              height: 60px;
              background-color: #f5f5f5;
            }


            /* Custom page CSS
            -------------------------------------------------- */
            /* Not required for template or sticky footer method. */

            #wrap > .container {
              padding: 60px 15px 0;
            }
            .container .credit {
              margin: 20px 0;
            }

            #footer > .container {
              padding-left: 15px;
              padding-right: 15px;
            }

            code {
              font-size: 80%;
            }
            
            .table-nonfluid {
                width: auto !important;
                font-size: 11px;
             }
    </style>
    
    <script type="text/javascript">
        
        $(document).on("click", "#cmdOK", function(e) {
            var req = false;
            $(':input[required]:visible').each(
                    function(index){
                        var input = $(this);
                        if(input.val() ==''){
                            req = true;
                        }
                    }
            );
            
            if(!req){
                bootbox.confirm("¿Esta seguro de registrar?", function(result) {
                    console.log("Confirm result: "+result);
                    if(result){
                        $("#frmProyecto").submit();
                    }
                  }); 
            }else{
                bootbox.alert("Campos faltantes!");
            }
        });
        
        $(document).on("click", "#cmdUpd", function(e) {
            var req = false;
            $(':input[required]:visible').each(
                    function(index){
                        var input = $(this);
                        if(input.val() ==''){
                            req = true;
                        }
                    }
            );
            
            if(!req){
                bootbox.confirm("¿Esta seguro de actualizar los datos del proyecto?", function(result) {
                    console.log("Confirm result: "+result);
                    if(result){
                        $("#frmProyecto").submit();
                    }
                  }); 
            }else{
                bootbox.alert("Campos faltantes!");
            }
        });
        
        
        
    </script>
           
    </head>
    <body>
        <div id="wrap">
        <div class="navbar navbar-default navbar-fixed-top">
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="#"><?php echo($opc=='new'?'Nuevo':'Editar'); ?> Proyecto</a>
            </div>
            <div class="collapse navbar-collapse">
              <ul class="nav navbar-nav">
                  <li class="active" href="javascript:void(0)" onclick="window.location='../main.html'" ><a href="#">Volver</a></li>                
              </ul>
            </div><!--/.nav-collapse -->
          </div>
        </div>
        <div class="container">
            <form id="frmProyecto" class="form-horizontal" role="form" method="post">
                <?php
                    if($error){
                ?>
                        <div class="alert alert-danger">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Error!</strong><?php echo($errmsg)?>
                        </div>
                <?php
                    }
                ?>
                <input type='hidden' name='idproymacro' value='<?php echo($idproymacro); ?>'>
                <input type='hidden' name='opc' value='<?php echo($opc); ?>'>
                <input type='hidden' name='accion' value='<?php echo($opc=='new'?'Nuevo':'Editar'); ?>'>
                <div class="form-group">
                    <label for="txtcodigo" class="control-label col-sm-3">Codigo:</label>
                    <div class="col-xs-4">
                        <input type="text" class="form-control  input-sm" name="txtcodigo" id="txtcodigo" placeholder="Codigo" required autofocus value="<?php echo(isset($cod)?$cod:"") ?>" <?php echo($opc=='edit'?"disabled":"") ?> />
                    </div>
                </div>      
                <div class="form-group">
                    <label for="txtnomproy" class="control-label col-sm-3">Nombre Proyecto:</label>
                    <div class="col-xs-4">
                        <input type="text" class="form-control  input-sm" id="txtnomproy" name="txtnomproy" required placeholder="Nombre Proyecto"  value="<?php echo(isset($nombre)?$nombre:"")?>" />
                    </div>
                </div> 
                
<?php

if($idproymacro!=null && $idproymacro!='undefined'){
    
    if($opc=='new'){
        $pars = $cx->listar_parametros($idproymacro,1);
    }else if($opc=='edit'){
        $pars = $cx->listar_parametros_valor_proyecto($idproy,1);
    }
    
    
    foreach($pars as $p){
        echo '<div class="form-group">';
        echo '<label for="idpar'.$p['IDPARAMETRO'].'" class="control-label col-sm-3">'.$p['NOMBREPARAM'].':</label>';
        echo '<div class="col-xs-4">';
        if($p['USAMAESTROPARAM']==0){
            if($p['IDTIPODATO']==1 || $p['IDTIPODATO']==2){
                if($opc=='new'){
                    echo '<input type="number" class="form-control  input-sm" id="idpar'.$p['IDPARAMETRO'].'" placeholder="'.$p['NOMBREPARAM'].'" name="idpar'.$p['IDPARAMETRO'].'" value="'.(isset($_POST['idpar'.$p['IDPARAMETRO']])?$_POST['idpar'.$p['IDPARAMETRO']]:"").'"/>';
                }else if($opc=='edit'){
                    echo '<input type="number" class="form-control  input-sm" id="idval'.$p['IDVALOR'].'" placeholder="'.$p['NOMBREPARAM'].'" name="idval'.$p['IDVALOR'].'" value="'.trim($p['VAL']).'"/>';
                }
            }elseif($p['IDTIPODATO']==4){
                echo '<div class="input-group date">';
                if($opc=='new'){
                    echo '<input type="text" class="form-control" data-date-format="DD/MM/YYYY" id="idpar'.$p['IDPARAMETRO'].'" name="idpar'.$p['IDPARAMETRO'].'" value="'.(isset($_POST['idpar'.$p['IDPARAMETRO']])?$_POST['idpar'.$p['IDPARAMETRO']]:"").'" />';
                }else if($opc=='edit'){
                    echo '<input type="text" class="form-control" data-date-format="DD/MM/YYYY" id="idval'.$p['IDVALOR'].'" name="idval'.$p['IDVALOR'].'" value="'.trim($p['VAL']).'" />';
                }
                echo '    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>';
                echo '     </span>';
                echo '</div>';
                echo '<script>';
                echo '    jQuery(document).ready(function ($) {';
                if($opc=='new'){
                    echo '    $("#idpar'.$p['IDPARAMETRO'].'").datetimepickerM({';
                }else if($opc=='edit'){
                    echo '    $("#idval'.$p['IDVALOR'].'").datetimepickerM({';
                }
                echo '                    format: "dd/mm/yyyy",';
                echo '                    pickTime: false,';
                echo '                    pick12HourFormat: false';
                echo '            });';
                echo '        });';
                echo '</script>';
            }
            else{
                if($opc=='new'){
                    echo '<input type="text" class="form-control  input-sm" id="idpar'.$p['IDPARAMETRO'].'" placeholder="'.$p['NOMBREPARAM'].'" name="idpar'.$p['IDPARAMETRO'].'" value="'.(isset($_POST['idpar'.$p['IDPARAMETRO']])?$_POST['idpar'.$p['IDPARAMETRO']]:"").'" />';
                }else if($opc=='edit'){
                    echo '<input type="text" class="form-control  input-sm" id="idval'.$p['IDVALOR'].'" placeholder="'.$p['NOMBREPARAM'].'" name="idval'.$p['IDVALOR'].'" value="'.trim($p['VAL']).'" />';
                }
            }
        }else{
            $ms = $cx->listar_maestro($p['IDPARAMETRO']);
            if($opc=='new'){
                echo '<select class="form-control input-sm" id="idpar'.$p['IDPARAMETRO'].'" name="idpar'.$p['IDPARAMETRO'].'">';
            }else if($opc=='edit'){
                echo '<select class="form-control input-sm" id="idval'.$p['IDVALOR'].'" name="idval'.$p['IDVALOR'].'">';
            }
            echo '<option value="">--Seleccione--</option>';
            foreach($ms as $m){
                if($opc=='new'){
                    if(isset($_POST['idpar'.$p['IDPARAMETRO']])){
                        if($m['ETIQUETA']==$_POST['idpar'.$p['IDPARAMETRO']]){
                            echo '<option value="'.$m['ETIQUETA'].'" selected>'.$m['ETIQUETA'].'</option>';
                        }else{
                            echo '<option value="'.$m['ETIQUETA'].'">'.$m['ETIQUETA'].'</option>';
                        }
                    }else{                    
                        echo '<option value="'.$m['ETIQUETA'].'">'.$m['ETIQUETA'].'</option>';
                    }
                }else if($opc=='edit'){
                    if($m['ETIQUETA']==$p['VAL']){
                        echo '<option value="'.$m['ETIQUETA'].'" selected>'.$m['ETIQUETA'].'</option>';
                    }else{
                        echo '<option value="'.$m['ETIQUETA'].'">'.$m['ETIQUETA'].'</option>';
                    }
                }
            }
            echo '</select>';
        }
        echo '</div>';
        echo '</div>';                
    }
    
    echo '<div class="form-group">';
    if($opc=='new'){
        echo '<a href="#" id="cmdOK" class="btn btn-primary btn-large" >Guardar</a>&nbsp;';
    }else if($opc=='edit'){
        echo '<a href="#" id="cmdUpd" class="btn btn-primary btn-large" >Actualizar</a>&nbsp;';
    }
    echo '<button id="cmdCancel" type="button" class="btn btn-default" onclick="window.location=\'../main.html\'">Cancelar</button>';
    echo '</div>';
}else{
    header("Location: ../main.html");
    die();
}

?>
                
                
            </form>
        </div>
                
    </body>
</html>