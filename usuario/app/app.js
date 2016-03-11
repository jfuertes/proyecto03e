(function(){

'use strict';

var app = angular.module('entelApp', [
  'ngRoute',
  'ngAnimate',
  'datatables',
  'datatables.bootstrap',
  'datatables.buttons',
  'ui.bootstrap',
  'ngCsvImport',
  'Controllers']);

    app.config(['$routeProvider', function($routeProvider){
      $routeProvider.
        when('/', {
          templateUrl: 'views/proyectos.html',
          caseInsensitiveMatch: true,
          controller: 'proyectosController'
        })
          .when('/proyectos', {
          templateUrl: 'views/proyectos.html',
          caseInsensitiveMatch: true,
          controller: 'proyectosController'
        })
        .otherwise({
          redirectTo: '/'
        });

      }])
})();