<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Offers extends MY_Controller {

    function __construct() {
        parent::__construct();
        //echo "sssss"; die;
        if (menuIdExitsInPrivilageArray(19) == 'N') {
            redirect(base_url() . 'notfound');
            die;
        }
        if (menuIdExitsInPrivilageArray(4) == 'N') {
            //redirect(base_url().'notfound'); die;
        }
        //$this->load->library('pagination');
        $this->load->model('Offers_model');
        $this->load->library('form_validation');
    }

    public function offerslist() {
        $data['sellersArray'] = $this->Offers_model->getallsellersdata();
        $data['offersArray'] = $this->Offers_model->all();
        $this->load->view('offerM/offerlist', $data);
    }
    
     public function giftOffersList() {
        $data['sellersArray'] = $this->Offers_model->getallsellersdata();
        $data['offersArray'] = $this->Offers_model->all();
        $this->load->view('offerM_gift/offerlist', $data);
    }

    public function GetofferOrderslist() {
        $data['sellersArray'] = $this->Offers_model->getallsellersdata();
        $data['offersArray'] = $this->Offers_model->all();
        $this->load->view('offerM/offerOrders', $data);
    }
    
      public function GetofferOrderslist_gift() {
        $data['sellersArray'] = $this->Offers_model->getallsellersdata();
        $data['offersArray'] = $this->Offers_model->all();
        $this->load->view('offerM_gift/offerOrders', $data);
    }

    public function Addoffers() {

        $data['sellersArray'] = $this->Offers_model->getallsellersdata();
        $data['ItemsArray'] = $this->Offers_model->getallitemsdata();
        $this->load->view('offerM/add_offer', $data);
    }

    public function AddofferGift() {

        $data['sellersArray'] = $this->Offers_model->getallsellersdata();
        $data['ItemsArray'] = $this->Offers_model->getallitemsdata();
        $this->load->view('offerM_gift/add_offer', $data);
    }

    public function edit_offer($id = null) {
        $data['editData'] = $this->Offers_model->edit_view($id);
        $data['edit_id'] =$id;

        $this->load->view('offerM/edit_offer', $data);
    }
    
      public function edit_offer_gift($id = null) {
          
          
           $data['sellersArray'] = $this->Offers_model->getallsellersdata();
           
          
        $data['editData'] = $this->Offers_model->edit_view_gift($id);
        $main_itemsArr =$this->Offers_model->GetALlskuAQtyQry_gift_edit($data['editData']['promocode']);
        $main_json=array();
        foreach($main_itemsArr as $val)
        {
            
           array_push($main_json,$val->main_item); 
        }
          $data['main_itemsArr']= json_encode($main_json);
           $data['offer_itemsArr']=  $data['editData']['gift_item'];
          $data['seller_name']= getallsellerdatabyID( $data['editData']['seller_id'],'name');

        $this->load->view('offerM_gift/edit_offer', $data);
    }

    function sellerdropdata() {
        $dataArray = json_decode(file_get_contents('php://input'), true);
        $sellers = $this->Offers_model->getallitemsdata($dataArray['seller_id']);

        echo json_encode($sellers);
    }

    public function Getofferlistdata() {
        $dataArray = json_decode(file_get_contents('php://input'), true);
        $listArr = $this->Offers_model->all($dataArray);
        $newlistArray = $listArr['result'];
        foreach ($newlistArray as $key => $row) {
            if ($row['expire_date'] >= date('Y-m-d'))
                $expireStatus = "N";
            else
                $expireStatus = "Y";
            $newlistArray[$key]['expireStatus'] = $expireStatus;
            $SkuArr = $this->Offers_model->GetALlskuAQtyQry($row['promocode']);
            $newlistArray[$key]['SkuArr'] = $SkuArr;
            $newlistArray[$key]['firstSku'] = getalldataitemtables($row['main_item'], 'sku');
            $newlistArray[$key]['seller_name'] = getallsellerdatabyID($row['seller_id'], 'name');

            $offer_itemArr = explode(',', $row['offer_item']);
            //print_r($offer_itemArr);
            foreach ($offer_itemArr as $key2 => $val) {
                if ($key2 == 0)
                    $offerItems = getalldataitemtables($val, 'sku');
                else
                    $offerItems .= ',' . getalldataitemtables($val, 'sku');
            }
            $newlistArray[$key]['offerItems'] = $offerItems;
            if ($row['type'] == 'admin')
                $newlistArray[$key]['username'] = getUserNameById($row['added_by']);
            else
                $newlistArray[$key]['username'] = getallsellerdatabyID($row['added_by'], 'name');
        }
        $return['result'] = $newlistArray;
        $return['count'] = $listArr['count'];
        echo json_encode($return);
    }
    
     public function Getofferlistdata_gift() {
         
         
        $dataArray = json_decode(file_get_contents('php://input'), true);
        $listArr = $this->Offers_model->all_gift($dataArray);
        $newlistArray = $listArr['result'];
        foreach ($newlistArray as $key => $row) {
          
          
            $gift_item= json_decode($row['gift_item']);
            $newlistArray[$key]['gift_item']=$gift_item;
           
             $SkuArr = $this->Offers_model->GetALlskuAQtyQry_gift($row['promocode']);
            $newlistArray[$key]['SkuArr'] = $SkuArr;
            $newlistArray[$key]['seller_name'] = getallsellerdatabyID($row['seller_id'], 'name');

           
           
            if ($row['type'] == 'admin')
                $newlistArray[$key]['username'] = getUserNameById($row['added_by']);
            else
                $newlistArray[$key]['username'] = getallsellerdatabyID($row['added_by'], 'name');
        }
        $return['result'] = $newlistArray;
        $return['count'] = $listArr['count'];
        echo json_encode($return);
    }

    public function Getofferorderlistdata() {


        $dataArray = json_decode(file_get_contents('php://input'), true);
        $listArr = $this->Offers_model->all_orders($dataArray);
        $newlistArray = $listArr['result'];
        foreach ($newlistArray as $key => $row) {
            $SkuArr = $this->Offers_model->GetALlskuAQtyQry_orders($row['slip_no']);
            $newlistArray[$key]['SkuArr'] = $SkuArr;
            $newlistArray[$key]['seller_name'] = getallsellerdatabyID($row['seller_id'], 'name');
        }
        $return['result'] = $newlistArray;
        $return['count'] = $listArr['count'];
        echo json_encode($return);
    }

    
     public function Getofferorderlistdata_gift() {
   

        $dataArray = json_decode(file_get_contents('php://input'), true);
        $listArr = $this->Offers_model->all_orders_gift($dataArray);
        $newlistArray = $listArr['result'];
        foreach ($newlistArray as $key => $row) {
            $SkuArr = $this->Offers_model->GetALlskuAQtyQry_orders_gift($row['slip_no']);
            $newlistArray[$key]['SkuArr'] = $SkuArr;
            $newlistArray[$key]['seller_name'] = getallsellerdatabyID($row['seller_id'], 'name');
        }
        $return['result'] = $newlistArray;
        $return['count'] = $listArr['count'];
        echo json_encode($return);
    }

    public function Inactive($id = null, $status = null) {
        if ($id && ($status == 'Y' || $status == 'N')) {
            $inserarray = array('status' => $status);
            $res = $this->Offers_model->GetupdateOfferdata($inserarray, $id);
            if ($res > 0) {
                if ($status == 'Y')
                    $update = 'Active';
                else
                    $update = 'Inactive';
                $this->session->set_flashdata('succmsg', 'has been ' . $update . ' successfully');
            } else {
                $this->session->set_flashdata('errmsg', 'try gain');
            }
        }
         redirect('Offers/offerslist');
    }
    
     public function Inactive_gift($id = null, $status = null) {
        if ($id > 0 && ($status == 'Y' || $status == 'N')) {
            $inserarray = array('status' => $status);
            $res = $this->Offers_model->GetupdateOfferdata_gift($inserarray, $id);
            if ($res > 0) {
                if ($status == 'Y')
                    $update = 'Active';
                else
                    $update = 'Inactive';
                $this->session->set_flashdata('succmsg', 'has been ' . $update . ' successfully');
            } else {
                $this->session->set_flashdata('errmsg', 'try gain');
            }
        }
        redirect('Offers/giftOffersList');
    }

    public function Updateform($id = nll) {
        $this->load->helper('security');
        $this->form_validation->set_rules("start_date", 'Start Date', 'trim|required|xss_clean');
        $this->form_validation->set_rules("expire_date", 'End Date', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $this->edit_offer($id);
        } else {
            $start_date = $this->input->post('start_date');
            $expire_date = $this->input->post('expire_date');
            $inserarray = array('start_date' => $start_date, 'expire_date' => $expire_date);
            $res = $this->Offers_model->GetupdateOfferdata($inserarray, $id);
            if ($res > 0) {
                $this->session->set_flashdata('succmsg', 'has been Updated successfully');
            } else {
                $this->session->set_flashdata('errmsg', 'try gain');
            }
           redirect('Offers/offerslist');
        }
    }

    public function GetaddformofferData() {

        $data = json_decode(file_get_contents('php://input'), true);
        //print_r($data);
        if (!empty($data['main_item']) && !empty($data['seller_id']) && !empty($data['itemqty']) && !empty($data['start_date']) && !empty($data['expire_date'])) {
            if ($data['expire_date'] >= $data['start_date']) {
                $promocode = getpromodegenrate(10);
                $QtyArr = $data['itemqty'];
                foreach ($data['main_item'] as $val) {
                    $qty = $QtyArr[$val];
                    $inserarray[] = array('seller_id' => $data['seller_id'], 'main_item' => $val, 'qty' => $qty, 'start_date' => $data['start_date'], 'expire_date' => $data['expire_date'], 'promocode' => $promocode, 'entrydate' => date("Y-m-d H:i:s"), 'type' => 'admin', 'added_by' => $this->session->userdata('user_details')['user_id'], 'super_id' => $this->session->userdata('user_details')['super_id']);
                }
              //  print_r($inserarray);
                $this->Offers_model->getinsertoffersdata($inserarray);
            } else
                $return = array('status' => 'error', 'mess' => 'please enter valid start or expire date');
        } else
            $return = array('status' => 'error', 'mess' => 'all field are required');

        echo json_encode($return);
    }
    
     public function GetaddformofferData_gift() {

        $data = json_decode(file_get_contents('php://input'), true);
       // print_r($data);
        if (!empty($data['main_item']) && !empty($data['seller_id']) && !empty($data['gift_item'])) {
             {
                $promocode = getpromodegenrate(10);
                
                if($data['status']==1)
                {
                    $status='Y';
                }
                else
                {
                   $status='N'; 
                }
                $giftSku = json_encode($data['gift_item']);
                foreach ($data['main_item'] as $val) {
                    $qty = $QtyArr[$val];
                    $inserarray[] = array('seller_id' => $data['seller_id'], 'main_item' => $val,'gift_item'=>$giftSku, 'promocode' => $promocode, 'entrydate' => date("Y-m-d H:i:s"), 'type' => 'admin','status'=>$status, 'added_by' => $this->session->userdata('user_details')['user_id'], 'super_id' => $this->session->userdata('user_details')['super_id']);
                }
               
                $this->Offers_model->getinsertoffersdata_gift($inserarray);
            } 
        } else
            $return = array('status' => 'error', 'mess' => 'all field are required');

        echo json_encode($return);
    }
 public function GetaddformofferData_gift_edit() {

        $data = json_decode(file_get_contents('php://input'), true);
      //  print_r($data); die;
        if (!empty($data['main_item']) && !empty($data['seller_id']) && !empty($data['gift_item']) && !empty($data['promocode'])) {
             {
                $promocode = $data['promocode'];
                
                if($data['status']==1)
                {
                    $status='Y';
                }
                else
                {
                   $status='N'; 
                }
                $giftSku = json_encode($data['gift_item']);
                 $this->Offers_model->DeleteOfferData($promocode);
                foreach ($data['main_item'] as $val) {
                    $qty = $QtyArr[$val];
                    $inserarray[] = array('seller_id' => $data['seller_id'], 'main_item' => $val,'gift_item'=>$giftSku, 'promocode' => $promocode, 'entrydate' => date("Y-m-d H:i:s"), 'type' => 'admin','status'=>$status, 'added_by' => $this->session->userdata('user_details')['user_id'], 'super_id' => $this->session->userdata('user_details')['super_id']);
                }
               
                $this->Offers_model->getinsertoffersdata_gift($inserarray);
            } 
        } else
            $return = array('status' => 'error', 'mess' => 'all field are required');

        echo json_encode($return);
    }
    public function getaddform() {
        $this->load->helper('security');
        $this->form_validation->set_rules("seller_id", 'Seller', 'trim|required|xss_clean');
        $this->form_validation->set_rules("main_item[]", 'Main Item', 'trim|required|xss_clean');
        // $this->form_validation->set_rules("offer_item[]", 'Sub Item', 'trim|required|xss_clean');
        // $this->form_validation->set_rules("itemqty", 'QTY', 'trim|required|xss_clean');

        $this->form_validation->set_rules("start_date", 'Start Date', 'trim|required|xss_clean');
        $this->form_validation->set_rules("expire_date", 'End Date', 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->Addoffers();
        } else {
            // echo '<pre>';
            // print_r($this->input->post()); die;
            $seller_id = $this->input->post('seller_id');
            $main_itemarray = $this->input->post('main_item');
            $qty = $this->input->post('itemqty');
            $offer_itemarray = implode(',', $this->input->post('offer_item'));
            $start_date = $this->input->post('start_date');
            $expire_date = $this->input->post('expire_date');

            $promocode = getpromodegenrate(10);

            $inserarray[] = array('seller_id' => $seller_id, 'main_item' => $main_itemarray, 'qty' => $qty, 'offer_item' => $offer_itemarray, 'start_date' => $start_date, 'expire_date' => $expire_date, 'promocode' => $promocode, 'entrydate' => date("Y-m-d H:i:s"), 'type' => 'admin', 'added_by' => $this->session->userdata('user_details')['user_id'], 'super_id' => $this->session->userdata('user_details')['super_id']);


            $res = $this->Offers_model->getinsertoffersdata($inserarray);
            if ($res > 0) {
                $this->session->set_flashdata('succmsg', 'has been added successfully');
            } else {
                $this->session->set_flashdata('errmsg', 'try gain');
            }
           redirect('Offers/offerslist');
        }
    }

}

?>