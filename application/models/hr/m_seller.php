<?php

/*
 * กำหนดสิทธิ์พนักงานขายตั๋ว 
 */


if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_seller extends CI_Model {

    public function get_seller($rcode = NUll, $vtid = NULL, $sid = NULL, $eid = NULL) {
        $this->db->join('employees', 'sellers.EID = employees.EID', 'left');
        $this->db->join('t_stations', 'sellers.SID = t_stations.SID', 'left');
        $this->db->join('t_routes', 'sellers.RCode = t_routes.RCode', 'left');
        $this->db->where('StartPoint', 'S');
        if ($rcode != NULL) {
            $this->db->where('sellers.RCode', $rcode);
        }
        if ($vtid != NULL) {
            $this->db->where('sellers.VTID', $vtid);
        }
        if ($sid != NULL) {
            $this->db->where('sellers.SID', $sid);
        }
        if ($eid != NULL) {
            $this->db->where('sellers.EID', $eid);
        }
        $query = $this->db->get('sellers');

        return $query->result_array();
    }

    public function get_route($rcode = NULL, $vtid = NULL) {

        $this->db->join('vehicles_type', 'vehicles_type.VTID = t_routes.VTID');

        if ($rcode != NULL) {
            $this->db->where('RCode', $rcode);
        }

        if ($vtid != NULL) {
            $this->db->where('t_routes.VTID', $vtid);
        }
        $this->db->group_by(array('RCode', 't_routes.VTID'));
        $this->db->where('StartPoint', 'S');
//        $this->db->order_by('StartPoint');
        $query = $this->db->get('t_routes');
        return $query->result_array();
    }

    public function insert_seller($data) {
        $rs = 'มีเเล้ว';
        $rcode = $data['RCode'];
        $vtid = $data['VTID'];
        $sid = $data['SID'];
        $eid = $data['EID'];
        $seller = $this->get_seller($rcode, $vtid, $sid, $eid);
        if (count($seller) <= 0) {
            //        insert data seller
            $this->db->insert('sellers', $data);
            $SellerID = $this->db->insert_id();
            $rs = $this->get_seller();
        }
        return $rs;
    }

    public function delete_seller($seller_id) {
        $this->db->where('SellerID', $seller_id);
        $rs = $this->db->delete('sellers');
        return $rs;
    }

    public function set_form_search() {

        $this->load->model('m_vehicle');
        $i_VTID[0] = 'เลือกประเภทรถ';
        foreach ($this->m_vehicle->get_vehicle_types() as $value) {
            $i_VTID[$value['VTID']] = $value['VTDescription'];
        }
        //ข้อมูลเส้นทาง        
        $i_RCode[0] = 'เส้นทางทั้งหมด';
        foreach ($this->get_route() as $value) {
            $i_RCode[$value['RCode']] = $value['RCode'] . ' ' . $value['RSource'] . ' - ' . $value['RDestination'];
        }
        $dropdown = 'class="selecter_3" data-selecter-options = \'{"cover":"true"}\' ';
        $form_search = array(
            'form' => form_open('hr/seller', array('class' => 'form-horizontal', 'id' => 'form_search_seller')),
            'RCode' => form_dropdown('RCode', $i_RCode, set_value('RCode'), $dropdown.' id="RCode"'),
            'VTID' => form_dropdown('VTID', $i_VTID, set_value('VTID'), $dropdown),
        );
        return $form_search;
    }

    public function set_form_add($eid, $rcode = NULL, $vtid = NULL) {
        $i_EID = array(
            'type' => 'hidden',
            'id' => 'EID',
            'name' => "EID",
            'class' => 'form-control text-center',
            'readonly' => TRUE,
            'value' => $eid,
        );
        $i_RCode = array(
            'type' => 'hidden',
            'id' => 'EID',
            'name' => "RCode",
            'class' => 'form-control text-center',
            'readonly' => TRUE,
            'value' => $rcode,
        );

        $i_VTID = array(
            'type' => 'hidden',
            'id' => 'VTID',
            'name' => "VTID",
            'class' => 'form-control text-center',
            'readonly' => TRUE,
            'value' => $vtid,
        );

        $i_SellerNote = array(
            'name' => 'SellerNote',
            'id' => 'SellerNote',
            'value' => set_value('SellerNote'),
            'placeholder' => '',
            'rows' => '1',
            'class' => 'form-control'
        );

        $form_add = array(
            'form' => form_open("hr/seller/add/$eid/$rcode/$vtid", array('class' => '', 'id' => 'form_seller')),
            'EID' => form_input($i_EID),
            'RCode' => form_input($i_RCode),
            'VTID' => form_input($i_VTID),
            'SellerNote' => form_textarea($i_SellerNote),
        );
        return $form_add;
    }

    public function validation_form_search() {
        $rcode = $this->input->post('RCode');
        $this->form_validation->set_rules('RCode', 'เส้นทาง', 'trim|xss_clean');
        return TRUE;
    }

    public function validation_form_add() {
        $this->form_validation->set_rules('EID', 'พนักงาน', 'trim|xss_clean|required');
        $this->form_validation->set_rules('RCode', 'เส้นทาง', 'trim|required|xss_clean');
        $this->form_validation->set_rules('VTID', 'ประเภทรถ', 'trim|required|xss_clean');
        $this->form_validation->set_rules('SID', 'จุดขายตั๋วโดยสาร', 'trim|xss_clean|callback_check_dropdown');
        $this->form_validation->set_rules('SellerNote', 'หมายเหตุ', 'trim|xss_clean');
        return TRUE;
    }

    public function get_post_form_add() {
        $form_data = array(
            'EID' => $this->input->post('EID'),
            'RCode' => $this->input->post('RCode'),
            'VTID' => $this->input->post('VTID'),
            'SID' => $this->input->post('SID'),
            'SellerNote' => $this->input->post('SellerNote'),
            'CreateBy' => 'admin',
            'CreateDate' => $this->m_datetime->getDatetimeNow(),
        );

        return $form_data;
    }

}
