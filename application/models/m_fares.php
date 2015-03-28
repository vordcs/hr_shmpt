<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_fares extends CI_Model {

    function set_rate_type($vtid) {
        if ($vtid == 1) {
            $this->rate_type = array('รถตู้');
        } else {
            $this->rate_type = array('รถบัส');
        }
    }

    public function get_stations($rcode, $vtid, $sid = NULL, $seq = NULL) {
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
            $this->db->where('Seq >', $seq);
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

    public function check_fares($SourceID, $DestinationID) {
        $FID = NULL;
        $fare = NULL;

        $this->db->where('SourceID', $SourceID);
        $this->db->where('DestinationID', $DestinationID);
        $query = $this->db->get('f_fares');
        if ($query->num_rows() > 0) {
            $fare = $query->row_array();
            $FID = $FID['FID'];
        }
        return $FID;
    }

    public function check_rate($FID) {
        $RateID = NULL;
        $this->db->where('FID', $FID);
        $query = $this->db->get('f_fares_has_rate');
        if ($query->num_rows() > 0) {
            $rate = $query->row_array();
            $RateID = $rate['RateID'];
        }
        return $RateID;
    }

    public function check_fares_has_rate($FID, $RateID) {
        $this->db->where('FID', $FID);
        $this->db->where('RateID', $RateID);
        $query = $this->db->get('f_fares_has_rate');
        return $query->num_rows();
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

    public function update_fares($data) {
        $fares_s = $data['fares_s'];
        $fares_d = $data['fares_d'];
        $rate = $data['rate'];

        $rs = array();

        if (count($fares_s) == count($fares_d)) {
            $num = count($fares_d);
            for ($i = 0; $i < $num; $i++) {
                $FID_S = $fares_s[$i]['FID'];
                $FID_D = $fares_d[$i]['FID'];
                $RateID = $rate[$i]['RateID'];

                $FID_S_new = NULL;
                $FID_D_new = NULL;
                $RateID_new = '';


                if ($FID_S != NULL) {
                    $this->db->where('FID', $FID_S);
                    $this->db->update('f_fares', $fares_s[$i]);
                    $rs['fares_s'][$i] = "UPDATE $FID_S ";
                } else {
                    $SourceID = $fares_s[$i]['SourceID'];
                    $DestinationID = $fares_s[$i]['DestinationID'];
                    $check_fid = $this->check_fares($SourceID, $DestinationID);
                    if ($check_fid != NULL) {
                        $FID_S_new = $check_fid;
                    } else {
                        $this->db->insert('f_fares', $fares_s[$i]);
                        $FID_S_new = $this->db->insert_id();
                    }
                    $rs['fares_s'][$i] = "INSERT $FID_S_new";
                }
                if ($FID_D != NULL) {
                    $this->db->where('FID', $FID_D);
                    $this->db->update('f_fares', $fares_d[$i]);
                    $rs['fares_d'][$i] = "UPDATE $FID_D";
                } else {
                    $SourceID = $fares_d[$i]['SourceID'];
                    $DestinationID = $fares_d[$i]['DestinationID'];
                    $check_fid = $this->check_fares($SourceID, $DestinationID);
                    if ($check_fid != NULL) {
                        $FID_S_new = $check_fid;
                    } else {
                        $this->db->insert('f_fares', $fares_d[$i]);
                        $FID_D_new = $this->db->insert_id();
                    }
                    $rs['fares_d'][$i] = "INSERT $FID_D_new";
                }

                if ($RateID == NULL) {
                    $check_rate_id = $this->check_rate($FID_D);
                    if ($check_rate_id != NULL) {
                        $RateID_new = $check_rate_id;
                    } else {
                        $this->db->insert('f_rate', $rate[$i]);
                        $RateID_new = $this->db->insert_id();
                    }
                    $rs['rate'][$i] = "INSERT -> RateID $RateID_new ";
                } else {
                    $this->db->where('RateID', $RateID);
                    $this->db->update('f_rate', $rate[$i]);
                    $rs['rate'][$i] = "UPDATE -> RateID $RateID ";
                }

                /* insert fares_has_rate */

                if ($RateID_new != NULL) {
                    if ($FID_S_new != NULL) {
                        $fares_has_rate_s = array(
                            'FID' => $FID_S_new,
                            'RateID' => $RateID_new,
                        );
                        if ($this->check_fares_has_rate($FID_S_new, $RateID_new) <= 0) {
                            $this->db->insert('f_fares_has_rate', $fares_has_rate_s);
                            $rs['fare_has_rate']["S"][$i] = "INSERT $FID_S_new <-> $RateID_new";
                        }
                    }
                    if ($FID_D_new != NULL) {
                        $fares_has_rate_d = array(
                            'FID' => $FID_D_new,
                            'RateID' => $RateID_new,
                        );
                        if ($this->check_fares_has_rate($FID_D_new, $RateID_new) <= 0) {
                            $this->db->insert('f_fares_has_rate', $fares_has_rate_d);
                            $rs['fare_has_rate']["D"][$i] = "INSERT $FID_D_new <-> $RateID_new";
                        }
                    }
                }
            }
        }

        return $rs;
    }

    /* -------------------------------------------- */

    public function set_form_search($RCode, $VTID, $SID = NULL) {

        $i_RCode = array(
            'name' => 'RCode',
            'type' => 'hidden',
            'value' => $RCode,
            'placeholder' => 'รหัสเส้นทาง',
            'class' => 'form-control'
        );
        $i_VTID = array(
            'name' => 'VTID',
            'type' => 'hidden',
            'value' => $VTID,
            'placeholder' => 'รหัสประเภทรถ',
            'class' => 'form-control'
        );
        $station_in_route = $this->get_stations($RCode, $VTID);
        $last_station_id = '';
        if (end($station_in_route) != "") {
            $last_station = array_pop($station_in_route);
            $last_station_id = $last_station['SID'];
        }
        $i_Statiions[0] = 'เลือกต้นทาง';
        foreach ($station_in_route as $value) {
            $i_Statiions[$value['SID']] = $value['StationName'];
        }

        $stations_source = $this->get_stations($RCode, $VTID, $SID);
        $data_statons = array();
        foreach ($stations_source as $s_source) {
            $SourceID = $s_source['SID'];
            $SourceSeq = $s_source['Seq'];
            $SourceName = $s_source['StationName'];
            $stations_destination = $this->get_stations($RCode, $VTID, NULL, $SourceSeq);
            $data_desttination = array();
            $x = 0;
            foreach ($stations_destination as $s_destination) {
                $DestinationID = $s_destination['SID'];
                $DestinationName = $s_destination['StationName'];
                $rate = reset($this->get_fares($RCode, $VTID, $SourceID, $DestinationID));
                if (isset($rate['Price'])) {
                    $x++;
                    $price = $rate['Price'];
                } else {
                    $price = "-";
                }
                if (isset($rate['PriceDicount'])) {
                    $price_dis = $rate['PriceDicount'];
                } else {
                    $price_dis = "-";
                }

                $temp_station_destination = array(
                    'DestinationID' => $DestinationID,
                    'DestinationName' => $DestinationName,
                    'Price' => $price,
                    'PriceDicount' => $price_dis,
                );
                array_push($data_desttination, $temp_station_destination);
            }
            $add = array(
                'type' => "button",
                'class' => "btn btn-info",
            );
            $edit = array(
                'type' => "button",
                'class' => "btn btn-warning",
            );
            if ($x > 0) {
                $action = anchor("fares/edit/$RCode/$VTID/$SourceID", '<i class="fa fa-edit" ></i>&nbsp;แก้ไขข้อมูลค่าโดยสาร จาก ' . $SourceName . " (ไป-กลับ)", $edit);
            } else {
                $action = anchor("fares/add/$RCode/$VTID/$SourceID", '<i class="fa fa-plus" ></i>&nbsp;เพิ่มข้อมูลค่าโดยสาร จาก ' . $SourceName . " (ไป-กลับ)", $add);
            }
            $station_source = array(
                'SourceID' => $SourceID,
                'SourceName' => $SourceName,
                'Action' => $action,
                'DestinationStations' => $data_desttination,
            );
            array_push($data_statons, $station_source);
        }

        $dropdown = 'class="selecter_3" data-selecter-options = \'{"cover":"true"}\' ';
        $rs = array(
            'form' => form_open('fares/search/' . $RCode . '/' . $VTID, array('class' => 'form-horizontal', 'id' => 'form_search_stations')),
            'RCode' => form_input($i_RCode),
            'VTID' => form_input($i_VTID),
            'stations' => form_dropdown('SID', $i_Statiions, set_value('SID'), $dropdown),
            'data' => $data_statons,
        );
        return $rs;
    }

    public function get_post_form_search() {
        $form_data = array(
            'RCode' => $this->input->post('RCode'),
            'VTID' => $this->input->post('VTID'),
            'SID' => $this->input->post('SID'),
        );
        return $form_data;
    }

    public function validation_form_search() {
        $this->form_validation->set_rules('RCode', 'รหัสเส้นทาง', 'trim|required|xss_clean');
        $this->form_validation->set_rules('VTID', 'ประเภทรถ', 'trim|required|xss_clean');
        $this->form_validation->set_rules('SID', 'สถานีต้นทาง', 'trim|xss_clean|callback_check_dropdown');
        return TRUE;
    }

    public function set_form_add($RCode, $VTID, $SID) {
        $stations_source = $this->get_stations($RCode, $VTID, $SID);
        $data_statons = array();
        foreach ($stations_source as $s_source) {
            $SourceID = $s_source['SID'];
            $SourceSeq = $s_source['Seq'];
            $SourceName = $s_source['StationName'];
            $stations_destination = $this->get_stations($RCode, $VTID, NULL, $SourceSeq);
            $data_desttination = array();
            foreach ($stations_destination as $s_destination) {
                $DestinationID = $s_destination['SID'];
                $DestinationSeq = $s_destination['Seq'];
                $DestinationName = $s_destination['StationName'];

                $i_price_full = array(
                    'name' => "Price[$SourceID][$DestinationID]",
                    'type' => "text",
                    'class' => "form-control text-center",
                    'value' => set_value("Price[$SourceID][$DestinationID]"),
                );
                $i_price_dis = array(
                    'name' => "PriceDicount[$SourceID][$DestinationID]",
                    'type' => "text",
                    'class' => "form-control text-center",
                    'value' => set_value("PriceDicount[$SourceID][$DestinationID]"),
                );

                $temp_station_destination = array(
                    'DestinationID' => $DestinationID,
                    'DestinationName' => $DestinationName,
                    'Price' => form_input($i_price_full),
                    'PriceDicount' => form_input($i_price_dis),
                );
                if (count($stations_destination) > 0) {
                    array_push($data_desttination, $temp_station_destination);
                }
            }
            $station_source = array(
                'SourceID' => $SourceID,
                'SourceName' => $SourceName,
                'DestinationStations' => $data_desttination,
            );
            array_push($data_statons, $station_source);
        }
        $rs = array(
            'form' => form_open("fares/add/$RCode/$VTID/$SID", array('class' => 'form-horizontal', 'id' => 'form_fares')),
            'data' => $data_statons,
        );
        return $rs;
    }

    public function set_form_edit_($RCode, $VTID, $SID) {

        $stations_source = $this->get_stations($RCode, $VTID, $SID);
        $data_statons = array();
        foreach ($stations_source as $s_source) {
            $SourceID = $s_source['SID'];
            $SourceSeq = $s_source['Seq'];
            $SourceName = $s_source['StationName'];
            $stations_destination = $this->get_stations($RCode, $VTID, NULL, $SourceSeq);
            $data_desttination = array();
            foreach ($stations_destination as $s_destination) {
                $DestinationID = $s_destination['SID'];
                $DestinationSeq = $s_destination['Seq'];
                $DestinationName = $s_destination['StationName'];

                $rate = reset($this->get_fares($RCode, $VTID, $SourceID, $DestinationID));
                if (isset($rate['Price'])) {
                    $price = $rate['Price'];
                } else {
                    $price = "";
                }
                if (isset($rate['PriceDicount'])) {
                    $price_dis = $rate['PriceDicount'];
                } else {
                    $price_dis = "";
                }

                $i_price_full = array(
                    'name' => "Price[$SourceID][$DestinationID]",
                    'type' => "text",
                    'class' => "form-control text-center",
                    'value' => (set_value("Price[$SourceID][$DestinationID]") == NULL) ? $price : set_value("Price[$SourceID][$DestinationID]"),
                );
                $i_price_dis = array(
                    'name' => "PriceDicount[$SourceID][$DestinationID]",
                    'type' => "text",
                    'class' => "form-control text-center",
                    'value' => (set_value("PriceDicount[$SourceID][$DestinationID]") == NULL) ? $price_dis : set_value("PriceDicount[$SourceID][$DestinationID]"),
                );

                $temp_station_destination = array(
                    'DestinationID' => $DestinationID,
                    'DestinationName' => $DestinationName,
                    'Price' => form_input($i_price_full),
                    'PriceDicount' => form_input($i_price_dis),
                );
                if (count($stations_destination) > 0) {
                    array_push($data_desttination, $temp_station_destination);
                }
            }
            $station_source = array(
                'SourceID' => $SourceID,
                'SourceName' => $SourceName,
                'DestinationStations' => $data_desttination,
            );
            array_push($data_statons, $station_source);
        }



        $rs = array(
            'form' => form_open("fares/edit/$RCode/$VTID/$SID", array('class' => 'form-horizontal', 'id' => 'form_fares')),
            'data' => $data_statons,
        );
        return $rs;
    }

    public function get_post_form_add($RCode, $VTID) {

        $fares_s = array();
        $fares_d = array();
        $rates = array();

        $Prices = $this->input->post('Price');
        $PricesDicount = $this->input->post('PriceDicount');
        foreach ($Prices as $SourceID => $destinations) {
            foreach ($destinations as $DestinationID => $Price) {
                $source = reset($this->get_stations($RCode, $VTID, $SourceID));
                $destination = reset($this->get_stations($RCode, $VTID, $DestinationID));

                $SourceName = $source['StationName'];
                $DestinationName = $destination['StationName'];

                $temp_fares_s = array(
                    'RCode' => $RCode,
                    'VTID' => $VTID,
                    'StartPoint' => 'S',
                    'SourceID' => $SourceID,
                    'SourceName' => $source['StationName'],
                    'DestinationID' => $DestinationID,
                    'DestinationName' => $destination['StationName'],
                    'CreateBy' => '',
                    'CreateDate' => $this->m_datetime->getDatetimeNow(),
                );
                array_push($fares_s, $temp_fares_s);

                $temp_fares_d = array(
                    'RCode' => $RCode,
                    'VTID' => $VTID,
                    'StartPoint' => 'D',
                    'SourceID' => $DestinationID,
                    'SourceName' => $destination['StationName'],
                    'DestinationID' => $SourceID,
                    'DestinationName' => $source['StationName'],
                    'CreateBy' => '',
                    'CreateDate' => $this->m_datetime->getDatetimeNow(),
                );
                array_push($fares_d, $temp_fares_d);

                $i_rate = array(
                    'RateType' => $VTID,
                    'Price' => $Price,
                    'PriceDicount' => $PricesDicount[$SourceID][$DestinationID],
                    'RNote' => "ราคาโดยสาร $RCode $VTID $SourceName <-> $DestinationName",
                );
                array_push($rates, $i_rate);
            }
        }
        $rs = array(
            'fares_s' => $fares_s,
            'fares_d' => $fares_d,
            'rate' => $rates,
        );
        return $rs;
    }

    public function get_post_form_edit_($RCode, $VTID) {
        $fares_s = array();
        $fares_d = array();
        $rates = array();

        $Prices = $this->input->post('Price');
        $PricesDicount = $this->input->post('PriceDicount');
        foreach ($Prices as $SourceID => $destinations) {
            foreach ($destinations as $DestinationID => $Price) {
                $source = reset($this->get_stations($RCode, $VTID, $SourceID));
                $destination = reset($this->get_stations($RCode, $VTID, $DestinationID));

                $SourceName = $source['StationName'];
                $DestinationName = $destination['StationName'];

                $fares_source = reset($this->get_fares($RCode, $VTID, $SourceID, $DestinationID));
                $fares_destination = reset($this->get_fares($RCode, $VTID, $DestinationID, $SourceID));
                $FID_S = '';
                $FID_D = '';
                $RateID = '';
                if (isset($fares_source['FID'])) {
                    $FID_S = $fares_source['FID'];
                }
                if (isset($fares_destination['FID'])) {
                    $FID_D = $fares_destination['FID'];
                }
                if (isset($fares_destination['RateID'])) {
                    $RateID = $fares_destination['RateID'];
                }

                $temp_fares_s = array(
                    'FID' => $FID_S,
                    'RCode' => $RCode,
                    'VTID' => $VTID,
                    'StartPoint' => 'S',
                    'SourceID' => $SourceID,
                    'SourceName' => $source['StationName'],
                    'DestinationID' => $DestinationID,
                    'DestinationName' => $destination['StationName'],
                    'CreateBy' => '',
                    'CreateDate' => $this->m_datetime->getDatetimeNow(),
                );
                array_push($fares_s, $temp_fares_s);

                $temp_fares_d = array(
                    'FID' => $FID_D,
                    'RCode' => $RCode,
                    'VTID' => $VTID,
                    'StartPoint' => 'D',
                    'SourceID' => $DestinationID,
                    'SourceName' => $destination['StationName'],
                    'DestinationID' => $SourceID,
                    'DestinationName' => $source['StationName'],
                    'CreateBy' => '',
                    'CreateDate' => $this->m_datetime->getDatetimeNow(),
                );
                array_push($fares_d, $temp_fares_d);

                $i_rate = array(
                    'RateID' => $RateID,
                    'RateType' => $VTID,
                    'Price' => $Price,
                    'PriceDicount' => $PricesDicount[$SourceID][$DestinationID],
                    'RNote' => "ราคาโดยสาร $RCode $VTID $SourceName <-> $DestinationName",
                );
                array_push($rates, $i_rate);
            }
        }
        $rs = array(
            'fares_s' => $fares_s,
            'fares_d' => $fares_d,
            'rate' => $rates,
        );
        return $rs;
    }

    public function validation_form_add($RCode, $VTID, $SID) {
        $stations_source = $this->get_stations($RCode, $VTID, $SID);
        foreach ($stations_source as $sources) {
            $SourceID = $sources['SID'];
            $SourceSeq = $sources['Seq'];
            $SourceName = $sources['StationName'];
            $stations_destination = $this->get_stations($RCode, $VTID, NULL, $SourceSeq);
            foreach ($stations_destination as $destination) {
                $DestinationID = $destination['SID'];
                $DestinationName = $destination['StationName'];

                $this->form_validation->set_rules("Price[$SourceID][$DestinationID]", "ค่าโดยสาร เต็ม $SourceName <-> $DestinationName", 'trim|required|numeric|xss_clean');
                $this->form_validation->set_rules("PriceDicount[$SourceID][$DestinationID]", "ค่าโดยสาร ลด $SourceName <-> $DestinationName", 'trim|required|numeric|xss_clean');
            }
        }
        return TRUE;
    }

    public function validation_form_edit_($RCode, $VTID, $SID) {
        $stations_source = $this->get_stations($RCode, $VTID, $SID);
        foreach ($stations_source as $sources) {
            $SourceID = $sources['SID'];
            $SourceSeq = $sources['Seq'];
            $SourceName = $sources['StationName'];
            $stations_destination = $this->get_stations($RCode, $VTID, NULL, $SourceSeq);
            foreach ($stations_destination as $destination) {
                $DestinationID = $destination['SID'];
                $DestinationName = $destination['StationName'];

                $this->form_validation->set_rules("Price[$SourceID][$DestinationID]", "ค่าโดยสาร เต็ม $SourceName($SourceID) -> $DestinationName($DestinationID)", 'trim|required|numeric|xss_clean');
                $this->form_validation->set_rules("PriceDicount[$SourceID][$DestinationID]", "ค่าโดยสาร ลด $SourceName -> $DestinationName", 'trim|required|numeric|xss_clean');
            }
        }
        return TRUE;
    }

}
