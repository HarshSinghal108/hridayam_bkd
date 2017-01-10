(function(){
  var app = angular.module('bkd', [ ]);

  app.controller('Customer', function($scope, $http) {

    $scope.user={};

    $scope.addCustomer=function(){
      $scope.data={
        'email':$scope.user.email,
        'first_name':$scope.user.fname,
        'last_name':$scope.user.lname,
        'mobile':$scope.user.mobile,
        'telephone':$scope.user.telephone,
        'gender':$scope.user.gender,
        'address':$scope.user.address,
        'city':$scope.user.city,
        'district':$scope.user.district,
        'state':$scope.user.state,
        'country':$scope.user.country,
        'pincode':$scope.user.pincode,
        'landmark':$scope.user.landmark,
        'dob':$scope.user.dob,
        'dom':$scope.user.anvr
      };

      // console.log(JSON.stringify($scope.data));

      $http({
        method  : 'POST',
        url     : "http://localhost/bkd/backend/index.php/user/add_user",
        data    : $scope.data, //forms user object
        headers : {'Content-Type': 'application/x-www-form-urlencoded'}
       }).then(function mySucces(response) {
        //  console.log(JSON.stringify(response));
         if(response.data.status==true){
           $scope.userId=response.data.data;
           console.log("id"+response.data.data);
         }
         else {
           console.error(response);
         }
       }, function myError(response) {
         console.error(response);
       });
    };
  });


  app.controller('Feedback', function($scope, $http) {

    $scope.user={};

    $http({
      method  : 'POST',
      url     : "http://localhost/bkd/backend/index.php/user/is_logged_in",
      data    : $scope.data, //forms user object
      headers : {'Content-Type': 'application/x-www-form-urlencoded'}
     }).then(function mySucces(response) {
      //  console.log(JSON.stringify(response));
       if(response.data.status==true){
         $scope.userId=response.data.data;
         console.log("id"+response.data.data);
       }
       else {
         $scope.userId=response.data.data;
       }
     }, function myError(response) {
       console.error(response);
     });


    $scope.addFeedback=function(){
      $scope.data={
        'user_id':$scope.userId,
        'shopping_medium':$scope.medium,
        'shopping_schedule':$scope.schedule,
        'feedback':$scope.feedback,
        'suggestion':$scope.suggestion,
        };

      $http({
        method  : 'POST',
        url     : "http://localhost/bkd/backend/index.php/user/add_user_feedback",
        data    : $scope.data, //forms user object
        headers : {'Content-Type': 'application/x-www-form-urlencoded'}
       }).then(function mySucces(response) {
        //  console.log(JSON.stringify(response));
         if(response.data.status==true){
           $scope.userId=response.data.data;
           console.log("id"+response.data.data);
         }
         else {
           console.error(response);
         }
       }, function myError(response) {
         console.error(response);
       });
    };
  });

  })();
