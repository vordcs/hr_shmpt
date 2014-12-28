<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class m_schedule_vehicle extends CI_Model {

    public function insert_schedule($data) {
//        $this->db->truncate('t_schedules_day'); 
        $rs = array();
        if (count($data) > 0) {
            for ($i = 0; $i < count($data); $i++) {
                $rid = $data[$i]['RID'];
                $date = $data[$i]['Date'];
                $time_depart = $data[$i]['TimeDepart'];
                $num_schedule = $this->check_schedule($date, $rid, $time_depart);
                if ($num_schedule == 0) {
                    $this->db->insert('t_schedules_day', $data[$i]);
                    $tsid = $data[$i]['TSID'];
                    $rs[$i] = "Date $date INSERT -> TSID = " . $tsid;
                }  else {
                    $rs[$i] = "Date $date UPDATE -> TSID = " . $tsid;
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

        $rs = $query_schedule->num_row();

        return $rs;
    }

    public function run_schedule() {
        $rs = array();
        $route = $this->get_route_detail();
        $n = 0;
        $x=0;
        foreach ($route as $r) {
            
            $rid = $r['RID'];
            $vt_name = $r['VTDescription'];
            $schedule_type = $r['ScheduleType'];
            $rcode = $r['RCode'];
            $source = $r['RSource'];
            $destination = $r['RDestination'];
            $start_point = $r['StartPoint'];
            $time_duration = $r['Time'];
            
             $rs['TEST'][$x] = $schedule_type;
            $x++;
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

    public function vehicles_initicial_station() {
        $routes = $this->get_route();
        foreach ($routes as $route) {
            $i = 0;
            $rcode = $route['RCode'];
            $vtid = $route['VTID'];
            $vehicles = $this->get_vehicle($rcode, $vtid);
//            กำหนดค่า สถานีเริ่มต้นและสถานี้สุดท้ายของเส้นทางในรถแต่ละคัน 
            $stations = $this->get_stations($rcode, $vtid);
            $first_seq = 1;
            $last_seq = count($stations);
            foreach ($stations as $station) {
                $sid = $station['SID'];
                $seq = $station['Seq'];
                if ($seq == $first_seq) {
                    $start_station_id = $sid;
                }
                if ($seq == $last_seq) {
                    $end_station_id = $sid;
                }
            }
            foreach ($vehicles as $vehicle) {
                $vid = $vehicle['VID'];
                if ($i <= 5) {
                    $sid = $start_station_id;
                    $seq = $first_seq;
                } else {
                    $sid = $end_station_id;
                    $seq = $last_seq;
                }

                $data[$i] = array(
                    'VID' => $vid,
                    'CurrentStationID' => $sid,
                    'CurrentStatonSeq' => $seq,
                    'CurrentTime' => $this->m_datetime->getTimeNow(),
                    'CurrentDate' => $this->m_datetime->getDateToday(),
                );
                $this->db->insert('vehicles_current_stations', $data[$i]);
                $i++;
            }
        }

        $query = $this->db->get('vehicles_current_stations');

        return $query->result_array();
    }

    public function set_vehicles_to_schedule() {
        $date = $this->m_datetime->getDateToday();
        $rs = array();
        $routes = $this->get_route();
        $routes_detail = $this->get_route_detail();
        $n = 0;
        $action = 'ACTION';
        foreach ($routes as $r) {
            $rcode = $r['RCode'];
            $vtid = $r['VTID'];
            //ค้นหาและกำหนดสถานีต้นทางปลายทาง
            $stations = $this->get_stations($rcode, $vtid);
            $first_seq = 1;
            $last_seq = count($stations);
//            ค้นหา ID สถานีต้นทาง และสถานีปลายทาง
            foreach ($stations as $station) {
                $sid = $station['SID'];
                $seq = $station['Seq'];
                if ($seq == $first_seq) {
                    $start_station_id = $sid;
                }
                if ($seq == $last_seq) {
                    $end_station_id = $sid;
                }
            }
            //
            $rid_s = '';
            $rid_d = '';
            foreach ($routes_detail as $rd) {
                if ($rcode == $rd['RCode']) {
                    $start_point = $rd['StartPoint'];
                    if ($start_point == "S") {
                        $rid_s = $rd['RID'];
                    } else {
                        $rid_d = $rd['RID'];
                    }
                }
            }

            $rs[$rcode]['S'] = $rid_s;
            $rs[$rcode]['D'] = $rid_d;


            $schedule_s = $this->get_schedule_by_route($date, $rid_s);
            $schedule_d = $this->get_schedule_by_route($date, $rid_d);
            $nun_schedule_s = count($schedule_s);
            $nun_schedule_d = count($schedule_d);

//            $rs[$rcode]['S'] = $schedule_s;//"$date, $rid_s" ;
//            $rs[$rcode]['D'] = $schedule_d ;//"$date, $rid_d";
//            $rs['schedule_s']=$schedule_s;
            //เปรียบเทียบเอาค่ามากที่สุด 
            $max_num_schedule = 0;
            if ($nun_schedule_s > $nun_schedule_d) {
                $max_num_schedule = $nun_schedule_s;
            } elseif ($nun_schedule_d > $nun_schedule_s) {
                $max_num_schedule = $nun_schedule_d;
            } else {
                $max_num_schedule = $nun_schedule_d;
            }
            $n = 0;
            for ($i = 0; $i < $max_num_schedule; $i++) {
                if (array_key_exists($i, $schedule_s)) {
                    $schedule = $schedule_s[$i];
                    $tsid_s = $schedule_s[$i]['TSID'];
                    $time_depart = $schedule['TimeDepart'];
                    $time_arrive = $schedule['TimeArrive'];

                    $vehicle = $this->get_vehicle_current_stations($rcode, $vtid, $start_station_id, $first_seq);
                    $vid = $vehicle['VID'];
                    $vcode = $vehicle['VCode'];
                    $current_time = $vehicle['CurrentTime'];

                    $data_vehicles_has_schedules = array(
                        'TSID' => $tsid_s,
                        'VID' => $vid,
                        'RID' => $rid_s,
                    );
                    $this->db->insert('vehicles_has_schedules', $data_vehicles_has_schedules);

                    $this->update_vehicle_curent_stations($vid, $end_station_id, $last_seq, $time_arrive);

                    $rs[$action][$rcode][$n]['S'] = $data_vehicles_has_schedules; //"เวลาออก $time_depart เวลาถึง $time_arrive -> $tsid รถเบอร์ $vcode vid->$vid | -> $current_time <- |$vid, $end_station_id, $last_seq, $time_arrive"; //"S-> $tsid เวลาออก $time_depart | เวลาถึง $time_arrive | รถเบอร์ $vcode | UPDATE $vcode TO last_seq ->$last_seq |";
//                    $n++;
                }
                if (array_key_exists($i, $schedule_d)) {
                    $schedule = $schedule_d[$i];
                    $tsid_d = $schedule['TSID'];
                    $time_depart = $schedule['TimeDepart'];
                    $time_arrive = $schedule['TimeArrive'];

                    $vehicle = $this->get_vehicle_current_stations($rcode, $vtid, $end_station_id);
                    $vid = $vehicle['VID'];
                    $vcode = $vehicle['VCode'];
                    $current_time = $vehicle['CurrentTime'];


                    $data_vehicles_has_schedules = array(
                        'TSID' => $tsid_d,
                        'VID' => $vid,
                        'RID' => $rid_d,
                    );
                    $this->db->insert('vehicles_has_schedules', $data_vehicles_has_schedules);

                    $this->update_vehicle_curent_stations($vid, $start_station_id, $first_seq, $time_arrive);

                    $rs[$action][$rcode][$n]['D'] = $data_vehicles_has_schedules; //"เวลาออก $time_depart เวลาถึง $time_arrive -> $tsid รถเบอร์ $vcode vid->$vid | -> $current_time <- |$vid, $end_station_id, $last_seq, $time_arrive"; //"D-> $tsid เวลาออก $time_depart | เวลาถึง $time_arrive |รถเบอร์ $vcode | UPDATE $vcode TO first_seq->$first_seq |";
                }
                $n++;
            }
        }

        return $rs;
    }

    public function get_schedule($date = NULL, $rid = NULL) {
        $this->db->join('t_routes', ' t_schedules_day.RID=t_routes.RID', 'left');
        $this->db->join('vehicles_has_schedules', 't_schedules_day.TSID = vehicles_has_schedules.TSID', 'left');
        $this->db->join('vehicles', 'vehicles_has_schedules.VID = vehicles.VID', 'left');
        if ($date != NULL) {
            $this->db->where('Date', $date);
        }
        if ($rid != NULL) {
            $this->db->where('t_routes.RID', $rid);
        }
        $this->db->order_by('SeqNo', 'ASC');
        $query_schedule = $this->db->get("t_schedules_day");
        return $query_schedule->result_array();
    }

    public function get_schedule_by_route($date = NULL, $rid = NULL) {

        $this->db->join('t_routes', ' t_schedules_day.RID=t_routes.RID', 'left');
        if ($date != NULL) {
            $this->db->where('Date', $date);
        }

        $this->db->where('t_routes.RID', $rid);

        $this->db->order_by('SeqNo', 'ASC');
        $query_schedule = $this->db->get("t_schedules_day");
        return $query_schedule->result_array();
    }

    public function get_vehicle_current_stations($rcode, $vtid, $station_id = NULL, $seq = NULL) {
        $this->db->join('vehicles', 'vehicles_current_stations.VID = vehicles.VID', 'left');
        $this->db->join('t_routes_has_vehicles', 'vehicles.VID = t_routes_has_vehicles.VID', 'left');

        $this->db->where('t_routes_has_vehicles.RCode', $rcode);
        $this->db->where('vehicles.VTID', $vtid);

        if ($station_id != NULL) {
            $this->db->where('vehicles_current_stations.CurrentStationID', $station_id);
        }

        if ($seq != NULL) {
            $this->db->where('vehicles_current_stations.CurrentStatonSeq', $seq);
        }

        $this->db->order_by('CurrentTime,CurrentDate,vehicles.VID', 'asc');
        $query = $this->db->get('vehicles_current_stations');

        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row;
        }
        return FALSE;
    }

    public function vehicle_curent_stations($rcode, $vtid, $station_id = NULL, $seq = NULL) {
        $this->db->join('vehicles', 'vehicles_current_stations.VID = vehicles.VID', 'left');
        $this->db->join('t_routes_has_vehicles', 'vehicles.VID = t_routes_has_vehicles.VID', 'right');

        $this->db->where('t_routes_has_vehicles.RCode', $rcode);
        $this->db->where('vehicles.VTID', $vtid);
        if ($seq != NULL) {
            $this->db->where('vehicles_current_stations.CurrentStatonSeq', $seq);
        }
        if ($station_id != NULL) {
            $this->db->where('vehicles_current_stations.CurrentStationID', $station_id);
        }
        $this->db->order_by('CurrentTime,CurrentDate', 'desc');
        $query = $this->db->get('vehicles_current_stations');

        if ($query->num_rows() > 0) {
//            $row = $query->row_array();
//            return $row;
            return $query->result_array();
        }
        return FALSE;
    }

    public function update_vehicle_curent_stations($vid, $next_station_id, $next_seq, $time_arrive) {
        $data_update = array(
            'CurrentTime' => $time_arrive,
            'CurrentDate' => $this->m_datetime->getDateToday(),
            'CurrentStationID' => $next_station_id,
            'CurrentStatonSeq' => $next_seq,
            'UpdateDate' => $this->m_datetime->getDatetimeNow(),
        );
        $this->db->where('VID', $vid);
        $this->db->update('vehicles_current_stations', $data_update);
    }

    public function get_vehicle($rcode = NULL, $vtid = NULL) {
        $this->db->join('t_routes_has_vehicles', 'vehicles.VID = t_routes_has_vehicles.VID', 'left');
        if ($rcode != NULL) {
            $this->db->where('t_routes_has_vehicles.RCode', $rcode);
        }
        if ($vtid != NULL) {
            $this->db->where('vehicles.VTID', $vtid);
        }
        $query = $this->db->get('vehicles');
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
        $this->db->order_by('RID', 'asc');
        $query = $this->db->get('t_routes');
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

}
