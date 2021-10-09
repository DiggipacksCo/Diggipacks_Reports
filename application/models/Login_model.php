<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {

	function __construct(){            
		parent::__construct();
		// $this->user_id =isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
	}

	/**
      * This function is used authenticate user at login *
    **/
	public function auth_user($username,$password) {
		
		//echo $password; die;
                $checkpass=md5($password); 
		$this->db->where("is_deleted='0'");
		$this->db->where("email",$username);
                $this->db->where("password",$checkpass);
		$result = $this->db->get('user')->result();
               // echo $this->db->last_query(); die;
		//print_r($result); die;   
		if(!empty($result)){       
			//if (password_verify($password, $result[0]->password)) {       
				if($result[0]->status != 'Y') {
					return 'not_verified';
				}
				return $result;

			//}
			//else {             
				//return false;
			//}
		} else {
			return false;
		}
	}

	public function changeSystem($super_id=null) {
		
		
		$this->db->where("is_deleted='0'");
		$this->db->where("id",$super_id);
              
		$result = $this->db->get('user')->result();
            
		if(!empty($result)){       
			 
				return $result;

		} else {
			return false;
		}
	}
}