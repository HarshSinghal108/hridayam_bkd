<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class category extends CI_CONTROLLER {


    public function __construct()
	{

		parent::__construct();
		$this->load->library('form_validation');
    	// $this->load->library('session');
        $this->input_arr=include('variables/user_variables.php');
        $this->load->model('general_model','gm',true);
        $this->load->model('category_model','cm',true);
       
	}

	public function add_category(){

        
		$data = file_get_contents("php://input");
		$data = json_decode($data, TRUE);

		if(isset($data['category_name']) && isset($data['category_parent_id']))
		{
			$cat_where=array('category_name'=>$data['category_name'],'category_parent_id'=>$data['category_parent_id']);
			$flag=$this->cm->check_category_exists($cat_where);
			
			if($flag)
			{
				$this->gm->send_response(false,'Already_Exists','','');
			}
			else
			{
				$cat_data=array('category_name'=>$data['category_name'],'category_parent_id'=>$data['category_parent_id'],'category_added_on'=>time(),'category_updated_on'=>time());
				$category_id=$this->cm->add_category($cat_data);
				$this->gm->send_response(true,'Success','',$category_id);
			}	
		}
		else
		{
			$this->gm->send_response(false,'Empty_Field','','');
		}
	}


		public function list_category(){

        
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
}
