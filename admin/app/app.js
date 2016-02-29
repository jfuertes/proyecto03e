(function(){

'use strict';

var app = angular.module('entelApp', [
  'ngRoute',
  'ngAnimate',
  'datatables',
  'datatables.bootstrap',
  'datatables.buttons',
  'ngCsvImport',
  'Controllers']);

    app.config(['$routeProvider', function($routeProvider){
      $routeProvider.
        when('/', {
          redirectTo: '/proyMacro'
        })
        .when('/nuevo', {
          templateUrl: 'views/nuevo.html',
          caseInsensitiveMatch: true,
          controller: 'nuevoController'
        })
        .when('/tabMaestras', {
          templateUrl: 'views/tablasMaestras.html',
          caseInsensitiveMatch: true,
          controller: 'tabMaestrasController'
        })
        .when('/proyMacro', {
          templateUrl: 'views/proyMacro.html',
          caseInsensitiveMatch: true,
          controller: 'proyMacroController'
        })
        .when('/parametros', {
          templateUrl: 'views/parametros.html',
          caseInsensitiveMatch: true,
          controller: 'parametrosController'
        })
        .otherwise({
          redirectTo: '/'
        });

      }])
})();