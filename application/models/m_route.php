<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_route extends CI_Model {

    public function insert_route($data) {

//        go to destination ,กำหนด StartPoint เป็น S 
        $dataD = array(
            'RCode' => $data['RCode'],
            'StartPoint' => 'S',
            'VTID' => $data['VTID'],
            'RSource' => $data['RSource'],
            'RDestination' => $data['RDestination'],
        );
        $this->db->insert('t_routes', $dataD);

//        go to source ,กำหนด StartPoint เป็น D
        $dataS = array(
            'RCode' => $data['RCode'],
            'StartPoint' => 'D',
            'VTID' => $data['VTID'],
            'RSource' => $data['RDestination'],
            'RDestination' => $data['RSource'],
        );
        $this->db->insert('t_routes', $dataS);

        return $data['RCode'];
    }

    public function update_route($rcode, $vtid, $data) {

        $rid = $this->get_route_detail($rcode, $vtid);

//        go to destination ,กำหนด StartPoint เป็น S 
        $dataD = array(
            'RCode' => $data['RCode'],
            'StartPoint' => 'S',
            'VTID' => $data['VTID'],
            'RSource' => $data['RSource'],
            'RDestination' => $data['RDestination'],
        );
        $this->db->where('RID', $rid[0]['RID']);
        $this->db->update('t_routes', $dataD);

//        go to source ,กำหนด StartPoint เป็น D
        $dataS = array(
            'RCode' => $data['RCode'],
            'StartPoint' => 'D',
            'VTID' => $data['VTID'],
            'RSource' => $data['RDestination'],
            'RDestination' => $data['RSource'],
        );
        $this->db->where('RID', $rid[1]['RID']);
        $this->db->update('t_routes', $dataS);

        return $data['RCode'];
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

    public function search_route($rcode = NULL, $source = NULL, $destination = NULL) {

        if ($rcode != NULL)
            $this->db->where('RCode', $rcode);
        if ($source != NULL)
            $this->db->where('RSource', $source);
        if ($destination != NULL)
            $this->db->where('RDestination', $destination);
//        $this->db->where('StartPoint', 'S');
//        $this->db->group_by(array('RCode', 't_routes.VTID'));
        $query = $this->db->get('t_routes');
        return $query->result_array();
    }

    public function get_route_detail($rcode = NULL, $vtid = NULL) {
        if ($rcode != NULL)
            $this->db->where('RCode', $rcode);
        if ($vtid != NULL)
            $this->db->where('VTID', $vtid);

        $this->db->order_by('StartPoint', 'desc');
        $query = $this->db->get('t_routes');
        return $query->result_array();
    }

    function get_vehicle_types($id = NULL) {
        if ($id != NULL)
            $this->db->where('VTID', $id);
        $temp = $this->db->get('vehicles_type');
        return $temp->result_array();
    }

    public function get_vehicle_type_name($type_id) {
        $name = '';
        $query = $this->db->get_where('vehicles_type', array('VTID' => $type_id));
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $name = $row->VTDescription;
        }
        return $name;
    }

    function set_form_search_route() {
        $i_RCode = array(
            'name' => 'RCode',
            'value' => set_value('RID'),
            'placeholder' => 'รหัสเส้นทาง',
            'class' => 'form-control');

        $i_VTID[0] = 'เลือกประเภทรถ';
        foreach ($this->get_vehicle_types() as $value) {
            $i_VTID[$value['VTID']] = $value['VTDescription'];
        }
        $i_RSource = array(
            'name' => 'RSource',
            'value' => set_value('RSource'),
            'placeholder' => 'ต้นทาง',
            'class' => 'form-control');

        $i_RDestination = array(
            'name' => 'RDestination',
            'value' => set_value('RDestination'),
            'placeholder' => 'ปลายทาง',
            'class' => 'form-control');

        $dropdown = 'class="selecter_3" data-selecter-options = \'{"cover":"true"}\' ';

        $form_search_route = array(
            'form' => form_open('route/', array('id' => 'form_search_route')),
            'RCode' => form_input($i_RCode),
            'VTID' => form_dropdown('VTID', $i_VTID, set_value('VTID'), $dropdown),
            'RSource' => form_input($i_RSource),
            'RDestination' => form_input($i_RDestination),
        );
        return $form_search_route;
    }

    function set_form_add_route($type_id) {
        $i_RCode = array(
            'name' => 'RCode',
            'value' => set_value('RCode'),
            'placeholder' => 'รหัสเส้นทาง',
            'class' => 'form-control');

        $i_VTID[0] = 'เลือกประเภทรถ';
        foreach ($this->get_vehicle_types() as $value) {
            $i_VTID[$value['VTID']] = $value['VTDescription'];
        }
        $i_RSource = array(
            'name' => 'RSource',
            'value' => set_value('RSource'),
            'placeholder' => 'ต้นทาง',
            'class' => 'form-control');

        $i_RDestination = array(
            'name' => 'RDestination',
            'value' => set_value('RDestination'),
            'placeholder' => 'ปลายทาง',
            'class' => 'form-control');

        $i_Time = array(
            'name' => 'Time',
            'value' => set_value('Time'),
            'placeholder' => 'เวลาที่ใช้',
            'class' => 'form-control');

        $dropdown = 'class="selecter_3" disabled="disabled" data-selecter-options = \'{"cover":"true"}\' ';

        $form_add_route = array(
            'form' => form_open('route/add/' . $type_id, array('class' => 'form-horizontal', 'id' => 'form_route')),
            'RCode' => form_input($i_RCode),
            'VTID' => form_dropdown('VTID', $i_VTID, (set_value('VTID') == NULL) ? $type_id : set_value('VTID'), $dropdown),
            'RSource' => form_input($i_RSource),
            'RDestination' => form_input($i_RDestination),
            'Time' => form_input($i_Time),
        );
        return $form_add_route;
    }

    function set_form_edit_route($data) {
        $i_RCode = array(
            'name' => 'RCode',
            'value' => (set_value('RCode') == NULL) ? $data ['RCode'] : set_value('RCode'),
            'placeholder' => 'รหัสเส้นทาง',
            'class' => 'form-control');

        $i_VTID[0] = 'เลือกประเภทรถ';
        foreach ($this->get_vehicle_types() as $value) {
            $i_VTID[$value['VTID']] = $value['VTDescription'];
        }
        $i_RSource = array(
            'name' => 'RSource',
            'value' => (set_value('RSource') == NULL) ? $data ['RSource'] : set_value('RSource'),
            'placeholder' => 'ต้นทาง',
            'class' => 'form-control');

        $i_RDestination = array(
            'name' => 'RDestination',
            'value' => (set_value('RDestination') == NULL) ? $data ['RDestination'] : set_value('RDestination'),
            'placeholder' => 'ปลายทาง',
            'class' => 'form-control');

        $i_Time = array(
            'name' => 'Time',
            'value' => (set_value('Time') == NULL) ? $data ['Time'] : set_value('Time'),
            'placeholder' => 'เวลาที่ใช้',
            'class' => 'form-control');

        $dropdown = 'class="selecter_3" data-selecter-options = \'{"cover":"true"}\' ';

        $form_edit_route = array(
            'form' => form_open('route/edit/' . $data['RCode'] . '/' . $data['VTID'], array('class' => 'form-horizontal', 'id' => 'form_route')),
            'RCode' => form_input($i_RCode),
            'VTID' => form_dropdown('VTID', $i_VTID, (set_value('VTID') == NULL) ? $data['VTID'] : set_value('VTID'), $dropdown),
            'RSource' => form_input($i_RSource),
            'RDestination' => form_input($i_RDestination),
            'Time' => form_input($i_Time),
        );
        return $form_edit_route;
    }

    public function validation_form_add_route() {
        $this->form_validation->set_rules('RCode', 'รหัสเส้นทาง', 'trim|required|xss_clean|callback_check_route_duplication');
        $this->form_validation->set_rules('VTID', 'ประเภทรถ', 'trim|xss_clean|callback_check_dropdown');
        $this->form_validation->set_rules('RSource', 'ต้นทาง', 'trim|required|xss_clean');
        $this->form_validation->set_rules('RDestination', 'ปลายทาง', 'trim|required|xss_clean');
         $this->form_validation->set_rules('Time', 'เวลา', 'trim|required|xss_clean');
        return TRUE;
    }

    public function validation_form_edit_route() {
        $this->form_validation->set_rules('RCode', 'รหัสเส้นทาง', 'trim|required|xss_clean|callback_check_route');
        $this->form_validation->set_rules('VTID', 'ประเภทรถ', 'trim|xss_clean|callback_check_dropdown|callback_check_route');
        $this->form_validation->set_rules('RSource', 'ต้นทาง', 'trim|required|xss_clean');
        $this->form_validation->set_rules('RDestination', 'ปลายทาง', 'trim|required|xss_clean');
        $this->form_validation->set_rules('Time', 'เวลา', 'trim|required|xss_clean');
        return TRUE;
    }

    public function get_post_form_add_route($type_id) {
        $form_data = array(
            'RCode' => $this->input->post('RCode'),
            'VTID' => $type_id,
            'RSource' => $this->input->post('RSource'),
            'RDestination' => $this->input->post('RDestination'),
            'Time' => $this->input->post('Time'),
        );
        return $form_data;
    }

    public function get_post_form_edit_route() {
        $form_data = array(
            'RCode' => $this->input->post('RCode'),
            'VTID' => $this->input->post('VTID'),
            'RSource' => $this->input->post('RSource'),
            'RDestination' => $this->input->post('RDestination'),
            'Time' => $this->input->post('Time'),
        );
        return $form_data;
    }

}

//$this->form_validation->set_rules('product_name[thai]', 'ชื่อสินค้า', 'trim|required|xss_clean');
       
//'product_name' => $this->input->post('product_name'),
//    $i_ = array(
//            'name' => '',
//            'value' => set_value(''),
//            'placeholder' => '',
//            'class' => 'form-control');
//    
//
//        $i_type = array();
//        $temp = $this->m_products->check_product_type();
//        foreach ($temp as $row) {
//            $i_type[$row['id']] = unserialize($row['product_type'])['thai'];
//        }