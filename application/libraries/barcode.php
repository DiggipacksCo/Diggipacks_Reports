<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 include_once APPPATH.'/third_party/fpdf/mpdf.php';
class M_pdf {

     public $param;
    public $pdf;

    public function __construct($param = "")
    {
        $this->param =$param;
        $this->pdf = new mPDF('utf-8',array(101,152),0, '', 0, 0, 0, 0, 0, 0);
    }
}	