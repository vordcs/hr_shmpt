<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('m_template');
        $this->load->model('m_route');
        $this->load->library('form_validation');

        //Initial language
        $this->m_template->set_Language(array('plan'));
    }

    public function index() {
        $query_schedule = $this->db->get("t_schedules_day");

        $this->db->join('vehicles_type', 'vehicles_type.VTID = t_routes.VTID');
        $route = $this->db->get("t_routes")->result_array();

        $schedule_manual = $this->m_route->get_schedule_manual();

        $data = array(
//            'route' => $route,
//            'schedule_manual' => $schedule_manual,
            'schedule' => $query_schedule->result_array(),
            'result' => $this->run_schedule(),
        );
        //$this->m_template->set_Permission('SSL');
        $this->m_template->set_Content('home/main', $data);
        $this->m_template->showTemplate();
    }

    public function run_schedule() {
        $rs = array();
        $this->db->join('vehicles_type', 'vehicles_type.VTID = t_routes.VTID');
        $query_route = $this->db->get("t_routes");

        $route = $query_route->result_array();

        foreach ($route as $r) {
            $rid = $r['RID'];
            $vt_name = $r['VTDescription'];
            $schedule_type = $r['ScheduleType'];
            $rcode = $r['RCode'];
            $source = $r['RSource'];
            $destination = $r['RDestination'];
            $start_point = $r['StartPoint'];

            if ($schedule_type == '0') {

                $start_time = $r['StartTime'];
                $interval = $r['IntervalEachAround'];
                $around = $r['AroundNumber'];

                for ($i = 1; $i <= (int) $around; $i++) {
                    $time = strtotime($start_time) + $interval * 60 * $i;
                    $rs["$rid"][$i] = array(
                        'RID' => $rid,
                        'SeqNo' => $i,
                        'TimeDepart' => date('H:i:s', $time),
                        'Date' => $this->m_datetime->getDateToday(),
                        'ScheduleNote' => "$vt_name เส้นทาง $rcode  $source - $destination",
                        'CreateDate' => $this->m_datetime->getDatetimeNow(),
                    );
                }
            } else {
                $schedule_manual = $this->m_route->get_schedule_manual();
                $i = 1;
                foreach ($schedule_manual as $sm) {
                    if ($rid == $sm['RID']) {
                        $seq_no = $sm['SeqNo'];
                        $time = strtotime($sm['Time']);
                        $rs["$rid"] [$i] = array(
                            'RID' => $rid,
                            'SeqNo' => $seq_no,
                            'TimeDepart' => date('H:i:s', $time),
                            'Date' => $this->m_datetime->getDateToday(),
                            'ScheduleNote' => "$vt_name เส้นทาง $rcode  $source - $destination  $start_point",
                            'CreateDate' => $this->m_datetime->getDatetimeNow(),
                        );

                        $i++;
                    }
                }
            }
        }

        return $rs;
    }

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */