<?php if ( ! defined('BASEPATH')) exit('Direct access allowed');
class LanguageS extends MY_Controller
{
   public function __construct() {
       parent::__construct();
   }
   function langSwitch($language=null) {
      
	if($language=='AR')
	{
		$this->session->set_userdata(array('langCheck'=>'AR'));
	}
	else
	$this->session->set_userdata(array('langCheck'=>'EN'));
	
	 redirect(base_url().'Home');
       //redirect($_SERVER['HTTP_REFERER']);
   }
}