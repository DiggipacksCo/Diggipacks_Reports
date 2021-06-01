<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png');?>" type="image/x-icon">
<title>Inventory</title>
<?php $this->load->view('include/file'); ?>

<style>
  .loader,
        .loader:after {
            border-radius: 50%;
            width: 10em;
            height: 10em;
        }
        .loader {            
            margin: 60px auto;
            font-size: 10px;
            position: relative;
            text-indent: -9999em;
            border-top: 1.1em solid rgba(255, 255, 255, 0.2);
            border-right: 1.1em solid rgba(255, 255, 255, 0.2);
            border-bottom: 1.1em solid rgba(255, 255, 255, 0.2);
            border-left: 1.1em solid #ffffff;
            -webkit-transform: translateZ(0);
            -ms-transform: translateZ(0);
            transform: translateZ(0);
            -webkit-animation: load8 1.1s infinite linear;
            animation: load8 1.1s infinite linear;
        }
        @-webkit-keyframes load8 {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
        @keyframes load8 {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
        #loadingDiv {
            position:absolute;;
            top:0;
            left:0;
            width:100%;
            height:100%;
            background-color:#000;
        }
</style>
</head>

<body ng-app="fulfill" >



<!-- Page container -->
<div class="page-container" ng-controller="pickListView" ng-init="loadMore(1,0);">

<!-- Page content -->
<div class="page-content" style="display:none;">




<!-- Main content -->
<div class="content-wrapper" >
<!--style="background-color: black;"-->




<!-- Content area -->
<div class="content" ng-init="filterData.pickupId='<?= $pickupId?>'" >
<!--style="background-color: red;"-->





<!--style="background-color: green;"-->
<table class="table table-striped table-hover table-bordered dataTable bg-*" id="printTable" style="width:100%;">
<thead>
<tr>
<th>Sr.No.</th>
<th>AWB No.</th>
   <th>Origin</th>  
    <th>Destination</th>
     <th>Receiver Mobile</th>
      <th>Item Sku Detail   <table class="table"><thead>
      <tr>
        <th>SKU</th>
        <th>Qty</th>
        <th>COD (<?= site_configTable("default_currency"); ?>)</th>
           
      </tr>
    </thead></table></th>
 <th>Pickup Status</th>  
         <th>Pickup Date</th>
     <th>Picker</th>  
          <th>Packed By</th>
           <th>Pack Date</th> 
         
          



</tr>
</thead>
    <tr ng-if='shipData!=0' ng-repeat="data in shipData"> 
    
        <td>{{$index+1}}</td>
        <td>{{data.slip_no}}</td>
        <td>{{data.origin}}</td>
        <td>{{data.destination}}</td>
      
          <td>{{data.reciever_phone}}</td>
        <td>
            <table class="table table-striped table-hover table-bordered dataTable bg-*">
   
    <tbody>
      <tr ng-repeat="data1 in data.sku">
          <td ><span class="label label-primary">{{data1.sku}}</span></td>
        <td><span class="label label-info">{{data1.piece}}</span></td>
       
                <td><span class="label label-danger">{{data1.cod}}</span></td>
      </tr>
                </tbody>
            </table>
            
            </td>
        
       <td><span ng-if="data.pickup_status=='Yes'" class="label label-success">{{data.pickup_status}}</span>
          <span ng-if="data.pickup_status=='No'" class="label label-danger">{{data.pickup_status}}</span>
          </td>
            <td><span ng-if="data.pickup_status=='Yes'" class="label label-success">{{data.pickupDate}}</span>
          <span ng-if="data.pickup_status=='No'" class="label label-danger">N/A</span>
          </td>
           <td><span ng-if="data.assigned_to!=null" class="label label-success">{{data.assigned_to}}</span>
          <span ng-if="data.pickup_status==null" class="label label-danger">N/A</span>
          </td>
          
           <td><span ng-if="data.packedBy!=null" class="label label-success">{{data.packedBy}}</span>
          <span ng-if="data.packedBy==null" class="label label-danger">N/A</span>
          </td>
           <td><span ng-if="data.packedBy!=null" class="label label-success">{{data.packDate}}</span>
          <span ng-if="data.packedBy==null" class="label label-danger">N/A</span>
          </td>
    
      
    </tr>
    
</table>


<!-- /basic responsive table -->
<?php $this->load->view('include/footer'); ?>
 
</div>
<!-- /content area -->


</div>
<!-- /main content -->


</div>



</div>
<script>
$('body').append('<div style="" id="loadingDiv"><div class="loader">Loading...</div></div>');
$(window).on('load', function(){
  setTimeout(removeLoader(), 3000); //wait for page load PLUS two seconds.
});
function removeLoader(){
    $( "#loadingDiv" ).fadeOut(2000, function() {
		printPageview()
      // fadeOut complete. Remove the loading div
      $( "#loadingDiv" ).remove(); //makes page more lightweight 
  });  
}


function printPageview()
{
   var divToPrint = document.getElementById('printTable');
    var htmlToPrint = '' +
        '<style type="text/css">' +
		 'table th, table td {' +
        'border:1px solid #000;' +
        'width:1200px' +
        '}' +
        'table th, table td {' +
        'border:1px solid #000;' +
        'padding:8px;' +
        '}' +
		'table th {' +
		'padding-top: 12px;'+
		'padding-bottom: 12px;'+
		' text-align: left;'+
        'border:1px solid #000;' +
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

<!-- /page container -->

</body>
</html>
