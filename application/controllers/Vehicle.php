<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicle extends MY_Controller {

    function __construct() {
        parent::__construct();

        if (menuIdExitsInPrivilageArray(3) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }

       
        $this->load->model('Vehicle_model');
        $this->load->model('ItemInventory_model');
      
    }

    public function index() {

        $this->load->view('vehicle/view_list', $data);
    }

    public function add_view($id=null) {



        if (($this->session->userdata('user_details') != '')) {


            $data["Edit_data"] = $this->Vehicle_model->edit_view($id);
            //print_r($data["StorageArray"]);
            $this->load->view('vehicle/add_form', $data);
        } else {
            redirect(base_url() . 'Login');
        }
    }
    
    
    public function deleteVehicle($id=null)
    {
        if(!empty($id))
        {
            
            $updateArry=array('deleted'=>'Y');
            ///$updateArry_w=array('id'=>$id);
            $this->Vehicle_model->add_update($updateArry,$id);
            $this->session->set_flashdata('msg','has been deleted');
          //  die;
          
        }
        else
        {$this->session->set_flashdata('msg','try again');
            
        }
        
         redirect('vehicle_list');   
                
           
        
    }

    public function add() {


        
         $edit_id=$this->input->post('edit_id');
        $this->load->library('form_validation');
       
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
      


        if ($this->form_validation->run() == FALSE) {
            $this->add_view();
        } else {

             //print_r($_FILES);

            if (!empty($_FILES['icon_path']['name'])) {

                $config['upload_path'] = 'assets/vehicle_icon/';
                $config['overwrite'] = TRUE;
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['file_name'] = $_FILES['icon_path']['name'];
                $config['file_name'] = time();
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('icon_path')) {

                    $uploadData = $this->upload->data();

                    $small_img = $config['upload_path'] . '' . $uploadData['file_name'];

                    $uploadedImage = $uploadData['file_name'];
                    $source_path = $config['upload_path'] . $uploadedImage;
                    $thumb_path = $config['upload_path'];
                    $thumb_width = 50;
                    $thumb_height = 50;

                    // Image resize config 
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = $source_path;
                    $config['new_image'] = $thumb_path;
                    $config['maintain_ratio'] = FALSE;
                    $config['width'] = $thumb_width;
                    $config['height'] = $thumb_height;

                    // Load and initialize image_lib library 
                    $this->load->library('image_lib', $config);
                    $this->image_lib->resize();
                } else {

                    $small_img =$this->input->post('old_icon_path');
                }
            } else {
                $small_img = $this->input->post('old_icon_path');
            }
            //  echo $small_img;  
            // die;
            //$errors= $this->upload->display_errors();





            $data2 = array(
                
                'name' => $this->input->post('name'),
                'icon_path' => $small_img,
                'super_id' => $this->session->userdata('user_details')['super_id'],
                    //'item_subcategory'=>$sub_category,
                    //'attributes_values'=>$attributes_values,
            );

            
          //  print_r($data2); die;
            if(!empty($edit_id))
            {
            $item_id = $this->Vehicle_model->add_update($data2,$edit_id);
             $this->session->set_flashdata('msg', $this->input->post('name') . '   has been Updated successfully');
            
            }
            else
            {
                $item_id = $this->Vehicle_model->add($data2); 
                 $this->session->set_flashdata('msg', $this->input->post('name') . '   has been added successfully');
                
            }
           
            redirect('vehicle_list');
        }
    }

    public function edit_view($id) {

        $data = $this->Vehicle_model->edit_view($id);
        //print_r($data);
        $data["StorageArray"] = $this->Vehicle_model->GetAllStorageTypes();
        $this->load->view('ItemM/item_detail', $data);
    }

    public function edit($item_id) {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('wh_id', 'Warehouse', 'trim|required');
        $this->form_validation->set_rules('storage_id', 'Select Storage Type', 'trim|required');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules("sku", 'SKU', 'trim|required');
        $this->form_validation->set_rules("sku_size", 'Capacity', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->edit_view($item_id);
        } else {

            if (!empty($_FILES['item_path']['name'])) {

                $config['upload_path'] = 'assets/item_uploads/';
                $config['overwrite'] = TRUE;
                $config['allowed_types'] = 'jpg|jpeg|png';
                $config['file_name'] = $_FILES['item_path']['name'];
                $config['file_name'] = time();
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('item_path')) {

                    $uploadData = $this->upload->data();

                    $small_img = $config['upload_path'] . '' . $uploadData['file_name'];

                    $uploadedImage = $uploadData['file_name'];
                    $source_path = $config['upload_path'] . $uploadedImage;
                    $thumb_path = $config['upload_path'];
                    $thumb_width = 120;
                    $thumb_height = 120;

                    // Image resize config 
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = $source_path;
                    $config['new_image'] = $thumb_path;
                    $config['maintain_ratio'] = FALSE;
                    $config['width'] = $thumb_width;
                    $config['height'] = $thumb_height;

                    // Load and initialize image_lib library 
                    $this->load->library('image_lib', $config);
                    $this->image_lib->resize();
                } else {

                    $small_img = $this->input->post('old_item_path');
                }
            } else {
                $small_img = $this->input->post('old_item_path');
            }
            $data = array(
                'storage_id' => $this->input->post('storage_id'),
                'name' => $this->input->post('name'),
                'expire_block' => $this->input->post('expire_block'),
                'sku' => $this->input->post('sku'),
                'sku_size' => $this->input->post('sku_size'),
                'description' => $this->input->post('description'),
                'less_qty' => $this->input->post('less_qty'),
                'alert_day' => $this->input->post('alert_day'),
                'color' => $this->input->post('color'),
                'length' => $this->input->post('length'),
                'width' => $this->input->post('width'),
                'height' => $this->input->post('height'),
                'item_path' => $small_img,
                'wh_id' => $this->input->post('wh_id')
            );

            $this->Vehicle_model->edit($item_id, $data);

            $this->Vehicle_model->edit($item_id, $data);


            $this->session->set_flashdata('msg', 'id#' . $item_id . ' has been updated successfully');
            redirect('Item');
        }

        // }
    }

    public function filter() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $result = $this->Vehicle_model->filter($_POST);

        $newArray = $result['result'];
        foreach ($newArray as $key => $val) {
            $newArray[$key]['storage_type'] = Getallstoragetablefield($val['storage_id'], 'storage_type');
        }
        $dataArray['result'] = $newArray;
        $dataArray['count'] = $result['count'];
        echo json_encode($dataArray);
    }

}

?>
