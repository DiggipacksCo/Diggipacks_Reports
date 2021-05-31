<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Generalsetting extends MY_Controller {

    function __construct() {
        parent::__construct();
        if (menuIdExitsInPrivilageArray(68) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $this->load->model('General_model');
        $this->load->model('Seller_model');
        $this->load->model('Shipment_model');
        $this->load->model('ItemInventory_model');
        $this->load->library('form_validation');
        $this->load->model('CourierSeller_model');
        $this->load->model('Ccompany_model');
    }

    public function defaultlist_view() {

        $data['fullfilment_drp'] = $this->General_model->getSellerAddCourier();
        $this->load->view('generalsetting/defaultlist_view', $data);
    }

    public function update_password() {


        $this->load->view('generalsetting/update_password');
    }

    
    public function filter() {
        // print("heelo"); 
        // exit();
        // $search=$this->input->post('tracking_numbers');
        // echo $search;exit;

		// error_reporting(-1);
		// ini_set('display_errors', 1);
        $_POST = json_decode(file_get_contents('php://input'), true);

        $delivered = $_POST['status'];
     
        $page_no = $_POST['page_no'];
       
        $awb = $_POST['slip_no'];
        $cc_id = $_POST['cc_id'];
		$status = $_POST['status'];

        //echo json_encode($_POST);
        // print($exact);
        // print($awb);
        ///print($sku);  
        // print($from);
        // print($to);
        // print($delivered);  
        // print($seller);
        //exit();

        $shipments = $this->General_model->getShipmentLogview($awb, $page_no,$cc_id,$status);


        //$shiparrayexcel = $shipmentsexcel['result'];
        $shiparray = $shipments['result'];
        //echo json_encode($shipments); die;
        $ii = 0;
        $jj = 0;

        $tolalShip = $shipments['count'];
        $downlaoadData = 2000;
        $j = 0;
        for ($i = 0; $i < $tolalShip;) {
            $i = $i + $downlaoadData;
            if ($i > 0) {
                $expoertdropArr[] = array('j' => $j, 'i' => $i);
            }
            $j = $i;
        }
        foreach ($shipments['result'] as $rdata) {

           
            $shiparray[$ii]['cc_name'] = GetCourCompanynameId($rdata['cc_id'], 'company');
			$shiparray[$ii]['update_date'] =  date("Y-m-d H:i:s", strtotime('+3 hours', strtotime($rdata['update_date'])));
        

            //$shiparray='rith';
            $ii++;
        }




        //echo '<pre>';
        //print_r($shiparray);
        //echo json_encode($shiparray);
        // die;
        //$dataArray['excelresult'] = $shiparrayexcel;
        $dataArray['dropexport'] = $expoertdropArr;
        $dataArray['result'] = $shiparray;
        $dataArray['count'] = $shipments['count'];
        //print_r($shipments);
        //exit();
        echo json_encode($dataArray);
    }

    
    public function ShipmentLogview() {

        $post = $this->input->post();
        //$data['detail'] = $this->General_model->getShipmentLogview($post);

        $this->load->view('generalsetting/ShipmentLogview', $data);
    }

    public function updateCourier() {
        $dataArray = $this->input->post();
        $idArray = $dataArray['id'];
        $data = array();
        foreach ($idArray as $id) {
            array_push($data, array('id' => $id, 'priority' => $dataArray['priority'][$id], 'status' => $dataArray['status'][$id]));
        }
        if ($data) {
            $resM = $this->General_model->updateCourier($data);
        }
        redirect(base_url() . 'defaultlist_view');
    }

    public function CompanyDetails() {

        if ($this->session->userdata('user_details')) {

            $data['EditData'] = $this->General_model->GetallcompanyDetails();
            $this->load->view('generalsetting/companydetails', $data);

            //$this->load->view('home');
        } else {
            redirect(base_url() . 'Login');
        }
    }

    public function updateform() {
//        if (!empty($_FILES['logo']['name'])) {
//            $config['upload_path'] = 'assets/logo/';
//            $config['overwrite'] = TRUE;
//            $config['allowed_types'] = 'jpg|jpeg|png|gif';
//            $config['file_name'] = $_FILES['logo']['name'];
//
//            $this->load->library('upload', $config);
//            $this->upload->initialize($config);
//
//            if ($this->upload->do_upload('logo')) {
//                $uploadData = $this->upload->data();
//                $small_img = $config['upload_path'] . '' . $uploadData['file_name'];
//            } else {
//
//                $small_img = $this->input->post('logo_old');
//            }
//        } else
//            $small_img = $this->input->post('logo_old');

        $updatearray = array(
                    "company_name" => $this->input->post('company_name'),
                    'company_address' => $this->input->post('company_address'),
                    'phone' => $this->input->post('phone'),
                    'fax' => $this->input->post('fax'),
                    'email' => $this->input->post('email'),
                    'support_email' => $this->input->post('support_email'),
                    'webmaster_email' => $this->input->post('webmaster_email'),
                    'default_awb_char_fm' => $this->input->post('default_awb_char_fm'), 
                    'e_city' => implode(',', $this->input->post('e_city')), 
                    'tollfree_fm' => $this->input->post('tollfree_fm'), 
                    'theme_color_fm' => $this->input->post('theme_color_fm'), 
                    'auto_assign_picker' => $this->input->post('auto_assign_picker'),
                    'font_color'=>$this->input->post('font_color'),
                    'vat'=>$this->input->post('vat'),
                    //'dropoff_option'=>$this->input->post('dropoff_option'),
                    'default_currency'=>$this->input->post('default_currency')
                );


        //print "<pre>"; print_r($updatearray);die;
        $res = $this->General_model->Getupdatecompnaydata($updatearray);
        if ($res == true)
            $this->session->set_flashdata('msg', 'Data has been updated successfully');
        else
            $this->session->set_flashdata('err_msg', 'Try again');

        redirect('CompanyDetails');
    }

    public function check_old() {
        
    
        $PostData = json_decode(file_get_contents('php://input'), true);
        $password = $PostData['password'];
      //  echo "sss".$password."sssss";
        $return = $this->General_model->checkOld($password);
        echo json_encode($return);
    }
    
    public function UpdatePasswordFrm()
    {
         $PostData = json_decode(file_get_contents('php://input'), true);
        $old_pass = $PostData['old_pass'];
        $new_pass = $PostData['new_pass'];
        $confrim_pass = $PostData['confrim_pass'];
        if(!empty($old_pass) && !empty($new_pass) && !empty($confrim_pass))
        {
            if($new_pass==$confrim_pass)
            {
                $updateArr=array('password'=>md5($new_pass));
                $this->General_model->updatePassword($updateArr);
               $return=array('status'=>'succ','mess'=>"Password Changed Successfully!");   
            }
            else
            {
              $return=array('status'=>'match','mess'=>"password don't match!");   
            }
            
        }
        else
        {
         $return=array('status'=>'errror','mess'=>"all field are required!");   
        }
    
       
        echo json_encode($return);
    }

    public function updatePassword() {


        // echo $_POST;
        $res_data = $this->GeneralSetting_model->updatePassword(array('password' => md5($_POST['new_password'])), $this->session->userdata('useridadmin'));
    }

}
