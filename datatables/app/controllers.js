 /**
 * List Controller
 * @version v1.0.0 - 2016-02-08 * @link http://www.mindtec.pe
 * @author MindTec <contacto@mindtec.pe>
 * @license MIT License, http://www.opensource.org/licenses/MIT
 */
(function(){
  'use strict';

angular.module('Controllers', ['datatables']).controller('WithOptionsCtrl', WithOptionsCtrl);

function WithOptionsCtrl(DTOptionsBuilder, DTColumnDefBuilder) {
    var vm = this;
    vm.dtOptions = DTOptionsBuilder.newOptions()
        .withPaginationType('full_numbers')
        .withDisplayLength(2)
        .withDOM('pitrfl');
    vm.dtColumnDefs = [
        DTColumnDefBuilder.newColumnDef(0),
        DTColumnDefBuilder.newColumnDef(1).notVisible(),
        DTColumnDefBuilder.newColumnDef(2).notSortable()
    ];

vm.persons = [{
    "id": 860,
    "firstName": "Superman",
    "lastName": "Yoda"
}, {
    "id": 870,
    "firstName": "Foo",
    "lastName": "Whateveryournameis"
}, {
    "id": 590,
    "firstName": "Toto",
    "lastName": "Titi"
}, {
    "id": 803,
    "firstName": "Luke",
    "lastName": "Kyle"
}];
}







  /*angular.module('Controllers', ['datatables'])

  
  .controller('mainController',['$scope', function ($scope) {
    

  }])
  .controller('proyMacroController',['$scope', function ($scope, DTOptionsBuilder, DTColumnDefBuilder) {
      var vm = this;
      vm.dtOptions = DTOptionsBuilder.newOptions()
          .withPaginationType('full_numbers')
          .withDisplayLength(2)
          .withDOM('pitrfl');
      vm.dtColumnDefs = [
          DTColumnDefBuilder.newColumnDef(0),
          DTColumnDefBuilder.newColumnDef(1).notVisible(),
          DTColumnDefBuilder.newColumnDef(2).notSortable()
      ];

  }])
  .controller('tabMaestrasController',['$scope', '$http', function ($scope, $http) {


  }])
  .controller('exportarController',['$scope', function ($scope) {
    


  }])
  .controller('cargaMasivaController',['$scope', function ($scope) {
    

  }])*/

  
})();
