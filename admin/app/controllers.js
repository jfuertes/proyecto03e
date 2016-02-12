 /**
 * List Controller
 * @version v1.0.0 - 2016-02-08 * @link http://www.mindtec.pe
 * @author MindTec <contacto@mindtec.pe>
 * @license MIT License, http://www.opensource.org/licenses/MIT
 */
(function(){
  'use strict';

  angular.module('Controllers', ['ngAnimate', 'ngTouch', 'ui.grid', 'ui.grid.selection', 'ui.grid.exporter','ui.grid.edit', 'ui.grid.moveColumns', 'ui.grid.pagination'])

  
  .controller('mainController',['$scope', function ($scope) {
    
    $scope.variablePrueba = "Giggio";
    //alert('entro');

  }])
  .controller('nuevoController',['$scope', function ($scope) {
    

  }])
  .controller('tabMaestrasController',['$scope', '$http', function ($scope, $http) {

     $scope.gridOptions = {
      paginationPageSizes: [10, 20, 30],
    paginationPageSize: 10,
    columnDefs: [
      { field: 'IDMAESTRO', displayName: 'ID Maestro'},
      { field: 'ETIQUETA', exporterPdfAlign: 'right' },
      { field: 'IDPARAMETRO', visible: false }
    ],
    exporterLinkLabel: 'get your csv here',
    exporterPdfDefaultStyle: {fontSize: 9},
    exporterPdfTableStyle: {margin: [30, 30, 30, 30]},
    exporterPdfTableHeaderStyle: {fontSize: 10, bold: true, italics: true, color: 'red'},
    exporterPdfOrientation: 'portrait',
    exporterPdfPageSize: 'LETTER',
    exporterPdfMaxGridWidth: 500,
    exporterHeaderFilter: function( displayName ) { 
      if( displayName === 'Name' ) { 
        return 'Person Name'; 
      } else { 
        return displayName;
      } 
    },
    onRegisterApi: function(gridApi){ 
      $scope.gridApi = gridApi;
    }
  };

      $scope.export = function(){
    if ($scope.export_format == 'csv') {
      var myElement = angular.element(document.querySelectorAll(".custom-csv-link-location"));
      $scope.gridApi.exporter.csvExport( $scope.export_row_type, $scope.export_column_type, myElement );
    } else if ($scope.export_format == 'pdf') {
      $scope.gridApi.exporter.pdfExport( $scope.export_row_type, $scope.export_column_type );
    };
  };
    $scope.getEtiquetas= function(){
         $http.post('api/getEtiqueta.php' )
                  .success(function(data) {
                    console.log(data);
                    $scope.etiquetas=data;
                    $scope.gridOptions.data = data;
                  })
                  .error(function(data) {
                    console.log('Error: ' + data);
                    });
    };
       
  $scope.getEtiquetas();

    $scope.formNewEtiqueta = function(et){
       $http.post('api/addEtiqueta.php', { et: et } )
              .success(function(data) {
                console.log(data);
                alert("Registro de Etiqueta Exitoso");
                //falta verificar si esta ya en la base de datos y se esta repitiendo
                console.log(et);
                $("#inputEtiqueta").val("");
                $scope.getEtiquetas();

              })
              .error(function(data) {
                //console.log('Error: ' + data);
                console.log(data);
                alert("ocurrio un error con el servidor")
                });

    }

  }])
  .controller('exportarController',['$scope', function ($scope) {
    


  }])
  .controller('cargaMasivaController',['$scope', function ($scope) {
    

  }])

  
})();
