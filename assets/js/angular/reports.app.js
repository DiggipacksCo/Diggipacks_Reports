var app = angular.module('AppReports', [])



.controller('ClientReportCTRL', function($scope,$http,$window,$location) {
  $scope.baseUrl = new $window.URL($location.absUrl()).origin+'/fm';
  $scope.filterData={};
  $scope.shipData=[];
  $scope.Items=[]

console.log($scope.baseUrl);
$scope.pickerArr={};
 $scope.loadMore=function(page_no,reset)
    {
    console.log(page_no);    
   // console.log($scope.selectedData);    
     $scope.filterData.page_no=page_no;
     $scope.filterData.status=1;
      if(reset==1)
      {
      $scope.shipData=[];
      $scope.Items=[];
      }
  
    $http({
		url: URLBASE+"pickListFilter",
		method: "POST",
		data:$scope.filterData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           console.log(response.data.result)
		 $scope.totalCount=response.data.count;
                 $scope.pickerArr=response.data.picker;
                 
			 if(response.data.result.length > 0){
                        angular.forEach(response.data.result,function(value){
                            //console.log(value)
                           
                                $scope.shipData.push(value);
                            
//                         $scope.dataIndex=  $scope.shipData.findIndex( record => record.slip_no ===value.slip_no);   
//                        $scope.shipData[$scope.dataIndex].skuData=[];  
//                        $scope.shipData[$scope.dataIndex].skuData.push(JSON.parse(JSON.stringify(value.sku)));   
                                //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });
                //.console.log( $scope.shipData[0].skuData[0])
                 //$scope.$broadcast('scroll.infiniteScrollComplete');
                    }else{$scope.nodata=true
                    }					
			 
			 
		
  })  
  
        
    };
    
    $scope.GetClientOrderReports=function(page_no,reset)
    {
    //console.log(page_no);    
   // console.log($scope.selectedData);    
     $scope.filterData.page_no=page_no;
     $scope.filterData.status=1;
      if(reset==1)
      {
      $scope.shipData=[];
      $scope.Items=[];
      }
  
    $http({
		url: URLBASE+"Reports/GetClientOrderReports",
		method: "POST",
		data:$scope.filterData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
          
               $scope.dropexport=response.data.dropexport;  
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
    
     $scope.GetofdDetails = function (page_no,reset,frwd_throw,status,from,to)
            {
               
               $scope.filterData.frwd_throw = frwd_throw;
               $scope.filterData.status = status;
               $scope.filterData.from = from;
               $scope.filterData.to = to;
                console.log($scope.filterData);
                // console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                if (reset == 1)
                {
                    $scope.shipData = [];
                }

                $http({
                    url: URLBASE + "Reports/performance_details_filter",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                   
                    $scope.totalCount = response.data.count;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                          

                            $scope.shipData.push(value);

                        });
                        //console.log( $scope.shipData)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }



                })


            };
            
            $scope.Get3plDetails = function (page_no,reset,frwd_throw,status,from,to)
            {
               
               
               $scope.filterData.frwd_throw = frwd_throw;
               $scope.filterData.status = status;
               $scope.filterData.from = from;
               $scope.filterData.to = to;
                console.log($scope.filterData);
                // console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                if (reset == 1)
                {
                    
                    $scope.shipData = [];
                }

                $http({
                    url: URLBASE + "Reports/performance_details_filter",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                   
                    $scope.totalCount = response.data.count;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                          

                            $scope.shipData.push(value);

                        });
                        //console.log( $scope.shipData)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }



                })


            };
    
    $scope.getExcelDetails = function () {

        $scope.listData1.exportlimit = $scope.filterData.exportlimit;
        $("#excelcolumn").modal({backdrop: 'static',
            keyboard: false})
    };
	
	
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