<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ticket extends MY_Controller {

	function __construct() {
		parent::__construct(); 
		if(menuIdExitsInPrivilageArray(18)=='N')
		{
			redirect(base_url().'notfound'); die;
			
		}
        if ($this->session->userdata('user_details')['user_id'] == null || $this->session->userdata('user_details')['user_id'] < 1) {
            // Prevent infinite loop by checking that this isn't the login controller               
            if ($this->router->class != 'User') 
            {                        
                redirect(base_url());
            }
        }
		$this->load->model('Ticket_model');
		$this->load->helper('utility');
		// $this->user_id = isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
	}

	
	
	public function showTicket()
	{
		
		$this->load->view('tickets/listall');
	}
	public function showTicketview()
	{
		
		$this->load->view('tickets/fulfil_ticket');
	}
	public function tickethistory($ticketid=null)
	{
		$view['ticketData']=$this->Ticket_model->getticketdetailsdata_fm($ticketid);
		//print_r($view['ticketData']); die;
		$view['oldtcount']=count($this->Ticket_model->gettickethistorydata_fm($view['ticketData']['id']));
		
		$this->load->view('tickets/detailsview_fulfil',$view);
	}
	public function ticketdetails_view($ticketid=null)
	{
		$view['ticketData']=$this->Ticket_model->getticketdetailsdata($ticketid);
		$view['oldtcount']=count($this->Ticket_model->gettickethistorydata($view['ticketData']['id']));
		
		$this->load->view('tickets/detailsview',$view);
	}
	
	
	public function filter_fulfil(){
         $this->load->model('User_model');
         $_POST = json_decode(file_get_contents('php://input'), true);
        
        $page_no= $_POST['page_no']; 
        $seller_id=$_POST['seller_id'];
		$searchstatus=$_POST['searchstatus'];
		$filterarray=array('seller_id'=>$seller_id,'searchstatus'=>$searchstatus);
		$shipments=$this->Ticket_model->filter_fulfil($page_no,$filterarray);

		$manifestarray=$shipments['result'];
		$ii=0;
		$seller_ids="";
		foreach($shipments['result'] as $rdata)
		{
			
			
			//$stockLocation[]=$this->Ticket_model->GetallstockLocation($rdata['seller_id']);
			
		
			$manifestarray[$ii]['sid']=$rdata['seller_id'];
			if($ii==0)
			$seller_ids=$rdata['seller_id'];
			else
			$seller_ids.=','.$rdata['seller_id'];
			
			$manifestarray[$ii]['mid_id']=getpickuprequestData($rdata['pickup_id'],'uniqueid');
			
			 
            if($rdata['seller_id']>0)
            $manifestarray[$ii]['seller_id']=getallsellerdatabyID($rdata['seller_id'],'name');
            else
             $manifestarray[$ii]['seller_id']='N/A';  
			  
			
			$ii++;
		}
		$sellers=Getallsellerdata($seller_ids);
		$dataArray['result']=$manifestarray;
        $dataArray['count']=$shipments['count'];
        $dataArray['sellers']=$sellers;
		//$dataArray['stockLocation']=$stockLocation;
		//print_r($shipments);
		//exit();
		echo json_encode($dataArray);

	}
	public function filter(){
         $this->load->model('User_model');
         $_POST = json_decode(file_get_contents('php://input'), true);
        
        $page_no= $_POST['page_no']; 
        $seller_id=$_POST['seller_id'];
		$searchstatus=$_POST['searchstatus'];
		$filterarray=array('seller_id'=>$seller_id,'searchstatus'=>$searchstatus);
		$shipments=$this->Ticket_model->filter($page_no,$filterarray);

		$manifestarray=$shipments['result'];
		$ii=0;
		$seller_ids="";
		foreach($shipments['result'] as $rdata)
		{
			
			
			//$stockLocation[]=$this->Ticket_model->GetallstockLocation($rdata['seller_id']);
			
		
			$manifestarray[$ii]['sid']=$rdata['seller_id'];
			if($ii==0)
			$seller_ids=$rdata['seller_id'];
			else
			$seller_ids.=','.$rdata['seller_id'];
			
			$manifestarray[$ii]['mid_id']=getpickuprequestData($rdata['pickup_id'],'uniqueid');
			
			 
            if($rdata['seller_id']>0)
            $manifestarray[$ii]['seller_id']=getallsellerdatabyID($rdata['seller_id'],'name');
            else
             $manifestarray[$ii]['seller_id']='N/A';  
			  
			
			$ii++;
		}
		$sellers=Getallsellerdata($seller_ids);
		$dataArray['result']=$manifestarray;
        $dataArray['count']=$shipments['count'];
        $dataArray['sellers']=$sellers;
		//$dataArray['stockLocation']=$stockLocation;
		//print_r($shipments);
		//exit();
		echo json_encode($dataArray);

	}
	
	public function GetUpdateticketdatafile(){
        // $this->load->model('User_model');
         $_POST = json_decode(file_get_contents('php://input'), true);
		 $tableid=$_POST['tid'];
        $result=$this->Ticket_model->getticketupdatequery($tableid);
		$erurnarray=$result;
		$jj=0;
		foreach($result as $rdata)
		{
			$erurnarray[$jj]['seller_name']=getallsellerdatabyID($rdata['seller_id'],'name');
			$erurnarray[$jj]['user_name']=getUserNameByIdType($rdata['user_id']);
			
			$jj++;
		}
		$data=array('result'=>$erurnarray,'tcount'=>count($result));
        
		echo json_encode($data);

	}
	public function GetUpdateticketdatafile_fm(){
        // $this->load->model('User_model');
         $_POST = json_decode(file_get_contents('php://input'), true);
		
		 $tableid=$_POST['id'];
		 $upstatus=$_POST['upstatus'];
		 $ticket_id=$_POST['ticket_id'];
		 $update_array=array('status'=>$upstatus,'updateon'=>date('Y-m-d'));
		 $update_array_w=array('id'=>$tableid);
        $result=$this->Ticket_model->getticketupdatequery_fm($update_array,$update_array_w);
		$data=array('result'=>$result,'tcount'=>count($result));
        
		echo json_encode($data);

	}
	
	
	public function getallticketshitorydata(){
        // $this->load->model('User_model');
         $_POST = json_decode(file_get_contents('php://input'), true);
		 $DataArray=$_POST;
		 $tableid=$DataArray['tid'];
		  $replymess=$DataArray['replymess'];
		  $oldtcount=$DataArray['oldtcount'];
		 if(!empty($replymess))
		 {
			
			$returndata= $this->Ticket_model->getupdatereplyMessage($DataArray);
		 }
        $result=$this->Ticket_model->gettickethistorydata($tableid);
		$erurnarray=$result;
		$jj=0;
		foreach($result as $rdata)
		{
			$erurnarray[$jj]['seller_name']=getallsellerdatabyID($rdata['seller_id'],'name');
			$erurnarray[$jj]['user_name']=getUserNameByIdType($rdata['user_id']);
			
			$jj++;
		}
		if(!empty($replymess))
		 {
			// $oldtcount=count($result);
		 }
        $data=array('result'=>$erurnarray,'tcount'=>count($result),'oldtcount'=>$oldtcount);
		echo json_encode($data);

	}
	public function replyaddchat_fm(){
		// $this->load->model('User_model');
         $_POST = json_decode(file_get_contents('php://input'), true);
		 $DataArray=$_POST;
		 $tableid=$DataArray['tid'];
		  $replymess=$DataArray['replymess'];
		  $oldtcount=$DataArray['oldtcount'];
		 if(!empty($replymess))
		 {
			
			$returndata= $this->Ticket_model->getupdatereplyMessage_fm($DataArray);
		 }
        $result=$this->Ticket_model->gettickethistorydata_fm($tableid);
		$erurnarray=$result;
		$jj=0;
		foreach($result as $rdata)
		{
			$erurnarray[$jj]['seller_name']=getallsellerdatabyID($rdata['seller_id'],'name');
			$erurnarray[$jj]['user_name']=getUserNameByIdType($rdata['user_id']);
			
			$jj++;
		}
		if(!empty($replymess))
		 {
			// $oldtcount=count($result);
		 }
        $data=array('result'=>$erurnarray,'tcount'=>count($result),'oldtcount'=>$oldtcount);
		echo json_encode($data);
	}
	
        
	public function getallticketshitorydata_fm(){
        // $this->load->model('User_model');
         $_POST = json_decode(file_get_contents('php://input'), true);
		 $DataArray=$_POST;
		 $tableid=$DataArray['tid'];
		  $replymess=$DataArray['replymess'];
		  $oldtcount=$DataArray['oldtcount'];
		 if(!empty($replymess))
		 {
			
			//$returndata= $this->Ticket_model->getupdatereplyMessage_fm($DataArray);
		 }
        $result=$this->Ticket_model->gettickethistorydata_fm($tableid);
		$erurnarray=$result;
		$jj=0;
		foreach($result as $rdata)
		{
			$erurnarray[$jj]['seller_name']=getallsellerdatabyID($rdata['seller_id'],'name');
			$erurnarray[$jj]['user_name']=getUserNameByIdType($rdata['user_id']);
			
			$jj++;
		}
		if(!empty($replymess))
		 {
			// $oldtcount=count($result);
		 }
        $data=array('result'=>$erurnarray,'tcount'=>count($result),'oldtcount'=>$oldtcount);
		echo json_encode($data);

	}
	
	
	
  

}
?>