<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('ItemCategory_model');
        $this->load->model('Item_model');
        $this->load->model('Cartoon_model');
        $this->load->model('ItemInventory_model');
        $this->load->model('Seller_model');
        $this->load->model('Shipment_model');
        $this->load->helper('form');
        // $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function GetgenrateBaarcode($text = null) {
      echo   $has_pass=password_hash('fast@124@2021',PASSWORD_DEFAULT);
        die;

        $barcodpath1 = checknewbarrcode("", 'BSE5756776074', 60, 'horizontal', 'code128', true, 1);
        $barcodpath2 = checknewbarrcode("", 'BSE5756776074', 70, 'horizontal', 'code128', true, 1);
        $barcodpath2last = checknewbarrcode("", 'BSE5756776074', 80, 'horizontal', 'code128', true, 1);
        
        $barcodpath3 = checknewbarrcode("", 'BSE5756776074', 60, 'horizontal', 'code128a', true, 1);
        $barcodpath4 = checknewbarrcode("", 'BSE5756776074', 70, 'horizontal', 'code128a', true, 1);
        $barcodpath4last = checknewbarrcode("", 'BSE5756776074', 80, 'horizontal', 'code128a', true, 1);
        
        $barcodpath5 = checknewbarrcode("", 'BSE5756776074', 60, 'horizontal', 'code39', true, 1);
        $barcodpath6 = checknewbarrcode("", 'BSE5756776074', 70, 'horizontal', 'code39', true, 1);
        $barcodpath6last = checknewbarrcode("", 'BSE5756776074', 80, 'horizontal', 'code39', true, 1);
        
        $barcodpath7 = checknewbarrcode("", 'BSE5756776074', 60, 'horizontal', 'code25', true, 1);
        $barcodpath8 = checknewbarrcode("", 'BSE5756776074', 70, 'horizontal', 'code25', true, 1);
        $barcodpath8last = checknewbarrcode("", 'BSE5756776074', 80, 'horizontal', 'code25', true, 1);
        
        $barcodpath9 = checknewbarrcode("", 'BSE5756776074', 60, 'horizontal', 'codabar', true, 1);
        $barcodpath10 = checknewbarrcode("", 'BSE5756776074', 70, 'horizontal', 'codabar', true, 1);
        $barcodpath10last = checknewbarrcode("", 'BSE5756776074', 80, 'horizontal', 'codabar', true, 1);
        
        
      
        echo '<div><h3>Sample 1</h3>';
        echo '<table><tr><td width="300"><img alt="coding sips" src="' . $barcodpath1 . '"></td>';
         echo '<td width="300"><img alt="coding sips" src="' . $barcodpath2 . '"></td>';
          echo '<td width="300"> <img alt="coding sips" src="' . $barcodpath2last . '"></td></tr></table>';
       
        echo '</div>';
          echo '<div><h3>Sample 2</h3>';
        echo '<table><tr><td width="300"><img alt="coding sips" src="' . $barcodpath3 . '"></td>';
         echo '<td width="300"><img alt="coding sips" src="' . $barcodpath4 . '"></td>';
         echo '<td width="300"><img alt="coding sips" src="' . $barcodpath4last . '"></td></tr></table>';
       
        echo '</div>';
          echo '<div><h3>Sample 3</h3>';
        echo '<table><tr><td width="300"><img alt="coding sips" src="' . $barcodpath5 . '"></td>';
         echo '<td width="300"><img alt="coding sips" src="' . $barcodpath6 . '"></td>';
         echo '<td width="300"><img alt="coding sips" src="' . $barcodpath6last . '"></td></tr></table>';
       
        echo '</div>';
          echo '<div><h3>Sample 4</h3>';
        echo '<table><tr><td width="300"><img alt="coding sips" src="' . $barcodpath7 . '"></td>';
         echo '<td width="300"><img alt="coding sips" src="' . $barcodpath8 . '"></td>';
          echo '<td width="300"><img alt="coding sips" src="' . $barcodpath8last . '"></td></tr></table>';
       
        echo '</div>';
          echo '<div><h3>Sample 5</h3>';
        echo '<table><tr><td width="300"><img alt="coding sips" src="' . $barcodpath9 . '"></td>';
         echo '<td width="300"><img alt="coding sips" src="' . $barcodpath10 . '"></td>';
         echo '<td width="300"><img alt="coding sips" src="' . $barcodpath10last . '"></td></tr></table>';
       
        echo '</div>';
        die;
    }

    public function GetCreateShipRows() {


        $this->db->select('shipment_fm.delivered,shipment_fm.code,shipment_fm.slip_no');
        $this->db->from('shipment_fm');
        $this->db->where_in('shipment_fm.code', 'POD');
        $this->db->where_in('shipment_fm.delivered', '7');
        $this->db->where(" shipment_fm.id BETWEEN 32846  and 34077");
        $this->db->join('status_fm', 'status_fm.slip_no = shipment_fm.slip_no');
        // $this->db->where_in('status_fm.code','DL');
        //  $this->db->where_in('status_fm.new_status','5');
        // $this->db->where_not_in('status_fm.new_status','9');
        // $this->db->where_not_in('status_fm.code','C');
        // $this->db->limit(10);
        $query = $this->db->get();
        $result = $query->result_array();
        echo count($result);
        die;
        /*  $query=$this->db->query('select * from shipment_fm limit 2000,5362');
          $result=$query->result_array();
          echo count($result);
          die;

          foreach($result as $data)
          {



          //   $addedArr=array('delivered'=>'8','code'=>'RTC');
          // $this->db->update('shipment_fm',$addedArr,array('slip_no'=>$data['slip_no']));
          }


          //echo $this->db->insert_batch('status_fm', $addedArr);

          //// echo '<pre>';
          // print_r($addedArr);
          // echo $query->num_rows(); die;
          die;
          die;

          /* $this->db->select('shipment_fm.delivered,shipment_fm.code,shipment_fm.slip_no');
          $this->db->from('shipment_fm');
          $this->db->where_in('shipment_fm.code','PG');
          $this->db->where_in('shipment_fm.delivered','2');
          $this->db->where(" shipment_fm.id BETWEEN 32846  and 34077");
          $this->db->join('status_fm', 'status_fm.slip_no = shipment_fm.slip_no');
          $this->db->where_in('status_fm.code','DL');
          $this->db->where_in('status_fm.new_status','5');
          $this->db->where_not_in('status_fm.new_status','9');
          $this->db->where_not_in('status_fm.code','C');
          // $this->db->limit(10);
          $query = $this->db->get();
          $result=$query->result_array(); */
        /*  $query=$this->db->query('select * from shipment_fm limit 2000,5362');
          $result=$query->result_array();
          foreach($result as $data)
          {
          $Activites="Reverse order as per customer request &rarr; Original AWB #".$data['slip_no'];
          $Details="Reverse order as per customer request &rarr; Original AWB #".$data['slip_no'];
          $addedArr[]=array('slip_no'=>$data['slip_no'],'new_location'=>$data['origin'],'new_status'=>1,'pickup_time'=>$data['entrydate'],'pickup_date'=>$data['entrydate'],'Activites'=>$Activites,'Details'=>$Details,'entry_date'=>$data['entrydate'],'user_id'=>$data['cust_id'],'user_type'=>'user','code'=>'OC');
          } */


        // echo '<pre>';
        // print_r($addedArr);
        //$this->db->insert_batch('status_fm', $addedArr); 
        //  $statusInsertData.=" ('".$data['slip_no']."','".$data['sender_city']."','".$data['delivered']."','".$data['CURRENT_TIME']."','".$entrydate."','".$Activites."','".$Details."','".$entrydate."','".$data['user_id']."','".$user_type."','".$this->getStatusCode($data['delivered'])."'),";
    }

    public function GetcheekGraph() {
        $totalorderschart = $this->Shipment_model->Getalltotalchartmonth();
        echo '<pre>';
        print_r($totalorderschart);
    }

    public function index() {
        // echo md5('Am@2021@@@'); die;

        
        $year= $this->input->post('year'); 
        if ($this->session->userdata('user_details')) {

            $Total_Shipments = $this->Shipment_model->count();
            $Total_Rts = $this->Shipment_model->countRTS();
            $Item_Inventory = $this->ItemInventory_model->count_all();
            $Item_Inventory_expire = $this->ItemInventory_model->count_all_expire('exp');
            $Total_Items = $this->Item_model->count();
            $Total_Cartoons = $this->Cartoon_model->count_all();
            $totalorderschart = $this->Shipment_model->Getalltotalchartmonth($year);

            //print_r($totalorderschart); die;
            //print_r(json_encode($totalorderschart));die;
            $Total_Sellers = $this->Seller_model->count();
            $this->load->view('home', [
                'Total_Shipments' => $Total_Shipments,
                'Total_Rts' => $Total_Rts,
                'Item_Inventory' => $Item_Inventory,
                'Total_Items' => $Total_Items,
                'Total_Cartoons' => $Total_Cartoons,
                'Total_Sellers' => $Total_Sellers,
                'totalorderschart' => $totalorderschart,
                'Item_Inventory_expire' => $Item_Inventory_expire
            ]);

            //$this->load->view('home');
        } else {
            redirect(base_url() . 'Login');
        }
    }

    public function logout() {


        $this->session->unset_userdata('user_details');
        $this->session->sess_destroy();
        $this->load->library('session');
        redirect(base_url() . 'Login');
    }

}
