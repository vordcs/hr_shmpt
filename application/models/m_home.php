<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_home extends CI_Model {

    function check_report_day() {
        $ans = array();
        $end = date('Y-m-d');
        $date1 = str_replace('-', '/', $end);
        $start = date('Y-m-d', strtotime($date1 . "-2 days"));

        $this->db->from('report_day AS rd');
        $this->db->join('employees AS em', 'em.EID = rd.CreateBy');
        $this->db->where('rd.ReportDate >=', $start);
        $this->db->where('rd.ReportDate <=', $end);
        $this->db->order_by('rd.CreateDate', 'desc');
        $query = $this->db->get();
        $temp = $query->result_array();

        foreach ($temp as $row) {
            if (!isset($ans[$row['ReportDate']])) {
                $ans[$row['ReportDate']] = array();
            }
            $temp = array_push($ans[$row['ReportDate']], $row);
        }
        return $ans;
    }

    function check_report($ReportID, $EID) {
        $data = array(
            'EID' => $EID,
            'ReportID' => $ReportID,
            'ReadDateTime' => $this->m_datetime->getDatetimeNow()
        );
        if ($this->db->insert('reading_report', $data)) {
            $data2 = array(
                'ReportStatus' => 1
            );
            $this->db->where('ReportID', $ReportID);
            if ($this->db->update('report_day', $data2)) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

}
