 /**
 * List Controller
 * @version v1.0.0 - 2016-02-08 * @link http://www.mindtec.pe
 * @author MindTec <contacto@mindtec.pe>
 * @license MIT License, http://www.opensource.org/licenses/MIT
 */
(function(){
  'use strict';

  angular.module('Controllers', ['ngAnimate', 'ngTouch', 'ui.grid', 'ui.grid.selection', 'ui.grid.exporter', 'ui.grid.moveColumns'])

  
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


     $scope.gridOptions = {
    columnDefs: [
      { field: 'name' },
      { field: 'gender', exporterPdfAlign: 'right' },
      { field: 'company', visible: false }
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
    exporterFieldCallback: function( grid, row, col, input ) {
      if( col.name == 'gender' ){
        switch( input ){
          case 1:
            return 'female';
            break;
          case 2:
            return 'male';
            break;
          default:
            return 'unknown';
            break;
        }
      } else {
        return input;
      }
    },
    onRegisterApi: function(gridApi){ 
      $scope.gridApi = gridApi;
    }
  };
   $http.get('https://cdn.rawgit.com/angular-ui/ui-grid.info/gh-pages/data/100.json')
    .success(function(data) {
      data.forEach( function( row, index ) {
        if( row.gender === 'female' ){
          row.gender = 1;
        } else {
          row.gender = 2;
        }
      });
      $scope.gridOptions.data = data;
      //$scope.gridOptions.datas = data;
    });
      $scope.export = function(){
    if ($scope.export_format == 'csv') {
      var myElement = angular.element(document.querySelectorAll(".custom-csv-link-location"));
      $scope.gridApi.exporter.csvExport( $scope.export_row_type, $scope.export_column_type, myElement );
    } else if ($scope.export_format == 'pdf') {
      $scope.gridApi.exporter.pdfExport( $scope.export_row_type, $scope.export_column_type );
    };
  };



    $http.post('api/getEtiqueta.php' )
              .success(function(data) {

                console.log(data);
               // console.log(data[0].IDMAESTRO);
                $scope.etiquetas=data;

                //$scope.gridOptions.datas
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
