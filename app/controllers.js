/**
 * List Controller
 * @version v1.0.0 - 2016-02-08 * @link http://www.mindtec.pe
 * @author MindTec <contacto@mindtec.pe>
 * @license MIT License, http://www.opensource.org/licenses/MIT
 */
(function(){
  'use strict';

angular.module('Controllers', ['datatables', 'datatables.bootstrap', 'datatables.buttons']).run(function(DTDefaultOptions) {
    DTDefaultOptions.setLanguageSource('//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json');
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
    else{
      return id;
    }
    };
  })



  .controller('mainController',['$scope', '$http', function ($scope, $http) {
    
    /*
      $http({method:'POST',url: 'api/getProy.php',headers : { 'Content-Type': 'application/x-www-form-urlencoded' }})
      .success(function(response) {
        console.log(response);
      })
      .error(function(response){
        console.log(response);
      });
*/

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

       $scope.getProyMacro= function(){
            $http.post('admin/api/getProyMacro.php' )
                .success(function(data) {
                  console.log(data);
                  $scope.ProyMacro=data;
                })
                .error(function(data) {
                  console.log('Error: ' + data);
                  });
      };
       $scope.getModulos= function(){
            $http.post('admin/api/getModulos.php' )
                .success(function(data) {
                  console.log(data);
                  $scope.Modulos=data;
                })
                .error(function(data) {
                  console.log('Error: ' + data);
                  });
      };

      $scope.getProyMacro();
      $scope.getModulos();
      
      $scope.getProyecByProyMacro=function(pm){
        $scope.pmgetProyecByProyMacro=pm;
        console.log(pm);
        console.log( 'inicio');
            $http.post('admin/api/getProyecByProyMacro.php',{pm:pm} )
                .success(function(data) {
                  $scope.ShowTablecomplete=true;
                  console.log(data);
                  $scope.Proyectos=data;
                  $scope.ProyectosArray=[];
                  $scope.valores=[];

                  $.each(data,function(index,value){
                    $scope.ProyectosArray[index]=value.NOMBREPROY;
                    $scope.Proyectos[index].param={};
                    $scope.valores[index]={};
                  });
                  console.log( $scope.ProyectosArray);
                })
                .error(function(data) {
                  console.log('Error: ' + data);
                  });

            $http.post('admin/api/getParamsByMacroyMod.php',{pm:pm} )
                .success(function(data) {
                    $scope.ShowTablecomplete=true;
                  console.log('param');
                  console.log(data);
                  $scope.Params=data;
                      $http.post('api/getEtByparams.php',{params: $scope.Params} )
                     
                        .success(function(data) {
                            //$scope.ShowTablecomplete=true;
                          console.log(data);
                          $scope.Etiquetas=data;
                        })
                        .error(function(data) {
                          console.log('Error: ' + data);
                          });

                })
                .error(function(data) {
                  console.log('Error: ' + data);
                  });

            $http.post('admin/api/getValoresByMacroyMod.php',{pm:pm} )
                .success(function(data) {
                    $scope.ShowTablecomplete=true;
                 console.log(data);
                //  console.log(data[0]);
                 // $scope.Valores=data;
                 console.log($scope.Params);
                  $.each(data,function(index,value){
                   /// console.log(value.NOMBREPROY);
                   // console.log(value.NOMBREPARAM);
                     //   console.log(value.VAL);
                    console.log(value.IDPARAMETRO);
                    //  console.log(jQuery.inArray( value.NOMBREPROY, $scope.ProyectosArray ));
                        var contador = jQuery.inArray( value.NOMBREPROY, $scope.ProyectosArray );
                        // contador de parametro usando el value.IDPARAMETRO  
                        var contPara=0;
                        var paracont;
                        $.each($scope.Params, function(indexp, valuep){
                          //console.log(parseInt(valuep.IDPARAMETRO) == value.IDPARAMETRO);
                          if(parseInt(valuep.IDPARAMETRO) == value.IDPARAMETRO){
                            paracont=contPara;
                          }
                          contPara++;

                        });
                        console.log(paracont);
                        console.log($scope.Params[paracont].IDTIPODATO);
                        //var nameparam=value.NOMBREPARAM;
                       if($scope.Params[paracont].IDTIPODATO==1){
                          $scope.Proyectos[contador].param[value.NOMBREPARAM]=parseInt(value.VAL);
                         // console.log(parseInt(value.VAL));
                       }
                       else if($scope.Params[paracont].IDTIPODATO==2){
                          $scope.Proyectos[contador].param[value.NOMBREPARAM]=parseFloat(value.VAL);
                          console.log(value.VAL);
                          console.log(contador);
                       }
                       else if($scope.Params[paracont].IDTIPODATO==3){
                          $scope.Proyectos[contador].param[value.NOMBREPARAM]=value.VAL;
                       }
                       else{
                          $scope.Proyectos[paracont].param[value.NOMBREPARAM]=value.VAL;

                       }
                        
                        //console.log( $scope.Proyectos[contador].param);
                        //                  console.log("!!!!!!!!!!---------"+$scope.Proyectos[contador].param);
                        $scope.valores[contador][value.NOMBREPARAM]=value.IDVALOR;
                        //console.log($scope.valores);

                    
                    //console.log($scope.Proyectos[contador].param);
                  });
                  console.log($scope.Proyectos);
                  //$scope.ordenar($scope.Params, $scope.Proyectos);
                 // var $scope.desorden=$scope.Proyectos;
                  //var $scope.orden=$scope.Params;
                // console.log(desorden);

                })
                .error(function(data) {
                  console.log('Error: ' + data);
                  });
               // console.log($scope.Params);

               console.log( 'fin');



 

      };
      $scope.editarValores=function(pro,index){
        //alert(index);
      //  alert($scope.Proyectos[index].CODPROYECTO);
        $scope.pro=pro.param;//falta castear para que reconoscca como entero al momento de editar!!!! no olvidar
        $scope.pro.CODPROYECTO=$scope.Proyectos[index].CODPROYECTO;
        $scope.pro.NOMBREPROY=$scope.Proyectos[index].NOMBREPROY;
        $scope.pro.IDPROYECTO=$scope.Proyectos[index].IDPROYECTO;
        $scope.pro.valores=$scope.valores[index];
        $scope.pro.etiquetas=$scope.Etiquetas;

       // alert(JSON.stringify($scope.pro.etiquetas));
        console.log($scope.pro);
        $scope.EditarProyecto=true;
        $scope.ShowTablecomplete=false;
        $scope.ShowTableParams=false;
        $scope.NuevoProyecto=false;

      };

      $scope.volvertablaproyectos=function(){
          $scope.EditarProyecto=false;
          $scope.ShowTablecomplete=true;
          $scope.ShowTableParams=true;
          $scope.NuevoProyecto=false;
      }
      $scope.editProyecto=function(pro){
          console.log(JSON.stringify(pro));
          //$http({method:'POST',url: 'api/editProyecto2.php', data: $.param({pro:pro, pa:$scope.Params}) ,headers : { 'Content-Type': 'application/x-www-form-urlencoded' }})
          $http.post('api/editProyecto.php',{pro:pro, pa:$scope.Params} )
                .success(function(data) {
                    $scope.getProyecByProyMacro($scope.pmgetProyecByProyMacro);
                    $scope.ShowTablecomplete=true;
                    $scope.ShowTableParams=true;
                    $scope.EditarProyecto=false;
                  console.log(data);
                })
                .error(function(data) {
                  console.log('Error: ' + data);
                  });
      }

      $scope.agregarProyecto= function(){
        $scope.ShowTablecomplete=false;
        $scope.NuevoProyecto=true;
        $scope.EditarProyecto=false;
      }
      $scope.guardarProyecto=function(pron){
         console.log(pron);
          $http.post('api/addProyecto.php',{pro:pron, pa:$scope.Params, pm: $scope.pmgetProyecByProyMacro} )
                .success(function(data) {
                    $scope.getProyecByProyMacro($scope.pmgetProyecByProyMacro);
                    $scope.ShowTablecomplete=true;
                    $scope.ShowTableParams=true;
                  console.log(data);
                })
                .error(function(data) {
                  console.log('Error: ' + data);
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

      $scope.importar = function (json, tabWidth) {
          /*if($scope.csv.result){
            var objeto = $scope.csv.result.slice(0,$scope.csv.result.length);

            if ( confirm("¿Está seguro que desea importar del archivo seleccionado?") ) {
                $http.post('api/importarParametro.php', {pa :objeto, pm : $scope.IDPROYMACRO} )
                .success(function(data) {
                  console.log(data);
                  $scope.formByProyMacro($scope.pmLocal);
                  $scope.csv.result=null;
                })
                .error(function(data) {
                  console.log('Error: ' + data);
                  });
            }
            console.log('resultado:' + $scope.csv.result);
          }*/
          console.log($scope.csv.result);
    };

  }])

  .controller('editarController',['$scope', function ($scope) {


  }])
  .controller('exportarController',['$scope', function ($scope) {
    


  }])
  .controller('cargaMasivaController',['$scope', function ($scope) {
    

  }])

  
})();
