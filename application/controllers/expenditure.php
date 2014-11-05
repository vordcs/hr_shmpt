<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class expenditure extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->model('m_expenditure');
        $this->load->model('m_route');
        $this->load->model('m_vehicle');
        $this->load->library('form_validation');

        //Initial language
        $this->m_template->set_Language(array('plan'));
    }

    public function index() {
        $data = array(
            'vehicle_types'=>  $this->m_vehicle->get_vehicle_types(),
            'routes' => $this->m_route->get_route(),
            'vehicles' =>  $this->m_vehicle->get_vehicle(),
        );
        
        $data_debug=array(
            'vehicles' =>  $data['vehicles'],
        );

//        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Content('expenditure/expenditure', $data);
        $this->m_template->showTemplate();
    }

}
