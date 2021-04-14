<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Seller extends MY_Controller {

    function __construct() {
        parent::__construct();
        if (menuIdExitsInPrivilageArray(4) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        //$this->load->library('pagination');
        $this->load->model('Seller_model');
        $this->load->model('Shipment_model');
        $this->load->model('ItemInventory_model');
        $this->load->library('form_validation');
        $this->load->model('CourierSeller_model');
        $this->load->model('Storage_model');
    }

    Public function add_courier_company($id = Null)
    {
        $data['id'] = $id;
        
        $this->load->view('SellerM/add_courier_company', $data);       

       
    }

    public function index() {
        $data['sellers'] = $this->Seller_model->all();

        $this->load->view('SellerM/view_sellers', $data);
    }

    public function add_view() {

        if (($this->session->userdata('user_details') != '')) {
            $data['customers'] = $this->Seller_model->customers();
            $data['city_drp'] = $this->Seller_model->fetch_all_cities();

            $this->load->view('SellerM/add_seller', $data);
        } else {
            redirect(base_url() . 'Login');
        }
    }

    public function add() {

        // print_r($this->input->post());
        // print_r($this->input->post('warehousing_charge'));
        // print_r($this->input->post('fulfillment_charge'));
        // exit();
        // print_r($this->input->post('cbm_no'));
        // exit();
        // $customer_id=$this->input->post('dd_customer');

        

        
        $this->form_validation->set_rules("email", 'Email Address', 'trim|required|is_unique[customer.email]');
        $this->form_validation->set_rules("password", 'Password ', 'trim|required|min_length[6]');
         $this->form_validation->set_rules("city_drop", 'City', 'trim|required');
        $this->form_validation->set_rules('conf_password', 'Confirm Password', 'required|matches[password]');

        //die(print_r($this->input->post()));
        if ($this->input->post('zid_active') == 'Y') {
            $this->form_validation->set_rules("manager_token", 'X-MANAGER-TOKEN ', 'required');
            $this->form_validation->set_rules('user_Agent', 'User-Agent ', 'required');
        }
		if ($this->input->post('salla_active') == 'Y') {
            $this->form_validation->set_rules("salla_manager_token", 'X-MANAGER-TOKEN ', 'required');
        }
        if ($this->form_validation->run() == FALSE) {

            $this->add_view();
        } else {
            if (!empty($this->input->post('zid_active'))) {
                $zid_active = 'Y';
            } else {
                $zid_active = 'N';
            }
			
			if (!empty($this->input->post('salla_active'))) {
                $salla_active = 'Y';
            } else {
                $salla_active = 'N';
            }
			
            //echo "sssss"; die;
            // print_r($_POST); die;
            $unique_acc_mp =  time().rand(10,100);

            $data = array(
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'location' => $this->input->post('address'),
                'phone' => $this->input->post('phone1'),
                'account_no' => $unique_acc_mp,
                'phone2' => $this->input->post('phone2'));

            //print_r($data); die;
            // 'warehousing_charge'=>$this->input->post('warehousing_charge'),
            // 'fulfillment_charge'=>$this->input->post('fulfillment_charge'),
            // 'cbm_no'=>$this->input->post('cbm_no'));
            // print_r($data);
            // print_r($data);exit;
           // $seller_id = $this->Seller_model->add($data);
            // $password1=$this->input->post('password');
            // $conf_password=$this->input->post('conf_password');
            // echo "first1".$password1."1".$conf_password;
            if (!empty($this->input->post('password'))) {

                if ($this->input->post('password') != $this->input->post('conf_password')) {
                    $errors = "Confirm password mismatch";
                } else
                    $pass = md5($_REQUEST['password']);
            } else {
                $pass = " ";
            }
            

            if (!empty($_FILES['upload_cr']['name'])) {
               $config['upload_path'] = '../fs_files/cust_upload/';
                $upload_path='cust_upload/';
                $config['overwrite'] = TRUE;
                $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
                $config['file_name'] = $_FILES['upload_cr']['name'];
                $config['file_name'] = time() . 'cr';
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('upload_cr')) {
                    $uploadData = $this->upload->data();
                    $path_upload_cr = $upload_path . '' . $uploadData['file_name'];
                }
            } else
                $path_upload_cr = "";

            if (!empty($_FILES['upload_id']['name'])) {
              $config['upload_path'] = '../fs_files/cust_upload/';
                $upload_path='cust_upload/';
                $config['overwrite'] = TRUE;
                $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
                $config['file_name'] = $_FILES['upload_id']['name'];
                $config['file_name'] = time() . 'upid';
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('upload_id')) {
                    $uploadData = $this->upload->data();
                    $path_upload_id = $upload_path . '' . $uploadData['file_name'];
                }
            } else
                $path_upload_id = "";
            if (!empty($_FILES['upload_contact']['name'])) {
               $config['upload_path'] = '../fs_files/cust_upload/';
                 $upload_path='cust_upload/';
                $config['overwrite'] = TRUE;
                $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
                $config['file_name'] = $_FILES['upload_contact']['name'];
                $config['file_name'] = time() . 'ctc';
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('upload_contact')) {
                    $uploadData = $this->upload->data();
                    $path_upload_contact = $upload_path . '' . $uploadData['file_name'];
                }
            } 
            else
            { 
            $path_upload_id = "";
            }  
            
             if(empty($this->input->post('from')))
            {
               $salla_from_date= "";
            }
            else
            {
               $salla_from_date= $this->input->post('from');  
            }
            
            if(empty($this->input->post('order_status')))
            {
               $order_status= "";
            }
            else
            {
               $order_status= $this->input->post('order_status');  
            }
            
             $secret_key = implode('-', str_split(substr(strtolower(md5(microtime() . rand(1000, 9999))), 0, 30), 6)) . $seller_id;
             $customer_info = array(
                'name' => $this->input->post('name'),
                'uniqueid' => $unique_acc_mp,
                'seller_id' => 0,
                'email' => $this->input->post('email'),
                'company' => $this->input->post('company'),
                'account_number' => $this->input->post('account_number'),
                'phone' => $this->input->post('phone1'),
                'fax' => $this->input->post('phone2'),
                'iban_number' => $this->input->post('iban_number'),
                'password' => $pass,
                'entrydate' => $this->input->post('entrydate'),
                'managerMobileNo' => $this->input->post('managerMobileNo'),
                'managerEmail' => $this->input->post('managerEmail'),
                'iban_number' => $this->input->post('iban_number'),
                'bank_fees' => $this->input->post('bankfee'),
                'vat_no' => $this->input->post('vat_no'),
                'upload_cr' => $path_upload_cr,
                'upload_id' => $path_upload_id,
                'upload_contact' => $path_upload_contact,
                'account_manager' => $this->input->post('account_manager'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city_drop'),
                'store_link' => $this->input->post('store_link'),
                'access_fm'=>'Y',
                'secret_key' => $secret_key,
                'bank_name' => $this->input->post('bank_name'),
                'manager_token' => $this->input->post('manager_token'),
		        'salla_athentication' => $this->input->post('salla_manager_token'),
                'user_Agent' => $this->input->post('user_Agent'),
                'access_lm' => $this->input->post('access_lm'),
                'super_id' => $this->session->userdata('user_details')['super_id'],
		        'salla_active' => $salla_active,
                'auto_forward' => $this->input->post('auto_forward'),
                'zid_active' => $zid_active,
                'salla_from_date' => $salla_from_date,
                'invoice_type' => $this->input->post('invoice_type'),
                'first_out' => $this->input->post('first_out'),
            );

           // print_r($customer_info); die; 
            // $this->Seller_model->customer($seller_id,$customer_id);
            if (empty($errors)) {
                $customer_id = $this->Seller_model->add_customer($customer_info);
                
               // die;
               // $this->Seller_model->update_seller_id($seller_id, $customer_id);

                //// echo  $customer_id.'//'. $seller_id;     exit();  
                $this->session->set_flashdata('msg', $this->input->post('name') . '   has been added successfully');
            } else {
                $this->session->set_flashdata('msg', $this->input->post('name') . '   Customer adding is failed');
            }

            //die;

            redirect('Seller');
        }
    }

    public function updateCourier() {
        $dataArray = $this->input->post();
        $idArray = $dataArray['id'];
        $data = array();
        foreach ($idArray as $id) {
            array_push($data, array('id' => $id, 'priority' => $dataArray['priority'][$id], 'status' => $dataArray['status'][$id]));
        }
        if ($data) {
            $this->CourierSeller_model->updateCourier($data);
            $this->session->set_flashdata('msg', 'has been Courier Set successfully');
        }


        redirect(base_url() . 'Seller');
       // print_r($data); die();
    }

    public function set_courier($id) {
        $data['fullfilment_drp'] = $this->CourierSeller_model->getSellerAddCourier($id);
        //echo "<pre>"; print_r($data);  die; 
        $this->load->view('SellerM/set_courier', $data);
    }

    public function storage_charges($id) {
       $data['fullfilment_drp'] = $this->Storage_model->getSellerStorageCharges($id);
      // echo "<pre>"; print_r($data);  die; 
        $this->load->view('SellerM/storage_charges', $data);
    }

    public function add_storagecharges($id = null) {
               $view['editid'] = $id;
        // $view['editdata']=$this->Storage_model->editviewquery($id); 
        $this->load->view('SellerM/add_storagecharges', $view);
    }


    public function edit_view($id) {
        // $id = $this->input->get('id');
        $data['seller'] = $this->Seller_model->edit_view($id);
        $data['city_drp'] = $this->Seller_model->fetch_all_cities();
        $data['customer'] = $this->Seller_model->edit_view_customerdata($id);

        $this->load->view('SellerM/seller_detail', $data);
    }

    public function edit($id) {
        //$id=$this->input->post('id');
        //echo "<pre>";print_r($this->input->post());exit;


        if (!empty($_FILES['upload_cr']['name'])) {
            $config['upload_path'] = '../fs_files/cust_upload/';
             $upload_path='cust_upload/';
            $config['overwrite'] = TRUE;
            $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
            $config['file_name'] = $_FILES['upload_cr']['name'];
            $config['file_name'] = time() . 'cr';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('upload_cr')) {
                $uploadData = $this->upload->data();
                 unlink('../fs_files/'.$this->input->post('upload_cr_old'));
                $path_upload_cr = $upload_path . '' . $uploadData['file_name'];
            }
        } else
            $path_upload_cr = $this->input->post('upload_cr_old');
        
        if (!empty($_FILES['upload_id']['name'])) {
           $config['upload_path'] = '../fs_files/cust_upload/';
             $upload_path='cust_upload/';
            $config['overwrite'] = TRUE;
            $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
            $config['file_name'] = $_FILES['upload_id']['name'];
            $config['file_name'] = time() . 'upid';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('upload_id')) {
                $uploadData = $this->upload->data();
                 unlink('../fs_files/'.$this->input->post('upload_id_old'));
                $path_upload_id = $upload_path . '' . $uploadData['file_name'];
            }
        } else
            $path_upload_id = $this->input->post('upload_id_old');


        if (!empty($_FILES['upload_contact']['name'])) {
          $config['upload_path'] = '../fs_files/cust_upload/';
             $upload_path='cust_upload/';
            $config['overwrite'] = TRUE;
            $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
            $config['file_name'] = $_FILES['upload_contact']['name'];
            $config['file_name'] = time() . 'ctc';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('upload_contact')) {
                $uploadData = $this->upload->data();
                unlink('../fs_files/'.$this->input->post('upload_contact_old'));
                $path_upload_contact = $upload_path . '' . $uploadData['file_name'];
            }
        } else
            $path_upload_contact = $this->input->post('upload_contact_old');
        if($this->input->post('zid_active')=='Y'){
            $zid_access = 'FM';
        }


        //echo $path_upload_contact; die;
        
        
      $first_out=$this->input->post('first_out');
        if (!empty($this->input->post('password'))) {
            $customer_info = array(
                'name' => $this->input->post('name'),
                'account_number' => $this->input->post('account_number'),
                'phone' => $this->input->post('phone1'),
                'fax' => $this->input->post('phone2'),
                'iban_number' => $this->input->post('iban_number'),
                'company' => $this->input->post('company'),
                'entrydate' => $this->input->post('entrydate'),
                'password' => md5($this->input->post('password')),
                'managerMobileNo' => $this->input->post('managerMobileNo'),
                'managerEmail' => $this->input->post('managerEmail'),
                'iban_number' => $this->input->post('iban_number'),
                'bank_fees' => $this->input->post('bankfee'),
                'vat_no' => $this->input->post('vat_no'),
                'upload_cr' => $path_upload_cr,
                'upload_id' => $path_upload_id,
                'upload_contact' => $path_upload_contact,
                'account_manager' => $this->input->post('account_manager'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city_drop'),
                'store_link' => $this->input->post('store_link'),
                'access_lm' => $this->input->post('access_lm'),
                'bank_name' => $this->input->post('bank_name'),
                'zid_active' => $this->input->post('zid_active'),
		'manager_token' => $this->input->post('manager_token'),
                'user_Agent' => $this->input->post('user_Agent'),
                'auto_forward' => $this->input->post('auto_forward'),
                'salla_athentication' => $this->input->post('salla_manager_token'),
                'salla_from_date' => $this->input->post('from'),
               // 'invoice_type' => $this->input->post('invoice_type'),
                'first_out'=>$first_out,
                'zid_access' => $zid_access
            );
        } else {
            $customer_info = array(
                'name' => $this->input->post('name'),
                'account_number' => $this->input->post('account_number'),
                'company' => $this->input->post('company'),
                'phone' => $this->input->post('phone1'),
                'fax' => $this->input->post('phone2'),
                'iban_number' => $this->input->post('iban_number'),
                'managerMobileNo' => $this->input->post('managerMobileNo'),
                'entrydate' => $this->input->post('entrydate'),
                'managerEmail' => $this->input->post('managerEmail'),
                'iban_number' => $this->input->post('iban_number'),
                'bank_fees' => $this->input->post('bankfee'),
                'vat_no' => $this->input->post('vat_no'),
                'upload_cr' => $path_upload_cr,
                'upload_id' => $path_upload_id,
                'upload_contact' => $path_upload_contact,
                'account_manager' => $this->input->post('account_manager'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city_drop'),
                'store_link' => $this->input->post('store_link'),
                'access_lm' => $this->input->post('access_lm'),
                'bank_name' => $this->input->post('bank_name'),
                'zid_active' => $this->input->post('zid_active'),
		'manager_token' => $this->input->post('manager_token'),
                'user_Agent' => $this->input->post('user_Agent'),
                'auto_forward' => $this->input->post('auto_forward'),
                'salla_athentication' => $this->input->post('salla_manager_token'),
                'salla_from_date' => $this->input->post('from'),
                //'invoice_type' => $this->input->post('invoice_type'),
                'first_out'=>$first_out,
                'zid_access' => $zid_access
            );  
        }

         //print_r($customer_info);
        //exit();
         
         
        

       // $this->Seller_model->edit($id, $data);
        $this->Seller_model->edit_custimer($id, $customer_info);
        $this->session->set_flashdata('msg', $this->input->post('name') . '   has been updated successfully');
        redirect('Seller');
    }

    public function report_view($id = null) {


//error_reporting(E_ALL);
//ini_set('display_errors', '1');
        $data['status'] = $this->Shipment_model->allstatus();
        $data['total_inventory_items'] = $this->ItemInventory_model->count_find($id);
        $data['seller_info'] = $this->Seller_model->find($id);
        $data['customer_info'] = $this->Seller_model->find_customer($id);




        // print_r($data['seller_info']);
        // exit();
        $data['seller_shipments'] = $this->Shipment_model->find_by_seller($id);

        if ($data['seller_shipments'] != Null) {
            // 	print('<pre>');
            // print_r($data['seller_shipments']);
            // 	print('</pre>');
            // exit();
            // for($i=0;$i<count($data['seller_shipments']);$i++)
            // {
            $array = array(
                'item_inventory.seller_id' => $id,
                    //'item_sku'=>$data['seller_shipments'][$i]->sku
            );
            // print_r($data['seller_shipments'][$i]);
            // exit();

            $data['item_inventory'] = $this->ItemInventory_model->find_by_seller($array);

            //}
            // print('<pre>');
            // print_r($data['item_inventory']);
            // print('</pre>');
            // exit();
            // print_r($data['seller_shipments']);
            //   exit();
            ///////////////////////////////////////////////////////////////////////////////////
            // for($i=0;$i<count($data['total_inventory_items']);$i++)
            // {
            // 	$array= array(
            // 		'seller_id' =>$id,  
            // 	);
            // 	$item_inventory_all[$i]=$this->ItemInventory_model->find_by_seller($array);
            // }
            // $data['items']=$this->Item_model->all();
            /////////////////////////////////////////////////////////////////////////////////////
            // print('<pre>');
            // print_r($item_inventory_all);
            // print('</pre>');
            // exit();
            // print_r($data['seller_shipments']);
            // print_r($item_inventory);
            //  exit();
            //print_r($data['status']);
            //print_r($data['item_inventory']);
            //print_r($data['total_inventory_items']);
            //print_r($data['seller_shipments']);
            //print_r($data['seller_info']);
            //exit();
            //$info=array(
            // 'item_inventory'=>$item_inventory,
            // 'item_inventory_all'=>$item_inventory_all,
            //'data'=>$data
            //);
            // print_r($item_inventory);
            // exit();
            // print_r($data['seller_shipments'][0]->sku);
            // exit();
            $this->load->view('SellerM/seller_report', $data);
        } elseif ($data['seller_shipments'] == Null) {
            // for($i=0;$i<count($data['total_inventory_items']);$i++)
            // 		{
            // 			$array= array(
            // 				'seller_id' =>$id,  
            // 			);
            // 			$item_inventory_all[$i]=$this->ItemInventory_model->find_by_seller($array);
            // 		}
            // 		$data['items']=$this->Item_model->all();
            // 	$info=array(
            // 	'item_inventory_all'=>$item_inventory_all,
            // 	'data'=>$data
            // );
            // print_r($data['seller_shipments']);
            // exit();
            $this->load->view('SellerM/seller_report', $data);
        }
    }

}

?>