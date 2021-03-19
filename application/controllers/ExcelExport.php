<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ExcelExport extends MY_Controller {

    function __construct() {
        parent::__construct();

        if ($this->session->userdata('user_details')['user_id'] == null || $this->session->userdata('user_details')['user_id'] < 1) {
            // Prevent infinite loop by checking that this isn't the login controller               
            if ($this->router->class != 'User') {
                redirect(base_url());
            }
        }
        $this->load->model('Shipment_model');
        $this->load->model('Seller_model');
        $this->load->model('Item_model');
        $this->load->model('Status_model');
        $this->load->model('Pickup_model');
        $this->load->helper('utility');
        $this->load->model('User_model');
        $this->load->model('ItemInventory_model');
        $this->load->model('Excel_export_model');
    }

    public function forwardShipmentsExport() {

        $request = json_decode(file_get_contents('php://input'), true);

        $result = $this->Excel_export_model->forwardShipmentExport($request);
        $file_name = 'shipments.csv';
        $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;charset=UTF-8;base64," . base64_encode($result)
        );
        echo json_encode($response);
    }

    public function InboundRecordExport() {

        $request = json_decode(file_get_contents('php://input'), true);
        $result = $this->Excel_export_model->InboundRecordExport($request);
        $file_name = 'InboundRecord.csv';

        $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;charset=UTF-8;base64," . base64_encode($result)
        );
        echo json_encode($response);
    }

    public function ItemInventoryExport() {

        $request = json_decode(file_get_contents('php://input'), true);
        $result = $this->Excel_export_model->ItemInventoryExport($request);
        $file_name = 'ItemInventory.csv';

        $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;charset=UTF-8;base64," . base64_encode($result)
        );
        echo json_encode($response);
    }

    public function ViewTotalInventoryExport() {

        $request = json_decode(file_get_contents('php://input'), true);
        $result = $this->Excel_export_model->ViewTotalInventoryExport($request);

        $file_name = 'ItemInventory.csv';

        $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;charset=UTF-8;base64," . base64_encode($result)
        );
        echo json_encode($response);
    }

    public function ViewSlaveExport() {

        $request = json_decode(file_get_contents('php://input'), true);
        $result = $this->Excel_export_model->ViewSlaveExport($request);

        $file_name = 'ViewSlave.csv';

        $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;charset=UTF-8;base64," . base64_encode($result)
        );
        echo json_encode($response);
    }

    public function historyViewExport() {

        $request = json_decode(file_get_contents('php://input'), true);
        $result = $this->Excel_export_model->historyViewExport($request);

        $file_name = 'ViewSlave.csv';

        $response = array(
            'op' => 'ok',
            'file_name' => $file_name,
            'file' => "data:application/vnd.ms-excel;charset=UTF-8;base64," . base64_encode($result)
        );
        echo json_encode($response);
    }

}

?>