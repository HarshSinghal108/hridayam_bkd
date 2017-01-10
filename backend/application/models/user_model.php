<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class User_model extends CI_MODEL
{

  function __construct()
  {

    parent::__construct();

  }


  /********************************************************************************
  * * Function            : check if user exist or not
  * * Description         : check if mobile number or email are unique or not
  * * Input Parameters    :$login_value
  * * Return Values       :  true or false
  * ****************************************************************************** */
  public function check_if_user_exist($login_value){
    $this->db->select('user_id');
    $this->db->where(array('user_email'=>$login_value));
    $this->db->or_where(array('user_mobile'=>$login_value));
    $query=$this->db->get('bkd_user')->result_array();
    return $query;
  }

  /********************************************************************************
  * * Function            : check_if_email_exist
  * * Description         : check if email of user is unique or not
  * * Input Parameters    : email
  * * Return Values       :  true or false
  * ****************************************************************************** */
  public function check_if_email_exist($email){
    $this->db->select('user_id');
    $this->db->where(array('user_email'=>$email));
    $query=$this->db->get('bkd_user')->result_array();
    return $query;
  }

  /********************************************************************************
  * * Function            : check if mobile exist or not
  * * Description         : check if mobile is unique or not
  * * Input Parameters    : mobile
  * * Return Values       :  true or false
  * ****************************************************************************** */
  public function check_if_mobile_exist($mobile){
    $this->db->select('user_id');
    $this->db->where(array('user_mobile'=>$mobile));
    $query=$this->db->get('bkd_user')->result_array();
    return $query;
  }

  /********************************************************************************
  * * Function            : update_user
  * * Description         : update the userdata after completing profile
  * * Input Parameters    : user_data
  * * Return Values       :  true or false
  * ****************************************************************************** */
  public function update_user($user_data)  {
    $this->db->where('user_id', $user_data['user_id']);
    $query=$this->db->update('bkd_user', $user_data);
    return $query;
  }

  /********************************************************************************
  * * Function            : login
  * * Description         : to cehck if user has correct credential or not
  * * Input Parameters    : loginvalue and password
  * * Return Values       : true or false
  * ****************************************************************************** */
  public function login($login_value,$password){
    // $this->db->select('user_id');
    $where="(`user_email` ='".$login_value."' AND `user_password` ='".$password."')OR (`user_mobile` ='".$login_value."' AND `user_password` ='".$password."')";
    $this->db->where($where);
    $query=$this->db->get('bkd_user')->result_array();
    return $query;

  }
  /********************************************************************************
  * * Function            : add_user
  * * Description         : add user info at the time of signup
  * * Input Parameters    : data
  * * Return Values       :  true or false
  * ****************************************************************************** */
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
