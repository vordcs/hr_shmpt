<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class m_schedule extends CI_Model {

    public function get_schedule($date = NULL, $rid = NULL) {

        if ($date != NULL) {
            $this->db->where('Date', $date);
        }
        $query_schedule = $this->db->get("t_schedules_day");
        return $query_schedule->result_array();
    }

    public function insert_schedule($data) {
//        $this->db->truncate('t_schedules_day'); 
        $rs = array();
        if (count($data) > 0) {
            for ($i = 0; $i < count($data); $i++) {
                $rid = $data[$i]['RID'];
                $date = $data[$i]['Date'];
                $time_depart = $data[$i]['TimeDepart'];

                $rs[$i] = count($this->check_schedule($date, $rid, $time_depart));

                if (count($this->check_schedule($date, $rid, $time_depart)) == 0) {
                    $this->db->insert('t_schedules_day', $data[$i]);
                    $tsid = $data[$i]['TSID'];
                    $rs[$i] = "Date $date INSERT -> TSID = " . $tsid;
                }
            }
        }
        return $rs;
    }

    public function check_schedule($date, $rid, $time_depart) {

        $this->db->where('RID', $rid);
        $this->db->where('Date', $date);
        $this->db->where('TimeDepart', $time_depart);
        $query_schedule = $this->db->get("t_schedules_day");

        return $query_schedule->result_array();
    }

    public function run_schedule() {
        $rs = array();
        $route = $this->get_route_detail();
        $n = 0;
        foreach ($route as $r) {
            $rid = $r['RID'];
            $vt_name = $r['VTDescription'];
            $schedule_type = $r['ScheduleType'];
            $rcode = $r['RCode'];
            $source = $r['RSource'];
            $destination = $r['RDestination'];
            $start_point = $r['StartPoint'];
            $time_duration = $r['Time'];

            if ($schedule_type == '0') {
                $start_time = $r['StartTime'];
                $interval = $r['IntervalEachAround'];
                $around = $r['AroundNumber'];
                for ($i = 0; $i < (int) $around; $i++) {
                    $s_time = strtotime($start_time) + $interval * 60 * $i;
                    $e_time = strtotime("+$time_duration minutes", $s_time);
                    $tsid = $this->get_TSID($rid, $i + 1);
                    if ($tsid != '') {
                        $temp_schedual = array(
                            'TSID' => $tsid,
                            'RID' => $rid,
                            'SeqNo' => $i + 1,
                            'TimeDepart' => date('H:i:s', $s_time),
                            'TimeArrive' => date('H:i:s', $e_time),
                            'Date' => $this->m_datetime->getDateToday(),
                            'ScheduleNote' => " $vt_name เส้นทาง $rcode  $source - $destination (ไป $destination)",
                            'CreateDate' => $this->m_datetime->getDatetimeNow(),
                        );
                        $rs[$n] = $temp_schedual;
                        $n++;
                    }
                }
            } else {
                $schedule_manual = $this->get_schedule_master();

                foreach ($schedule_manual as $sm) {
                    if ($rid == $sm['RID']) {
                        $seq_no = $sm['SeqNo'];
                        $s_time = strtotime($sm['Time']);
                        $e_time = strtotime("+$time_duration minutes", $s_time);
                        $tsid = $this->get_TSID($rid, $seq_no);
                        if ($tsid != '') {
                            $temp_schedual = array(
                                'TSID' => $this->get_TSID($rid, $seq_no),
                                'RID' => $rid,
                                'SeqNo' => $seq_no,
                                'TimeDepart' => date('H:i:s', $s_time),
                                'TimeArrive' => date('H:i:s', $e_time),
                                'Date' => $this->m_datetime->getDateToday(),
                                'ScheduleNote' => " $vt_name เส้นทาง $rcode  $source - $destination (ไป $destination)",
                                'CreateDate' => $this->m_datetime->getDatetimeNow(),
                            );
                            $rs[$n] = $temp_schedual;
                            $n++;
                        }
                    }
                }
            }
        }
        return $rs;
    }

    public function get_TSID($rid, $around) {
        $tsid = '';

        $this->db->where("RID", $rid);
        $this->db->where("SeqNo", $around);
        $this->db->where('Date', $this->m_datetime->getDateToday());
        $query = $this->db->get('t_schedules_day');
        $schedual = $query->result_array();

        if (count($schedual) <= 0) {
//        วันที่
            $date = new DateTime();
            $tsid .=$date->format("Ymd");
//        เส้นทาง
            $tsid .= $rid;
//        เที่ยวที่
            $tsid .=str_pad($around, 3, '0', STR_PAD_LEFT);
        }

        return $tsid;
    }

    public function get_schedule_master($rid = NULL, $smid = NULL) {
        $this->db->join('t_routes_has_schedules_manual', 't_routes_has_schedules_manual.SMID = t_schedules_manual.SMID', 'left');
        if ($rid != NULL) {
            $this->db->where('RID', $rid);
        }
        $this->db->order_by('SeqNo');
        $query = $this->db->get('t_schedules_manual');

        return $query->result_array();
    }

    public function get_route($rcode = NULL, $vtid = NULL) {

        $this->db->join('vehicles_type', 'vehicles_type.VTID = t_routes.VTID');

        if ($rcode != NULL) {
            $this->db->where('RCode', $rcode);
        }

        if ($vtid != NULL) {
            $this->db->where('t_routes.VTID', $vtid);
        }
        $this->db->group_by(array('RCode', 't_routes.VTID'));
        $this->db->where('StartPoint', 'S');
//        $this->db->order_by('StartPoint');
        $query = $this->db->get('t_routes');
        return $query->result_array();
    }

    public function get_route_detail($rcode = NULL, $vtid = NULL) {
        $this->db->join('vehicles_type', 'vehicles_type.VTID = t_routes.VTID');
        if ($rcode != NULL) {
            $this->db->where('RCode', $rcode);
        }
        if ($vtid != NULL) {
            $this->db->where('t_routes.VTID', $vtid);
        }
        $this->db->order_by('StartPoint', 'DESC');
        $query = $this->db->get('t_routes');
        return $query->result_array();
    }

    function set_form_search($rcode = NULL, $vtid = NULL) {
        $i_RCode = array(
            'name' => 'RCode',
            'value' => set_value('RID'),
            'placeholder' => 'รหัสเส้นทาง',
            'class' => 'form-control');

        $i_VTID[0] = 'เลือกประเภทรถ';
        foreach ($this->get_vehicle_types() as $value) {
            $i_VTID[$value['VTID']] = $value['VTDescription'];
        }
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

        $dropdown = 'class="selecter_3" data-selecter-options = \'{"cover":"true"}\' ';



        $form_search_route = array(
            'form' => form_open('schedule/', array('id' => 'form_search_schedule')),
            'RCode' => form_input($i_RCode),
            'VTID' => form_dropdown('VTID', $i_VTID, set_value('VTID'), $dropdown),
            'RSource' => form_input($i_RSource),
            'RDestination' => form_input($i_RDestination),
        );
        return $form_search_route;
    }

    public function get_post_form_search() {
        $form_data = array(
            'RCode' => $this->input->post('RCode'),
            'VTID' => $this->input->post('VTID'),
            'RSource' => $this->input->post('RSource'),
            'RDestination' => $this->input->post('RDestination'),
        );
        return $form_data;
    }

    public function validation_form_search() {
        $this->form_validation->set_rules('RCode', 'รหัสเส้นทาง', 'trim|xss_clean');
        $this->form_validation->set_rules('VTID', 'ประเภทรถ', 'trim|xss_clean');
        $this->form_validation->set_rules('RSource', 'ต้นทาง', 'trim|xss_clean');
        $this->form_validation->set_rules('RDestination', 'ปลายทาง', 'trim|xss_clean');
    }

    public function get_vehicle_types($id = NULL) {
        if ($id != NULL) {
            $this->db->where('VTID', $id);
        }
        $temp = $this->db->get('vehicles_type');
        return $temp->result_array();
    }

}
