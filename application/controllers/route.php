<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class route extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->library('form_validation');
        $this->load->model('m_route');
        $this->load->model('m_station');
        //Initial language
        $this->m_template->set_Language(array('plan'));
    }

    private $rcode = NULL;

    function set_rcode($id) {
        $this->rcode = $id;
    }

    private $type_id = NULL;

    function set_type($type_id) {
        $this->type_id = $type_id;
    }

    public function index() {

        $data = array(
            'form_search' => $this->m_route->set_form_search_route(),
            'route_detail' => $this->m_route->get_route_detail(),
            'stations' => $this->m_station->get_stations(),
        );

        $data['strtitle'] = '';
        $rcode = $this->input->post('RCode');
        $vtid = $this->input->post('VTID');
        $s = $this->input->post('RSource');
        $d = $this->input->post('RDestination');

        if ($rcode != NULL || ($vtid != NULL && $vtid != '0') || $s != NULL || $d != NULL) {
            $data['strtitle'] = '<strong>ผลการค้นหา  : </strong>';
        }
        if ($rcode != NULL || $s != NULL || $d != NULL) {
            $data['route'] = $this->m_route->search_route($rcode, $s, $d);
            if (count($data['route']) > 0)
                $data['strtitle'] .= $rcode != NULL ? 'เส้นทาง :' . $data['route'][0]['RCode'] : '';
            $data['strtitle'] .= $s != NULL ? 'ต้นทาง : ' . $s : '';
            $data['strtitle'] .= $d != NULL ? 'ปลายทาง : ' . $d : '';
        } else {
            $data['route'] = $this->m_route->get_route();
        }

        if ($vtid != NULL && $vtid != '0') {
            $data['vehicles_type'] = $this->m_route->get_vehicle_types($vtid);
            $data['strtitle'] .=$data['vehicles_type'][0]['VTDescription'];
        } else {
            $data['vehicles_type'] = $this->m_route->get_vehicle_types();
        }

        $data_debug = array(
//            'vehicles_type' => $data['vehicles_type'],
//            'route' => $data['route'],
//            'route_detail' => $data['route_detail'],
//            'form_route' => $data['form_route'],
            'stations' => $data['stations']
        );

        $this->m_template->set_Title('การจัดการรถ');
//        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Content('routes/routes', $data);
        $this->m_template->showTemplate();
    }

    public function add($type_id) {

        $this->set_type($type_id);

        if ($this->m_route->validation_form_add_route() && $this->form_validation->run() == TRUE) {
            $form_data = $this->m_route->get_post_form_add_route($type_id);
            $this->m_template->set_Debug($form_data);
            $rcode = $this->m_route->insert_route($form_data);

            $this->session->set_flashdata('rcode', $rcode);
            redirect('route/detail/' . $rcode);
        }

        $data = array(
            'page_title' => 'เพิ่มเส้นทาง ' . $this->m_route->get_vehicle_type_name($type_id),
            'form_route' => $this->m_route->set_form_add_route($type_id),
        );

        $this->m_template->set_Title('เพิ่มเส้นทาง');
//        $this->m_template->set_Debug($rs);
        $this->m_template->set_Content('routes/frm_route', $data);
        $this->m_template->showTemplate();
    }

    public function edit($rcode, $vtid) {
        $this->set_type($vtid);
        $this->set_rcode($rcode);
        if ($this->m_route->validation_form_edit_route() && $this->form_validation->run() == TRUE) {
            $form_data = $this->m_route->get_post_form_edit_route();
//            $route_detail = $this->m_route->get_route_detail($rcode, $vtid);
//            $this->m_template->set_Debug($route_detail);
            //Update data
            $this->m_route->update_route($rcode, $vtid, $form_data);
            redirect('route/time/' . $rcode . '/' . $vtid);
        }
//        Check detail and sent to load form
        $detail = $this->m_route->get_route($rcode, $vtid);
        if ($detail[0] != NULL) {
            $route_name = 'เส้นทาง สาย ' . $detail[0]['RCode'] . ' ' . ' ' . $detail[0]['RSource'] . ' - ' . $detail[0]['RDestination'];

            $data = array(
                'page_title' => 'แก้ไขข้อมูล ' . $detail[0]['VTDescription'] . ' ' . $route_name,
                'form_route' => $this->m_route->set_form_edit_route($detail[0]),
            );
        } else {
            redirect('route/');
        }

        $this->m_template->set_Title('แก้ไขข้อมูลเส้นทาง');
//        $this->m_template->set_Debug($detail);
        $this->m_template->set_Content('routes/frm_route', $data);
        $this->m_template->showTemplate();
    }

    public function time($rcode, $vtid) {
        $route_detail = $this->m_route->get_route($rcode, $vtid);
        if (count($route_detail) <= 0)
            echo "<script>window.location.href='javascript:history.back(-1);'</script>";
        $source = $route_detail[0]['RSource'];
        $desination = $route_detail[0]['RDestination'];
        $vt_name = $route_detail[0]['VTDescription'];
        $route_name = 'เส้นทาง สาย ' . $route_detail[0]['RCode'] . ' ' . ' ' . $source . ' - ' . $desination;

        $data = array(
            'page_title' => 'กำหนดตารางเวลาเดินรถ<i>' . $vt_name . '</i> ' . $route_name,
            'page_title_small' => '',
            'form' => $this->m_station->set_form_add($rcode, $vtid),
            'route_detail' => $route_detail[0],
        );
        $this->m_template->set_Title('แก้ไขข้อมูลเส้นทาง');
//        $this->m_template->set_Debug($detail);
        $this->m_template->set_Content('routes/frm_route_time', $data);
        $this->m_template->showTemplate();
    }

    public function detail($rocde = NULL) {

        // get flashdata
        $rcode = '264'; //$this->session->flashdata('rcode');

        if ($rcode) {
            // pass message to view, etc...
        }

        $data = array(
            'page_title' => 'เพิ่มจุดจอดและกำหนดอัตราค่าโดยสาร สาย ' . $rcode,
            'rcode' => $rcode,
        );

        $this->m_template->set_Title('เพิ่มจุดจอดและกำหนดอัตราค่าโดยสาร');
        $this->m_template->set_Debug($data);
        $this->m_template->set_Content('routes/frm_route_detail', $data);
        $this->m_template->showTemplate();
    }

