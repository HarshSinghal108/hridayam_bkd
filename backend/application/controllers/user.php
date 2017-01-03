<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class user extends CI_CONTROLLER {


    public function __construct()
	{

		parent::__construct();
		$this->load->library('form_validation');
    	//$this->load->library('session');
        $this->input_arr=include('variables/user_variables.php');
        $this->load->model('general_model','gm',true);

	}

	public function add_user(){

        $this->gm->validate($this->input_arr['add_user_rule'], $this->input_arr['add_user_parameters'], true);
    	$login_input = $this->gm->get_input($this->input_arr['add_user_parameters']);

	}


}
