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

.filter('filterActivo', function(){
  return function(id){
    var estados = ['Inactivo', 'Activo'];
      return estados[id];
    };
  })

.controller('mainController', function ($scope) {
})
.controller('proyMacroController', function ($scope, DTOptionsBuilder, DTColumnDefBuilder, DTDefaultOptions, $http) {
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
       $http.post('api/getProyMacro.php' )
                .success(function(data) {
                  console.log(data);
                  $scope.ProyMacro=data;
                })
                .error(function(data) {
                  console.log('Error: ' + data);
                  });
    };
     
    $scope.getProyMacro();

     $scope.formNewProyMacro= function(pm){
       $http.post('api/addProyMacro.php', {pm :pm} )
                .success(function(data) {
                  console.log(data);
                  $scope.addProyMacro=data;
                  if(data="true"){
                    $scope.getProyMacro();
                    alert("registro de proyecto macro exitoso");
                  }
                  
                })
                .error(function(data) {
                  console.log('Error: ' + data);
                  });
    };

  })


.controller('tabMaestrasController', function ($scope, DTOptionsBuilder, DTColumnDefBuilder, $http) {
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


    $scope.getEtiquetas= function(){
       $http.post('api/getEtiqueta.php' )
                .success(function(data) {
                  console.log(data);
                  $scope.etiquetas=data;
                })
                .error(function(data) {
                  console.log('Error: ' + data);
                  });
    };
     
    $scope.getEtiquetas();

})


.controller('parametrosController', function ($scope, DTOptionsBuilder, DTColumnDefBuilder, $http) {
   $scope.ShowTableParams=true;
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


    $scope.getParametro= function(){
       $http.post('api/getParametro.php' )
                .success(function(data) {
                  console.log(data);
                  $scope.Parametro=data;
                })
                .error(function(data) {
                  console.log('Error: ' + data);
                  });
    };
    $scope.getiposDatos= function(){
       $http.post('api/getTiposDatos.php' )
                .success(function(data) {
                  console.log(data);
                  $scope.tiposDatos=data;
                })
                .error(function(data) {
                  console.log('Error: ' + data);
                  });
    };
     
    $scope.getParametro();
    $scope.getiposDatos();

     $scope.formNewParam= function(pa){
      console.log(pa);
       $http.post('api/addParametro.php', {pa :pa} )
                .success(function(data) {
                  console.log(data);
                  //$scope.addProyMacro=data;
                  if(data="true"){
                    $scope.getParametro();
                    alert("registro de parametro exitoso");
                     $scope.ShowTableParams=true;
                     $("#formParametro").reset();
                  }
                  else{
                    alert("error con el servidor intentelo mas tarde");
                     $scope.ShowTableParams=true;
                  }
                  
                })
                .error(function(data) {
                  console.log('Error: ' + data);
                  });
    };

    $scope.agregarParam= function(){
        $scope.ShowTableParams=false;
    };

  })
  
})();
