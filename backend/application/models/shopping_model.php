<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 */
class shopping_model extends CI_MODEL
{

  function __construct(){
    parent::__construct();
  }

  /********************************************************************************
  * * Function            : add_to_cart
  * * Description         : add product info in the cart
  * * Input Parameters    : data
  * * Return Values       :  true or false
  * ****************************************************************************** */
  public function add_to_cart($data){
    $query=$this->db->insert('bkd_cart',$data);
    if($this->db->affected_rows()==1)
    {
      return $this->db->insert_id();
    }
    else
    {
      return false;
    }
  }

  /********************************************************************************
  * * Function            : select_cart
  * * Description         : select cart details
  * * Input Parameters    : where condition
  * * Return Values       :  true or false
  * ****************************************************************************** */
  public function select_cart($where){
    $this->db->where($where);
    $query=$this->db->get('bkd_cart')->result_array();
    return $query;
  }

  /********************************************************************************
  * * Function            : edit_cart
  * * Description         : edit cart details
  * * Input Parameters    : data and where condition
  * * Return Values       :  true or false
  * ****************************************************************************** */
  public function edit_cart($data,$where){
    $this->db->where($where);
    $query=$this->db->update('bkd_cart', $data);
    return $query;
  }

  /********************************************************************************
  * * Function            : delete_cart
  * * Description         : delete cart details
  * * Input Parameters    :  where
  * * Return Values       :  true or false
  * ****************************************************************************** */
  public function delete_cart($where){
    $query=$this->db->delete('bkd_cart', $where);
    return $query;
  }

  /********************************************************************************
  * * Function            : list_cart
  * * Description         : list all products from the cart for a particular user
  * * Input Parameters    :  where
  * * Return Values       :  cart product data
  * ****************************************************************************** */
  public function list_cart($user_id){
    $sql="SELECT bp.`product_id`,`product_name`,`product_price`,`product_image`,`product_discount`
    ,`product_discount_status`,bc.`quantity`,bc.`cart_id` FROM `bkd_product` bp INNER JOIN `bkd_cart` bc
    ON bp.product_id=bc.product_id WHERE bc.user_id=".$user_id;

    $query=$this->db->query($sql)->result_array();
    return $query;

  }
}


 ?>
