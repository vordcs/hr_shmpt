<?php

/*
 * ลำดับการทำงาน 
 *  1 เพิ่มข้อมูลใน t_schedules_day
 *      - 
 *      -
 *  2 update ค่าเวลา vehicles_current_stations 
 *  3 เลือกหรือเพิ่ม รถในแต่ละรอบ 
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class m_schedule_vehicle extends CI_Model {
    /*
     * vehicles_current_stations
     * 
     * กำหนดตำแห่งเริ่มต้น ของ รถ แต่ละคัน
     */

    public function set_vehicles_initicial_station() {
        $routes = $this->get_routes();
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
                $check_vehicles_current = $this->check_vehicles_current_point('', $vid);
                if (count($check_vehicles_current) <= 0) {
                    $this->db->insert('vehicles_current_stations', $data[$i]);
                } else {
                    $data_update = array(
                        'CurrentStationID' => $sid,
                        'CurrentStatonSeq' => $seq,
                        'CurrentTime' => date("H:i:s", strtotime($str_time)),
                        'CurrentDate' => $this->m_datetime->getDateToday(),
                    );
                    $this->db->where('VID', $vid);
                    $this->db->update('vehicles_current_stations', $data_update);
                }

                $i++;
            }
        }

        $query = $this->db->get('vehicles_current_stations');

        return $data;
    }

    /*
     * 1. สร้างเวลาเดินรถ 
     */

    /* 1.1 create schedule  */

    public function run_schedule() {
        $rs = array();
        $routes = $this->get_routes_detail();
        $n = 0;
        $x = 0;
        foreach ($routes as $rd) {
            $rid = $rd['RID'];
            $ScheduleType = $rd['ScheduleType'];
            $time_duration = $rd['Time'];
            $x++;

            if ($ScheduleType == '0') {
                $start_time = $rd['StartTime'];
                $interval = $rd['IntervalEachAround'];
                $around = $rd['AroundNumber'];
                for ($i = 0; $i < (int) $around; $i++) {

                    $TimeDepart = strtotime($start_time) + $interval * 60 * $i;
                    $TimeArrive = strtotime("+$time_duration minutes", $TimeDepart);

                    $tsid = $this->generate_tsid($rid, $i + 1);

                    if ($tsid != '') {
                        $temp_schedule = array(
                            'TSID' => $tsid,
                            'RID' => $rid,
                            'TimeDepart' => date('H:i:s', $TimeDepart),
                            'TimeArrive' => date('H:i:s', $TimeArrive),
                            'Date' => $this->m_datetime->getDateToday(),
                            'CreateDate' => $this->m_datetime->getDatetimeNow(),
                        );
                        $rs[$n] = $temp_schedule;
                        $n++;
                    }
                }
            } else {
                $schedule_manual = $this->get_schedule_master($rid);
                foreach ($schedule_manual as $sm) {
                    $seq_no = $sm['SeqNo'];
                    $TimeDepart = strtotime($sm['Time']);
                    $TimeArrive = strtotime("+$time_duration minutes", $TimeDepart);
                    $tsid = $this->generate_tsid($rid, $seq_no);
                    if ($tsid != '') {
                        $temp_schedule = array(
                            'TSID' => $tsid,
                            'RID' => $rid,
                            'TimeDepart' => date('H:i:s', $TimeDepart),
                            'TimeArrive' => date('H:i:s', $TimeArrive),
                            'Date' => $this->m_datetime->getDateToday(),
                            'CreateDate' => $this->m_datetime->getDatetimeNow(),
                        );
                        $rs[$n] = $temp_schedule;
                        $n++;
                    }
                }
            }
        }
        return $rs;
    }

    /* 1.2 insert  */

    public function insert_schedule($data) {
        $rs = array();
        $count = 0;
        if (count($data) > 0) {
            for ($i = 0; $i < count($data); $i++) {
                $tsid = $data[$i]['TSID'];
                $rid = $data[$i]['RID'];
                $date = $data[$i]['Date'];
                $time_depart = $data[$i]['TimeDepart'];
                $num_schedule = $this->check_schedule($date, $rid, $time_depart);
                if ($num_schedule == 0) {
                    if ($this->db->insert('t_schedules_day', $data[$i])) {
                        $rs[$i] = "Date $date INSERT -> TSID = " . $tsid;
                        $count++;
                    }
                } else {
                    $rs[$i] = "Date $date UPDATE -> TSID = " . $tsid;
                }
            }
        }
        array_push($rs, count($data));
        array_push($rs, $count);
        return $rs;
//        if ($this->db->insert_batch('t_schedules_day', $data))
//            return TRUE;
//        else
//            return FALSE;
    }

    /*
     * 2 ตั้งค่าเวลา
     */

    public function set_time_initicial_vehicles() {
        $rs = array();
        $types = $this->get_vehicle_types();
        foreach ($types as $type) {
            $vtid = $type['VTID'];
            $routes = $this->get_routes(NULL, $vtid);
            foreach ($routes as $route) {
                $rcode = $route['RCode'];

                /* ค้นหาสถานีเเรกเเละสถานนีสุดท้ายแต่ละในเส้นทาง */

                $stations = $this->get_stations($rcode, $vtid);

                $first_station_id = reset($stations)['SID'];
                $first_seq = reset($stations)['Seq'];

                $last_station_id = end($stations)['SID'];
                $last_seq = count($stations);

                $n = 0;
                $vehicles = $this->get_vehicles($rcode, $vtid);
                foreach ($vehicles as $vehicle) {
                    $vid = $vehicle['VID'];
                    $str_time = "04:00:00";
                    $vehicle_current_station = $this->check_vehicles_current_stations($rcode, $vtid, $vid);
                    if (count($vehicle_current_station) <= 0) {
                        $this->insert_vehicles_current_stations($vid, $first_station_id, $first_seq);
                        $action = "INSERT";
                    } else {
                        $CurrentTime = date("H:i:s", strtotime("+$n minutes", strtotime($str_time)));
                        $CurrentStationID = '';
                        $CurrentStatonSeq = '';

                        if ($first_seq == $vehicle_current_station['CurrentStatonSeq']) {
                            $CurrentStationID = $first_station_id;
                            $CurrentStatonSeq = $first_seq;
                        }
                        if ($last_seq == $vehicle_current_station['CurrentStatonSeq']) {
                            $CurrentStationID = $last_station_id;
                            $CurrentStatonSeq = $last_seq;
                        }

                        $data = array(
                            'CurrentStationID' => $CurrentStationID,
                            'CurrentStatonSeq' => $CurrentStatonSeq,
                            'CurrentTime' => $CurrentTime,
                            'CurrentDate' => $this->m_datetime->getDateToday(),
                            'UpdateDate' => $this->m_datetime->getDateTimeNow(),
                        );
                        $this->db->where('VID', $vid);
                        $this->db->update('vehicles_current_stations', $data);
                        $n++;
                        $action = "UPDATE";
                    }

                    $rs[$rcode][$vtid][$vid] = "$action $vid => $CurrentTime";
                }
            }
        }

        return $rs;
    }

    /*
     * t_schedules_manual
     */

    public function get_schedule_master($rid = NULL, $smid = NULL) {
        $this->db->select('*,t_routes_has_schedules_manual.SMID as SMID');
        $this->db->join('t_routes_has_schedules_manual', 't_routes_has_schedules_manual.SMID = t_schedules_manual.SMID', 'left');
        if ($rid != NULL) {
            $this->db->where('RID', $rid);
        }
        $this->db->order_by('SeqNo');
        $query = $this->db->get('t_schedules_manual');

        return $query->result_array();
    }

