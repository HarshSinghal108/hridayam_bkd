<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Product_model extends CI_MODEL
{

    function __construct()
    {

        parent::__construct();

    }


   public function check_product_exists($where)
    {
        $query=$this->db->select('*')->where($where)->get('bkd_product');
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

    public function add_product($data)
    {
        $query=$this->db->insert('bkd_product',$data);
        if($this->db->affected_rows()==1)
        {
            return $this->db->insert_id();
        }
        else
        {
            return false;
        }
    }

    public function get_product($where)
    {
        $arr=array();
        $query=$this->db->select('*')->where($where)->get('bkd_product');
        $arr=$query->result_array();
        return $arr;
    }

    /********************************************************************************
    * * Function            : select_product
    * * Description         : get product details according to select and where array
    * * Input Parameters    : select,where
    * * Return Values       : product details
    * ****************************************************************************** */
    public function select_product($select,$where)  {
      if (sizeof($select)) {
        $query=$this->db->select($select)->where($where)->get('bkd_product');
        $arr=$query->result_array();
        return $arr;
      }
      else {
        $query=$this->db->select('*')->where($where)->get('bkd_product');
        $arr=$query->result_array();
        return $arr;
      }
    }

}
