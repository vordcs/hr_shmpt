<?php

/*
 * การจัดการพนักงงานพนักงานขายตั๋วโดยสารโดยเฉพาะ
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class seller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->model('hr/m_seller');
        $this->load->model('m_route');
        $this->load->model('m_station');
        $this->load->model('m_vehicle');
        $this->load->library('form_validation');

        //Initial language
        $this->m_template->set_Language(array('plan'));
    }

    public function index() {
        $RCode = NULL;
        $VTID = NULL;

        $form_data = array();
        if ($this->m_seller->validation_form_search() && $this->form_validation->run() == TRUE) {
            $rcode = $this->input->post('RCode');
            $vtid = $this->input->post('VTID');
            if ($rcode != "0") {
                $RCode = $rcode;
            }
            if ($vtid != "0") {
                $VTID = $vtid;
            }
            $form_data = $this->input->post();
        }
        $data = array(
            'page_title' => 'งานบุคคล : ',
            'page_title_small' => 'การจัดการพนักงานขายตั๋วรถโดยสาร',           
            'data_seller' => $this->m_seller->set_form_view($RCode,$VTID),
            'form' => $this->m_seller->set_form_search($RCode,$VTID),
        );

        $data_debug = array(
//            'form' => $data['form'],
//            'form_data' => $form_data,
//            'data_seller' => $data['data_seller'],
        );


        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Title('พนักงานขายตั๋ว');
        $this->m_template->set_Permission('HSI');
        $this->m_template->set_Content('hr/seller/seller', $data);
        $this->m_template->showTemplate();
    }

    public function add($rcode = NULL, $vtid = NULL, $EID = NULL) {
        $form_data = array();
        $rs = array();
        if ($this->m_seller->validation_form_add() && $this->form_validation->run()) {
            $form_data = $this->m_seller->get_post_form_add();
            $rs = $this->m_seller->insert_seller($form_data);

            $alert['alert_message'] = "เพิ่มข้อมูลสำเร็จ";
            $alert['alert_mode'] = "success";
            $this->session->set_flashdata('alert', $alert);

            redirect('hr/seller/');
        }

        $data = array(
            'page_title' => 'งานบุคคล : ',
            'page_title_small' => 'เพิ่มพนักงานขายตั๋วรถโดยสาร',
            'EID' => $EID,
            'form' => $this->m_seller->set_form_add($rcode, $vtid),
        );
        $data_debug = array(
//            'form' => $data['form'],
//            'form_data' => $form_data,
//            'rs' => $rs,
        );


        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Title('พนักงานขายตั๋ว');
        $this->m_template->set_Permission('HSA');
        $this->m_template->set_Content('hr/seller/frm_seller', $data);
        $this->m_template->showTemplate();
    }

    public function delete($seller_id) {
        if ($seller_id != NULL) {
            $rs = $this->m_seller->delete_seller($seller_id);
            if ($rs) {
                $alert['alert_message'] = "ลบข้อมูลสำเร็จ";
                $alert['alert_mode'] = "success";
                $this->session->set_flashdata('alert', $alert);
            } else {
                $alert['alert_message'] = "ลบข้อมูลไม่สำเร็จสำเร็จ";
                $alert['alert_mode'] = "warning";
                $this->session->set_flashdata('alert', $alert);
            }
        }
        redirect('hr/seller/');
    }

//    ตรวจสอบค่าใน dropdown
    public function check_dropdown($str) {
        if ($str === '0' || $str == '') {
            $this->form_validation->set_message('check_dropdown', 'เลือก %s');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function IsSeller($sid) {
        $con = array(
            'EID' => $this->input->post('EID'),
            'RCode' => $this->input->post('RCode'),
            'VTID' => $this->input->post('VTID'),
            'SID' => $sid,
        );
    }

}
