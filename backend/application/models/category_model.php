<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Category_model extends CI_MODEL
{
 
    function __construct()
    {
     
        parent::__construct();
        
    }


   public function check_category_exists($where)
    {
        $query=$this->db->select('*')->where($where)->get('bkd_category');
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

    public function add_category($data)
    {
        $query=$this->db->insert('bkd_category',$data);
        if($this->db->affected_rows()==1)
        {
            return $this->db->insert_id();
        }
        else
        {
            return false;       
        }
    }

    public function get_category($where)
    {
        $arr=array();
        $query=$this->db->select('*')->where($where)->get('bkd_category');
        $arr=$query->result_array();
        return $arr;
    }

}