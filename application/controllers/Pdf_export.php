<?php
	class Pdf_export extends MY_Controller 
	{

		function __construct() {

		parent::__construct(); 
		$this->load->helper('pdf_helper');
		$this->load->library('pagination');
		$this->load->model('Pdf_export_model');
		}
		

		public function report_view($id,$action){
		
			
			$data['shipment']=$this->Pdf_export_model->find($id);
		    $data['sku_per_shipment']=$this->Pdf_export_model->find_by_slip_no($data['shipment'][0]->slip_no);
		  	$account_no=$this->Pdf_export_model->find_account_id($data['shipment'][0]->cust_id);	
		  	$data['account_no']=$account_no[0]->uniqueid;

		    for($i=0;$i<count($data['sku_per_shipment']);$i++){
		    	$sku[$i]=$data['sku_per_shipment'][$i]->sku;
		    	$piece[$i]=$data['sku_per_shipment'][$i]->piece;
		    	//$description[$i]=$data['sku_per_shipment'][$i]->description;		
		    }
		    // print_r($data['shipment'][0]);
		    // exit();
		    $check=$this->Pdf_export_model->find_city_code($data['shipment'][0]->destination);
			$data['city_code']=$check[0]->city_code;

		    $check2=$this->Pdf_export_model->find_city_code($data['shipment'][0]->destination);
			$data['city_code2']=$check2[0]->city;

			 $check1=$this->Pdf_export_model->find_city_code($data['shipment'][0]->origin);
			$data['city_code1']=$check1[0]->city;

			$data['seller']=$this->Pdf_export_model->find_customer_sellerm($data['shipment'][0]->cust_id);
			$data['status']=$this->Pdf_export_model->find_status($data['shipment'][0]->delivered);
			
			$this->load->view('ShipmentM/shipment_report_pdf',['data'=>$data,'sku'=>$sku,'piece'=>$piece,'action'=>$action]);

		}

		public function all_report_view(){
		
			$sku_per_shipment=null;
			$shipment=null;
			// $data['shipment']=$this->Pdf_export_model->all();
			// $this->load->view('ShipmentM/shipment_report_pdf_all',$data);
			$shipment=$this->Pdf_export_model->all();
			for($i=0;$i<count($shipment);$i++){
		    $sku_per_shipment[$i]=$this->Pdf_export_model->find_by_slip_no_for_sku($shipment[$i]->slip_no);
			}
		    // echo "<pre>";
		    // print_r($sku_per_shipment);
		    // echo "</pre>";
		    // exit();
			if(!empty($shipment)):
			$this->load->view('ShipmentM/shipment_report_pdf_all2',['shipment'=>$shipment,'sku_per_shipment'=>$sku_per_shipment]);
			else:
				redirect(base_url().'Shipment');
			endif;
		}

		public function filtered_pdf($filter){
			$sku_per_shipment=null;

			$data=explode("&", $filter);
			for($i=0;$i<count($data);$i++){
				$innerData=explode("=",$data[$i]);
				
					
					if($innerData[0]=="exact"){
						
						$exact =$innerData[1];
					}

					if($innerData[0]=="awb"){
						
						$awb =$innerData[1];
					}
					

					if($innerData[0]=="sku"){
						
						$sku =$innerData[1];
					}

					if($innerData[0]=="from"){
						
						$from =$innerData[1];
					}

					if($innerData[0]=="to"){
						
						$to =$innerData[1];
					}
					
					if($innerData[0]=="status"){
						
						$status =$innerData[1];
					}

					if($innerData[0]=="seller"){
						
						$seller =$innerData[1];
					}
				
				
			}
			$shipment=$this->Pdf_export_model->filter($awb,$sku,$status,$seller,$to,$from,$exact);
			
			for($i=0;$i<count($shipment);$i++){
		    $sku_per_shipment[$i]=$this->Pdf_export_model->find_by_slip_no_for_sku($shipment[$i]->slip_no);
			}

			
			$this->load->view('ShipmentM/shipment_report_pdf_all2',['data'=>$data,'sku_per_shipment'=>$sku_per_shipment,'shipment'=>$shipment]);
			
		}


	}
?>