angular.module('main',[])
.controller('ControllerLogin',['$scope','$http',function($scope,$http){
    this.postForm = function(){
        var encodedString = 'login='+encodeURIComponent(this.inputData.login)+"&clave="+encodeURIComponent(this.inputData.clave);        
        console.log(encodedString);
        $http(
              {
                  method: 'POST',
                  url: 'php/check-login.php',
                  data: encodedString,
                  headers: {'Content-Type':'application/x-www-form-urlencoded'}
              }              
            )
            .success(function(data,status,headers,config){
                console.log(data);                
                if(data.acceso === 'true'){
                    window.location.href = data.url;
                }else{
                    $scope.errorMsg = 'Login incorrecto';
                }
            })
            .error(function(data,status,headers,config){
                console.log(status);
                $scope.errorMsg = 'No se pudo enviar la solicitud';
            })
    };
}])
.controller('ctrlPm',['$scope','$http','$window',function($scope,$http,$window){
           $scope.lstpm=[];
           $scope.lstmodulo=[];
           $scope.lstparametro=[];
           $scope.lstproyecto=[];
           $scope.urlexp="";
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
                     url: 'php/controlador.php?opc=list&class=modxpm&idproymacro='+$scope.cboPmvalue,
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
           
           $scope.filtrarValores = function(){
               $http({
                   method : 'GET',
                   url:'php/controlador.php?opc=list&class=proy&idproymacro='+$scope.cboPmvalue+'&idmodulo='+$scope.cboModulo,
                   data: {}
               })
                .success(function(result){                    
                    $scope.lstproyecto = result;
                    console.log('Consultado Proyectos');
                })
                .error(function(result) {
                    console.log('Error al consultar Proyectos: ' + result);
                });
           };
           
           
           $scope.filtrarProyectos = function(){
               $scope.filtrarParametros();
               $scope.filtrarValores();
           };
           
           $scope.setSelected = function() {
                for(i=0;i<this.lstproyecto.length;i++){
                    this.lstproyecto[i].selected=false;
                }
                this.proy.selected=true;
                $scope.selected = this.proy;
                console.log($scope.selected);
           };
           
           $scope.cmdNuevoProyecto = function(){    
               if(typeof $scope.cboPmvalue != 'undefined'){
                window.location.href = 'php/proyecto.php?idproymacro='+$scope.cboPmvalue+'&opc=new';
               }
           };
           
           $scope.cmdEditarProyecto=function(){
               if($scope.selected!=null){
                    window.location.href = 'php/proyecto.php?idproy='+$scope.selected.IDPROYECTO+'&opc=edit';
               }else{
                    $scope.errorMsg="Debe seleccionar un proyecto";
               }
           };
           
           
           $scope.cmdpreExportar=function(){
               if($scope.cboPmvalue != '' && $scope.cboModulo!=''){
                $window.open('php/exportar.php?idproymacro='+$scope.cboPmvalue+'&idmodulo='+$scope.cboModulo, '_blank');
               }
           };
           
           $scope.cmdExportar = function(){
               $http({
                   method : 'GET',
                   url:'php/exportar.php?idproymacro='+$scope.cboPmvalue+'&idmodulo='+$scope.cboModulo,
                   data: {}
               })
                .success(function(result){                    
                    $scope.urlexp = result;
                    console.log('Exportando resultados a XLS');
                })
                .error(function(result) {
                    console.log('Error al exportar: ' + result);
                });
           };
           
        }
]);

