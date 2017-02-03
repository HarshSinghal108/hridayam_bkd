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

	// {"0":{"weight":"24","price":"50","packing":"open","piece":"5","type":"branded"},"1":{"weight":"48","price":"75","packing":"open","piece":"10","type":"branded"},"product_name":"product name"}

	public function add_product(){


		$data = file_get_contents("php://input");
		$data = json_decode($data, TRUE);


		if(isset($data['product_name']) && isset($data['category_id']) && isset($data['prod_img']))
		{

			for($i=0;$i<count($data['details']);$i++)
			{

				if(sizeof($data['details'][$i])==0)
				{

					$this->gm->send_response(false,'Empty_Field','','');
				}
			}



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


				$img_data=$data['prod_img'];
				$ext_type= explode(';', $img_data)[0];
				if($ext_type=="data:image/png")
				{
					$ext='.png';
				}
				else if($ext_type=="data:image/jpeg")
				{
					$ext='.jpeg';
				}
				else if($ext_type=="data:image/jpg")
				{
					$ext='.jpg';
				}
				else if($ext_type=="data:image/gif")
				{
					$ext='.gif';
				}


		   		$image_type = array("data:image/jpeg;base64,", "data:image/png;base64,", "data:image/jpg;base64,", "data:image/gif;base64,");
				$img_data= str_replace($image_type, '', $img_data);
				$img = base64_decode($img_data);
				$f='product_'.time().$ext;
				$file_name=FCPATH.'images/'.$f;
				$success = file_put_contents($file_name, $img);

				$prod_data=array('product_name'=>$data['product_name'],'product_subcategory_id'=>$data['category_id'],'product_category_id'=>$cat[0]['category_parent_id'],'product_image'=>$f,'product_added_on'=>time(),'product_updated_on'=>time());
				$product_id=$this->pm->add_product($prod_data);

				for($i=0;$i<count($data['details']);$i++)
				{

				$prod_data1=array('bsp_product_id'=>$product_id,'bsp_weight'=>$data['details'][$i]['weight'],'bsp_type'=>$data['details'][$i]['type'],'bsp_price'=>$data['details'][$i]['price'],'bsp_packing'=>$data['details'][$i]['packing'],'bsp_piece'=>$data['details'][$i]['piece'],'bsp_added_on'=>time(),'bsp_updated_on'=>time());
				$this->pm->add_sub_product($prod_data1);
				}




				$this->gm->send_response(true,'Success','',$product_id);
			}
		}
		else
		{
			$this->gm->send_response(false,'Empty_Field','','');
		}
	}



	public function edit_product(){


		$data = file_get_contents("php://input");
		$data = json_decode($data, TRUE);


		if(isset($data['product_name']) && isset($data['product_id']))
		{

			for($i=0;$i<count($data['details']);$i++)
			{

				if(sizeof($data['details'][$i])==0)
				{

					$this->gm->send_response(false,'Empty_Field','','');
				}
			}


			$prod_where=array('product_id'=>$data['product_id']);
			$flag=$this->pm->check_product_exists($prod_where);

			if(!$flag)
			{
				$this->gm->send_response(false,'Not_Exists','','');
			}
			else
			{

				$prod_data=array('product_name'=>$data['product_name'],'product_updated_on'=>time());
				$product_id=$this->pm->edit_product($prod_data,$prod_where);


				$sub_pro_where=array('bsp_product_id'=>$data['product_id']);
				$this->pm->delete_sub_product($sub_pro_where);

				for($i=0;$i<count($data['details']);$i++)
				{

				$prod_data1=array('bsp_product_id'=>$data['product_id'],'bsp_weight'=>$data['details'][$i]['weight'],'bsp_type'=>$data['details'][$i]['type'],'bsp_price'=>$data['details'][$i]['price'],'bsp_packing'=>$data['details'][$i]['packing'],'bsp_piece'=>$data['details'][$i]['piece'],'bsp_added_on'=>time(),'bsp_updated_on'=>time());
				$this->pm->add_sub_product($prod_data1);
				}




				$this->gm->send_response(true,'Success','','');
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
  * * Function            : delete_product
  * * Description         : delete product from the db
  * * Input Parameters    : product_id
  * * Return Values       :  true or false(JSON)
  * ****************************************************************************** */
  public function delete_product($product_id){
    $where=array("product_id"=>$product_id);
    $response=$this->pm->delete_product($where);
    if($response){
      $this->gm->send_response(true,"Product_Deleted",'','');
    }
    else {
      $this->gm->send_response(false,"Product_Not_Deleted","somme_error_occured_while_deleting_data",'');
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
      $this->gm->send_response(true,"Success",'',$response);//success
    }
    else {
      $this->gm->send_response(false,"Product_Not_Found",$response,'');//invalid product id
    }

  }

  public function get_product_details2($product_id){

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

      $this->gm->send_response(true,"Success",'',$response);//success
    }
    else {
      $this->gm->send_response(false,"Product_Not_Found",$response,'');//invalid product id
    }

  }




		public function upload(){


		$data = file_get_contents("php://input");
		$data = json_decode($data, TRUE);
		$img_data=$data['prod_img'];
		$ext_type= explode(';', $img_data)[0];
		if($ext_type=="data:image/png")
		{
			$ext='.png';
		}
		else if($ext_type=="data:image/jpeg")
		{
			$ext='.jpeg';
		}
		else if($ext_type=="data:image/jpg")
		{
			$ext='.jpg';
		}
		else if($ext_type=="data:image/gif")
		{
			$ext='.gif';
		}


   		$image_type = array("data:image/jpeg;base64,", "data:image/png;base64,", "data:image/jpg;base64,", "data:image/gif;base64,");
		$img_data= str_replace($image_type, '', $img_data);
		$img = base64_decode($img_data);
		$file_name=FCPATH.'/images/'.'product_'.time().$ext;
		$success = file_put_contents($file_name, $img);

			
}

	public function add_product_image()
    {
        $is_uploaded=$this->upload('prod_image');
        if($is_uploaded['status'])
        {
        	$image_path=$is_uploaded['name'];
	        $data=array('place_image_place_id'=>$input['place_id'],'place_image_name'=>$input['image_name'],'place_image_path'=>'place_image/'.$image_path,'place_image_added_on'=>time(),'place_image_updated_on'=>time());
	        $is_added=$this->am->add_place_image($data);
	        if(! $is_added)
	        {
	           $this->send_response(false, 'please_try_later');
	        }
	        $this->send_response(true,'place_image_added');
    	}
    	else
    	{
    		$this->send_response(false,'please_upload_image');
    	}
    }


    public function upload1($file)
    {
    	if(isset($_FILES[$file]))
        {

			$this->load->helper('string');
        	$rand=random_string('alnum', 4);
            $name=$rand.'_'.time();
            $img=$_FILES[$file]['name'];
            $path_parts = pathinfo($img);
            $ext = strtolower($path_parts["extension"]);
            $path=getcwd();
            chdir($path);
            $data=array();

            if( $_FILES[$file]['name']!='' and $_FILES[$file]['size']>0)
            {
                $config['upload_path'] = './'.$file;
                $config['allowed_types'] = 'png|jpg|gif|pdf';
                //$config['max_size'] = '4096';
                //$config['max_width']  = '3500';
                //$config['max_height']  = '3500';

                $config['file_name'] = $file.'_'.$name.$ext;
                $this->load->library('upload', $config);

                if(!$this->upload->do_upload($file))
                {
                    $error=$this->upload->display_errors('', '');
                    $this->send_response(false,$error);
                }
                return array('status'=>true,'name'=>$file.'_'.$name.$ext);
            }
            else
            {
                $this->send_response(false,'please_upload_image');
            }
        }
        else
        {
            $this->send_response(false,'please_upload_image');
        }
    }

}
