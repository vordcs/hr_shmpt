<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_permission extends CI_Model {

    function search_all_office($data = NULL) {
        $this->db->from('employees AS em');
        $this->db->join('employee_positions AS ep', 'ep.PID = em.PID');
        $this->db->join('employee_work_status AS es', 'es.StatusID = em.StatusID');
        $this->db->join('employee_role_permission AS erp', 'erp.RoleID = em.RoleID', 'left');
        $this->db->where('em.PID', '3');
        if (isset($data['EID']) && $data['EID'] != NULL)
            $this->db->where('em.EID', $data['EID']);
        if (isset($data['FirstName']) && $data['FirstName'] != NULL)
            $this->db->like('em.FirstName', $data['FirstName']);
        if (isset($data['LastName']) && $data['LastName'] != NULL)
            $this->db->like('em.LastName', $data['LastName']);
        $query = $this->db->get();
        return $query->result_array();
    }

    function set_form_search() {
        $i_EID = array(
            'name' => 'EID',
            'value' => set_value('EID'),
            'placeholder' => 'รหัสพนักงาน',
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

        $data = array(
            'EID' => form_input($i_EID),
            'FirstName' => form_input($i_FirstName),
            'LastName' => form_input($i_LastName),);
        return $data;
    }

    function set_validation_search() {
        $this->form_validation->set_rules('EID', '', 'trim|xss_clean');
        $this->form_validation->set_rules('FirstName', '', 'trim|xss_clean');
        $this->form_validation->set_rules('LastName', '', 'trim|xss_clean');
        return true;
    }

    function get_post_search() {
        $f_data = array(
            'EID' => $this->input->post('EID'),
            'FirstName' => $this->input->post('FirstName'),
            'LastName' => $this->input->post('LastName'));
        return $f_data;
    }

    function set_form_change() {
        $query = $this->db->get('employee_role_permission');
        $temp = $query->result_array();
        $i_RoleID = array();
        foreach ($temp as $row) {
            $i_RoleID[$row['RoleID']] = trim($row['RoleLevel']);
        }
        $i_EID = array(
            'id' => 'EID',
            'type' => 'hidden',
            'name' => 'EID',
            'value' => set_value('EID'),
            'placeholder' => 'รหัสพนักงาน',
            'class' => 'form-control');

        $data = array(
            'RoleID' => form_dropdown('RoleID', $i_RoleID, set_value('RoleID'), 'class="selecter_1" id="RoleID"'),
            'EID' => form_input($i_EID)
        );
        return $data;
    }

    function set_validation_change() {
        $this->form_validation->set_rules('EID', '', 'trim|required|xss_clean');
        $this->form_validation->set_rules('RoleID', '', 'trim|required|xss_clean');
        return true;
    }

    function get_post_change() {
        $f_data = array(
            'EID' => $this->input->post('EID'),
            'RoleID' => $this->input->post('RoleID'),
        );
        return $f_data;
    }

    function update_permission($data) {
        $data = $this->set_update_date($data);
        $this->db->where('EID', $data['EID']);
        $this->db->update('employees', $data);
        if ($this->db->affected_rows() == 1)
            return TRUE;
        else
            return FALSE;
    }

    function set_update_date($data) {
        $name = $this->session->userdata('name');
        $data['UpdateDate'] = date('Y-m-d H:i:s');
        $data['UpdateBy'] = $name;
        return $data;
    }

}
