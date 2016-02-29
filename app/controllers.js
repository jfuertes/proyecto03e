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
  .controller(  'proyectosController', function ($scope, DTOptionsBuilder, DTColumnDefBuilder, DTDefaultOptions, $http) {
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
            $http.post('admin/api/getProyecByProyMacro.php',{pm:pm} )
                .success(function(data) {
                    $scope.ShowTablecomplete=true;
                  console.log(data);
                  $scope.Proyectos=data;
                })
                .error(function(data) {
                  console.log('Error: ' + data);
                  });

            $http.post('admin/api/getParamsByMacroyMod.php',{pm:pm} )
                .success(function(data) {
                    $scope.ShowTablecomplete=true;
                  console.log(data);
                  $scope.Params=data;
                })
                .error(function(data) {
                  console.log('Error: ' + data);
                  });



      };

  })

  .controller('editarController',['$scope', function ($scope) {


  }])
  .controller('exportarController',['$scope', function ($scope) {
    


  }])
  .controller('cargaMasivaController',['$scope', function ($scope) {
    

  }])

  
})();