///////////////////////-- run vehicle into schedule --///////////////////////////////////////////////////

    public function run_vehicle_to_schedule() {
        $rs = array();
        $date = $this->m_datetime->getDateToday();

        $routes = $this->get_routes();

        foreach ($routes as $route) {
            $RCode = $route['RCode'];
            $VTID = $route['VTID'];


            $routes_detail = $this->get_routes_detail($RCode, $VTID);

            if (count($routes_detail) > 0) {

                $RID_S = $routes_detail[0]['RID'];
                $RID_D = $routes_detail[1]['RID'];

                $schedules_s = $this->get_schedule($date, $RID_S);
                $schedules_d = $this->get_schedule($date, $RID_D);

                $num_schedules_s = count($schedules_s);
                $num_schedules_d = count($schedules_d);

                if ($num_schedules_s > $num_schedules_d) {
                    $num_schedule_max = $num_schedules_s;
                } elseif ($num_schedules_d > $num_schedules_s) {
                    $num_schedule_max = $num_schedules_d;
                } else {
                    $num_schedule_max = $num_schedules_s;
                }

                $stations = $this->get_stations($RCode, $VTID);

                $first_station_id = reset($stations)['SID'];
                $first_station_seq = reset($stations)['Seq'];

                $last_station_id = end($stations)['SID'];
                $last_station_seq = end($stations)['Seq'];

                $action = array();
                $n = 0;
                for ($i = 0; $i <= $num_schedule_max; $i++) {
                    if (array_key_exists($i, $schedules_s)) {
                        $schedule = $schedules_s[$i];
                        $TSID = $schedule['TSID'];
                        $TimeDepart = $schedule['TimeDepart'];
                        $TimeArrive = $schedule['TimeArrive'];
                        if ($this->check_vehicle_has_schedule($TSID) == NULL) {
                            $vehicle = $this->get_vehicles_current_stations($RCode, $VTID, $first_station_id);
                            if (count($vehicle) == 0) {
                                $vehicles = $this->get_vehicles($RCode, $VTID);
                                $VID = reset($vehicles)['VID'];
                            } else {
                                $VID = $vehicle['VID'];
                            }

                            if ($VID != NULL) {
                                $this->insert_vehicle_has_schedule($TSID, $VID);
                                $this->update_vehicles_current_stations($VID, $last_station_id, $last_station_seq, $TimeArrive);

                                $action[$n] = "INSERT -> S (RID = $RID_S) : $TSID -> $VID || UPDATE $first_station_id -> $last_station_id";
                                $n++;
                            }
                        }
                    }
                    if (array_key_exists($i, $schedules_d)) {
                        $schedule = $schedules_d[$i];
                        $TSID = $schedule['TSID'];
                        $TimeDepart = $schedule['TimeDepart'];
                        $TimeArrive = $schedule['TimeArrive'];

                        if ($this->check_vehicle_has_schedule($TSID) == NULL) {

                            $vehicle = $this->get_vehicles_current_stations($RCode, $VTID, $last_station_id);

                            if (count($vehicle) == 0) {
                                $vehicles = $this->get_vehicles($RCode, $VTID);
                                $VID = reset($vehicles)['VID'];
                            } else {
                                $VID = $vehicle['VID'];
                            }
                            if ($VID != NULL) {
                                $this->insert_vehicle_has_schedule($TSID, $VID);
                                $this->update_vehicles_current_stations($VID, $first_station_id, $first_station_seq, $TimeArrive);

                                $action[$n] = "INSERT -> D (RID = $RID_D) : $TSID -> $VID || UPDATE $last_station_id -> $first_station_id";
                                $n++;
                            }
                        }
                    }
                }
            }

            $VTName = $route['VTDescription'];
            $RSourceName = $route['RSource'];
            $RDestinationName = $route['RDestination'];
            $RouteName = "เส้นทาง $VTName $RCode $RSourceName - $RDestinationName";

            $temp_in_route = array(
                'RCode' => $RCode,
                'VTID' => $VTID,
                'VTName' => $VTName,
                'RouteName' => $RouteName,
                'num_schedules_s' => $num_schedules_s,
                'num_schedules_d' => $num_schedules_d,
                'action' => $action,
            );
            array_push($rs, $temp_in_route);
        }


        return $rs;
    }

    /*
     * t_schedules_day
     */

    public function check_schedule($date, $rid, $time_depart) {
        $this->db->where('RID', $rid);
        $this->db->where('Date', $date);
        $this->db->where('TimeDepart', $time_depart);
        $query = $this->db->get("t_schedules_day");
        $rs = $query->result_array();
        return count($rs);
    }

    public function get_schedule($date = NULL, $rid = NULL, $TSID = NULL) {

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
        if ($TSID != NULL) {
            $this->db->where('t_schedules_day.TSID', $TSID);
        }
        $this->db->group_by('t_schedules_day.TSID');
        $this->db->order_by('TimeDepart', 'ASC');
        $query_schedule = $this->db->get("t_schedules_day");
        return $query_schedule->result_array();
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
            //วันที่
            $date = new DateTime();
            $tsid .=$date->format("Ymd");
            //เส้นทาง
            $tsid .= str_pad($rid, 2, '0', STR_PAD_LEFT);
            //เที่ยวที่
            $tsid .=str_pad($around, 3, '0', STR_PAD_LEFT);
        }
        return $tsid;
    }

    /*
     * vehicles
     */

    public function get_vehicles($rcode = NULL, $vtid = NULL, $status = NULL, $vid = NULL) {

        $this->db->join('t_routes_has_vehicles', 'vehicles.VID = t_routes_has_vehicles.VID', 'left');
        $this->db->join('vehicles_current_stations', 'vehicles.VID = vehicles_current_stations.VID', 'left');
        $this->db->join('t_stations', 'vehicles_current_stations.CurrentStationID = t_stations.SID');
        if ($rcode != NULL) {
            $this->db->where('t_routes_has_vehicles.RCode', $rcode);
        }
        if ($vtid != NULL) {
            $this->db->where('vehicles.VTID', $vtid);
        }
        if ($vid != NULL) {
            $this->db->where('vehicles.VID', $vid);
        }
        if ($status != NULL) {
            $this->db->where('VStatus', $status);
        }

        $this->db->order_by('CurrentTime,CurrentStationID,vehicles.VCode', 'ASC');
        $query = $this->db->get('vehicles');

        return $query->result_array();
    }

    function get_vehicle_types($id = NULL) {
        if ($id != NULL) {
            $this->db->where('VTID', $id);
        }
        $temp = $this->db->get('vehicles_type');
        return $temp->result_array();
    }

    /*
     * vehicle_has_schedule 
     */

    public function check_vehicle_has_schedule($TSID) {
        $VID = NULL;
        $this->db->where('TSID', $TSID);
        $query = $this->db->get('vehicles_has_schedules');
        if ($query->num_rows() > 0) {
            $VID = $query->row_array()['VID'];
        }

        return $VID;
    }

    //เพิ่มข้อมูล
    public function insert_vehicle_has_schedule($tsid, $vid) {
        $rs = FALSE;
        $data_insert = array(
            'TSID' => $tsid,
            'VID' => $vid,
        );
        if ($vid != NULL) {
            $this->db->insert('vehicles_has_schedules', $data_insert);
            if ($this->db->affected_rows() > 0) {
                $rs = TRUE;
            }
        }
        return $rs;
    }

    //แก้ไขข้อมูล
    public function update_vehicle_in_schedule($TSID, $VID) {
        $rs = FALSE;

        $data_update = array(
            'VID' => $VID,
        );

        $this->db->where('TSID', $TSID);
        $this->db->update('vehicles_has_schedules', $data_update);

        if ($this->db->affected_rows() > 0) {
            $rs = TRUE;
        }
        return $rs;
    }

    /*
     * t_stations
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

    public function get_stations_by_start_point($start_point, $rcode = null, $vtid = null) {
        if ($rcode != NULL) {
            $this->db->where('RCode', $rcode);
        }
        if ($vtid != NULL) {
            $this->db->where('VTID', $vtid);
        }
        if ($start_point == 'S') {
            $this->db->order_by('Seq', 'asc');
        }
        if ($start_point == 'D') {
            $this->db->order_by('Seq', 'desc');
        }

        $query = $this->db->get('t_stations');

        return $query->result_array();
    }

    public function get_station_sale_ticket($rcode = null, $vtid = null, $StartPoint = NULL) {
        if ($rcode != NULL) {
            $this->db->where('RCode', $rcode);
        }
        if ($vtid != NULL) {
            $this->db->where('VTID', $vtid);
        }
        $this->db->where('IsSaleTicket', 1);

        if ($StartPoint == 'S') {
            $this->db->order_by('Seq', 'asc');
        }
        if ($StartPoint == 'D') {
            $this->db->order_by('Seq', 'desc');
        }

        $query = $this->db->get('t_stations');

        $rs = $query->result_array();

        return $rs;
    }

    /*
     * vehicles_current_stations
     */

    public function get_vehicles_current_stations($RCode, $VTID, $CurrentStationID) {
        $this->db->join('t_routes_has_vehicles', 'vehicles_current_stations.VID = t_routes_has_vehicles.VID', 'left');
        $this->db->join('vehicles', 'vehicles_current_stations.VID = vehicles.VID', 'left');

        $this->db->where('t_routes_has_vehicles.RCode', $RCode);
        $this->db->where('vehicles.VTID', $VTID);
        $this->db->where('vehicles_current_stations.CurrentStationID', $CurrentStationID);

        $this->db->where('VStatus', 1);
        $this->db->order_by('CurrentTime,CurrentDate,CurrentStationID', 'ASC');

        $query = $this->db->get('vehicles_current_stations');

        return $query->row_array();
    }

    public function check_vehicles_current_stations($RCode, $VTID, $VID = NULL) {

        $rs = array();
        $this->db->select('vehicles_current_stations.VID as VID,VCode,CurrentStationID,CurrentStatonSeq,CurrentTime,CurrentDate,t_routes.RCode,t_routes.VTID');
        $this->db->join('vehicles', 'vehicles_current_stations.VID = vehicles.VID', 'left');
        $this->db->join('t_routes_has_vehicles', 'vehicles_current_stations.VID = t_routes_has_vehicles.VID', 'left');
        $this->db->join('t_routes', 't_routes.RCode = t_routes_has_vehicles.RCode AND vehicles.VTID = t_routes.VTID', 'left');

        $this->db->where('t_routes.RCode', $RCode);
        $this->db->where('vehicles.VTID', $VTID);

        if ($VID != NULL) {
            $this->db->where('vehicles_current_stations.VID', $VID);
        }
        $this->db->group_by('vehicles_current_stations.VID');
        $query = $this->db->get('vehicles_current_stations');

        if ($query->num_rows() > 0) {
            $rs = $query->row_array();
        }
        return $rs;
    }

    public function insert_vehicles_current_stations($VID, $CurrentStationID, $CurrentStatonSeq) {
        $data_insert = array(
            'VID' => $VID,
            'CurrentTime' => $this->m_datetime->getTimeNow(),
            'CurrentDate' => $this->m_datetime->getDateToday(),
            'CurrentStationID' => $CurrentStationID,
            'CurrentStatonSeq' => $CurrentStatonSeq,
            'UpdateDate' => $this->m_datetime->getDatetimeNow(),
        );
        $this->db->insert('vehicles_current_stations', $data_insert);
    }

    public function update_vehicles_current_stations($VID, $NextStationID, $NextStatonSeq, $TimeArrive) {
        $data_update = array(
            'CurrentTime' => $TimeArrive,
            'CurrentDate' => $this->m_datetime->getDateToday(),
            'CurrentStationID' => $NextStationID,
            'CurrentStatonSeq' => $NextStatonSeq,
            'UpdateDate' => $this->m_datetime->getDatetimeNow(),
        );

        $this->db->where('VID', $VID);
        $this->db->update('vehicles_current_stations', $data_update);
    }

    /*
     * t_routes
     */

    public function get_routes($rcode = NULL, $vtid = NULL, $rid = NULL) {

        $this->db->join('vehicles_type', 'vehicles_type.VTID = t_routes.VTID');

        if ($rcode != NULL) {
            $this->db->where('RCode', $rcode);
        }

        if ($vtid != NULL) {
            $this->db->where('t_routes.VTID', $vtid);
        }
        if ($rid != NULL) {
            $this->db->where('t_routes.RID', $rid);
        } else {
            $this->db->where('StartPoint', 'S');
            $this->db->group_by(array('RCode', 't_routes.VTID'));
        }
        $this->db->order_by('StartPoint', 'desc');

        $query = $this->db->get('t_routes');
        return $query->result_array();
    }

    public function get_routes_detail($rcode = NULL, $vtid = NULL, $rid = NULL) {

        $this->db->join('vehicles_type', 'vehicles_type.VTID = t_routes.VTID');
        if ($rcode != NULL) {
            $this->db->where('RCode', $rcode);
        }
        if ($vtid != NULL) {
            $this->db->where('t_routes.VTID', $vtid);
        }
        if ($rid != NULL) {
            $this->db->where('t_routes.RID', $rid);
        }

        $this->db->order_by('StartPoint', 'DESC');
        $query = $this->db->get('t_routes');
        return $query->result_array();
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

////////////////////////-- set form view --/////////////////////////////////////

    public function set_form_view() {
        $date = $this->m_datetime->getDateToday();

        $types = $this->get_vehicle_types();
        $data_vehicles = array();
        foreach ($types as $type) {
            $VTID = $type['VTID'];
            $VTName = $type['VTDescription'];

            $routes = $this->get_routes(NULL, $VTID);
            $routes_in_type = array();
            foreach ($routes as $route) {
                $RCode = $route['RCode'];

                $RSourceName = $route['RSource'];
                $RDestinationName = $route['RDestination'];
                $RouteName = "เส้นทาง $VTName $RCode $RSourceName - $RDestinationName";

                $vehicles = $this->get_vehicles($RCode, $VTID);

                $temp_in_vehicles = array(
                    'RCode' => $RCode,
                    'RouteName' => $RouteName,
                    'vehicles' => $vehicles,
                );
                array_push($routes_in_type, $temp_in_vehicles);
            }
            $temp_vehicles = array(
                'VTID' => $VTID,
                'VTName' => $VTName,
                'routes' => $routes_in_type,
            );
            array_push($data_vehicles, $temp_vehicles);
            //data scheadule day
            $data_scheadules = array();
            $detail_in_route = array();
            $routes_detail = $this->get_routes_detail($RCode, $VTID);
            foreach ($routes_detail as $rd) {
                $RID = $rd['RID'];
                $StartPoint = $rd['StartPoint'];
                $source_name = $rd['RSource'];
                $destination_name = $rd['RDestination'];
                $route_detail_name = "$VTName เส้นทาง " . $RCode . ' ' . ' ' . $source_name . ' - ' . $destination_name;

                $schedules = $this->get_schedule($date, $RID);
                $stations = $this->get_station_sale_ticket($RCode, $VTID, $StartPoint);

                $schedules_in_route = array();

                foreach ($schedules as $schedule) {
                    $TSID = $schedule['TSID'];
                    $VCode = $schedule['VCode'];

                    $stations_in_schedule = array();
                    foreach ($stations as $station) {
                        $SID = $station['SID'];
                        $TimeDepart = $this->time_depart($RID, $TSID, $SID);
                        $temp_station = array(
                            'SID' => $SID,
                            'StationName' => $station['StationName'],
                            'TimeDepart' => $TimeDepart,
                        );
                        array_push($stations_in_schedule, $temp_station);
                    }
                    $temp_schedules = array(
                        'TSID' => $TSID,
                        'VCode' => $VCode,
                        'stations' => $stations_in_schedule,
                    );
                    array_push($schedules_in_route, $temp_schedules);
                }
                $temp_route_detail = array(
                    'RID' => $RID,
                    'RouteName' => $route_detail_name,
                    'NumStation' => count($stations),
                    'stations' => $stations,
                    'schedules' => $schedules_in_route,
                );
                array_push($detail_in_route, $temp_route_detail);
            }
            $SourceName = $route['RSource'];
            $DestinationName = $route['RDestination'];
            $RouteName = "$VTName สาย " . $RCode . ' ' . ' ' . $SourceName . ' - ' . $DestinationName;

            $temp_route = array(
                'RCode' => $RCode,
                'RouteName' => $RouteName,
                'routes_detail' => $detail_in_route,
            );
            array_push($data_scheadules, $temp_route);
        }
        $rs = array(
            'data_vehicles' => $data_vehicles,
            'data_scheadules' => $data_scheadules,
        );

        return $rs;
    }

    public function time_depart($RID, $TSID, $SID = NULL) {
        $date = $this->m_datetime->getDateToday();
        /*
         * ข้อมูลเส้นทาง
         */
        $this->db->where('RID', $RID);
        $query_route = $this->db->get('t_routes');
        $route = $query_route->result_array()[0];

        $RCode = $route['RCode'];
        $VTID = $route['VTID'];
        $StartPoint = $route['StartPoint'];

        /*
         * ข้อมูลจุดจอด
         */
        $stations = $this->get_station_sale_ticket($RCode, $VTID, $StartPoint);
        $num_station = count($this->get_stations($RCode, $VTID));
        /*
         * ข้อมูลตารางเวลาเดินรถ
         */
        $schedules = $this->get_schedule($date, $RID, $TSID);

        $TimeDepart = NULL;
        $debug = array();
        foreach ($schedules as $schedule) {
            $StartTime = $schedule['TimeDepart'];
            $EndTime = $schedule['TimeArrive'];
            $temp = 0;
            foreach ($stations as $station) {
                $sid = $station['SID'];
                $travel_time = $station['TravelTime'];
                if ($station['Seq'] == '1') {
                    if ($StartPoint == 'S') {
                        $time = strtotime($StartTime);
                    } else {
                        $time = strtotime($EndTime);
                    }
                    $debug[0] = "สถานีต้นทาง";
                } elseif ($station['Seq'] == $num_station) {
                    if ($StartPoint == 'S') {
                        $time = strtotime($EndTime);
                    } else {
                        $time = strtotime($StartTime);
                    }
                    $debug[0] = "สถานีปลายทาง";
                } else {
                    $temp+=$travel_time;
                    $time = strtotime("+$temp minutes", strtotime($StartTime));
                    $debug[0] = "สถานีกลางทาง";
                }
                $time_depart = date('H:i', $time);
                if ($SID == $sid) {
                    $TimeDepart = $time_depart;
                    $debug[1] = $time_depart;
                    $debug[2] = "$sid";
                    break;
                }
            }
        }
        return $TimeDepart;
    }

}
