angular.module('adminapp',['ngRoute','ui.bootstrap'])
.config(['$routeProvider', function($routeProvider) {
   $routeProvider.
   when('/proymacro', {
      templateUrl: 'templates/proymacro.html',caseInsensitiveMatch: true, controller: 'proymacroctrl'
   }).   
   when('/newproymacro', {
      templateUrl: 'templates/newproymacro.html', caseInsensitiveMatch: true, controller: 'newproymacroctrl'
   }).
   when('/parametros', {
      templateUrl: 'templates/parametro.html', caseInsensitiveMatch: true, controller: 'parametroctrl'
   }).
   otherwise({
          redirectTo: '/proymacro'
        });
	
}])
.controller('proymacroctrl',['$scope','$http','$uibModal', function($scope,$http,$uibModal) {
    $scope.lstpm=[];
    
    $scope.listarPm = function(){
               $http({
                   method: 'POST',
                   url:'php/controlador.php?opc=list&class=proymacro',
                   data:{}
               })
               .success(function(result){
                   $scope.lstpm = result;
                   console.log('Consultado');
               })
               .error(function(result) {
                    console.log('Error: ' + result);
                });
           };       
    $scope.listarPm();
    
    $scope.abrirPM=function(){
        
    };
    
        }])   
.controller('newproymacroctrl',['$scope',function($scope) {
    $scope.showModal = false;
    
    }])    
.controller('parametroctrl',['$scope','$http',function($scope,$http) {
    $scope.lstpm=[];
    $scope.lstmodulo=[];
    $scope.lstparametro=[];
    $scope.listarPm = function(){
               $http({
                   method: 'POST',
                   url:'php/controlador.php?opc=list&class=proymacro',
                   data:{}
               })
               .success(function(result){
                   $scope.lstpm = result;
                   console.log('Consultado');
               })
               .error(function(result) {
                    console.log('Error: ' + result);
                });
           };       
    $scope.listarPm();
    
    $scope.listarModulos = function(){
             $http({
                     method:'GET',
                     url: 'php/controlador.php?opc=list&class=mod',
                     data : {}
                 })
                .success(function(result){
                    $scope.lstmodulo = result;
                    console.log('Consultado modulos');
                })
                .error(function(result) {
                    console.log('Error al consultar modulos: ' + result);
                });
           };
    $scope.listarModulos();
    
    $scope.filtrarParametros = function(){
               $http({
                   method : 'GET',
                   url:'php/controlador.php?opc=list&class=param&idproymacro='+$scope.cboPmvalue+'&idmodulo='+$scope.cboModulo,
                   data: {}
               })
                .success(function(result){                    
                    $scope.lstparametro = result;
                    console.log('Consultado Parametros');
                })
                .error(function(result) {
                    console.log('Error al consultar parametros: ' + result);
                });
           };
    
    }]);    
 