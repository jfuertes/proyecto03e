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
      return "No Data";
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
                 //console.log(data);
                //  console.log(data[0]);
                 // $scope.Valores=data;
                  $.each(data,function(index,value){
                   /// console.log(value.NOMBREPROY);
                   // console.log(value.NOMBREPARAM);
                     //   console.log(value.VAL);
                    
                    //  console.log(jQuery.inArray( value.NOMBREPROY, $scope.ProyectosArray ));
                        var contador = jQuery.inArray( value.NOMBREPROY, $scope.ProyectosArray );
                        //var nameparam=value.NOMBREPARAM;
                       /* $scope.Proyectos[contador][nameparam]=value.VAL;
                        console.log($scope.Proyectos[contador][nameparam]);
                        */
                        
                       // $scope.Proyectos[contador].param[value.ORDEN-1]=value.VAL;
                        $scope.Proyectos[contador].param[value.NOMBREPARAM]=value.VAL;
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

        //alert(JSON.stringify($scope.pro));

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
          $http.post('api/editProyecto.php',{pro:pro, pa:$scope.Params} )
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

      $scope.agregarProyecto= function(){
        $scope.ShowTablecomplete=false;
        $scope.NuevoProyecto=true;
      }
      $scope.guardarProyecto=function(pro){
         console.log(JSON.stringify(pro));
          $http.post('api/addProyecto.php',{pro:pro, pa:$scope.Params, pm: $scope.pmgetProyecByProyMacro} )
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

  }])

  .controller('editarController',['$scope', function ($scope) {


  }])
  .controller('exportarController',['$scope', function ($scope) {
    


  }])
  .controller('cargaMasivaController',['$scope', function ($scope) {
    

  }])

  
})();
