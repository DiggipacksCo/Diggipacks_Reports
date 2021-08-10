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
        $this->load->helper('zid_helper');
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
    
    public function active_seller($id=null,$status=null) {

        if (($this->session->userdata('user_details') != '')) {
           
            if($id>0 && ($status=='Y' || $status=='N'))
            {
                $updateArr=array('status'=>$status);
                $this->Seller_model->edit($id,$updateArr);
                
                if($status=='Y')
                {
                $this->session->set_flashdata('msg','has been updated Active successfully');
                }
                else
                {
                $this->session->set_flashdata('msg','has been updated Inactive successfully');
                }
                 
            }
            else
            {
                $this->session->set_flashdata('errmsg','try again');
            }
            
            redirect('Seller');
        } else {
            redirect(base_url() . 'Login');
        }
    }

    
    
    public function add() {

        $this->form_validation->set_rules("email", 'Email Address', 'trim|required|is_unique[customer.email]');
        $this->form_validation->set_rules("password", 'Password ', 'trim|required|min_length[6]');
        $this->form_validation->set_rules("city_drop", 'City', 'trim|required');
        $this->form_validation->set_rules('conf_password', 'Confirm Password', 'required|matches[password]');

        //die(print_r($this->input->post()));
        if ($this->input->post('zid_active') == 'Y') {
            $this->form_validation->set_rules("manager_token", 'X-MANAGER-TOKEN', 'required');
            $this->form_validation->set_rules('user_Agent', 'User-Agent', 'required');
            $this->form_validation->set_rules('zid_sid', 'Zid Store ID', 'required');
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
            $unique_acc_mp = time() . rand(10, 100);

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
                $upload_path = 'cust_upload/';
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
                $upload_path = 'cust_upload/';
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
                $upload_path = 'cust_upload/';
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
            } else {
                $path_upload_id = "";
            }

            if (empty($this->input->post('from'))) {
                $salla_from_date = "";
            } else {
                $salla_from_date = $this->input->post('from');
            }

            if (empty($this->input->post('order_status'))) {
                $order_status = "";
            } else {
                $order_status = $this->input->post('order_status');
            }

            
            $u_type=$this->input->post('name');
            if($u_type=='B2B')
            {
                $u_type="B2B";
            }
            else
            {
              $u_type="B2C";  
            }
            $secret_key = implode('-', str_split(substr(strtolower(md5(microtime() . rand(1000, 9999))), 0, 30), 6)) . $seller_id;
            $customer_info = array(
                'u_type'=>$u_type,
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
                'access_fm' => 'Y',
                'secret_key' => $secret_key,
                'bank_name' => $this->input->post('bank_name'),
                // 'manager_token' => $this->input->post('manager_token'),
                // 'salla_athentication' => $this->input->post('salla_manager_token'),
                'user_Agent' => $this->input->post('user_Agent'),
                'access_lm' => $this->input->post('access_lm'),
                'super_id' => $this->session->userdata('user_details')['super_id'],
                //'salla_active' => $salla_active,
                'auto_forward' => $this->input->post('auto_forward'),
                //'zid_active' => $zid_active,
                // 'salla_from_date' => $salla_from_date,
                'invoice_type' => $this->input->post('invoice_type'),
                'first_out' => $this->input->post('first_out'),
                    //'zid_sid' => $this->input->post('zid_sid'),
                    //'zid_status' => $this->input->post('zid_status'),
            );


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

    

    Public function add_courier_company($id = Null)
    {
        $data['id'] = $id;        
        $this->load->view('SellerM/add_courier_company', $data);      
       
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
            $upload_path = 'cust_upload/';
            $config['overwrite'] = TRUE;
            $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
            $config['file_name'] = $_FILES['upload_cr']['name'];
            $config['file_name'] = time() . 'cr';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('upload_cr')) {
                $uploadData = $this->upload->data();
                unlink('../fs_files/' . $this->input->post('upload_cr_old'));
                $path_upload_cr = $upload_path . '' . $uploadData['file_name'];
            }
        } else
            $path_upload_cr = $this->input->post('upload_cr_old');

        if (!empty($_FILES['upload_id']['name'])) {
            $config['upload_path'] = '../fs_files/cust_upload/';
            $upload_path = 'cust_upload/';
            $config['overwrite'] = TRUE;
            $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
            $config['file_name'] = $_FILES['upload_id']['name'];
            $config['file_name'] = time() . 'upid';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('upload_id')) {
                $uploadData = $this->upload->data();
                unlink('../fs_files/' . $this->input->post('upload_id_old'));
                $path_upload_id = $upload_path . '' . $uploadData['file_name'];
            }
        } else
            $path_upload_id = $this->input->post('upload_id_old');


        if (!empty($_FILES['upload_contact']['name'])) {
            $config['upload_path'] = '../fs_files/cust_upload/';
            $upload_path = 'cust_upload/';
            $config['overwrite'] = TRUE;
            $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
            $config['file_name'] = $_FILES['upload_contact']['name'];
            $config['file_name'] = time() . 'ctc';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('upload_contact')) {
                $uploadData = $this->upload->data();
                unlink('../fs_files/' . $this->input->post('upload_contact_old'));
                $path_upload_contact = $upload_path . '' . $uploadData['file_name'];
            }
        } else
            $path_upload_contact = $this->input->post('upload_contact_old');
        if ($this->input->post('zid_active') == 'Y') {
            $zid_access = 'FM';
        }


        //echo $path_upload_contact; die;
