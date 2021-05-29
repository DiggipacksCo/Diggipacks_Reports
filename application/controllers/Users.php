<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {

    function __construct() {
        parent::__construct();
        if (menuIdExitsInPrivilageArray(5) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        //$this->load->library('pagination');
        $this->load->model('User_model');
        $this->load->model('Shipment_model');
        $this->load->model('ItemInventory_model');
        $this->load->library('form_validation');
        $this->load->helper('security');
        $this->load->helper('utility');
        //$this->load->helper('form');
        //error_reporting(0);
    }

    public function index() {

        //echo $this->session->userdata('user_details')['profile_pic']; die;	
        $data['usersrows'] = $this->User_model->all();

        $this->load->view('user/view_users', $data);
    }

    public function picker_settings($user_id = null) {
        $view['user_id'] = $user_id;

        $view['name'] = getUserNameById_field($user_id, 'name');



        $this->load->view('user/picker_settings', $view);
    }

    public function edit_picker_setting($user_id = null) {
        $view['user_id'] = $user_id;

        $view['editdata'] = $this->User_model->edit_view($user_id);



        $this->load->view('user/edit_picker_setting', $view);
    }

    public function add_access_template($user_id = null) {
         $view['user_id'] = $user_id; 

        $view['typeArr'] = $this->User_model->designation_tblDaata();

        $view['CatData'] = $this->User_model->userCategoryData();

        $view['editdata'] = $this->User_model->edit_view_access($user_id);



        $this->load->view('user/add_access_template', $view);
    }
    

    public function show_access_template($user_id = null) {
        $view['user_id'] = $user_id;

        $view['editdata'] = $this->User_model->edit_view($user_id);



        $this->load->view('user/show_access_template', $view);
    }

    public function add_view() {


        if (($this->session->userdata('user_details') != '')) {

            // print_r($this->session->userdata('user_details'));
            // exit();
            //echo "ssssss"; die;
            //$data['Usersdata']=$this->User_model->customers();
            //print_r($data['customers']);exit();
            $this->load->view('user/add_user', $data);
        } else {
            redirect(base_url() . 'Login');
        }
    }

    public function auto_assign_active($auto_status = null, $user_id = null) {
        $data = array(
            'auto_status' => $auto_status,
        );
        $return = $this->User_model->edit($user_id, $data);

        if ($return == true)
            $this->session->set_flashdata('msg', 'updated successfully!');
        else
            $this->session->set_flashdata('err_msg', 'Try again');

        redirect('picker_setting');
    }

    public function add_picker_setting() {
        $user_id = $this->input->post('user_id');
        echo $this->input->post('day_off');
        $day_off = implode(',', $this->input->post('day_off'));
        $data = array(
            'per_day_target' => $this->input->post('per_day_target'),
            'batch_no' => $this->input->post('batch_no'),
            'assign_time' => $this->input->post('assign_time'),
            'day_off' => $day_off,
        );
        // print_r($data); die;
        $return = $this->User_model->edit($user_id, $data);

        if ($return == true)
            $this->session->set_flashdata('msg', 'has been updated successfully!');
        else
            $this->session->set_flashdata('err_msg', 'Try again');

        redirect('picker_setting');
    }

    public function addnewaccessTemplate() {
        $this->form_validation->set_rules('d_id', 'Type', 'trim|required|xss_clean');
        //$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('privilage_array[]', 'Category', 'trim|required');
        $this->form_validation->set_rules('privilage_array_sub[]', 'Sub Category', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $this->add_access_template();
        } else {

           // print_r($this->input->post()); die;

            $privilage_array = implode(',', $this->input->post('privilage_array'));
            $d_id = $this->input->post('d_id');
            $name = $this->input->post('name');
            $privilage_array_sub = implode(',', $this->input->post('privilage_array_sub'));
            $data = array(
                'd_id' => $d_id,
                'privilage_array' => $privilage_array,
                'privilage_array_sub' => $privilage_array_sub,
                'super_id' => $this->session->userdata('user_details')['super_id'],
            );
            //  echo '<pre>';
            //  print_r($data); die;
            $new_d_id = $this->User_model->GetcheckExitsType($d_id);

            //  print_r($new_d_id); die;
            if (empty($new_d_id)) {
                ///   echo "sssss"; die;
                $return = $this->User_model->add_newTemplate($data);
                if ($return > 0)
                    $this->session->set_flashdata('msg', 'has been added successfully');
                else
                    $this->session->set_flashdata('err_msg', 'Try again');
            } else {
                //   echo "sssssttttttttss"; die;
                $return = $this->User_model->add_newTemplate_update($data, $d_id);
                if ($return > 0)
                    $this->session->set_flashdata('msg', 'has been updated successfully');
                else
                    $this->session->set_flashdata('err_msg', 'Try again');
            }

            redirect('show_access_template');
        }
    }

    public function add() {

        // print_r($this->input->post('dd_customer'));
        // print_r($this->input->post('warehousing_charge'));
        // print_r($this->input->post('fulfillment_charge'));
        // exit();
        // print_r($this->input->post('cbm_no'));
        // exit();
        $customer_id = $this->input->post('dd_customer');
        $this->form_validation->set_rules('wh_id', 'Warehouse', 'trim|required|xss_clean');
        $this->form_validation->set_rules('usertype', 'User Type', 'trim|required|xss_clean');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
        $this->form_validation->set_rules("email", 'Email Address', 'trim|required|xss_clean'); //|is_unique[users.email]
        $this->form_validation->set_rules("mobile_no", 'Mobile No.', 'trim|required|regex_match[/^[0-9]{10}$/]|xss_clean');
        $this->form_validation->set_rules("password", 'Password ', 'trim|required|min_length[6]|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->add_view();
        } else {

            if (!empty($_FILES['logo_path']['name'])) {
                $config['upload_path'] = '../fs_files/staff_upload/';
                $upload_path = 'staff_upload/';
                $config['overwrite'] = TRUE;
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_name'] = $_FILES['logo_path']['name'];
                $config['file_name'] = time();
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('logo_path')) {
                    $uploadData = $this->upload->data();
                    $small_img = $upload_path . '' . $uploadData['file_name'];
                    
                     $uploadedImage = $uploadData['file_name']; 
                    $source_path = $config['upload_path'].$uploadedImage; 
                    $thumb_path = $config['upload_path']; 
                    $thumb_width = 120; 
                    $thumb_height = 120; 
                     
                    // Image resize config 
                    $config['image_library']    = 'gd2'; 
                    $config['source_image']     = $source_path; 
                    $config['new_image']         = $thumb_path; 
                    $config['maintain_ratio']     = FALSE; 
                    $config['width']            = $thumb_width; 
                    $config['height']           = $thumb_height; 
                     
                    // Load and initialize image_lib library 
                    $this->load->library('image_lib', $config); 
                     $this->image_lib->resize();
                } else {

                    $small_img = $this->input->post('logo_path_old');
                }
            }

            if (empty($small_img))
                $small_img = "";

            $getcheckemail = $this->User_model->GetallusersCheckeamil($this->input->post('email'));

            if ($getcheckemail == false) {
                $this->session->set_flashdata('err_msg', 'this email address is already exists');
                redirect('users');
                die;
            }
            //echo $this->upload->display_errors(); die;
            //echo $small_img; die;
//            }else{
//               $this->session->set_flashdata('err_msg','Please Select User Logo');
//					redirect('add-new-user');
//            }
            // $errors = $this->upload->display_errors();
            //  echo $errors;
            //print_r($small_img); die;
            /* else
              {
              $this->session->set_flashdata('err_msg','Please Select User Logo');
              redirect('add-new-user');
              } */
            //echo $this->input->post('usertype'); die;
            // $salt=$this->config->item('PASSKEY');
            // echo $this->input->post('password');exit;
            //$has_pass = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
            // echo $has_pass;exit;

            $has_pass = md5($this->input->post('password'));

            $data = array(
                'name' => $this->input->post('username'),
                'branch_location' => $this->input->post('branch_location'),
                'address' => $this->input->post('address'),
                'username' => $this->input->post('username'),
                'usertype' => 'O',
                'email' => $this->input->post('email'),
                'password' => $has_pass,
                'user_type' => $this->input->post('usertype'),
                'phone' => $this->input->post('mobile_no'),
                'logopath' => $small_img,
                'wh_id' => $this->input->post('wh_id'),
                'status' => 'Y',
                'system_access' => $this->input->post('system_access'),
                'is_deleted' => '0',
                'system_access_fm' => 'Y',
                'super_id' => $this->session->userdata('user_details')['super_id']
            );
            

           $seller_id = $this->User_model->add($data);
            
            
            if ($seller_id > 0) {

                if ($this->input->post('usertype')) {
                    $PrivilageArray = $this->User_model->GetcheckExitsType($this->input->post('usertype'));
                    $mancat = $PrivilageArray['privilage_array'];
                    $subcat = $PrivilageArray['privilage_array_sub'];
                    $totalData = $mancat . ',' . $subcat;
                    $checkvalidData = $this->User_model->GetCheckPrivilageDataValid($seller_id);

                    if (!empty($checkvalidData)) {
                        $priArr = array('privilage_array' => $totalData);
                        $this->User_model->PrivilageAdduserUpdate($priArr, $seller_id);
                    } else {
                        $priArr = array('privilage_array' => $totalData, 'customer_id' => $seller_id,'super_id'=>$this->session->userdata('user_details')['super_id']);
                        $this->User_model->PrivilageAdduser($priArr);
                    }
                }
                $this->session->set_flashdata('msg', $this->input->post('username') . '   has been added successfully');
            } else
                $this->session->set_flashdata('err_msg', 'Try again');

            redirect('users');
        }
    }

    public function edit_view($id = null) {

        // $id = $this->input->get('id');
        $view['editdata'] = $this->User_model->edit_view($id);
        $this->load->view('user/user_details', $view);
    }

    public function delete_update($id = null) {

        // $id = $this->input->get('id');
        $array = array("is_deleted" => 1);
        $view['editdata'] = $this->User_model->deleteupdatequery($id, $array);
        $this->session->set_flashdata('msg', '   has been deleted successfully');
        redirect('users');
    }

    public function edit($id = null) {
        $uid = $this->input->post('uid');
        $g_privilage = $this->input->post('g_privilage');
        
        
        $this->form_validation->set_rules('wh_id', 'Warehouse', 'trim|required|xss_clean');
        $this->form_validation->set_rules('usertype', 'User Type', 'trim|required|xss_clean');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
        $this->form_validation->set_rules("email", 'Email Address', 'trim|required|xss_clean');
        $this->form_validation->set_rules("mobile_no", 'Mobile No.', 'trim|required|regex_match[/^[0-9]{10}$/]|xss_clean');
        if (!empty($this->input->post('password')))
            $this->form_validation->set_rules("password", 'Password ', 'trim|required|min_length[6]|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->edit_view($uid);
        } else {
            if (!empty($_FILES['logo_path']['name'])) {
                $config['upload_path'] = '../fs_files/staff_upload/';
                $upload_path = 'staff_upload/';
                $config['overwrite'] = TRUE;
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['file_name'] = $_FILES['logo_path']['name'];
                $config['file_name'] = time();
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('logo_path')) {
                    $uploadData = $this->upload->data();
                    unlink('../fs_files/' . $this->input->post('logo_path_old'));
                    $small_img = $upload_path . '' . $uploadData['file_name'];
                    
                     $uploadedImage = $uploadData['file_name']; 
                    $source_path = $config['upload_path'].$uploadedImage; 
                    $thumb_path = $config['upload_path']; 
                    $thumb_width = 120; 
                    $thumb_height = 120; 
                     
                    // Image resize config 
                    $config['image_library']    = 'gd2'; 
                    $config['source_image']     = $source_path; 
                    $config['new_image']         = $thumb_path; 
                    $config['maintain_ratio']     = FALSE; 
                    $config['width']            = $thumb_width; 
                    $config['height']           = $thumb_height; 
                     
                    // Load and initialize image_lib library 
                    $this->load->library('image_lib', $config); 
                     $this->image_lib->resize();
                } else {

                    $small_img = $this->input->post('logo_path_old');
                }
            } else {
                $small_img = $this->input->post('logo_path_old');
            }

            //echo $this->upload->display_errors(); die;
            if (!empty($this->input->post('per_day_target'))) {
                $per_day_target = $this->input->post('per_day_target');
            } else {
                $per_day_target = 0;
            }
            if (!empty($this->input->post('password'))) {
                // $salt=$this->config->item('PASSKEY');
                $has_pass = md5($this->input->post('password'));
                $data = array(
                    'name' => $this->input->post('username'),
                    'username' => $this->input->post('username'),
                    'email' => $this->input->post('email'),
                    'password' => $has_pass,
                    'user_type' => $this->input->post('usertype'),
                    'phone' => $this->input->post('mobile_no'),
                    'wh_id' => $this->input->post('wh_id'),
                    'per_day_target' => $per_day_target,
                    'logopath' => $small_img,
                    'system_access' => $this->input->post('system_access')
                );
            } else {
                $data = array(
                    'name' => $this->input->post('username'),
                    'username' => $this->input->post('username'),
                    'branch_location' => $this->input->post('branch_location'),
                    'address' => $this->input->post('address'),
                    'email' => $this->input->post('email'),
                    'per_day_target' => $per_day_target,
                    'user_type' => $this->input->post('usertype'),
                    'phone' => $this->input->post('mobile_no'),
                    'wh_id' => $this->input->post('wh_id'),
                    'logopath' => $small_img,
                    'system_access' => $this->input->post('system_access')
                );
            }
            // print($id);
            // exit();

         
    if($g_privilage=='Y'){
            if ($this->input->post('usertype')) {
                $oldUserData = $this->User_model->edit_view($uid);
               // echo $oldUserData['user_type']."!=". $this->input->post('usertype');
              //  if ($oldUserData['user_type']!= $this->input->post('usertype')) {
                  //  echo "ssssss"; die;
                    $PrivilageArray = $this->User_model->GetcheckExitsType($this->input->post('usertype'));
                    $mancat = $PrivilageArray['privilage_array'];
                    $subcat = $PrivilageArray['privilage_array_sub'];
                    $totalData = $mancat . ',' . $subcat;
                    $checkvalidData = $this->User_model->GetCheckPrivilageDataValid($uid);
                  // print_r($checkvalidData);

                    if (!empty($checkvalidData)) {
                        $priArr = array('privilage_array' => $totalData);
                        $this->User_model->PrivilageAdduserUpdate($priArr, $uid);
                    } else {
                        $priArr = array('privilage_array' => $totalData, 'customer_id' => $uid,'super_id'=>$this->session->userdata('user_details')['super_id']);
                        $this->User_model->PrivilageAdduser($priArr);
                    }
               // }
              //  echo "dddd"; die;
    }}
    
    //die;
            
            
            
            
             $this->User_model->edit($uid, $data);
            $this->session->set_flashdata('msg', $this->input->post('name') . '   has been updated successfully');
            redirect('users');
        }
    }

    public function report_view($id = null) {


        $data['userdata'] = $this->User_model->alldetails($id);
        $this->load->view('user/user_report', $data);
    }

    public function getusersprivilegeview($id = null) {

        $data['userid'] = $id;
        $data['privikegeData'] = $this->User_model->getallprivilegedata($id);
        //print_r($data['privikegeData']);
        $this->load->view('user/add_privilege', $data);
    }

    public function setCustomerPrivilage() {


        $customer_id = $this->input->get('customer_id');
        $privilage_id = $this->input->get('privilage_id');
        $onoff_true_false = $this->input->get('onoff_true_false');
        $data = array('customer_id' => $customer_id, 'privilage_id' => $privilage_id, 'onoff_true_false' => $onoff_true_false);
        $result = $this->User_model->setCustomerPrivilageUpdate($data);

        echo $result;
    }

    public function userPrivilageTable() {
        $customer_id = $this->input->get('customer_id');
        $privilege_details = $this->User_model->getallprivilegedata($id);
        $table = '<table class="table table-striped table-bordered table-hover">';
        $table .= '<thead><tr><td>Sr.No.</td><td>Privilege Name</td><td>Action</td></tr></thead><tbody>';
        foreach ($privilege_details as $key => $val) {
            $sr_no = $key + 1;
            if (checkPrivilageExitsForCustomer($customer_id, $privilege_details[$key]['id']) == 'Y') {
                $table .= '<tr><td>' . $sr_no . '</td><td>' . $privilege_details[$key]['privilege_name'] . '</td><td><label class="checkbox-inline" onclick="setUserPrivilageOnOff(' . $privilege_details[$key]['id'] . ');"><input type="checkbox" checked name="onoff_check_box_' . $privilege_details[$key]['id'] . '" id="onoff_check_box_' . $privilege_details[$key]['id'] . '"  data-toggle="toggle" value="' . $privilege_details[$key]['id'] . '" ></label></td></tr>';
            } else {
                $table .= '<tr><td>' . $sr_no . '</td><td>' . $privilege_details[$key]['privilege_name'] . '</td><td><label class="checkbox-inline" onclick="setUserPrivilageOnOff(' . $privilege_details[$key]['id'] . ');"><input type="checkbox" name="onoff_check_box_' . $privilege_details[$key]['id'] . '" id="onoff_check_box_' . $privilege_details[$key]['id'] . '"  data-toggle="toggle" value="' . $privilege_details[$key]['id'] . '" ></label></td></tr>';
            }
        }
        $table .= '</tbody></table>';

        echo $table;
    }

    public function getShowpickerListings() {

        $postData = json_decode(file_get_contents('php://input'), true);
        $return = $this->User_model->ShowPickerDataList($postData);
        echo json_encode($return);
    }

    public function showaccesstemplatelist() {


        $mainArray = $this->User_model->showaccesstemplatelistQry();
        $mainArray_new = $mainArray;
        foreach ($mainArray_new as $key => $val) {
            $newmaincatArray = explode(',', $val['privilage_array']);
            $mainCatnames = $this->User_model->GetmainCattDatashowQry($newmaincatArray);
            $newsubcatArray = explode(',', $val['privilage_array_sub']);
            $subCatnames = $this->User_model->GetmainCattDatashowQry($newsubcatArray);
            $mainArray_new[$key]['main_cat'] = $mainCatnames;
            $mainArray_new[$key]['sub_cat'] = $subCatnames;
        }
        echo json_encode($mainArray_new);
    }

    public function GetSubCatDatashow() {

        $postData = json_decode(file_get_contents('php://input'), true);
        $uid=$postData['uid'];
         $editArr = $this->User_model->edit_view_access($uid);
        $return['sub_array'] = $this->User_model->GetSubCatDatashowQry($postData['privilage_array'],$editArr['privilage_array']);
        $return['privilage_array_sub']=explode(',',$editArr['privilage_array_sub']);
        $return['privilage_array']=explode(',',$editArr['privilage_array']);
        echo json_encode($return);
    }
    
     public function getmaincatVal() {

        $postData = json_decode(file_get_contents('php://input'), true);
        $uid=$postData['uid'];
         $editArr = $this->User_model->edit_view_access($uid);
        $return['sub_array'] =  $this->User_model->userCategoryData($editArr['privilage_array']);;
        $return['privilage_array_sub']=explode(',',$editArr['privilage_array_sub']);
        $return['privilage_array']=explode(',',$editArr['privilage_array']);
        echo json_encode($return);
    }

}

?>