//    ตารางเวลาเดินรถ
    public function schedule($rocde = NULL) {

        // get flashdata
        $rcode = '264'; //$this->session->flashdata('rcode');

        if ($rcode) {
            // pass message to view, etc...
        }

        $data = array(
            'page_title' => 'ตารางเวลาเดินรถ ' . $rcode,
            'rcode' => $rcode,
        );

        $this->m_template->set_Title('ตารางเวลาเดินรถ');
        $this->m_template->set_Debug($data);
        $this->m_template->set_Content('routes/frm_route_detail', $data);
        $this->m_template->showTemplate();
    }

    public function add_schedule($rocde = NULL) {

        // get flashdata
        $rcode = '264'; //$this->session->flashdata('rcode');

        if ($rcode) {
            // pass message to view, etc...
        }

        $data = array(
            'page_title' => 'ตารางเวลาเดินรถ ' . $rcode,
            'rcode' => $rcode,
        );

        $this->m_template->set_Title('ตารางเวลาเดินรถ');
        $this->m_template->set_Debug($data);
        $this->m_template->set_Content('routes/frm_route_detail', $data);
        $this->m_template->showTemplate();
    }

    public function edit_schedule($rcode = NULL) {

        // get flashdata
        $rcode = '264'; //$this->session->flashdata('rcode');

        $data = array(
            'page_title' => 'กำหนดตารางเวลาเดินรถ ' . $rcode,
            'rcode' => $rcode,
        );

        $this->m_template->set_Title('ตารางเวลาเดินรถ');
        $this->m_template->set_Debug($data);
        $this->m_template->set_Content('routes/frm_route_detail', $data);
        $this->m_template->showTemplate();
    }

//    จุดจอด
    public function station($rcode = NULL, $vtid = NULL) {

        $route_detail = $this->m_route->get_route($rcode, $vtid);

        if (count($route_detail) <= 0)
            echo "<script>window.location.href='javascript:history.back(-1);'</script>";
        $vt_name = $route_detail[0]['VTDescription'];
        $route_name = 'เส้นทาง สาย ' . $route_detail[0]['RCode'] . ' ' . ' ' . $route_detail[0]['RSource'] . ' - ' . $route_detail[0]['RDestination'];

        $data = array(
            'page_title' => 'จุดจอดและอัตตราค่าโดยสาร ' . $vt_name,
            'page_title_small' => $route_name,
            'rcode' => $rcode,
            'vtid' => $vtid,
            'stations' => $this->m_station->get_stations($rcode, $vtid),
            'route_detail' => $route_detail,
        );

        $data_debug = array(
//            'page_title' => 'จุดจอดและอัตตราค่าโดยสาร ' . $vt_name,
//            'page_title_small' => $route_name,
            'vtid' => $vtid,
            'rcode' => $rcode,
            'stations' => $data['stations'],
//            'route_detail' => $route_detail,
        );


        $this->m_template->set_Title('จุดจอดและอัตตราค่าโดยสาร');
//        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Content('stations/stations', $data);
        $this->m_template->showTemplate();
    }

    public function add_station($rcode = NULL, $vtid = NULL) {
        $route_detail = $this->m_route->get_route($rcode, $vtid);
        if (count($route_detail) <= 0)
            echo "<script>window.location.href='javascript:history.back(-1);'</script>";

        $vt_name = $route_detail[0]['VTDescription'];
        $route_name = 'เส้นทาง สาย ' . $route_detail[0]['RCode'] . ' ' . ' ' . $route_detail[0]['RSource'] . ' - ' . $route_detail[0]['RDestination'];

        if ($this->m_station->validation_form_add() && $this->form_validation->run() == TRUE) {

            $data_debug = array(
                'save' => 'Save ',
            );
            $this->m_template->set_Debug($data_debug);
        }

        $data = array(
            'page_title' => 'เพิ่มจุดจอด <i>' . $vt_name . '</i> ' . $route_name,
            'page_title_small' => '',
            'form' => $this->m_station->set_form_add($rcode, $vtid),
            'route_detail' => $route_detail[0],
        );


        $data_debug = array(
            'page_title' => $data['page_title'],
            'page_title_small' => $data['page_title_small'],
//            'form' => $data['form'],
            'route_detail' => $data['route_detail'],
//            '' => $data[''],
        );

        $this->m_template->set_Title('เพิ่มจุดจอด');
//        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Content('stations/frm_station', $data);
        $this->m_template->showTemplate();
    }

    public function edit_station($rcode = NULL) {

        // get flashdata
        $rcode = '264'; //$this->session->flashdata('rcode');

        $data = array(
            'page_title' => 'แก้จุดจอดและอัตตราค่าโดยการ สาย ' . $rcode,
            'rcode' => $rcode,
        );

        $this->m_template->set_Title('แก้ไขจุดจอดและอัตตราค่าโดยการ');
        $this->m_template->set_Debug($data);
        $this->m_template->set_Content('routes/frm_station_price', $data);
        $this->m_template->showTemplate();
    }