$u_type = $this->input->post('u_type');
     if($u_type=='B2B')
            {
                $u_type="B2B";
            }
            else
            {
              $u_type="B2C";  
            }
        $first_out = $this->input->post('first_out');
        if (!empty($this->input->post('password'))) {
            $customer_info = array(
                  'u_type' => $u_type,
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
                //'salla_athentication' => $this->input->post('salla_manager_token'),
                // 'salla_from_date' => $this->input->post('from'),
                // 'invoice_type' => $this->input->post('invoice_type'),
                'first_out' => $first_out,
                'zid_access' => $zid_access,
                    // 'zid_sid' => $this->input->post('zid_sid'),
                    // 'zid_status' => $this->input->post('zid_status'),
            );
        } else {
            $customer_info = array(
                   'u_type' => $u_type,
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
                //'salla_athentication' => $this->input->post('salla_manager_token'),
                //'salla_from_date' => $this->input->post('from'),
                //'invoice_type' => $this->input->post('invoice_type'),
                'first_out' => $first_out,
                'zid_access' => $zid_access,
                    //'zid_sid' => $this->input->post('zid_sid'),
                    //'zid_status' => $this->input->post('zid_status'),
            );
        }

       // echo "<pre>";print_r($customer_info);exit();
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

    public function ZidProducts($id) {
        $data['zidproducts'] = $this->Seller_model->zidproduct($id);
        $storeID = $data['zidproducts'];
        $token = GetallCutomerBysellerId($id, 'manager_token');
        $store_link = "https://api.zid.dev/app/v2/products";
        $bearer = site_configTable('zid_provider_token');
        $ZidProductRT = ZidPcURL($storeID, $store_link, $bearer,$token); 

       // print_r( $ZidProductRT); exit;
        $ZidProductArr_total = json_decode($ZidProductRT, true);

        $total_pages = 1;
        if ($ZidProductArr_total['count'] > 50) {
            $total_pages = ceil($ZidProductArr_total['count'] / 50);
        }
         if(empty($this->input->post('i')))
         {
            $i = 1;    
         }
         else
         {
            $i = $this->input->post('i');      
         }
        $results = array();
        $results2 = array();
        $p = 0;
        $s = 0;
       
            $storlink_page = "https://api.zid.dev/app/v2/products?page=" . $i;
            $ZidProductArr = ZidPcURL($storeID, $storlink_page, $bearer,$token); 
            $ZidProductArr = json_decode($ZidProductArr, true);

            if (isset($ZidProductArr['results'])) {
                foreach ($ZidProductArr['results'] as $key => $products) {

                    if (isset($products['structure']) && $products['structure'] == 'parent') {
                      $product_link = $store_link .'/'. $products['id'].'/';
                        

                        $product = json_decode(ZidPcURL($storeID, $product_link, $bearer,$token), true);
                       // print_r($product); exit;
                       if(!empty( $product)){
                        if (count($product['variants']) > 0) {
                            foreach ($product['variants'] as $key=>$variant) {

                                $results[] = $variant;
                             
                            }
                        } else {
                            $results[] = $product;
                          
                        }
                    }
                    } else {

                        $results2[] = $products;
                       
                    }
                }
            
        }

        $final_Arr = array_merge($results, $results2);
        // echo '<pre>';
        // print_r( $results); exit;

        $ZidProducts['products'] = $final_Arr;
        $ZidProducts['total_pages'] = $total_pages;
        $ZidProducts['current_page'] = $i;
        $ZidProducts['seller_id'] = $id;
        
        

        $this->load->view('SellerM/view_zidp', $ZidProducts);

    }

    public function SaveZidProducts() {
              
        foreach ($this->input->post('selsku') as  $value) {
          

            $skuarray = array();
            $editData = array();
            //echo $this->input->post('image')[$value]; exit;
            file_put_contents('assets/item_uploads/'.$this->input->post('sku')[$value].'.jpg',  file_get_contents($this->input->post('image')[$value]));
            $skuarray = array(
                'sku' => $this->input->post('sku')[$value],
                'zid_pid' => $this->input->post('pid')[$value],
                'name' => $this->input->post('skuname')[$value],
                'super_id' => $this->session->userdata('user_details')['super_id'],
                'description' => $this->input->post('description')[$value],
                'type' => 'B2C',
                'storage_id' => $this->input->post('storageid'),
                'wh_id' => $this->input->post('warehouseid'),
                'sku_size' => $this->input->post('sku_size'),
                'entry_date' => date("Y-m-d H:i:s"),
                'item_path'=>'assets/item_uploads/'.$this->input->post('sku')[$value].'.jpg'
            );
            $editData= array(
                'name' => $this->input->post('skuname')[$value],
                'zid_pid' => $this->input->post('pid')[$value],
                'item_path'=>'assets/item_uploads/'.$this->input->post('sku')[$value].'.jpg'
               
            );
         
           $exist_zidsku_id = exist_zidsku_id($this->input->post('sku')[$value], $this->session->userdata('user_details')['super_id']);
            if ($exist_zidsku_id != '' || $exist_zidsku_id != 0) {
                $this->Item_model->edit($exist_zidsku_id,$editData);
            } else {
                AddSKUfromZid($skuarray);
            }
        }
        $this->session->set_flashdata('msg', "Selected Sku has been Added Successfully");
        redirect('Item');
    }


    public function zidconfig($id) {
        $this->load->view('SellerM/seller_zid', $data);
    }
    public function zidCities(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zid.dev/app/v2/settings/cities/by-country-id/184",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization:Bearer ".site_configTable('zid_provider_token'),
            ),
        ));

        $response = json_decode(curl_exec($curl), true);
        return $response['cities'];
    }

    public function updateZidConfig($id,$edit_id=null) {

        $data['customer'] = $this->Seller_model->edit_view_customerdata($id);
        $data['seller'] = $this->Seller_model->edit_view($id);
        $ListArr = $this->zidCities();//$this->Seller_model->zidCities();
        $data['delivery_options'] = $this->Seller_model->deliverOptions($id); 
        if($edit_id!=null)
        {
            $data['delivery_option_edit'] = $this->Seller_model->deliverOptionsByid($edit_id); 
        }
       
            // echo '<pre>';
            //         print_r(  $ListArr);  exit;
            $pre=array();
       if(  !empty($data['delivery_options']))
       {
        $listcity =   explode(',', $data['delivery_options'][0]['zid_city']); 

        $keys = array();        
           foreach ($listcity as $citie)
           {
                   $key = array_search($citie, array_column( $ListArr, 'id'));

                   if($key!=-1)
                   {
               
                       array_push($pre, $ListArr[$key]);  // cities add in next array
                       array_push($keys, $key); // selected cities remove from first list box
                   
                   }
           }
         
           foreach ($keys as $removecity)
           {
               unset($ListArr[$removecity]);    // remove selected cities from left side panel 
           }
       }
       
           $data['ListArr'] = $ListArr;
           $data['pre']= $pre;  
        
        
     
        if ($this->input->post('updatezid')) {

            if($this->input->post('zid_active')=='Y')
            {
                $zid_access='FM';
            }
            $update_data = array(
                'manager_token' => $this->input->post('manager_token'),
                'zid_sid' => $this->input->post('zid_sid'),
                'zid_status' => $this->input->post('zid_status'),
                'zid_active' => $this->input->post('zid_active'),
                'zid_access'=>  $zid_access,
            );

            $user = $this->Seller_model->update_zid($id, $update_data);

            if ($user > 0) {
                
                if(  $this->zidWebhookSubscriptionDelete( $data['customer']))
                    {
                    $data['customer'] = $this->Seller_model->edit_view_customerdata($id);
                    $this->zidWebhookSubscriptionCreate( $data['customer']);  
                    }
                        $this->session->set_flashdata('msg', $this->input->post('name') . '   has been updated successfully');
                            redirect('Seller/updateZidConfig/'.$id);
            }
        }
        $this->load->view('SellerM/seller_zidconfig', $data);
    }

    public function updateSallaConfig($id) {
        $data['customer'] = $this->Seller_model->edit_view_customerdata($id);
        $data['seller'] = $this->Seller_model->edit_view($id);

        if ($this->input->post('updatesalla')) {

           

            $update_data = array(
                'salla_athentication' => $this->input->post('salla_manager_token'),
                'salla_from_date' => $this->input->post('from'),
                'salla_active' => $this->input->post('salla_active'),
                'salla_status'=>$this->input->post('salla_status'),
                'salla_webhook_subscribed'=> $this->input->post('salla_active')
            );

            if ($this->Seller_model->update_salla($id, $update_data)) {
                $customer= $this->Seller_model->edit_view_customerdata($id);
                if($this->input->post('salla_active'))
                {
                  
                    $this->sallaWebhookSubscriptionDelete($customer) ;
                    $this->sallaWebhookSubscriptionCreate($customer) ;
                    
                }
                else
                {
                    $this->sallaWebhookSubscriptionDelete($customer) ; 
                }
                
                $this->session->set_flashdata('msg', $this->input->post('name') . '   has been updated successfully');
                redirect('Seller/updateSallaConfig/'.$id);
            }
        }

        $this->load->view('SellerM/seller_sallaconfig', $data);
    }



   

    /**
     * @param type $id
     * #description This method is used for zid webhook subscription
     */
    public function zidWebhookSubscribe($id) {

   ; 
        if (!empty($this->input->post('zid_webhook_subscribed'))) {

             $deliver_id=$this->input->post('zid_delivery_name'); 
            $customer = $this->Seller_model->edit_view_customerdata($id);
            if ($customer['manager_token'] !== "" && $customer['zid_active'] == 'Y') {

                if ($this->input->post('zid_webhook_subscribed') == 'Y') {
                    $this->zidWebhookSubscriptionCreate($customer,$deliver_id);
                } else {
                    $this->zidWebhookSubscriptionDelete($customer);
                }

                $update_data = array(
                    'zid_webhook_subscribed' => $this->input->post('zid_webhook_subscribed')
                );
                if ($this->Seller_model->update_zid($id, $update_data)) {
                    $this->session->set_flashdata('msg', $this->input->post('name') . '   has been updated successfully');
                    redirect('Seller/updateZidConfig/'.$id);
                }
            }
            redirect('Seller/updateZidConfig/'.$id);
        }
        
    }

    private function zidWebhookSubscriptionCreate($customer,$deliver_id) {

        /* check zid status and if status is new then order create other wise update webhook */
         $delivery_options = $this->Seller_model->deliverOptionsByid($deliver_id);
         // $delivery_options[0]['id']
        if ($customer['zid_status'] == 'new') {
            $event = "order.create";
            $condition = json_encode(array('status'=>'new','delivery_option_id'=>$delivery_options['delivery_id']));;
        } else {
            $event = "order.status.update";
            $condition = json_encode(array('status'=>'ready','delivery_option_id'=>$delivery_options['delivery_id']));
        }
      // echo  $subscribe = site_configTable('company_name'); die; 
        if ($customer['zid_active'] == 'Y') {
            $subscribe = site_configTable('company_name');
            $arr = array(
                "event" => $event,
                "target_url" => $this->config->item('zid_order_target_url') . '/' . $customer['uniqueid'],
                "original_id" => $customer['uniqueid'],
                "subscriber" =>  $subscribe,
                "conditions" => $condition
            );

            

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.zid.dev/app/v2/managers/webhooks",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $arr,
                CURLOPT_HTTPHEADER => array(
                    "Accept: en",
                    "Accept-Language: en",
                    "X-MANAGER-TOKEN: " . $customer['manager_token'],
                    "Authorization:Bearer " . site_configTable('zid_provider_token'),
                    "User-Agent: Fastcoo/1.00.00 (web)"
                ),
            ));

            $response = json_decode(curl_exec($curl));
            curl_close($curl);
            if ($response->status != "validation_error" || $response->status == "object") {
                $this->Seller_model->DeliveryOptionUpdate($deliver_id);
                return true;
            } else {
                return false;
            }
        }
    }

    private function zidWebhookSubscriptionDelete($customer) {
        $subscribe = site_configTable('company_name');
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zid.dev/app/v2/managers/webhooks?subscriber=".$subscribe."&original_id=" . $customer['uniqueid'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_HTTPHEADER => array(
                "Accept: en",
                "Accept-Language: en",
                "X-MANAGER-TOKEN: " . $customer['manager_token'],
                "Authorization:Bearer " . site_configTable('zid_provider_token'),
                "User-Agent: Fastcoo/1.00.00 (web)"
            ),
        ));

        $response = json_decode(curl_exec($curl));

        curl_close($curl);
        if ($response->status == "success") {
            return true;
        }
        return false;
    }
    public function getZidWebHooks() {
        $id = $this->input->post('cust_id');
        $customer = $this->Seller_model->edit_view_customerdata($id);
        $curl = curl_init();
         //echo site_configTable('zid_provider_token'); exit;
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zid.dev/app/v2/managers/webhooks",
           
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: en",
                "Accept-Language: en",
                "X-MANAGER-TOKEN: " . $customer['manager_token'],
                "Authorization:Bearer " . site_configTable('zid_provider_token'),
                "User-Agent: Fastcoo/1.00.00 (web)"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
        exit();
    }


    /**
     * @param type $id
     * #Description webhook subscription for salla
     */
    public function sallaWebhookSubscribe($id) {

        if ($this->input->post('salla_webhook_subscribed')) {
           
            $customer = $this->Seller_model->edit_view_customerdata($id);

            if ($customer['salla_athentication'] !== "" && $customer['salla_active'] == 'Y') {
               
                if ($this->input->post('salla_webhook_subscribed') == 'Y') {
                    $this->sallaWebhookSubscriptionCreate($customer);
                } else {
                    $this->sallaWebhookSubscriptionDelete($customer);
                }

                $update_data = array(
                    'salla_webhook_subscribed' => $this->input->post('salla_webhook_subscribed')
                );

                if ($this->Seller_model->update_salla($id, $update_data)) {
                    $this->session->set_flashdata('msg', $this->input->post('name') . '   has been updated successfully');
                    redirect('Seller/updateSallaConfig/'.$id);
                }
            }
            
            redirect('Seller/updateSallaConfig/'.$id);
        }
    }

    private function sallaWebhookSubscriptionCreate($customer) {


      
        if ($customer['salla_active'] == 'Y') {

          
            $event = "order.".$customer['salla_status'];
            $request = array(
                "name" => "Salla Update ".$customer['uniqueid'], 
                "event" =>  $event, 
                "url" => "https://api.diggipacks.com/API/sallaOrder/".$customer['uniqueid'], 
                "headers" => array(
                      array(
                         "key" => "X-EVENT-TYPE", 
                         "value" => "order.updated.diggipacks" 
                      )
                ) ,
             ); 
         //$this->config->item('salla_order_target_url') . '/' . $customer['uniqueid'],
          $request=json_encode($request); 
         $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.salla.dev/admin/v2/webhooks/subscribe",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $request,
                CURLOPT_HTTPHEADER => [
                    "Authorization: Bearer " . $customer['salla_athentication'],
                    "Accept-Language: AR",
                    "Content-Type: application/json"
                ],
            ]);

            

          $result = curl_exec($curl); 

            $response = json_decode(curl_exec($curl)); 
            
            curl_close($curl);
        }
    }

    private function sallaWebhookSubscriptionDelete($customer) {


        $request = array(
          
            "url" => "https://api.diggipacks.com/API/sallaOrder/".$customer['uniqueid'],
            
            
         ); 
     //$this->config->item('salla_order_target_url') . '/' . $customer['uniqueid'],
      $request=json_encode($request); 
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.salla.dev/admin/v2/webhooks/unsubscribe", 
            CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "DELETE",
                CURLOPT_POSTFIELDS => $request,
                CURLOPT_HTTPHEADER => [
                    "Authorization: Bearer " . $customer['salla_athentication'],
                    "Accept-Language: AR",
                    "Content-Type: application/json"
                
            ])
                );

        $response = json_decode(curl_exec($curl));

        curl_close($curl);
        if ($response->status == "success") {
            return true;
        }
        return false;
    }


    public function getsallaWebHooks() {
        $id = $this->input->post('cust_id');
        $customer = $this->Seller_model->edit_view_customerdata($id);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.salla.dev/admin/v2/webhooks",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . $customer['salla_athentication']
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }

        exit();
    }

    

    public function deleteDeliveryOption($cust_id,$id) {
       
        $this->Seller_model->deleteDeliveryOption($id);
    
   

    
    $this->session->set_flashdata('msg', 'Delivery Option Has been Deleted successfully');
    redirect('Seller/updateZidConfig/'.$cust_id);
    }
    public function zidDeliveryOptionAdd() {

        if ($this->input->post('deliver_option')) {
            $cust_id = $this->input->post('id');
            
            
            $rdata = array(         
                'name' => $this->input->post('zid_delivery_name'),
                'cost' => $this->input->post('zid_delivery_cost'),
                'cod_enabled' => $this->input->post('zid_cod_enabled'),
                'cod_fee' => $this->input->post('zid_cod_fee'),
                'cities' => $this->input->post('zid_city'),               
                'delivery_estimated_time_ar' => $this->input->post('delivery_estimated_time_ar'),
                'delivery_estimated_time_en' => $this->input->post('delivery_estimated_time_en'),
            );
         //  ECHO "<PRE>";print_r($rdata);die;
             $customer = $this->Seller_model->edit_view_customerdata($cust_id);
             $request = json_encode($rdata);
             
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.zid.dev/app/v2/managers/store/delivery-options/add",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $request,
                    CURLOPT_HTTPHEADER => array(
                        //"Accept: en",
                        "Accept-Language: en",
                        "X-MANAGER-TOKEN: " . $customer['manager_token'],
                        "Authorization:Bearer ".site_configTable('zid_provider_token'),
                        "Content-Type: application/json"
                    ),
                ));

                $response = curl_exec($curl); 
                $response = json_decode($response);
                if($response->status == "object"){
                    $deliver_id = $response->delivery_option->id;
                    $data = array(               
                        'cust_id' => $cust_id,
                        'zid_delivery_name' => $this->input->post('zid_delivery_name'),
                        'zid_delivery_cost' => $this->input->post('zid_delivery_cost'),
                        'zid_cod_enabled' => $this->input->post('zid_cod_enabled'),
                        'zid_cod_fee' => $this->input->post('zid_cod_fee'),
                        'zid_city' => implode(',',$this->input->post('zid_city')),               
                        'delivery_estimated_time_ar' => $this->input->post('delivery_estimated_time_ar'),
                        'delivery_estimated_time_en' => $this->input->post('delivery_estimated_time_en'),
                        'delivery_id' => $deliver_id
                    );
                    
                    $this->Seller_model->zidDeliveryOptionUpdate($data);
                }
               
            
                
                $this->session->set_flashdata('msg', 'Data been updated successfully');
                redirect('Seller');
        }
    }
    
    public function getZidDeliveryOptions($id)
    {
        $customer = $this->Seller_model->edit_view_customerdata($id);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zid.dev/app/v2/managers/store/delivery-options",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                //"Accept: en",
                "Accept-Language: en",
                "X-MANAGER-TOKEN: " . $customer['manager_token'],
                "Authorization:Bearer ".site_configTable('zid_provider_token'),
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $response = json_decode($response);
        echo "<pre>";print_r($response);
    }


    //Salla functions starts 

    public function SallaProducts($id) 
    {
    
         $store_link = "https://api.salla.dev/admin/v2/products";
         $customer = $this->Seller_model->edit_view_customerdata($id);
    
        $curl = curl_init();
        curl_setopt_array($curl, [
          CURLOPT_URL => $store_link,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => [
            "authorization: Bearer ".$customer['salla_athentication']
          ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);        
        curl_close($curl);
    
        $SallaProductArr_total = json_decode($response, true);
    
        // echo "<pre>"; print_r( $SallaProductArr_total);   die; 
    
            if (!empty($SallaProductArr_total['data']) && ($SallaProductArr_total['success'] == 1)) 
            {
                $sallaarray = array();
                foreach ($SallaProductArr_total['data'] as $key => $products) 
                {
                    if(!empty($products['sku']))
                    {
                        //echo "<pre>"; print_r($products); 
                        $sallaarray[] =  array(
                            'sku' => $products['sku'],
                            'name' => $products['name'] ,
                            'id' => $products['id']
                            );
                           
                    }
                   
                }
            }
            $SallaProducts['products'] = $sallaarray;
       
             //echo "<pre>"; print_r( $SallaProducts);   die; 
    
       $this->load->view('SellerM/view_sallaproducts', $SallaProducts);
        //$this->load->view('SellerM/view_sallaproducts');
    }
    
    public function SaveSallaProducts() {
                  
        foreach ($this->input->post('selsku') as  $value) {
          
            $skuarray = array();
            $skuarray = array(
                'sku' => $this->input->post('sku')[$value],
                'salla_pid' => $this->input->post('pid')[$value],
                'name' => $this->input->post('skuname')[$value],
                'super_id' => $this->session->userdata('user_details')['super_id'],
                'description' => $this->input->post('sku')[$value],
                'type' => 'B2C',
                'storage_id' => $this->input->post('storageid'),
                'wh_id' => $this->input->post('warehouseid'),
                'sku_size' => $this->input->post('sku_size'),
                'entry_date' => date("Y-m-d H:i:s")
            );
         
           $exist_zidsku_id = exist_zidsku_id($this->input->post('sku')[$value], $this->session->userdata('user_details')['super_id']);
            if ($exist_zidsku_id != '' || $exist_zidsku_id != 0) {
                echo $product['sku'] . ' Exist<br>';
            } else {
                AddSKUfromZid($skuarray);
            }
        }
        $this->session->set_flashdata('msg', "Selected Sku has been Added Successfully");
        redirect('Item');
    }
    
     public function updateShopify($id) {
        
        if ($this->input->post('updateshopify')) {
            $update_data = array(
                'shopify_url' => $this->input->post('shopify_url'),
                'shopify_tag' => $this->input->post('shopify_tag'),
                'location_id' => $this->input->post('location_id'),
                'is_shopify_active' => $this->input->post('is_shopify_active'),
                'shopify_fulfill' => $this->input->post('shopify_fulfill'),
            );
            
            if ($this->Seller_model->update_shopify($id, $update_data)) {                
                $this->session->set_flashdata('msg', $this->input->post('name') . '   has been updated successfully');
                redirect('Seller');
            }
        }
        
        $data['customer'] = $this->Seller_model->edit_view_customerdata($id);
        $data['seller'] = $this->Seller_model->edit_view($id);        
        
        $this->load->view('SellerM/shopify_config', $data);
    }
    
    public function updateWoocommerce($id) {             
        
        if ($this->input->post('updatewoocommerce')) {
            $update_data = array(
                'wc_consumer_key' => $this->input->post('consumer_key'),
                'wc_secreat_key' => $this->input->post('consumer_secreat_key'),
                'wc_store_url' => $this->input->post('consumer_store_url'),
                'wc_active' => ($this->input->post('consumer_active')) ? $this->input->post('consumer_active') : 0,
            );
            if ($this->Seller_model->update_Woocommerce($id, $update_data)) {                
                $this->session->set_flashdata('msg', $this->input->post('name') . '   has been updated successfully');
                redirect('Seller');
            }
        }
        
        $data['customer'] = $this->Seller_model->edit_view_customerdata($id);
        $data['seller'] = $this->Seller_model->edit_view($id);
        
        $this->load->view('SellerM/woocommerce_config', $data);
    }
    

}

?>