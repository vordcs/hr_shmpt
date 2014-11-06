<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_candidate extends CI_Model {

    function check_miscellaneous($MiscName) {
        $query = $this->db->get_where('miscellaneous', array('MiscName' => $MiscName));
        return $query->result_array();
    }

    function check_employee_positions() {
        $query = $this->db->get('employee_positions');
        return $query->result_array();
    }

    function set_form() {
        $temp = $this->m_candidate->check_employee_positions();
        $i_PID = array();
        foreach ($temp as $row) {
            $i_PID[$row['PID']] = trim($row['PositionName']);
        }
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
            'type' => 'number',
            'min' => 18,
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
            'value' => set_value('Weight'),
            'class' => 'form-control');
        $i_Height = array(
            'name' => 'Height',
            'value' => set_value('Height'),
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
        $temp = $this->m_candidate->check_miscellaneous('title');
        $i_SpouseTitle = array();
        foreach ($temp as $row) {
            $i_SpouseTitle[trim($row['StringValue'])] = trim($row['StringValue']);
        }
        $i_SpouseFirstName = array(
            'name' => 'SpouseFirstName',
            'value' => set_value('SpouseFirstName'),
            'class' => 'form-control');
        $i_SpouseLastName = array(
            'name' => 'SpouseLastName',
            'value' => set_value('SpouseLastName'),
            'class' => 'form-control');
        $i_SpouseAge = array(
            'name' => 'SpouseAge',
            'type' => 'number',
            'min' => 18,
            'value' => set_value('SpouseAge'),
            'class' => 'form-control');
        $i_SpouseOccupation = array(
            'name' => 'SpouseOccupation',
            'value' => set_value('SpouseOccupation'),
            'class' => 'form-control');
        $temp = array(
            'name' => 'SpouseIsAlive',
            'type' => 'radio',
            'value' => 0
        );
        if (set_value('SpouseIsAlive') == 0)
            $temp['checked'] = TRUE;
        $i_SpouseIsAlive[0] = form_checkbox($temp) . 'ยังมีชีวิต';
        $temp = array(
            'name' => 'SpouseIsAlive',
            'type' => 'radio',
            'value' => 0
        );
        if (set_value('SpouseIsAlive') == 1)
            $temp['checked'] = TRUE;
        $temp['value'] = 1;
        $i_SpouseIsAlive[1] = form_checkbox($temp) . 'ถึงแก่กรรม';
        $i_NumberSon = array(
            'name' => 'NumberSon',
            'type' => 'number',
            'min' => 0,
            'value' => set_value('NumberSon'),
            'class' => 'form-control');
        $i_SonTitle = array(
            'name' => 'SonTitle',
            'value' => set_value('SonTitle'),
            'class' => 'form-control');
        $i_SonFirstName = array(
            'name' => 'SonFirstName',
            'value' => set_value('SonFirstName'),
            'class' => 'form-control');
        $i_SonLastName = array(
            'name' => 'SonLastName',
            'value' => set_value('SonLastName'),
            'class' => 'form-control');
        $i_SonAge = array(
            'name' => 'SonAge',
            'value' => set_value('SonAge'),
            'class' => 'form-control');
        $i_SonOccupation = array(
            'name' => 'SonOccupation',
            'value' => set_value('SonOccupation'),
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
            'PID' => form_dropdown('PID', $i_PID, set_value('PID'), "class=\"selecter_1\""),
            'Title' => form_dropdown('Title', $i_Title, set_value('Title'), "class=\"selecter_1\""),
            'ExpectedPermanantSalary' => form_input($i_ExpectedPermanantSalary),
            'FirstName' => form_input($i_FirstName),
            'LastName' => form_input($i_LastName),
            'PersonalID' => form_input($i_PersonalID),
            'AvaliableStartDate' => form_input($i_AvaliableStartDate),
            // Person information
            'BirthDate' => form_input($i_BirthDate),
            'Age' => form_input($i_Age),
            'NickName' => form_input($i_NickName),
            'Race' => form_input($i_Race),
            'Nationality' => form_input($i_Nationality),
            'Religion' => form_input($i_Religion),
            'Sex' => $i_Sex,
            'Weight' => form_input($i_Weight),
            'Height' => form_input($i_Height),
            'CurrentAddress' => form_input($i_CurrentAddress),
            'CurrentMu' => form_input($i_CurrentMu),
            'CurrentStreet' => form_input($i_CurrentStreet),
            'CurrentVillage' => form_input($i_CurrentVillage),
            'CurrentSubDistrict' => form_input($i_CurrentSubDistrict),
            'CurrentDistrict' => form_input($i_CurrentDistrict),
            'CurrentProvince' => form_input($i_CurrentProvince),
            'CurrentZipCode' => form_input($i_CurrentZipCode),
            'MobilePhone' => form_input($i_MobilePhone),
            'Residential' => $i_Residential,
            'MilitaryServiceStatus' => $i_MilitaryServiceStatus,
            'MaritalStatus' => $i_MaritalStatus,
            'SpouseTitle' => form_dropdown('SpouseTitle', $i_SpouseTitle, set_value('SpouseTitle'), "class=\"selecter_1\""),
            'SpouseFirstName' => form_input($i_SpouseFirstName),
            'SpouseLastName' => form_input($i_SpouseLastName),
            'SpouseAge' => form_input($i_SpouseAge),
            'SpouseOccupation' => form_input($i_SpouseOccupation),
            'SpouseIsAlive' => $i_SpouseIsAlive,
            'NumberSon' => form_input($i_NumberSon),
            'SonTitle' => form_input($i_SonTitle),
            'SonFirstName' => form_input($i_SonFirstName),
            'SonLastName' => form_input($i_SonLastName),
            'SonAge' => form_input($i_SonAge),
            'SonOccupation' => form_input($i_SonOccupation),
        );
        return $data;
    }

}
