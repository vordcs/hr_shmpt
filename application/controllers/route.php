<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class route extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->library('form_validation');
        $this->load->model('m_route');
        //Initial language
        $this->m_template->set_Language(array('plan'));
    }

    public function index() {

        $data = array(
            'vehicles_type' => $this->m_route->get_vehicle_types(),
        );
        $this->m_template->set_Title('การจัดการรถ');
//        $this->m_template->set_Debug($data);
        $this->m_template->set_Content('routes/routes', $data);
        $this->m_template->showTemplate();
    }

    public function add($type_id) {

        $data = array(
            'page_title' => 'เพิ่มเส้นทาง ' . $this->m_route->get_vehicle_type_name($type_id),
            'form_route' => $this->m_route->set_form_add_route($type_id),
        );
        if ($this->m_route->validation_form_add_route() && $this->form_validation->run() == TRUE) {
            redirect('route');
        }

        $this->m_template->set_Title('เพิ่มเส้นทาง');
//        $this->m_template->set_Debug($rs);
        $this->m_template->set_Content('routes/frm_route', $data);
        $this->m_template->showTemplate();
    }

    public function time() {
        $this->load->view('routes/time');
    }

    public function check_route_duplication() {
        $rid = $this->input->post('RID');
        $v_type = $this->input->post('VTID');
    }

}

//if ($this->m_products->validation_set_form_add() && $this->form_validation->run() == TRUE) {