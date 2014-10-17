<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_vehicle extends CI_Model {

    function set_form() {
        //    ข้อมูลรถ
        $i_VID = array(
            'name' => 'VID',
            'value' => set_value('VID'),
            'placeholder' => 'ทะเบียนรถ',
            'class' => 'form-control');
        $i_VCode = array(
            'name' => 'VCode',
            'value' => set_value('VCode'),
            'placeholder' => 'เบอร์รถ',
            'class' => 'form-control');
        $i_VColor = array(
            'name' => 'VColor',
            'value' => set_value('VColor'),
            'placeholder' => 'สีรถ',
            'class' => 'form-control');
        $i_VBrand = array(
            'name' => 'VBrand',
            'value' => set_value('VBrand'),
            'placeholder' => 'ยี่ห้อรถ',
            'class' => 'form-control');
        $i_VType = array('เลือกประเภทรถ', 'รถตู้', 'รถบัส(แอร์)', 'รถบัส(พัดลม)');
        $i_VSeat = array(
            'name' => 'VSeat',
            'value' => set_value('VSeat'),
            'placeholder' => '0',
            'class' => 'form-control');
       $i_VStatus=  array('ปกติ');
       
       $form_add = array(
           'VID'=>  form_input($i_VID),
           'VCode'=>  form_input($i_VCode),
           '$i_VColor'=>  form_input(),
           
       );
       
       return $form_add;
    }

}

//
//    $i_ = array(
//            'name' => '',
//            'value' => set_value(''),
//            'placeholder' => '',
//            'class' => 'form-control');
//    
//
//$i_type = array();
//        $temp = $this->m_products->check_product_type();
//        foreach ($temp as $row) {
//            $i_type[$row['id']] = unserialize($row['product_type'])['thai'];
//        }