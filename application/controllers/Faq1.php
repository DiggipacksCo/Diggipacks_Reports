<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Faq extends MY_Controller {

    function __construct() {
        parent::__construct();
        
        $this->load->model('User_model');
        $this->load->model('Faq_model');
        $this->load->library('form_validation');
        $this->load->helper('utility');
    }

    
    public function add_faq() {

       

        $this->load->view('faq/add_faq');
    }

    public function show_faq() {


       

        $this->load->view('faq/show_faq');
    }
    public function add(){
        //print_r($this->input->post()); die;
        //$edit_id= $this->input->post('edit_id');
      $this->form_validation->set_rules('question', 'Question', 'trim|required');
       $this->form_validation->set_rules('category_id', 'Category', 'trim|required');
       $this->form_validation->set_rules('answer', 'Answer', 'trim|required');
        $this->form_validation->set_rules('video_link', 'Video Link', 'trim|required');
         
         
         if ($this->form_validation->run()== FALSE)
        {
            //die('asdasd');
             $this->add_faq();
        }
        else
        {
       
           if(!empty($edit_id))
           {
           
           
           $data = array(

           'fastcoo_question' => $this->input->post('question'),
           'category ‍' => $this->input->post('category_id'),
           'fastcoo_answer' => $this->input->post('answer'),
           'video_link' => $this->input->post('video_link')
       
       );
       $seller_id=$this->Faq_model->UpdateQry($data,$edit_id);
       if($seller_id>0)
       $this->session->set_flashdata('succ_mess', $this->input->post('question').'   has been updated successfully');
       else
       $this->session->set_flashdata('err_msg','Try again');
           
           }
           else
           {
            $dateTime = date('Y-m-d H:i:s');
       $data = array(

           'fastcoo_question' => $this->input->post('question'),
           'category' => $this->input->post('category_id'),
           'fastcoo_answer' => $this->input->post('answer'),
           'video_link' => $this->input->post('video_link'),
           'entry_date' => $dateTime
       );
       
       $seller_id=$this->Faq_model->add($data);
       if($seller_id>0)
       $this->session->set_flashdata('succ_mess', $this->input->post('question').'   has been added successfully');
       else
       $this->session->set_flashdata('err_msg','Try again');
           }
       redirect('show_faq');
        }

       
   }

   public function filter()
	{
		
		 $_POST = json_decode(file_get_contents('php://input'), true);
		$items=$this->Faq_model->filter($_POST);
		$ItemArray=$items['result'];
	
		foreach($ItemArray as $key=>$val)
		{
		$ItemArray[$key]['expiry_date']	=date('M d, Y',strtotime($val['expiry_date']));	
		$ItemArray[$key]['categoryname']	=ShowfaqcatName($val['category']);	
		
		}
		$returnArray['result']=$ItemArray;
		echo json_encode($returnArray);
    }
    public function staffactiveview($id=null,$status=null)
    {
        $array=array('status'=>$status);
        $this->Faq_model->UpdateQry($array,$id);
        if($status=='Y')
        $this->session->set_flashdata('succ_mess','Successfully Active Updated');
        else
        $this->session->set_flashdata('succ_mess','Successfully Inactive Updated');
        redirect('show_faq');die;
        
    }


    public function add_view($edit_id=null){
		
		
		if(($this->session->userdata('user_details') != ''))
		{
			$data['edit_id']=$edit_id;
			// print_r($this->session->userdata('user_details'));
			// exit();
			//echo "ssssss"; die;
			$data['EditData']=$this->Faq_model->ClientEditDataQry($edit_id);
			//print_r($data['EditData']);exit();
			$this->load->view('faq/add_faq',$data);
			
		}
		else{
			redirect(base_url().'Login');
		}
		
	}


}
