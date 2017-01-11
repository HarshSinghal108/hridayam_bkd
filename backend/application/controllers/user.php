<?php
header('Access-Control-Allow-Origin: *');

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class user extends CI_CONTROLLER {


  public function __construct(){
    parent::__construct();
    $this->load->library('form_validation');
    $this->load->library('session');
    $this->input_arr=include('variables/user_variables.php');
    $this->load->model('general_model','gm',true);
    $this->load->model('user_model','um',true);

  }

  public function add_user(){
    $data = file_get_contents("php://input");
    $data = json_decode($data, TRUE);
    if(isset($data['email']) && isset($data['first_name'])&& isset($data['last_name']) && isset($data['mobile']) && isset($data['telephone']) && isset($data['gender']) && isset($data['address']) && isset($data['city']) && isset($data['district']) && isset($data['state']) && isset($data['country']) && isset($data['pincode'])&& isset($data['landmark']) && isset($data['dob']) && isset($data['dom'])){
      $where1=array('user_email'=>$data['email']);
      $where2=array('user_mobile'=>$data['mobile']);
      $flag=$this->um->check_user_exists($where1,$where2);
      if($flag){
        $this->gm->send_response(false,'User_Exists','','');
      }
      $user_data=array('user_email'=>$data['email'],'user_first_name'=>$data['first_name'],'user_last_name'=>$data['last_name'],'user_mobile'=>$data['mobile'],'user_telephone'=>$data['telephone'],'user_gender'=>$data['gender'],'user_dob'=>$data['dob'],'user_dom'=>$data['dom'],'user_added_on'=>time(),'user_updated_on'=>time());
      $user_id=$this->um->add_user($user_data);

      if(!$user_id){
        $this->gm->send_response(false,'Please_Try_Again','','');
      }

      $user_address_data=array('ua_user_id'=>$user_id,'ua_address'=>$data['address'],'ua_city'=>$data['city'],'ua_district'=>$data['district'],'ua_state'=>$data['state'],'ua_country'=>$data['country'],'ua_pincode'=>$data['pincode'],'ua_landmark'=>$data['landmark'],'ua_added_on'=>time(),'ua_updated_on'=>time());
      $this->um->add_user_address($user_address_data);


      $this->session->set_userdata('user_id',$user_id);
      $this->gm->send_response(true,'Success','',$user_id);
    }
    else{
      $this->gm->send_response(false,'Empty_Field','','');
    }
  }


  public function add_user_feedback(){
    //$this->session->unset_userdata('user_id');
    if(!($user_id = $this->session->userdata('user_id')))
    {
      $this->gm->send_response(false,'Please_Add_User_First','','');
    }

    $data = file_get_contents("php://input");
    $data = json_decode($data, TRUE);

    if(isset($data['shopping_medium']) && isset($data['shopping_schedule'])&& isset($data['feedback']) && isset($data['suggestion']))
    {
      $where=array('bf_user_id'=>$user_id);
      $feedback_data=array('bf_user_id'=>$user_id,'bf_shopping_medium'=>$data['shopping_medium'],'bf_shopping_schedule'=>$data['shopping_schedule'],'bf_feedback'=>$data['feedback'],'bf_suggestion'=>$data['suggestion'],'bf_added_on'=>time(),'bf_updated_on'=>time());
      $bf_id=$this->um->add_user_feedback($feedback_data,$where);

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



  /********************************************************************************
   * * Function            : Signup
   * * Description         : Signup module
   * * Input Parameters    : first_name,last_name,mail or mobile number,passowrd
   * * Return Values       :  true or false(JSON)
   * ****************************************************************************** */
   public function signup(){
     if($this->session->userdata('user_id')){//check if someone is allready logged in or not
       $this->session->unset_userdata('user_id');//clear the session
     }

     //take input
     $data = file_get_contents("php://input");
     $data = json_decode($data, TRUE);

     if(empty($data['first_name']) ||empty($data['last_name']) ||empty($data['login_value']) ||empty($data['password'])){
       $this->gm->send_response(false,"Empty_Field",'',$data);
     }
     //bind data
     $first_name=$data['first_name'];
     $last_name=$data['last_name'];
     $login_value=$data['login_value'];
     $passowrd=$data['password'];

     //check if the mobile number or email allready exist or not
     $response=$this->um->check_if_user_exist($login_value);
     if(count($response))
     $this->gm->send_response(false,'User_Already_Exist','',$response );

     $user_data = array(
       'user_first_name' =>$first_name,
       'user_last_name'=>$last_name,
       'user_password'=>md5($passowrd),
       'user_added_on'=>time()
      );
      //check if login value is mobile number or email
      if (strpos($login_value, '@')) {
        $user_data['user_email']=$login_value;
      }
      else {
        $user_data['user_mobile']=$login_value;
      }
    //insert in to db
     $response=$this->um->add_user($user_data);
     $user_data['user_id']=$response;
     if($response){
       $this->session->set_userdata('user_id',$response);
       $this->gm->send_response(true,'Success','',$user_data);
     }
     else {
       $this->gm->send_response(false,'Some_Error_Occured','error_while_inserting_data','');
     }
   }

   /********************************************************************************
    * * Function            : Complete profile
    * * Description         : user all infoamrtion
    * * Input Parameters    : userInfo
    * * Return Values       :  true or false(JSON)
    * ****************************************************************************** */
    public function complete_profile(){
      if(!$user_id=$this->session->userdata('user_id')){//check if someone is allready logged in or not
        $this->gm->send_response(false,'Session_Expired','','');
      }

      //take input
      $data = file_get_contents("php://input");
      $data = json_decode($data, TRUE);

      if(empty($data['first_name']) ||empty($data['last_name']) ||empty($data['mobile']) ||empty($data['telephone'])||empty($data['gender'])||empty($data['email'])||empty($data['dob'])||empty($data['dom']) ){
        $this->gm->send_response(false,"Empty_Field",'',$data);
      }

      //bind the data
      $user_data = array(
        'user_id'=>$user_id,
        'user_first_name'=>$data['first_name'] ,
        'user_last_name'=>$data['last_name'],
        'user_mobile'=>$data['mobile'],
        'user_telephone'=>$data['telephone'],
        'user_gender'=>$data['gender'],
        'user_email'=>$data['email'],
        'user_dob'=>$data['dob'],
        'user_dom'=>$data['dom'],
        'user_updated_on'=>time()
        );

        //check if the  email allready exist or not
        $response=$this->um->check_if_email_exist($data['email']);
        if(count($response))
        $this->gm->send_response(false,'Email_Already_Exist','',$response );

        //check if the mobile number allready exist or not
        $response=$this->um->check_if_mobile_exist($data['mobile']);
        if(count($response))
        $this->gm->send_response(false,'Mobile_Already_Exist','',$response );


        //update user info in db
        $response=$this->um->update_user($user_data);
        if($response){
          $this->gm->send_response(true,'Success','',$user_data);
        }
        else {
          $this->gm->send_response(false,'Some_Error_Occured','somme_error_occured_while_updating_data','');
        }
    }

    /********************************************************************************
     * * Function            : Login
     * * Description         : login via email/phone number and password
     * * Input Parameters    : email/mobile and password
     * * Return Values       :  true or false(JSON)
     * ****************************************************************************** */
     public function login(){
       if($user_id=$this->session->userdata('user_id')){//check if someone is allready logged in or not
         $this->gm->send_response(false,'Already_Loggedin','',$user_id);//clear the session
       }

       //take input
       $data = file_get_contents("php://input");
       $data = json_decode($data, TRUE);

       if(empty($data['login_value']) ||empty($data['password'])){
         $this->gm->send_response(false,"Empty_Field",'',$data);
       }

       $login_value=$data['login_value'];
       $password=md5($data['password']);
       //check if user with this password exist or not
       $response=$this->um->login($login_value,$password);
       if(count($response)){
         $this->session->set_userdata('user_id',$response[0]['user_id']);
         $this->gm->send_response(true,'Success','',$response[0]);
       }
       else {
         $this->gm->send_response(false,'Invalid_Credential','',$data);
       }
     }

     /********************************************************************************
      * * Function            : Logout
      * * Description         : unset the session
      * * Input Parameters    :
      * * Return Values       :  true or false(JSON)
      * ****************************************************************************** */
      public function logout(){
        if($this->session->userdata('user_id')){
          $this->session->unset_userdata('user_id');//clear the session
          $this->gm->send_response(true,'Logout_Successfully','','');
        }
        else {
          $this->gm->send_response(false,'Invalid_Session','','');
        }
      }


}
