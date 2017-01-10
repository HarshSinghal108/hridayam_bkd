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

   public function check_user_exists($where1,$where2)
    {
        $query=$this->db->select('*')->where($where1)->or_where($where2)->get('bkd_user');
        $num= $query->num_rows();
        if($num>0)
        {   
            return 1;
        }
        else
        {
            return false;
        }
    }


    
   public function add_user_feedback($data,$where)
    {
        $query=$this->db->select('*')->where($where)->get('bkd_feedback');
        $num= $query->num_rows();
        if($num>0)
        {   
            $this->db->where($where)->update('bkd_feedback',$data);
            return $where['bf_user_id'];
        }
        else{
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


}