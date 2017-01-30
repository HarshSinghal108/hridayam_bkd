(function(){
  var app = angular.module('bkd', [ ]);


  app.controller('Customer', function($scope, $http) {

    $scope.user={};

    $scope.addCustomer=function(){
      $scope.data={
        'email':$scope.user.email,
        'salutation':$scope.user.salutation,
        'first_name':$scope.user.fname,
        'last_name':$scope.user.lname,
        'mobile':$scope.user.mobile,
        'telephone':$scope.user.telephone,
        'gender':$scope.user.gender,
        'address':$scope.user.buildingType+","+$scope.user.number+","+$scope.user.address,
        'city':$scope.user.city,
        'district':$scope.user.district,
        'state':$scope.user.state,
        'country':$scope.user.country,
        'pincode':$scope.user.pincode,
        'landmark':$scope.user.landmark,
        'dob':$scope.user.dob,
        'dom':$scope.user.anvr
      };

      console.log(JSON.stringify($scope.data));

      $http({
        method  : 'POST',
        url     : "http://www.baniyekidukaan.in/backend/index.php/user/add_user",
        data    : $scope.data, //forms user object
        headers : {'Content-Type': 'application/x-www-form-urlencoded'}
      }).then(function mySucces(response) {
        //  console.log(JSON.stringify(response));
        if(response.data.status==true){
          alert('success');
        }
        else {
          alert(response.data.msg);
        }
      }, function myError(response) {
        console.error(response);
      });
    };
  });


  app.controller('Feedback', function($scope, $http) {

    $scope.user={};
    $scope.loggedIn=0;
    $scope.customer={};
    $http({
      method  : 'POST',
      url     : "http://www.baniyekidukaan.in/backend/index.php/user/is_user_loggedin",
      data    : $scope.data, //forms user object
      headers : {'Content-Type': 'application/x-www-form-urlencoded'}
    }).then(function mySucces(response) {
      //  console.log(JSON.stringify(response));
      if(response.data.status==true){
        $scope.loggedIn=1;
        console.log($scope.loggedIn);
        $scope.customer.customerId=response.data.data.user_id;
        $scope.customer.referalCode=response.data.data.user_referal_code;
        alert('success');
      }
      else {
        $scope.loggedIn=0;
        alert(response.data.msg);
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
        url     : "http://www.baniyekidukaan.in/backend/index.php/user/add_user_feedback",
        data    : $scope.data, //forms user object
        headers : {'Content-Type': 'application/x-www-form-urlencoded'}
      }).then(function mySucces(response) {
        //  console.log(JSON.stringify(response));
        if(response.data.status==true){
          alert('success');
        }
        else {
          alert(response.data.msg);
        }

      }, function myError(response) {
        console.error(response);
      });
    };


    $scope.isLoggedIn=function() {
      return $scope.loggedIn;
    }
  });



  app.controller('Survey', function($scope, $http) {

    $scope.survey={};
    $scope.addSurvey=function(){
      $scope.data={
        'product_id':document.getElementsByName('product_id')[0].value,
        'quantity':$scope.quantity,
        'brand':$scope.brand,
        'size':$scope.size
      };

      $http({
        method  : 'POST',
        url     : "http://www.baniyekidukaan.in/backend/index.php/user/add_survey",
        data    : $scope.data, //forms user object
        headers : {'Content-Type': 'application/x-www-form-urlencoded'}
      }).then(function mySucces(response) {
        if(response.data.status==true){
          alert('success');
        }
        else {
          alert(response.data.msg);
        }
      }, function myError(response) {
        console.error(response);
      });
    };
  });



  app.controller('add_product', function($scope, $http) {
    $scope.number=1;
    $scope.getNumber=function(num){
      return new Array(num);
    }

    $scope.increase_count=function(){
      $scope.number=$scope.number+1;
    }

    $scope.increase_array_count=function(){
      $scope.subProduct[$scope.subProduct.length]=[];
    }


    $scope.addProduct=function(){
      $scope.data={'details':[],'product_name':'','category_id':''};
      for (var i = 0; i < $scope.number; i++) {
        $scope.data.details[i]={
          'weight':document.getElementsByName('weight'+i)[0].value,
          'price':document.getElementsByName('price'+i)[0].value,
          'packing':document.getElementsByName('packing'+i)[0].value,
          'piece':document.getElementsByName('piece'+i)[0].value,
          'type':document.getElementsByName('type'+i)[0].value,
        }
        console.log(JSON.stringify($scope.data));

      }
      $scope.data.product_name=document.getElementsByName('product_name')[0].value;
      // 'product_name':,
      $scope.data.category_id=localStorage.getItem("categoryId")
      console.log(JSON.stringify($scope.data));
      $http({
        method  : 'POST',
        url     : "http://www.baniyekidukaan.in/backend/index.php/product/add_product",
        data    : $scope.data, //forms user object
        headers : {'Content-Type': 'application/x-www-form-urlencoded'}
      }).then(function mySucces(response) {
        if(response.data.status==true){
          alert('success');
          $("#myModal2").modal('hide');
        }
        else {
          alert(response.data.msg);
        }
      }, function myError(response) {
        console.error(response);
      });
    }

    $scope.getProduct=function(){
      $scope.subProduct=[];
      $scope.product_name;
      $scope.productId=localStorage.getItem("productId")
      $http({
        method  : 'POST',
        url     : "http://www.baniyekidukaan.in/backend/index.php/product/get_product_details/"+$scope.productId,
        data    : $scope.data, //forms user object
        headers : {'Content-Type': 'application/x-www-form-urlencoded'}
      }).then(function mySucces(response) {
        if(response.data.status==true){
           $scope.subProduct=response.data.data;
           $scope.product_name=$scope.subProduct[0].product_name;
           $scope.number=$scope.subProduct.length;
           console.log($scope.subProduct[0].product_name);
        }
        else {
          alert(response.data.msg);
        }
      }, function myError(response) {
        console.error(response);
      });
    }

    $scope.editProduct=function(){
      $scope.data={'details':[],'product_name':'','product_id':''};
      for (var i = 0; i < $scope.subProduct.length; i++) {
        $scope.data.details[i]={
          'weight':document.getElementsByName('editweight'+i)[0].value,
          'price':document.getElementsByName('editprice'+i)[0].value,
          'packing':document.getElementsByName('editpacking'+i)[0].value,
          'piece':document.getElementsByName('editpiece'+i)[0].value,
          'type':document.getElementsByName('edittype'+i)[0].value,
        }
        }
      $scope.data.product_name=document.getElementsByName('editproduct_name')[0].value;
      // 'product_name':,
      $scope.data.product_id=localStorage.getItem("productId")
      console.log($scope.data);
      $http({
        method  : 'POST',
        url     : "http://www.baniyekidukaan.in/backend/index.php/product/edit_product",
        data    : $scope.data, //forms user object
        headers : {'Content-Type': 'application/x-www-form-urlencoded'}
      }).then(function mySucces(response) {
        if(response.data.status==true){
          alert('success');
          $scope.subProduct=[];
          $scope.product_name="";
          $("#myModal3").modal('hide');

        }
        else {
          alert(response.data.msg);
        }
      }, function myError(response) {
        console.error(response);
      });
    }


    $scope.setCategory=function(categoryId){
      $scope.category=categoryId;
      console.log('shubh');
    }

  });



})();
