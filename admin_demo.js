var sub_category;
var sub_category_list;
var product_category_id;

$('.parent').click(function(){
        $('.sub-nav').toggle();
    });



	function add_sub_category(sub_category1)
	{
		debugger;
    sub_category = sub_category1;

	$('#myModal').modal('show');
	}

	//add category

   function add_category(category)
   {
   debugger;
  // alert(category);


   if(category == "main_categoty")
   {
	 var category_parent_id = 0;
	 var category_name = $("#category_name").val();
   }
   else
   {
	var category_parent_id = sub_category;
	var category_name = $("#sub_category_name").val();

   }



   dataObj={ "category_name": category_name ,
        "category_parent_id": category_parent_id
		}

	callAjax('category/add_category', 'POST', dataObj, added_category );
	}


	//display category

	function added_category(result)
	{
		//alert(result);
		$("#myModal1").modal('hide');
		$("#myModal").modal('hide');
		$("#myModal2").modal('hide');
	     getCategory();

	}


	//get list of category
	function getCategory()
	{
    $("#showProduct").hide();
		$("#loader").show();
	$('#category').html("");
	debugger;
	dataObj={
           "category_id": 0}

	callAjax('category/list_category', 'POST', dataObj, get_category );
	}


	//display list of category

	function get_category(result)
	{
		//alert(result);
	var parseJson = $.parseJSON(result);
	if(parseJson.status)
	{

	debugger;

	var count = parseJson.data.category.length;
	for(var i= 0; i < count; i += 1)
	{

	var sub_id = parseJson.data.category[i].category_name + parseJson.data.category[i].category_id;



	$('#category').append('<a class="list-group-item" ><span onclick="getSubCategory(\'' + sub_id + '\',\'' + parseJson.data.category[i].category_id + '\')" data-toggle="collapse" data-target="#'+sub_id+'" data-parent="#menu" class="maincategory">'+parseJson.data.category[i].category_name+'</span><i title="Add Product" onclick="product_add_modal(\'' + parseJson.data.category[i].category_id + '\')" class="fa fa-plus tooltip_category" aria-hidden="true"></i><i title="Add Category" onclick="add_sub_category(\'' + parseJson.data.category[i].category_id + '\')" class="fa fa-plus tooltip_category" aria-hidden="true"></i></a><div id='+sub_id+' class="sublinks collapse"><a class="list-group-item small"></a>')



	}
	$("#loader").hide();
	}
	else
	{
		//alert(parseJson.msg);
	}
	}



//Get sub category of a category
	function getSubCategory(category_div,category_id)
	{
		debugger;
		//$("#loader").show();
		sub_category_list = category_div;

		//$("#"+category_div).slideToggle();

       dataObj={
           "category_id": category_id
		   }

	callAjax('category/list_category', 'POST', dataObj, get_category2 );







	}

  function showProduct(product_id,product_name) {
    $("#showProduct").show();
    $("#productName").html("<h2>"+product_name+"<h2>")
    $('input[name="product_id"]').val(product_id);
  }

//get sub category
	function get_category2(result)
	{
		debugger
		//alert(result);
		var parseJson = $.parseJSON(result);

	if(parseJson.status)
	{
		$("#"+sub_category_list).html("");

		if(parseJson.data.product.length != 0)
		{
		var count = parseJson.data.product.length;
		for(var j= 0; j < count; j += 1)
	    {


		var sub_id_1 = parseJson.data.product[j].product_name + parseJson.data.product[j].product_id;


		 $("#"+sub_category_list).append("<a title='Product' class='list-group-item small'><i class='fa fa-support sup'></i><span class='subcategory'   onclick='getSubCategory(\"" + sub_id_1 + "\",\"" + parseJson.data.product[j].product_id + "\"); showProduct(\""+parseJson.data.product[j].product_id+"\",\""+parseJson.data.product[j].product_name+"\")'>"+parseJson.data.product[j].product_name+"</span></a>");


	    }
		}
		if(parseJson.data.category.length != 0)
		{
		var count =	parseJson.data.category.length;
		for(var j= 0; j < count; j += 1)
	    {


		var sub_id_1 = parseJson.data.category[j].category_name + parseJson.data.category[j].category_id;


		 $("#"+sub_category_list).append("<a title='Category' class='list-group-item small'><span class='subcategory' onclick='getSubCategory(\"" + sub_id_1 + "\",\"" + parseJson.data.category[j].category_id + "\"),togglefunction(\"" + sub_id_1 + "\")'><span  class='glyphicon glyphicon-chevron-right'></span><span>"+parseJson.data.category[j].category_name+"</span></span> <i title='Add Category'  class='fa fa-plus tooltip_category sub' onclick='add_sub_category(\"" + parseJson.data.category[j].category_id + "\")' aria-hidden='true'></i><i onclick='product_add_modal(\"" + parseJson.data.category[j].category_id + "\")' title='Add Product' class='fa fa-plus tooltip_category sub' aria-hidden='true'></i><div style='display:none' id="+sub_id_1+"></div></a>");

		// $("#"+sub_category_list).append("<ul id=''><li onclick='togglefunction(\""+sub_id_1+"\")'><span class='listcat1' onclick='getSubCategory(\"" + sub_id_1 + "\",\"" + parseJson.data[j].category_id + "\")'>"+parseJson.data[j].category_name+"</span><i title='Add Category'  class='fa fa-plus tooltip_category' onclick='add_sub_category(\"" + parseJson.data[j].category_id + "\")' aria-hidden='true'></i><i onclick='product_add_modal(\"" + parseJson.data[j].category_id + "\")' title='Add Product'  class='fa fa-plus tooltip_category' aria-hidden='true'></i></li><div  style='display:none;' id="+sub_id_1+"></div></ul>");
	    }

		}

   // $("#"+sub_category_list).slideToggle();
	//$("#loader").hide();

	}

	else

		{

		//alert(parseJson.msg);
		}
	}


	//toggle function
	function togglefunction(obj)
							   {
								   debugger;


								$("#"+obj).slideToggle();


							   }



//product add modal
function product_add_modal(obj)
{

		debugger;
    product_category_id = obj;

	$('#myModal2').modal('show');
}

	//add product

function add_product()

{



		var category_id = product_category_id;
	var product_name = $("#product_name").val();

	dataObj = {
        "product_name": product_name,
        "category_id": category_id

}

callAjax('product/add_product', 'POST', dataObj, added_category);

}


//get product list
function get_product(result)
{
//alert();
}