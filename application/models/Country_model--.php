<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Country_model extends CI_Model {

    function __construct() {
        parent::__construct();
        // $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function datainsert($data = array(), $editid = null) {
        if ($editid > 0) {
            $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
            $this->db->update('country', $data, array('id' => $editid));
           //  echo $this->db->last_query();die;
            return 2;
        } else {
            $this->db->insert('country', $data);
             //echo $this->db->last_query();die;
            return 1;
        }
       
    }
    
    public function final_master_by_id($id=array())
    {
    
      if(!empty($id))
      {
        $this->db->where('deleted', 'N');
        $this->db->where('status', 'Y');
      
        $this->db->where_in("id",$id);
        $this->db->select("  'Saudi Arabia' as country,`city`, arabic_name as title, `naqel_city_code`, `state`, `aramex_city`, `samsa_city`, `clex`, `esnad_city`, `zajil`, `barq_city`, `moovo`, `sls`, `safe_arrival`, `saudipost_id`, `aymakan`, `tamex_city`, `alamalkon`, `shipsa_city`, `saee_city`, `labaih`, `quickbox`,".$this->session->userdata('user_details')['super_id']." as super_id ");
        $this->db->from('country_final_master');
       // $this->db->order_by('city','ASC');
       
        $query = $this->db->get();
      //  echo $this->db->last_query(); exit;
        return $query->result_array();

      }
         // $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
      
    }

    public function country_final_master()
    {
    
         // $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('deleted', 'N');
        $this->db->where('status', 'Y');
      
        $this->db->where("city!=''");
        $this->db->select('id,city');
        $this->db->from('country_final_master');
       // $this->db->order_by('city','ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function CoutrylistData()
    {
    
          $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('deleted', 'N');
        $this->db->where('status', 'Y');
        //$this->db->where("state=''");
        // $this->db->where("city=''");
        $this->db->select('id,country');
        $this->db->group_by('country');
        $this->db->from('country');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    
    public function CoutrylistData_drop($country=null)
    {
    
          $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('deleted', 'N');
        $this->db->where('status', 'Y');
        $this->db->where("state!=''");
         $this->db->where("city=''");
          $this->db->where('country',$country);
        $this->db->select('id,country,state');
        $this->db->from('country');
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result_array();
    }
     public function CoutrylistData_edit($id=null)
    {
       $this->db->where('id', $id);
          $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('deleted', 'N');
        $this->db->where('status', 'Y');
        $this->db->select('id,country,state,city,city_code,title');
        $this->db->from('country');
        $this->db->group_by('country');
        $query = $this->db->get();
       // echo $this->db->last_query();
        return $query->row_array();
    }
    public function CountryAlreadyExistsCheck($name=null,$field=null,$id=null)
    {
    
          if(!empty($id))
          {
             $this->db->where("id!='$id'");  
          }
          $this->db->where($field, $name);
          $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
          $this->db->where('deleted', 'N');
          $this->db->where('status', 'Y');
          $this->db->where("state!=''");
          $this->db->select('id,country');
          $this->db->from('country');
          $query = $this->db->get();
        //  echo $this->db->last_query();
       if($query->num_rows()==0)
       {
           return true;
       }
       else
       {
          return false;  
       }
       
    }
     public function hublistData_edit($id=null)
    {
    
          $this->db->where('id', $id);
          $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('deleted', 'N');
        $this->db->where('status', 'Y');
         $this->db->where("state!=''");
        $this->db->select('id,country');
        $this->db->from('country');
        $query = $this->db->get();
        return $query->row_array();
    }
    public function ViewhublistQry($country=null)
    {
    
          $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
          if($country)
          {
           $this->db->where('country', $country);
           //$this->db->group_by('country');
          }
         
        $this->db->where('deleted', 'N');
        $this->db->where('status', 'Y');
        $this->db->group_by('state');
         //$this->db->where("state!=''");
         // $this->db->where("city=''");
         
        $this->db->select('id,country,state');
        $this->db->from('country');
        $query = $this->db->get();
          // echo $this->db->last_query();
        return $query->result_array();
    }
    public function ViewcitylistQry($country=null)
    {
    
          $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
          if($country)
          {
           $this->db->where('state', $country);
          }
          else
          {
            $this->db->where('state', '');  
          }
        $this->db->where('deleted', 'N');
        $this->db->where('status', 'Y');
       //  $this->db->where("state!=''");
          $this->db->where("city!=''");
        $this->db->select('id,country,state,city,city_code,title');
        $this->db->from('country');
        $query = $this->db->get();
          // echo $this->db->last_query();
        return $query->result_array();
    }

    public function previousCity($country=null)
    {
    
          $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
          if($country)
          {
           $this->db->where('state', $country);
          }
        $this->db->where('deleted', 'N');
        $this->db->where('status', 'Y');
        // $this->db->where("state!=''");
          $this->db->where("city!=''");
        $this->db->select('city');
        $this->db->from('country');
       // $this->db->order_by('city','ASC');
        $query = $this->db->get();
        
          // echo $this->db->last_query();
        return $query->result_array();
    }
    
    
     public function GetsuperIdForCountry()
    {
    
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('deleted', 'N');
        $this->db->where('status', 'Y');
        $this->db->where("state=''");
        $this->db->where("city=''");
        $this->db->select('id,country');
        $this->db->from('country');
        $query = $this->db->get();
        return $query->row_array()['country'];
    }
    
     public function GetCountryDatacheck($name=null,$value=null,$match)
    {
    
          $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('deleted', 'N');
        $this->db->where('status', 'Y');
        $this->db->where("country",$name);
         $this->db->where("city=''");
         $this->db->where($match,$value);
        $this->db->select('id,country,state');
        $this->db->from('country');
        $query = $this->db->get();
        return $query->row_array();
    }
     public function GetCountryDatacheck_city($name=null)
    {
    
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->where('deleted', 'N');
        $this->db->where('status', 'Y');      
        $this->db->where("city!=''");
        $this->db->where('city',$name);
        $this->db->select('id,country,state,city');
        $this->db->from('country');
        $query = $this->db->get();
        return $query->row_array();
    }
    
     public function AddstateData_import($data=array())
     {
      $this->db->insert('country',$data);   
     }
      public function AddcityBatch($data=array())
     {
     return $this->db->insert_batch('country',$data);
    //echo $this->db->last_query(); die;
     }
    

      public function updatecodeData($data=array(),$data_w=array())
     {
      $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
      $this->db->update('country',$data,$data_w);
     // echo $this->db->last_query(); die;
     }

     
     public function GetdeliveryCOmpanyListQry() {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']); 
        $this->db->select('cc_id,id,company,"city"');
        $this->db->from('courier_company');
        $this->db->where('status', 'Y');
        $this->db->where('company_type', 'O');
        $this->db->where('deleted', 'N');
       $query = $this->db->get();
      // echo $this->db->last_query(); die;
         return $query->result_array();
    }
    
    public function GetUpdateDeliveryCOmapny($data=array()) {
         $this->db->where('super_id', $this->session->userdata('user_details')['super_id']); 
       return $this->db->update_batch('country',$data,'id');
       //echo $this->db->last_query(); die;
    }
    
    
    
    
    public function GetCourierCItyNew($city_id=null,$company=null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']); 
        $this->db->select('id,city');
        $this->db->from('country');
       $this->db->where('id', $city_id); 
     //  $this->db->where('cc_name', $company); 
       $query = $this->db->get();
     //  echo $this->db->last_query()."<br>"; 
         return $query->row_array();
    }
    
     public function GetCityListHeaderListQry() {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']); 
        $this->db->select('cc_name');
        $this->db->from('courier_city');
        $this->db->group_by('cc_name');
        $query = $this->db->get();
     //  echo $this->db->last_query()."<br>"; 
         return $query->result_array();
    }
    
     public function GetAllDeliveryCitylist($cc_name=null) {
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']); 
        $this->db->select('`id`, `city_id`, `city_name`');
        $this->db->from('courier_city');
        $this->db->where('cc_name',$cc_name);
        $query = $this->db->get();
     //  echo $this->db->last_query()."<br>"; 
         return $query->result_array();
    }
    
    
    public function GetDataUpdateCIty_new($data=array())
    {
         $this->db->where('super_id', $this->session->userdata('user_details')['super_id']); 
       return $this->db->update_batch('country',$data,'id');
    }
    
    public function InsertCityData_new($data=array())
    {
        $insertcity = $this->db->insert_batch('country',$data);
      // echo $this->db->last_query()."<br>"; die ; 
       return $insertcity;
    }
    
     public function CitylistData($start,$limit,$filterKey){
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']); 
        //$this->db->select('id,city,samsa_city,ubreem_city,aramex_city,dots_city,imile_city,naqel_city_code,esnad_city,samana_city,agility_city,descen,aymakan,zajil,clex,rabel_city,speedzi_city,barq_city,labaih,makhdoom,saee_city,ajeek_city,emdad_city,shipsy_city,shipsa_city,tamex_city,alamalkon,latitute,longitute');
        $this->db->select('id,city,samsa_city,ubreem_city,aramex_city,dots_city,imile_city,naqel_city_code,esnad_city,esnad_city_code,samana_city,agility_city,descen,aymakan,zajil,clex,rabel_city,speedzi_city,barq_city,labaih,makhdoom,saee_city,ajeek_city,emdad_city,shipsy_city,shipsa_city,tamex_city,zid,sala,alamalkon,latitute,longitute,saudipost_id');
        $this->db->from('country'); 
        if(!empty($filterKey)){
            $this->db->where("city like '%".$filterKey."%'",NULL,FALSE );
        }
        $this->db->where('status', 'Y');
        $this->db->where('deleted', 'N');
        $this->db->where('city!=', '');
        
        $this->db->limit($limit, $start);
        

        $query = $this->db->get();
       //echo $this->db->last_query()."<br>";
       $count =  $this->cityRecordsCount($filterKey); 
       $totalpages = ceil($count / $limit);

       $data['result'] = $query->result_array();
       $data['total_result'] = $count;
       $data['totalpages'] = $totalpages;
       $data['per_page_records'] = $limit;
       return $data; 
    }
    
    public function UpdateCitylistData($data,$id){
        $this->db->where('super_id', $this->session->userdata('user_details')['super_id']);
        $this->db->update('country', $data, array('id' => $id));
      //  echo $this->db->last_query();
        
    }
    
    public function cityRecordsCount($filterKey){
        $this->db->select('*');
        $this->db->from('country'); 
        if(!empty($filterKey)){
            $this->db->where("city like '%".$filterKey."%'",NULL,FALSE );
        }
        $this->db->where('status', 'Y');
        $this->db->where('deleted', 'N');
        $this->db->where('city!=', '');
        
        return  $this->db->get()->num_rows();
    }

}
