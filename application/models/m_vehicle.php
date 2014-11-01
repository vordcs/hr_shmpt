<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_vehicle extends CI_Model {

    function get_vehicle($vid = NULL) {
        $this->db->join('vehicles_type', 'vehicles.VTID = vehicles_type.VTID');
        $this->db->join('vehicles_registration', 'vehicles.RegID =vehicles_registration.RegID');
        $this->db->join('vehicles_insurance_act', 'vehicles.ActID = vehicles_insurance_act.ActID');

        $this->db->join('routes_has_vehicles', 'vehicles.VID = routes_has_vehicles.VID');

        if ($vid != NULL) {
            $this->db->where('vehicles.VID', $vid);
        }
        $query = $this->db->get('vehicles');
        return $query->result_array();
    }

    function search_vehicle() {
        $vcode = $this->input->post('VCode');
        $number_plate = $this->input->post('NumberPlate');

        if ($vcode != NULL)
            $this->db->where('Vcode', $vcode);

        if ($number_plate != NULL)
            $this->db->where('NumberPlate', $number_plate);

        $this->db->join('vehicles_type', 'vehicles.VTID = vehicles_type.VTID');
        $this->db->join('vehicles_registration', 'vehicles.RegID =vehicles_registration.RegID');
        $this->db->join('vehicles_insurance_act', 'vehicles.ActID = vehicles_insurance_act.ActID');
        $this->db->join('routes_has_vehicles', 'vehicles.VID = routes_has_vehicles.VID');
        $query = $this->db->get('vehicles');
        return $query->result_array();
    }

    public function get_route($rcode = NULL, $vtid = NULL) {

        $this->db->join('vehicles_type', 'vehicles_type.VTID = t_routes.VTID');

        if ($rcode != NULL)
            $this->db->where('RCode', $rcode);
        if ($vtid != NULL)
            $this->db->where('t_routes.VTID', $vtid);

//        $this->db->where('StartPoint', 'S');
        $this->db->group_by(array('RCode', 't_routes.VTID'));
        $query = $this->db->get('t_routes');
        return $query->result_array();
    }

    function get_vehicle_types($id = NULL) {
        if ($id != NULL)
            $this->db->where('VTID', $id);
        $temp = $this->db->get('vehicles_type');
        return $temp->result_array();
    }

    function get_policy_type($id = NULL) {
        $this->db->where('MiscName', 'PolicyType');
        $query = $this->db->get('miscellaneous');
        return $query->result_array();
    }

    function insert_vehicle($data) {
//        insert vehicles registration data  
        $this->db->insert('vehicles_registration', $data['data_registered']);
        $reg_id = $this->db->insert_id();

//        insert vehicles_insurance_act data         
        $this->db->insert('vehicles_insurance_act', $data['data_act']);
        $act_id = $this->db->insert_id();

//        insert vehicle data  
        $data['data_vehicle']['RegID'] = $reg_id;
        $data['data_vehicle']['ActID'] = $act_id;
        $this->db->insert('vehicles', $data['data_vehicle']);
        $v_id = $this->db->insert_id();

//      insert vehicles has route
        $data_v_r = array(
            'RCode' => $data['RCode'],
            'VID' => $v_id,
        );
        $this->db->insert('routes_has_vehicles', $data_v_r);

        return $v_id;
    }

    function update_vehicle($data) {

        $vid = $data['VID'];
        $reg_id = $data['data_vehicle']['RegID'];
        $act_id = $data['data_vehicle']['ActID'];

//        update vehicles registration data 
        $this->db->where('RegID', $reg_id);
        $this->db->update('vehicles_registration', $data['data_registered']);


//        update vehicles insurance act data         
        $this->db->where('ActID', $act_id);
        $this->db->update('vehicles_insurance_act', $data['data_act']);


//        update vehicle data  
        $this->db->where('VID', $vid);
        $this->db->update('vehicles', $data['data_vehicle']);

        //      insert vehicles has route
        $data_v_r = array(
            'RCode' => $data['RCode'],
            'VID' => $vid,
        );
        $this->db->where('VID', $vid);
        $this->db->update('routes_has_vehicles', $data_v_r);

//        return $vid;
    }

    function set_form_add($rcode, $vtid) {
        //    ข้อมูลรถ
        $i_NumberPlate = array(
            'name' => 'NumberPlate',
            'value' => set_value('NumberPlate'),
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

//        ประกันและพรบ
        $i_InsuranceCompanyName = array(
            'name' => 'InsuranceCompanyName',
            'value' => set_value('InsuranceCompanyName'),
            'placeholder' => 'ชื่อบริษัทประกัน',
            'class' => 'form-control');

        $i_PolicyType[0] = 'เลือกประเภทกรมธรรม์';
        foreach ($this->get_policy_type() as $value) {
            $i_PolicyType[$value['StringValue']] = $value['StringValue'];
        }

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

        $dropdown = 'class="selecter_3" data-selecter-options = \'{"cover":"true"}\' ';

        $form_add = array(
            'form' => form_open_multipart('vehicle/add/' . $rcode . '/' . $vtid, array('class' => 'form-horizontal', 'id' => 'form_vehicle')),
            'NumberPlate' => form_input($i_NumberPlate),
            'VCode' => form_input($i_VCode),
            'VColor' => form_input($i_VColor),
            'VBrand' => form_input($i_VBrand),
            'VType' => form_dropdown('VType', $i_VType, set_value('VType'), $dropdown),
            'VSeat' => form_input($i_VSeat),
            'VStatus' => form_dropdown('VStatus', $i_VStatus, set_value('VStatus'), $dropdown),
            'DateRegistered' => form_input($i_DateRegistered),
            'DateExpire' => form_input($i_DateExpire),
            'VRNote' => form_textarea($i_VRNote),
            'InsuranceCompanyName' => form_input($i_InsuranceCompanyName),
            'PolicyType' => form_dropdown('PolicyType', $i_PolicyType, set_value('PolicyType'), $dropdown),
            'PolicyStart' => form_input($i_PolicyStart),
            'PolicyEnd' => form_input($i_PolicyEnd),
            'PolicyNumber' => form_input($i_PolicyNumber),
            'ActNote' => form_textarea($i_ActNote)
        );
        return $form_add;
    }

    function set_form_edit($rcode, $vtid, $data) {

        //ข้อมูลเส้นทาง
        $i_RCode[0] = 'เลือกเส้นทาง';
        foreach ($this->get_route() as $value) {
            $i_RCode[$value['RCode']] = $value['RCode'] . ' ' . $value['RSource'] . ' - ' . $value['RDestination'];
        }
        //    ข้อมูลรถ
        $i_VTID[0] = 'เลือกประเภทรถ';
        foreach ($this->get_vehicle_types() as $value) {
            $i_VTID[$value['VTID']] = $value['VTDescription'];
        }

        $i_NumberPlate = array(
            'name' => 'NumberPlate',
            'value' => (set_value('NumberPlate') == NULL) ? $data ['NumberPlate'] : set_value('NumberPlate'),
            'placeholder' => 'ทะเบียนรถ',
            'class' => 'form-control');
        $i_VCode = array(
            'name' => 'VCode',
            'value' => (set_value('VCode') == NULL) ? $data ['VCode'] : set_value('VCode'),
            'placeholder' => 'เบอร์รถ',
            'class' => 'form-control');
        $i_VColor = array(
            'name' => 'VColor',
            'value' => (set_value('VColor') == NULL) ? $data ['VColor'] : set_value('VColor'),
            'placeholder' => 'สีรถ',
            'class' => 'form-control');
        $i_VBrand = array(
            'name' => 'VBrand',
            'value' => (set_value('VBrand') == NULL) ? $data ['VBrand'] : set_value('VBrand'),
            'placeholder' => 'ยี่ห้อรถ',
            'class' => 'form-control');

        $i_VSeat = array(
            'name' => 'VSeat',
            'value' => (set_value('VSeat') == NULL) ? $data ['VSeat'] : set_value('VSeat'),
            'type' => 'number',
            'placeholder' => '0',
            'class' => 'form-control');
        $i_VStatus = array('ปกติ', 'ซ่อมบำรุง');

        //ข้อมูลทะเบียน
        $i_DateRegistered = array(
            'name' => 'DateRegistered',
            'value' => (set_value('DateRegistered') == NULL) ? $data ['DateRegistered'] : set_value('DateRegistered'),
            'placeholder' => 'วันที่ต่อทะเบียน',
            'class' => 'form-control datepicker');
        $i_DateExpire = array(
            'name' => 'DateExpire',
            'value' => (set_value('DateExpire') == NULL) ? $data ['DateExpire'] : set_value('DateExpire'),
            'placeholder' => 'วันหมดอายุ',
            'class' => 'form-control datepicker');
        $i_VRNote = array(
            'name' => 'VRNote',
            'value' => (set_value('VRNote') == NULL) ? $data ['VRNote'] : set_value('VRNote'),
            'placeholder' => '',
            'rows' => '3',
            'class' => 'form-control');

//        ประกันและพรบ
        $i_InsuranceCompanyName = array(
            'name' => 'InsuranceCompanyName',
            'value' => (set_value('InsuranceCompanyName') == NULL) ? $data ['InsuranceCompanyName'] : set_value('InsuranceCompanyName'),
            'placeholder' => 'ชื่อบริษัทประกัน',
            'class' => 'form-control');

        $i_PolicyType[0] = 'เลือกประเภทกรมธรรม์';
        foreach ($this->get_policy_type() as $value) {
            $i_PolicyType[$value['StringValue']] = $value['StringValue'];
        }


        $i_PolicyStart = array(
            'name' => 'PolicyStart',
            'value' => (set_value('PolicyStart') == NULL) ? $data ['PolicyStart'] : set_value('PolicyStart'),
            'placeholder' => 'วันที่เริ่มกรมธรรม์',
            'class' => 'form-control datepicker');
        $i_PolicyEnd = array(
            'name' => 'PolicyEnd',
            'value' => (set_value('PolicyEnd') == NULL) ? $data ['PolicyEnd'] : set_value('PolicyEnd'),
            'placeholder' => 'วันสิ้นสุดกรมธรรม์',
            'class' => 'form-control datepicker');
        $i_PolicyNumber = array(
            'name' => 'PolicyNumber',
            'value' => (set_value('PolicyNumber') == NULL) ? $data ['PolicyNumber'] : set_value('PolicyNumber'),
            'placeholder' => 'เลขที่กรมธรรม์',
            'class' => 'form-control');
        $i_ActNote = array(
            'name' => 'ActNote',
            'rows' => '3',
            'value' => (set_value('ActNote') == NULL) ? $data ['ActNote'] : set_value('ActNote'),
            'placeholder' => '',
            'class' => 'form-control');

        $dropdown = 'class="selecter_3" data-selecter-options = \'{"cover":"true"}\' ';
        $form_edit = array(
            'form' => form_open_multipart('vehicle/edit/' . $rcode . '/' . $vtid . '/' . $data['VID'], array('class' => 'form-horizontal', 'id' => 'form_vehicle')),
            'RCode' => form_dropdown('RCode', $i_RCode, (set_value('RCode') == NULL) ? $data ['RCode'] : set_value('RCode'), $dropdown),
            'VTID' => form_dropdown('VTID', $i_VTID, (set_value('VTID') == NULL) ? $data ['VTID'] : set_value('VTID'), $dropdown),
            'NumberPlate' => form_input($i_NumberPlate),
            'VCode' => form_input($i_VCode),
            'VColor' => form_input($i_VColor),
            'VBrand' => form_input($i_VBrand),
            'VSeat' => form_input($i_VSeat),
            'VStatus' => form_dropdown('VStatus', $i_VStatus, set_value('VStatus'), $dropdown),
            'DateRegistered' => form_input($i_DateRegistered),
            'DateExpire' => form_input($i_DateExpire),
            'VRNote' => form_textarea($i_VRNote),
            'InsuranceCompanyName' => form_input($i_InsuranceCompanyName),
            'PolicyType' => form_dropdown('PolicyType', $i_PolicyType, (set_value('PolicyType') == NULL) ? $data ['PolicyType'] : set_value('PolicyType'), $dropdown),
            'PolicyStart' => form_input($i_PolicyStart),
            'PolicyEnd' => form_input($i_PolicyEnd),
            'PolicyNumber' => form_input($i_PolicyNumber),
            'ActNote' => form_textarea($i_ActNote)
        );

//        'product_type_id' => form_dropdown('product_type_id', $i_type, (set_value('product_type_id') == NULL) ? $data ['product_type_id'] : set_value('product_type_id'), 'class="form-control"'),


        return $form_edit;
    }

    function set_form_search() {
        //ข้อมูลเส้นทาง
        $i_RCode[0] = 'เส้นทางทั้งหมด';
        foreach ($this->get_route() as $value) {
            $i_RCode[$value['RCode']] = $value['RCode'] . ' ' . $value['RSource'] . ' - ' . $value['RDestination'];
        }

        $i_VTID[0] = 'ประเภทรถทั้งหมด';
        foreach ($this->get_vehicle_types() as $value) {
            $i_VTID[$value['VTID']] = $value['VTDescription'];
        }         
        $i_NumberPlate = array(
            'name' => 'NumberPlate',
            'value' => set_value('NumberPlate'),
            'placeholder' => 'ทะเบียนรถ',
            'class' => 'form-control');

        $i_VCode = array(
            'name' => 'VCode',
            'value' => set_value('VCode'),
            'placeholder' => 'เบอร์รถ',
            'class' => 'form-control');

        $dropdown = 'class="selecter_3" data-selecter-options = \'{"cover":"true"}\' ';

        $form_search = array(
            'form' => form_open('vehicle/', array('class' => 'form-horizontal', 'id' => 'form_search_vehicle')),
            'RCode' => form_dropdown('RCode', $i_RCode, set_value('RCode'), $dropdown),
            'VTID' => form_dropdown('VTID', $i_VTID, set_value('VTID'), 'class="selecter_3" '),
            'NumberPlate' => form_input($i_NumberPlate),
            'VCode' => form_input($i_VCode),
        );

        return $form_search;
    }

    function validation_form_add() {

//       ข้อมูลรถ
        $this->form_validation->set_rules('NumberPlate', 'ทะเบียนรถ', 'trim|required|xss_clean|callback_check_numberplate');
        $this->form_validation->set_rules('VCode', 'เบอร์รถ', 'trim|required|xss_clean|callback_check_vcode');
        $this->form_validation->set_rules('VColor', 'สีรถ', 'trim|required|xss_clean');
        $this->form_validation->set_rules('VBrand', 'ยี่ห้อรถ', 'trim|required|xss_clean');
        $this->form_validation->set_rules('VSeat', 'จำนวนที่นั่ง', 'trim|required|xss_clean');
        $this->form_validation->set_rules('RegNote', 'หมายเหตุ', 'trim|xss_clean');

////      ข้อมูลทะเบียน
        $this->form_validation->set_rules('DateRegistered', 'วันที่ต่อทะเบียน', 'trim|required|xss_clean');
        $this->form_validation->set_rules('DateExpire', 'วันหมดอายุ', 'trim|required|xss_clean');
        $this->form_validation->set_rules('VRNote', 'หมายเหตุ', 'trim|xss_clean');
////       ประกันและพรบ
        $this->form_validation->set_rules('InsuranceCompanyName', 'ชื่อบริษัทประกัน', 'trim|required|xss_clean');
        $this->form_validation->set_rules('PolicyType', 'ประเภทกรมธรรม์', 'trim|required|xss_clean|callback_check_dropdown');
        $this->form_validation->set_rules('PolicyStart', 'วันที่เริ่มกรมธรรม์', 'trim|required|xss_clean');
        $this->form_validation->set_rules('PolicyEnd', 'วันที่สิ้นสุดกรมธรรม์', 'trim|required|xss_clean');
        $this->form_validation->set_rules('PolicyNumber', 'เลขที่กรมธรรม์', 'trim|required|xss_clean');
        $this->form_validation->set_rules('ActNote', 'หมายเหตุ', 'trim|xss_clean');
        return TRUE;
    }

    function validation_form_edit() {
//        ข้อมูลเส้นทาง
        $this->form_validation->set_rules('RCode', 'เส้นทาง', 'trim|required|xss_clean|callback_check_dropdown');

//       ข้อมูลรถ
        $this->form_validation->set_rules('VTID', 'ประเภทรถ', 'trim|required|xss_clean|callback_check_dropdown');
        $this->form_validation->set_rules('NumberPlate', 'ทะเบียนรถ', 'trim|required|xss_clean|callback_check_numberplate');
        $this->form_validation->set_rules('VCode', 'เบอร์รถ', 'trim|required|xss_clean|callback_check_vcode');
        $this->form_validation->set_rules('VColor', 'สีรถ', 'trim|required|xss_clean');
        $this->form_validation->set_rules('VBrand', 'ยี่ห้อรถ', 'trim|required|xss_clean');
        $this->form_validation->set_rules('VSeat', 'จำนวนที่นั่ง', 'trim|required|xss_clean');
        $this->form_validation->set_rules('RegNote', 'หมายเหตุ', 'trim|xss_clean');

//      ข้อมูลทะเบียน
        $this->form_validation->set_rules('DateRegistered', 'วันที่ต่อทะเบียน', 'trim|required|xss_clean');
        $this->form_validation->set_rules('DateExpire', 'วันหมดอายุ', 'trim|required|xss_clean');
        $this->form_validation->set_rules('VRNote', 'หมายเหตุ', 'trim|xss_clean');
//       ประกันและพรบ
        $this->form_validation->set_rules('InsuranceCompanyName', 'ชื่อบริษัทประกัน', 'trim|required|xss_clean');
        $this->form_validation->set_rules('PolicyType', 'ประเภทกรมธรรม์', 'trim|required|xss_clean|callback_check_dropdown');
        $this->form_validation->set_rules('PolicyStart', 'วันที่เริ่มกรมธรรม์', 'trim|required|xss_clean');
        $this->form_validation->set_rules('PolicyEnd', 'วันที่สิ้นสุดกรมธรรม์', 'trim|required|xss_clean');
        $this->form_validation->set_rules('PolicyNumber', 'เลขที่กรมธรรม์', 'trim|required|xss_clean');
        $this->form_validation->set_rules('ActNote', 'หมายเหตุ', 'trim|xss_clean');
        return TRUE;
    }

    function get_post_form_add($rcode, $vtid) {
//       ข้อมูลรถ        
        $data_vehicle = array(
            'NumberPlate' => $this->input->post('NumberPlate'),
            'VTID' => $vtid,
            'VCode' => $this->input->post('VCode'),
            'VColor' => $this->input->post('VColor'),
            'VBrand' => $this->input->post('VBrand'),
            'VSeat' => $this->input->post('VSeat'),
        );
//      ข้อมูลทะเบียน        
        $data_registered = array(
            'DateRegistered' => $this->m_datetime->setDateFomat($this->input->post('DateRegistered')),
            'DateExpire' => $this->m_datetime->setDateFomat($this->input->post('DateExpire')),
            'VRNote' => $this->input->post('VRNote'),
        );
//       ประกันและพรบ    
        $data_act = array(
            'InsuranceCompanyName' => $this->input->post('InsuranceCompanyName'),
            'PolicyType' => $this->input->post('PolicyType'),
            'PolicyStart' => $this->input->post('PolicyStart'),
            'PolicyEnd' => $this->input->post('PolicyEnd'),
            'PolicyNumber' => $this->input->post('PolicyNumber'),
            'ActNote' => $this->input->post('ActNote'),
        );

        $form_data = array(
            'RCode' => $rcode,
            'data_vehicle' => $data_vehicle,
            'data_registered' => $data_registered,
            'data_act' => $data_act,
        );

        return $form_data;
    }

    function get_post_form_edit($vid) {
        $detail = $this->m_vehicle->get_vehicle($vid);
        if ($detail[0] != NULL) {
//            $rid = $detail[0]['RID'];
//            $vtid = $detail[0]['VTID'];
            $regid = $detail[0]['RegID'];
            $actid = $detail[0]['ActID'];
        }
//       ข้อมูลรถ        
        $data_vehicle = array(
            'NumberPlate' => $this->input->post('NumberPlate'),
            'VTID' => $this->input->post('VTID'),
            'VCode' => $this->input->post('VCode'),
            'VColor' => $this->input->post('VColor'),
            'VBrand' => $this->input->post('VBrand'),
            'VSeat' => $this->input->post('VSeat'),
            'RegID' => $regid,
            'ActID' => $actid,
        );
//      ข้อมูลทะเบียน        
        $data_registered = array(
            'DateRegistered' => $this->m_datetime->setDateFomat($this->input->post('DateRegistered')),
            'DateExpire' => $this->m_datetime->setDateFomat($this->input->post('DateExpire')),
            'VRNote' => $this->input->post('VRNote'),
        );
//       ประกันและพรบ    
        $data_act = array(
            'InsuranceCompanyName' => $this->input->post('InsuranceCompanyName'),
            'PolicyType' => $this->input->post('PolicyType'),
            'PolicyStart' => $this->input->post('PolicyStart'),
            'PolicyEnd' => $this->input->post('PolicyEnd'),
            'PolicyNumber' => $this->input->post('PolicyNumber'),
            'ActNote' => $this->input->post('ActNote'),
        );
        $form_data = array(
            'VID' => $vid,
            'RCode' => $this->input->post('RCode'),
            'data_vehicle' => $data_vehicle,
            'data_registered' => $data_registered,
            'data_act' => $data_act,
        );
        return $form_data;
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