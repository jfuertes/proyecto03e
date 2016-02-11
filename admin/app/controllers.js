 /**
 * List Controller
 * @version v1.0.0 - 2016-02-08 * @link http://www.mindtec.pe
 * @author MindTec <contacto@mindtec.pe>
 * @license MIT License, http://www.opensource.org/licenses/MIT
 */
(function(){
  'use strict';

  angular.module('Controllers', [])

  
  .controller('mainController',['$scope', function ($scope) {
    $(document).ready(function() {
        $('#dataTables-home').DataTable({
                responsive: true,
                "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            }
        });
    });

  }])
  .controller('proyMacroController',['$scope', function ($scope) {
    

  }])
  .controller('tabMaestrasController',['$scope', '$http', function ($scope, $http) {
    $(document).ready(function() {
          $('#dataTables-maestra').DataTable({
                  responsive: true,
                  "language": {
                      "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                  }
          });
      });


    $http.post('api/getEtiqueta.php' )
              .success(function(data) {

                console.log(data);
               // console.log(data[0].IDMAESTRO);
                $scope.etiquetas=data;
                //$scope.etiquetas=[42, 42, 43, 43];

              })
              .error(function(data) {
                //console.log('Error: ' + data);
                console.log(data);
                });



    $scope.formNewEtiqueta = function(et){
       $http.post('api/addEtiqueta.php', { et: et } )
              .success(function(data) {
                console.log(data);
              })
              .error(function(data) {
                //console.log('Error: ' + data);
                console.log(data);
                });

    }

  }])
  .controller('exportarController',['$scope', function ($scope) {
    


  }])
  .controller('cargaMasivaController',['$scope', function ($scope) {
    

  }])

  
})();
