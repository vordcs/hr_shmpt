<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class station extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->library('form_validation');
        $this->load->model('m_station');
        $this->load->model('m_route');

        //Initial language
        $this->m_template->set_Language(array('plan'));
    }

    public function index() {
        
    }

    public function add($rcode = NULL, $vtid = NULL) {
        if ($rcode == NULL || $vtid == NULL) {
            echo "<script>window.location.href='javascript:history.back(-1);'</script>";
        }

        $route_detail = $this->m_route->get_route($rcode, $vtid);
        $vt_name = $route_detail[0]['VTDescription'];
        $source = $route_detail[0]['RSource'];
        $desination = $route_detail[0]['RDestination'];

        if (count($route_detail) <= 0) {
            echo "<script>window.location.href='javascript:history.back(-1);'</script>";
        }

        $route_name = 'เส้นทาง สาย ' . $route_detail[0]['RCode'] . ' ' . ' ' . $source . ' - ' . $desination;

        $data = array(
            'page_title' => 'เพิ่มจุดจอด <i>' . $vt_name . '</i> ' . $route_name,
            'page_title_small' => '',
            'form' => $this->m_station->set_form_add($rcode, $vtid),
            'previous_page' => 'route/time/' . $rcode . '/' . $vtid,
            'next_page' => '',
        );

        $data['route_detail'] = $route_detail[0];

        $stations = $this->m_station->get_stations($rcode, $vtid);
        if (count($stations) > 0) {
            redirect('station/edit/' . $rcode . '/' . $vtid);
        }

        if ($this->m_station->validation_form_add() && $this->form_validation->run() == TRUE) {
            $form_data = $this->m_station->get_post_form_add();
            $rs = $this->m_station->insert_station($rcode, $vtid, $form_data);
            $data_debug = array(
                'form_data' => $form_data,
                'result' => $rs,
            );
            $this->m_template->set_Debug($data_debug);
            $alert['alert_message'] = "เพิ่มข้อมูล จุดจอด " . $route_name;
            $alert['alert_mode'] = "success";
            $this->session->set_flashdata('alert', $alert);

            redirect('fares/add/' . $rcode . '/' . $vtid);
        }
//        $data_debug = array(
////            'page_title' => $data['page_title'],
////            'page_title_small' => $data['page_title_small'],
//             'form' => $data['form'],            
////            'route_detail' => $data['route_detail'],
////            'Name' => $this->input->post('StationName'),
////            'post' => $post,
////            '' => $data[''],
//        );

        $this->m_template->set_Title('เพิ่มจุดจอด');
//        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Content('stations/frm_station', $data);
        $this->m_template->showTemplate();
    }

    public function edit($rcode = NULL, $vtid = NULL) {
        if ($rcode == NULL || $vtid == NULL) {
            echo "<script>window.location.href='javascript:history.back(-1);'</script>";
        }

        $route_detail = $this->m_route->get_route($rcode, $vtid);
        if (count($route_detail) <= 0) {
            echo "<script>window.location.href='javascript:history.back(-1);'</script>";
        }

        $stations = $this->m_station->get_stations($rcode, $vtid);
        if (count($stations) <= 0) {
            redirect('station/add/' . $rcode . '/' . $vtid);
        }

        $source = $route_detail[0]['RSource'];
        $desination = $route_detail[0]['RDestination'];
        $vt_name = $route_detail[0]['VTDescription'];
        $route_name = 'เส้นทาง สาย ' . $route_detail[0]['RCode'] . ' ' . ' ' . $source . ' - ' . $desination;

        if ($this->m_station->validation_form_edit() && $this->form_validation->run() == TRUE) {
            $form_data = $this->m_station->get_post_form_edit();
            $rs = $this->m_station->update_station($rcode, $vtid, $form_data);
            $data_debug_post = array(
                'form_data' => $form_data,
                'result' => $rs,
            );
            $this->m_template->set_Debug($data_debug_post);
            $alert['alert_message'] = "บันทึกข้อมูล จุดจอด " . $route_name;
            $alert['alert_mode'] = "success";
            $this->session->set_flashdata('alert', $alert);

            redirect('fares/edit/' . $rcode . '/' . $vtid);
        }

        $data = array(
            'page_title' => 'เพิ่มและแก้ไขจุดจอด <i>' . $vt_name . '</i> ' . $route_name,
            'page_title_small' => '',
            'form' => $this->m_station->set_form_edit($rcode, $vtid),
            'route_detail' => $route_detail[0],
            'previous_page' => 'route/time/' . $rcode . '/' . $vtid,
            'next_page' => 'fares/add/' . $rcode . '/' . $vtid,
        );

        $data_debug = array(
//            'page_title' => $data['page_title'],
//            'page_title_small' => $data['page_title_small'],
//            'form' => $data['form'],
//            'route_detail' => $data['route_detail'],
//            'Name' => $this->input->post('StationName'),
//            'post' => $post,
//            'stations_db' => $this->m_station->get_stations($rcode, $vtid),
//            'stop_time_post' => $this->input->post('StopTime'),
//            '' => $data[''],
        );

        $this->m_template->set_Title('เพิ่มและแก้ไขจุดจอด');
//        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Content('stations/frm_station', $data);
        $this->m_template->showTemplate();
    }

}
