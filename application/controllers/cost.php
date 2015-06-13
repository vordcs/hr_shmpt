<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class cost extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->model('m_cost');
        $this->load->model('m_route');
        $this->load->model('m_station');
        $this->load->model('m_vehicle');
        $this->load->library('form_validation');

        //Initial language
        $this->m_template->set_Language(array('plan'));
    }

    public function index() {
        $date = $this->m_datetime->getDateToday();
        // ตรวจการส่งค่า POST เพื่อเปลี่ยงแปลง $date ที่จะแสดงข้อมูล
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $temp = $this->input->post('date');
            if ($temp != NULL)
                $date = $this->m_datetime->setTHDateToDB($temp);
        }

        $data = array(
            'form_open' => form_open('cost', array('class' => 'form-inline')),
            'form_close' => form_close(),
            'form' => $this->m_cost->set_form_search(),
            'route_details' => $this->m_route->get_route_detail(),
            'stations' => $this->m_station->get_stations(),
            'date' => $date,
        );

//        $data['schedules'] = $this->m_cost->get_schedule($date);
//        $data['cost_detail'] = $this->m_cost->get_cost_detail();
//        $data['cost_types'] = $this->m_cost->get_cost_type();
        $data['all_cost'] = $this->m_cost->check_cost($date);

        $data_debug = array(
//            'cost' => $data['cost'],
//            'cost_detail' => $data['cost_detail'], 
//            'cost_types' => $data['cost_types'],
//            'routes' => $data['routes'],
//            'route_details' => $data["route_details"],
//            'stations' => $data['stations'],
//            'vehicles' => $data['vehicles'],
//            'form' => $data['form'],
//            'schedules' => $data['schedules'],
//            'date' => $this->m_datetime->setTHDateToDB($date),
//            'detail' => $this->m_route->get_route('264', NULL),
//            'all_cost' => $data['all_cost'],
//            'tbody' => $this->m_cost->generate_tbody('264', '1', '2015-06-10'),
//            'check_income_by_VID' => $this->m_cost->check_income_by_VID('1', '2015-06-10'),
//            'tbodyNew' => $this->m_cost->generate_tbody_cost_vehicle($this->m_cost->generate_tbody('264', '1', '2015-06-10'), '2015-06-10'),
        );

        $this->m_template->set_Title('ค่าใช้จ่าย');
        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Permission('COI');
        $this->m_template->set_Content('cost/cost', $data);
        $this->m_template->showTemplate();
    }

    public function view($rcode, $vtid, $date = NULL) {
        $route_detail = $this->m_route->get_route_detail($rcode, $vtid);
        $source = $route_detail[0]['RSource'];
        $desination = $route_detail[0]['RDestination'];
        $route_name = 'เส้นทาง ' . $route_detail[0]['RCode'] . ' ' . ' ' . $source . ' - ' . $desination;

        if ($date == NULL)
            $date = $this->m_datetime->getDateToday();
        // ตรวจการส่งค่า POST เพื่อเปลี่ยงแปลง $date ที่จะแสดงข้อมูล
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $temp = $this->input->post('date');
            if ($temp != NULL)
                $date = $this->m_datetime->setTHDateToDB($temp);
        }
        $data = array(
            'date' => $date,
            'page_title' => 'เพิ่มค่าใช้จ่าย ' . $route_name,
            'form_open' => form_open('cost/view/' . $rcode . '/' . $vtid, array('class' => 'form-inline')),
            'form_close' => form_close(),
            'rcode' => $rcode,
            'vtid' => $vtid
        );

        $data['vehicles'] = $this->m_vehicle->get_vehicle($rcode, $vtid);
        $data['routes'] = $this->m_route->get_route($rcode, $vtid);
        $data['schedules'] = $this->m_cost->get_schedule($date, $rcode, $vtid);

