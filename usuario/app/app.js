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
          templateUrl: 'views/proyectos.html',
          caseInsensitiveMatch: true,
          controller: 'proyectosController'
        })
        .when('/nuevo', {
          templateUrl: 'views/nuevo.html',
          caseInsensitiveMatch: true,
          controller: 'nuevoController'
        })
          .when('/proyectos', {
          templateUrl: 'views/proyectos.html',
          caseInsensitiveMatch: true,
          controller: 'proyectosController'
        })
        .when('/editar', {
          templateUrl: 'views/editar.html',
          caseInsensitiveMatch: true,
          controller: 'editarController'
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