<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mynotfound extends MY_Controller {

	function __construct() {
		parent::__construct(); 
	}

	public function index(){
		//echo "ssssss"; die;
		$this->load->view('403');
	}
	
	

}
?>