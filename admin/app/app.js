(function(){

'use strict';

var app = angular.module('entelApp', [
  'ngRoute',
  'ngAnimate',
  'Controllers']);

    app.config(['$routeProvider', function($routeProvider){
      $routeProvider.
        when('/', {
          templateUrl: 'views/home.html',
          caseInsensitiveMatch: true,
          controller: 'mainController'
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
        .when('/exportar', {
          templateUrl: 'views/exportar.html',
          caseInsensitiveMatch: true,
          controller: 'exportarController'
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