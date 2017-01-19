<?php
header('Access-Control-Allow-Origin: *');

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class product extends CI_CONTROLLER {


    public function __construct()
	{

		parent::__construct();
		$this->load->library('form_validation');
    	// $this->load->library('session');
        $this->input_arr=include('variables/user_variables.php');
        $this->load->model('general_model','gm',true);
        $this->load->model('product_model','pm',true);
        $this->load->model('category_model','cm',true);


	}

	public function add_product(){


		$data = file_get_contents("php://input");
		$data = json_decode($data, TRUE);

		if(isset($data['product_name']) && isset($data['category_id']))
		{


			$cat_where=array('category_id'=>$data['category_id']);
			$cat=$this->cm->get_category($cat_where);

			if(sizeof($cat)==0)
			{
				$this->gm->send_response(false,'Category_Not_Exists','','');
			}

			$prod_where=array('product_name'=>$data['product_name'],'product_subcategory_id'=>$data['category_id']);
			$flag=$this->pm->check_product_exists($prod_where);

			if($flag)
			{
				$this->gm->send_response(false,'Already_Exists','','');
			}
			else
			{
				$prod_data=array('product_name'=>$data['product_name'],'product_subcategory_id'=>$data['category_id'],'product_category_id'=>$cat[0]['category_parent_id'],'product_added_on'=>time(),'product_updated_on'=>time());
				$product_id=$this->pm->add_product($prod_data);
				$this->gm->send_response(true,'Success','',$product_id);
			}
		}
		else
		{
			$this->gm->send_response(false,'Empty_Field','','');
		}
	}


		public function list_product(){


		$data = file_get_contents("php://input");
		$data = json_decode($data, TRUE);

		if(isset($data['category_id']))
		{
			$cat_where=array('category_parent_id'=>$data['category_id']);
			$cat_data=$this->cm->get_category($cat_where);

			if(sizeof($cat_data)==0)
			{
				$this->gm->send_response(false,'No_Category_Found','','');
			}
			else
			{
				$this->gm->send_response(true,'Category_List','',$cat_data);
			}
		}
		else
		{
			$this->gm->send_response(false,'Empty_Field','','');
		}
	}



  /********************************************************************************
  * * Function            : product_details
  * * Description         : product details according to product id
  * * Input Parameters    : product_id,quantity
  * * Return Values       :  true or false(JSON)
  * ****************************************************************************** */

  public function get_product_details($product_id){

    //check empty fields
    if (empty($product_id)) {
      $this->gm->send_response(false,'Empty_Field','','Product');
    }

    $select = array();//select array
    $where=array("product_id"=>$product_id);//where condition
    $response=$this->pm->select_product($select,$where);//fetch from db
    if(count($response)){//if product found

      if($response[0]['product_status']!=1)//if product is not active
        $this->gm->send_response(false,"Product_Deleted",'','');

      if ($response[0]['product_quantity_in_stock']==0)//if product is out of stock
        $this->gm->send_response(false,"Product_Out_Of_Stock",'',$response[0]);

      $this->gm->send_response(false,"Success",'',$response[0]);//success
    }
    else {
      $this->gm->send_response(false,"Product_Not_Found",'','');//invalid product id
    }

  }
}
