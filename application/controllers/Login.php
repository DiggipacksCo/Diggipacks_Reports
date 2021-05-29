<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

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

    public function auth_user() {
        //echo password_hash(123456, PASSWORD_BCRYPT); die;

        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $return = $this->Login_model->auth_user($username, $password);
        if (empty($return)) {
            $this->session->set_flashdata('Error', 'Invalid details');
            redirect(base_url() . 'Login', 'refresh');
        } else {
            if($return[0]->system_access_fm=='N')
            {
                
                 $this->session->set_flashdata('Error', 'This account is Inactive. Please contact to your admin..');

                redirect(base_url() . 'Login', 'refresh');
            }
            else
            {

            if ($return == 'not_verified') {
                $this->session->set_flashdata('Error', 'This account is Inactive. Please contact to your admin..');

                redirect(base_url() . 'Login', 'refresh');
            } else {

                // print_r($return);
                // exit();
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
                        'is_logged_in' => TRUE
                    );
                } else {
                    $user_details = array(
                        'username' => $return[0]->username,
                        'user_id' => $return[0]->id,
                        'city' => $return[0]->city,
                        'status' => $return[0]->status,
                        'is_deleted' => $return[0]->is_deleted,
                        'email' => $return[0]->email,
                        'user_type' => $return[0]->user_type,
                        'profile_pic' => $return[0]->logopath,
                        'super_id' => $return[0]->super_id,
                        'wh_id' => $return[0]->wh_id,
                        'is_logged_in' => TRUE
                    );
                }
                // print_r($user_details);
                // exit();

                $this->session->set_userdata('user_details', $user_details);
                $actdetails = "login user";
                $logstattus = "login";
                $logArray = array('user_id' => $this->session->userdata('user_details')['user_id'], 'details' => $actdetails, 'status' => $logstattus, 'ip_address' => $_SERVER['REMOTE_ADDR'], 'super_id' => $this->session->userdata('user_details')['super_id']);
                $this->db->insert('activities_log', $logArray);
                // print_r($return);
                //       print_r($this->session->userdata('user_details'));
                // exit();
            }
            }
            // $name= $this->session->userdata->('username'); 

            redirect(base_url() . 'Home', 'refresh'); //.$name);
        }
    }

    public function sign_up() {
        $this->load->view('signup', 'refresh');
    }

}
