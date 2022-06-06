<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png');?>" type="image/x-icon">
  <title><?=lang('lang_Inventory');?></title>
  <?php $this->load->view('include/file'); ?>
<script type="text/javascript" src="<?=base_url();?>assets/js/angular/faq.app.js"></script>   

</head>

<body ng-app="faqAPP" ng-controller="Faqlistview">

  <?php $this->load->view('include/main_navbar'); ?>


  <!-- Page container -->
  <div class="page-container" >

    <!-- Page content -->
    <div class="page-content" ng-init="loadMore_faq(1,0)">

      <?php $this->load->view('include/main_sidebar'); ?>


      <!-- Main content -->
      <div class="content-wrapper" >
        <!--style="background-color: black;"-->
        <?php $this->load->view('include/page_header'); ?>



        <!-- Content area -->
        <div class="content" >
          <!--style="background-color: red;"-->
          
          
       
          <?php 
if($this->session->flashdata('succ_mess'))
echo '<div class="alert alert-success">'.$this->session->flashdata('succ_mess').' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
if($this->session->flashdata('err_msg'))
echo '<div class="alert alert-warning">'.$this->session->flashdata('err_msg').' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';


?>


          <!-- Basic responsive table -->
          <div class="panel panel-flat"  >
            <!--style="padding-bottom:220px;background-color: lightgray;"-->
            <div class="panel-heading">
              <!-- <h5 class="panel-title">Basic responsive table</h5> -->
              <h1><strong><?=lang('lang_Show_FAQ');?></strong>
                              
 
               
              </h1>

              <hr>

            </div>

            <div class="panel-body" >

          
            <div class="table-responsive" style="padding-bottom:20px;" >
              <!--style="background-color: green;"-->
              <table class="table table-striped table-hover table-bordered dataTable bg-*" id="example">
                <thead>
                  <tr>
                  <th><?=lang('lang_SrNo');?>.</th>
                    
                    <th><?=lang('lang_Question');?></th>
                    <th><?=lang('lang_Answer');?></th>
                    <th><?=lang('lang_Status');?></th>
                    <!-- <th>Category</th> -->
                    <th class="text-center" ><i class="icon-database-edit2"></i></th>
                  </tr>
                </thead>
                <tbody>
                  
                <tr ng-repeat="data in listArray">
                        <td scope="row">{{$index+1}}</td>   
                        <td>{{data.question}}</td>
                        <td>{{data.answer}}</td>   
                        <td><span class="badge badge-primary ng-binding ng-scope" ng-if="data.status == 'Y'"><?=lang('lang_active');?></span>
                        <span class="badge badge-danger ng-binding ng-scope" ng-if="data.status == 'N'"><?=lang('lang_inactive');?></span> 
                        </td> 
                        <td class="text-center">
                        <ul class="icons-list">
                          <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                              <i class="icon-menu9"></i>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-right">
                              <li><a href="<?=base_url();?>editfaq/{{data.id}}"><i class="icon-pencil7"></i> <?=lang('lang_Edit');?> </a></li>
                              <li> <a ng-if="data.status=='Y'" class="dropdown-item" href="<?=base_url();?>faqupactive/{{data.id}}/N"><i class="mdi mdi-chevron-down"></i>  <?=lang('lang_inactive');?> </a> </li>
                              <li> <a ng-if="data.status=='N'" class="dropdown-item" href="<?=base_url();?>faqupactive/{{data.id}}/Y"><i class="mdi mdi-chevron-down"></i>  <?=lang('lang_active');?> </a> </li>
                            </ul>
                          </li>
                        </ul>
                      </td>
                      
                     
                      </tr>

              </tbody>
            </table>
            
             
           </div>
           <button ng-hide="listArray.length==totalCount" class="btn btn-info" ng-click="loadMore_faq(count=count+1,0);" ng-init="count=1"><?=lang('lang_Load_More');?></button>

            <hr>
         </div>
       </div>
       <!-- /basic responsive table --> 
         <?php $this->load->view('include/footer'); ?>
       
     </div>
     <!-- /content area -->
   
   </div>
   <!-- /main content -->

 </div>
 <!-- /page content -->

</div>
<div style="display:none;">
 <table id="downloadtable">
                <thead>
                  <tr>
                    <th><?=lang('lang_SrNo');?>.</th>
                     <th><?=lang('lang_Item_Type');?></th>
                    <th><?=lang('lang_Name');?></th>
                    <th><?=lang('lang_ItemSku');?></th>
                     <th><?=lang('lang_Storage_Capacity');?></th>
                    <th><?=lang('lang_Description');?></th>
                    <!-- <th>Category</th> -->
                   
                  </tr>
                </thead>
                <tbody>
                  <tr ng-if="shipData!=0" ng-repeat="data in shipData">
                      <td>{{$index+1}}</td>
                       <td>
                         <span class="badge badge-success" ng-if="data.type=='B2B'">{{data.type}}</span>
                         <span class="badge badge-warning" ng-if="data.type=='B2C'">{{data.type}}</span>
                      </td>
                      <td>{{data.name}}</td>
                      <td>{{data.sku}}</td>
                           <td>{{data.storage_type}}</td>
                           <td>{{data.sku_size}}</td>
                      
                      <td>{{data.description}}</td>
                   
                    
                      
                    </tr>
              </tbody>
            </table>
            </div>
