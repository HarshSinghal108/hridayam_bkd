<?php

$add_user_parameters = array('email', 'first_name','last_name','mobile','telephone','gender','address','city','state','country','pincode','landmark','dob','dom');
$add_user_rule = array(
    array(
        'field' => 'email',
        'label' => 'Email',
        'rules' => 'trim|required|valid_email'
    ),
    array(
        'field' => 'firstname',
        'label' => 'Firstname',
        'rules' => 'trim|required'
    ),
    array(
        'field' => 'laststname',
        'label' => 'Lastname',
        'rules' => 'trim|required'
    ),
    array(
        'field' => 'mobile',
        'label' => 'Mobile',
        'rules' => 'trim|required'
    ),
    array(
        'field' => 'telephone',
        'label' => 'Telephone',
        'rules' => 'trim|required'
    ),
    array(
        'field' => 'gender',
        'label' => 'Gender',
        'rules' => 'trim|required'
    ),
    array(
        'field' => 'address',
        'label' => 'Address',
        'rules' => 'trim|required'
    ),
    array(
        'field' => 'city',
        'label' => 'City',
        'rules' => 'trim|required'
    ),
    array(
        'field' => 'state',
        'label' => 'State',
        'rules' => 'trim|required'
    ),
    array(
        'field' => 'country',
        'label' => 'Country',
        'rules' => 'trim|required'
    ),
    array(
        'field' => 'pincode',
        'label' => 'Pincode',
        'rules' => 'trim|required'
    ),
    array(
        'field' => 'landmark',
        'label' => 'Landmark',
        'rules' => 'trim|required'
    ),
    array(
        'field' => 'dob',
        'label' => 'DOB',
        'rules' => 'trim|required'
    ),
    array(
        'field' => 'dom',
        'label' => 'DOM',
        'rules' => 'trim|required'
    )
);



$arr= array('add_user_parameters'=>$add_user_parameters,
            'add_user_rule'=>$add_user_rule
        );

return $arr;