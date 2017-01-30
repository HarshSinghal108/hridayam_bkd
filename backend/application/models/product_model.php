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


    public function add_sub_product($data)
    {
        $query=$this->db->insert('bkd_sub_product',$data);
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


    public function edit_product($data,$where)
    {
        $query=$this->db->where($where)->update('bkd_product',$data);
        return true;
    }


    public function delete_sub_product($where)
    {
        $query=$this->db->where($where)->delete('bkd_sub_product');

        return true;
    }


    /********************************************************************************
    * * Function            : select_product
    * * Description         : get product details according to select and where array
    * * Input Parameters    : select,where
    * * Return Values       : product details
    * ****************************************************************************** */
    public function select_product($select,$where)  {
        $sql="SELECT * FROM `bkd_product` BP INNER JOIN `bkd_sub_product` BSP ON `product_id`=`bsp_product_id`
              WHERE `product_id`='".$where['product_id']."'";
              echo $sql;
        $query=$this->db->query($sql);
        $arr=$query->result_array();
        return $arr;
    }

    /********************************************************************************
    * * Function            : delete_product
    * * Description         : delete prduct fro the bothe sub product and producr tables
    * * Input Parameters    : where
    * * Return Values       : true or false
    * ****************************************************************************** */
    public function delete_product($where){
      $this->db->where($where);
      $this->db->update('bkd_product', array('product_status'=>2));
      if ($sql) {
        return $sql;
      } else {
        return 0;
      }
    }
}
