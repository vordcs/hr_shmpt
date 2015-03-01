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
                $tsid = $data[$i]['TSID'];
                $rid = $data[$i]['RID'];
                $date = $data[$i]['Date'];
                $time_depart = $data[$i]['TimeDepart'];
                $num_schedule = $this->check_schedule($date, $rid, $time_depart);
                if ($num_schedule == 0) {
                    $this->db->insert('t_schedules_day', $data[$i]);
                    $rs[$i] = "Date $date INSERT -> TSID = " . $tsid;
                } else {
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
        $query = $this->db->get("t_schedules_day");

        $rs = $query->num_rows();

        return $rs;
    }

    /*
     * สร้างเวลาเดินรถ 
     * 
     */

    public function run_schedule() {
        $rs = array();
        $routes = $this->get_route_detail();
        $n = 0;
        $x = 0;
        foreach ($routes as $r) {
            $rid = $r['RID'];
            $vt_name = $r['VTDescription'];
            $schedule_type = $r['ScheduleType'];
            $rcode = $r['RCode'];
            $source = $r['RSource'];
            $destination = $r['RDestination'];
            $start_point = $r['StartPoint'];
            $time_duration = $r['Time'];

            $x++;
            if ($schedule_type == '0') {
                $start_time = $r['StartTime'];
                $interval = $r['IntervalEachAround'];
                $around = $r['AroundNumber'];
                for ($i = 0; $i < (int) $around; $i++) {
                    $s_time = strtotime($start_time) + $interval * 60 * $i;
                    $e_time = strtotime("+$time_duration minutes", $s_time);
                    $tsid = $this->generate_tsid($rid, $i + 1);
                    if ($tsid != '') {
                        $temp_schedual = array(
                            'TSID' => $tsid,
                            'RID' => $rid,
                            'TimeDepart' => date('H:i:s', $s_time),
                            'TimeArrive' => date('H:i:s', $e_time),
                            'Date' => $this->m_datetime->getDateToday(),
//                            'ScheduleNote' => " $vt_name เส้นทาง $rcode  $source - $destination (ไป $destination)",
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
                        $tsid = $this->generate_tsid($rid, $seq_no);
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

    public function set_vehicles_initicial_station() {
        $routes = $this->get_route();
        $data = array();
        foreach ($routes as $route) {
            $i = 0;
            $rcode = $route['RCode'];
            $vtid = $route['VTID'];
            $this->db->join('t_routes_has_vehicles', 'vehicles.VID = t_routes_has_vehicles.VID', 'left');
            $this->db->where('RCode', $rcode);
            $this->db->where('VTID', $vtid);
            $query = $this->db->get('vehicles');
            $vehicles = $query->result_array();
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
            $num_vehicle = count($vehicles);
            foreach ($vehicles as $vehicle) {
                $vid = $vehicle['VID'];
                if ($i < ($num_vehicle / 2)) {
                    $sid = $start_station_id;
                    $seq = $first_seq;
                } else {
                    $sid = $end_station_id;
                    $seq = $last_seq;
                }
                $str_time = "04:00:00";
                $data[$i] = array(
                    'VID' => $vid,
                    'CurrentStationID' => $sid,
                    'CurrentStatonSeq' => $seq,
                    'CurrentTime' => date("H:i:s", strtotime($str_time)),
                    'CurrentDate' => $this->m_datetime->getDateToday(),
                );
                $this->db->insert('vehicles_current_stations', $data[$i]);
                $i++;
            }
        }

        $query = $this->db->get('vehicles_current_stations');

        return $data;
    }

    /*
     * 2 ตั้งค่าเวลา
     */

    public function set_time_initicial_vehicles() {
        $rs = array();
        $types = $this->get_vehicle_types();
        foreach ($types as $type) {
            $vtid = $type['VTID'];
            $vt_name = $type['VTDescription'];
            $routes = $this->get_route(NULL, $vtid);
            foreach ($routes as $route) {
                $rcode = $route['RCode'];
                /*
                 * ค้นหาสถานีเเรกเเละสถานนีสุดท้ายแต่ละในเส้นทาง
                 */
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
                /*
                 * ค้นหารถแต่ละค้นที่อยู่ในเเตำแหน่งเเรกแต่ตำแหน่งสุดท้ายของเเต่ละเส้นทาง
                 * โดยจะจะเเยกออกเป็นสองฝั่ง คือ ฝั่ง ขอนแก่น เเละ ปลายทาง
                 * start point S:ขอนแก่น,D: มุกดาหาร,กาฬสิน,อำนาจเจริญ 
                 */
                $route_detail = $this->get_route_detail($rcode, $vtid);
                foreach ($route_detail as $rd) {
                    $rid = $rd['RID'];
                    $start_point = $rd['StartPoint'];
                    if ($start_point == 'S') {
                        $vehicles = $this->check_vehicles_current_point($start_station_id);
                        $sid = $start_station_id;
                        $seq = $first_seq;
                    } else {
                        $vehicles = $this->check_vehicles_current_point($end_station_id);
                        $sid = $end_station_id;
                        $seq = $last_seq;
                    }
                    $n = 0;
                    foreach ($vehicles as $vehicle) {
                        $vid = $vehicle['VID'];
                        $str_time = "04:00:00";
                        $data = array(
                            'CurrentStationID' => $sid,
                            'CurrentStatonSeq' => $seq,
                            'CurrentTime' => date("H:i:s", strtotime("+$n minutes", strtotime($str_time))),
                            'CurrentDate' => $this->m_datetime->getDateToday(),
                        );
                        if (count($this->check_vehicles_current_point(NULL, $vid)) > 0) {
                            $this->db->where('VID', $vid);
                            $this->db->update('vehicles_current_stations', $data);
                            $action = "UPDATE";
                        } else {
                            $data['VID'] = $vid;
                            $this->db->insert('vehicles_current_stations', $data);
                            $action = "INSERT";
                        }

                        if ($start_point == 'S') {
                            $rs[$rcode]['S'][$n] = "$action $vid => $str_time";
                        } else {
                            $rs[$rcode]['D'][$n] = "$action $vid => $str_time";
                        }
                        $n++;
                    }
                }
            }
        }

        return $rs;
    }

    function get_vehicle_types($id = NULL) {
        if ($id != NULL) {
            $this->db->where('VTID', $id);
        }
        $temp = $this->db->get('vehicles_type');
        return $temp->result_array();
    }

    /*
     * check_vehicles_current_point คืนค่า vid โดยเรียงจาก มากไปหาน้อย เพื่อตอบ โจทย์ คิวบ๊วย ออกคิวเเรก ในวันต่อไป 
     */

    public function check_vehicles_current_point($station_id = NULL, $vid = NULL) {
        if ($vid != NULL) {
            $this->db->where('VID', $vid);
        }
        if ($station_id != NULL) {
            $this->db->where('CurrentStationID', $station_id);
        }
        $this->db->order_by('CurrentTime', 'asc');
        $query = $this->db->get('vehicles_current_stations');
        return $query->result_array();
    }

    /*
     * จัดรถเข้าแต่ละเที่ยวเวลา  
     */

    public function run_vehicles_to_schedule() {
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
            $schedule_s = $this->get_schedule_by_route($date, $rid_s);
            $schedule_d = $this->get_schedule_by_route($date, $rid_d);
            $num_schedule_s = count($schedule_s);
            $num_schedule_d = count($schedule_d);
            /*
             * เปรียบเทียบเอาค่ามากที่สุด               
             */
            $max_num_schedule = 0;
            if ($num_schedule_s > $num_schedule_d) {
                $max_num_schedule = $num_schedule_s;
            } elseif ($num_schedule_d > $num_schedule_s) {
                $max_num_schedule = $num_schedule_d;
            } else {
                $max_num_schedule = $num_schedule_d;
            }
            $n = 0;
            for ($i = 0; $i < $max_num_schedule; $i++) {
                $tsid_s = $tsid_d = $vid_s = $vid_d = $schedule = $time_depart_s = $time_depart_d = $time_arrive_s = $time_arrive_d = $vehicle_s = $vehicle_d = NULL;
                if (array_key_exists($i, $schedule_s)) {
                    $schedule = $schedule_s[$i];
                    $tsid_s = $schedule_s[$i]['TSID'];
                    $time_depart_s = $schedule['TimeDepart'];
                    $time_arrive_s = $schedule['TimeArrive'];

                    $vehicle_s = $this->check_vehicle_current_stations($rcode, $vtid, $start_station_id);

                    if (count($vehicle_s) > 0) {
                        $vid_s = $vehicle_s['VID'];
                        $vcode_s = $vehicle_s['VCode'];
//                        $rs['vehicle'][$rcode][$n]['S'] = "->$tsid_s<- $vcode_s | $vid_s || $rcode, $vtid, $start_station_id | UPDATE $vid_s TO last satation -> $end_station_id";
//                        
                        $this->insert_vehicle_has_schedule($tsid_s, $vid_s);
                        $this->update_vehicle_curent_stations($vid_s, $end_station_id, $time_arrive_s, $last_seq);
//
                        $rs[$action][$rcode][$n]['S'] = "->$tsid_s<-ออก $time_depart_s เวลาถึง $time_arrive_s -> รถเบอร์ $vcode_s ||| UPDATE $vid_s TO last_seq ->$last_seq | เวลา $time_arrive_s อยู่ S";
                    } else {
                        $rs[$action][$rcode][$n]['S'] = "$tsid_s ไม่มีรถ | $rcode, $vtid, $start_station_id";
                    }
                }
                if (array_key_exists($i, $schedule_d)) {
                    $schedule = $schedule_d[$i];
                    $tsid_d = $schedule['TSID'];
                    $time_depart_d = $schedule['TimeDepart'];
                    $time_arrive_d = $schedule['TimeArrive'];

                    $vehicle_d = $this->check_vehicle_current_stations($rcode, $vtid, $end_station_id);

                    if (count($vehicle_d) > 0) {
                        $vid_d = $vehicle_d['VID'];
                        $vcode_d = $vehicle_d['VCode'];
//                        $rs['vehicle'][$rcode][$n]['D'] = "->$tsid_d<- $vcode_d | $vid_d || $rcode, $vtid, $end_station_id | next time = $time_arrive_d";

                        $this->insert_vehicle_has_schedule($tsid_d, $vid_d);
                        $this->update_vehicle_curent_stations($vid_d, $start_station_id, $time_arrive_d, $first_seq);

                        $rs[$action][$rcode][$n]['D'] = "->$tsid_d<-ออก $time_depart_d เวลาถึง $time_arrive_d -> รถเบอร์ $vcode_d  ||| UPDATE $vid_d TO first_seq->$first_seq | เวลา $time_arrive_d อยู่ D";
                    } else {
                        $rs[$action][$rcode][$n]['D'] = "$tsid_s ไม่มีรถ | $rcode, $vtid, $end_station_id";
                    }
                }

                $n++;
            }
        }

        return $rs;
    }

    /*
     * คืนค่า schedule โดยเลือดโดย เส้นทาง 
     */

    public function get_schedule_by_route($date = NULL, $rid = NULL) {

        $this->db->join('t_routes', ' t_schedules_day.RID=t_routes.RID', 'left');
        if ($date != NULL) {
            $this->db->where('Date', $date);
        }
        if ($rid != NULL) {
            $this->db->where('t_routes.RID', $rid);
        }
        $this->db->group_by('t_schedules_day.TSID');
        $this->db->order_by('TimeDepart', 'ASC');
        $query_schedule = $this->db->get("t_schedules_day");
        return $query_schedule->result_array();
    }

    /*
     * ค้าหารถเพื่อ นำไปใส่ แต่ละ schedule
     */

    public function check_vehicle_current_stations($rcode, $vtid, $station_id = NULL, $seq = NULL) {
        $vehicles_in_route = $this->get_vehicle($rcode, $vtid);
        $vid = NULL;
        foreach ($vehicles_in_route as $vehicle) {
            $current_station_id = $vehicle['CurrentStationID'];
            if ($rcode == $vehicle['RCode'] && $vtid == $vehicle['VTID'] && $station_id == $current_station_id) {
                $vid = $vehicle['VID'];
                break;
            }
        }
        $rs = array();
        if ($vid != null) {
            $rs = $this->get_vehicle($rcode, $vtid, $vid)[0];
        }
        return $rs;
    }

    /*
     * เพิ่มข้อมูลรถในตารางเวลาเดินรถ
     */

    public function insert_vehicle_has_schedule($tsid, $vid) {
        if (count($this->is_exit_vehicle_in_schedule($tsid, $vid)) == 0) {
            $data_insert = array(
                'TSID' => $tsid,
                'VID' => $vid,
            );
            $this->db->insert('vehicles_has_schedules', $data_insert);
        } else {
            $data_update = array(
                'VID' => $vid
            );
            $this->db->where('TSID', $tsid);
            $this->db->update('vehicles_has_schedules', $data_update);
        }
    }

    /*
     * อัปเดทตำแหน่งของรถ
     */

    public function update_vehicle_curent_stations($vid, $next_station_id, $time_arrive, $next_seq = NULL) {
        $data_update = array(
            'CurrentTime' => $time_arrive,
            'CurrentDate' => $this->m_datetime->getDateToday(),
            'CurrentStationID' => $next_station_id,
            'UpdateDate' => $this->m_datetime->getDatetimeNow(),
        );
        if ($next_seq != NULL) {
            $data_update['CurrentStatonSeq'] = $next_seq;
        }

        $this->db->where('VID', $vid);
        $this->db->update('vehicles_current_stations', $data_update);
    }

    /*
     * ตรวจสอบรถในตารางเดินรถ 
     */

    public function is_exit_vehicle_in_schedule($tsid, $vid = NULL) {
        $this->db->where('TSID', $tsid);
        if ($vid == NULL) {
            $this->db->where('VID', $vid);
        }
        $query = $this->db->get('vehicles_has_schedules');

        return $query->result_array();
    }

    /*
     * คืนค่าข้อมูลรถ
     */

    public function get_vehicle($rcode = NULL, $vtid = NULL, $vid = NULL) {
        $this->db->join('t_routes_has_vehicles', 'vehicles.VID = t_routes_has_vehicles.VID', 'left');
        $this->db->join('vehicles_current_stations', 'vehicles.VID = vehicles_current_stations.VID', 'left');
        if ($rcode != NULL) {
            $this->db->where('t_routes_has_vehicles.RCode', $rcode);
        }
        if ($vtid != NULL) {
            $this->db->where('vehicles.VTID', $vtid);
        }
        if ($vid != NULL) {
            $this->db->where('vehicles.VID', $vid);
        }
        $this->db->where('VStatus', 1);
        $this->db->order_by('CurrentTime,CurrentDate,CurrentStationID,vehicles.VID', 'ASC');
        $query = $this->db->get('vehicles');
        return $query->result_array();
    }

    /*
     * คืนค้า สถานนี หรือจุดจอดของแต่ละเส้นทาง
     */

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

    /*
     * สร้างรหัสเวลาเดินรถ 
     * 14 digit
     * first, 8 digit are year mounth day 
     * secound,2 digit for RID 
     * and 3 digit for around(in present)
     */

    public function generate_tsid($rid, $around) {
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
            $tsid .= str_pad($rid, 2, '0', STR_PAD_LEFT);
//        เที่ยวที่
            $tsid .=str_pad($around, 3, '0', STR_PAD_LEFT);
        }
        return $tsid;
    }

    /*
     * คืนค่าเส้นทาง
     */

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

    /*
     * คืนค่าเส้นทางทั้งหมด ทั้งต้นทาง และออกจากปลายทาง
     */

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

    /*
     * for view only
     */

    public function get_schedule($date = NULL, $rid = NULL) {
        $this->db->select('*,t_schedules_day.TSID as TSID,t_schedules_day.RID as RID');
        $this->db->join('t_routes', ' t_schedules_day.RID=t_routes.RID', 'left');
        $this->db->join('vehicles_has_schedules', 't_schedules_day.TSID = vehicles_has_schedules.TSID', 'left');
        $this->db->join('vehicles', 'vehicles_has_schedules.VID = vehicles.VID', 'left');
        if ($date != NULL) {
            $this->db->where('Date', $date);
        }
        if ($rid != NULL) {
            $this->db->where('t_routes.RID', $rid);
        }
        $this->db->group_by('t_schedules_day.TSID');
        $this->db->order_by('TimeDepart', 'ASC');
        $query_schedule = $this->db->get("t_schedules_day");
        return $query_schedule->result_array();
    }

}
