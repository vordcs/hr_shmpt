<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_route extends CI_Model {

    public function get_vehicle_types() {

        $temp = $this->db->get('vehicles_type');
        $vehicle_type = $temp->result_array();

        return $vehicle_type;
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

    function set_form_add_route($type_id) {
        $i_RID = array(
            'name' => 'RID',
            'value' => set_value('RID'),
            'placeholder' => 'รหัสเส้นทาง',
            'class' => 'form-control');

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

        $form_add_route = array(
            'form_route' => form_open('route/add/' . $type_id, array('class' => 'form-horizontal', 'id' => 'form_route')),
            'RID' => form_input($i_RID),
            'RSource' => form_input($i_RSource),
            'RDestination' => form_input($i_RDestination),
        );

        return $form_add_route;
    }

    public function validation_form_add_route() {
        $this->form_validation->set_rules('RID', 'รหัสเส้นทาง', 'trim|required|xss_clean');
        $this->form_validation->set_rules('RSource', 'ต้นทาง', 'trim|required|xss_clean');
        $this->form_validation->set_rules('RDestination', 'ปลายทาง', 'trim|required|xss_clean');

        return TRUE;
    }

    public function get_post_form_add_route() {
        $get_page_data = array(
            'RDestination' => $this->input->post('RDestination'),
        );
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