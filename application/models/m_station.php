<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_station extends CI_Model {

    public function insert_station($rcode, $vtid, $data) {
        $rs = array();
        $i = 0;
        foreach ($data['station'] as $station) {
            $sid = $this->is_exits_station($rcode, $vtid, $station['StationName']);
            if ($sid == NULL) {
                $this->db->insert('t_stations', $station);
                $sid = $this->db->insert_id();

                $route = array(
                    'RCode' => $rcode,
                    'VTID' => $vtid,
                );
                $this->db->where('SID', $sid);
                $this->db->update('t_stations', $route);
                $rs[$i] = 'INSERT -> ' . $station['StationName'];
            } else {
                $this->db->where('SID', $sid);
                $this->db->update('t_stations', $station);
                $rs[$i] = 'UPDATE -> ' . $sid . '  ' . $station['StationName'];
            }
            $i++;
        }
        return $rs;
    }

    public function update_station($rcode, $vtid, $data) {
        $this->delete_station($rcode, $vtid);
        $rs = array();
        $i = 0;
        foreach ($data['station'] as $station) {
            $sid = $this->is_exits_station($rcode, $vtid, $station['StationName']);
            if ($sid == NULL) {
                $this->db->insert('t_stations', $station);
                $sid = $this->db->insert_id();

                $route = array(
                    'RCode' => $rcode,
                    'VTID' => $vtid,
                );
                $this->db->where('SID', $sid);
                $this->db->update('t_stations', $route);
                $rs[$i] = 'INSERT -> ' . $station['StationName'];
            }
            $i++;
        }
        return $rs;
    }

    public function delete_station($rcode, $vtid, $sid = NULL) {
        $station = $this->get_stations($rcode, $vtid);
        if ($sid == NULL) {
//        detete all stations
            foreach ($station as $s) {
                $this->db->where('RCode', $rcode);
                $this->db->where('VTID', $vtid);
                $this->db->where('SID', $s['SID']);
                $this->db->delete('t_stations');
            }
        } else {
//        detete station by SID
            $this->db->where('SID', $sid);
            $this->db->delete('t_stations');
        }
    }

    public function get_stations($rcode = null, $vtid = null, $sid = NULL) {
        if ($rcode != NULL) {
            $this->db->where('RCode', $rcode);
        }
        if ($vtid != NULL) {
            $this->db->where('VTID', $vtid);
        }
        if ($sid != NULL) {
            $this->db->where('SID', $sid);
        }
        $this->db->order_by('Seq');
        $query = $this->db->get('t_stations');

        $rs = $query->result_array();

        return $rs;
    }

    public function set_form_add($rcode = NULL, $vtid = NULL) {
        $station_name = $this->input->post('StationName');
        $travel_time = $this->input->post('TravelTime');
        $is_sale_ticket = $this->input->post('IsSaleTicket');
        $i_StationName = '';

        $route_detail = $this->m_route->get_route($rcode, $vtid);
        $source = $route_detail[0]['RSource'];
        $desination = $route_detail[0]['RDestination'];

        $i_Source = array(
            'name' => 'Source',
            'value' => $source,
            'placeholder' => 'ต้นทาง',
            'readonly' => '',
            'class' => 'form-control');

        $i_Destination = array(
            'name' => 'Destination',
            'value' => $desination,
            'placeholder' => 'ปลายทาง',
            'readonly' => '',
            'class' => 'form-control');

        if (!empty($station_name) && count($station_name) > 0) {

            for ($i = 0; $i < count($station_name); $i++) {
                $st = '';
                $tt = 'none';
                $s_error = '';
                $tt_error = '';
                if (!empty($is_sale_ticket) && array_key_exists($i, $is_sale_ticket)) {
                    $st = 'checked';
                    $tt = 'block';
                }
                if ($station_name[$i] == '') {
                    $s_error = 'has-error';
                }
                if ($travel_time[$i] == '') {
                    $tt_error = 'has-error';
                }

                $i_StationName .="<tr>"
                        . "<td class=\"text-center\">"
                        . " <input type=\"checkbox\"  name=\"IsSaleTicket[$i]\" class=\"IsSaleTicket\" onclick=\"ShowItemp('TravelTime$i')\" value=\"TravelTime$i\" $st> "
                        . "</td>"
                        . "<td class=\"$s_error\">"
                        . "<input type=\"text\" name=\"StationName[]\" class=\"form-control \" placeholder=\"ชื่อจุดจอด\"  value=\"$station_name[$i]\"> "
                        . "</td>"
                        . "<td class=\"$tt_error\">"
                        . "<input type=\"text\" class=\"form-control\" name=\"TravelTime[]\" id=\"TravelTime$i\" value=\" $travel_time[$i] \" style=\"display: $tt;\">"
                        . "</td>"
                        . "<td class=\"text-center\">"
                        . "<a class=\"btn btn-danger btn-sm\" onClick=\"RemoveRow(this)\"><i class=\"fa fa-minus\"></i></a>"
                        . "</td>"
                        . "</tr>";
            }
        }
        $form_add = array(
            'form' => form_open('station/add/' . $rcode . '/' . $vtid, array('class' => 'form-horizontal', 'id' => 'form_station')),
            'Source' => form_input($i_Source),
            'Destination' => form_input($i_Destination),
            'station' => $i_StationName,
        );

        return $form_add;
    }

    public function set_form_edit($rcode = NULL, $vtid = NULL) {
        $station_post = $this->input->post('StationName');
        $travel_time = $this->input->post('TravelTime');
        $is_sale_ticket = $this->input->post('IsSaleTicket');

        $i_Station = '';

        $stations_db = $this->get_stations($rcode, $vtid);

        $source = $stations_db[0]['StationName'];
        $desination = $stations_db[count($stations_db) - 1]['StationName'];



        $i_Source = array(
            'name' => 'Source',
            'value' => $source,
            'placeholder' => 'ต้นทาง',
            'readonly' => '',
            'class' => 'form-control');

        $i_Destination = array(
            'name' => 'Destination',
            'value' => $desination,
            'placeholder' => 'ปลายทาง',
            'readonly' => '',
            'class' => 'form-control');
        if (!empty($station_post) && count($station_post) > 0) {
            for ($i = 0; $i < count($station_post); $i++) {
                $st = '';
                $tt = 'none';
                $s_error = '';
                $tt_error = '';
                if (!empty($is_sale_ticket) && array_key_exists($i, $is_sale_ticket)) {
                    $st = 'checked';
                    $tt = 'block';
                }
                if ($station_post[$i] == '') {
                    $s_error = 'has-error';
                }
                if ($travel_time[$i] == '') {
                    $tt_error = 'has-error';
                }

                $i_Station .="<tr>"
                        . "<td class=\"text-center\">"
                        . " <input type=\"checkbox\"  name=\"IsSaleTicket[$i]\" class=\"IsSaleTicket\" onclick=\"ShowItemp('TravelTime$i')\" value=\"TravelTime$i\" $st> "
                        . "</td>"
                        . "<td class=\"$s_error\">"
                        . "<input type=\"text\" name=\"StationName[]\" class=\"form-control \" placeholder=\"ชื่อจุดจอด\"  value=\"$station_post[$i]\"> "
                        . "</td>"
                        . "<td class=\"$tt_error\">"
                        . "<input type=\"text\" class=\"form-control\" name=\"TravelTime[]\" id=\"TravelTime$i\" value=\" $travel_time[$i] \" style=\"display: $tt;\">"
                        . "</td>"
                        . "<td class=\"text-center\">"
                        . "<a class=\"btn btn-danger btn-sm\" onClick=\"RemoveRow(this)\"><i class=\"fa fa-minus\"></i></a>"
                        . "</td>"
                        . "</tr>";
            }
        } else {
            $i = 0;
            foreach ($stations_db as $s) {
                $station_name = $s['StationName'];
                if ($station_name != $source && $station_name != $desination) {
                    $st = '';
                    $tt = 'none';
                    $s_error = '';
                    $tt_error = '';
                    if ($s['IsSaleTicket'] == 1) {
                        $st = 'checked';
                        $tt = 'block';
                    }
                    $i_Station .="<tr>"
                            . "<td class=\"text-center\">"
                            . " <input type=\"checkbox\"  name=\"IsSaleTicket[$i]\" class=\"IsSaleTicket\" onclick=\"ShowItemp('TravelTime$i')\" value=\"TravelTime$i\" $st> "
                            . "</td>"
                            . "<td class=\"$s_error\">"
                            . "<input type=\"text\" name=\"StationName[]\" class=\"form-control \" placeholder=\"ชื่อจุดจอด\"  value=\"$station_name\"> "
                            . "</td>"
                            . "<td class=\"$tt_error\">"
                            . "<input type=\"text\" class=\"form-control\" name=\"TravelTime[]\" id=\"TravelTime$i\" value=\" $travel_time[$i] \" style=\"display: $tt;\">"
                            . "</td>"
                            . "<td class=\"text-center\">"
                            . "<a class=\"btn btn-danger btn-sm\" onClick=\"RemoveRow(this)\"><i class=\"fa fa-minus\"></i></a>"
                            . "</td>"
                            . "</tr>";
                    $i++;
                }
            }
        }
        $form_add = array(
            'form' => form_open('station/edit/' . $rcode . '/' . $vtid, array('class' => 'form-horizontal', 'id' => 'form_station')),
            'Source' => form_input($i_Source),
            'Destination' => form_input($i_Destination),
            'station' => $i_Station,
        );

        return $form_add;
    }

    public function get_post_form_station() {
        $station_name = $this->input->post('StationName');
        $travel_time = $this->input->post('TravelTime');
        $is_sale_ticket = $this->input->post('IsSaleTicket');

        $source = $this->input->post('Source');
        $destination = $this->input->post('Destination');

        $station = array();
        $seq = 1;
        $first = array(
            'StationName' => $source,
            'TravelTime' => '',
            'IsSaleTicket' => '1',
            'Seq' => $seq
        );
        $seq++;
        array_push($station, $first);
        if (!empty($station_name) && count($station_name) > 0) {
            for ($i = 0; $i < count($station_name); $i++) {
                $st = 0;
                if (!empty($is_sale_ticket) && array_key_exists($i, $is_sale_ticket)) {
                    $st = 1;
                }
                $temp = array(
                    'StationName' => $station_name[$i],
                    'TravelTime' => $travel_time[$i],
                    'IsSaleTicket' => $st,
                    'Seq' => $seq
                );
                array_push($station, $temp);
                $seq++;
            }
        }

        $last = array(
            'StationName' => $destination,
            'TravelTime' => '',
            'IsSaleTicket' => '1',
            'Seq' => $seq
        );

        array_push($station, $last);

        $form_data = array(
//            'IsSaleTicket' => $is_sale_ticket,
//            'StationName' => $station_name,
//            'TravelTime' => $travel_time,            
            'station' => $station,
        );

        return $form_data;
    }

    public function validation_form_status() {
        $station_name = $this->input->post('StationName');
        $travel_time = $this->input->post('TravelTime');
        $is_sale_ticket = $this->input->post('IsSaleTicket');

        $this->form_validation->set_rules("Source", "สถานีต้นทาง", 'trim|required|xss_clean');
        $this->form_validation->set_rules("Destination", "สถานีปลายทาง", 'trim|required|xss_clean');

        if (!empty($station_name) && count($station_name) > 0) {
            for ($i = 0; $i < count($station_name); $i++) {
                $this->form_validation->set_rules("StationName[$i]", "ชื่อจุดจอด $i", 'trim|required|xss_clean');
                if (!empty($is_sale_ticket) && array_key_exists($i, $is_sale_ticket)) {
                    $this->form_validation->set_rules("TravelTime[$i]", "เวลาที่ใช้ $i", 'trim|required|xss_clean');
                }
            }
        }
        return TRUE;
    }

    public function is_exits_station($rcode, $vtid, $station_name) {

        $this->db->where('RCode', $rcode);
        $this->db->where('VTID', $vtid);
        $this->db->where('StationName', $station_name);
        $query = $this->db->get('t_stations');

        $rs = $query->result_array();

        if (count($rs) > 0) {
            return $rs[0]['SID'];
        }
        return NULL;
    }

}
