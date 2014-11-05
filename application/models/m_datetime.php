<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class m_datetime extends CI_Model {

    private $month_th = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");

    function getDatetimeNow() {
        return date('Y-m-d H:i:s');
    }

    function getDateToday() {
        return date('Y-m-d');
    }

    function getDateTodayTH() {
        $day = date('d');
        $month = date('m');
        $year = date('Y') + 543;
        $today = $year . '-' . $month . '-' . $day;
        $d = new DateTime($today);
        $date = $d->format('Y-m-d');
        return $date;
    }

    function setDateFomat($input_date) {
        $d = new DateTime($input_date);
        $date = $d->format('Y-m-d');
        return $date;
    }

    function setYearFomat($input_year) {
        $y = new DateTime($input_year);
        $year = $y->format('yyyy');
        return $year;
    }

    public function monthTHtoDB($str_date_th) {
        for ($i = 0; $i < count($this->month_th); $i++) {
            if ($month_th[$i] == $str_date_th) {
                return $i;
            }
        }
    }

    public function getMonthThai($i) {
        return $this->month_th[$i];
    }

    public function DateThai($strDate) {
        if ($strDate == NULL) {
            return '-';
        } else {
            $str = explode('-', $strDate);
            $strYear = trim($str[0]) + 543;
            $strMonthThai = $this->month_th[(int) $str[1]];
            $strDay = $str[2];
            return "$strDay $strMonthThai $strYear";
        }
    }

    public function DateThaiToDay() {
        $strDate = $this->getDateToday();
        if ($strDate == NULL) {
            return '-';
        } else {
            $str = explode('-', $strDate);
            $strYear = trim($str[0]) + 543;
            $strMonthThai = $this->month_th[(int) $str[1]];
            $strDay = $str[2];
            return "$strDay $strMonthThai $strYear";
        }
    }

    public function DateTimeThai($strDate) {
        if ($strDate == NULL) {
            return '-';
        } else {
            $date = new DateTime($strDate);
            $strYear = date("Y", strtotime($strDate)) + 543;
            $strMonth = date("n", strtotime($strDate));
            $strDay = date("j", strtotime($strDate));
            $strHour = date("H", strtotime($strDate));
            $strMinute = date("i", strtotime($strDate));
            $strSeconds = date("s", strtotime($strDate));
            $strMonthCut = $this->month_th;
            $strMonthThai = $strMonthCut[$strMonth];
            return "$strDay $strMonthThai $strYear " . " เวลา $strHour:$strMinute ";
        }
    }

}
