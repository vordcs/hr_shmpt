<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_auto_schedule_vehicle extends CI_Model {

    public function get_schedules($date = NULL, $rid = NULL) {

        if ($date != NULL) {
            $this->db->where('Date', $date);
        }
        $this->db->order_by('RID');
        $query_schedule = $this->db->get("t_schedules_day");
        return $query_schedule->result_array();
    }

    public function get_schedule_id($date, $rid, $seq_no = NULL) {
        $this->db->where('t_schedules_day.Date', $date);
        $this->db->where('t_schedules_day.RID', $rid);
        $query_schedule = $this->db->get("t_schedules_day");
        return $query_schedule->row_array();
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
                    $tsid = $this->gen_TSID($rid, $i + 1);
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
                        $tsid = $this->gen_TSID($rid, $seq_no);
                        if ($tsid != '') {
                            $temp_schedual = array(
                                'TSID' => $tsid,
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

    public function gen_TSID($rid, $around) {
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

    public function set_vehicle_to_schedule() {
        $rs = array();
        $date = $this->m_datetime->getDateToday();
        $routes = $this->get_route();
        $routes_detail = $this->get_route_detail();
        $i = 0;
        foreach ($routes as $r) {
            $rcode = $r['RCode'];
            foreach ($routes_detail as $rd) {
                if ($rcode == $rd['RCode']) {
                    $rid = $rd['RID'];
                    $start_point = $rd['StartPoint'];

                    $schedule = $this->get_schedule_id($date, $rid);

                    $rs[$rcode][$rid] = $schedule; //$start_point;
                }
            }

//            $rid = $route['RID'];
//            
//
//            $schedule = $this->get_schedule_id($date, $rid);
//
//            $rs[$rid] = $schedule;

            $i++;
        }
        return $rs;
    }

    public function get_vehicles_current_station($date = NULL, $rcode = NULL, $vtid = NULL) {
        
    }

    public function set_initial_station($rcode, $vtid) {
        $vehicles =  $this->get_vehicle($rcode, $vtid);
        $i++;
        $stations = $this->get_stations($rcode, $vtid);
        $first_seq =1;
        $last_seq = count($stations);
        $first_station_id ='';
        $last_station_id ='';
        
        foreach ($stations as $station) {
            if ($first_seq==$station['Seq']) {
                $first_station_id=$station['SID'];
            }
            if($last_seq==$station['Seq']){
                $last_station_id=$station['SID'];
            }
        }        
        foreach ($vehicles as $vehicle) {
            $vid = $vehicle['VID'];
            
            if ($i<5) {
                $dats_initial =array(
                    ''=>'',
                );
            }  else {
                
            }
            $i++;
        }
    }

    public function get_vehicle($rcode, $vtid) {
        $this->db->join('vehicles_type', 'vehicles.VTID = vehicles_type.VTID');
        $this->db->join('t_routes_has_vehicles', 'vehicles.VID = t_routes_has_vehicles.VID');
        if ($vtid != NULL) {
            $this->db->where('vehicles.VTID', $vtid);
        }
        if ($rcode != NULL) {
            $this->db->where('t_routes_has_vehicles.RCode', $rcode);
        }
        $query = $this->db->get('vehicles');
        return $query->result_array();
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
//        $this->db->order_by('StartPoint', 'DESC');
        $query = $this->db->get('t_routes');
        return $query->result_array();
    }
    
       public function get_stations($rcode = null, $vtid = null, $sid = NULL, $seq = NULL) {
        if ($rcode != NULL) {
            $this->db->where('RCode', $rcode);
        }
        if ($vtid != NULL) {
            $this->db->where('VTID', $vtid);
        }
        if ($sid != NULL) {
            $this->db->where('SID', $sid);
        }
        if ($seq != NULL) {
            $this->db->where('Seq', $seq);
        }
        $this->db->order_by('Seq');
        $query = $this->db->get('t_stations');

        $rs = $query->result_array();

        return $rs;
    }

}
