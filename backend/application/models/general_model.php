<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class General_model extends CI_MODEL
{
 
    function __construct()
    {
     
        parent::__construct();
        
    }

    public function log($did)
    {
        $did=$did."update";
        $logs=APPPATH.'logs';
        $log="At time stamp :".time()."data: ".$did.PHP_EOL;
                    
        chdir($logs);
        file_put_contents('log_GPS_'.date("j.n.Y").'.txt', $log, FILE_APPEND);
    }

     
    public function unset_variables($data, $variables, $type) 
    {
            //loop the input
        foreach ($data as $key => $var) 
        {
            //if type 1 means if key not in varribles then remove it from data
            if ($type == 1) 
            {

                if (!in_array($key, $variables)) 
                {

                    unset($data[$key]);
                }
            } 
            else 
            {
                //it means if key  in varribles then remove it from data
                if (in_array($key, $variables))
                {

                    unset($data[$key]);
                }
            }
        }
        return $data;
    }


     public function set_user_session($user_role, $id)
     {
        //set seesion varribles 
        $this->session->set_userdata(array('user_logged_in' => '1', 'role' => $user_role, 'admin_id' => $id));
     }


     /**
     *  @function   :validate
     *  @param      :rule,array of data,isset(flag)
     *  @Method     :none
     *  @return     :none
     *  @brief      :Helper function to validate data according to rules and 
     *  @caller     : by all api's to validate input
     */

    public function validate($rule, $dataposted, $isset = true) 
    {
        $err = array();
        $flag = true;
        //if isset then we check that field is posted or not
        if ($isset) 
        {
            foreach ($dataposted as $value) 
            {
                if (!isset($_POST[$value]) && $flag) 
                {
                    $flag = false;
                    $err[$value] = "You Need to Post " . $value . " field";
                }
            }
        }
        //true when all things posted
        if ($flag) 
        {

            $this->form_validation->set_rules($rule);
            $this->form_validation->set_message('is_unique', 'This %s is already registered');
            $this->form_validation->set_error_delimiters('', '');
            //running a rule
            if ($this->form_validation->run($rule) == FALSE) {
                $errors = $this->form_error_formating($dataposted);
                $this->send_response(false, 'form_errors', $errors);
            }
        } 
        else
        {
            $this->send_response(false, 'form_error', $err);
        }
    }
     


    public function form_error_formating($dataposted) 
    {

        $errorarray = array();
        //formating each for in array format
        foreach ($dataposted as $value) {
            if (form_error($value) != "") {
                $errorarray[$value] = form_error($value);
            }
        }

        return $errorarray;
    }


    public function send_response($type,$errormsg,$errors = array(),$data = array()) {
            echo json_encode(array('status' => $type, 'msg' => $errormsg,'errors' => $errors, 'data' => $data));
            exit;
        }

    public function get_input($inputdata) 
    {
    
        $inputs = array();
        //looping throgh input data varriblees
        foreach ($inputdata as $var) {
                $inputs[$var] = $this->input->post($var, TRUE);
            }        
        return $inputs;
    }  



}