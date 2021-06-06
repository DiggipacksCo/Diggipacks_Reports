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
   public function smsconfigrationsave($id=null)
   {
    
    $dataArray =$this->input->post();
    if($id==null)
    {
        $socialArray = array('company_name' => $dataArray['company_name'], 'api_url' => $dataArray['api_url'], 'params' => $dataArray['params'], 'super_id' => $this->session->userdata('user_details')['super_id']);
        $res = $this->General_model->sms_detail($socialArray);
    }
    else
    {
        
        $socialArray = array('company_name' => $dataArray['company_name'], 'api_url' => $dataArray['api_url'], 'params' => $dataArray['params']);
        $res = $this->General_model->smsUpdate($socialArray,$id);
        

    }
   
  //echo "<pre>"; print_r( $res); exit;



if ($res == true)
    $this->session->set_flashdata('msg', 'Data has been updated successfully');
else
    $this->session->set_flashdata('err_msg', 'Try again');
    redirect(base_url() . 'smsconfigration');
   }


   public function smsconfigration() {

    $data['res_data'] = $this->General_model->GetsmsConfigrationDataQry();
    $this->load->view('generalsetting/smsconfig', $data);
}

public function addsmssetting($id=null) {

    if($id!=null)
    { 
        $data['EditData'] = $this->General_model->GetsmsConfigrationDataQry();
        //print_r($data['res_data']);
    }
  
    $this->load->view('generalsetting/addsms', $data);
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
            
            $zonelist = $this->zoneList();
            //print "<pre>"; print_r($list);die;
            $data['EditData'] = $this->General_model->GetallcompanyDetails();
            $data['TimeZone'] = $zonelist;
            $this->load->view('generalsetting/companydetails', $data);

            //$this->load->view('home');
        } else {
            redirect(base_url() . 'Login');
        }
    }
    
    function zoneList(){
       $listArray = array( 
        array("value"=>"-12","name"=>"[UTC - 12] Baker Island Time"),
        array("value"=>"-11","name"=>"[UTC - 11] Niue Time, Samoa Standard Time"),
        array("value"=>"-10","name"=>"[UTC - 10] Hawaii-Aleutian Standard Time, Cook Island Time"),
        array("value"=>"-9.5","name"=>"[UTC - 9:30] Marquesas Islands Time"),
        array("value"=>"-9","name"=>"[UTC - 9] Alaska Standard Time, Gambier Island Time"),
        array("value"=>"-8","name"=>"[UTC - 8] Pacific Standard Time"),
        array("value"=>"-7","name"=>"[UTC - 7] Mountain Standard Time"),
        array("value"=>"-6","name"=>"[UTC - 6] Central Standard Time"),
        array("value"=>"-5","name"=>"[UTC - 5] Eastern Standard Time"),
        array("value"=>"-4.5","name"=>"[UTC - 4:30] Venezuelan Standard Time"),
        array("value"=>"-4","name"=>"[UTC - 4] Atlantic Standard Time"),
        array("value"=>"-3.5","name"=>"[UTC - 3:30] Newfoundland Standard Time"),
        array("value"=>"-3","name"=>"[UTC - 3] Amazon Standard Time, Central Greenland Time"),
        array("value"=>"-2","name"=>"[UTC - 2] Fernando de Noronha Time, South Georgia &amp; the South Sandwich Islands Time"),
        array("value"=>"-1","name"=>"[UTC - 1] Azores Standard Time, Cape Verde Time, Eastern Greenland Time"),
        array("value"=>"0" ,"name"=>"[UTC] Western European Time, Greenwich Mean Time"),
        array("value"=>"1","name"=>"[UTC + 1] Central European Time, West African Time"),
        array("value"=>"2","name"=>"[UTC + 2] Eastern European Time, Central African Time"),
        array("value"=>"3","name"=>"[UTC + 3] Moscow Standard Time, Eastern African Time"),
        array("value"=>"3.5","name"=>"[UTC + 3:30] Iran Standard Time"),
        array("value"=>"4","name"=>"[UTC + 4] Gulf Standard Time, Samara Standard Time"),
        array("value"=>"4.5","name"=>"[UTC + 4:30] Afghanistan Time"),
        array("value"=>"5","name"=>"[UTC + 5] Pakistan Standard Time, Yekaterinburg Standard Time"),
        array("value"=>"5.5","name"=>"[UTC + 5:30] Indian Standard Time, Sri Lanka Time"),
        array("value"=>"5.75","name"=>"[UTC + 5:45] Nepal Time"),
        array("value"=>"6","name"=>"[UTC + 6] Bangladesh Time, Bhutan Time, Novosibirsk Standard Time"),
        array("value"=>"6.5","name"=>"[UTC + 6:30] Cocos Islands Time, Myanmar Time"),
        array("value"=>"7","name"=>"[UTC + 7] Indochina Time, Krasnoyarsk Standard Time"),
        array("value"=>"8","name"=>"[UTC + 8] Chinese Standard Time, Australian Western Standard Time, Irkutsk Standard Time"),
        array("value"=>"8.75","name"=>"[UTC + 8:45] Southeastern Western Australia Standard Time"),
        array("value"=>"9","name"=>"[UTC + 9] Japan Standard Time, Korea Standard Time, Chita Standard Time"),
        array("value"=>"9.5","name"=>"[UTC + 9:30] Australian Central Standard Time"),
        array("value"=>"10","name"=>"[UTC + 10] Australian Eastern Standard Time, Vladivostok Standard Time"),
        array("value"=>"10.5","name"=>"[UTC + 10:30] Lord Howe Standard Time"),
        array("value"=>"11","name"=>"[UTC + 11] Solomon Island Time, Magadan Standard Time"),
        array("value"=>"11.5","name"=>"[UTC + 11:30] Norfolk Island Time"),
        array("value"=>"12","name"=>"[UTC + 12] New Zealand Time, Fiji Time, Kamchatka Standard Time"),
        array("value"=>"12.75","name"=>"[UTC + 12:45] Chatham Islands Time"),
        array("value"=>"13","name"=>"[UTC + 13] Tonga Time, Phoenix Islands Time"),
        array("value"=>"14","name"=>"[UTC + 14] Line Island Time")
       );
       return $listArray;
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
                    'default_currency'=>$this->input->post('default_currency'),
                    'default_time_zone'=>$this->input->post('default_time_zone'),
                    'country_code'=>$this->input->post('country_code'),
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
