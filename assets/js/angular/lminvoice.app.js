var app = angular.module('lmInvoice', [])
.config(['$compileProvider', function($compileProvider) {
  $compileProvider.aHrefSanitizationWhitelist(/^\s*(https?|local|data|chrome-extension):/);
 $compileProvider.imgSrcSanitizationWhitelist(/^\s*(https?|local|data|chrome-extension):/);

 }])
.controller('createInvoice', function($scope,$http,$window,Excel,$timeout) {
  
  $scope.filterData={};
  $scope.shipData=[];
  $scope.Items=[];
  $scope.errorBackorder={};
  $scope.dropexport=[]; 
  

  $scope.checkIncvoice=function()
  {     
       // console.log($scope.filterData);   
      
      
        /* disableScreen(1);
        $scope.loadershow=true; */
          $http({
        url: "LastmileInvoice/checkInvoice",
        method: "POST",
        data:$scope.filterData, 
        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        
      }).then(function (response) {
        
        console.log(response.data);
        $scope.returnData=response.data;
        // var saving = document.createElement('a');

        // saving.href = 'data:attachment/csv,' + encodeURIComponent(csv(response.data));
        // saving.download = 'invoice.csv';
        // saving.click();
        });
      
  }
$scope.downloadexl=function(data,fileName){


  var saving = document.createElement('a');

        saving.href = 'data:attachment/csv,' + encodeURIComponent(CommanExcl(data,fileName));
        saving.download = fileName+'_invoice.csv';
        saving.click();
}

function CommanExcl (arr,fileName) {

    var ret = [];
    ret.push('"' + fileName+ '"');
    for (var i = 0, len = arr.length; i < len; i++) {
        var line = [];
       
          
                line.push('"' + arr[i] + '"');
           
        
        ret.push(line.join(','));
    }
    return ret.join('\n');
}
$scope.lines=0;

$scope.createInvoice=function()
{
  if (confirm("Are you sure?")) {
   
 
  console.log($scope.returnData.Available);
  console.log($scope.filterData.seller);

  $http({
    url: "LastmileInvoice/CreateInvoiceCalulation",
    method: "POST",
    data:{cust_id:$scope.filterData.seller, slip_no:$scope.returnData.Available},
    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
    
  }).then(function (response) {
    
   $scope.returnData= response.data;
   if($scope.returnData.price_zero!=undefined)
   $scope.message="Invoice Genearted Successfully";
    
    });
  }
}
$scope.countData=function()
{
  

  var items = $scope.filterData.slip_no.split('\n');
  $scope.lines = 0;
  for(var no=0;no<items.length;no++){
    $scope.lines += Math.ceil(items[no].length/40);    }
      
}
	 $scope.exportExcel=function()
    {     
    console.log($scope.filterData.exportlimit);   
	 if($scope.filterData.exportlimit>0)
   {
	   /* disableScreen(1);
		 $scope.loadershow=true; */
      $http({
		url: "Shipment/exportdispatchExcel",
		method: "POST",
		data:$scope.filterData, 
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
		
		  console.log(response.data.file);
        
         var d = new Date();  
    var $a = $("<a>");
    $a.attr("href",response.data.file);
    $("body").append($a);
    $a.attr("Backorders",d+"orders.xls");
    $a[0].click();
    $a.remove();
    
       /*disableScreen(0);
		 $scope.loadershow=false; */   
		});
   }
    else
  alert("please select export limit");
    }
	
  $scope.exportExcel1=function()
    {
     console.log($scope.exportlimit);
      $http({
		url: "Shipment/backoredrexcel",
		method: "POST",
		data:$scope.exportlimit, 
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {  
      console.log(response);
        $scope.totalCount=response.data.count;
		//  $scope.dropexport=response.data.dropexport; 
			 if(response.data.result.length > 0){
                        angular.forEach(response.data.result,function(value){
                           
                          $scope.dataIndex=  $scope.shipData1.findIndex( record => record.slip_no ===value.slip_no);
                        if( $scope.dataIndex!=-1) 
                        {
                       
                            $scope.shipData1[$scope.dataIndex].skuData.push({'sku':value.sku,'piece':value.piece,'cod':value.cod});   //scope.shipData[$scope.dataIndex].piece=parseInt($scope.shipData[$scope.dataIndex].piece)+parseInt(value.piece);    
                        }
                        else
                        {
                        
                         $scope.shipData1.push(value);
                         $scope.dataIndex=  $scope.shipData1.findIndex( record => record.slip_no ===value.slip_no);   
                         $scope.shipData1[$scope.dataIndex].skuData=[]; 
                        $scope.shipData1[$scope.dataIndex].skuData.push({'sku':value.sku,'piece':value.piece,'cod':value.cod});   
                        }
                           //console.log(value.slip_no +'//'+$scope.dataIndex)  
                               
                                //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });
               /// console.log( $scope.shipData)
                 //$scope.$broadcast('scroll.infiniteScrollComplete');
                    }else{$scope.nodata=true
                    }	
    
   
      });
    }
	
	$scope.exportToExcelPaymentReport = function (testTable_new) { // ex: '#my-table'
       //alert("Hi");  
          $timeout(function () {
                  var exportHref = Excel.tableToExcel(downloadtable , 'sheet name');
                location.href = exportHref; }, 1000); // trigger download 
        }   
		
		
})


.factory('Excel',function($window){
        var uri='data:application/vnd.ms-excel;base64,',
            template='<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
            base64=function(s){return $window.btoa(unescape(encodeURIComponent(s)));},
            format=function(s,c){return s.replace(/{(\w+)}/g,function(m,p){return c[p];})};
        return {
            tableToExcel:function(tableId,worksheetName){
                var table=$(tableId),
                    ctx={worksheet:worksheetName,table:table.html()},
                    href=uri+base64(format(template,ctx));
                return href;
            }
        };
    })
    
	
	
.directive('checkList', function() {
  return {
    scope: {
      list: '=checkList',
      value: '@'
    },
    link: function(scope, elem, attrs) {
      var handler = function(setup) {
        var checked = elem.prop('checked');
        var index = scope.list.indexOf(scope.value);

        if (checked && index == -1) {
          if (setup) elem.prop('checked', false);
          else scope.list.push(scope.value);
        } else if (!checked && index != -1) {
          if (setup) elem.prop('checked', true);
          else scope.list.splice(index, 1);
        }
      };
      
      var setupHandler = handler.bind(null, true);
      var changeHandler = handler.bind(null, false);
            
      elem.on('change', function() {
        scope.$apply(changeHandler);
      });
      scope.$watch('list', setupHandler, true);
    }
  };
})



.controller('bulkmanagementCtrl', function ($scope,$http,$window,Excel,$timeout) {
   
  $scope.codlistArray=[];
$scope.filterData = {};
$scope.filterData.searchfield ="";
$scope.totalCount=0;
$scope.routeArray={};
$scope.editstaffArray={};
$scope.payableinvoicelistArray=[];
$scope.payablelistArray=[];
$scope.editcodlistArray={};
$scope.invoiceArray={};
$scope.scan={};
$scope.scan.counterval=0;  
 $scope.scan.awb_no="";
 $scope.SearArr={};
 
 
 angular.element(document).ready(function () {
   $(".select2").select2();
 $( "#datepicker1" ).datepicker({ changeMonth: true,changeYear: true,dateFormat: 'dd-mm-yy'});
 $( "#datepicker2" ).datepicker({ changeMonth: true,changeYear: true,dateFormat: 'dd-mm-yy'});
 $( "#datepicker3" ).datepicker({ changeMonth: true,changeYear: true,dateFormat: 'dd-mm-yy'});
 $( "#datepicker4" ).datepicker({ changeMonth: true,changeYear: true,dateFormat: 'dd-mm-yy'});
 $( "#datepicker5" ).datepicker({ changeMonth: true,changeYear: true,dateFormat: 'dd-mm-yy'});
 $( "#datepicker6" ).datepicker({ changeMonth: true,changeYear: true,dateFormat: 'dd-mm-yy'});
 $( "#datepicker7" ).datepicker({ changeMonth: true,changeYear: true,dateFormat: 'dd-mm-yy'});
 $( "#datepicker8" ).datepicker({ changeMonth: true,changeYear: true,dateFormat: 'dd-mm-yy'});
 
  });

$scope.modelClose=function (id)
{
 $('#'+id).modal('hide');
 
}
 $scope.getCODlist = function (page_no,reset) {
  // alert("sssss");
  $scope.SearArr.page_no=page_no;
  console.log($scope.SearArr);
  
  
    if(reset==1)
    {
    $scope.codlistArray=[];
    }
     
     $http({
      url: "LastmileInvoice/showCodInvoiceData",
      method: "POST",
      data:$scope.SearArr, 
      headers: {'Content-Type': 'application/x-www-form-urlencoded'}
      
    }).
     then(function (results) {
       $scope.totalCount=results.data.count;
      console.log(results);
       if(results.data.result.length > 0)
       {
                      angular.forEach(results.result,function(value)
          {
                        $scope.codlistArray.push(value);

                      });
                  }
        else
        {$scope.nodata=true
                  }
    });
  };
 







   $scope.getPayableCODlist = function (page_no,reset) {
  $scope.SearArr.page_no=page_no;
    if(reset==1)
    {
    $scope.payableinvoicelistArray=[];
    }
   
     $http({
      url: "LastmileInvoice/showPayableInvoiceData",
      method: "POST",
      data:$scope.SearArr, 
      headers: {'Content-Type': 'application/x-www-form-urlencoded'}        
    }).then(function (results) {
       $scope.totalCount=results.data.count;      
      console.log(results.data);   
       if(results.data.result.length > 0)
       {
                      angular.forEach(results.data.result,function(value)
          {
                        $scope.payableinvoicelistArray.push(value);

                      });
                  }
        else
        {$scope.nodata=true
                  }
    });
  };
       $scope.dailyinvoicedata=[];
       
              
              
              $scope.multiinvoicedata = []
              $scope.error_msg = "";
              $scope.success_msg = "";
              
   
             
              
   
   
   $scope.getPayablelist = function (page_no,reset) {
  $scope.filterData.page_no=page_no;
    if(reset==1)
    {
    $scope.payablelistArray=[];
    }
    
     $http({
      url: "LastmileInvoice/showPayableData",
      method: "POST",
      data:$scope.filterData, 
      headers: {'Content-Type': 'application/x-www-form-urlencoded'}
      
    }).then(function (results) {
       $scope.totalCount=results.count;
      console.log(results);
       if(results.data.result.length > 0)
       {
                      angular.forEach(results.result,function(value)
          {
                        $scope.payablelistArray.push(value);

                      });
                  }
        else
        {$scope.nodata=true
                  }
    });
  };


$scope.Getpopoprncustdetais=function(pid,openid,type)
{


if(type=='one')
 
 
 
 $http({
  url: "LastmileInvoice/ShowEditpay",
  method: "POST",
  data: {id:pid}, 
  headers: {'Content-Type': 'application/x-www-form-urlencoded'}
  
}).then(function (results) {
    console.log(results);
       $scope.editcodlistArray=results.data;
      });
  else
  {
    
  }
    $(openid).modal('show');
}
$scope.invoiceArrayAll={};
$scope.total_cod_amount=0;

$scope.invoice_print=function(pid)
{
   console.log(pid);
    
  $http({
    url: "LastmileInvoice/ShowInvoice",
    method: "POST",
    data:{pid:$stateParams.printid}, 
    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
    
  }).then(function (results) {
    console.log(results);
    $scope.invoiceArrayAll=results.data.allarray;
    $scope.invoiceArray.pid= $stateParams.pid;
    $scope.invoiceArray=results.allarray[0];
    $scope.total_cod_amount=results.total_cod_amount;

      });
};

$scope.GetupdatePayment=function(arry)
{

  
  $http({
    url: "LastmileInvoice/PaymentConfirmUpdaye",
    method: "POST",
    data:$scope.editcodlistArray, 
    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
    
  }).then(function (results) {
    console.log(results);
      if (results.data== 'true') {
      // Data.toast(results);
      //$window.location.reload();
      
          }
    else
    {
      alert("try again");
    //$scope.errormess=results.error;
    }
    

      });
}

$scope.payableinvoice_print=function(pid)
{
  
  $http({
    url: "LastmileInvoice/Showpayableinvoice",
    method: "POST",
    data:{pid:$stateParams.pid}, 
    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
    
  }).then(function (results) {
    console.log(results);
    $scope.invoiceArray=results.data;
    $scope.invoiceArray.pid= $stateParams.pid;

      });
};


$scope.payablecod_print=function(pid)
{
   console.log(pid);

  
  $http({
    url: "LastmileInvoice/ShowpayableCODinvoice",
    method: "POST",
    data:{pid:$stateParams.pid}, 
    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
    
  }).then(function (results) {
    console.log(results);
    $scope.invoiceArray=results.data;
    $scope.invoiceArray.pid= $stateParams.pid;

      });
};


$scope.CustomerDropdata={};
 $scope.GetcustomerData=function()
 {
   //alert("sssss");
   $http({
    url: "LastmileInvoice/GetcustomerShowdata",
    method: "POST",
    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
    
  }).then(function (results) {
    console.log(results);
    
    $scope.CustomerDropdata=results.data;
  

      });
 }
 $scope.staffDropdata={};
 $scope.GetstaffDropData=function()
 {
   //alert("sssss");
   $http({
    url: "LastmileInvoice/GetstaffDropData",
    method: "POST",
    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
    
  }).then(function (results) {
    console.log(results);
    
    $scope.staffDropdata=results.data;
  

      });
 }
 
 $scope.GetcheckCounter=function()
 {
  $scope.scan.counterval=$scope.scan.counterval+1;
 }
$scope.WarninsArr={};
function csv(arr) {
  var ret = [];
 // ret.push('"' + Object.keys(arr[0]).join('","') + '"');
  for (var i = 0, len = arr.length; i < len; i++) {
      var line = [];
      
         
              line.push('"' + arr[i] + '"');
          
      
      ret.push(line.join(','));
  }
  return ret.join('\n');
}

$scope.GetCreateInvocieAwbfinal=function()
 {  $('#myModal').modal('hide'); 
  // alert("sss");
   if($scope.scan.awb_no!="")
   {
    // $scope.WarninsArr.empty="";
   
      
      $http({
        url: "LastmileInvoice/GetCreateBultINvoiceDataAdd",
        method: "POST",
        data:$scope.scan, 
        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        
      }).then(function (results) {
      console.log(results);        
    console.log(results.returnArr.invoice_present);
    
    if( results.returnArr.success_update!=undefined)
    {
    
     $scope.success_update= encodeURIComponent(csv(results.returnArr.success_update)); 
    }

     if( results.returnArr.invoice_present!=undefined)
    {
    
     $scope.invoice_present= encodeURIComponent(csv(results.returnArr.invoice_present)); 
    }

      if( results.returnArr.not_belong!=undefined)
    {
    
     $scope.not_belong= encodeURIComponent(csv(results.returnArr.not_belong)); 
    }
      if( results.returnArr.status_not_delivered!=undefined)
    {
    
     $scope.status_not_delivered= encodeURIComponent(csv(results.returnArr.status_not_delivered)); 
    }

     if( results.returnArr.price_issue!=undefined)
    {
    
     $scope.price_issue= encodeURIComponent(csv(results.returnArr.price_issue)); 
    }

     if( results.returnArr.cod_deliver_awb!=undefined)
    {
    
     $scope.cod_deliver_awb= encodeURIComponent(csv(results.returnArr.cod_deliver_awb)); 
    }
     if( results.returnArr.cod_return_awb!=undefined)
    {
    
     $scope.cod_return_awb= encodeURIComponent(csv(results.returnArr.cod_return_awb)); 
    }


     if( results.returnArr.cc_awb!=undefined)
    {
    
     $scope.cc_awb= encodeURIComponent(csv(results.returnArr.cc_awb)); 
    }

     if( results.returnArr.wrong_awb!=undefined)
    {
    
     $scope.wrong_awb= encodeURIComponent(csv(results.returnArr.wrong_awb)); 
    }
         

 });
  $scope.scan=[]; 
  $scope.message='Invoice Generated!'  ;
   }
   else
   {
   //  alert("sssss");
   $scope.WarninsArr.empty="Please Scan Awb No.";
   }
   
 }
 $scope.GetCreateInvocieAwb=function()
 {
  // alert("sss");
   if($scope.scan.awb_no!="")
   {
    // $scope.WarninsArr.empty="";
    
      
      $http({
        url: "LastmileInvoice/GetCreateBultINvoiceData",
        method: "POST",
        data:$scope.scan, 
        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        
      }).then(function (results) {
      console.log(results);
    console.log(results.returnArr.invoice_present);
        $('#myModal').modal('show'); 
        if( results.returnArr.success_update!=undefined)
    {
    
     $scope.success_update= encodeURIComponent(csv(results.returnArr.success_update)); 
    }

     if( results.returnArr.invoice_present!=undefined)
    {
    
     $scope.invoice_present= encodeURIComponent(csv(results.returnArr.invoice_present)); 
    }

      if( results.returnArr.not_belong!=undefined)
    {
    
     $scope.not_belong= encodeURIComponent(csv(results.returnArr.not_belong)); 
    }
      if( results.returnArr.status_not_delivered!=undefined)
    {
    
     $scope.status_not_delivered= encodeURIComponent(csv(results.returnArr.status_not_delivered)); 
    }

     if( results.returnArr.price_issue!=undefined)
    {
    
     $scope.price_issue= encodeURIComponent(csv(results.returnArr.price_issue)); 
    }

     if( results.returnArr.cod_deliver_awb!=undefined)
    {
    
     $scope.cod_deliver_awb= encodeURIComponent(csv(results.returnArr.cod_deliver_awb)); 
    }
     if( results.returnArr.cod_return_awb!=undefined)
    {
    
     $scope.cod_return_awb= encodeURIComponent(csv(results.returnArr.cod_return_awb)); 
    }


     if( results.returnArr.cc_awb!=undefined)
    {
    
     $scope.cc_awb= encodeURIComponent(csv(results.returnArr.cc_awb)); 
    }

     if( results.returnArr.wrong_awb!=undefined)
    {
    
     $scope.wrong_awb= encodeURIComponent(csv(results.returnArr.wrong_awb)); 
    }
         
  


    //$scope.CustomerDropdata=results;
      

    //$scope.WarninsArr=results.returnArr;
      });
     
   }
   else
   {
   //  alert("sssss");
   $scope.WarninsArr.empty="Please Scan Awb No.";
   }
   
 }
$scope.payableInvoice_update = function (custdata) {
  
     
      
      $http({
        url: "LastmileInvoice/payableInvoice_update",
        method: "POST",
        data:$scope.editcodlistArray, 
        headers: {'Content-Type': 'application/x-www-form-urlencoded'}        
      }).then(function (results) {
    console.log(results);
     
          if (results.data== 'true') {  
      // Data.toast(results);
      $window.location.reload();
      
          }
    else
    {
      alert("try again");
    //$scope.errormess=results.error;
    }
      });
  };

   $scope.DeletePayableInvoice = function (invoice_no) {
  alert (invoice_no); 
     
      $http({
        url: "LastmileInvoice/GetPayableInvoiceDelete",
        method: "POST",
        data:{invoice_no:invoice_no}, 
        headers: {'Content-Type': 'application/x-www-form-urlencoded'}        
      }).then(function (results) { 
    console.log(results);
    alert("Deleted Successfully");
      $state.reload(); 
                 // $state.go('payable_invoices');
      });
  };


})



.filter('reverse', function() {
  return function(items) {
    return items.slice().reverse();
  };
})

.directive('myEnter', function () {
    return function (scope, element, attrs) {
        element.bind("keydown keypress", function (event) {
            if(event.which === 13) {
                scope.$apply(function (){
                    scope.$eval(attrs.myEnter);
                });

                event.preventDefault();
            }
        });
    };
})