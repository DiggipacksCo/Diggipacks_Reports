<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    public $site_data;
    function __construct() {
        parent::__construct(); 
        $url = $_SERVER['HTTP_HOST'];
         //$url = ltrim($url, 'fm.'); 
         $neeUrlArr=explode('.',$url);
         $url=$neeUrlArr[1].'.'.$neeUrlArr[2];
         

         if($this->session->userdata('langCheck')=='AR')
         {
            
         $this->config->set_item('language', 'arabic');
         $this->lang->load("arabic_main","arabic");
         }
         else
         {
        $this->config->set_item('language', 'english');
         //echo $this->config->item('language');	
         $this->lang->load("english_main","english");
         }	 
    
         
       
      
        if ('diggipacks.com'!= $url) {
            
          
         $this->site_data       = site_config($url);
         //print_r($this->site_data  );
        //  $this->site_data->newlogo = 'https://super.diggipacks.com/'.$this->site_data->logo; 
             
        
         
         //echo "<script> var string =JSON.stringify(" . json_encode($this->site_data) . ") localStorage.setItem('site_data', JSON.stringify(string)); </script>";
      
        } else {
         
          
         $this->site_data = site_config_default();
         $this->site_data->newlogo = 'https://lm.diggipacks.com/clientLogo/dgpk.png';
        }
		   if ($this->session->userdata('user_details')['user_id'] == null || $this->session->userdata('user_details')['user_id'] < 1) {
            
             if ($this->router->class != 'Login') 
             {                        
                 redirect(base_url());
             }
             
         }  
          
      
    }
    
}