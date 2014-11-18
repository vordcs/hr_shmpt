<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class m_station extends CI_Model {
    public function insert_station($data) {
        
    }
    
    public function set_form_add($rcode=NULL,$vtid=NULL) {
        
        
        
        $form_add =array(
            'form' => form_open('route/add_station/' . $rcode.'/'.$vtid, array('class' => 'form-horizontal', 'id' => 'form_route')),
        );
    }
}


