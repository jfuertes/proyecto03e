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
.filter('filterMaestro', function(){
  return function(id){
    var estados = ['No usa', 'Si usa'];
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
  $scope.seleccionar=false;
  $scope.showConsultaByProy=false;
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


  /*  $scope.getParametro= function(){
       $http.post('api/getParametro.php' )
                .success(function(data) {
                  console.log(data);
                  $scope.Parametro=data;
                })
                .error(function(data) {
                  console.log('Error: ' + data);
                  });
    };*/
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

      $scope.getModulos= function(){
       $http.post('api/getModulos.php' )
                .success(function(data) {
                  console.log(data);
                  $scope.Modulos=data;
                })
                .error(function(data) {
                  console.log('Error: ' + data);
                  });
    };
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
     
   // $scope.getParametro();
    $scope.getiposDatos();
    $scope.getModulos();
    $scope.getProyMacro();

     $scope.formNewParam= function(pa){
       if ( confirm("¿Está seguro que desea Confirmar el registro?") ) {
          console.log(pa);
           $http.post('api/addParametro.php', {pa :pa, pm : $scope.IDPROYMACRO} )
                    .success(function(data) {
                      console.log(data);
                      //$scope.addProyMacro=data;
                      if(data="true"){
                        $scope.formByProyMacro($scope.pmLocal);
                         $scope.ShowTableParams=true;
                         alert("registro de parametro exitoso");
                        document.getElementById("formParametro").reset();
                      }
                      else{
                        alert("error con el servidor intentelo mas tarde");
                         $scope.ShowTableParams=true;
                      }
                      
                    })
                    .error(function(data) {
                      console.log('Error: ' + data);
                      });

         }
    };

    $scope.agregarParam= function(){
        $scope.ShowTableParams=false;
    };

     $scope.volverParametro= function(){
        $scope.ShowTableParams=true;
    };
     $scope.formByProyMacro= function(pm){
      console.log(pm);

      $scope.IDPROYMACRO=pm.idProy;
      $scope.pmLocal=pm;
       $http.post('api/selectParamByProyMacro.php',{pm:pm})
                .success(function(data) {
                  console.log(data);
                  $scope.Parametro=data;
                  $scope.NAMEPROYMACRO=data[0].NOMBREPROYMACRO;
                  $scope.showConsultaByProy=true;
                  
                })
                .error(function(data) {
                  console.log('Error: ' + data);
                  });
        
    };
    

  })
  
})();
