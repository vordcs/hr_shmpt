<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_candidate extends CI_Model {

    function check_miscellaneous($MiscName) {
        $query = $this->db->get_where('miscellaneous', array('MiscName' => $MiscName));
        return $query->result_array();
    }

    function set_form() {
        $temp = $this->m_candidate->check_miscellaneous('title');
        $i_Title = array();
        foreach ($temp as $row) {
            $i_Title[trim($row['StringValue'])] = trim($row['StringValue']);
        }
        $i_ExpectedPermanantSalary = array(
            'name' => 'ExpectedPermanantSalary',
            'type' => 'number',
            'min' => 0,
            'step' => 100,
            'value' => set_value('ExpectedPermanantSalary'),
            'placeholder' => 'เงินเดือนที่ต้องการ',
            'class' => 'form-control');
        $i_FirstName = array(
            'name' => 'FirstName',
            'value' => set_value('FirstName'),
            'placeholder' => 'ชื่อ',
            'class' => 'form-control');
        $i_LastName = array(
            'name' => 'LastName',
            'value' => set_value('LastName'),
            'placeholder' => 'นามสกุล',
            'class' => 'form-control');
        $i_PersonalID = array(
            'name' => 'PersonalID',
            'value' => set_value('PersonalID'),
            'placeholder' => 'เลขประจำตัวประชาชน',
            'class' => 'form-control');
        $i_AvaliableStartDate = array(
            'name' => 'AvaliableStartDate',
            'value' => set_value('AvaliableStartDate'),
            'class' => 'form-control');

        // Person information
        $i_BirthDate = array(
            'name' => 'BirthDate',
            'value' => set_value('BirthDate'),
            'class' => 'form-control');
        $i_Age = array(
            'name' => 'Age',
            'value' => set_value('Age'),
            'class' => 'form-control');
        $i_NickName = array(
            'name' => 'NickName',
            'value' => set_value('NickName'),
            'class' => 'form-control');
        $i_Race = array(
            'name' => 'Race',
            'value' => set_value('Race'),
            'class' => 'form-control');
        $i_Nationality = array(
            'name' => 'Nationality',
            'value' => set_value('Nationality'),
            'class' => 'form-control');
        $i_Religion = array(
            'name' => 'Religion',
            'value' => set_value('Religion'),
            'class' => 'form-control');
        $temp = $this->m_candidate->check_miscellaneous('sex');
        $i_Sex = array();
        foreach ($temp as $row) {
            $temp2 = array(
                'name' => 'Sex',
                'type' => 'radio',
                'value' => trim($row['StringValue'])
            );
            if (set_value('Sex') == $row['StringValue'])
                $temp2['checked'] = TRUE;
            $i_Sex[trim($row['StringValue'])] = form_checkbox($temp2) . $temp2['value'];
        }
        $i_Weight = array(
            'name' => 'Weight',
            'type' => 'number',
            'min' => 0,
            'value' => set_value('Weight'),
            'class' => 'form-control');
        $i_Height = array(
            'name' => 'Height',
            'value' => set_value('Height'),
            'type' => 'number',
            'min' => 0,
            'class' => 'form-control');
        $i_CurrentAddress = array(
            'name' => 'CurrentAddress',
            'value' => set_value('CurrentAddress'),
            'class' => 'form-control');
        $i_CurrentMu = array(
            'name' => 'CurrentMu',
            'value' => set_value('CurrentMu'),
            'class' => 'form-control');
        $i_CurrentStreet = array(
            'name' => 'CurrentStreet',
            'value' => set_value('CurrentStreet'),
            'class' => 'form-control');
        $i_CurrentVillage = array(
            'name' => 'CurrentVillage',
            'value' => set_value('CurrentVillage'),
            'class' => 'form-control');
        $i_CurrentSubDistrict = array(
            'name' => 'CurrentSubDistrict',
            'value' => set_value('CurrentSubDistrict'),
            'class' => 'form-control');
        $i_CurrentDistrict = array(
            'name' => 'CurrentDistrict',
            'value' => set_value('CurrentDistrict'),
            'class' => 'form-control');
        $i_CurrentProvince = array(
            'name' => 'CurrentProvince',
            'value' => set_value('CurrentProvince'),
            'class' => 'form-control');
        $i_CurrentZipCode = array(
            'name' => 'CurrentZipCode',
            'value' => set_value('CurrentZipCode'),
            'class' => 'form-control');
        $i_MobilePhone = array(
            'name' => 'MobilePhone',
            'value' => set_value('MobilePhone'),
            'class' => 'form-control');
        $temp = $this->m_candidate->check_miscellaneous('residential');
        $i_Residential = array();
        foreach ($temp as $row) {
            $temp2 = array(
                'name' => 'Residential',
                'type' => 'radio',
                'value' => trim($row['StringValue'])
            );
            if (set_value('Residential') == $row['StringValue'])
                $temp2['checked'] = TRUE;
            $i_Residential[trim($row['StringValue'])] = form_checkbox($temp2) . $temp2['value'];
        }
        $temp = $this->m_candidate->check_miscellaneous('militaryservicestatus');
        $i_MilitaryServiceStatus = array();
        foreach ($temp as $row) {
            $temp2 = array(
                'name' => 'MilitaryServiceStatus',
                'type' => 'radio',
                'value' => trim($row['StringValue'])
            );
            if (set_value('MilitaryServiceStatus') == $row['StringValue'])
                $temp2['checked'] = TRUE;
            $i_MilitaryServiceStatus[trim($row['StringValue'])] = form_checkbox($temp2) . $temp2['value'];
        }
        $temp = $this->m_candidate->check_miscellaneous('maritalstatus');
        $i_MaritalStatus = array();
        foreach ($temp as $row) {
            $temp2 = array(
                'name' => 'MaritalStatus',
                'type' => 'radio',
                'value' => trim($row['StringValue'])
            );
            if (set_value('MaritalStatus') == $row['StringValue'])
                $temp2['checked'] = TRUE;
            $i_MaritalStatus[trim($row['StringValue'])] = form_checkbox($temp2) . $temp2['value'];
        }
        $i_pass = array(
            'name' => 'pass',
            'value' => set_value('pass'),
            'placeholder' => 'Password',
            'class' => 'form-control');
        $i_pass = array(
            'name' => 'pass',
            'value' => set_value('pass'),
            'placeholder' => 'Password',
            'class' => 'form-control');

        // Family information
        $i_pass = array(
            'name' => 'pass',
            'value' => set_value('pass'),
            'placeholder' => 'Password',
            'class' => 'form-control');
        $i_pass = array(
            'name' => 'pass',
            'value' => set_value('pass'),
            'placeholder' => 'Password',
            'class' => 'form-control');
        $i_pass = array(
            'name' => 'pass',
            'value' => set_value('pass'),
            'placeholder' => 'Password',
            'class' => 'form-control');
        $i_pass = array(
            'name' => 'pass',
            'value' => set_value('pass'),
            'placeholder' => 'Password',
            'class' => 'form-control');
        $i_pass = array(
            'name' => 'pass',
            'value' => set_value('pass'),
            'placeholder' => 'Password',
            'class' => 'form-control');
        $i_pass = array(
            'name' => 'pass',
            'value' => set_value('pass'),
            'placeholder' => 'Password',
            'class' => 'form-control');
        $i_pass = array(
            'name' => 'pass',
            'value' => set_value('pass'),
            'placeholder' => 'Password',
            'class' => 'form-control');
        $i_pass = array(
            'name' => 'pass',
            'value' => set_value('pass'),
            'placeholder' => 'Password',
            'class' => 'form-control');
        $i_pass = array(
            'name' => 'pass',
            'value' => set_value('pass'),
            'placeholder' => 'Password',
            'class' => 'form-control');
        $i_pass = array(
            'name' => 'pass',
            'value' => set_value('pass'),
            'placeholder' => 'Password',
            'class' => 'form-control');
        $i_pass = array(
            'name' => 'pass',
            'value' => set_value('pass'),
            'placeholder' => 'Password',
            'class' => 'form-control');
        $i_pass = array(
            'name' => 'pass',
            'value' => set_value('pass'),
            'placeholder' => 'Password',
            'class' => 'form-control');

        // Education information
        $i_pass = array(
            'name' => 'pass',
            'value' => set_value('pass'),
            'placeholder' => 'Password',
            'class' => 'form-control');
        $i_pass = array(
            'name' => 'pass',
            'value' => set_value('pass'),
            'placeholder' => 'Password',
            'class' => 'form-control');
        $i_pass = array(
            'name' => 'pass',
            'value' => set_value('pass'),
            'placeholder' => 'Password',
            'class' => 'form-control');
        $i_pass = array(
            'name' => 'pass',
            'value' => set_value('pass'),
            'placeholder' => 'Password',
            'class' => 'form-control');

        // Experience information
        $i_pass = array(
            'name' => 'DateForm',
            'value' => set_value('DateForm'),
            'class' => 'form-control');
        $i_pass = array(
            'name' => 'pass',
            'value' => set_value('pass'),
            'placeholder' => 'Password',
            'class' => 'form-control');
        $i_pass = array(
            'name' => 'pass',
            'value' => set_value('pass'),
            'placeholder' => 'Password',
            'class' => 'form-control');
        $i_pass = array(
            'name' => 'pass',
            'value' => set_value('pass'),
            'placeholder' => 'Password',
            'class' => 'form-control');
        $i_pass = array(
            'name' => 'pass',
            'value' => set_value('pass'),
            'placeholder' => 'Password',
            'class' => 'form-control');
        $i_pass = array(
            'name' => 'pass',
            'value' => set_value('pass'),
            'placeholder' => 'Password',
            'class' => 'form-control');

        // Emergency_contact
        $i_pass = array(
            'name' => 'pass',
            'value' => set_value('pass'),
            'placeholder' => 'Password',
            'class' => 'form-control');
        $i_pass = array(
            'name' => 'pass',
            'value' => set_value('pass'),
            'placeholder' => 'Password',
            'class' => 'form-control');
        $i_pass = array(
            'name' => 'pass',
            'value' => set_value('pass'),
            'placeholder' => 'Password',
            'class' => 'form-control');
        $i_pass = array(
            'name' => 'pass',
            'value' => set_value('pass'),
            'placeholder' => 'Password',
            'class' => 'form-control');
        $i_pass = array(
            'name' => 'pass',
            'value' => set_value('pass'),
            'placeholder' => 'Password',
            'class' => 'form-control');
        $i_pass = array(
            'name' => 'pass',
            'value' => set_value('pass'),
            'placeholder' => 'Password',
            'class' => 'form-control');

        $data = array(
            'Title' => form_dropdown('Title', $i_Title, set_value('Title'), "class=\"selecter_1\""),
            'ExpectedPermanantSalary' => form_input($i_ExpectedPermanantSalary),
            'FirstName' => form_input($i_FirstName),
            'LastName' => form_input($i_LastName),
            'PersonalID' => form_input($i_PersonalID),
            'AvaliableStartDate' => form_input($i_AvaliableStartDate),
        );
        return $data;
    }

}
