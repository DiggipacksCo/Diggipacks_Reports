<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Zone extends MY_Controller {

    function __construct() {

        parent::__construct();
        if (menuIdExitsInPrivilageArray(23) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        //$this->load->library('pagination');
        $this->load->model('Zone_model');
        $this->load->model('Ccompany_model');

        $this->load->library('form_validation');
    }

    public function list_view() {
        $data['sellers'] = $this->Zone_model->all();

        $this->load->view('Zone/list_view', $data);
    } 

     public function add_view($id = null) {

        if (($this->session->userdata('user_details') != '')) {

            $data['EditData'] = $this->Zone_model->find_customer_sellerm($id);
            $data['id'] = $id;
            $data['customers'] = $this->Zone_model->Zone();
            $data['city_drp'] = $this->Zone_model->fetch_all_cities();
            $data['company'] = $this->Ccompany_model->all();

            $this->load->view('Zone/add_view', $data);
        } else {
            redirect(base_url() . 'Login');
        }
    }

    public function editZoneUpdate($id = null) {
        if ($id > 0) {
            $data = array(
                'name' => $this->input->post('name'),
                'capacity' => $this->input->post('capacity'),
                'cc_id' => $this->input->post('c_id'),
                'city_id' => json_encode($this->input->post('city_id')),
                'price' => $this->input->post('price'),
            );
            $this->Zone_model->UpdateZoneCompanyLIst($data, $id);
            $this->session->set_flashdata('msg', $this->input->post('name') . '   has been updated successfully');
        } else
            $this->session->set_flashdata('msg', 'try again');
        redirect('viewZone');
    }




    


    public function add() {

        // print_r($this->input->post('dd_customer'));
        // print_r($this->input->post('warehousing_charge'));
        // print_r($this->input->post('fulfillment_charge'));
        // exit();
        // $customer_id=$this->input->post('dd_customer');


        $this->form_validation->set_rules("capacity", 'capacity', 'trim|required');
        $this->form_validation->set_rules("c_id", 'Seller', 'trim|required');
        $this->form_validation->set_rules("city_id[]", 'City', 'trim|required');
//		  $this->form_validation->set_rules("password", 'Password ', 'trim|required|min_length[6]');
//		  $this->form_validation->set_rules('conf_password', 'Confirm Password', 'required|matches[password]'); 
        if ($this->form_validation->run() == FALSE) {

            $this->add_view();
        } else {
            //echo "sssss"; die;
            // print_r($_POST); die;
            $unique_acc_mp = uniqid();
            $data = array(
                'name' => $this->input->post('name'),
                'capacity' => $this->input->post('capacity'),
                'cc_id' => $this->input->post('c_id'),
                'super_id' => $this->session->userdata('user_details')['super_id'],
                'city_id' => json_encode($this->input->post('city_id')),
                'price' => $this->input->post('price'),
            );
            if (empty($errors)) {
                if ($this->Zone_model->add_company($data))


                //echo  $customer_id.'//'. $seller_id;     exit();  
                    $this->session->set_flashdata('msg', $this->input->post('name') . '   has been added successfully');
                else {
                    $this->session->set_flashdata('msg', $this->input->post('name') . '    adding is failed');
                }
            } else {
                $this->session->set_flashdata('msg', $this->input->post('name') . '    adding is failed');
            }

//die;

            redirect('viewZone');
        }
    }

    public function edit_view($id) {
        // $id = $this->input->get('id');
        $data['seller'] = $this->Zone_model->edit_view($id);
        $data['city_drp'] = $this->Zone_model->fetch_all_cities();
        $data['customer'] = $this->Zone_model->edit_view_customerdata($id);

        $this->load->view('Zone/edit_view', $data);
    }

    public function edit($id) {
        //$id=$this->input->post('id');
        if (!empty($_FILES['upload_cr']['name'])) {
            $config['upload_path'] = 'assets/sellerupload/';
            $config['overwrite'] = TRUE;
            $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
            $config['file_name'] = $_FILES['upload_cr']['name'];
            $config['file_name'] = time() . 'cr';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('upload_cr')) {
                $uploadData = $this->upload->data();
                $path_upload_cr = $config['upload_path'] . '' . $uploadData['file_name'];
            }
        } else
            $path_upload_cr = $this->input->post('upload_cr_old');
        if (!empty($_FILES['upload_id']['name'])) {
            $config['upload_path'] = 'assets/sellerupload/';
            $config['overwrite'] = TRUE;
            $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
            $config['file_name'] = $_FILES['upload_id']['name'];
            $config['file_name'] = time() . 'upid';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('upload_id')) {
                $uploadData = $this->upload->data();
                $path_upload_id = $config['upload_path'] . '' . $uploadData['file_name'];
            }
        } else
            $path_upload_id = $this->input->post('upload_id_old');


        if (!empty($_FILES['upload_contact']['name'])) {
            $config['upload_path'] = 'assets/sellerupload/';
            $config['overwrite'] = TRUE;
            $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';
            $config['file_name'] = $_FILES['upload_contact']['name'];
            $config['file_name'] = time() . 'ctc';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('upload_contact')) {
                $uploadData = $this->upload->data();
                $path_upload_contact = $config['upload_path'] . '' . $uploadData['file_name'];
            }
        } else
            $path_upload_contact = $this->input->post('upload_contact_old');


        //echo $path_upload_contact; die;


        $customer_info = array(
            'phone' => $this->input->post('phone1'),
            'phone' => $this->input->post('phone2'),
            'company' => $this->input->post('company'),
            'entrydate' => $this->input->post('entrydate'),
            'vat_no' => $this->input->post('vat_no'),
            'upload_cr' => $path_upload_cr,
            'upload_id' => $path_upload_id,
            'upload_contact' => $path_upload_contact,
            'address' => $this->input->post('address'),
            'city_id' => $this->input->post('city_drop'),
            'store_link' => $this->input->post('store_link'),
        );



        $this->Zone_model->edit_custimer($id, $customer_info);
        $this->session->set_flashdata('msg', $this->input->post('name') . '   has been updated successfully');
        redirect('viewZone');
    }

    
    public function report_view($id = null) {



        $data['status'] = $this->Shipment_model->allstatus();
        $data['total_inventory_items'] = $this->ItemInventory_model->count_find($id);
        $data['seller_info'] = $this->Seller_model->find($id);
        $data['customer_info'] = $this->Seller_model->find_customer($id);




        // print_r($data['seller_info']);
        // exit();
        $data['seller_shipments'] = $this->Shipment_model->find_by_seller($data['seller_info']->customer);

        if ($data['seller_shipments'] != Null) {
            // 	print('<pre>');
            // print_r($data['seller_shipments']);
            // 	print('</pre>');
            // exit();
            // for($i=0;$i<count($data['seller_shipments']);$i++)
            // {
            $array = array(
                'seller_id' => $id,
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