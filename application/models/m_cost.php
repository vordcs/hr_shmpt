<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_cost extends CI_Model {

    public function get_cost($cid = null, $ctid = NULL) {
        if ($cid != NULL) {
            $this->db->where('cost.CostID', $cid);
        }
        if ($ctid != NULL) {
            $this->db->where('cost.CostTypeID', $ctid);
        }
        $this->db->join('cost_type', 'cost_type.CostTypeID = cost.CostTypeID');
        $this->db->join('cost_detail', 'cost_detail.CostDetailID = cost.CostDetailID');
        $this->db->join('vehicles_has_cost', 'vehicles_has_cost.CostID = cost.CostID');
        $query = $this->db->get('cost');
        return $query->result_array();
    }

    public function get_cost_type($id = NULL) {

        if ($id != NULL) {
            $this->db->where('CostTypeID', $id);
        }
        $query = $this->db->get('cost_type');
        return $query->result_array();
    }

    public function get_cost_detail($id = NULL) {
        if ($id != NULL) {
            $this->db->where('CostTypeID', $id);
        }
        $query = $this->db->get('cost_detail');
        return $query->result_array();
    }

    function get_vehicle($vcode = NULL, $vtid = NULL) {
        $this->db->join('vehicles_type', 'vehicles.VTID = vehicles_type.VTID');
        $this->db->join('routes_has_vehicles', 'vehicles.VID = routes_has_vehicles.VID','left');
        if ($vcode != NULL) {
            $this->db->where('vehicles.VCode', $vcode);
        }
        if ($vtid != NULL) {
            $this->db->where('vehicles_type.VTID', $vtid);
        }
        
        $query = $this->db->get('vehicles');
        return $query->result_array();
    }

    public function get_vehicle_types($id = NULL) {
        if ($id != NULL)
            $this->db->where('VTID', $id);
        $temp = $this->db->get('vehicles_type');
        return $temp->result_array();
    }

    public function insert_cost($data) {

//      insert cost data  
        $this->db->insert('cost', $data['data_cost']);
        $cost_id = $this->db->insert_id();

//      insert vehicles has cost
        $vehicle_cost = array(
            'CostID' => $cost_id,
            'VID' => $data['data_vehicle'][0]['VID'],
        );

        $this->db->insert('vehicles_has_cost', $vehicle_cost);

        $rs = $this->get_cost($cost_id);
        $rs[0]['VTID'] = $data['data_vehicle'][0]['VTID'];
        $rs[0]['RCode'] = $data['data_vehicle'][0]['RCode'];
        $rs[0]['VTDescription'] = $data['data_vehicle'][0]['VTDescription'];

        return $rs;
    }

    public function set_form_add($ctid) {
        $i_CostDetailID[0] = 'เลือกรายการ';
        foreach ($this->get_cost_detail($ctid) as $value) {
            $i_CostDetailID[$value['CostDetailID']] = $value['CostDetail'];
        }
        $i_CostDate = array(
            'name' => 'CostDate',
            'value' => (set_value('CostDate') == NULL) ? $this->m_datetime->getDateTodayTH() : set_value('CostDate'),
            'placeholder' => 'วันที่ทำรายการ',
            'class' => 'form-control datepicker');
        $i_VTID[0] = 'เลือกประเภทรถ';
        foreach ($this->get_vehicle_types() as $value) {
            $i_VTID[$value['VTID']] = $value['VTDescription'];
        }
        $i_VCode = array(
            'name' => 'VCode',
            'value' => set_value('VCode'),
            'placeholder' => 'เบอร์รถ',
            'class' => 'form-control');
        $i_CostValue = array(
            'name' => 'CostValue',
            'value' => set_value('CostValue'),
            'placeholder' => 'จำนวนเงิน',
            'class' => 'form-control');
        $i_CostNote = array(
            'name' => 'CostNote',
            'value' => set_value('CostNote'),
            'placeholder' => 'หมายเหตุ',
            'rows' => '3',
            'class' => 'form-control');

        $dropdown = 'class="selecter_3" data-selecter-options = \'{"cover":"true"}\' ';

        $form_add = array(
            'form' => form_open('cost/add/' . $ctid, array('class' => 'form-horizontal', 'id' => 'form_cost')),
            'CostDate' => form_input($i_CostDate),
            'CostDetailID' => form_dropdown('CostDetailID', $i_CostDetailID, set_value('CostDetailID'), $dropdown),
            'VTID' => form_dropdown('VTID', $i_VTID, set_value('VTID'), $dropdown),
            'VCode' => form_input($i_VCode),
            'CostValue' => form_input($i_CostValue),
            'CostNote' => form_textarea($i_CostNote),
        );
        return $form_add;
    }

    public function validation_form_add() {
        $this->form_validation->set_rules('CostDetailID', 'รายการ', 'trim|required|xss_clean|callback_check_dropdown');
        $this->form_validation->set_rules('CostDate', 'วันที่ทำรายการ', 'trim|required|xss_clean');
        $this->form_validation->set_rules('VTID', 'ประเภทรถ', 'trim|required|xss_clean|callback_check_dropdown');
        $this->form_validation->set_rules('VCode', 'เบอร์รถ', 'trim|required|xss_clean|callback_check_vcode');
        $this->form_validation->set_rules('CostValue', 'จำนวนเงิน', 'trim|required|xss_clean');
        $this->form_validation->set_rules('CostNote', 'หมายเหตุ', 'trim|xss_clean');
        return TRUE;
    }

    public function get_post_form_add($ctid) {
        //ข้อมูลค่าใช้จ่าย

        $data_cost = array(
            'CostTypeID' => $ctid,
            'CostDetailID' => $this->input->post('CostDetailID'),
            'CostDate' => $this->input->post('CostDate'),
            'CostValue' => $this->input->post('CostValue'),
            'CostNote' => $this->input->post('CostNote'),
        );
        $vcode = $this->input->post('VCode');
        $vtid = $this->input->post('VTID');
        $form_data = array(
            'data_cost' => $data_cost,
            'data_vehicle' => $this->get_vehicle($vcode, $vtid),
        );

        return $form_data;
    }

}
