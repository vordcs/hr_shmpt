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
        $emp_seller = array();
        for ($i = 0; $i < 10; $i++) {
            $temp = array(
                'EID' => "E" . str_pad($i, 9, '0', STR_PAD_LEFT),
                'Title' => '',
                'FirstName' => "พนักงานขาย $i ",
                'LastName' => "-LastName-",
            );
            array_push($emp_seller, $temp);
        }
        $data = array(
            'page_title' => 'งานบุคคล : ',
            'page_title_small' => 'การจัดการพนักงานขายตั๋วรถโดยสาร',
            'vehicle_types' => $this->m_vehicle->get_vehicle_types(),
        );

        if ($this->m_seller->validation_form_search() && $this->form_validation->run() == TRUE) {
            
        }
        $data['form'] = $this->m_seller->set_form_search();

        $rcode = $this->input->post('RCode');
        if ($rcode != 0 || $rcode != '0') {
            $data['epmployees_seller'] = $this->m_seller->get_seller($rcode);
            $data['sellers'] = $this->m_seller->get_seller($rcode);
        } else {
            $data['epmployees_seller'] = $this->m_seller->get_employee_by_PID();
            $data['sellers'] = $this->m_seller->get_seller();
        }

        //Merge employee and seller
        $prepare = array(
            'SellerID' => NULL,
            'RCode' => NULL,
            'RSource' => NULL,
            'RDestination' => NULL,
            'StationName' => NULL,
            'EID' => NULL,
        );
        $sort = $data['epmployees_seller'];
        $sellers = $data['sellers'];
        for ($i = 0; $i < count($sort); $i++) {
            if (isset($sort[$i]['sell_1']) == FALSE) {
                $sort[$i]['sell_1'] = array();
                $sort[$i]['sell_2'] = array();
            }
            for ($j = 0; $j < count($sellers); $j++) {
                if ($sellers[$j]['EID'] == $sort[$i]['EID'] && $sellers[$j]['VTID'] == '1') {
                    array_push($sort[$i]['sell_1'], $sellers[$j]);
                }
                if ($sellers[$j]['EID'] == $sort[$i]['EID'] && $sellers[$j]['VTID'] == '2') {
                    array_push($sort[$i]['sell_2'], $sellers[$j]);
                }
            }
            if (count($sort[$i]['sell_1']) == 0) {
                array_push($sort[$i]['sell_1'], $prepare);
            }
            if (count($sort[$i]['sell_2']) == 0) {
                array_push($sort[$i]['sell_2'], $prepare);
            }
        }

        //Send data to view
        $data['sort'] = $sort;

        $data_debug = array(
//            'form' => $data['form'],
//            'routes' => $data['routes'],
//            'stations' => $data['stations'],
//            'sellers' => $data['sellers'],
//            'temp' => $temp,
//            'epmployees_seller' => $data['epmployees_seller'],
//            'emp_4' => $this->m_seller->get_employee_by_PID(),
//            'form_data' => $form_data,
//            'rs' => $rs,
//            'sort' => $sort,
        );


        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Title('พนักงานขายตั๋ว');
        $this->m_template->set_Permission('HSI');
        $this->m_template->set_Content('hr/seller/seller', $data);
        $this->m_template->showTemplate();
    }

    public function add($eid, $rcode = NULL, $vtid = NULL) {
        if ($eid == NULL) {
            redirect("hr/seller");
        }

        $data = array(
            'page_title' => 'งานบุคคล : ',
            'page_title_small' => 'กำหนดเส้นทางเเละจุดขายของตั๋วพนักงานขายตั๋วรถโดยสาร',
            'EID' => $eid,
            'RCode' => $rcode,
            'VTID' => $vtid,
            'vehicle_types' => $this->m_vehicle->get_vehicle_types($vtid),
        );


        if ($rcode != NULL || $vtid != NULL) {
            $routes = $this->m_route->get_route();
            $station = $this->m_station->get_stations_sale_ticket($rcode, $vtid);
            $form = $this->m_seller->set_form_add($eid, $rcode, $vtid);
        } else {
            $routes = $this->m_route->get_route();
            $station = array();
            $form = $this->m_seller->set_form_add($eid);
        }
        $form_data = $rs = array();
        if ($this->m_seller->validation_form_add() && $this->form_validation->run() == TRUE) {
            $form_data = $this->m_seller->get_post_form_add();
            $rs = $this->m_seller->insert_seller($form_data);

            $alert['alert_message'] = "เพิ่มข้อมูลสำเร็จ";
            $alert['alert_mode'] = "success";
            $this->session->set_flashdata('alert', $alert);

            redirect('hr/seller/');
        }

        $data['employee'] = $this->m_seller->get_employee_by_EID($eid)[0];
        $data['form'] = $form;
        $data['routes'] = $routes;
        $data['stations'] = $station;
        $data_debug = array(
//            'routes' => $data['routes'],
//            'stations' => $data['stations'],
//            'form' => $data['form'],
//            'form_data' => $form_data,
//            'employee' => $data['employee'],
//            'rs' => $rs,
        );


        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Title('พนักงานขายตั๋ว');
        $this->m_template->set_Permission('HSA');
        $this->m_template->set_Content('hr/seller/frm_seller', $data);
        $this->m_template->showTemplate();
    }

    public function delete($saller_id) {
        $this->m_seller->delete_seller($saller_id);

        $alert['alert_message'] = "ลบข้อมูลสำเร็จ";
        $alert['alert_mode'] = "success";
        $this->session->set_flashdata('alert', $alert);

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
