<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class System extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Login_model');

        // $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function index() {

        // if($this->session->userdata('user_details'))
        // {
        $this->load->view('login', 'refresh');
        //}
    }

    public function select($super_id=null) {
        //echo password_hash(123456, PASSWORD_BCRYPT); die;

      if($this->session->userdata('user_details')['changeSystem']==1 && $super_id!=null)
      {
        $return = $this->Login_model->changeSystem($super_id);
       
        if (empty($return)) {
            $this->session->set_flashdata('Error', 'Invalid details');
            redirect(base_url() . 'Login', 'refresh');
        } else {
            if($return[0]->system_access_fm=='N')
            {
                
                 $this->session->set_flashdata('Error', 'This account is Inactive. Please contact to your admin..');

                redirect(base_url() . 'Home', 'refresh');
            }
            else
            {

                if ($return[0]->super_id == 0) {
                    $user_details = array(
                        'username' => $return[0]->username,
                        'user_id' => $return[0]->id,
                        'status' => $return[0]->status,
                        'is_deleted' => $return[0]->is_deleted,
                        'city' => $return[0]->city,
                        'email' => $return[0]->email,
                        'user_type' => $return[0]->user_type,
                        'profile_pic' => $return[0]->logopath,
                        'super_id' => $return[0]->id,
                        'wh_id' => $return[0]->wh_id,
                        'is_logged_in' => TRUE,
                        'changeSystem'=>1
                    );
                } 
                // print_r($user_details);
                // exit();

                $this->session->set_userdata('user_details', $user_details);
                $actdetails = "login user";
                $logstattus = "login";
                $logArray = array('user_id' => $this->session->userdata('user_details')['user_id'], 'details' => $actdetails, 'status' => $logstattus, 'ip_address' => $_SERVER['REMOTE_ADDR'], 'super_id' => $this->session->userdata('user_details')['super_id']);
                $this->db->insert('activities_log', $logArray);
               
            }
          

            redirect(base_url() . 'Home', 'refresh'); //.$name);
        }
    }
    }

    public function sign_up() {
        $this->load->view('signup', 'refresh');
    }

}
