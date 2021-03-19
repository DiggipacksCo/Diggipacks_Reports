var app = angular.module('appPickup', [])



.controller('pickListView', function($scope,$http,$window,$location) {
  $scope.baseUrl = new $window.URL($location.absUrl()).origin+'/';
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
    
    $scope.GetremoveOrderPicklist=function(slip_no)
    {
         disableScreen(1);
                $scope.loadershow = true;
                $http({
                    url: URLBASE+"Shipment/GetProcessOpenOrder",
                    method: "POST",
                    data: {slip_no: slip_no},
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }

                }).then(function (response) {

                    disableScreen(0);
                    $scope.loadershow = false;
                    alert("successfully order removed!");
                    $scope.loadMore(1, 1);
                });
        
    };
    
    $scope.StaffPickerReport=function(page_no,reset)
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
		url: URLBASE+"PickUp/StaffpickingReport",
		method: "POST",
		data:$scope.filterData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           console.log(response.data.result)
		 $scope.totalCount=response.data.count;
              
                 $scope.shipData=response.data.result;
							
			 
			 
		
  })  
  
        
    };
    
    $scope.Getpickerdata=function()
    {
    
      
  
    $http({
		url: URLBASE+"PickUp/Getpickerdata",
		method: "POST",
		data:$scope.filterData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
        
		   $scope.pickerArr=response.data;
							
			 
			 
		
  })  
  
        
    };
    
     $scope.loadMore1=function(page_no,reset)
    {
		
     $scope.filterData.page_no=page_no;
     $scope.filterData.status=1;
	 	$scope.showlistData=[];
		$scope.showlistData1=[];
		$scope.awbArray=[];
      if(reset==1)
      {
      $scope.shipData=[];
      $scope.Items=[];
      }
  
 // $scope.filterData.seller_id=$scope.sellerdata.seller_id;
  //console.log($scope.filterData);
    $http({
		url: "PickUp/getSkuDetails",
		method: "POST",
		data:$scope.filterData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           console.log(response);
		    $scope.showlistData=response.data.result;
			//console.log($scope.showlistData);
						 
			 $scope.tableshow=true;
			  
						
		
  })  
  
        
    };
    
   

 

    
  $scope.exportExcel=function()
    {
    console.log($scope.shipData);
      $http({
		url: URLBASE+"pickUp/pickListViewExport",
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

 .controller('manifestView', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            $scope.filterData = {};
            $scope.shipData = [];
            $scope.Items = []
            $scope.modelData = {};
            $scope.status_data = {};

            console.log($scope.baseUrl);
            $scope.filterData.s_type="AWB";
            $scope.loadMore = function (page_no, reset)
            {
                console.log(page_no);
                // console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                $scope.filterData.status = 1;
                if (reset == 1)
                {
                    $scope.shipData = [];
                    $scope.Items = [];
                }

                $http({
                    url: $scope.baseUrl + "/manifestListFilter",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response.data.result)
                    $scope.dropexport = response.data.dropexport;
                    $scope.totalCount = response.data.count;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            //console.log(value)

                            $scope.shipData.push(value);

//                         $scope.dataIndex=  $scope.shipData.findIndex( record => record.slip_no ===value.slip_no);   
//                        $scope.shipData[$scope.dataIndex].skuData=[];  
//                        $scope.shipData[$scope.dataIndex].skuData.push(JSON.parse(JSON.stringify(value.sku)));   
                            //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });
                        //.console.log( $scope.shipData[0].skuData[0])
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }



                })


            };

           


            $scope.exportExcel = function ()
            {
                console.log($scope.shipData);
                $http({
                    url: $scope.baseUrl + "/pickUp/pickListViewExport",
                    method: "POST",
                    data: $scope.shipData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response.data.file);

                    var d = new Date();
                    var $a = $("<a>");
                    $a.attr("href", response.data.file);
                    $("body").append($a);
                    $a.attr("download", d + "orders.xls");
                    $a[0].click();
                    $a.remove();


                });
            }
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
                link: function (scope, element, attr) {
                    var msg = attr.ngConfirmClick || "Are you sure?";
                    var clickAction = attr.confirmedClick;
                    element.bind('click',function (event) {
                        if ( window.confirm(msg) ) {
                            scope.$eval(clickAction)
                        }
                    });
                }
            };
    }])