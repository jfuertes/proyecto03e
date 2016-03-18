/**
 * List Controller
 * @version v1.0.0 - 2016-02-08 * @link http://www.mindtec.pe
 * @author MindTec <contacto@mindtec.pe>
 * @license MIT License, http://www.opensource.org/licenses/MIT
 */
(function(){
  'use strict';

angular.module('Controllers', ['datatables', 'datatables.bootstrap', 'datatables.buttons', 'ui.bootstrap', 'ngAnimate'])

.run(function(DTDefaultOptions) {
    DTDefaultOptions.setLanguageSource('../vendor/datatables/Spanish.json');
})
  .filter('filtertipoDato', function(){
  return function(id){
    
    if(id== "1"){
      return "number";
    }
    else if(id== "2"){
      return "number";
    }
     else if(id== "3"){
      return "text";
    }
    else{
      return "date";
    }
    };
  })
 .filter('filternull', function(){
  return function(id){
    
    if(id==null){
      return " ";
    }
    else if(id=="NaN"){
      return " ";
    }
    else{
      return id;
    }
    };
  })

  .filter('filterfecha', function(){
    return function(id){
      if((typeof id) == 'object'){
        Date.prototype.yyyymmdd = function() {
           var yyyy = this.getFullYear().toString();
           var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
           var dd  = this.getDate().toString();
           return (dd[1]?dd:"0"+dd[0]) +"/"+ (mm[1]?mm:"0"+mm[0]) +"/"+ yyyy; // padding
          };

            return id.yyyymmdd();
      }
      else{
        return id;
      }
    };
  })
  .controller('mainController',['$scope', '$http', function ($scope, $http) {
    logout
    
    /*
      $http({method:'POST',url: 'api/getProy.php',headers : { 'Content-Type': 'application/x-www-form-urlencoded' }})
      .success(function(response) {
        //console.log(response);
      })
      .error(function(response){
        //console.log(response);
      });
*/

  }])
  .controller('cabecera', ['$scope', '$http', function($scope, $http) {
    $scope.logout = function(){
      //alert("saliendo");
    $http.post ('api/logout.php')
        .success(function(data) {
          //console.log(data);
           location.reload();
                //
            })
        .error(function(data) {
                //console.log('Error: ' + data);
        });
};

}])
  .controller('nuevoController',['$scope', function ($scope) {
    

  }])
  .controller('proyectosController',['$scope', 'DTOptionsBuilder', 'DTColumnDefBuilder', 'DTDefaultOptions', '$http', function ($scope, DTOptionsBuilder, DTColumnDefBuilder, DTDefaultOptions, $http) {
    $scope.ShowTableParams=true;
    $scope.ShowTablecomplete=false;
    $scope.NuevoProyecto=false;

      $scope.dtOptions = DTOptionsBuilder.newOptions()
        .withPaginationType('full_numbers')
        .withDisplayLength(10)
        .withBootstrap()
        .withButtons([
            'colvis',
            'copy',
            'csv'
        ]);
      $scope.dtColumnDefs = [
        DTColumnDefBuilder.newColumnDef(0),
        DTColumnDefBuilder.newColumnDef(1),
        DTColumnDefBuilder.newColumnDef(2).notSortable()
    ];

    $scope.alerts = [];

    $scope.newAlert = function(mensaje, tipo, tiempo) {
        $scope.alerts.push({msg: mensaje, type: tipo, tiempo: tiempo});

        /*$('html,body').animate({
            scrollTop: $("#alerta").offset().top
        }, 500);*/
    };

    $scope.closeAlert = function(index) {
      $scope.alerts.splice(index, 1);
    };

       $scope.getProyMacro= function(){
            $http.post('../admin/api/getProyMacro.php' )
                .success(function(data) {
                  //console.log(data);
                  $scope.ProyMacro=data;
                })
                .error(function(data) {
                  //console.log('Error: ' + data);
                  });
      };

        $scope.getProyMacrobyUser= function(){
            $http.post('api/getProyMacrobyUser.php' )
                .success(function(data) {
                  //console.log(data);
                  $scope.ProyMacrobyUser=data;
                })
                .error(function(data) {
                  //console.log('Error: ' + data);
                  });
      };

      
       $scope.getModulos= function(){
            $http.post('../admin/api/getModulos.php' )
                .success(function(data) {
                  //console.log(data);
                  $scope.Modulos=data;
                })
                .error(function(data) {
                  //console.log('Error: ' + data);
                  });
      };
       $scope.getModulosbyPMandUser= function(ProyMacro){
            $http.post('api/getModulosbyPMandUser.php',{ProyMacro:ProyMacro} )
                .success(function(data) {
                  //console.log(data);
                  $scope.Modulos=data;
                })
                .error(function(data) {
                  //console.log('Error: ' + data);
                  });
      };

      $scope.getProyMacro();
      $scope.getProyMacrobyUser();
      $scope.getModulos();
      $scope.getModulosbyproymacro=function(){
       // alert($scope.pm.idProy);
         if($scope.pm.hasOwnProperty('idProy')){
          $scope.getModulosbyPMandUser($scope.pm.idProy);
        }
      }
      $scope.getProyecByProyMacro=function(pm){
        if(pm && pm.idProy && pm.idMod){
            $scope.pmgetProyecByProyMacro=pm;
            //console.log(pm);
            //console.log( 'inicio');
            $http.post('../admin/api/getProyecByProyMacro.php',{pm:pm} )
                .success(function(data) {
                  $scope.ShowTablecomplete=true;
                  //console.log(data);
                  $scope.Proyectos=data;
                  $scope.ProyectosArray=[];
                  $scope.valores=[];

                  $.each(data,function(index,value){
                    $scope.ProyectosArray[index]=value.NOMBREPROY;
                    $scope.Proyectos[index].param={};
                    $scope.valores[index]={};
                  });
                  //console.log( $scope.ProyectosArray);
                })
                .error(function(data) {
                  //console.log('Error: ' + data);
                });

            $http.post('../admin/api/getParamsByMacroyMod.php',{pm:pm} )
                .success(function(data) {
                    $scope.ShowTablecomplete=true;
                  //console.log('param');
                  //console.log(data);
                  $scope.Params=data;
                      $http.post('api/getEtByparams.php',{params: $scope.Params} )
                     
                        .success(function(data) {
                            //$scope.ShowTablecomplete=true;
                          //console.log(data);
                          $scope.Etiquetas=data;
                        })
                        .error(function(data) {
                          //console.log('Error: ' + data);
                          });

                })
                .error(function(data) {
                  //console.log('Error: ' + data);
                  });

              $http.post('../admin/api/getValoresByMacroyMod.php',{pm:pm} )
                    .success(function(data) {
                        $scope.ShowTablecomplete=true;
                     //console.log(data);
                    //  //console.log(data[0]);
                     // $scope.Valores=data;
                     //console.log($scope.Params);
                      $.each(data,function(index,value){
                       /// //console.log(value.NOMBREPROY);
                       // //console.log(value.NOMBREPARAM);
                         //   //console.log(value.VAL);
                        ////console.log(value.IDPARAMETRO);
                        //  //console.log(jQuery.inArray( value.NOMBREPROY, $scope.ProyectosArray ));
                            var contador = jQuery.inArray( value.NOMBREPROY, $scope.ProyectosArray );
                            // contador de parametro usando el value.IDPARAMETRO  
                            var contPara=0;
                            var paracont;
                            $.each($scope.Params, function(indexp, valuep){
                              ////console.log(parseInt(valuep.IDPARAMETRO) == value.IDPARAMETRO);
                              if(parseInt(valuep.IDPARAMETRO) == value.IDPARAMETRO){
                                paracont=contPara;
                              }
                              contPara++;

                            });
                            //console.log(paracont);
                            ////console.log($scope.Params[paracont].IDTIPODATO);
                            //var nameparam=value.NOMBREPARAM;
                           if($scope.Params[paracont].IDTIPODATO==1){
                              if (parseInt(value.VAL)!="NaN"){
                                $scope.Proyectos[contador].param[value.NOMBREPARAM]=parseInt(value.VAL);
                              }
                              else{
                                $scope.Proyectos[contador].param[value.NOMBREPARAM]=null;
                              }
                              
                             // //console.log(parseInt(value.VAL));
                           }
                           else if($scope.Params[paracont].IDTIPODATO==2){
                                if (parseFloat(value.VAL)!="NaN"){
                                $scope.Proyectos[contador].param[value.NOMBREPARAM]=parseFloat(value.VAL);
                              }
                              else{
                                $scope.Proyectos[contador].param[value.NOMBREPARAM]=null;
                              }
                                
                             // //console.log( $scope.Proyectos[paracont].param[value.NOMBREPARAM]);
                              ////console.log(value.VAL);
                              ////console.log(contador);
                           }
                           else if($scope.Params[paracont].IDTIPODATO==3){
                              $scope.Proyectos[contador].param[value.NOMBREPARAM]=value.VAL;
                              ////console.log( $scope.Proyectos[paracont].param[value.NOMBREPARAM]);
                           }
                           else if ($scope.Params[paracont].IDTIPODATO==4){
                             // $scope.Proyectos[contador].param[value.NOMBREPARAM]=value.VAL;
                             if(value.VAL){
                             var res = value.VAL.split("/");
                              //var fechaactual=res[2]+"-"+res[1]+"-"+res[0];
                             var fechaactual = new Date(res[2], res[1]-1, res[0]);//cambiar a que muestre solo esos valores

                              $scope.Proyectos[contador].param[value.NOMBREPARAM]=fechaactual;
                            }
                            else{
                                $scope.Proyectos[contador].param[value.NOMBREPARAM]="";
                            }

                           }
                            $scope.valores[contador][value.NOMBREPARAM]=value.IDVALOR;
                      });
                      //console.log($scope.Proyectos);

                    })
                    .error(function(data) {
                      //console.log('Error: ' + data);
                      });

                   //console.log( 'fin');

                   //verificar accesos de R y RW
                   $http.post('api/getAccesbyProys.php',{pm:pm})
                    .success(function(data) {
                      if(data.PRIVILEGIO=="RW"){
                        //console.log(data.PRIVILEGIO);
                        $scope.ShowWrite=true;
                      }
                      else if(data.PRIVILEGIO=="R"){
                        //console.log(data.PRIVILEGIO);
                        $scope.ShowWrite=false;
                      }
                      else{
                        //console.log("error salio:"+data.PRIVILEGIO);
                      }

                    })
                    .error(function(data) {
                      //console.log('Error: ' + data);
                      });
          }
          else{
              $scope.alerts.push({msg: 'Seleccione el Proyecto Macro y el Módulo', type: 'warning', tiempo: '4000'});
          }
      };
      $scope.editarValores=function(pro,index){
        //alert(index);
      //  alert($scope.Proyectos[index].CODPROYECTO);
        if($scope.ShowWrite){
          $scope.pro=pro.param;//falta castear para que reconoscca como entero al momento de editar!!!! no olvidar
          $scope.pro.CODPROYECTO=$scope.Proyectos[index].CODPROYECTO;
          $scope.pro.NOMBREPROY=$scope.Proyectos[index].NOMBREPROY;
          $scope.pro.IDPROYECTO=$scope.Proyectos[index].IDPROYECTO;
          $scope.pro.ESTADOPROY=$scope.Proyectos[index].ESTADOPROY;
          $scope.pro.valores=$scope.valores[index];
          $scope.pro.etiquetas=$scope.Etiquetas;

          //alert(typeof $scope.pro["Fecha Modificacion Plan"]);
          //console.log($scope.pro);
          $scope.EditarProyecto=true;
          $scope.ShowTablecomplete=false;
          $scope.ShowTableParams=false;
          $scope.NuevoProyecto=false;
        }
      };

      $scope.volvertablaproyectos=function(){
          $scope.EditarProyecto=false;
          $scope.ShowTablecomplete=true;
          $scope.ShowTableParams=true;
          $scope.NuevoProyecto=false;
      }
      $scope.editProyecto=function(pro){
          //console.log(JSON.stringify(pro));
          //$http({method:'POST',url: 'api/editProyecto2.php', data: $.param({pro:pro, pa:$scope.Params}) ,headers : { 'Content-Type': 'application/x-www-form-urlencoded' }})
          $http.post('api/editProyecto.php',{pro:pro, pa:$scope.Params} )
            .success(function(data) {
                $scope.getProyecByProyMacro($scope.pmgetProyecByProyMacro);
                $scope.ShowTablecomplete=true;
                $scope.ShowTableParams=true;
                $scope.EditarProyecto=false;
              //console.log(data);
            })
            .error(function(data) {
              //console.log('Error: ' + data);
              });
      }

      $scope.agregarProyecto= function(){
        $scope.ShowTablecomplete=false;
        $scope.NuevoProyecto=true;
        $scope.EditarProyecto=false;
      }
      $scope.guardarProyecto=function(pron){
         //console.log(pron);
          $http.post('api/addProyecto.php',{pro:pron, pa:$scope.Params, pm: $scope.pmgetProyecByProyMacro} )
            .success(function(data) {
                $scope.getProyecByProyMacro($scope.pmgetProyecByProyMacro);
                $scope.ShowTablecomplete=true;
                $scope.ShowTableParams=true;
              //console.log(data);
            })
            .error(function(data) {
              //console.log('Error: ' + data);
              });
    }

      $scope.csv = {
          content: null,
          header: true,
          headerVisible: false,
          separator: ',',
          separatorVisible: false,
          result: null,
          accept:'.csv, .xls, .xlsx',
          encoding: 'UTF16',
          encodingVisible: false,
    };

    $scope.LogError = function () {
        ////console.log($scope.logImportar);
        //alert($scope.logImportar);
        if($scope.LogImp == true) $scope.LogImp=false;
        else $scope.LogImp=true;
        //console.log($scope.LogImp);
    }

      $scope.importar = function (json, tabWidth) {
          if($scope.csv.result){
            var objeto = $scope.csv.result.slice(0,$scope.csv.result.length);
            if(objeto[0]['id del proyecto'] && objeto[0]['codigo del proyecto'] && objeto[0]['Nombre del proyecto']){
              if ( confirm("¿Está seguro que desea importar del archivo seleccionado?") ) {
                  $http.post('api/importarProyecto.php', {va :objeto, pm : $scope.pmgetProyecByProyMacro, pa : $scope.Params } )
                  .success(function(data) {
                     $scope.logImportar=[];
                      //console.log(data);
                      if(data.success){
                         
                         //$scope.csv.result=null;
                          var mensaje = data.success.split('\n');
                          //console.log('mensaje');
                          //console.log(mensaje);
                          $.each( mensaje, function( index, value ) {
                                 if (value!=''){
                                    //console.log('value:' + value);
                                    $scope.newAlert(value,'success','3000');
                                    $scope.logImportar.push(value);
                                }
                            });
                          $scope.getProyecByProyMacro($scope.pmgetProyecByProyMacro);
                        }
                        else{
                          $scope.newAlert('Se encontró un error al importar los parámetros','danger','3000');
                        }
                         if(data.error){
                          var mensaje = data.error.split('\n');
                          $.each( mensaje, function( index, value ) {
                                 if (value!=''){
                                    $scope.newAlert(value,'danger','3000');
                                    $scope.logImportar.push(value);
                                }
                          });
                        }
                        //console.log('log');
                        //console.log($scope.logImportar);
                   
                  })
                  .error(function(data) {
                    //console.log('Error: ' + data);
                    });
                }
            }
            else{
                $scope.newAlert('Error: el archivo a importar debe contener al menos los campos: id del proyecto, codigo del proyecto, Nombre del proyecto.','danger','5000');
            }
            //console.log('resultado:' + $scope.csv.result);
          }
          else{
              $scope.newAlert('Seleccione el archivo a importar.','warning','3000');
          }
          //console.log($scope.csv.result);
    };

  }])

  .controller('editarController',['$scope', function ($scope) {


  }])
  .controller('exportarController',['$scope', function ($scope) {
    


  }])
  .controller('cargaMasivaController',['$scope', function ($scope) {
    

  }])

  
})();
