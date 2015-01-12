<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class schedule extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->library('form_validation');
        $this->load->model('m_route');
        $this->load->model('m_station');
        $this->load->model('m_vehicle');
        $this->load->model('m_schedule');
        //Initial language
        $this->m_template->set_Language(array('plan'));
    }

    public function index() {

        $data = array(
            'form_search' => $this->m_schedule->set_form_search(),
            'page_title' => '',
            'page_title_small' => '',
            'route_detail' => $this->m_route->get_route_detail(),
        );

        $data['schedules'] = $this->m_schedule->get_schedule($this->m_datetime->getDateToday());
        $data['vehicles'] = $this->m_schedule->get_vehicle();
        $data['vehicles_type'] = $this->m_vehicle->get_vehicle_types();
        $data['route'] = $this->m_route->get_route();
        $data['stations'] = $this->m_station->get_stations();
        $data['schedule_master'] = $this->m_route->get_schedule_manual();


        $data_debug = array(
//            'vehicles' => $data['vehicles'],
//            'vehicles_type' => $data['vehicles_type'],
//            'route' => $data['route'],
//            'route_detail' => $data['route_detail'],
//            'form_search' => $data['form_search'],
//            'stations' => $data['stations'],
//            'schedules' => $data['schedules'],
        );

        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Title("ตารางเวลาเดินรถ");
        $this->m_template->set_Content('schedule/schedule', $data);
        $this->m_template->showTemplate();
    }

    public function view($rcode, $vtid) {

//        REMOVE_TSID_S

        $route_detail = $this->m_route->get_route($rcode, $vtid);
        $vt_name = $route_detail[0]['VTDescription'];
        $source = $route_detail[0]['RSource'];
        $desination = $route_detail[0]['RDestination'];

        $route_name = $vt_name . ' เส้นทาง ' . $route_detail[0]['RCode'] . ' ' . ' ' . $source . ' - ' . $desination;

        $data = array(
//            'form_search' => $this->m_schedule->set_form_search(),
            'page_title' => 'ตารางเวลาเดิน ' . $route_name,
            'page_title_small' => '',
            'route_detail' => $this->m_route->get_route_detail($rcode, $vtid),
        );

        $data['schedules'] = $this->m_schedule->get_schedule($this->m_datetime->getDateToday(), $rcode, $vtid);

        $data['vehicles_type'] = $this->m_vehicle->get_vehicle_types();
        $data['route'] = $this->m_route->get_route();
        $data['stations'] = $this->m_station->get_stations($rcode, $vtid);
        $data['schedule_master'] = $this->m_route->get_schedule_manual();


        $data['form_open'] = form_open('schedule/view/' . $rcode . '/' . $vtid, array('id' => 'form_main'));
        $data['form_close'] = form_close();

        $data_debug = array(
//            'vehicles_type' => $data['vehicles_type'],
//            'route' => $data['route'],
//            'route_detail' => $data['route_detail'],
//            'stations' => $data['stations'],
//            'form_search' => $data['form_search'],
//            'stations' => $data['stations']
//            'schedules' => $data['schedules'],
            'post' => $this->input->post(),
        );


        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Title("ตารางเวลาเดิน $route_name");
        $this->m_template->set_Content('schedule/view_schedule', $data);
        $this->m_template->showTemplate();
    }

    public function add($rcode, $vtid, $rid) {

        $route_detail = $this->m_route->get_route($rcode, $vtid, $rid);
        $vt_name = $route_detail[0]['VTDescription'];
        $source = $route_detail[0]['RSource'];
        $desination = $route_detail[0]['RDestination'];

        $route_name = $vt_name . ' เส้นทาง ' . $route_detail[0]['RCode'] . ' ' . ' ' . $source . ' - ' . $desination;

        $data = array(
//            'form_search' => $this->m_schedule->set_form_search(),
            'page_title' => 'เพิ่มเที่ยวรถ',
            'page_title_small' => ' ' . $vt_name,
            'previous_page' => "schedule/view/$rcode/$vtid/$rid",
            'next_page' => "schedule/view/$rcode/$vtid/$rid",
        );

        $form_data = $rs = array();
        if ($this->m_schedule->validation_form_add() && $this->form_validation->run() == TRUE) {
            $form_data = $this->m_schedule->get_post_form_add();

            //เตรียมข้อมูลก่อนการ insert t_schedules_day
            $duration = $route_detail[0]['Time'];
            $t = date('H:i', strtotime("+$duration minutes", strtotime($form_data['TimeDepart'])));
            $prepare_t_schedules_day = array(
                'TSID' => $form_data['TSID'],
                'RID' => $form_data['RID'],
                'Date' => $form_data['Date'],
                'TimeDepart' => $form_data['TimeDepart'],
                'TimeArrive' => $t,
                'ScheduleStatus' => '1',
                'ScheduleNote' => $route_name . '(ไป ' . (($route_detail[0]['StartPoint'] == 'S') ? $route_detail[0]['RDestination'] : $route_detail[0]['RSource']) . ' รอบเสริม)',
            );
            //นำข้อมูลเข้า
            $result['insert'] = $this->m_schedule->insert_new_t_schedules_day($prepare_t_schedules_day);


            /*
             * ดึงข้อมูลการทำงานจัดเรียงลำดับใหม่
             */
            $result['sort'] = $this->m_schedule->get_schedule_to_sort($form_data['RID'], $this->m_datetime->getDateToday());
            $temp1 = $result['sort'];
            $temp2 = $result['sort'];
            $i2 = 0;
            $flag = FALSE;
            for ($i1 = 0; $i1 < count($temp1); $i1++, $i2++) {
                if ($temp1[$i1]['VID'] == NULL && $flag != TRUE) {
                    if ($i2 + 1 < count($temp2)) {
                        $temp1[$i1]['VID'] = $temp2[$i2 + 1]['VID'];
                        $flag = TRUE;
                    }
                }
                if ($flag) {
                    if ($i2 + 1 < count($temp2)) {
                        $temp1[$i1]['VID'] = $temp2[$i2 + 1]['VID'];
                    } else {
                        //ตรวจสอบรถที่จะนำเขามาเสริมในคิว
                        $RID = $form_data['RID'];
                        $RCode = $form_data['RCode'];
                        $VTID = $route_detail[0]['VTID'];
                        $Free_VID = $this->m_schedule->get_next_vehicle($RCode, $VTID, $RID);
                        $temp1[$i1]['VID'] = $Free_VID[0]['VID'];

                        //ตรวจสอบสถานีสุดท้าย ตัวนี้จะเป็นถ้าเริ่ม S ต้องไปเอาตัวสุดท้าย, ถ้าเป็น D ต้องไปเอาตัวแรก
                        $StartPoint = $this->m_schedule->get_route_detail($RCode, $VTID, $RID)[0]['StartPoint'];
                        if ($StartPoint == 'S') {
                            $temp = end($this->m_schedule->get_stations($RCode, $VTID));
                            $next_station_id = $temp['SID'];
                        } else {
                            $temp = $this->m_schedule->get_stations($RCode, $VTID)[0];
                            $next_station_id = $temp['SID'];
                        }

                        //ดึงเวลาสุดท้ายเพื่อไปต่อคิว
                        //อัพเดทตำแหน่งปัจจุบันของรถ
                        $temp = end($Free_VID);
                        $duration = $route_detail[0]['Time'];
                        $t = date('H:i', strtotime("+$duration minutes", strtotime($temp['CurrentTime'])));
                        $new_last_time = $temp['CurrentTime'];
                        $this->m_schedule->update_vehicle_curent_stations($Free_VID[0]['VID'], $next_station_id, $new_last_time);

                        // อัพเดทตาราง vehicles_has_schedules ที่ได้จากการ sort
                        $this->m_schedule->update_vehicles_has_schedules_with_new_data($temp1);

                        //Alert success and redirect to candidate
                        $alert['alert_message'] = "เพิ่มรอบ " . $vt_name . " เวลา " . $form_data['TimeDepart'] . " ของเส้นทาง " . $route_name . " สำเร็จแล้ว";
                        $alert['alert_mode'] = "success";
                        $this->session->set_flashdata('alert', $alert);
                        redirect('schedule/view/' . $rcode . '/' . $vtid);
                    }
                }
            }



//            $rs = $this->m_seller->insert_seller($form_data);
//
//            $alert['alert_message'] = "เพิ่มข้อมูลสำเร็จ";
//            $alert['alert_mode'] = "success";
//            $this->session->set_flashdata('alert', $alert);
//
//            redirect('hr/seller/');
        }

        $data['form'] = $this->m_schedule->set_form_add($rcode, $vtid, $rid);
        $get_vehicle_current_stations = $this->m_schedule->get_vehicle_current_stations($rcode, $vtid, 1);
        $data_debug = array(
//            'vehicles_type' => $data['vehicles_type'],
//            'route' => $data['route'],
//            'route_detail' => $route_detail,
//            'stations' => $data['stations'],
//            'form_search' => $data['form_search'],
//            'stations' => $data['stations']
//            'schedules' => $data['schedules'],
//            'post' => $this->input->post(),
//            'form_data' => $form_data,
//            'get_vehicle_current_stations' => $get_vehicle_current_stations,
//            'prepare_t_schedules_day' => isset($prepare_t_schedules_day) ? $prepare_t_schedules_day : '',
//            'result' => isset($result) ? $result : '',
//            'temp' => isset($temp1) ? $temp1 : '',
//            'last' => isset($Free_VID) ? $Free_VID : '',
//            'temp' => isset($temp) ? $temp : '',
        );



        $this->m_template->set_Debug($data_debug);
        $this->m_template->set_Title("ตารางเวลาเดิน $route_name");
        $this->m_template->set_Content('schedule/frm_schedule', $data);
        $this->m_template->showTemplate();
    }

    //    ตรวจสอบค่าใน dropdown
    public function check_dropdown($str) {
        if ($str === '0') {
            $this->form_validation->set_message('check_dropdown', 'เลือก %s');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function check_schedule($str) {
        $rid = $this->input->post('RID');
        $time_depart = $str; //$this->input->post('TimeDepart');
        $date = $this->input->post('Date');

        if ($this->m_schedule->IsExitSchedule($date, $rid, $time_depart)) {
            $this->form_validation->set_message('check_schedule', '%s ถูกใช้งานแล้ว');
            return FALSE;
        } else {
            return TRUE;
        }
    }

}