<div class="modal fade bd-example-modal-lg" tabindex="-1" id="showskuformviewid"  role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
     <div class="modal-header">
   
      
        <h5 class="modal-title" id="exampleModalLabel"><?=lang('lang_PrintSKUBarcode');?> </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        
       
      </div>
      
      
      <div class="modal-body">
     <form novalidate ng-submit="myForm.$valid && GetGenratebarcode()" name="myForm" >
     <div class="alert alert-warning" ng-if="message" >{{message}}</div>
     <table class="table table-bordered table-hover" style="width: 100%;">
                  <!-- width="170px;" height="200px;" -->
                <tbody >
                    
                      <tr style="width: 80%;">
                       
                       <td>
                          <div class="form-group" ><strong><?=lang('lang_Seller');?>:</strong>
                                  <select id="seller_id" name="seller_id" ng-model="filterData.seller_id" class="form-control"> 
                                  <option value=""><?=lang('lang_SelectSeller');?></option>
                                  <option ng-repeat="sdata in showdropsellerArr"  value="{{sdata.id}}">{{sdata.name}}</option>
                                  </select>
                                  <span class="error" ng-show="myForm.seller_id.$error.required"> <?=lang('lang_PleaseSelectSeller');?> </span>
                            </div>
                        </td>
                         
                         
                       
                       <td>
                          <div class="form-group" ><strong><?=lang('lang_QTY');?>:</strong>
                                  <input type="text" id="sqty" name="sqty" ng-model="filterData.sqty" class="form-control"> 
                                  <span class="error" ng-show="myForm.sqty.$error.required"> <?=lang('lang_Please_Enter_QTY');?> </span>
                            </div>
                        </td>
                       
                        
                        <td><button type="button" class="btn btn-success" ng-click="myForm.$valid && GetGenratebarcode()" style="margin-left: 7%"><?=lang('lang_Generate');?></button></td>
                      
                        
                       
                      </tr>
                   
                    
                </tbody>
                </table>
                <a onclick="printPage('block1');" ><i class="fa fa-print pull-right" style="font-size: 40px;color:#999;" ng-show="tableshow"></i></a>
                 <table class="table table-striped table-hover table-bordered dataTable bg-*" id="printTable" ng-show="tableshow">
                <thead>
                  <tr>
                    <th><?=lang('lang_SrNo');?>.</th>
                    <th> <?=lang('lang_Barcode');?></th>
                  
                    
                    
                  
                  </tr>
                </thead>
                <tbody>
                <tr ng-repeat="data2 in showbarcode">
                
                <td>{{$index+1}}</td>
                 <td><img src="{{data2.barcode}}"/><br>
                  {{data2.sku}}</td>
                
                
                
                 
                </tr>
                </tbody>
                </table>
                <br>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?=lang('lang_Close');?></button>
      
      </div>
         </form>            
    </div>
    </div>
  </div>
</div>
<!-- /page container -->

<script>
 



var tableToExcel = (function() {
  var uri = 'data:application/vnd.ms-excel;base64,'
    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
  return function(table, name) {
    if (!table.nodeType) table = document.getElementById(table)
    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
    var blob = new Blob([format(template, ctx)]);
  var blobURL = window.URL.createObjectURL(blob);
    return blobURL;
  }
})()

$("#btnExport").click(function () {
    var todaysDate = 'Items Table '+ new Date();
    var blobURL = tableToExcel('downloadtable', 'test_table');
    $(this).attr('download',todaysDate+'.xls')
    $(this).attr('href',blobURL);
});
function printPage()
{
   var divToPrint = document.getElementById('printTable');
    var htmlToPrint = '' +
        '<style type="text/css">' +
		'table {'+
		 'border:1px solid #000;' +
		 '}'+
		 'table th, table td {' +
       
        'width:1200px' +
        '}' +
        'table th, table td {' +
       
        'padding:8px;' +
        '}' +
		'table th {' +
		'padding-top: 12px;'+
		'padding-bottom: 12px;'+
		' text-align: left;'+
       
        'padding:0.5em;' +
        '}' +
        '</style>';
    htmlToPrint += divToPrint.outerHTML;
    newWin = window.open("");
    newWin.document.write(htmlToPrint);
    newWin.print();
    newWin.close();
}
</script>

</body>
</html>