<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_candidate extends CI_Model {

    function set_form() {
        $i_ExpectedPermanantSalary = array(
            'name' => 'ExpectedPermanantSalary',
            'type' => 'number',
            'min' => 0,
            'step' => 100,
            'value' => set_value('ExpectedPermanantSalary'),
            'placeholder' => 'เงินเดือนที่ต้องการ',
            'class' => 'form-control');
        $i_FirstName = array(
            'name' => 'FirstName',
            'value' => set_value('FirstName'),
            'placeholder' => 'ชื่อ',
            'class' => 'form-control');
        $i_LastName = array(
            'name' => 'LastName',
            'value' => set_value('LastName'),
            'placeholder' => 'นามสกุล',
            'class' => 'form-control');
        $i_PersonalID = array(
            'name' => 'PersonalID',
            'value' => set_value('PersonalID'),
            'placeholder' => 'เลขประจำตัวประชาชน',
            'class' => 'form-control');
        $i_AvaliableStartDate = array(
            'name' => 'AvaliableStartDate',
            'value' => set_value('AvaliableStartDate'),
            'class' => 'form-control');
        $i_user = array(
            'name' => 'user',
            'value' => set_value('user'),
            'placeholder' => 'Username',
            'class' => 'form-control');
        $i_pass = array(
            'name' => 'pass',
            'value' => set_value('pass'),
            'placeholder' => 'Password',
            'class' => 'form-control');

        $data = array(
            'ExpectedPermanantSalary' => form_input($i_ExpectedPermanantSalary),
            'FirstName' => form_input($i_FirstName),
            'LastName' => form_input($i_LastName),
            'PersonalID' => form_input($i_PersonalID),
            'AvaliableStartDate' => form_input($i_AvaliableStartDate),
        );
        return $data;
    }

}
