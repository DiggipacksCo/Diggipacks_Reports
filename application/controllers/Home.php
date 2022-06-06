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
        $this->load->helper('utility_helper');
        // $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    
    
    public function Gettemp_update()
    {
        die('stop');
          echo '<pre>';      
        $data=array('DG1127900240','DG1166111769','DG1170464613','DG1397501955','DG1419755673','DG1536351584','DG1542969792','DG1613370859','DG1659496087','DG1761333252','DG1841767223','DG1907636597','DG1934746455','DG2080203220','DG2230897857','DG2250454375','DG2501409763','DG2764420784','DG2766389940','DG2767604805','DG2826370785','DG2862220071','DG2885866908','DG2905209700','DG2923730886','DG3041036593','DG3261358413','DG3272019343','DG3640920945','DG3704611298','DG3914384216','DG3939771119','DG4106314171','DG4298184044','DG4410749852','DG4540813851','DG4671446633','DG4756040551','DG4838764281','DG5233016711','DG5326438495','DG5352033291','DG5449951396','DG5471864323','DG5508940645','DG5570918603','DG5599228817','DG5678827510','DG6109156183','DG6192998835','DG6241293664','DG6897507612','DG7079828347','DG7103985063','DG7186020895','DG7358857310','DG7365787057','DG7693146539','DG7866136481','DG7915823854','DG8463635135','DG8567832417','DG8607226046','DG8700018534','DG8711280208','DG9362301097','DG9398270434','DG9547036424','DG9609917910','DG9766669126','DG9781433555','DG9823969836','DG9934734411','JDK3001497664','JDK5763185984','JDK5975744565','JDK8984943931');
    
          $newArray=array_unique($data);
//       
//                $this->db->select('*');
//                 $this->db->from('inventory_activity');
//                 $this->db->where_in('inventory_activity.awb_no', $newArray);
//                 $this->db->where('type','deducted');
//                 $this->db->where('super_id','5');
//                 $query = $this->db->get();
//                 $result = $query->result_array();
//                 print_r($result);
//               die;
      
        
        $this->db->select('shipment_fm.slip_no,shipment_fm.cust_id');
        $this->db->from('shipment_fm');
        $this->db->where_in('shipment_fm.slip_no', $newArray);
        $this->db->where('shipment_fm.super_id','5');
        $this->db->where('shipment_fm.deleted','N');
        
         $this->db->group_by('shipment_fm.slip_no');
       
        $query = $this->db->get();
        $result = $query->result_array();
        
        foreach($result as $key=>$val)
        {
            $newslip=$val['slip_no'];
             $cust_id=$val['cust_id'];
           $this->db->select('diamention_fm.piece,diamention_fm.slip_no,diamention_fm.sku,items_m.id as item_sku,items_m.sku_size,item_inventory.quantity,item_inventory.seller_id');
        $this->db->from('diamention_fm');
        $this->db->where('diamention_fm.slip_no', $newslip);
        $this->db->where('diamention_fm.super_id','5');
        $this->db->where('diamention_fm.deleted','N');
         $this->db->where('item_inventory.seller_id',$cust_id);
         $this->db->join('items_m', 'items_m.sku = diamention_fm.sku','left');
         $this->db->join('item_inventory', 'item_inventory.item_sku = items_m.id','left');
         $this->db->group_by('item_inventory.item_sku');
        
       
        $query2 = $this->db->get();
      //  echo $this->db->last_query();
        $result2 = $query2->result_array();  
        
        foreach ($result2 as $row)
        {
          $sql="update item_inventory set quantity='".$row['piece']."' where seller_id='".$row['seller_id']."' and super_id=5 and item_sku='".$row['item_sku']."'";
     
        
          
          //echo $sql."<br>"; 
        }
        
        // print_r($result2);
            
        }
      //  print_r($result);
        
        die;
        
        
      
        
 print_r($newArray);
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
         //echo md5('Am@2021@@@'); die;

        
        $year= $this->input->post('year'); 
        if ($this->session->userdata('user_details')) {
            // echo statusCount(4);   die;
            $Total_Shipments = $this->Shipment_model->count();
            $Total_Rts = $this->Shipment_model->countRTS();
            $Item_Inventory = $this->ItemInventory_model->count_all();
            $Item_Inventory_expire = $this->ItemInventory_model->count_all_expire('exp');
            $Total_Items = $this->Item_model->count();
            $Total_Cartoons = $this->Cartoon_model->count_all();
            $totalorderschart = $this->Shipment_model->Getalltotalchartmonth($year);
            // $sql = $this->db->query("SELECT COUNT(ID) as total_cnt FROM shipment_fm  where delivered='8' and deleted='N'  and backorder='0' $cndition");
          
            // $result=$sql->result();
            // print_r($result);
            // echo count($result);
            // die;

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
    public function test() {  
      //echo md5('Am@2021@@@'); die;

     
     $year= $this->input->post('year'); 
     if ($this->session->userdata('user_details')) {
         // echo statusCount(4);   die;
         $Total_Shipments = $this->Shipment_model->count();
         $Total_Rts = $this->Shipment_model->countRTS();
         $Item_Inventory = $this->ItemInventory_model->count_all();
         $Item_Inventory_expire = $this->ItemInventory_model->count_all_expire('exp');
         $Total_Items = $this->Item_model->count();
         $Total_Cartoons = $this->Cartoon_model->count_all();
         $totalorderschart = $this->Shipment_model->Getalltotalchartmonth($year);
         // $sql = $this->db->query("SELECT COUNT(ID) as total_cnt FROM shipment_fm  where delivered='8' and deleted='N'  and backorder='0' $cndition");
       
         // $result=$sql->result();
         // print_r($result);
         // echo count($result);
         // die;

         //print_r($totalorderschart); die;
         //print_r(json_encode($totalorderschart));die;
         $Total_Sellers = $this->Seller_model->count();
         $this->load->view('home1', [
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
