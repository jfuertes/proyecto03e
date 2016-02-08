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
        }).
        when('/vacunas', {
          templateUrl: 'views/vacunas.html',
          caseInsensitiveMatch: true,
          controller: 'VacunasController'
        }).
        
        otherwise({
          redirectTo: '/'
        });

      }])

})();