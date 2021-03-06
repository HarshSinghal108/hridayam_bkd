<?php
header('Access-Control-Allow-Origin: *');

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class user extends CI_CONTROLLER{


  public function __construct(){
    parent::__construct();
    $this->load->library('form_validation');
    $this->load->library('session');
    $this->input_arr=include('variables/user_variables.php');
    $this->load->model('general_model','gm',true);
    $this->load->model('user_model','um',true);

  }

  public function excel(){
    if(!($user_id = $this->session->userdata('user_id'))){
      $this->gm->send_response(false,'Please_Add_User_First','','');
    }


    $f = fopen('php://memory', 'w');
    /** loop through array  */

    $output_file_name=$user_id.'.csv';

    //fetch user data
    $where = array('user_id' => $user_id );
    $user_details=$this->um->select_user($where);


    $user_headings=array(
      'User Id',
      'First Name',
      'Last Name',
      'Gender',
      'Mobile',
      'Telephone',
      'Email',
      'Birthday',
      'Marriage Annerversery',
      'Joined Date'
    );
    $user_values=array(
      $user_details[0]['user_id'],
      $user_details[0]['user_first_name'],
      $user_details[0]['user_last_name'],
      $user_details[0]['user_gender'],
      $user_details[0]['user_mobile'],
      $user_details[0]['user_telephone'],
      $user_details[0]['user_email'],
      date('Y-m-d',$user_details[0]['user_dob']),
      date('Y-m-d',$user_details[0]['user_dom']),
      date('Y-m-d',$user_details[0]['user_added_on']),
    );

    fputcsv($f, $user_headings);
    fputcsv($f, $user_values);
    $empty=[];
    fputcsv($f, $empty);fputcsv($f, $empty);fputcsv($f, $empty);

    //fetch feedback data
    $where = array('bf_user_id' => $user_id   );
    $feedback_details=$this->um->select_feedback($where);

    // print_r($feedback_details);die;

    $feedback_headings=array(
      'Shopping Medium',
      'Shopping Schedule',
      'Feedback',
      'Suggestion',
    );
    $feedback_values=array(
      $feedback_details[0]['bf_shopping_medium'],
      $feedback_details[0]['bf_shopping_schedule'],
      $feedback_details[0]['bf_feedback'],
      $feedback_details[0]['bf_suggestion'],
    );

    fputcsv($f, $feedback_headings);
    fputcsv($f, $feedback_values);
    $empty=[];
    fputcsv($f, $empty);fputcsv($f, $empty);fputcsv($f, $empty);

    //fetch survey data
    $survey_details=$this->um->select_survey($user_id);

    $survey_headings=array(
      'Category Name',
      'Product Name',
      'Brand',
      'Quantity',
      'Size'
    );

    fputcsv($f, $survey_headings);
    foreach ($survey_details as $line){
    /** default php csv handler **/
        fputcsv($f, $line);
    }
    /** rewrind the "file" with the csv lines **/


    fseek($f, 0);
    /** modify header to be downloadable csv file **/
    header('Content-Type: application/csv');
    header('Content-Disposition: attachement; filename="' . $output_file_name . '";');
    /** Send file to browser for download */
    fpassthru($f);


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
      $referalCode=$data['first_name'].rand(99999,10000);
      $user_data=array('user_email'=>$data['email'],'user_referal_code'=>$referalCode,'user_salutation'=>$data['salutation'],'user_first_name'=>$data['first_name'],'user_last_name'=>$data['last_name'],'user_mobile'=>$data['mobile'],'user_telephone'=>$data['telephone'],'user_gender'=>$data['gender'],'user_dob'=>$data['dob'],'user_dom'=>$data['dom'],'user_added_on'=>time(),'user_updated_on'=>time());
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
    if(!($user_id = $this->session->userdata('user_id'))){
      $this->gm->send_response(false,'Please_Add_User_First','','');
    }

    $data = file_get_contents("php://input");
    $data = json_decode($data, TRUE);

    if(isset($data['shopping_medium']) && isset($data['shopping_schedule'])&& isset($data['feedback']) && isset($data['suggestion']))
    {
      foreach ($data['shopping_medium'] as $key => $value) {
        if($data['shopping_medium'][$key]==true)
          $shopping_medium=$key;
      }

      foreach ($data['shopping_schedule'] as $key => $value) {
        if($data['shopping_schedule'][$key]==true)
          $shopping_schedule=$key;
      }
      $where=array('bf_user_id'=>$user_id);
      $feedback_data=array('bf_user_id'=>$user_id,'bf_shopping_medium'=>$shopping_medium,'bf_shopping_schedule'=>$shopping_schedule,'bf_feedback'=>$data['feedback'],'bf_suggestion'=>$data['suggestion'],'bf_added_on'=>time(),'bf_updated_on'=>time());
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


  public function add_survey(){
    //$this->session->unset_userdata('user_id');
    if(!($user_id = $this->session->userdata('user_id')))
    {
      $this->gm->send_response(false,'Please_Add_User_First','','');
    }

    $data = file_get_contents("php://input");
    $data = json_decode($data, TRUE);

    if(isset($data['product_id']) && isset($data['brand'])&& isset($data['quantity']) && isset($data['size']))
    {
      $survey_data=array(
        'user_id'=>$user_id,
        'product_id'=>$data['product_id'],
        'brand'=>$data['brand'],
        'size'=>$data['size'],
        'quantity'=>$data['quantity'],
        'added_on'=>time()
      );
      $bf_id=$this->um->add_survey($survey_data);

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
  * * Function            : is_user_loggedin
  * * Description         : check if user is logged in or not
  * * Input Parameters    :
  * * Return Values       : user data
  * ****************************************************************************** */
  public function is_user_loggedin(){
    if(!($user_id = $this->session->userdata('user_id'))){
      $this->gm->send_response(false,'No_Session','','');
    }

    $where_user = array('user_id' => $user_id );
    $response=$this->um->select_user($where_user);
    if(count($response)){
      $this->gm->send_response(true,"Session_Exist",'',$response[0]);
    }
    else {
      $this->gm->send_response(false,"Some_Error_Occured",'','');
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

    $fields = array('first_name','last_name','login_value','passoword');
    $this->gm->check_empty_fields($data,$fields);

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


    $fields = array('first_name','last_name','mobile','telephone','gender','email','dob','dom');
    $this->gm->check_empty_fields($data,$fields);

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
    $where_user = array('user_id' => $user_id );
    $response=$this->um->update_user($user_data,$where_user);
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

    $fields = array('login_value','password');
    $this->gm->check_empty_fields($data,$fields);

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



  /********************************************************************************
  * * Function            : Change Password
  * * Description         : Change password if you already know old password
  * * Input Parameters    : old password & new password
  * * Return Values       :  true or false(JSON)
  * ****************************************************************************** */
  public function change_password(){
    if(!$user_id=$this->session->userdata('user_id')){//check if someone is allready logged in or not
      $this->gm->send_response(false,'Session_Expired','','');
    }

    //take input
    $data = file_get_contents("php://input");
    $data = json_decode($data, TRUE);

    //check vallidation
    $fields = array('old_password','new_password');
    $this->gm->check_empty_fields($data,$fields);

    $old_password=$data['old_password'];
    $new_password=$data['new_password'];

    $where_user = array('user_id' => $user_id );
    $response=$this->um->select_user($where_user);
    if(count($response)){
      if($response[0]['user_password']==md5($old_password)){
        $update_data=array('user_password'=>md5($new_password));
        $response=$this->um->update_user($update_data,$where_user);
        if($response){
          $this->gm->send_response(true,'Success','',$response);
        }
        else {
          $this->gm->send_response(false,'Some_Error_Occured','somme_error_occured_while_updating_data','');
        }
      }
      else {
        $this->gm->send_response(false,'Invalid_Old_Password','','');
      }
    }
    else {
      $this->gm->send_response(false,"Invalid_User",'','');
    }
  }

}
