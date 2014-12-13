<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_fares extends CI_Model {

   
    private $price = array('เต็ม', 'ลด');

    function set_rate_type($vtid) {
        if ($vtid == 1) {
            $this->rate_type = array('รถตู้');
        } else {
            $this->rate_type = array('รถบัส');
        }
    }

    public function get_stations($rcode, $vtid, $sid = NULL) {
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

// get  ข้อมูลค่าโดยสาร
    public function get_fares($rcode, $vtid, $source_id = NULL, $destination_id = NULL) {

        $this->db->join('f_fares_has_rate', 'f_fares_has_rate.FID=f_fares.FID');
        $this->db->join('f_rate', 'f_rate.RateID=f_fares_has_rate.RateID');

        $this->db->where('RCode', $rcode);
        $this->db->where('VTID', $vtid);
        if ($source_id != NULL) {
            $this->db->where('SourceID', $source_id);
        }
        if ($destination_id != NULL) {
            $this->db->where('DestinationID', $destination_id);
        }

        $query = $this->db->get('f_fares');

        return $query->result_array();
    }

    public function insert_fares($data) {
        $fares_s = $data['fares_s'];
        $fares_d = $data['fares_d'];
        $rate = $data['rate'];

        $rs = array();

        if (count($fares_s) == count($fares_d)) {
            $num = count($fares_d);
            for ($i = 0; $i < $num; $i++) {
                $this->db->insert('f_fares', $fares_s[$i]);
                $fid_s = $this->db->insert_id();

                $this->db->insert('f_fares', $fares_d[$i]);
                $fid_d = $this->db->insert_id();

                $this->db->insert('f_rate', $rate[$i]);
                $rid = $this->db->insert_id();

                $fares_has_rate_s = array(
                    'FID' => $fid_s,
                    'RateID' => $rid,
                );
                $fares_has_rate_d = array(
                    'FID' => $fid_d,
                    'RateID' => $rid,
                );

                $this->db->insert('f_fares_has_rate', $fares_has_rate_s);
                $this->db->insert('f_fares_has_rate', $fares_has_rate_d);

                $rs[$i] = "INSERT $fid_s,$fid_d ->$rid ";
            }
        }

        return $rs;
    }

    public function update_fares($rcode, $vtid, $data) {
        $fares_s = $data['fares_s'];
        $fares_d = $data['fares_d'];
        $rate = $data['rate'];

        $rs = array();

        if (count($fares_s) == count($fares_d)) {
            $num = count($fares_d);
            for ($i = 0; $i < $num; $i++) {

                $ac = '';

                //update star point S
                $source_id_s = $fares_s[$i]['SourceID'];
                $destination_id_s = $fares_s[$i]['DestinationID'];
                $f_s = $this->get_fares($rcode, $vtid, $source_id_s, $destination_id_s);

                if (count($f_s) > 0) {
                    $fid_s = $f_s[0]['FID'];
                    $this->db->where('FID', $fid_s);
                    $this->db->update('f_fares', $fares_s[$i]);

                    $ac .= "UPDATE fares_s -> $fid_s | ";
                } else {
                    $this->db->insert('f_fares', $fares_s[$i]);
                    $fid_s = $this->db->insert_id();

                    $ac .= "INSERT fares_s -> $fid_s | ";
                }


                //update star point D
                $source_id_d = $fares_d[$i]['SourceID'];
                $destination_id_d = $fares_d[$i]['DestinationID'];
                $f_d = $this->get_fares($rcode, $vtid, $source_id_d, $destination_id_d);
                if (count($f_d) > 0) {
                    $fid_d = $f_d[0]['FID'];
                    $this->db->where('FID', $fid_d);
                    $this->db->update('f_fares', $fares_d[$i]);

                    $ac .= "UPDATE fares_d -> $fid_d | ";
                } else {
                    $this->db->insert('f_fares', $fares_d[$i]);
                    $fid_d = $this->db->insert_id();

                    $ac .= "INSERT fares_d -> $fid_d | ";
                }

                //update ค่าโดยสาร
                $r = $this->get_fares($rcode, $vtid, $source_id_d, $destination_id_d);

                if (count($f_s) > 0 && count($f_d) > 0 && count($r) > 0) {
                    $rid_ = $r[0]['RateID'];
                    $this->db->where('RateID', $rid_);
                    $this->db->update('f_rate', $rate[$i]);

                    $ac .= "UPDATE rate -> $rid_ ";
                } else {

                    $this->db->insert('f_rate', $rate[$i]);
                    $rid = $this->db->insert_id();

                    $fares_has_rate_s = array(
                        'FID' => $fid_s,
                        'RateID' => $rid,
                    );

                    $fares_has_rate_d = array(
                        'FID' => $fid_d,
                        'RateID' => $rid,
                    );

                    $this->db->insert('f_fares_has_rate', $fares_has_rate_s);
                    $this->db->insert('f_fares_has_rate', $fares_has_rate_d);

                    $ac .= "INSERT rate -> $rid ";
                }
                $rs[$i] = "$ac";
            }
        }

        return $rs;
    }

    public function set_form_add($rcode, $vtid) {
        $this->set_rate_type($vtid);

        $stations = $this->get_stations($rcode, $vtid);

        $number_station = count($stations) - 1;

        $i_price = array();
        $i_source = array();
        $i_destination = array();

        for ($i = 0; $i < $number_station; $i++) {
            $s = array_shift($stations);
            $source_id = $s['SID'];
            $source = $s['StationName'];


            $i_source[$i] = $source;

            $j = 0;
            foreach ($stations as $st) {
                $destination_id = $st['SID'];
                $destination = $st['StationName'];

                $i_destination[$i][$j] = $destination;

                for ($k = 0; $k < count($this->price); $k++) {
                    $t_price = array(
                        'name' => "Price[$i][$j][$k]",
                        'type' => "text",
                        'class' => "form-control text-center",
                        'value' => set_value("Price[$i][$j][$k]"),
                    );
                    $i_price[$i][$j][$k] = form_input($t_price);
                }

                $j++;
            }
        }

        $form_add = array(
            'form' => form_open('fares/add/' . $rcode . '/' . $vtid, array('class' => 'form-horizontal', 'id' => 'form_fares')),
            'Source' => $i_source,
            'Destination' => $i_destination,
            'Price' => $i_price,
        );

        return $form_add;
    }

    public function set_form_edit($rcode, $vtid) {
        $this->set_rate_type($vtid);

        $stations = $this->get_stations($rcode, $vtid);

        $number_station = count($stations) - 1;

        $i_price = array();
        $i_source = array();
        $i_destination = array();


        for ($i = 0; $i < $number_station; $i++) {
            $s = array_shift($stations);
            $source_id = $s['SID'];
            $source = $s['StationName'];


            $i_source[$i] = $source;

            $j = 0;
            foreach ($stations as $st) {
                $destination_id = $st['SID'];
                $destination = $st['StationName'];

                $i_destination[$i][$j] = $destination;

                $rate = $this->get_fares($rcode, $vtid, $source_id, $destination_id);
                if (count($rate) <= 0) {
                    $price_ = "";
                    $price_dis = "";
                } else {
                    $price_ = $rate[0]['Price'];
                    $price_dis = $rate[0]['PriceDicount'];
                }


                //ราคาเต็ม
                $t_price_full = array(
                    'name' => "Price[$i][$j][0]",
                    'type' => "text",
                    'class' => "form-control text-center",
                    'value' => (set_value("Price[$i][$j][0]") == NULL) ? $price_ : set_value("Price[$i][$j][0]"),
                );
                $i_price[$i][$j][0] = form_input($t_price_full);

                //ราคาลด
                $t_price_dis = array(
                    'name' => "Price[$i][$j][1]",
                    'type' => "text",
                    'class' => "form-control text-center",
                    'value' => (set_value("Price[$i][$j][1]") == NULL) ? $price_dis : set_value("Price[$i][$j][1]"),
                );
                $i_price[$i][$j][1] = form_input($t_price_dis);

                $j++;
            }
        }


        $form_edit = array(
            'form' => form_open('fares/edit/' . $rcode . '/' . $vtid, array('class' => 'form-horizontal', 'id' => 'form_fares')),
            'Source' => $i_source,
            'Destination' => $i_destination,
            'Price' => $i_price,
        );

        return $form_edit;
    }

    public function get_post_form_add($rcode, $vtid) {
        $this->set_rate_type($vtid);
        $price = $this->input->post('Price');
        $stations = $this->get_stations($rcode, $vtid);
        $number_station = count($stations) - 1;

        $fares_s = array();
        $fares_d = array();
        $rate = array();
        for ($i = 0; $i < $number_station; $i++) {
            $s = array_shift($stations);
            $source_id = $s['SID'];
            $source = $s['StationName'];
            $j = 0;
            foreach ($stations as $st) {
                $destination_id = $st['SID'];
                $destination = $st['StationName'];
                //start point S
                $i_farse_s = array(
                    'RCode' => $rcode,
                    'VTID' => $vtid,
                    'StartPoint' => 'S',
                    'SourceID' => $source_id,
                    'SourceName' => $source,
                    'DestinationID' => $destination_id,
                    'DestinationName' => $destination,
                    'FNote' => "S ออกจาก $source -> $destination",
                    'CreateDate' => $this->m_datetime->getDatetimeNow(),
                );

                array_push($fares_s, $i_farse_s);

                //start point D
                $i_farse_d = array(
                    'RCode' => $rcode,
                    'VTID' => $vtid,
                    'StartPoint' => 'D',
                    'SourceID' => $destination_id,
                    'SourceName' => $destination,
                    'DestinationID' => $source_id,
                    'DestinationName' => $source,
                    'FNote' => "D ออกจาก $destination -> $source",
                    'CreateDate' => $this->m_datetime->getDatetimeNow(),
                );
                array_push($fares_d, $i_farse_d);

                $rate_type = $this->rate_type[0];
                $price_ = $price[$i][$j][0];
                $price_dis = $price[$i][$j][1];
                $i_rate = array(
                    'RateType' => $rate_type,
                    'Price' => $price_,
                    'PriceDicount' => $price_dis,
                    'RNote' => "ราคาโดยสาร $rcode $rate_type $source -> $destination",
                );
                array_push($rate, $i_rate);

                $j++;
            }
        }

        $form_data_add = array(
            'fares_s' => $fares_s,
            'fares_d' => $fares_d,
            'rate' => $rate,
        );

        return $form_data_add;
    }

    public function get_post_form_edit($rcode, $vtid) {
        $this->set_rate_type($vtid);
        $price = $this->input->post('Price');
        $stations = $this->get_stations($rcode, $vtid);
        $number_station = count($stations) - 1;

        $fares_s = array();
        $fares_d = array();
        $rate = array();
        for ($i = 0; $i < $number_station; $i++) {
            $s = array_shift($stations);
            $source_id = $s['SID'];
            $source = $s['StationName'];
            $j = 0;
            foreach ($stations as $st) {
                $destination_id = $st['SID'];
                $destination = $st['StationName'];
                //start point S
                $i_farse_s = array(
                    'RCode' => $rcode,
                    'VTID' => $vtid,
                    'StartPoint' => 'S',
                    'SourceID' => $source_id,
                    'SourceName' => $source,
                    'DestinationID' => $destination_id,
                    'DestinationName' => $destination,
                    'FNote' => "S ออกจาก $source -> $destination",
                    'UpdateDate' => $this->m_datetime->getDatetimeNow(),
                );

                array_push($fares_s, $i_farse_s);

                //start point D
                $i_farse_d = array(
                    'RCode' => $rcode,
                    'VTID' => $vtid,
                    'StartPoint' => 'D',
                    'SourceID' => $destination_id,
                    'SourceName' => $destination,
                    'DestinationID' => $source_id,
                    'DestinationName' => $source,
                    'FNote' => "D ออกจาก $destination -> $source",
                    'UpdateDate' => $this->m_datetime->getDatetimeNow(),
                );
                array_push($fares_d, $i_farse_d);

                $rate_type = $this->rate_type[0];
                $price_ = $price[$i][$j][0];
                $price_dis = $price[$i][$j][1];
                $i_rate = array(
                    'RateType' => $rate_type,
                    'Price' => $price_,
                    'PriceDicount' => $price_dis,
                    'RNote' => "ราคาโดยสาร $rcode $rate_type $source -> $destination",
                );
                array_push($rate, $i_rate);

                $j++;
            }
        }

        $form_data_edit = array(
            'fares_s' => $fares_s,
            'fares_d' => $fares_d,
            'rate' => $rate,
        );

        return $form_data_edit;
    }

    public function validation_form_add($rcode, $vtid) {
        $this->set_rate_type($vtid);
        $stations = $this->get_stations($rcode, $vtid);
        $number_station = count($stations) - 1;

        for ($i = 0; $i < $number_station; $i++) {
            $s = array_shift($stations);
            $source = $s['StationName'];
            $j = 0;
            foreach ($stations as $st) {
                $destination = $st['StationName'];
                for ($k = 0; $k < count($this->price); $k++) {
                    $p = $this->price[$k];
                    $this->form_validation->set_rules("Price[$i][$j][$k]", "ค่าโดยสาร ($p) $source -> $destination", 'trim|required|numeric|xss_clean');
                }
                $j++;
            }
        }
        return TRUE;
    }

    public function validation_form_edit($rcode, $vtid) {
        $this->set_rate_type($vtid);
        $stations = $this->get_stations($rcode, $vtid);
        $number_station = count($stations) - 1;

        for ($i = 0; $i < $number_station; $i++) {
            $s = array_shift($stations);
            $source = $s['StationName'];
            $j = 0;
            foreach ($stations as $st) {
                $destination = $st['StationName'];
                for ($k = 0; $k < count($this->price); $k++) {
                    $p = $this->price[$k];
                    $this->form_validation->set_rules("Price[$i][$j][$k]", "ค่าโดยสาร ($p) $source -> $destination", 'trim|required|numeric|xss_clean');
                }
                $j++;
            }
        }
        return TRUE;
    }

}
