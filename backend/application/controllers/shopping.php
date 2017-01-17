<?php
header('Access-Control-Allow-Origin: *');

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Shopping extends CI_CONTROLLER {


  public function __construct(){
    parent::__construct();
    $this->load->library('form_validation');
    $this->load->library('session');
    // $this->input_arr=include('variables/shopping_variables.php');
    $this->load->model('general_model','gm',true);
    $this->load->model('shopping_model','sm',true);
    $this->load->model('product_model','pm',true);

  }


  /********************************************************************************
  * * Function            : add to cart
  * * Description         : product are added in to the cart
  * * Input Parameters    : product_id,quantity
  * * Return Values       :  true or false(JSON)
  * ****************************************************************************** */
  public function add_to_cart(){
    if(!$user_id=$this->session->userdata('user_id')){//check if someone is allready logged in or not
      $this->gm->send_response(false,'Session_Expired','','');
    }

    //take input
    $data = file_get_contents("php://input");
    $data = json_decode($data, TRUE);

    //check empty fields
    if(empty($data['product_id']) ||empty($data['quantity'])){
      $this->gm->send_response(false,"Empty_Field",'',$data);
    }

    //bind data
    $product_id=$data['product_id'];
    $quantity=$data['quantity'];

    //check if product is allready in cart or not
    $where_cart=array('product_id'=>$product_id,'user_id'=>$user_id);
    $response=$this->sm->select_cart($where_cart);
    if(count($response)){
      $this->gm->send_response(True,'Success','',$response[0]['cart_id']);
    }

    //check if product id exist or not
    $where_product=array('product_id'=>$product_id,'product_status'=>1);
    $response=$this->pm->check_product_exists($where_product);
    if(!$response){
      $this->gm->send_response(false,'Invalid_Product','',$data['product_id']);
    }

    //check if quantity is valid or not
    $where_product=array('product_id'=>$product_id,'product_quantity_in_stock >='=>$quantity);
    $response=$this->pm->check_product_exists($where_product);
    if(!$response){
      $this->gm->send_response(false,'Invalid_Quantity','',$data['quantity']);
    }

    //bind data for db
    $insert_data = array(
      'user_id'=>$user_id,
      'product_id' =>$product_id ,
      'quantity'=>$quantity ,
      'cart_added_on'=>time()
    );
    //insert product in to db
    $response=$this->sm->add_to_cart($insert_data);
    if($response){
      $this->gm->send_response(true,'Success','',$response);
    }
    else {
      $this->gm->send_response(false,'Some_Error_Occured','somme_error_occured_while_inserting_data','');
    }
  }

  /********************************************************************************
  * * Function            : edit cart
  * * Description         : product are edited in to the cart
  * * Input Parameters    : cart_id,quantity
  * * Return Values       :  true or false(JSON)
  * ****************************************************************************** */
  public function edit_cart(){
    if(!$user_id=$this->session->userdata('user_id')){//check if someone is allready logged in or not
      $this->gm->send_response(false,'Session_Expired','','');
    }

    //take input
    $data = file_get_contents("php://input");
    $data = json_decode($data, TRUE);

    //check empty fields
    if(empty($data['cart_id']) ||empty($data['quantity']) ||empty($data['product_id'])){
      $this->gm->send_response(false,"Empty_Field",'',$data);
    }

    //bind data
    $product_id=$data['product_id'];
    $cart_id=$data['cart_id'];
    $quantity=$data['quantity'];



    //check if cart id is authentic or not
    $where_cart=array('cart_id'=>$cart_id,'user_id'=>$user_id);
    $response=$this->sm->select_cart($where_cart);
    if(!count($response)){
      $this->gm->send_response(false,'Invalid_Cart_Id','',$cart_id);
    }

    //check if product id exist or not
    $where_product=array('product_id'=>$product_id,'product_status'=>1);
    $response=$this->pm->check_product_exists($where_product);
    if(!$response){
      $this->gm->send_response(false,'Invalid_Product','',$data['product_id']);
    }

    //check if quantity is valid or not
    $where_product=array('product_id'=>$product_id,'product_quantity_in_stock >='=>$quantity);
    $response=$this->pm->check_product_exists($where_product);
    if(!$response){
      $this->gm->send_response(false,'Invalid_Quantity','',$data['quantity']);
    }

    //bind data for db
    $update_data = array(
      'quantity' =>$quantity ,
      'cart_updated_on'=>time()
    );

    $where_cart=array('cart_id'=>$cart_id);
    $response=$this->sm->edit_cart($update_data,$where_cart);
    if($response){
      $this->gm->send_response(true,'Success','','');
    }
    else {
      $this->gm->send_response(false,'Some_Error_Occured','somme_error_occured_while_updating_data','');
    }
  }

  /********************************************************************************
  * * Function            : delete cart
  * * Description         : product are deleted from the cart
  * * Input Parameters    : cart_id
  * * Return Values       :  true or false(JSON)
  * ****************************************************************************** */
  public function delete_cart(){
    if(!$user_id=$this->session->userdata('user_id')){//check if someone is allready logged in or not
      $this->gm->send_response(false,'Session_Expired','','');
    }

    //take input
    $data = file_get_contents("php://input");
    $data = json_decode($data, TRUE);

    //check empty fields
    if(empty($data['cart_id'])){
      $this->gm->send_response(false,"Empty_Field",'',$data);
    }

    //bind data
    $cart_id=$data['cart_id'];

    //check if cart id is authentic or not
    $where_cart=array('cart_id'=>$cart_id,'user_id'=>$user_id);
    $response=$this->sm->select_cart($where_cart);
    if(!count($response)){
      $this->gm->send_response(false,'Invalid_Cart_Id','',$cart_id);
    }

    //delete from cart
    $where_cart=array('cart_id'=>$cart_id);
    $response=$this->sm->delete_cart($where_cart);
    if($response){
      $this->gm->send_response(true,'Success','','');
    }
    else {
      $this->gm->send_response(false,'Some_Error_Occured','somme_error_occured_while_deleting_data','');
    }
  }


  /********************************************************************************
  * * Function            : delete cart
  * * Description         : product are deleted from the cart
  * * Input Parameters    : cart_id
  * * Return Values       :  true or false(JSON)
  * ****************************************************************************** */
  public function delete_all_cart(){
    if(!$user_id=$this->session->userdata('user_id')){//check if someone is allready logged in or not
      $this->gm->send_response(false,'Session_Expired','','');
    }

    //delete from cart
    $where_cart=array('user_id'=>$user_id);
    $response=$this->sm->delete_cart($where_cart);
    if($response){
      $this->gm->send_response(true,'Success','','');
    }
    else {
      $this->gm->send_response(false,'Some_Error_Occured','somme_error_occured_while_deleting_data','');
    }
  }


  /********************************************************************************
  * * Function            : list cart
  * * Description         : product are listed from the cart
  * * Input Parameters    : user_id from session
  * * Return Values       :  true or false(JSON)
  * ****************************************************************************** */
    public function list_cart(){
      if(!$user_id=$this->session->userdata('user_id')){//check if someone is allready logged in or not
        $this->gm->send_response(false,'Session_Expired','','');
      }

      $response=$this->sm->list_cart($user_id);
      if($response){
        for ($i=0; $i <count($response) ; $i++) {
          $data[$i]['cart_id']=$response[$i]['cart_id'];
          $data[$i]['product_id']=$response[$i]['product_id'];
          $data[$i]['product_name']=$response[$i]['product_name'];
          $data[$i]['product_image']=$response[$i]['product_image'];
          $data[$i]['product_quantity']=$response[$i]['quantity'];

          if($response[$i]['product_discount_status']){
            $data[$i]['product_price']=($response[$i]['product_price']-(($response[$i]['product_discount']/100)*$response[$i]['product_price']))*$response[$i]['quantity'];
          }
          else {
            $data[$i]['product_price']=$response[$i]['product_price']*$data[$i]['product_quantity'];
          }

          $data['total_price']=$data['total_price']+$data[$i]['product_price'];
        }
        $this->gm->send_response(true,'Success','',$data);
      }
      else {
        $this->gm->send_response(false,'Empty_Cart','','');
      }

    }

    /********************************************************************************
    * * Function            : add to wishlist
    * * Description         : product are added  to wishlist of the user
    * * Input Parameters    : product_id
    * * Return Values       :  true or false(JSON)
    * ****************************************************************************** */
    public function add_to_wishlist(){
      if(!$user_id=$this->session->userdata('user_id')){//check if someone is allready logged in or not
        $this->gm->send_response(false,'Session_Expired','','');
      }

      //take input
      $data = file_get_contents("php://input");
      $data = json_decode($data, TRUE);

      //check empty fields
      if(empty($data['product_id'])){
        $this->gm->send_response(false,"Empty_Field",'',$data);
      }

      //bind data
      $product_id=$data['product_id'];

      //check if product is allready in wishlist or not
      $where_wishlist=array('product_id'=>$product_id,'user_id'=>$user_id);
      $response=$this->sm->select_wishlist($where_wishlist);
      if(count($response)){
        $this->gm->send_response(True,'Success','',$response[0]['wishlist_id']);
      }

      //check if product id exist or not
      $where_product=array('product_id'=>$product_id,'product_status'=>1);
      $response=$this->pm->check_product_exists($where_product);
      if(!$response){
        $this->gm->send_response(false,'Invalid_Product','',$data['product_id']);
      }

      //bind data for db
      $insert_data = array(
        'user_id'=>$user_id,
        'product_id' =>$product_id ,
        'wishlist_added_on'=>time()
      );

      //insert product in to db
      $response=$this->sm->add_to_wishlist($insert_data);
      if($response){
        $this->gm->send_response(true,'Success','',$response);
      }
      else {
        $this->gm->send_response(false,'Some_Error_Occured','somme_error_occured_while_inserting_data','');
      }
    }

    /********************************************************************************
    * * Function            : delete wishlist
    * * Description         : product are deleted from the wishlist
    * * Input Parameters    : wishlist_id
    * * Return Values       :  true or false(JSON)
    * ****************************************************************************** */
    public function delete_wishlist(){
      if(!$user_id=$this->session->userdata('user_id')){//check if someone is allready logged in or not
        $this->gm->send_response(false,'Session_Expired','','');
      }

      //take input
      $data = file_get_contents("php://input");
      $data = json_decode($data, TRUE);

      //check empty fields
      if(empty($data['wishlist_id'])){
        $this->gm->send_response(false,"Empty_Field",'',$data);
      }

      //bind data
      $wishlist_id=$data['wishlist_id'];

      //check if cart id is authentic or not
      $where_wishlist=array('wishlist_id'=>$wishlist_id,'user_id'=>$user_id);
      $response=$this->sm->select_wishlist($where_wishlist);
      if(!count($response)){
        $this->gm->send_response(false,'Invalid_wishlist_Id','',$wishlist_id);
      }

      //delete from cart
      $where_wishlist=array('wishlist_id'=>$wishlist_id);
      $response=$this->sm->delete_wishlist($where_wishlist);
      if($response){
        $this->gm->send_response(true,'Success','','');
      }
      else {
        $this->gm->send_response(false,'Some_Error_Occured','somme_error_occured_while_deleting_data','');
      }
    }


    /********************************************************************************
    * * Function            : delete wishlist
    * * Description         : product are deleted from the wishlist
    * * Input Parameters    :
    * * Return Values       :  true or false(JSON)
    * ****************************************************************************** */
    public function delete_all_wishlist(){
      if(!$user_id=$this->session->userdata('user_id')){//check if someone is allready logged in or not
        $this->gm->send_response(false,'Session_Expired','','');
      }

      //delete from cart
      $where_wishlist=array('user_id'=>$user_id);
      $response=$this->sm->delete_wishlist($where_wishlist);
      if($response){
        $this->gm->send_response(true,'Success','','');
      }
      else {
        $this->gm->send_response(false,'Some_Error_Occured','somme_error_occured_while_deleting_data','');
      }
    }



}

?>
