<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Warehouse extends MY_Controller {

    function __construct() {
        parent::__construct();
        //error_reporting(0);
        
        if (menuIdExitsInPrivilageArray(77) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        //$this->load->library('pagination');
        $this->load->model('Warehouse_model');
        $this->load->model('Ccompany_model');

        $this->load->library('form_validation');
    }

    public function list_view() {
        $data['sellers'] = $this->Warehouse_model->all();

        $this->load->view('Warehouse/list_view', $data);
    }

    public function add_view() {

        
        if (($this->session->userdata('user_details') != '')) {
            $data['customers'] = $this->Warehouse_model->Zone();
            $data['city_drp'] = $this->Warehouse_model->fetch_all_cities();
            //$data['company'] = $this->Ccompany_model->find1();

            $this->load->view('Warehouse/add_view', $data);
        } else {
            redirect(base_url() . 'Login');
        }
    }

    public function add() {


        $this->form_validation->set_rules("city_id[]", 'City', 'trim|required');
        if ($this->form_validation->run() == FALSE) {

            $this->add_view();
        } else {
            //echo "sssss"; die;     
            // print_r($_POST); die;
            $unique_acc_mp = uniqid();
            $data = array(
                'name' => $this->input->post('name'),
                'city_id' => json_encode($this->input->post('city_id')),
                'super_id' => $this->session->userdata('user_details')['super_id'],
            );
            if (empty($errors)) {
                if ($this->Warehouse_model->add_warehouse($data))


                //echo  $customer_id.'//'. $seller_id;     exit();  
                    $this->session->set_flashdata('msg', $this->input->post('name') . '   has been added successfully');
                else {
                    $this->session->set_flashdata('msg', $this->input->post('name') . '   Customer adding is failed');
                }
            } else {
                $this->session->set_flashdata('msg', $this->input->post('name') . '   Customer adding is failed');
            }

//die;

            redirect('viewWarehouse');
        }
    }

    public function edit_msg_template($id) {

        $data['editdata'] = $this->Warehouse_model->edit_msg_temp($id);
        $data['city_drp'] = $this->Warehouse_model->fetch_all_cities();
        $data['editid'] = $id;
        $this->load->view('Warehouse/add_view', $data);
    }

    public function edit_view($id) {
        // $id = $this->input->get('id');
        $data['seller'] = $this->Warehouse_model->edit_view($id);
        $data['city_drp'] = $this->Warehouse_model->fetch_all_cities();
        $data['customer'] = $this->Warehouse_model->edit_view_customerdata($id);

        $this->load->view('Warehouse/edit_view', $data);
    }
    public function setup_storage($id=null) {
       
        
         $data['id'] = $id;
        $data['wareArr'] = $this->Warehouse_model->edit_view_customerdata($id);
       $data['storageArr'] = $this->Warehouse_model->fetch_all_storage($id);
        $this->load->view('Warehouse/setup_storage', $data);
    }
    
    public function storage_processs($wh_id=0)
    {
        if($wh_id>0)
        {
            $capacityArr=$this->input->post('capacity');
           // print_r($capacityArr); 
            $entrydate=date("Y-m-d H:i:s");
            foreach($capacityArr as $key=>$val)
            {
              $s_id=$key;
              $size=$val;
              
              $newEntryArr[]=array(
                  'wh_id'=>$wh_id,
                  'storage_id'=>$s_id,
                  'size'=>$size,
                   'entrydate'=>$entrydate,
                  'super_id'=>$this->session->userdata('user_details')['super_id'],
              );
            }
            
          // echo '<pre>';print_r($newEntryArr); die;
           if(!empty($newEntryArr))
           {
               $this->Warehouse_model->insertstorageType($newEntryArr,$wh_id);
               $this->session->set_flashdata('msg','updated successfully');
           }
           
             
        redirect('viewWarehouse');
            
        }
    }
    
    
    

    public function edit($id=null) {

        $this->form_validation->set_rules("city_id[]", 'City', 'trim|required');
        $id = $this->input->post('id');
        $customer_info = array(
            'name' => $this->input->post('name'),
            'city_id' => json_encode($this->input->post('city_id'))
        );


        $this->Warehouse_model->edit_custimer($id, $customer_info);
        $this->session->set_flashdata('msg', $this->input->post('name') . '   has been updated successfully');
        redirect('viewWarehouse');
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
    
    public function warehouse_stprage_report()
    {
         if (menuIdExitsInPrivilageArray(147) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        } 
        $data['warehouseArr']= $this->Warehouse_model->all();
        $this->load->view('Warehouse/storage_graph_report', $data);
    }

}

?>