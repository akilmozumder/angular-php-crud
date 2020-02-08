<!DOCTYPE html>
<html>
 <head>
  <title>Angular-Crud-PHP</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <!-- <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.15/angular.min.js"></script> -->
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular-route.js"></script>
  <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-datatables/0.6.4/angular-datatables.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.19/css/dataTables.bootstrap.css">
 
 </head>
        <body ng-app="crudApp" ng-controller="crudController">
            <div class="container" ng-init="fetchData()">
                <div class="alert alert-success alert-dismissible" ng-show="success" >
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {{successMessage}}
                </div>
                <div align="right">
                    <button type="button" name="add_button" ng-click="addData()" class="btn btn-success">Add</button>
                </div>
                <div class="table-responsive" style="overflow-x:unset;">
                    <table datatable="ng" dt-options="vm.dtOptions" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="name in namesData track by $index">
                                <td>{{name.first_name}}</td>
                                <td>{{name.last_name}}</td>
                                <td><button type="button" ng-click="fetchSingleData(name.id)" class="btn btn-warning btn-xs">Edit</button>
                                <button type="button" ng-click="deleteData(name.id)" class="btn btn-danger btn-xs">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </body>
</html>

<!-- bootatrap modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="crudmodal">
 <div class="modal-dialog" role="document">
     <div class="modal-content">
     <form method="post" ng-submit="submitForm()">
         <div class="modal-header">
           
           <h4 class="modal-title">{{modalTitle}}</h4>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
         </div>
         <div class="modal-body">
            <div class="alert alert-danger alert-dismissible" ng-show="error" >
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {{errorMessage}}
            </div>
            <div class="form-group">
                <label>Enter First Name</label>
                <input type="text" name="first_name" ng-model="first_name" class="form-control" />
            </div>
            <div class="form-group">
                <label>Enter Last Name</label>
                <input type="text" name="last_name" ng-model="last_name" class="form-control" />
            </div>
         </div>
         <div class="modal-footer">
            <input type="hidden" name="hidden_id" value="{{hidden_id}}" />
            <input type="submit" name="submit" id="submit" class="btn btn-info" value="{{submit_button}}" />
          
           <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
         </form>
     </div>
   </div>
</div>
<!-- bootstrap modal end -->
<script>
var app = angular.module('crudApp', ['ngRoute']);
app.controller('crudController',function($scope,$http){

        $scope.success = false;
        $scope.error = false;

       $scope.fetchData = function(){
            $http.get('fetch_data.php').success(function(data){
                $scope.namesData = data;
            });
       };

       $scope.openModal = function(){
            var modal_popup = angular.element('#crudmodal');
            modal_popup.modal('show');
       };

       $scope.closeModal = function(){
           var modal_popup = angular.element('#crudmodal');
           modal_popup.modal('hide');
       };

       $scope.addData = function(){
           $scope.modalTitle = 'Add Data';
           $scope.submit_button = 'Insert';
           $scope.openModal();
       };

       $scope.submitForm = function(){
            $http({
                method:"POST",
                url:"insert.php",
                data:{
                    'first_name':$scope.first_name,'last_name':$scope.last_name,'action':$scope.submit_button,'id':$scope.hidden_id
                }

            }).success(function(data){
          
                if(data.error != ''){
                    $scope.success = false;
                    $scope.error = true;
                    $scope.errorMessage = data.error;
                }else{
                    $scope.success = true;
                    $scope.error = false;
                    $scope.successMessage = data.message;
                    $scope.form_data = {};
                    $scope.closeModal();
                    $scope.fetchData();
                }
            });
       };

       $scope.fetchSingleData = function(id){
            $http({
                method:"POST",
                url:"insert.php",
                data:{'id':id,'action':'fetch_single_data'}
            }).success(function(data){
                $scope.first_name = data.first_name;
                $scope.last_name = data.last_name;
                $scope.hidden_id = id;
                $scope.modalTitle = 'Edit Data';
                $scope.submit_button = 'Update';
                $scope.openModal();
            });
       };

       $scope.deleteData = function(id){
            if(confirm('Are you sure to delete this data?')){
                $http({
                    method:"POST",
                    url:"insert.php",
                    data:{'id':id,'action':'delete'}
                }).success(function(data){
                    $scope.success = true;
                    $scope.error = false;
                    $scope.successMessage = data.message;
                    $scope.fetchData();
                });
            }
       };


});

</script>