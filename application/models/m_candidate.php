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
                'id' => 'MaritalStatus',
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

        // Parent information
        $temp = $this->m_candidate->check_miscellaneous('title');
        $i_FatherTitle = array();
        foreach ($temp as $row) {
            $i_FatherTitle[trim($row['StringValue'])] = trim($row['StringValue']);
        }
        $i_FatherFirstName = array(
            'name' => 'FatherFirstName',
            'value' => set_value('FatherFirstName'),
            'class' => 'form-control');
        $i_FatherLastName = array(
            'name' => 'FatherLastName',
            'value' => set_value('FatherLastName'),
            'class' => 'form-control');
        $i_FatherAge = array(
            'name' => 'FatherAge',
            'type' => 'number',
            'min' => 18,
            'value' => set_value('FatherAge'),
            'class' => 'form-control');
        $i_FatherOccupation = array(
            'name' => 'FatherOccupation',
            'value' => set_value('FatherOccupation'),
            'class' => 'form-control');
        $temp = array(
            'name' => 'FatherIsAlive',
            'type' => 'radio',
            'value' => 0
        );
        if (set_value('FatherIsAlive') == 0)
            $temp['checked'] = TRUE;
        $i_FatherIsAlive[0] = form_checkbox($temp) . 'ยังมีชีวิต';
        $temp = array(
            'name' => 'FatherIsAlive',
            'type' => 'radio',
            'value' => 0
        );
        if (set_value('FatherIsAlive') == 1)
            $temp['checked'] = TRUE;
        $temp['value'] = 1;
        $i_FatherIsAlive[1] = form_checkbox($temp) . 'ถึงแก่กรรม';

        $temp = $this->m_candidate->check_miscellaneous('title');
        $i_MotherTitle = array();
        foreach ($temp as $row) {
            $i_MotherTitle[trim($row['StringValue'])] = trim($row['StringValue']);
        }
        $i_MotherFirstName = array(
            'name' => 'MotherFirstName',
            'value' => set_value('MotherFirstName'),
            'class' => 'form-control');
        $i_MotherLastName = array(
            'name' => 'MotherLastName',
            'value' => set_value('MotherLastName'),
            'class' => 'form-control');
        $i_MotherAge = array(
            'name' => 'MotherAge',
            'type' => 'number',
            'min' => 18,
            'value' => set_value('MotherAge'),
            'class' => 'form-control');
        $i_MotherOccupation = array(
            'name' => 'MotherOccupation',
            'value' => set_value('MotherOccupation'),
            'class' => 'form-control');
        $temp = array(
            'name' => 'MotherIsAlive',
            'type' => 'radio',
            'value' => 0
        );
        if (set_value('MotherIsAlive') == 0)
            $temp['checked'] = TRUE;
        $i_MotherIsAlive[0] = form_checkbox($temp) . 'ยังมีชีวิต';
        $temp = array(
            'name' => 'MotherIsAlive',
            'type' => 'radio',
            'value' => 0
        );
        if (set_value('MotherIsAlive') == 1)
            $temp['checked'] = TRUE;
        $temp['value'] = 1;
        $i_MotherIsAlive[1] = form_checkbox($temp) . 'ถึงแก่กรรม';

        // Education information
        $i_InstitutionName = array(
            'name' => 'InstitutionName',
            'value' => set_value('InstitutionName'),
            'class' => 'form-control');
        $i_EDMajor = array(
            'name' => 'EDMajor',
            'value' => set_value('EDMajor'),
            'class' => 'form-control');
        $i_EDDateFrom = array(
            'name' => 'EDDateFrom',
            'value' => set_value('EDDateFrom'),
            'class' => 'form-control');
        $i_EDDateTo = array(
            'name' => 'EDDateTo',
            'value' => set_value('EDDateTo'),
            'class' => 'form-control');

        // Experience information
        $i_ExCompanyName = array(
            'name' => 'ExCompanyName',
            'value' => set_value('ExCompanyName'),
            'class' => 'form-control');
        $i_ExDateForm = array(
            'name' => 'ExDateForm',
            'value' => set_value('ExDateForm'),
            'class' => 'form-control');
        $i_ExDateTo = array(
            'name' => 'ExDateTo',
            'value' => set_value('ExDateTo'),
            'class' => 'form-control');
        $i_ExPositionName = array(
            'name' => 'ExPositionName',
            'value' => set_value('ExPositionName'),
            'class' => 'form-control');
        $i_ExSaraly = array(
            'name' => 'ExSaraly',
            'value' => set_value('ExSaraly'),
            'class' => 'form-control');
        $i_ReasonOfResign = array(
            'name' => 'ReasonOfResign',
            'value' => set_value('ReasonOfResign'),
            'class' => 'form-control');

        // Emergency_contact
        $temp = $this->m_candidate->check_miscellaneous('title');
        $i_ECTitle = array();
        foreach ($temp as $row) {
            $i_ECTitle[trim($row['StringValue'])] = trim($row['StringValue']);
        }
        $i_ECFirstName = array(
            'name' => 'ECFirstName',
            'value' => set_value('ECFirstName'),
            'class' => 'form-control');
        $i_ECLastName = array(
            'name' => 'ECLastName',
            'value' => set_value('ECLastName'),
            'class' => 'form-control');
        $i_ECRelationShip = array(
            'name' => 'ECRelationShip',
            'value' => set_value('ECRelationShip'),
            'class' => 'form-control');
        $i_ECAddress = array(
            'name' => 'ECAddress',
            'rows' => 3,
            'value' => set_value('ECAddress'),
            'class' => 'form-control');
        $i_ECMobilePhone = array(
            'name' => 'ECMobilePhone',
            'value' => set_value('ECMobilePhone'),
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
            // Parent information
            'FatherTitle' => form_dropdown('FatherTitle', $i_FatherTitle, set_value('FatherTitle'), "class=\"selecter_1\""),
            'FatherFirstName' => form_input($i_FatherFirstName),
            'FatherLastName' => form_input($i_FatherLastName),
            'FatherAge' => form_input($i_FatherAge),
            'FatherOccupation' => form_input($i_FatherOccupation),
            'FatherIsAlive' => $i_FatherIsAlive,
            'MotherTitle' => form_dropdown('MotherTitle', $i_MotherTitle, set_value('MotherTitle'), "class=\"selecter_1\""),
            'MotherFirstName' => form_input($i_FatherFirstName),
            'MotherLastName' => form_input($i_FatherLastName),
            'MotherAge' => form_input($i_FatherAge),
            'MotherOccupation' => form_input($i_FatherOccupation),
            'MotherIsAlive' => $i_FatherIsAlive,
            // Education information
            'InstitutionName' => form_input($i_InstitutionName),
            'EDMajor' => form_input($i_EDMajor),
            'EDDateFrom' => form_input($i_EDDateFrom),
            'EDDateTo' => form_input($i_EDDateTo),
            // Experience information
            'ExCompanyName' => form_input($i_ExCompanyName),
            'ExDateForm' => form_input($i_ExDateForm),
            'ExDateTo' => form_input($i_ExDateTo),
            'ExPositionName' => form_input($i_ExPositionName),
            'ExSaraly' => form_input($i_ExSaraly),
            'ReasonOfResign' => form_input($i_ReasonOfResign),
            // Emergency_contact
            'ECTitle' => form_dropdown('ECTitle', $i_ECTitle, set_value('ECTitle'), "class=\"selecter_1\""),
            'ECFirstName' => form_input($i_ECFirstName),
            'ECLastName' => form_input($i_ECLastName),
            'ECRelationShip' => form_input($i_ECRelationShip),
            'ECAddress' => form_textarea($i_ECAddress),
            'ECMobilePhone' => form_input($i_ECMobilePhone),
        );
        return $data;
    }

    function set_validation() {
        $this->form_validation->set_rules('PID', '', 'trim|required|xss_clean');
        $this->form_validation->set_rules('Title', '', 'trim|required|xss_clean');
        $this->form_validation->set_rules('ExpectedPermanantSalary', '', 'trim|required|xss_clean');
        $this->form_validation->set_rules('FirstName', '', 'trim|required|xss_clean');
        $this->form_validation->set_rules('LastName', '', 'trim|required|xss_clean');
        $this->form_validation->set_rules('PersonalID', '', 'trim|required|xss_clean');
        $this->form_validation->set_rules('AvaliableStartDate', '', 'trim|required|xss_clean');
        // Person information
        $this->form_validation->set_rules('BirthDate', '', 'trim|xss_clean');
        $this->form_validation->set_rules('Age', '', 'trim|xss_clean');
        $this->form_validation->set_rules('NickName', '', 'trim|xss_clean');
        $this->form_validation->set_rules('Race', '', 'trim|xss_clean');
        $this->form_validation->set_rules('Nationality', '', 'trim|xss_clean');
        $this->form_validation->set_rules('Religion', '', 'trim|xss_clean');
        $this->form_validation->set_rules('Sex', '', 'trim|xss_clean');
        $this->form_validation->set_rules('Weight', '', 'trim|xss_clean');
        $this->form_validation->set_rules('Height', '', 'trim|xss_clean');
        $this->form_validation->set_rules('CurrentAddress', '', 'trim|xss_clean');
        $this->form_validation->set_rules('CurrentMu', '', 'trim|xss_clean');
        $this->form_validation->set_rules('CurrentStreet', '', 'trim|xss_clean');
        $this->form_validation->set_rules('CurrentVillage', '', 'trim|xss_clean');
        $this->form_validation->set_rules('CurrentSubDistrict', '', 'trim|xss_clean');
        $this->form_validation->set_rules('CurrentDistrict', '', 'trim|xss_clean');
        $this->form_validation->set_rules('CurrentProvince', '', 'trim|xss_clean');
        $this->form_validation->set_rules('CurrentZipCode', '', 'trim|xss_clean');
        $this->form_validation->set_rules('MobilePhone', '', 'trim|xss_clean');
        $this->form_validation->set_rules('Residential', '', 'trim|xss_clean');
        $this->form_validation->set_rules('MilitaryServiceStatus', '', 'trim|xss_clean');
        $this->form_validation->set_rules('MaritalStatus', '', 'trim|xss_clean');
        $this->form_validation->set_rules('SpouseTitle', '', 'trim|xss_clean');
        $this->form_validation->set_rules('SpouseFirstName', '', 'trim|xss_clean');
        $this->form_validation->set_rules('SpouseLastName', '', 'trim|xss_clean');
        $this->form_validation->set_rules('SpouseAge', '', 'trim|xss_clean');
        $this->form_validation->set_rules('SpouseOccupation', '', 'trim|xss_clean');
        $this->form_validation->set_rules('SpouseIsAlive', '', 'trim|xss_clean');
        $this->form_validation->set_rules('SonTitle', '', 'trim|xss_clean');
        $this->form_validation->set_rules('SonFirstName', '', 'trim|xss_clean');
        $this->form_validation->set_rules('SonLastName', '', 'trim|xss_clean');
        $this->form_validation->set_rules('SonAge', '', 'trim|xss_clean');
        $this->form_validation->set_rules('SonOccupation', '', 'trim|xss_clean');
        // Parent information
        $this->form_validation->set_rules('FatherTitle', '', 'trim|xss_clean');
        $this->form_validation->set_rules('FatherFirstName', '', 'trim|xss_clean');
        $this->form_validation->set_rules('FatherLastName', '', 'trim|xss_clean');
        $this->form_validation->set_rules('FatherAge', '', 'trim|xss_clean');
        $this->form_validation->set_rules('FatherOccupation', '', 'trim|xss_clean');
        $this->form_validation->set_rules('FatherIsAlive', '', 'trim|xss_clean');
        $this->form_validation->set_rules('MotherTitle', '', 'trim|xss_clean');
        $this->form_validation->set_rules('MotherFirstName', '', 'trim|xss_clean');
        $this->form_validation->set_rules('MotherLastName', '', 'trim|xss_clean');
        $this->form_validation->set_rules('MotherAge', '', 'trim|xss_clean');
        $this->form_validation->set_rules('MotherOccupation', '', 'trim|xss_clean');
        $this->form_validation->set_rules('MotherIsAlive', '', 'trim|xss_clean');
        // Education information
        $this->form_validation->set_rules('InstitutionName', '', 'trim|xss_clean');
        $this->form_validation->set_rules('EDMajor', '', 'trim|xss_clean');
        $this->form_validation->set_rules('EDDateFrom', '', 'trim|xss_clean');
        $this->form_validation->set_rules('EDDateTo', '', 'trim|xss_clean');
        // Experience information
        $this->form_validation->set_rules('ExCompanyName', '', 'trim|xss_clean');
        $this->form_validation->set_rules('ExDateForm', '', 'trim|xss_clean');
        $this->form_validation->set_rules('ExDateTo', '', 'trim|xss_clean');
        $this->form_validation->set_rules('ExPositionName', '', 'trim|xss_clean');
        $this->form_validation->set_rules('ExSaraly', '', 'trim|xss_clean');
        $this->form_validation->set_rules('ReasonOfResign', '', 'trim|xss_clean');
        // Emergency_contact
        $this->form_validation->set_rules('ECTitle', '', 'trim|xss_clean');
        $this->form_validation->set_rules('ECFirstName', '', 'trim|xss_clean');
        $this->form_validation->set_rules('ECLastName', '', 'trim|xss_clean');
        $this->form_validation->set_rules('ECRelationShip', '', 'trim|xss_clean');
        $this->form_validation->set_rules('ECAddress', '', 'trim|xss_clean');
        $this->form_validation->set_rules('ECMobilePhone', '', 'trim|xss_clean');
        return true;
    }

    function get_post() {
        $f_data = array(
            'act_id' => $this->input->post('act_id'),
            'act_years' => $this->input->post('act_years'),
            'act_term' => $this->input->post('act_term'),
            'act_name' => $this->input->post('act_name'),
            'group_id' => $this->input->post('group_id'),
            'act_unit' => $this->input->post('act_unit'),
            'act_check' => $this->input->post('act_check'),
            'act_expect' => $this->input->post('act_expect'),
            'act_budget' => $this->input->post('act_budget'),
            'act_charge' => $this->input->post('act_charge'),
            'act_charge_detail' => $this->input->post('act_charge_detail'),
            'act_register' => $this->input->post('act_register'),
            'act_register_t' => $this->input->post('act_register_t'),
            'act_start' => $this->input->post('act_start'),
            'act_start_t' => $this->input->post('act_start_t'),
            'act_end' => $this->input->post('act_end'),
            'act_end_t' => $this->input->post('act_end_t'),
            'act_location' => $this->input->post('act_location'),
            'act_responsible' => $this->input->post('act_responsible'),
            'act_consultant' => $this->input->post('act_consultant'),
            'act_status' => $this->input->post('act_status')
        );
        return $f_data;
    }

}
