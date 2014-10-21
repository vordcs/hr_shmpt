<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_vehicle extends CI_Model {

    function set_form_add() {
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
            'type' => 'number',
            'placeholder' => '0',
            'class' => 'form-control');
        $i_VStatus = array('ปกติ');

        //ข้อมูลทะเบียน
        $i_DateRegistered = array(
            'name' => 'DateRegistered',
            'value' => set_value('DateRegistered'),
            'placeholder' => 'วันที่ต่อทะเบียน',
            'class' => 'form-control datepicker');
        $i_DateExpire = array(
            'name' => 'DateExpire',
            'value' => set_value('DateExpire'),
            'placeholder' => 'วันหมดอายุ',
            'class' => 'form-control datepicker');
        $i_VRNote = array(
            'name' => 'VRNote',
            'value' => set_value('VRNote'),
            'placeholder' => '',
            'rows' => '3',
            'class' => 'form-control');
        //ประกันและพรบ
        $i_InsuranceCompanyName = array(
            'name' => 'InsuranceCompanyName',
            'value' => set_value('InsuranceCompanyName'),
            'placeholder' => 'ชื่อบริษัทประกัน',
            'class' => 'form-control');
        $i_PolicyType = array(
            'name' => 'PolicyType',
            'value' => set_value('PolicyType'),
            'placeholder' => 'ประเภทกรมธรรม์',
            'class' => 'form-control');
        $i_PolicyStart = array(
            'name' => 'PolicyStart',
            'value' => set_value('PolicyStart'),
            'placeholder' => 'วันที่เริ่มกรมธรรม์',
            'class' => 'form-control datepicker');
        $i_PolicyEnd = array(
            'name' => 'PolicyEnd',
            'value' => set_value('PolicyEnd'),
            'placeholder' => 'วันสิ้นสุดกรมธรรม์',
            'class' => 'form-control datepicker');
        $i_PolicyNumber = array(
            'name' => 'PolicyNumber',
            'value' => set_value('PolicyNumber'),
            'placeholder' => 'เลขที่กรมธรรม์',
            'class' => 'form-control');
        $i_ActNote = array(
            'name' => 'ActNote',
            'rows' => '3',
            'value' => set_value('ActNote'),
            'placeholder' => '',
            'class' => 'form-control');


        $form_add = array(
            'form' => form_open_multipart('vehicle/add', array('class' => 'form-horizontal', 'id' => 'form_vehicle')),
            'VID' => form_input($i_VID),
            'VCode' => form_input($i_VCode),
            'VColor' => form_input($i_VColor),
            'VBrand' => form_input($i_VBrand),
            'VType' => form_dropdown('VType', $i_VType, set_value('VType'), 'class="form-control"'),
            'VSeat' => form_input($i_VSeat),
            'VStatus' => form_dropdown('VStatus', $i_VStatus, set_value('VStatus'), 'class="form-control"'),
            'DateRegistered' => form_input($i_DateRegistered),
            'DateExpire' => form_input($i_DateExpire),
            'VRNote' => form_textarea($i_VRNote),
            'InsuranceCompanyName' => form_input($i_InsuranceCompanyName),
            'PolicyType' => form_input($i_PolicyType),
            'PolicyStart' => form_input($i_PolicyStart),
            'PolicyEnd' => form_input($i_PolicyEnd),
            'PolicyNumber' => form_input($i_PolicyNumber),
            'ActNote'=>  form_textarea($i_ActNote)
        );


        return $form_add;
    }

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
        $i_VType = array(
            '0' => 'เลือกประเภทรถ',
            '1' => 'รถตู้',
            '2' => 'รถบัส(แอร์)',
            '3' => 'รถบัส(พัดลม)');
        $i_VSeat = array(
            'name' => 'VSeat',
            'value' => set_value('VSeat'),
            'type' => 'number',
            'placeholder' => '0',
            'class' => 'form-control');
        $i_VStatus = array('ปกติ', 'ซ่อมบำรุง');

        $form_add = array(
            'form' => form_open_multipart('vehicle/add', array('class' => 'form-horizontal', 'id' => 'form_vehicle')),
            'VID' => form_input($i_VID),
            'VCode' => form_input($i_VCode),
            'VColor' => form_input($i_VColor),
            'VBrand' => form_input($i_VBrand),
            'VType' => form_dropdown('VType', $i_VType, set_value('VType'), 'class="form-control"'),
            'VSeat' => form_input($i_VSeat),
            'VStatus' => form_dropdown('VStatus', $i_VStatus, set_value('VStatus'), 'class="form-control"'),
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