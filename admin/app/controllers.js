 /**
 * List Controller
 * @version v1.0.0 - 2016-02-08 * @link http://www.mindtec.pe
 * @author MindTec <contacto@mindtec.pe>
 * @license MIT License, http://www.opensource.org/licenses/MIT
 */
(function(){
  'use strict';

angular.module('Controllers', ['datatables', 'datatables.bootstrap', 'datatables.buttons', 'ui.bootstrap', 'ngAnimate']).run(function(DTDefaultOptions) {
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
    var estados = ['No', 'Si'];
      return estados[id];
    };
  })

.directive('stringToNumber', function() {
  return {
    require: 'ngModel',
    link: function(scope, element, attrs, ngModel) {
      ngModel.$parsers.push(function(value) {
        return '' + value;
      });
      ngModel.$formatters.push(function(value) {
        return parseFloat(value, 10);
      });
    }
  }
})

.controller('mainController', function ($scope) {
   $scope.alerts = [];

    $scope.newAlert = function(mensaje, tipo, tiempo) {
        $scope.alerts.push({msg: mensaje, type: tipo, tiempo: tiempo});

        $('html,body').animate({
            scrollTop: $("#alerta").offset().top
        }, 500);
    };

    $scope.closeAlert = function(index) {
      $scope.alerts.splice(index, 1);
    };

})

// Controlador Gestión Proyecto Macro
.controller('proyMacroController', ['$scope', 'DTOptionsBuilder', 'DTColumnDefBuilder', 'DTDefaultOptions', '$http', function ($scope, DTOptionsBuilder, DTColumnDefBuilder, DTDefaultOptions, $http) {
     $scope.dtOptions = DTOptionsBuilder.newOptions()
        .withPaginationType('full_numbers')
        .withDisplayLength(10)
        .withBootstrap()
        .withButtons([
            'colvis',
            'copy',
            'csv',
            'pdf'
        ]);

    $scope.alerts = [];

    $scope.newAlert = function(mensaje, tipo, tiempo) {
        $scope.alerts.push({msg: mensaje, type: tipo, tiempo: tiempo});

        $('html,body').animate({
            scrollTop: $("#alerta").offset().top
        }, 500);
    };

    $scope.closeAlert = function(index) {
      $scope.alerts.splice(index, 1);
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
     
    $scope.cambioEstado= function(id, estado, $index){
        
      $http({method:'POST',url: 'api/estadoProyMacro.php', data: $.param({id: id, estado: estado}) ,headers : { 'Content-Type': 'application/x-www-form-urlencoded' }})
        .success(function(response) {
          $scope.ProyMacro[$index].ESTADOPM=estado;
          var NOMBREPROYMACRO=$scope.ProyMacro[$index].NOMBREPROYMACRO;
          $scope.newAlert('Cambio de estado de proyecto macro '+ NOMBREPROYMACRO + ' exitoso.','success','3000');
      })
      .error(function(response){

      });
    }

    $scope.formNewProyMacro= function(pm){
       $http.post('api/addProyMacro.php', {pm :pm} )
                .success(function(data) {
                  console.log(data);
                  $scope.addProyMacro=data;
                  if(data="true"){
                    
                    $scope.getProyMacro();
                    $scope.pm.NOMBREPROYMACRO="";

                    $('html,body').animate({
                      scrollTop: $("#alerta").offset().top
                      }, 0);

                    $scope.newAlert('registro de proyecto macro exitoso!.','success','3000');

                  }
                  
                })
                .error(function(data) {
                  console.log('Error: ' + data);
                  $scope.newAlert('Error al crear proyecto macro.','danger','3000');
                });
    };

    $scope.getProyMacro();
  }])

// Controlador Gestión tablas maestras
.controller('tabMaestrasController', ['$scope', 'DTOptionsBuilder', 'DTColumnDefBuilder', '$http', function ($scope, DTOptionsBuilder, DTColumnDefBuilder, $http) {
          $scope.dtOptions = DTOptionsBuilder.newOptions()
        .withPaginationType('full_numbers')
        .withDisplayLength(10)
        .withBootstrap()
        .withButtons([
            'colvis',
            'copy',
            'excel'
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

}])

.controller('usuariosController', ['$scope', 'DTOptionsBuilder', 'DTColumnDefBuilder', '$http', function ($scope, DTOptionsBuilder, DTColumnDefBuilder, $http) {
          $scope.dtOptions = DTOptionsBuilder.newOptions()
        .withPaginationType('full_numbers')
        .withDisplayLength(10)
        .withBootstrap()
        .withButtons([
            'colvis',
            'copy',
            'excel'
        ]);

        $scope.alerts = [];
         $scope.newAlert = function(mensaje, tipo, tiempo) {
        $scope.alerts.push({msg: mensaje, type: tipo, tiempo: tiempo});

        $('html,body').animate({
            scrollTop: $("#alerta").offset().top
        }, 500);
    };

    $scope.closeAlert = function(index) {
      $scope.alerts.splice(index, 1);
    };

    $scope.getUsuarios= function(){
       $http.post('api/getUsuarios.php' )
                .success(function(data) {
                  console.log(data);
                  $scope.usuarios=data;
                })
                .error(function(data) {
                  console.log('Error: ' + data);
                  });
    };
    $scope.getUsuarios();

    $scope.cambioEstadoUser= function(iduser, estado, $index){
      console.log(iduser);
      console.log(estado);
      console.log($index);
       
      $http({method:'POST',url: 'api/estadoUser.php', data: $.param({iduser: iduser, estado: estado}) ,headers : { 'Content-Type': 'application/x-www-form-urlencoded' }})
        .success(function(response) {
          $scope.usuarios[$index].ESTADO=estado;
          
          $scope.newAlert('Cambio de estado de Usuario exitoso.','success','3000');
          //$scope.alerts.push({msg: 'Cambio de estado de parámetro exitoso.', type: 'success', tiempo: '3000'});
      })
      .error(function(response){

      });
    }


}])



// Controlador Gestión Parámetros
.controller('parametrosController', ['$scope', 'DTOptionsBuilder', 'DTColumnDefBuilder', '$http', function ($scope, DTOptionsBuilder, DTColumnDefBuilder, $http) {
    $scope.ShowEtiquetasByParam=false;
    $scope.seleccionar=false;
    $scope.showConsultaByProy=false;
    $scope.ShowTableParams=true;
    $scope.editParams=false;

    $scope.dtOptions = DTOptionsBuilder.newOptions()
        .withPaginationType('full_numbers')
        .withDisplayLength(10)
        .withBootstrap()
        .withButtons([
            'colvis',
            'copy',
            'csv',
            'pdf'
        ]);

    $scope.alerts = [];

    $scope.newAlert = function(mensaje, tipo, tiempo) {
        $scope.alerts.push({msg: mensaje, type: tipo, tiempo: tiempo});

        $('html,body').animate({
            scrollTop: $("#alerta").offset().top
        }, 500);
    };

    $scope.closeAlert = function(index) {
      $scope.alerts.splice(index, 1);
    };

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
                     $scope.newAlert('Registro de parametro exitoso.','success','3000');
                    document.getElementById("formParametro").reset();
                  }
                  else{
                      $scope.newAlert('Error con el servidor. Inténtelo más tarde.','danger','3000');
                      $scope.ShowTableParams=true;
                  }
                  
                })
                .error(function(data) {
                  console.log('Error: ' + data);
                  });

         }
    };

    $scope.formEditParam= function(pa){
      console.log(pa);
       $http.post('api/editParametro.php', {pa :pa} )
            .success(function(data) {
              console.log(data);
              //$scope.addProyMacro=data;
              if(data="true"){
                $scope.formByProyMacro($scope.pmLocal);
                 $scope.ShowTableParams=true;
                 $scope.editParams=false;
                 $scope.newAlert('Cambios del parámetro guardados exitosamente.','success','3000');
                document.getElementById("formParametro").reset();
              }
              else{
                  $scope.newAlert('Error con el servidor. Inténtelo más tarde.','danger','3000');
                  $scope.ShowTableParams=true;
              }
              
            })
            .error(function(data) {
              console.log('Error: ' + data);
              });

    };

    $scope.agregarParam= function(){
        $scope.ShowTableParams=false;
        $scope.editParams=false;
    };

    $scope.editarParam= function(pa){
        $scope.ShowTableParams=false;
        $scope.editParams=true;
        console.log(pa);
        $scope.ParamSelect=pa;
    };

     $scope.volverParametro= function(){
        $scope.ShowEtiquetasByParam=false;
        $scope.ShowTableParams=true;
        $scope.editParams=false;
    };

  
    $scope.ShowEtiquetasByParams= function(idpa, namepa){
        $http.post('api/selectEtiqueByParam.php',{idpa:idpa})
          .success(function(data){
              console.log(data);
              $scope.NAMEParametro=namepa;
              $scope.IDParametro=idpa;
              $scope.EtiquetasbyParam=data;
              $scope.ShowTableParams=false;
              $scope.ShowEtiquetasByParam=true;
          })
          .error(function(data){
            console.log("ERROR: "+data);
          })
    };

     $scope.formByProyMacro= function(pm){
      console.log(pm);
      var result = $.grep($scope.ProyMacro, function(e){ return e.IDPROYMACRO == pm.idProy; });
      $scope.NAMEPROYMACRO=result[0].NOMBREPROYMACRO;

      $scope.IDPROYMACRO=pm.idProy;
      $scope.pmLocal=pm;
      $scope.showConsultaByProy=true;
      
      $http.post('api/selectParamByProyMacro.php',{pm:pm})
          .success(function(data) {
            console.log(data);
            console.log('aca es');
            $scope.Parametro=data.data;
          })
          .error(function(data) {
            console.log('Error: ' + data);
          });
        
    };

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
          if($scope.csv.result){
            var objeto = $scope.csv.result.slice(0,$scope.csv.result.length);

            if ( confirm("¿Está seguro que desea importar del archivo seleccionado?") ) {
                $http.post('api/importarParametro.php', {pa :objeto, pm : $scope.IDPROYMACRO} )
                .success(function(data) {
                  console.log(data);
                  $scope.formByProyMacro($scope.pmLocal);
                  $scope.csv.result=null;

                  //validacion pendiente
                })
                .error(function(data) {
                  console.log('Error: ' + data);
                  });
            }
            console.log('resultado:' + $scope.csv.result);
          }
          console.log($scope.csv.result);
    };

    $scope.formNewet= function(et){
          console.log(et);
           $http.post('api/addEtiqueta.php', {et :et, idpa : $scope.IDParametro} )
                .success(function(data) {
                  console.log(data);
                  //$scope.addProyMacro=data;
                  if(data="true"){
                    $scope.ShowEtiquetasByParams($scope.IDParametro, $scope.NAMEParametro);
                     $scope.newAlert('registro de etiqueta exitoso.','success','3000');
                    document.getElementById("formParametro").reset();
                  }
                  else{
                    $scope.newAlert('Error con el servidor. Inténtelo más tarde.','danger','3000');
                     $scope.ShowTableParams=true;
                  }
                  
                })
                .error(function(data) {
                  console.log('Error: ' + data);
                  });
    }

    $scope.deleteEtiqueta=function(idma, index){
         console.log(idma);
         if ( confirm("¿Está seguro que desea eliminar la etiqueta "+index+" ?") ) {
             $http.post('api/deleteEtiqueta.php', { idma : idma} )
                  .success(function(data) {
                    console.log(data);
                    if(data="true"){
                      $scope.EtiquetasbyParam.splice(index,1);
                      $scope.newAlert('La equiqueta seleccionada fue eliminada.','success','3000');
                     // $scope.ShowEtiquetasByParams($scope.IDParametro, $scope.NAMEParametro);
                      document.getElementById("formParametro").reset();
                    }
                    else{
                      $scope.newAlert('Error con el servidor. Inténtelo más tarde.','danger','3000');
                    }
                  })
                  .error(function(data) {
                    console.log('Error: ' + data);
                    });
          }
    }

    $scope.cambioEstadoParam= function(idParam, idProyMacro, estado, $index){
       
      $http({method:'POST',url: 'api/estadoParametro.php', data: $.param({idParam: idParam, idProyMAcro: idProyMacro, estado: estado}) ,headers : { 'Content-Type': 'application/x-www-form-urlencoded' }})
        .success(function(response) {
          $scope.Parametro[$index].ESTADOPMPARAMETRO=estado;
          
          $scope.newAlert('Cambio de estado de parámetro exitoso.','success','3000');
          //$scope.alerts.push({msg: 'Cambio de estado de parámetro exitoso.', type: 'success', tiempo: '3000'});
      })
      .error(function(response){

      });
    }
    
  }])
  
})();
