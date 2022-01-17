<?php

defined('BASEPATH') OR exit('No direct script access allowed');

Class Templates extends MY_Controller {

    function __construct() {
        parent::__construct();
        error_reporting(0);
        $this->load->model('Templates_model');
    }

    public function smsList() {
        $view = array();
        $this->load->view('smstemplates/show_template', $view);
    }

    public function showSmsList() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $returnArray = $this->Templates_model->getSmsData($_POST);
        $maniarray = $returnArray['result'];
        $dataArray['result'] = $maniarray;
        $dataArray['count'] = $returnArray['count'];
        echo json_encode($returnArray);
    }

    public function getStatusDrop() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $returnArray = $this->Templates_model->getStatusDropData();
        echo json_encode($returnArray);
    }

    public function subStatus() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $returnArray = $this->Templates_model->getSubstatus($_POST['main_status']);
        if ($returnArray)
            echo json_encode($returnArray);
    }

    public function showNotificationlist_alert() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $returnArray = GetLmNotificationShow();

        echo json_encode($returnArray);
    }

    public function Addtemplate() {
        $view = array();
        $this->load->view('smstemplates/add_template', $view);
    }

    public function AddtemplateSave() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $dataArray = $_POST['add_sms'];

        //print_r($dataArray); die;
        if (!empty($dataArray['id'])) {
            $templateArray = array('status_id' => $dataArray['status_id'], 'sub_status' => $dataArray['sub_status'], 'arabic_sms' => $dataArray['arabic_sms'], 'english_sms' => $dataArray['english_sms'], 'arabic_status' => $dataArray['arabic_status'], 'english_status' => $dataArray['english_status']);
        } else {
            $templateArray = array('status_id' => $dataArray['status_id'], 'sub_status' => $dataArray['sub_status'], 'arabic_sms' => $dataArray['arabic_sms'], 'english_sms' => $dataArray['english_sms'], 'arabic_status' => $dataArray['arabic_status'], 'english_status' => $dataArray['english_status'], 'super_id' => $this->session->userdata('user_details')['super_id']);
        }

        if (!empty($dataArray['id'])) {
            $res_data = $this->Templates_model->UpdateSmsDataQry($templateArray, $dataArray['id']);
        } else {

            $res_data = $this->Templates_model->insertsmsdata($templateArray);
        }

        //die;
        echo json_encode($res_data);
    }

    public function editTemplate() {
       $view = array();
        $this->load->view('smstemplates/edit_template', $view);
    }

    public function get_delete_notify() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $array = array('deleted' => 'Y');
        $ReturnData = $this->Templates_model->smsUpdate($array, $_POST['id']);
        echo json_encode($ReturnData);
    }

    public function GetActivestatusUpdate() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $array = array('arabic_status' => $_POST['arabic_status']);

        $returnArray = $this->Templates_model->smsUpdate($array, $_POST['id']);
        echo json_encode($_POST);
    }

    public function GetEnglishStatusUpdate() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $array = array('english_status' => $_POST['english_status']);

        $returnArray = $this->Templates_model->smsUpdate($array, $_POST['id']);
        echo json_encode($_POST);
    }

    public function GetSmsEditData() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $table_id = $_POST['smsid'];
        $returnArray = $this->Templates_model->QueryEditData($table_id);
        echo json_encode($returnArray);
    }

    public function EditSmsform() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $dataArray = $_POST['edit_sms'];
        $smsid = $dataArray['smsid'];
        $editsmsArray = array('status_id' => $dataArray['status_id'], 'sub_status' => $dataArray['sub_status'], 'arabic_sms' => $dataArray['arabic_sms'], 'english_sms' => $dataArray['english_sms'], 'arabic_status' => $dataArray['arabic_status'], 'english_status' => $dataArray['english_status']);
        $res_data = $this->Templates_model->smsUpdate($editsmsArray, $smsid);
        echo json_encode($res_data);
    }

}