//        $data['cost_detail'] = $this->m_cost->get_cost_detail();
//        $data['cost_types'] = $this->m_cost->get_cost_type();
        //Check vehicle cost
        foreach ($data['vehicles'] as $key => $row) {
            $data['vehicles'][$key]['cost']['1'] = array('total' => 0, 'list' => array());
            $data['vehicles'][$key]['cost']['2'] = array('total' => 0, 'list' => array());
            $temp = $this->m_cost->check_income_by_VID($row['VID'], $date);
            foreach ($temp as $row2) {
                //รายรับ
                if ($row2['CostTypeID'] == 1) {
                    $data['vehicles'][$key]['cost']['1']['total'] += $row2['CostValue'];
                    array_push($data['vehicles'][$key]['cost']['1']['list'], array('CostDetail' => $row2['CostDetail'],
                        'CostValue' => $row2['CostValue'], 'Name' => $row2['Title'] . $row2['FirstName'] . ' ' . $row2['LastName'],));
                } else {
                    //รายจ่าย
                    $data['vehicles'][$key]['cost']['2']['total'] += $row2['CostValue'];
                    array_push($data['vehicles'][$key]['cost']['2']['list'], array('CostDetail' => $row2['CostDetail'],
                        'CostValue' => $row2['CostValue'], 'Name' => $row2['Title'] . $row2['FirstName'] . ' ' . $row2['LastName'],));
                }
            }
        }

        //Make schedule by RID
        $route_rid = array();
        foreach ($route_detail as $row) {
            $route_rid[$row['RID']] = array(
                'RID' => $row['RID'],
                'RSource' => $row['RSource'],
                'RDestination' => $row['RDestination'],
                'list' => array(),
            );
        }
        foreach ($data['schedules'] as $row) {
            if (array_key_exists($row['RID'], $route_rid)) {
                array_push($route_rid[$row['RID']]['list'], $row);
            }
        }
        $data['route_rid'] = $route_rid;

        $data_debug = array(
//            'cost' => $data['cost'],
//            'cost_detail' => $data['cost_detail'], 
//            'cost_types' => $data['cost_types'],
//            'routes' => $data['routes'],
//            'route_details' => $data["route_details"],
//            'stations' => $data['stations'],
//            'vehicles' => $data['vehicles'],
//            'form' => $data['form'],
//            'schedules' => $data['schedules'],
//            'date' => $date,
//            'test' => $route_rid,
//            'input_modal_add' => $data['input_modal_add'],
//            'check_income_by_VID' => $this->m_cost->check_income_by_VID('1', '2015-06-14'),
        );

        $this->m_template->set_Title("ค่าใช้จ่าย $route_name");
        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Permission('COV');
        $this->m_template->set_Content('cost/view_cost', $data);
        $this->m_template->showTemplate();
    }

    public function add($rcode = NULL, $vtid = NULL, $vid = NULL, $ctid = NULL, $date = NULL) {
        if ($rcode == NULL || $vtid == NULL || $vid == NULL || $ctid == NULL || $date == NULL)
            redirect('cost');

        $page_title = 'เพิ่ม ' . $this->m_cost->get_cost_type($ctid)[0]['CostTypeName'] . ' ';
        $page_title .= $this->m_vehicle->get_vehicle_types($vtid)[0]['VTDescription'];

        if ($this->m_cost->validation_form_add() && $this->form_validation->run() == TRUE) {
            $form_data = $this->m_cost->get_post_form_add($ctid);
            //Combined post and data
            $form_data['cost']['CostDate'] = $date;
            $form_data['cost']['CostTypeID'] = $ctid;
            $form_data['cost']['CreateBy'] = $this->session->userdata('username');
            $form_data['cost']['CreateDate'] = $this->m_datetime->getDatetimeNow();
            $form_data['vehicles_has_cost']['VID'] = $vid;

            $rs = $this->m_cost->insert_cost($form_data);
            $form_data['status'] = $rs;
            if ($rs == TRUE) {
                //Alert success and redirect to candidate
                $alert['alert_message'] = "บันทึกรายการค่าใช้จ่ายสำเร็จ";
                $alert['alert_mode'] = "success";
                $this->session->set_flashdata('alert', $alert);
            } else {
                //Alert success and redirect to candidate
                $alert['alert_message'] = "กรุณาลองใหม่อีกครั้ง";
                $alert['alert_mode'] = "danger";
                $this->session->set_flashdata('alert', $alert);
            }

            redirect('cost/view/' . $rcode . '/' . $vtid . '/' . $date);
        }
        $data = array(
            'page_title' => $page_title,
            'form' => $this->m_cost->set_form_add($ctid, $vtid),
            'date' => $date,
            'vehicle' => $this->m_vehicle->get_vehicle($rcode, $vtid, $vid, NULL)[0],
            'form_open' => form_open('cost/add/' . $rcode . '/' . $vtid . '/' . $vid . '/' . $ctid . '/' . $date, array('class' => 'form-horizontal')),
            'form_close' => form_close(),
        );
        $data['page_title'] = $data['page_title'] . ' ' . $data['vehicle']['VCode'];

        $data_debug = array(
//            'form' => $data['form'],
//            'get_vehicle' => $data['vehicle'],
//            'form_data' => (isset($form_data)) ? $form_data : '',
        );

        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Permission('COA');
        $this->m_template->set_Content('cost/frm_cost', $data);
        $this->m_template->showTemplate();
    }

//ตรวจสอบเบอร์รถ
    public function check_vcode($str) {
        $con = array(
            'VCode' => $str,
            'VTID' => $this->input->post('VTID'),
        );
        $query = $this->db->get_where('vehicles', $con);
        if ($query->num_rows() <= 0) {
            $this->form_validation->set_message('check_vcode', 'ไม่พบข้อมูล %s');
            return FALSE;
        } else {
            return TRUE;
        }
    }

//    check dropdown
    public function check_dropdown($str) {
//        $this->input->post('PolicyType')
        if ($str === '0') {
            $this->form_validation->set_message('check_dropdown', 'เลือก %s');
            return FALSE;
        } else {
            return TRUE;
        }
    }

}
