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


        $first_out = $this->input->post('first_out');
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

        //echo "<pre>";print_r($this->input->post());exit();
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
        $store_link = "https://api.zid.sa/v1/products/";
        $bearer = $this->config->item('zid_authorization');
        $ZidProductRT = ZidPcURL($storeID, $store_link, $bearer);

        $ZidProductArr_total = json_decode($ZidProductRT, true);

        $total_pages = 1;
        if ($ZidProductArr_total['count'] > 10) {
            $total_pages = ceil($ZidProductArr_total['count'] / 10);
        }

        $results = array();
        $results2 = array();
        $p = 0;
        $s = 0;
        for ($i = 1; $i <= $total_pages; $i++) {
            $storlink_page = "https://api.zid.sa/v1/products/?page=" . $i;
            $ZidProductArr = ZidPcURL($storeID, $storlink_page, $bearer);
            $ZidProductArr = json_decode($ZidProductArr, true);

            if (isset($ZidProductArr['results'])) {
                foreach ($ZidProductArr['results'] as $key => $products) {

                    if (isset($products['structure']) && $products['structure'] == 'parent') {
                        $product_link = $store_link . $products['id'];

                        $product = json_decode(ZidPcURL($storeID, $product_link, $bearer), true);

                        if (count($product['variants']) > 0) {
                            foreach ($product['variants'] as $variant) {

                                $results[] = $variant;
                            }
                        } else {
                            $results[] = $product;
                        }
                    } else {

                        $results2[] = $products;
                    }
                }
            }
        }

        $final_Arr = array_merge($results, $results2);

        $ZidProducts['products'] = $final_Arr;

        $this->load->view('SellerM/view_zidp', $ZidProducts);
    }

    public function SaveZidProducts() {
        $skuarray = array();

        foreach ($this->input->post('selsku') as $key => $value) {
            $skuarray = array(
                'sku' => $this->input->post('sku')[$key],
                'zid_pid' => $this->input->post('pid')[$key],
                'name' => $this->input->post('skuname')[$key],
                'super_id' => $this->session->userdata('user_details')['super_id'],
                'description' => $this->input->post('sku')[$key],
                'type' => 'B2C',
                'storage_id' => $this->input->post('storageid')[$key],
                'wh_id' => $this->input->post('warehouseid')[$key],
                'sku_size' => $this->input->post('sku_size')[$key],
                'entry_date' => date("Y-m-d H:i:s")
            );

            $exist_zidsku_id = exist_zidsku_id($this->input->post('sku')[$key], $this->session->userdata('user_details')['super_id']);
            if ($exist_zidsku_id != '' || $exist_zidsku_id != 0) {
                echo $product['sku'] . ' Exist<br>';
            } else {
                AddSKUfromZid($skuarray);
            }
        }

        redirect('Item');
    }

    public function zidconfig($id) {
        $this->load->view('SellerM/seller_zid', $data);
    }

    public function updateZidConfig($id) {


        $data['customer'] = $this->Seller_model->edit_view_customerdata($id);
        $data['seller'] = $this->Seller_model->edit_view($id);
        $ListArr = $this->Seller_model->zidCities();        
        $data['delivery_options'] = $this->Seller_model->deliverOptionExist($id);
        $listcity =   explode(',', $data['delivery_options'][0]['zid_city']); ; 
         $pre = array();
         $keys = array();        
            foreach ($listcity as $citie)
            {
                    $key = array_search($citie, array_column( $ListArr, 'id'));
                    if(!empty($key))
                    {
                        array_push($pre, $ListArr[$key]);  // cities add in next array
                        array_push($keys, $key); // selected cities remove from first list box
                    
                    }
            }
            $data['pre']= $pre;  
            foreach ($keys as $removecity)
            {
                unset($ListArr[$removecity]);    // remove selected cities from left side panel 
            }
            $data['ListArr'] = $ListArr;
           // $ListArr = $ListArr[$removecity];
         


        if ($this->input->post('updatezid'))
        {

            $update_data = array(
                'manager_token' => $this->input->post('manager_token'),
                'zid_sid' => $this->input->post('zid_sid'),
                'zid_status' => $this->input->post('zid_status'),
                'zid_active' => $this->input->post('zid_active'),
            );


            $user = $this->Seller_model->update_zid($id, $update_data);

            if ($user > 0) {
                $this->session->set_flashdata('msg', $this->input->post('name') . '   has been updated successfully');
                redirect('Seller');
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
            );

            if ($this->Seller_model->update_salla($id, $update_data)) {
                $this->session->set_flashdata('msg', $this->input->post('name') . '   has been updated successfully');
                redirect('Seller');
            }
        }

        $this->load->view('SellerM/seller_sallaconfig', $data);
    }

    /**
     * @param type $id
     * #description This method is used for zid webhook subscription
     */
    public function zidWebhookSubscribe($id) {
        if ($this->input->post('zid_webhook_subscribed')) {

            $customer = $this->Seller_model->edit_view_customerdata($id);
            if ($customer['manager_token'] !== "" && $customer['zid_active'] == 'Y') {

                if ($this->input->post('zid_webhook_subscribed') == 'Y') {
                    $this->zidWebhookSubscriptionCreate($customer);
                } else {
                    $this->zidWebhookSubscriptionDelete($customer);
                }

                $update_data = array(
                    'zid_webhook_subscribed' => $this->input->post('zid_webhook_subscribed')
                );
                if ($this->Seller_model->update_zid($id, $update_data)) {
                    $this->session->set_flashdata('msg', $this->input->post('name') . '   has been updated successfully');
                    redirect('Seller');
                }
            }
            redirect('Seller');
        }
    }

    private function zidWebhookSubscriptionCreate($customer) {

        /* check zid status and if status is new then order create other wise update webhook */
         $delivery_options = $this->Seller_model->deliverOptionExist($customer['id']);
         // $delivery_options[0]['id']
        if ($customer['zid_status'] == 'new') {
            $event = "order.create";
            $condition = json_encode(array('status'=>'new','delivery_option_id'=>$delivery_options[0]['delivery_id']));;
        } else {
            $event = "order.status.update";
            $condition = json_encode(array('status'=>'ready','delivery_option_id'=>$delivery_options[0]['delivery_id']));
        }
        
        if ($customer['zid_active'] == 'Y') {

            $arr = array(
                "event" => $event,
                "target_url" => $this->config->item('zid_order_target_url') . '/' . $customer['uniqueid'],
                "original_id" => $customer['uniqueid'],
                "subscriber" => "Fastcoo",
                "conditions" => $condition
            );

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.zid.sa/v1/managers/webhooks",
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
                    "Authorization:Bearer " . $this->config->item('zid_authorization'),
                    "User-Agent: Fastcoo/1.00.00 (web)"
                ),
            ));

            $response = json_decode(curl_exec($curl));
            curl_close($curl);
            if ($response->status != "validation_error" || $response->status == "object") {
                return true;
            } else {
                return false;
            }
        }
    }

    private function zidWebhookSubscriptionDelete($customer) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zid.sa/v1/managers/webhooks?subscriber=Fastcoo&original_id=" . $customer['uniqueid'],
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
                "Authorization:Bearer " . $this->config->item('zid_authorization'),
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

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zid.sa/v1/managers/webhooks",
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
                "Authorization:Bearer " . $this->config->item('zid_authorization'),
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
            ini_set('display_errors', '1');
            ini_set('display_startup_errors', '1');
            error_reporting(E_ALL);
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
                    redirect('Seller');
                }
            }
            die;
            redirect('Seller');
        }
    }

    private function sallaWebhookSubscriptionCreate($customer) {

        /* check zid status and if status is new then order create other wise update webhook */
//        if ($customer['salla_status'] == 'new') {
//            $event = "order.create";
//            $condition = null;
//        }
//        } else {
//            $event = "order.update";
//            $condition = "json_encode(array('status'=>'ready'))";
//        }
        $event = "order.create";
        if ($customer['salla_active'] == 'Y') {


            $request = array(
                'name' => 'Salla Update Customer Event',
                'event' => 'order.created',
                'url' => $this->config->item('salla_order_target_url') . '/' . $customer['uniqueid'],
            );

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
                    "Authorization: Bearer " . $customer['salla_authorization']
                ],
            ]);

            $result = curl_exec($curl);

            $response = json_decode(curl_exec($curl));
            curl_close($curl);
        }
    }

    private function sallaWebhookSubscriptionDelete($customer) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zid.sa/v1/managers/webhooks?subscriber=Fastcoo&original_id=" . $customer['uniqueid'],
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
                "Authorization:Bearer " . $this->config->item('zid_authorization'),
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
                    CURLOPT_URL => "https://api.zid.dev/app/v1/managers/store/delivery-options/add",
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
                        "Authorization:Bearer eyJ4NXQiOiJNell4TW1Ga09HWXdNV0kwWldObU5EY3hOR1l3WW1NNFpUQTNNV0kyTkRBelpHUXpOR00wWkdSbE5qSmtPREZrWkRSaU9URmtNV0ZoTXpVMlpHVmxOZyIsImtpZCI6Ik16WXhNbUZrT0dZd01XSTBaV05tTkRjeE5HWXdZbU00WlRBM01XSTJOREF6WkdRek5HTTBaR1JsTmpKa09ERmtaRFJpT1RGa01XRmhNelUyWkdWbE5nX1JTMjU2IiwiYWxnIjoiUlMyNTYifQ.eyJzdWIiOiJoYXJpQGZhc3Rjb28uY29tQGNhcmJvbi5zdXBlciIsImF1dCI6IkFQUExJQ0FUSU9OIiwiYXVkIjoiN0ZlcjRpZGthZkNpaDV6bnVYR3g0Tk9mRXZ3YSIsIm5iZiI6MTYxOTAxMzc0NiwiYXpwIjoiN0ZlcjRpZGthZkNpaDV6bnVYR3g0Tk9mRXZ3YSIsInNjb3BlIjoiU2hpcHBpbmctUGFydG5lcnMiLCJpc3MiOiJodHRwczpcL1wvcG9ydGFsLnppZC5kZXY6NDQzXC9vYXV0aDJcL3Rva2VuIiwiZXhwIjoxNjUwNTcwNjcyLCJpYXQiOjE2MTkwMTM3NDYsImp0aSI6ImFjMzNmNWMyLTE1MDItNGUwYi05MWI1LTBiNmIxZTllMTBhNCJ9.crd3muE0AU1lOxSdfi0LAzd6vTw_ae6FCilR-X44nt3Uzp_-aR6pR3N0GV8A8AI9PLOu0RnRUjWXr2nS8fHMgijRNd910z2nowlqJ1g_xLb3wtLj7vwpXurvHP6Hy9-wG8vHUKNXy2QAU7ei-ToQsUMW-2CGlyzjFR64p8yQmFc5DzK6GmEO4JQ_tbbciz7BAmjdyzM8vyV01AqRiyLxN3yS_imTLAVqZpm8yAjYrcM3EdE9sS1W9JpQcjGovriLKFl3Z6-u0kb9SFDI9jP-wmVmSJ1lfEBgPCrzPGXWa5GQuCoIG7CMZBP0WSlL6Zu_v8Pq5lnTXHQegWmxLZ5x8A",
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
    
    public function getZidDeliveryOptions($id){
        $customer = $this->Seller_model->edit_view_customerdata($id);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zid.dev/app/v1/managers/store/delivery-options",
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
                "Authorization:Bearer " . $this->config->item('zid_authorization'),
                
            ),
        ));

        $response = curl_exec($curl);
    }

}

?>