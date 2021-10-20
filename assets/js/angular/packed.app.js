var app = angular.module('AppPacked', [])



.controller('orderPacked', function($scope,$http,$window,Excel,$timeout,$location) {
  
  $scope.baseUrl = new $window.URL($location.absUrl()).origin;
  $scope.filterData={};
  $scope.shipData=[];
  $scope.Items=[]
 $scope.dropexport=[];
  $scope.dropshort={};
   $scope.loadershow=false; 
   $scope.filterData.s_type="AWB";

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
    console.log(page_no);    
   // console.log($scope.selectedData);    
     $scope.filterData.page_no=page_no;
     $scope.filterData.status=4;
      if(reset==1)
      {
      $scope.shipData=[];
      $scope.Items=[];
      }
  
    $http({
		url: "Shipment/filter",
		method: "POST",
		data:$scope.filterData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'} 
		
	}).then(function (response) {
           console.log(response.data.result)
            $scope.dropshort=response.data.dropshort;
		 $scope.totalCount=response.data.count;
		  $scope.dropexport=response.data.dropexport;
			 if(response.data.result.length > 0){
                        angular.forEach(response.data.result,function(value){
                           
                          $scope.dataIndex=  $scope.shipData.findIndex( record => record.slip_no ===value.slip_no);
                        if( $scope.dataIndex!=-1) 
                        {
                       
                            $scope.shipData[$scope.dataIndex].skuData.push({'sku':value.sku,'piece':value.piece,'cod':value.cod});   //scope.shipData[$scope.dataIndex].piece=parseInt($scope.shipData[$scope.dataIndex].piece)+parseInt(value.piece);    
                        }
                        else
                        {
                        
                         $scope.shipData.push(value);
                         $scope.dataIndex=  $scope.shipData.findIndex( record => record.slip_no ===value.slip_no);   
                         $scope.shipData[$scope.dataIndex].skuData=[]; 
                        $scope.shipData[$scope.dataIndex].skuData.push({'sku':value.sku,'piece':value.piece,'cod':value.cod});   
                        }
                           //console.log(value.slip_no +'//'+$scope.dataIndex)  
                               
                                //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });
                console.log( $scope.shipData)
                 //$scope.$broadcast('scroll.infiniteScrollComplete');
                    }else{$scope.nodata=true
                    }					
			 
			  disableScreen(0);
		 $scope.loadershow=false; 
		
  })  
  
        
    };    
   $scope.GetInventoryPopup = function (id) {
	     disableScreen(1);
		 $scope.loadershow=true; 	
      //alert(id); 
      //data:$scope.shipData,
      $scope.filterData.id = id;
      $http({
        url: "Shipment/filterdetail",
        method: "POST",
        data: $scope.filterData,
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }

      }).then(function (response) {
        //console.log(response)

        $scope.shipData1 = response.data;
        console.log($scope.shipData1)
        $("#deductQuantityModal").modal({
          backdrop: 'static',
          keyboard: true
        })

 disableScreen(0);
		 $scope.loadershow=false; 


      })
     	


    } 
    $scope.selectedAll=false;
     $scope.selectAll = function() {
         
         
        //console.log($scope.selectedAll); 
      angular.forEach($scope.shipData, function(data) {
        data.Selected = $scope.selectedAll;
        if($scope.selectedAll==true)  
        $scope.Items.push(data.slip_no);
          else
        $scope.Items=[];   
      });
         
         
    };

     $scope.shipData1=[];   
  $scope.exportExcel=function(downloadtable,exportlimit)
    {
    console.log($scope.exportlimit); 
	  // console.log(downloadtable);
	  $scope.filterData.status=4;
     $scope.filterData.exportlimit=$scope.exportlimit; 
      $http({
		url: "Shipment/exportExcel",
		method: "POST",
		data:$scope.filterData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
     // console.log(response);
       
		// $scope.dropexport=response.data.dropexport; 
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
                console.log( $scope.shipData1)
                 //$scope.$broadcast('scroll.infiniteScrollComplete');
                    }else{$scope.nodata=true
                    }					
			 
      
      });
    }

    // use the array "every" function to test if ALL items are checked
    $scope.checkIfAllSelected = function() {
      $scope.selectedAll = $scope.shipData.every(function(data) {
        return data.Selected == true
      })
        
    };
    
    $scope.exportPackedExcel=function()
    {
		
		disableScreen(1);
		 $scope.loadershow=true; 
		 
		 $scope.filterData.status=4;
		
   if($scope.filterData.exportlimit>0)
   {
      $http({
		url: "Shipment/exportPackedExcel",
		method: "POST",
		data:$scope.filterData,  
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
      console.log(response.data.file);
        
         var d = new Date();
    var $a = $("<a>");
    $a.attr("href",response.data.file);
    $("body").append($a);
    $a.attr("download",d+"orders.xls");
    $a[0].click();
    $a.remove();
     disableScreen(0);
		 $scope.loadershow=false; 
      
      });
   }
   else
   {
	   disableScreen(0);
		 $scope.loadershow=false; 
   alert("please select export limit");
   
   }
    }
 


 $scope.ExportData = {};
    $scope.listData1 = [];
	$scope.listData2 = {};
	$scope.listDatalist = {};  
    $scope.getExcelDetailsPacked = function () {

        $scope.listData1.exportlimit = $scope.filterData.exportlimit;
        $("#excelcolumnPacked").modal({backdrop: 'static',
            keyboard: false})
    };
	

$scope.checkAll = function () {
//alert("Hi");
        $scope.checkall = true;
    }

    $scope.uncheckAll = function () {
        $scope.checkall = false;
    }
	
	
	 $scope.checkall = false;
    $scope.checkAll = function () {
        if ($scope.checkall === false) {
            angular.forEach($scope.listData1, function (data) {
                data.checked = true;
            });
            $scope.checkall = true;
        } else {
            angular.forEach($scope.listData1, function (data) {
                data.checked = false;
            });  
            $scope.checkall = false;
        }
    };
	
	
	 $scope.transferShipPacked = function () {   

        //$scope.exportlimit
		
		$scope.listDatalist.filterData=$scope.filterData;
		$scope.listDatalist.listData2=$scope.listData2; 
		
		$scope.filterData.status=4;	  
		$scope.listDatalist.status= $scope.filterData.status;        	
		$scope.listDatalist.filterData=$scope.filterData;		
		
		$scope.listDatalist.filterData
		console.log($scope.listDatalist);  
	$http({
		url: "Shipment/getexceldataOrderPacked",     
		method: "POST",
		data:$scope.listDatalist,        
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}  
		 }).then(function (results) {
			console.log(results);  
			var $a = $("<a>");  
            $a.attr("href", results.data.file);
            $("body").append($a);
            $a.attr("download", results.data.file_name);
            $a[0].click();
            $a.remove();



        });
		$('#excelcolumnPacked').modal('hide');  
    };	
	
	
    $scope.exportToExcelpacked = function (testTable_new) { 
      //alert("Hi");   
          $timeout(function () {     
                  var exportHref = Excel.tableToExcel(downloadtable , 'sheet name');
                location.href = exportHref; }, 20000); // trigger download         
        } 
  
})
.controller('orderoutbound', function($scope,$http,$window) {
  
  $scope.filterData={};
  $scope.shipData=[];
  $scope.Items=[]
    $scope.PalletUpdate={};

  
 $scope.loadMore=function(page_no,reset)
    {
    console.log(page_no);    
   // console.log($scope.selectedData);    
     $scope.filterData.page_no=page_no;
     $scope.filterData.status=4;
      if(reset==1)
      {
      $scope.shipData=[];
      $scope.Items=[];
      }
  
    $http({

		url: "Shipment/filter_outbound",
		method: "POST",
		data:$scope.filterData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           console.log(response)
		 $scope.totalCount=response.data.count;
			 if(response.data.result.length > 0){
                        angular.forEach(response.data.result,function(value){
                           
                          $scope.dataIndex=  $scope.shipData.findIndex( record => record.slip_no ===value.slip_no);
                        if( $scope.dataIndex!=-1) 
                        {
                       
                            $scope.shipData[$scope.dataIndex].skuData.push({'sku':value.sku,'piece':value.piece,'cod':value.cod});   //scope.shipData[$scope.dataIndex].piece=parseInt($scope.shipData[$scope.dataIndex].piece)+parseInt(value.piece);    
                        }
                        else
                        {
                        
                         $scope.shipData.push(value);
                         $scope.dataIndex=  $scope.shipData.findIndex( record => record.slip_no ===value.slip_no);   
                         $scope.shipData[$scope.dataIndex].skuData=[]; 
                        $scope.shipData[$scope.dataIndex].skuData.push({'sku':value.sku,'piece':value.piece,'cod':value.cod});   
                        }
                           //console.log(value.slip_no +'//'+$scope.dataIndex)  
                               
                                //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });
                console.log( $scope.shipData)
                 //$scope.$broadcast('scroll.infiniteScrollComplete');
                    }else{$scope.nodata=true
                    }					
			 
			 
		
  })  
  
        
    };    
    $scope.GetupdateoutboundPallet=function(pallet,slip_no)
	{
		if(!slip_no)
		{
			alert("please enter pallet no");
		}
		else
		{
			$scope.PalletUpdate.pallet=pallet;
			$scope.PalletUpdate.slip_no=slip_no;
			    $http({
			url: "Shipment/GetUpdatePalletNoData",
			method: "POST",
			data:$scope.PalletUpdate,
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			
		}).then(function (response) {
		  alert("succesfully Updated");
		  location.reload();
         });
		}
	}
    $scope.selectedAll=false;
     $scope.selectAll = function() {
         
         
        //console.log($scope.selectedAll); 
      angular.forEach($scope.shipData, function(data) {
        data.Selected = $scope.selectedAll;
        if($scope.selectedAll==true)  
        $scope.Items.push(data.slip_no);
          else
        $scope.Items=[];   
      });
         
         
    };

    // use the array "every" function to test if ALL items are checked
    $scope.checkIfAllSelected = function() {
      $scope.selectedAll = $scope.shipData.every(function(data) {
        return data.Selected == true
      })
        
    };
    
    $scope.exportPackedExcel=function()
    {
    console.log($scope.shipData);
      $http({
		url: "Shipment/exportPackedExcel",
		method: "POST",
		data:$scope.shipData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
      console.log(response.data.file);
        
         var d = new Date();
    var $a = $("<a>");
    $a.attr("href",response.data.file);
    $("body").append($a);
    $a.attr("download",d+"orders.xls"); 
    $a[0].click();
    $a.remove();
    
      
      });
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