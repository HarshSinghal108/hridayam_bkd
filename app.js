(function(){
  var app = angular.module('bkd', [ 'naif.base64']);


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
          bootbox.confirm("Customer Added Successfully !", function(result){
            window.location.href = 'http://www.baniyekidukaan.in/feedback.html';
           });
        }
        else {
          bootbox.alert(response.data.msg);

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
        // bootbox.alert("Success");
      }
      else {
        $scope.loggedIn=0;
      bootbox.confirm("Need to add Customer First!", function(result){
        window.location.href = 'http://www.baniyekidukaan.in/customer.html';
       });
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
          $scope.userId="";
          $scope.medium="";
          $scope.schedule="";
          $scope.feedback="";
          $scope.suggestion="";
          bootbox.confirm("Feedback Submited!", function(result){
            window.location.href = 'http://www.baniyekidukaan.in/Survey.html';
           });
        }
        else {
          bootbox.alert(response.data.msg);
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

    $http({
      method  : 'POST',
      url     : "http://www.baniyekidukaan.in/backend/index.php/user/is_user_loggedin",
      data    : $scope.data, //forms user object
      headers : {'Content-Type': 'application/x-www-form-urlencoded'}
    }).then(function mySucces(response) {
      //  console.log(JSON.stringify(response));
      if(response.data.status==true){

      }
      else {
        $scope.loggedIn=0;
      bootbox.confirm("Need to add Customer First!", function(result){
        window.location.href = 'http://www.baniyekidukaan.in/customer.html';
       });
     }
   });


    $scope.addSurvey=function(){
      $scope.data={
        'product_id':document.getElementsByName('product_id')[0].value,
        'quantity':$scope.quantity,
        'brand':$scope.brand,
        'size':$scope.size
      };
      console.log('hi'+$scope.data);
      $http({
        method  : 'POST',
        url     : "http://www.baniyekidukaan.in/backend/index.php/user/add_survey",
        data    : $scope.data, //forms user object
        headers : {'Content-Type': 'application/x-www-form-urlencoded'}
      }).then(function mySucces(response) {
        if(response.data.status==true){
          $("#showProduct").hide();
          bootbox.alert("Details Submitted !!");
            }
        else {
          bootbox.alert(response.data.msg);
        }
      }, function myError(response) {
        console.error(response);
      });
    };

    $scope.showProduct=function(){
      alert('hi');
      console.log('hi');
    }


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

    $scope.addImage=function(){
      $scope.imageStrings = [];
      $scope.processFiles = function(files){
        angular.forEach(files, function(flowFile, i){
          var fileReader = new FileReader();
          fileReader.onload = function (event) {
            var uri = event.target.result;
            $scope.imageStrings[i] = uri;
          };
          fileReader.readAsDataURL(flowFile.file);
        });
      };
  }
  $scope.notInclude=[];

  $scope.addProduct=function(){
    var j=0;
    $scope.data={'details':[],'product_name':'','category_id':''};
    for (var i = 0; i < $scope.number; i++) {
      if(!$scope.notInclude.includes(i)){
      $scope.data.details[j]={
        'weight':document.getElementsByName('weight'+i)[0].value,
        'price':document.getElementsByName('price'+i)[0].value,
        'packing':document.getElementsByName('packing'+i)[0].value,
        'piece':document.getElementsByName('piece'+i)[0].value,
        'type':document.getElementsByName('type'+i)[0].value,
      }
      j++;
    }
    }
    $scope.data.product_name=document.getElementsByName('product_name')[0].value;
    $scope.data.product_image='data:'+$scope.productImage.filetype+';base64,'+$scope.productImage.base64;
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
        $("#myModal2").modal('hide');
        bootbox.alert(document.getElementsByName('product_name')[0].value+ " Added ");
      }
      else {
        bootbox.alert(response.data.msg);
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
        bootbox.alert(response.data.msg);
      }
    }, function myError(response) {
      console.error(response);
    });
  }

  $scope.editProduct=function(){
    var j=0;
    $scope.data={'details':[],'product_name':'','product_id':''};
    for (var i = 0; i < $scope.subProduct.length; i++) {
      if($scope.subProduct[i].bsp_weight){
        console.log($scope.subProduct[i]);

        $scope.data.details[j]={
          'weight':document.getElementsByName('editweight'+i)[0].value,
          'price':document.getElementsByName('editprice'+i)[0].value,
          'packing':document.getElementsByName('editpacking'+i)[0].value,
          'piece':document.getElementsByName('editpiece'+i)[0].value,
          'type':document.getElementsByName('edittype'+i)[0].value,
        }
        j++;
      }
    }
    $scope.data.product_name=document.getElementsByName('editproduct_name')[0].value;
    // 'product_name':,
    $scope.data.product_image=$scope.productImage;
    $scope.data.product_id=localStorage.getItem("productId")
    console.log($scope.data);
    $http({
      method  : 'POST',
      url     : "http://www.baniyekidukaan.in/backend/index.php/product/edit_product",
      data    : $scope.data, //forms user object
      headers : {'Content-Type': 'application/x-www-form-urlencoded'}
    }).then(function mySucces(response) {
      if(response.data.status==true){
        $scope.subProduct=[];
        $scope.product_name="";
        $("#myModal3").modal('hide');
        bootbox.alert("Product Edited");
      }
      else {
        bootbox.alert(response.data.msg);
      }
    }, function myError(response) {
      console.error(response);
    });
  }

  $scope.editCategory=function(){
    $scope.data={};
    $scope.data.category_id=localStorage.getItem("categoryId");
    $scope.data.category_name=document.getElementsByName('categoryName')[0].value

    console.log($scope.data);
    $http({
      method  : 'POST',
      url     : "http://www.baniyekidukaan.in/backend/index.php/category/edit_category",
      data    : $scope.data, //forms user object
      headers : {'Content-Type': 'application/x-www-form-urlencoded'}
    }).then(function mySucces(response) {
      if(response.data.status==true){
        $scope.subProduct=[];
        $scope.product_name="";
        $("#myModal4").modal('hide');
        getCategory();

      }
      else {
        bootbox.alert(response.data.msg);
      }
    }, function myError(response) {
      console.error(response);
    });
  }

  $scope.setCategory=function(categoryId){
    $scope.category=categoryId;
    console.log('shubh');
  }

  $scope.delete_row_edit=function(id){
    $scope.subProduct[id]={};
    $("#"+id).hide();
  }

  $scope.delete_row_add=function(id){
    var weight=weight+id;
    $scope.notInclude.push(id);
    $("#"+id).hide();
  }



});



})();
