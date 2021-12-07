<?php
class Pdf_export_model extends CI_Model
{
 
    public function find($id){
      $deleted='N';
      $this->db->where('deleted', $deleted);
       $this->db->where('id',$id);
        $query = $this->db->get('shipment_fm');

        if($query->num_rows()>0){
            return $query->result();
          
        }

    }

    public function all(){

        $fulfillment='Y';
       $deleted='N';
        $this->db->where('fulfillment',$fulfillment);
        $this->db->where('deleted', $deleted);
        // $query = $this->db->get('shipment_fm');

        // if($query->num_rows()>0){
        //          return $query->result();

        //    // echo "<pre>";
        //    // print_r($query->result());
        //    // echo "</pre>";
        //    // exit();
          
        // }
        //diamention_fm.sku,diamention_fm.description,diamention_fm.piece
        $this->db->select('shipment_fm.id,shipment_fm.slip_no,shipment_fm.sku,status_main_cat_fm.main_status,customer.name,shipment_fm.entrydate,shipment_fm.sender_name,shipment_fm.sender_phone,shipment_fm.sender_address,shipment_fm.weight,shipment_fm.pieces,shipment_fm.total_cod_amt,shipment_fm.entrydate,shipment_fm.reciever_name,shipment_fm.reciever_phone,shipment_fm.reciever_address');
        $this->db->from('shipment_fm');
        // $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
        $this->db->join('status_main_cat_fm','status_main_cat_fm.id=shipment_fm.delivered');
        $this->db->join('customer','customer.id=shipment_fm.cust_id');

        $this->db->order_by('id','desc');
        $query =  $this->db->get();
        if($query->num_rows()>0){
                 
                  return $query->result();
                   //  echo "<pre>";
                   // print_r($query->result());
                   // echo "</pre>";
                   // exit();
        }

        // $this->db->select('shipment_fm.id,shipment_fm.slip_no,diamention_fm.sku,status_main_cat_fm.main_status,diamention_fm.piece,customer.name,shipment_fm.entrydate,diamention_fm.description,shipment_fm.sender_name,shipment_fm.sender_phone,shipment_fm.sender_address,shipment_fm.weight,shipment_fm.pieces,shipment_fm.total_cod_amt,shipment_fm.entrydate,shipment_fm.reciever_name,shipment_fm.reciever_phone,shipment_fm.reciever_address');
        // $this->db->from('shipment_fm');
        // $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
        // $this->db->join('status_main_cat_fm','status_main_cat_fm.id=shipment_fm.delivered');
        // $this->db->join('customer','customer.id=shipment_fm.cust_id');

        // $this->db->order_by('id','desc');
        // $query =  $this->db->get();
      

        // if($query->num_rows()>0){
         
        //   //return $query->result();
        //     echo "<pre>";
        //    print_r($query->result());
        //    echo "</pre>";
        //    exit();
        // }

    }

    public function find_account_id($cust_id){
        $this->db->where('id',$cust_id);
        $query=$this->db->get('customer');
        
        if($query->num_rows()>0){
         
          return $query->result();
          
        }
    }

    public function find_city_code($city_id){
        $this->db->where('id',$city_id);
        $query=$this->db->get('country');
        
        if($query->num_rows()>0){
         
          return $query->result();
          
        }
    }
    public function find_by_slip_no_for_sku($slip_no){

       
       $conditions=array(
        'slip_no'=> $slip_no
        );
		
		$this->db->order_by('deducted_shelve');
        $this->db->where($conditions);
        $query=$this->db->get('diamention_fm');
        //$this->db->last_query();
        if($query->num_rows()>0){
         
          return $query->result();
          
        }
    
    }

    public function find_by_slip_no($slip_no){


       $fulfillment='Y';
       $deleted='N';
  
       $conditions=array(
        'fulfillment' =>$fulfillment ,
        'shipment_fm.slip_no'=>$slip_no,
        'shipment_fm.deleted'=>$deleted
        );
        $this->db->where($conditions);
        $this->db->select('shipment_fm.id,shipment_fm.slip_no,diamention_fm.sku,status_main_cat_fm.main_status,diamention_fm.piece,customer.name,shipment_fm.entrydate');
        $this->db->from('shipment_fm');
        $this->db->join('status_main_cat_fm','status_main_cat_fm.id=shipment_fm.delivered');
        $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
        $this->db->join('customer','customer.id=shipment_fm.cust_id');
        $query = $this->db->get();


        if($query->num_rows()>0){
         
          return $query->result();
          
        }
    
      }

      public function find_customer_sellerm($id){
        $this->db->where('customer',$id);
        $query=$this->db->get('seller_m');

        if($query->num_rows()>0){
            return $query->result();
        }
    }

    public function find_status($id){

       $this->db->where('id',$id);
        $query = $this->db->get('status_main_cat_fm');

        if($query->num_rows()>0){
            return $query->result();
          
        }

      }


      public function filter($awb,$sku,$delivered,$seller,$to,$from,$exact){
        
        $fulfillment='Y';
         $deleted='N';
    
        $this->db->select('shipment_fm.id,shipment_fm.slip_no,shipment_fm.sku,status_main_cat_fm.main_status,customer.name,shipment_fm.entrydate,shipment_fm.sender_name,shipment_fm.sender_phone,shipment_fm.sender_address,shipment_fm.weight,shipment_fm.pieces,shipment_fm.origin,shipment_fm.destination,shipment_fm.total_cod_amt,shipment_fm.entrydate,shipment_fm.reciever_name,shipment_fm.reciever_phone,shipment_fm.reciever_address');
        $this->db->from('shipment_fm');
        $this->db->join('status_main_cat_fm','status_main_cat_fm.id=shipment_fm.delivered');
        $this->db->join('diamention_fm', 'diamention_fm.slip_no = shipment_fm.slip_no');
        $this->db->join('customer','customer.id=shipment_fm.cust_id');
        
        $this->db->where('shipment_fm.fulfillment', $fulfillment);
       $this->db->where('shipment_fm.deleted', $deleted);
        if(!empty($exact)){
          $this->db->where('DATE(shipment_fm.entrydate)', $exact);
        
        }
       
          
        if (!empty($from)&&!empty($to)) {
         $where = "DATE(shipment_fm.entrydate) BETWEEN '".$from."' AND '".$to."'";
        
         
           $this->db->where($where);
        }
         


        if(!empty($delivered)){
          $this->db->where('shipment_fm.delivered',$delivered);
        }

        if(!empty($awb)){
          $this->db->where('shipment_fm.slip_no',$awb);

        }

        if(!empty($sku)){
          $this->db->where('diamention_fm.sku',$sku);

        }

        if(!empty($seller)){
          $this->db->where('shipment_fm.cust_id',$seller);

        } 

       

        $this->db->order_by('shipment_fm.id','desc');
     
        $query = $this->db->get();
   

        if($query->num_rows()>0){
         
         return $query->result();
          // print_r($query->result());
          // exit();

        }
    }
 
}












