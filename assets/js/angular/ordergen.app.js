var app = angular.module('AppOrderGen', [])

.controller('OrderGenCRTL', function($scope,$http,$window,Excel,$timeout,$location) {
  $scope.baseUrl = new $window.URL($location.absUrl()).origin;
  $scope.filterData={};
  $scope.shipData=[];
  $scope.Items=[];
  $scope.errorBackorder={};
  $scope.dropexport=[]; 
  $scope.loadershow=false;
  $scope.ShipmentEditArr={};
  $scope.filterData.s_type="AWB";
   $scope.shipDataOLD=[];
    $scope.dropshort={};
  //$scope.filterData.seller="";
     $scope.dropexport_checkbox=[];
     $scope.showCity = function ()
     {

      
         $http({
             url: $scope.baseUrl+ "/Country/showCity",
             method: "POST",
             data: $scope.filterData,
             headers: {'Content-Type': 'application/json'}

         }).then(function (response) {

              console.log(response);
             $scope.citylist = response.data;
             $('.selectpicker').selectpicker('refresh');

         })

     }
 $scope.loadMore=function(page_no,reset)
    {
       disableScreen(1);
    $scope.loadershow=true; 
     //alert($scope.loadershow);
   // console.log(page_no);    
   // console.log($scope.selectedData);    
     $scope.filterData.page_no=page_no;
     $scope.filterData.status=11;
      if(reset==1)
      {
      $scope.shipData=[];
	   $scope.shipDataOLD=[];
      $scope.Items=[]; 
      }
  
    $http({
		url: "Shipment/filter_orderGen",
		method: "POST",
		data:$scope.filterData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) { 
              disableScreen(0);
  $scope.loadershow=false;
           //console.log(response);
          // alert(response.data.result.length);
       //   console.log(response);
            $scope.dropshort=response.data.dropshort;
		 $scope.totalCount=response.data.count;
		  $scope.dropexport=response.data.dropexport; 
                    $scope.dropexport_checkbox=response.data.dropexport_checkbox; 
			 if(response.data.result.length > 0){
                        angular.forEach(response.data.result,function(value){
                           
                          $scope.dataIndex=  $scope.shipData.findIndex( record => record.slip_no ===value.slip_no);
						
                        if( $scope.dataIndex!=-1) 
                        {
						    

                       
                            $scope.shipData[$scope.dataIndex].skuData.push({'sku':value.sku,'piece':value.piece,'cod':value.cod,'wh_id':value.whid,'sku_id':value.sku_id,'d_id':value.d_id,'free_sku':value.free_sku});   //scope.shipData[$scope.dataIndex].piece=parseInt($scope.shipData[$scope.dataIndex].piece)+parseInt(value.piece);    
                        }
                        else
                        {
                        
                         $scope.shipData.push(value);
                         $scope.dataIndex=  $scope.shipData.findIndex( record => record.slip_no ===value.slip_no);   
                         $scope.shipData[$scope.dataIndex].skuData=[]; 
                        $scope.shipData[$scope.dataIndex].skuData.push({'sku':value.sku,'piece':value.piece,'cod':value.cod,'wh_id':value.whid,'sku_id':value.sku_id,'d_id':value.d_id,'free_sku':value.free_sku});   
					      }
						  
						  $scope.dataIndex1=  $scope.shipDataOLD.findIndex( record => record.slip_no ===value.slip_no);  
						   if( $scope.dataIndex1!=-1) 
                        {
						    $scope.shipDataOLD[$scope.dataIndex1].skuData1.push({'sku':value.sku,'piece':value.piece,'cod':value.cod,'wh_id':value.whid,'sku_id':value.sku_id,'d_id':value.d_id,'free_sku':value.free_sku});   //scope.shipData[$scope.dataIndex].piece=parseInt($scope.shipData[$scope.dataIndex].piece)+parseInt(value.piece);    

                       
            
                        }
                        else
                        {
                        
 						 $scope.shipDataOLD.push(value);
                        $scope.dataIndex1=  $scope.shipDataOLD.findIndex( record => record.slip_no ===value.slip_no);   
                         $scope.shipDataOLD[$scope.dataIndex1].skuData1=[]; 
                        $scope.shipDataOLD[$scope.dataIndex1].skuData1.push({'sku':value.sku,'piece':value.piece,'cod':value.cod,'wh_id':value.whid,'sku_id':value.sku_id,'d_id':value.d_id,'free_sku':value.free_sku});   
						
						
                        }
                        
                      
						  
                           //console.log(value.slip_no +'//'+$scope.dataIndex)  
                               
                                //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });
               /// console.log( $scope.shipData)
                 //$scope.$broadcast('scroll.infiniteScrollComplete');
                    }else{$scope.nodata=true
                    }					
			 
			 
		
  })  

  
        
    };    
    $scope.boxval=0;
    $scope.selectedAll=false;
     $scope.selectAll = function(val) {
        //alert(val);
         
         
       // console.log("sssssss");
        var newval=val-1;
      angular.forEach($scope.shipData, function(data,key) {
         // console.log(key+"======="+newval);
          if(key<=newval)
          {
              
               //console.log(key+"======="+newval);
               //console.log($scope.selectedAll);
        data.Selected = true;
        
        $scope.Items.push(data.slip_no);
        
          }
          else
          {
                //console.log($scope.selectedAll);
        data.Selected = $scope.selectedAll;
        if($scope.selectedAll==true)  
        $scope.Items.push(data.slip_no);
          else
        $scope.Items=[];   
          }
              
      });
         
         
    };
	
	$scope.GetcheckOrderDeleteStatus=function(slip_no)
	{
		disableScreen(1);
		 $scope.loadershow=true;
		$http({
		url: "Shipment/GetcheckOrderDeleteStatus",
		method: "POST",
		data:{slip_no:slip_no},
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) { 
	disableScreen(0);
		 $scope.loadershow=false; 
	if(response.data=="succ")
	{
		alert("successfuly Deleted");
		$window.location.reload();
	}
	else
	{
		alert("successfuly Deleted");
		$window.location.reload();
	}
	});
	}
		$scope.countryArr={};
		$scope.warehouseArr={};
	$scope.GetEditshipemtPopProcee=function(id)
	{
		console.log('bimal');
		 $scope.shipmentOLD=$scope.shipDataOLD[id].skuData1;
		 $scope.shipdataNew=$scope.shipData[id];
		 // var shipdata=$scope.shipData[id];
		//console.log(shipdata);
		disableScreen(1);
		 $scope.loadershow=true; 
		$http({
		url: "Shipment/GetestinationDropData",
		method: "POST",
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) { 
		disableScreen(0);
		 $scope.loadershow=false; 
	//	console.log(shipdata);
		$scope.ShipmentEditArr=$scope.shipdataNew;
		$scope.countryArr=response.data.city;
                $scope.warehouseArr=response.data.warehouse;
		$("#UpdateShipemtData").modal({ backdrop: 'static',
    keyboard: false}) 
	});
	
	}
	
	$scope.GetremoverowsskuId=function(d_id,index,sku,slip_no)
	{
		console.log(index);
		$http({
		url: "Shipment/GetRemoveDimationSku",
		method: "POST",
		data:{d_id:d_id,sku:sku,slip_no:slip_no},
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) { 
	
	$scope.ShipmentEditArr.skuData.splice( index, 1);
	});
		
		 //
   
		
	}
	 $scope.sku = null;
	$scope.GetAddNewrowsSku=function()
	{
		  var temarray = [];
		    temarray.sku = $scope.sku;
              //  movie.director = $scope.director;
	//	$scope.temarray.sku="";
		//$scope.temarray.cod="";
		//$scope.temarray.cod="";
		//$scope.ShipmentEditArr.skuData=[];
		$scope.ShipmentEditArr.skuData.push($scope.temarray);
		 
		
	}
	$scope.GetCheckDuplicationSku=function(sku,id)
	{
		//console.log($scope.ShipmentEditArr);
		console.log($scope.shipmentOLD);
		  $scope.ShipmentEditArr.skuData.forEach(function(value, key) {
			  if(key!=id)
			  {
				  if(value.sku==sku)
				  {
					 if($scope.ShipmentEditArr.skuData[id].piece!=null)
					 {
					  $scope.ShipmentEditArr.skuData[id].piece=  parseInt($scope.ShipmentEditArr.skuData[id].piece)+  parseInt($scope.ShipmentEditArr.skuData[key].piece);
					  $scope.ShipmentEditArr.skuData[id].cod=  parseInt($scope.ShipmentEditArr.skuData[id].cod)+  parseInt($scope.ShipmentEditArr.skuData[key].cod);
					  $scope.ShipmentEditArr.skuData.splice( key, 1);
					 }
				  }
			  }
		  });
		
	}
	$scope.CloseModelPage=function()
	{
		$("#UpdateShipemtData").modal('hide');
		$scope.loadMore(1,1);
		$scope.ShipmentEditArr={};
	}
	$scope.GetUpdateShipmetTableData=function()
	{
        		disableScreen(1);
        		 $scope.loadershow=true; 
        			$http({
        		url: "Shipment/GetUpdateShipmentDataPage",
        		data:$scope.ShipmentEditArr,
        		method: "POST",
        		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        		
        	}).then(function (response) { 
        	disableScreen(0);
        		 $scope.loadershow=false; 
        	 if(response.data.status=="succ")
                	{
                		alert("Successfully Updated");
                		$scope.CloseModelPage();
                	}
                	else if(response.data.status=="booking_id")
                	{
                		alert("this booking id already exits");
                	}
                	else if(response.data.status.sku=="booking_id")
                	{
                		alert(response.data.status.sku);
                	}
                	else
                	{
                		alert("all field are required");
                	}
        	 });
	}

    // use the array "every" function to test if ALL items are checked
    $scope.checkIfAllSelected = function() {
      $scope.selectedAll = $scope.shipData.every(function(data) {
        return data.Selected == true
      })
        
    };
    $scope.checkDeleteArr=[];
    
    $scope.removemultipleorder=function()
    {
        
    if($scope.Items.length>0)
    {
       $http({
		url: "Shipment/GetremoveMultipleOrders",
		method: "POST",
		data:{slipData:$scope.Items},
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
                
	}).then(function (response) {
            if(response.data=="succ")
	{
		alert("successfuly Deleted");
		$window.location.reload();
	}
	else
	{
		alert("successfuly Deleted");
		$window.location.reload();
	}
            
        });
    }
    else
    {
        alert("please select atleat one order");
    }
    };
    $scope.CreateOrderCheck=function()
    {
        disableScreen(1);
       $scope.loadershow=true; 
        $scope.shipData_new = $scope.shipData.filter(function(item) 
        {
            return $scope.Items.includes(item.slip_no); 
        })
      
      if($scope.shipData_new.length==0)
        {
            alert('Please select Orders to Create Order!');}
        else
        {
  
   

      //console.log($scope.shipData_new); 
      //console.log($scope.Items); 
            
        $http({
		url: "Shipment/CreateGenratedOrderCheck",
		method: "POST",
		data:{
            listData:$scope.shipData_new,
            slipData:$scope.Items   
			
        },
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
             disableScreen(0);
   $scope.loadershow=false; 
		 $scope.shipData=[]; 
        console.log(response);
		$scope.errorBackorder=response.data;
		$scope.loadMore(1,0);
        //$window.location.reload();
         //$window.location.replace('pickupList');
        }) 
        
       
        }
}
  
 $scope.shipData1=[]; 
    
	 $scope.exportExcel=function()
    {     
    console.log($scope.filterData.exportlimit);   
	 if($scope.filterData.exportlimit>0)
   {
	   $scope.filterData.status=11;
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
.directive('ngConfirmClick', [
  function(){
    return {
      priority: -1,
      restrict: 'A',
      link: function(scope, element, attrs){
        element.bind('click', function(e){
          var message = attrs.ngConfirmClick;
          // confirm() requires jQuery
          if(message && !confirm(message)){
            e.stopImmediatePropagation();
            e.preventDefault();
          }
        });
      }
    }
  }
])
.directive('stringToNumber', function() {
  return {
    require: 'ngModel',
    link: function(scope, element, attrs, ngModel) {
      ngModel.$parsers.push(function(value) {
        return '' + value;
      });
      ngModel.$formatters.push(function(value) {
        return parseFloat(value);
      });
    }
  };
})
.directive('selectWatcher', function ($timeout) {
  return {
      link: function (scope, element, attr) {
          var last = attr.last;
          if (last === "true") {
              $timeout(function () {
                  $(element).parent().selectpicker('val', 'any');
                  $(element).parent().selectpicker('refresh');
              });
          }
      }
  };
});