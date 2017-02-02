var basePath = "http://www.baniyekidukaan.in/backend/index.php";

var callAjax=function(url,type,dataObj,callback)
{
	$(".loader1").show();
	debugger;
    var jsonData='';
   if(dataObj!=''){
           jsonData = JSON.stringify(dataObj);
   }
   if(basePath != "")
       url = basePath + "/"+url;
      //  console.log(url);
      //  console.log("...................");
			$.ajax({

				type: type,
				url: url,
				data: jsonData,
			   cache: false,


        beforeSend: function (XMLHttpRequest) {

				},
				success: function(result)
				{
					// console.log();
						  //Validate Response
						 // var parseJson = $.parseJSON(result);
							  callback(result);





	}
	});
};
