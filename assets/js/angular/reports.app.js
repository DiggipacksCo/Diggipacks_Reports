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
 .controller('CtritemInvontaryview', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            $scope.filterData = {};
            $scope.shipData = [];
            $scope.Items=[];
            $scope.UpdateData = {};
            $scope.locationData = {};
            $scope.QtyUpArray = {};
            $scope.UpdateData.locationUp = 'error';
            $scope.QtyUpArray.newqty = "";
            $scope.inputbutton = false;
            $scope.PalletArray = {};
            $scope.PupdateArray = {};
            $updateArray = {};
            $scope.loadershow = false;
            
            
             $scope.assigndata = {};
            $scope.returnUpdate = {};
            $scope.courierData = {};

           
            
             $scope.returnUpdate.assign_type = "D";
            $scope.driverbtn = true;
            $scope.crourierbtn = false;
            
            $scope.returnUpdate.pack_type="B";

            $scope.listData2 = {
                "name": false,
                "sku": false,
               
                "quantity": false,
                "seller_name": false,
                "item_description": false,
                "update_date": false,
               
                "order_no": false,
                "m_qty": false,
                "d_qty": false

            };



            $scope.checkall = false;
            $scope.toggleAll = function () {

                $scope.checkall = !$scope.checkall;
                console.log($scope.listData2);
                for (var key in $scope.listData2) {

                    $scope.listData2[key] = $scope.checkall;
                }
            };
            $scope.loadMore = function (page_no, reset)
            {
                disableScreen(1);
                $scope.loadershow = true;
                console.log(page_no);
                // console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                if (reset == 1)
                {
                 $scope.count=1;
                    $scope.shipData = [];
                 //   alert($scope.filterData.page_no);
                    // $scope.filterData.page_no = 1;
                }
               //  alert($scope.filterData.page_no);

                $http({
                    url: "Reports/filter_damage",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response)
                    $scope.totalCount = response.data.count;
                    $scope.dropexport = response.data.dropexport;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            // console.log(value.slip_no)

                            $scope.shipData.push(value);

                        });
                        //console.log( $scope.shipData)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }
                    disableScreen(0);
                    $scope.loadershow = false;



                })


            };
            
            
           
            
            
                $scope.selectedAll = false;
                $scope.selectAll = function (val) {
                //alert(val);
                $scope.Items=[];

                // console.log("sssssss");
                var newval = val - 1;
                angular.forEach($scope.shipData, function (data, key) {
                    // console.log(key+"======="+newval);
                    if (key <= newval)
                    {

                        //console.log(key+"======="+newval);
                        //console.log($scope.selectedAll);
                        data.Selected = true;

                        $scope.Items.push(data.id);

                    } else
                    {
                        //console.log($scope.selectedAll);
                        data.Selected = $scope.selectedAll;
                        if ($scope.selectedAll == true)
                            $scope.Items.push(data.id);
                        else
                            $scope.Items = [];
                    }

                });


            };
    
            $scope.checkIfAllSelected = function () {
                $scope.selectedAll = $scope.shipData.every(function (data) {
                    return data.Selected == true
                })

            };
            
            
           
            
            
            
            
           
           
            
           
            
            

          

            $scope.ExportData = {};
            $scope.listData1 = [];

            $scope.listDatalist = {};
            $scope.getExcelDetails = function () {

                $scope.listData1.exportlimit = $scope.filterData.exportlimit;
                $("#excelcolumn").modal({backdrop: 'static',
                    keyboard: false})
            };

            $scope.checkall1 = function () {
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



            $scope.ItemInventoryExport = function () {

                //$scope.exportlimit
                selected_cols = {};
                for (var key in $scope.listData2) {
                    if ($scope.listData2[key] == true) {
                        selected_cols[key] = true;
                    }
                }

                if (Object.keys(selected_cols).length == 0) {
                    alert("Please select at least one columns");
                    return false;
                }
                $scope.listDatalist.filterData = $scope.filterData;
                $scope.listDatalist.listData2 = selected_cols;

                console.log($scope.listDatalist);
                $http({
                    //  url: "ItemInventory/getexceldata",
                    url: "ExcelExport/ItemInventoryExport_damage",
                    method: "POST",
                    data: $scope.listDatalist,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (results) {
                    $scope.toggleAll();
                    var $a = $("<a>");
                    $a.attr("href", results.data.file);
                    $("body").append($a);
                    $a.attr("download", results.data.file_name);
                    $a[0].click();
                    $a.remove();



                });
                $('#excelcolumn').modal('hide');
            };






            $scope.ExportExcelitemInventory = function ()
            {
                if ($scope.filterData.exportlimit > 0)
                {
                    disableScreen(1);
                    $scope.loadershow = true;
                    console.log($scope.exportlimit);
                    $http({
                        url: URLBASE + "ItemInventory/exportexcelinventory",
                        method: "POST",
                        data: $scope.filterData,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                        //	  console.log(response.data);

                        var d = new Date();
                        var $a = $("<a>");
                        $a.attr("href", response.data.file);
                        $("body").append($a);
                        $a.attr("download", 'Items Inventory ' + d + "orders.xls");

                        $a[0].click();
                        $a.remove();


                    });
                } else
                {
                    alert("please select export limit");
                }
                disableScreen(0);
                $scope.loadershow = false;


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