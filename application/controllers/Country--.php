<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Country extends MY_Controller {

    function __construct() {
        parent::__construct();

        if (menuIdExitsInPrivilageArray(79) == 'N') {
            redirect(base_url() . 'notfound');
           die;
        }
        $this->load->model('Country_model');
       // $this->load->model('Storage_model');

        $this->load->helper('utility');
        // $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    public function CountraddForm($id=null) {
         $data['EditData'] = $this->Country_model->CoutrylistData_edit($id);

        $this->load->view('country/add_country',$data);
    }
      public function Importlocations($id=null) {
         //$data['EditData'] = $this->Country_model->CoutrylistData_edit($id);

        $this->load->view('country/importlocation.php',$data);
    }
    
    public function Delivery_city_list() {    
        $this->load->view('country/delivery_city.php');
    }
    public function Importdeliverycity($id=null) {
         $companyData = $this->Country_model->GetdeliveryCOmpanyListQry();
         //print "<<pre>"; print_r($companyData);die;
         $comapnyArray = array();
         if(!empty($companyData)){
             foreach($companyData as $dataresult){
                 if($dataresult['company']=='Esnad')
                 $comapnyArray[] = array("company"=>'Esnad City Code');

                 $comapnyArray[] = array("company"=>$dataresult['company']);
             }
         }
        // $comapnyArray[] = array("company"=>"Esnad City Code");
         $comapnyArray[] = array("company"=>"Arabic City Name");
         $comapnyArray[] = array("company"=>"Salla");
         $comapnyArray[] = array("company"=>"Zid");
         sort($comapnyArray);
         $data['ListArr'] = $comapnyArray;
            //print "<pre>"; print_r($data);die;
         //$data['ListArr'] = $this->Country_model->GetdeliveryCOmpanyListQry();
         
        $this->load->view('country/importdeliverycity',$data);
    }
    
     public function Hubaddform($id=null) {
         
         $data['Countrylist'] = $this->Country_model->CoutrylistData();

         $data['EditData'] = $this->Country_model->CoutrylistData_edit($id);

        $this->load->view('country/add_hub',$data);
    }
     public function CityAddForm($id=null) {
          $data['Countrylist'] = $this->Country_model->CoutrylistData();
         $data['EditData'] = $this->Country_model->CoutrylistData_edit($id);

        $this->load->view('country/addcity',$data);
    }
    public function ViewCountrylist() {

         $data['ListArr'] = $this->Country_model->CoutrylistData();
        $this->load->view('country/vewcountry',$data);
    }
     public function Viewhublist($id=null) {

         
         $name=getdestinationfieldshow($id,'country');
         $data['ListArr'] = $this->Country_model->ViewhublistQry($name);
        $this->load->view('country/Viewhublist',$data);
    }
    
     public function Viewcitylist($id=null) {

         
         $name=getdestinationfieldshow($id,'state');
         $data['ListArr'] = $this->Country_model->ViewcitylistQry($name);
        $this->load->view('country/viewcitylist.php',$data);
    }

    public function addmaster() {
        $editids = $this->input->post('master_id');

        $masterCity = $this->Country_model->final_master_by_id($editids);
        if(!empty($masterCity))
        {
           if( $this->Country_model->AddcityBatch($masterCity)) 
           {
            $res=1;
           }  
        
           else {
            
         }
        }
        else {
            
        }
       
        if ($res == 1) {
            $this->session->set_flashdata('succmsg', 'has been added successfully');
            redirect(base_url() . 'Country/ViewCountrylist');
        } else if ($res == 2) {
            $this->session->set_flashdata('succmsg', 'has been updated successfully');
            redirect(base_url() . 'Country/ViewCountrylist');
        } else {
            $this->session->set_flashdata('errormess', 'try again');
             redirect(base_url() . 'Country/ViewCountrylist');
        }
    }
    public function import_from_master($id=null) {

        $masterCity = $this->Country_model->country_final_master();
    
         $precity=$this->Country_model->previousCity();
        $keyArray=array();
      
         // print_r( $masterCity); exit;
         foreach($precity as $key=>$val)
         {
           // array_map($masterCity);
            $key = array_search($val['city'], array_column($masterCity, 'city'));
           
           // echo '<br>'. $key.'//' .  $val['city']; 
            if(!empty($key) || $key==0 )
            {
           
              if(!in_array($key,$keyArray))
              {
                array_push($keyArray,$key);
               // $data['pre'][]=$masterCity[$key];
              }
              $key=null; 
            }
       
         }
//print_r($keyArray); 
         foreach($keyArray as $k1)
         {
             //echo '<pre>xx'.$k1 .print_r($masterCity[$k1]);
           unset($masterCity[$k1]);   
         }
        // print_r($masterCity);
    
     array_values($masterCity);
         $data['ListArr']=$masterCity;
        
       

      
       $this->load->view('country/import_from_master.php',$data);
   }
    
    
      public function Addcountry() {
         // echo "ssssss"; die;
        $this->load->library('form_validation');
        $this->form_validation->set_rules('country', 'Name', 'trim|required');
       // $this->form_validation->set_rules('user_name', 'User Name', 'required|trim|is_unique[country.country]');
       $editid = $this->input->post('id');
        if ($this->form_validation->run() == FALSE) {
            $this->CountraddForm($editid);
        } else {

            
            $country = $this->input->post('country');
            
            $data = array('country' => $country,'super_id' => $this->session->userdata('user_details')['super_id']);
            // print_r($data); die;
            $res = $this->Country_model->datainsert($data, $editid);
           // die;
            if ($res == 1) {
                $this->session->set_flashdata('succmsg', 'has been added successfully');
                redirect(base_url() . 'Country/ViewCountrylist');
            } else if ($res == 2) {
                $this->session->set_flashdata('succmsg', 'has been updated successfully');
                redirect(base_url() . 'Country/ViewCountrylist');
            } else {
                $this->session->set_flashdata('errormess', 'try again');
                 redirect(base_url() . 'Country/ViewCountrylist');
            }
        }
    }
     public function addhub() {
         // echo "ssssss"; die;
        $this->load->library('form_validation');
        $this->form_validation->set_rules('country', 'Name', 'trim|required');
         $this->form_validation->set_rules('state', 'Hub Name', 'trim|required');
       // $this->form_validation->set_rules('user_name', 'User Name', 'required|trim|is_unique[country.country]');
         $editid = $this->input->post('id');
        if ($this->form_validation->run() == FALSE) {
            $this->Hubaddform($editid);
        } else {
            
           
          
          
            $country = $this->input->post('country');
            $state = $this->input->post('state');
              $c_id=getdestinationfieldshow_name($country,'id','country');
              //die;
            $checkExists= $this->Country_model->CountryAlreadyExistsCheck($state, 'state',$editid);
          //  echo $checkExists; die;
            if($checkExists==true)
            {
            
            $data = array('country' => $country,'state' =>$state,'super_id' => $this->session->userdata('user_details')['super_id']);
            // print_r($data); die;
            $res = $this->Country_model->datainsert($data, $editid);
           // die;
            if ($res == 1) {
                $this->session->set_flashdata('succmsg', 'has been added successfully');
                redirect(base_url() . 'Country/Viewhublist/'.$c_id);
            } else if ($res == 2) {
                $this->session->set_flashdata('succmsg', 'has been updated successfully');
                redirect(base_url() . 'Country/Viewhublist/'.$c_id);
            } else {
                $this->session->set_flashdata('errormess', 'try again');
                redirect(base_url() . 'Country/Viewhublist/'.$c_id);
            }
            }
            else
            {
              
                 $this->session->set_flashdata('errormess', 'Hub Name already exists.please enter other name');
                   if($editid>0)
                 redirect(base_url() . 'Country/Hubaddform/'.$editid);
                   else
                      redirect(base_url() . 'Country/Hubaddform');  
                
            }
        }
    }
     public function addcitybtn() {
         // echo "ssssss"; die;
        $this->load->library('form_validation');
        $this->form_validation->set_rules('country', 'Name', 'trim|required');
         $this->form_validation->set_rules('state', 'Hub Name', 'trim|required');
          $this->form_validation->set_rules('city_code', 'City Code', 'trim|required');
           $this->form_validation->set_rules('city', 'City Name', 'trim|required');
       // $this->form_validation->set_rules('user_name', 'User Name', 'required|trim|is_unique[country.country]');
         $editid = $this->input->post('id');
        if ($this->form_validation->run() == FALSE) {
            $this->Hubaddform($editid);
        } else {
            
           
          
          
            $country = $this->input->post('country');
            $state = $this->input->post('state');
            $city_code = $this->input->post('city_code');
            $city = $this->input->post('city');
             $arabic_city = $this->input->post('title');
              $c_id=getdestinationfieldshow_name($state,'id','state');
              //die;
            $checkExists= $this->Country_model->CountryAlreadyExistsCheck($state, 'city',$editid);
          //  echo $checkExists; die;
            if($checkExists==true)
            {
            
            $data = array('country' => $country,'state' =>$state,'city_code'=>$city_code,'city'=>$city,'sender_city'=>'Y','title' =>$arabic_city,'super_id' => $this->session->userdata('user_details')['super_id']);
            // print_r($data); die;
            $res = $this->Country_model->datainsert($data, $editid);
           // die;
            if ($res == 1) {
                $this->session->set_flashdata('succmsg', 'has been added successfully');
                redirect(base_url() . 'Country/Viewcitylist/'.$c_id);
            } else if ($res == 2) {
                $this->session->set_flashdata('succmsg', 'has been updated successfully');
                redirect(base_url() . 'Country/Viewcitylist/'.$c_id);
            } else {
                $this->session->set_flashdata('errormess', 'try again');
                redirect(base_url() . 'Country/Viewcitylist/'.$c_id);
            }
            }
            else
            {
              
                 $this->session->set_flashdata('errormess', 'City Name already exists.please enter other name');
                   if($editid>0)
                 redirect(base_url() . 'Country/CityAddForm/'.$editid);
                   else
                      redirect(base_url() . 'Country/CityAddForm');  
                
            }
        }
    }

    
    public function getStatelistDrop()
    {
         $data = json_decode(file_get_contents('php://input'), true);
         $country=$data['country'];
         $return=$this->Country_model->CoutrylistData_drop($country);
         echo json_encode($return);
         
        
    }
    
    public function GetDeliveryCompanylist()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $headerArr=$this->Country_model->GetCityListHeaderListQry();
        
        
        $newArray=$headerArr;
        foreach($newArray as $key=>$val)
        {
            $mainArr=$this->Country_model->GetAllDeliveryCitylist($val['cc_name']);
            foreach($mainArr as $key1=>$data)
            {
            $mainArr[$key1]['city_id']=getdestinationfieldshow($data['city_id'],'city');
            $mainArr[$key1]['city_name']=$data['city_name'];
             
            }
            $newArray[$key]['city_name']=$mainArr;
            
        }
       
         echo json_encode($newArray);
    }
    
      public function cityList() {
         $startcounter = isset($_REQUEST['startcounter'])?$_REQUEST['startcounter']:0;
         $start = 0 ;
         $limit= 100;
         $filterKey = isset($_REQUEST['filter_by'])?$_REQUEST['filter_by']:'';
         $dataResult = $this->Country_model->CitylistData($start,$limit,$filterKey,$startcounter);
         //print "<pre>"; print_r($dataResult);die;
         $this->load->view('country/citylist',$dataResult);
    }
    
    public function filter_city(){
        
        
         $start = $_REQUEST['offset'] ;
         $limit= $_REQUEST['limit'];
         
         $filterKey = isset($_REQUEST['filter_by'])?$_REQUEST['filter_by']:'';;
         $dataResult = $this->Country_model->CitylistData($start,$limit,$filterKey);
         $dataResult['counter'] = $start +1;
        
         $response = $this->load->view('country/_citylist',$dataResult,TRUE);
         echo $response;
    }
    
    public function UpdateCityList() {
        
         $id = $this->input->post('city_id');
         $column = $this->input->post('column_name');
         $value = $this->input->post('columnVal');
         $data = array(
             $column=>$value,
         );
         $this->Country_model->UpdateCitylistData($data,$id);
         echo json_encode(array('success'=>true));
    }


 
}

?>