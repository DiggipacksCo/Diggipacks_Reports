<?php

defined('BASEPATH') OR exit('No direct script access allowed');

Class RoutsManagement extends MY_Controller {

    function __construct() {
        parent::__construct();
        //error_reporting(0);
        $this->load->model("RoutsManagement_model");
        $this->load->model("Country_model");
    }

    public function show_route() {

        $this->load->view('routes/show_route');
    }

    public function add_route_view($id = null) {
        $data['edit_id'] = $id;
        
        $data['EditData'] = $this->RoutsManagement_model->Getroutelist_edit($id);
        $data['Countrylist'] = $this->Country_model->CoutrylistData();

        $this->load->view('routes/add_route', $data);
    }

    
    public function delete_route($id=null)
    {
        if(!empty($id))
        {
        $array=array('deleted'=>'Y');
        $this->RoutsManagement_model->getroutedelete($array,$id);
         $this->session->set_flashdata('succmsg', 'has been Deleted successfully');
        }
          redirect(base_url() . 'show_route');
    }
    public function addRouteFormSubmit() {
        // echo "ssssss"; die;
          $edit_id = $this->input->post('edit_id');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('country', 'Country', 'trim|required');
        $this->form_validation->set_rules('routecode', 'Route Code', 'trim|required');
        $this->form_validation->set_rules('city_id', 'City', 'trim|required');
        $this->form_validation->set_rules('route', 'Route', 'trim|required');
        $this->form_validation->set_rules('latlang', 'latitude,longitude', 'trim|required');
        $this->form_validation->set_rules('keyword', 'keyword', 'trim|required');

        
       // $editid = $this->input->post('id');
        if ($this->form_validation->run() == FALSE) {
            $this->add_route_view($edit_id);
        } else {




            $country_id = $this->input->post('country');
            $routecode = $this->input->post('routecode');
            $state_id = $this->input->post('city_id');
            $city = $this->input->post('city_id');
            $route = $this->input->post('route');
            $latlang = $this->input->post('latlang');
            $keyword = $this->input->post('keyword');
            
            $state_id=getdestinationfieldshow_name($city,'state','city');
         $checkRouts=$this->RoutsManagement_model->checkRoute($route,$edit_id);
            //die;
        // echo $checkRouts; die;
            if ($checkRouts == true) {

                $data = array('routecode' => $routecode, 'country_id' => $country_id, 'state_id' => $state_id, 'city_id' => $city, 'route' => $route, 'super_id' => $this->session->userdata('user_details')['super_id'],'latlang'=>$latlang,'keyword'=>$keyword);
                // print_r($data); die;
                $res = $this->RoutsManagement_model->AddedRutesData($data, $edit_id);
                // die;
                if ($res == 1) {
                    $this->session->set_flashdata('succmsg', 'has been added successfully');
                    redirect(base_url() . 'show_route');
                } else if ($res == 2) {
                    $this->session->set_flashdata('succmsg', 'has been updated successfully');
                   redirect(base_url() . 'show_route');
                } else {
                    $this->session->set_flashdata('errormess', 'try again');
                    redirect(base_url() . 'show_route');
                }
            } else {
//echo "ssss"; die;
                $this->session->set_flashdata('errormess', 'Route Already there');
                if ($editid > 0)
                    redirect(base_url() . 'edit_route/' . $edit_id);
                else
                    redirect(base_url() . 'add_route');
            }
        }
    }

    public function showRoutelist() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $returnArray = $this->RoutsManagement_model->getShowroute($_POST['searchroute'], $_POST['page_no']);
        $maniarray = $returnArray['result'];
        $dataArray['pdata'] = $_POST;
        $dataArray['result'] = $maniarray;
        $dataArray['count'] = $returnArray['count'];
        echo json_encode($dataArray);
    }

    public function showRoutelistExcel() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $returnArray = $this->RoutsManagement_model->getexcelroutrtabl($_POST);

        echo json_encode($returnArray);
    }

    public function get_delete_route() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $array = array('deleted' => 'Y');
        $ReturnData = $this->RoutsManagement_model->getroutedelete($array, $_POST['id']);
        echo json_encode($ReturnData);
    }

    public function add_route() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $dataArray = $_POST['add_route'];
        $city = $dataArray['city_id'];
        $city_id = getidsByNameshow($city);
        // print_r($dataArray); die; 

        if ($this->RoutsManagement_model->checkRoute($dataArray['routecode']) == true) {

            $routeArray = array('routecode' => $dataArray['routecode'], 'route' => $dataArray['route'], 'keyword' => $dataArray['keyword'], 'country_id' => $dataArray['country_id'], 'city_id' => $city_id, 'super_id' => $this->session->userdata('super_id'), 'latlang' => $dataArray['latlang']);

            $res_data = $this->RoutsManagement_model->getRoute($routeArray);

            echo json_encode(true);
        } else {
            echo json_encode(false);
        }
    }

    public function geteditrouteData() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $table_id = $_POST['routeid'];

        $returnArray = $this->RoutsManagement_model->Getroutelist_edit($table_id);
        $returnArray['cityname'] = getdestinationfieldshow($returnArray['city_id'], 'state');
        echo json_encode($returnArray);
    }

    public function edit_Routeform() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $dataArray = $_POST['edit_route'];
        $city = $dataArray['city_id'];
        $city_id = getidsByNameshow($city);
        $routeid = $dataArray['id'];
        $editrouteArray = array('routecode' => $dataArray['routecode'], 'route' => $dataArray['route'], 'keyword' => $dataArray['keyword'], 'country_id' => $dataArray['country_id'], 'city_id' => $city_id, 'latlang' => $dataArray['latlang']);
        $res_data = $this->RoutsManagement_model->routeUpdate($editrouteArray, $routeid);
        echo json_encode($res_data);
    }

    public function RouteCityDrop() {

        $_POST = json_decode(file_get_contents('php://input'), true);
        $returnArray = $this->RoutsManagement_model->GetCityRouteDrop($_POST);
        echo json_encode($returnArray);
    }

    public function UploadBulkUpExcel() {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $path = $_FILES["file"]["tmp_name"];
        if (!empty($path)) {
            $this->load->library("excel");
            $object = PHPExcel_IOFactory::load($path);
            foreach ($object->getWorksheetIterator() as $worksheet) {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                $returnArr = array();
                for ($row = 2; $row <= $highestRow; $row++) {

                    $countryName = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $cityname = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $routecode = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $route = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $keyword = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $latlang = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $hub = $worksheet->getCellByColumnAndRow(6, $row)->getValue();


                    $cityData = GetCityAllDataByname($cityname);
                    $city_id = $cityData['id'];

                    if (empty($hub)) {
                        $hub = $cityData['state'];
                    }


                    if (empty($city_id)) {
                        if (!empty($hub)) {
                            $this->RoutsManagement_model->InsertCityList(array('country' => 'Saudi Arabia', 'city' => $cityname, 'state' => $hub, 'super_id' => $this->session->userdata('super_id')));

                            $cityData = GetCityAllDataByname($cityname);
                            $city_id = $cityData['id'];

                            if (empty($hub)) {
                                $hub = $cityData['state'];
                            }
                        }
                    }

                    $countryName = Get_name_country_by_id('country', $city_id);

                    $country_id = GetCountryNameByid($countryName);
                    if ($city_id > 0 && $this->RoutsManagement_model->checkRoute($route) == true) {

                        $data = array(
                            'country_id' => $country_id,
                            'city_id' => $city_id,
                            'state_id' => $hub,
                            'routecode' => $routecode,
                            'super_id' => $this->session->userdata('super_id'),
                            'route' => $route,
                            'keyword' => $keyword,
                            'latlang' => $latlang
                        );

                        $this->RoutsManagement_model->getRoute($data);


                        $returnArr['valid'][] = $row;
                    } else {
                        $returnArr['cityiderr'][] = $row;
                    }
                }
            }
        } else
            $returnArr['fileemtpy'];

        echo json_encode($returnArr);
    }

}
