var app = angular.module('InventoryCheckApp', [])

        .controller('scanInventory', function ($scope, $http, $interval, $window) {
            $scope.shipData = [];
            $scope.completeShip = [];
            $scope.scan = {};
            $scope.scan_new = {};
            $scope.specialtype = {};
            $scope.shipData_new = [];

            $scope.awbArray = [];
            $scope.CustDropArr = {};
            $scope.shelve = null;
            $scope.scan_new.box_no = 1;
            $scope.cust_nameBtn=false;
            $scope.location_nameBtn=false;
            $scope.sku_nameBtn=true;
             $scope.nextBtnShow=false;
             $scope.TotalUserCount=0;
             $scope.ExportBtnShow=false;

            $scope.scan_customer = function () {
                
                $scope.CheckCustomerInventory();
            }
            $scope.GetcheckStockLocation = function () {
                
                //.CheckCustomerInventory();
                
            $scope.arrayIndexnew1 = $scope.shipData.findIndex(record => (record.stock_location.toUpperCase() === $scope.scan.stock_location.toUpperCase()));
            if($scope.arrayIndexnew1==-1)
            {
                 $scope.Message=null;
               $scope.warning = "Stock Location Not Found";  
            }
            else
            {
                  $scope.inxexComp3 = $scope.completeArray.findIndex(record => (record.stock_location === $scope.scan.stock_location))
                 // alert($scope.inxexComp3);
                    if ($scope.inxexComp3 ==-1)
                    {
                         $scope.warning=null;
                    $scope.Message = "Stock Location Scanned !";  
                     $('#scan_sku_id').focus();
                     $scope.nextBtnShow=true;
                    $scope.location_nameBtn=true;
                    $scope.sku_nameBtn=false;
                    }
                    else
                    {
                        $scope.Message=null;
                         $scope.warning = "Already Stock Location Scanned !";  
                    }
               
               
            }
            }
            
            $scope.GetCustomerNamesData=function()
            {
                 $http({
                    url: "ItemInventory/GetCustomerNamesData",
                    method: "POST",
                  headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    $scope.shipData=[];
                   $scope.CustDropArr=response.data;
                   
                    
                });
                
            }
            
            $scope.EnableLocaion=function()
            {
                 $scope.completeArray.push({'stock_location': $scope.scan.stock_location});
                $scope.scan.stock_location=null;
                 $scope.scan.sku=null;
                $scope.location_nameBtn=false; 
                  $scope.nextBtnShow=false;
                  
                  if($scope.TotalUserCount==$scope.completeArray.length)
                        {
                            $scope.ExportBtnShow=true;
                        }
            }
            
            


            $scope.CheckCustomerInventory = function () {
               
                $scope.warning = null;
                $scope.Message = null;
                $scope.arrayIndexnew = [];

                $http({
                    url: "ItemInventory/inventoryCheck",
                    method: "POST",
                    data: $scope.scan,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //alert("ssssssss");
                    //$scope.specialtype.specialpack=true;
                    //$scope.specialtype.specialpacktype="warehouse";
                  //  alert(response.data.length);
                  
                    if (response.data.length == 0)
                    {
                        $scope.Message=null;
                        $scope.warning = "Record Not Found";
                        //  alert($scope.warning);

                        responsiveVoice.speak($scope.warning);
                    }
                    else
                    {
                        $scope.warning=null;
                         $scope.TotalUserCount=response.data.length;
                        $('#scan_stocklocation_id').focus();
                        $scope.cust_nameBtn=true;
                         
                         $scope.Message = "Customer Scan";
                        //  alert($scope.warning);

                        responsiveVoice.speak($scope.Message);
                        
                    }
                    $scope.totalpiece=0;
                    angular.forEach(response.data, function (value) {
                        
                       
                        $scope.totalpiece+=parseInt(value.quantity);
                         //console.log($scope.totalpiece);
                        
                        $scope.shipData.push({'cust_id': value.cust_id,'cust_name': value.cust_name,'storage_type': value.storage_type, 'sku_size': value.sku_size,'stock_location': value.stock_location, 'sku': value.sku, 'sku1': value.sku, 'piece': value.quantity, 'scaned': 0, 'extra': 0});

                    });
                  
                    // console.log($scope.shipData_new);
                });


            }

            $scope.CheckCustomerInventory_sku = function () {
                
              //  alert("sssssss");
               
                        // alert($scope.completeArray);
                        $scope.scanCheck();
                        $scope.checkComplte($scope.shipData, $scope.scan.stock_location);
                   

            }


          $scope.total={};
          $scope.total.peices=0;
          $scope.total.extra=0;
          $scope.total.scaned=0;


            $scope.scanCheck = function ()
            {
               
             
              console.log("ssssssss");
              //console.log($scope.shipData);
                
                $scope.arrayIndexnew = $scope.shipData.findIndex(record => (record.stock_location.toUpperCase() === $scope.scan.stock_location.toUpperCase() && record.sku.toUpperCase() === $scope.scan.sku.toUpperCase()))
                // $scope.arrayIndexnew= $scope.shipData.findIndex( record => (record.slip_no ===$scope.scan.slip_no && record.sku ===$scope.scan.sku ))
                
               
                if ($scope.arrayIndexnew != -1)
                {
                    if (parseInt($scope.shipData[$scope.arrayIndexnew].scaned) < parseInt($scope.shipData[$scope.arrayIndexnew].piece))
                    {
                        $scope.shipData[$scope.arrayIndexnew].scaned = parseInt($scope.shipData[$scope.arrayIndexnew].scaned) + 1;
                        if (parseInt($scope.shipData[$scope.arrayIndexnew].scaned) == parseInt($scope.shipData[$scope.arrayIndexnew].piece))
                        {
                           // $scope.scan.stock_location=null;
                              $scope.location_nameBtn=false;
                               $scope.nextBtnShow=false;
                           $scope.sku_nameBtn=true;
                            $scope.Message = null;
                            // $scope.warning='All Parts Scanned for '+$scope.shipData[$scope.arrayIndexnew].sku;
                            $scope.warning = 'All Parts Scanned for ' + $scope.shipData[$scope.arrayIndexnew].sku;
                            //responsiveVoice.speak($scope.warning);   
                        } else
                        {
                            $scope.Message = 'Scaned!';
                            //responsiveVoice.speak($scope.message);    
                            responsiveVoice.speak('Scaned!');
                        }
                        $scope.total.scaned=$scope.total.scaned+1;
                        $scope.total.peices=$scope.total.peices+1;

                    } else
                    {
                        $scope.total.extra=$scope.total.extra+1;

                        //$scope.shipData[$scope.arrayIndexnew].scaned=parseInt($scope.shipData[$scope.arrayIndexnew].scaned)+1; 
                        $scope.shipData[$scope.arrayIndexnew].extra = parseInt($scope.shipData[$scope.arrayIndexnew].extra) + 1;
                        $scope.Message = null;
                        $scope.warning = 'Extra Item Scaned';
                        responsiveVoice.speak($scope.warning);
                        //$scope.warning='Shipment Already scanned';
                        var sound = document.getElementById("audio");
                        sound.play();

                    }



                }
                else
                {
                    if ($scope.scan.sku.length > 0)
                    {
                        $scope.Message = null;
                        $scope.warning = $scope.scan.sku + ', SKU not available for this Stock Location!';
                        responsiveVoice.speak('SKU not available for this Stock Location!');
                    } else

                    {

                    }


                }
                $scope.scan.sku = null;
            }

            $scope.completeArray = [];
            // $scope.checkArray=[];  
            $scope.checkComplte = function (dataArray, stock_location)
            {
                $scope.checkArray = [];
                angular.forEach(dataArray, function (value) {

                    if (value.stock_location == stock_location)
                    {
                        $scope.checkArray.push(value);


                    }
                });
                $scope.checkqty = 0;
                angular.forEach($scope.checkArray, function (value) {
                    if (value.piece == value.scaned)
                    {
                        $scope.checkqty++
                    }


                });
                if ($scope.checkArray.length == $scope.checkqty && $scope.checkqty > 0)
                {
                    //alert($scope.scan_new.box_no);
                    $scope.inxexComp = $scope.completeArray.findIndex(record => (record.stock_location === $scope.scan.stock_location))
                    if ($scope.inxexComp == -1)
                    {
                        
                        $scope.completeArray.push({'stock_location': $scope.checkArray[0].stock_location});
                        
                        if($scope.TotalUserCount==$scope.completeArray.length)
                        {
                            $scope.ExportBtnShow=true;
                        }
                      //  console.log($scope.completeArray.length);
                    }
                    // alert($scope.specialtype.specialpack);

                    $scope.warning = null;
                    var soundsuccess = document.getElementById("audioSuccess");
                    soundsuccess.play();
                    $scope.Message = $scope.checkArray[0].stock_location + ' Completly Scaned !';
                    //responsiveVoice.speak($scope.message);  



                    responsiveVoice.speak('Completly Scaned');

                  
                }
                console.log($scope.completeArray);
            }
            
            $scope.GetSaveReportInventpry=function()
            {
             
             //console.log($scope.shipData);   
             
             $http({
                    url: "ItemInventory/GetSaveReportInventpry",
                    method: "POST",
                    data: $scope.shipData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    alert("report save successflly!");
                    $window.location.reload();
                });
            }


        })



        .factory('Excel', function ($window) {
            var uri = 'data:application/vnd.ms-excel;base64,',
                    template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
                    base64 = function (s) {
                        return $window.btoa(unescape(encodeURIComponent(s)));
                    },
                    format = function (s, c) {
                        return s.replace(/{(\w+)}/g, function (m, p) {
                            return c[p];
                        })
                    };
            return {
                tableToExcel: function (tableId, worksheetName) {
                    var table = $(tableId),
                            ctx = {worksheet: worksheetName, table: table.html()},
                            href = uri + base64(format(template, ctx));
                    return href;
                }
            };
        })


        .directive('checkList', function () {
            return {
                scope: {
                    list: '=checkList',
                    value: '@'
                },
                link: function (scope, elem, attrs) {
                    var handler = function (setup) {
                        var checked = elem.prop('checked');
                        var index = scope.list.indexOf(scope.value);

                        if (checked && index == -1) {
                            if (setup)
                                elem.prop('checked', false);
                            else
                                scope.list.push(scope.value);
                        } else if (!checked && index != -1) {
                            if (setup)
                                elem.prop('checked', true);
                            else
                                scope.list.splice(index, 1);
                        }
                    };

                    var setupHandler = handler.bind(null, true);
                    var changeHandler = handler.bind(null, false);

                    elem.on('change', function () {
                        scope.$apply(changeHandler);
                    });
                    scope.$watch('list', setupHandler, true);
                }
            };
        })
        .filter('reverse', function () {
            return function (items) {
                return items.slice().reverse();
            };
        })

        .directive('myEnter', function () {
            return function (scope, element, attrs) {
                element.bind("keydown keypress", function (event) {
                    if (event.which === 13) {
                        scope.$apply(function () {
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
/*------ /show shipments-----*/