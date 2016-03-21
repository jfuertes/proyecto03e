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
          templateUrl: 'views/proyectos2.html',
          caseInsensitiveMatch: true,
          controller: 'proyectos2Controller'
        })
          /*.when('/proyectos', {
          templateUrl: 'views/proyectos.html',
          caseInsensitiveMatch: true,
          controller: 'proyectosController'
        })*/
          .when('/proyectos2', {
          templateUrl: 'views/proyectos2.html',
          caseInsensitiveMatch: true,
          controller: 'proyectos2Controller'
        })
        .otherwise({
          redirectTo: '/'
        });

      }])
})();