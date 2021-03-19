var app = angular.module('Appbackorderlist', [])

.controller('backorderview', function($scope,$http,$window,Excel,$timeout) {
  
  $scope.filterData={};
  $scope.shipData=[];
  $scope.Items=[];
  $scope.errorBackorder={};
  $scope.dropexport=[]; 
  
 $scope.loadMore=function(page_no,reset)
    {
   // console.log(page_no);    
   // console.log($scope.selectedData);    
     $scope.filterData.page_no=page_no;
     $scope.filterData.status=1;
      if(reset==1)
      {
      $scope.shipData=[];
      $scope.Items=[]; 
      }
  
    $http({
		url: "Shipment/filter_backorder",
		method: "POST",
		data:$scope.filterData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {    
           console.log(response);
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
               /// console.log( $scope.shipData)
                 //$scope.$broadcast('scroll.infiniteScrollComplete');
                    }else{$scope.nodata=true
                    }					
			 
			 
		
  })  
  
        
    };    
    
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
    
    $scope.generatePickup=function()
{
    $scope.shipData_new = $scope.shipData.filter(function(item) {
  return $scope.Items.includes(item.slip_no); 
})
        if($scope.shipData_new.length==0)
        {alert('Please select Orders to Create Order!');}
        else
        {
  var isConfirmed = confirm("You are going to Create Order, This Action will change the Order status! Are you sure?");         
   
        if(isConfirmed)
        {


//console.log($scope.shipData_new); 
//console.log($scope.Items); 
            
        $http({
		url: "Shipment/createbackorder",
		method: "POST",
		data:{
            listData:$scope.shipData_new,
            slipData:$scope.Items   
			
        },
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
		 $scope.shipData=[]; 
        console.log(response);
		$scope.errorBackorder=response.data;
		$scope.loadMore(1,0);
        //$window.location.reload();
         //$window.location.replace('pickupList');
        })     
        }
        }
}
  
 $scope.shipData1=[]; 
    
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