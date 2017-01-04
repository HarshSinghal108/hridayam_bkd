<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class user extends CI_CONTROLLER {


    public function __construct()
	{

		parent::__construct();
		$this->load->library('form_validation');
    	// $this->load->library('session');
        $this->input_arr=include('variables/user_variables.php');
        $this->load->model('general_model','gm',true);
        $this->load->model('user_model','um',true);

	}
	public function add_user(){

        
		$data = file_get_contents("php://input");
		$data = json_decode($data, TRUE);

		if(isset($data['email']) && isset($data['first_name'])&& isset($data['last_name']) && isset($data['mobile']) && isset($data['telephone']) && isset($data['gender']) && isset($data['address']) && isset($data['city']) && isset($data['district']) && isset($data['state']) && isset($data['country']) && isset($data['pincode'])&& isset($data['landmark']) && isset($data['dob']) && isset($data['dom']))
		{
			$user_data=array('user_email'=>$data['email'],'user_first_name'=>$data['first_name'],'user_last_name'=>$data['last_name'],'user_mobile'=>$data['mobile'],'user_telephone'=>$data['telephone'],'user_gender'=>$data['gender'],'user_dob'=>$data['dob'],'user_dom'=>$data['dom'],'user_added_on'=>time(),'user_updated_on'=>time());
			$user_id=$this->um->add_user($user_data);
			
			if(!$user_id)
			{
				$this->gm->send_response(false,'Please_Try_Again','','');
			}
	
			$user_address_data=array('ua_user_id'=>$user_id,'ua_address'=>$data['address'],'ua_city'=>$data['city'],'ua_district'=>$data['district'],'ua_state'=>$data['state'],'ua_country'=>$data['country'],'ua_pincode'=>$data['pincode'],'ua_landmark'=>$data['landmark'],'ua_added_on'=>time(),'ua_updated_on'=>time());
			$this->um->add_user_address($user_address_data);

			$this->gm->send_response(true,'Success','',$user_id);
		}
		else
		{
			$this->gm->send_response(false,'Empty_Field','','');
		}
	}	


	public function add_user_feedback(){

        
		$data = file_get_contents("php://input");
		$data = json_decode($data, TRUE);

		if(isset($data['shopping_medium']) && isset($data['shopping_schedule'])&& isset($data['feedback']) && isset($data['suggestion']) && isset($data['user_id']))
		{
			$feedback_data=array('bf_user_id'=>$data['user_id'],'bf_shopping_medium'=>$data['shopping_medium'],'bf_shopping_schedule'=>$data['shopping_schedule'],'bf_feedback'=>$data['feedback'],'bf_suggestion'=>$data['suggestion'],'bf_added_on'=>time(),'bf_updated_on'=>time());
			$bf_id=$this->um->add_user_feedback($feedback_data);
			
			if(!$bf_id)
			{
				$this->gm->send_response(false,'Please_Try_Again','','');
			}
	
			$this->gm->send_response(true,'Success','',$bf_id);
		}
		else
		{
			$this->gm->send_response(false,'Empty_Field','','');
		}
	}

}