//   อัตตราค่าโดยการ
    public function price($rcode = NULL, $vtid = NULL) {

        $route_detail = $this->m_route->get_route($rcode, $vtid);

        if (count($route_detail) <= 0)
            echo "<script>window.location.href='javascript:history.back(-1);'</script>";
        $vt_name = $route_detail[0]['VTDescription'];
        $route_name = 'เส้นทาง สาย ' . $route_detail[0]['RCode'] . ' ' . ' ' . $route_detail[0]['RSource'] . ' - ' . $route_detail[0]['RDestination'];

        $data = array(
            'page_title' => 'จุดจอดและอัตตราค่าโดยสาร ' . $vt_name,
            'page_title_small' => $route_name,
            'rcode' => $rcode,
            'route_detail' => $route_detail[0],
        );

        $data_debug = array(
            'page_title' => 'จุดจอดและอัตตราค่าโดยสาร ' . $vt_name,
            'page_title_small' => $route_name,
            'rcode' => $rcode,
//            'route_detail' => $route_detail,
        );


        $this->m_template->set_Title('จุดจอดและอัตตราค่าโดยการ');
        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Content('routes/station_price', $data);
        $this->m_template->showTemplate();
    }

    public function add_price($rcode = NULL, $vtid = NULL) {

        // get flashdata
        $rcode = '264'; //$this->session->flashdata('rcode');
        $data = array(
            'page_title' => 'แก้จุดจอดและอัตตราค่าโดยการ',
            'page_title_small' => 'สาย ' . $rcode,
            'rcode' => $rcode,
        );

        $this->m_template->set_Title('แก้ไขจุดจอดและอัตตราค่าโดยการ');
        $this->m_template->set_Debug($data);
        $this->m_template->set_Content('routes/frm_station_price', $data);
        $this->m_template->showTemplate();
    }

    public function edit_price($rcode = NULL) {

        // get flashdata
        $rcode = '264'; //$this->session->flashdata('rcode');



        $data = array(
            'page_title' => 'แก้จุดจอดและอัตตราค่าโดยการ สาย ' . $rcode,
            'rcode' => $rcode,
        );

        $this->m_template->set_Title('แก้ไขจุดจอดและอัตตราค่าโดยการ');
        $this->m_template->set_Debug($data);
        $this->m_template->set_Content('routes/frm_station_price', $data);
        $this->m_template->showTemplate();
    }

    public function check_route_duplication($str) {
        $con = array(
            'RCode' => $str,
            'VTID' => $this->type_id,
        );
        $query = $this->db->get_where('t_routes', $con);
        if ($query->num_rows() > 0) {

            if ($this->rcode != NULL) {
                return TRUE;
            }
            $this->form_validation->set_message('check_route_duplication', ' %s ' . $str . ' ถูกใช้งานเเล้ว ');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function check_dropdown($str) {
        if ($str === '0') {
            $this->form_validation->set_message('check_dropdown', 'เลือก %s');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function check_route() {
        $old_rcode = $this->rcode;
        $old_vtid = $this->type_id;

        $new_rcode = $this->input->post('RCode');
        $new_vtid = $this->input->post('VTID');

        if ($old_rcode === $new_rcode && $old_vtid === $new_vtid) {
            return TRUE;
        }

        $con = array(
            'RCode' => $new_rcode,
            'VTID' => $new_vtid,
        );
        $query = $this->db->get_where('t_routes', $con);
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('check_route', ' %s  ถูกใช้งานเเล้ว ');
            return FALSE;
        } else {
            return TRUE;
        }
    }

}

//if ($this->m_products->validation_set_form_add() && $this->form_validation->run() == TRUE) {