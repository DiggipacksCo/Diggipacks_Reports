<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Storage extends MY_Controller {

    function __construct() {
        parent::__construct();

        if (menuIdExitsInPrivilageArray(20) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        $this->load->model('Storage_model');

        $this->load->helper('utility');
        // $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function storageview() {

        $this->load->view('storage/viewlist');
    }

    public function setStorageRate() {
        $view['sellerData'] = Getallsellerdata();
        $this->load->view('storage/setusertype', $view);
    }

    public function getallsellerdata() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $returndata = Getallsellerdata();
        echo json_encode($returndata);
    }

    public function getallsoragetypes() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $seller_id = $_POST['seller_id'];
        $returndata = $this->Storage_model->getalltypesetrate($seller_id);
        $typearray = $returndata;
        $ii = 0;
        foreach ($typearray as $rdata) {
            $typearray[$ii]['rates'] = getalluserstoragerates($seller_id, $rdata['id'], 'rate');
            $typearray[$ii]['rateid'] = getalluserstoragerates($seller_id, $rdata['id'], 'id');
            $typearray[$ii]['seller_id'] = $seller_id;
            $ii++;
        }
        echo json_encode($typearray);
    }

    public function add_storage($id = null) {

        $view['editid'] = $id;
        // $view['editdata']=$this->Storage_model->editviewquery($id); 
        $this->load->view('storage/add_storage', $view);
    }

   
    public function geteditviewdata() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $id = $_POST['id'];
        $returndata = $this->Storage_model->editviewquery($id);
        echo json_encode($returndata);
    }

    public function geteditStoragedata() {
        $_POST = json_decode(file_get_contents('php://input'), true);
       
         $id = $_POST['id'];
        $returndata = $this->Storage_model->editviewchargesquery($id);
       // print_r($returndata); die;
        echo json_encode($returndata);
    }

    public function getUpdateRateSetData() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $dataArray = $_POST;


        $result = $this->Storage_model->getalldataupdatequery($dataArray);
        //$returndata=$this->Storage_model->editviewquery($id); 
        echo json_encode($result);
    }
     public function add_storagecharges($id = null) {
               $view['editid'] = $id;
        // $view['editdata']=$this->Storage_model->editviewquery($id); 
        $this->load->view('SellerM/add_storagecharges', $view);
    }

    public function addstoragecharges(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('storage_type', 'Storage Type', 'trim|required');
        $this->form_validation->set_rules("rate", 'Rate ', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
           // echo "jhjhjh"; 
            //$this->add_storage();
        } else {

            $editid = $this->input->post('editid');
            $storage_type = $this->input->post('storage_type');
            $rate = $this->input->post('rate');
            $data = array('storage_type' => $storage_type,'super_id' => $this->session->userdata('user_details')['super_id'], 'rate' => $rate,);
            // print_r($data); die;
            $res = $this->Storage_model->datainsertdefault($data, $editid);
            if ($res == 1) {
                $this->session->set_flashdata('succmsg', 'has been added successfully');
                redirect(base_url() . 'Seller');
            } else if ($res == 2) {
                $this->session->set_flashdata('succmsg', 'has been updated successfully');
                redirect(base_url() . 'Seller');
            } else {
                $this->session->set_flashdata('errormess', 'try again');
                redirect(base_url() . 'Seller');
            }
        }
    }

    public function addstorage() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('storage_type', 'Storage Type', 'trim|required');
        $this->form_validation->set_rules("rate", 'Rate ', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->add_storage();
        } else {

            $editid = $this->input->post('editid');
            $storage_type = $this->input->post('storage_type');
            $rate = $this->input->post('rate');
            $data = array('storage_type' => $storage_type,'super_id' => $this->session->userdata('user_details')['super_id'], 'rate' => $rate,);
            // print_r($data); die;
            $res = $this->Storage_model->datainsert($data, $editid);
            if ($res == 1) {
                $this->session->set_flashdata('succmsg', 'has been added successfully');
                redirect(base_url() . 'view_storage');
            } else if ($res == 2) {
                $this->session->set_flashdata('succmsg', 'has been updated successfully');
                redirect(base_url() . 'view_storage');
            } else {
                $this->session->set_flashdata('errormess', 'try again');
                redirect(base_url() . 'view_storage');
            }
        }
    }

    


  



    public function getliststorage() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $page_no = $_POST['page_no'];
        $returnarray = $this->Storage_model->getlistdata($page_no);
        echo json_encode($returnarray);
    }

}

?>