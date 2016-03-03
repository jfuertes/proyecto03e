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
  .controller('proyectosController', function ($scope, DTOptionsBuilder, DTColumnDefBuilder, DTDefaultOptions, $http) {
    $scope.ShowTableParams=true;
    $scope.ShowTablecomplete=false;

      $scope.dtOptions = DTOptionsBuilder.newOptions()
        .withPaginationType('full_numbers')
        .withDisplayLength(10)
        .withBootstrap()
        .withButtons([
            'colvis',
            'copy',
            'print',
            'excel',
            {
                text: 'Importar',
                key: '1',
                action: function (e, dt, node, config) {
                    console.log(e);
                    console.log(dt);
                    console.log(node);
                    console.log(config);
                }
            }
        ]);

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
            $http.post('admin/api/getProyecByProyMacro.php',{pm:pm} )
                .success(function(data) {
                    $scope.ShowTablecomplete=true;
                  console.log(data);
                  $scope.Proyectos=data;
                  $scope.ProyectosArray=[];
                  $scope.valores={};
                  $.each(data,function(index,value){
                    $scope.ProyectosArray[index]=value.NOMBREPROY;
                    $scope.Proyectos[index].param={};
                  });
                  console.log( $scope.ProyectosArray);
                })
                .error(function(data) {
                  console.log('Error: ' + data);
                  });

            $http.post('admin/api/getParamsByMacroyMod.php',{pm:pm} )
                .success(function(data) {
                    $scope.ShowTablecomplete=true;
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
                  $.each(data,function(index,value){
                    //console.log( value);
                    if( jQuery.inArray( value.NOMBREPROY, $scope.ProyectosArray )>0){
                    //  console.log(jQuery.inArray( value.NOMBREPROY, $scope.ProyectosArray ));
                        var contador = jQuery.inArray( value.NOMBREPROY, $scope.ProyectosArray );
                        //var nameparam=value.NOMBREPARAM;
                       /* $scope.Proyectos[contador][nameparam]=value.VAL;
                        console.log($scope.Proyectos[contador][nameparam]);
                        */
                        //console.log(value.NOMBREPROY, value.VAL);
                       // $scope.Proyectos[contador].param[value.ORDEN-1]=value.VAL;
                        $scope.Proyectos[contador].param[value.NOMBREPARAM]=value.VAL;
                        //                  console.log("!!!!!!!!!!---------"+$scope.Proyectos[contador].param);
                        $scope.valores[value.NOMBREPARAM]=value.IDVALOR;
                        //console.log($scope.valores);

                    }
                    //console.log($scope.Proyectos[contador].param);
                  });
                  console.log($scope.Proyectos);
                })
                .error(function(data) {
                  console.log('Error: ' + data);
                  });
               // console.log($scope.Params);

      };
      $scope.editarValores=function(pro,index){
        //alert(index);
      //  alert($scope.Proyectos[index].CODPROYECTO);
        $scope.pro=pro.param;
        $scope.pro.CODPROYECTO=$scope.Proyectos[index].CODPROYECTO;
        $scope.pro.NOMBREPROY=$scope.Proyectos[index].NOMBREPROY;
        $scope.pro.IDPROYECTO=$scope.Proyectos[index].IDPROYECTO;
        $scope.pro.valores=$scope.valores;
        $scope.pro.etiquetas=$scope.Etiquetas;
      alert(JSON.stringify($scope.pro.etiquetas));
        $scope.EditarProyecto=true;
        $scope.ShowTablecomplete=false;
        $scope.ShowTableParams=false;

      };
      $scope.volvertablaproyectos=function(){
          $scope.EditarProyecto=false;
          $scope.ShowTablecomplete=true;
          $scope.ShowTableParams=true;
      }
      $scope.editProyecto=function(pro){
         console.log(JSON.stringify(pro));
          $http.post('api/editProyecto.php',{pro:pro, pa:$scope.Params} )
                .success(function(data) {
                    //$scope.getProyecByProyMacro($scope.pmgetProyecByProyMacro);
                    //$scope.ShowTablecomplete=true;
                   // $scope.ShowTableParams=true;
                  console.log(data);
                })
                .error(function(data) {
                  console.log('Error: ' + data);
                  });


      }

  })

  .controller('editarController',['$scope', function ($scope) {


  }])
  .controller('exportarController',['$scope', function ($scope) {
    


  }])
  .controller('cargaMasivaController',['$scope', function ($scope) {
    

  }])

  
})();
