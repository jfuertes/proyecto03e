(function(){

'use strict';

var app = angular.module('entelApp', [
  'ngRoute',
  'ngAnimate',
   'ngTouch', 'ui.grid',
    'ui.grid.selection', 'ui.grid.exporter',
     'ui.grid.moveColumns', 'ui.grid.pagination',
      'ui.grid.edit',
      'datatables',
  'datatables.bootstrap',
  'datatables.buttons',
  'Controllers']);

    app.config(['$routeProvider', function($routeProvider){
      $routeProvider.
        when('/', {
          templateUrl: 'views/home.html',
          caseInsensitiveMatch: true,
          controller: 'mainController'
        })
        .when('/proymacro', {
          templateUrl: 'views/proymacro.html',
          caseInsensitiveMatch: true,
          controller: 'proymacroController'
        })
        .when('/tabMaestras', {
          templateUrl: 'views/tablasMaestras.html',
          caseInsensitiveMatch: true,
          controller: 'tabMaestrasController'
        })
   
        .when('/carga_masiva', {
          templateUrl: 'views/carga_masiva.html',
          caseInsensitiveMatch: true,
          controller: 'cargaMasivaController'
        })
        .otherwise({
          redirectTo: '/'
        });

      }])
})();