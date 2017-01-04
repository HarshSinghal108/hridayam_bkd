<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class User_model extends CI_MODEL
{
 
    function __construct()
    {
     
        parent::__construct();
        
    }


    public function add_user($data)
    {
        $query=$this->db->insert('bkd_user',$data);
        if($this->db->affected_rows()==1)
        {
            return $this->db->insert_id();
        }
        else
        {
            return false;       
        }
    }
   public function add_user_address($data)
    {
        $query=$this->db->insert('bkd_user_address',$data);
        if($this->db->affected_rows()==1)
        {
            return $this->db->insert_id();
        }
        else
        {
            return false;       
        }
    }


   public function add_user_feedback($data)
    {
        $query=$this->db->insert('bkd_feedback',$data);
        if($this->db->affected_rows()==1)
        {
            return $this->db->insert_id();
        }
        else
        {
            return false;       
        }
    }




}