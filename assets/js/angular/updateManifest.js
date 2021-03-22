var app = angular.module('updateManifest', [])

        .controller('scanInventory', function ($scope, $http, $interval, $window, $location) {

            $scope.Menidata = [];
            $scope.completeShip = [];
            $scope.scan = {};
            $scope.scan_new = {};
            $scope.specialtype = {};
            $scope.shipData_new = [];

            $scope.awbArray = [];
            $scope.CustDropArr = {};
            $scope.shelve = null;
            $scope.scan_new.box_no = 1;
            $scope.cust_nameBtn = false;
            $scope.location_nameBtn = true;
            $scope.sku_nameBtn = true;
            $scope.nextBtnShow = false;
            $scope.shelve_nameBtn = true;
            $scope.TotalUserCount = 0;
            $scope.AlltotalCount = 0;
            $scope.AlltotalCount_shelve = 0;

            $scope.newCompeleteArr = [];
            $scope.ExportBtnShow = false;
            $scope.AddInventoryBtn = false;

            $scope.skuLoopArr = [];
            $scope.skuLoopArr_new = [];
            $scope.MatchStockLocation = [];
            $scope.MatchShelveArrr = [];
            $scope.UpdateInventoryCheck = 0;
            $scope.TotalCountSameTime = 0;
            $scope.singleStockLocationIndex = null;


            $scope.GetUrlData = function (uid, sid)
            {
                $scope.scan.uid = uid;
                $scope.scan.sid = sid;

                $http({
                    url: URLBASE + "Manifest/GetSkulistForUpdateInventory",
                    method: "POST",
                    data: {uid: uid},
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //console.log(response);
                    $scope.skuLoopArr = response.data.result;
                    $scope.skuLoopArr_new = response.data.result;


                });
            }



            $scope.scan_sku = function () {

                $scope.arrayIndexnew1 = $scope.Menidata.findIndex(record => (record.sku === $scope.scan.sku));
                //  alert($scope.arrayIndexnew1);
                if ($scope.arrayIndexnew1 == -1)
                {

                    $http({
                        url: URLBASE + "Manifest/GetUpdateManifestStockLocation",
                        method: "POST",
                        data: {list: $scope.scan, stockArr: $scope.MatchStockLocation, shelveArr: $scope.MatchShelveArrr},
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                         console.log(response.data.result); 

                        if (response.data.result.length === 0)  
                        {
                            $scope.Message = null;
                            $scope.warning1 = true;
                            //  alert($scope.warning);

                            //responsiveVoice.speak($scope.warning);
                        } else
                        {
                            $scope.warning1 = false;
                            if(response.data.countarray!=response.data.countbox)
                            {
                                $scope.warning2 = true;
                                $scope.countarray=response.data.countarray;
                                $scope.countbox=response.data.countbox;
                                response.data=[];

                            }
                            
                            else if ($scope.UpdateInventoryCheck == 0)
                            {
                                $scope.countarray=response.data.countarray;
                                $scope.countbox=response.data.countbox;
                                $scope.warning2 = false;
                                $scope.ExportBtnShow = false;
                                $scope.AddInventoryBtn = false;
                                $scope.cust_nameBtn = true;
                                $scope.location_nameBtn = false;
                                $scope.shelve_nameBtn = true;
                                $scope.AlltotalCount = 0;
                                $scope.AlltotalCount_shelve = 0;
                                $scope.warning = null;
                                $scope.TotalUserCount = response.data.length;
                                $scope.TotalCountSameTime = response.data.result.length

                                $scope.warning = null;
                                $('#scan_shelve_id').focus();
                                $scope.Message = "Sku Scan";
                                //  alert($scope.warning);

                                responsiveVoice.speak($scope.Message);
                            } else
                            {
                                $scope.ExportBtnShow = false;
                                $scope.AddInventoryBtn = false;
                                $scope.cust_nameBtn = false;
                                $scope.location_nameBtn = true;
                                $scope.shelve_nameBtn = true;
                                $scope.AlltotalCount = 0;
                                $scope.AlltotalCount_shelve = 0;
                                $scope.warning = null;
                                $scope.TotalUserCount = response.data.length;
                                $scope.TotalCountSameTime = response.data.result.length


                                //$('#scan_stocklocation_id').focus();
                                $scope.Message = "inventory added";
                                $scope.UpdateInventoryCheck = 0;

                            }

                        }
                        // alert(response.data.result.length);
                        angular.forEach(response.data.result, function (value, key) {

                            $scope.Menidata.push({'stockLocation': value.stockLocation, 'boxes': value.boxes, 'capacity': value.capacity, 'shelveNo': value.shelveNo, 'sid': value.sid, 'sku': value.sku, 'storage_type': value.storage_type, 'totalqty': value.totalqty, 'uid': value.uid, 'warehouse_name': value.warehouse_name, 'wh_id': value.wh_id, 'totoalbox': value.countbox, 'total_location': response.data.countarray, 'scaned': 0, 's_status': 'pending', 'l_status': 'pending', 'scaned_s': 0, 'expire_date': value.expire_date, 'skuid': value.skuid});

                            $scope.MatchStockLocation.push(value.stockLocation);
                            $scope.MatchShelveArrr.push(value.shelveNo);

                        });

                        // console.log($scope.Menidata);

                    });
                } else
                {
                    $scope.warning = "this sku already scanned!";
                    // $('#scan_stocklocation_id').focus();
                    $scope.Message = null;
                    responsiveVoice.speak($scope.warning);
                }




                // $scope.CheckCustomerInventory();
            }
            $scope.oldStockLocationArr = [];
            $scope.oldshelveArr = [];
            $scope.GetcheckStockLocation = function () {
                $scope.arrayIndexnew1 = $scope.Menidata.findIndex(record => (record.sku === $scope.scan.sku && record.stockLocation.toUpperCase() === $scope.scan.stock_location.toUpperCase()));
                $scope.oldlocationIndex = $scope.oldStockLocationArr.findIndex(record => (record.sku === $scope.scan.sku && record.stockLocation.toUpperCase() === $scope.scan.stock_location.toUpperCase()));



                $scope.skuMainArrayIndex = $scope.newCompeleteArr.findIndex(record => (record.sku === $scope.scan.sku));

                //   alert($scope.oldlocationIndex);

                console.log(1);
//alert($scope.skuMainArrayIndex);
                if ($scope.arrayIndexnew1 == -1)
                {

                    console.log(2);
                    $scope.scan.stock_location = null;
                    $scope.Message = null;
                    $scope.warning = "Stock Location Not Found";
                } else
                {
                    if ($scope.oldlocationIndex == -1)
                    {

                        console.log(3);

                        if (parseInt($scope.AlltotalCount) < parseInt($scope.Menidata[$scope.arrayIndexnew1].total_location))
                        {
                            console.log(4);
                            $scope.singleStockLocationIndex = $scope.arrayIndexnew1;

                            $scope.oldStockLocationArr.push({'sku': $scope.scan.sku, 'stockLocation': $scope.scan.stock_location})
                            // console.log($scope.oldStockLocationArr);
                            $scope.AlltotalCount = parseInt($scope.AlltotalCount) + 1;
                            $scope.Menidata[$scope.arrayIndexnew1].l_status = "completed"
                            //$scope.scan.stock_location = null;
                           // $('#scan_shelve_id').focus();
                            $scope.cust_nameBtn = true;
                            $scope.location_nameBtn = true;
                            $scope.shelve_nameBtn = false;
                            $scope.nextBtnShow = true;
                            $scope.sku_nameBtn = true;

                            if (parseInt($scope.AlltotalCount) == parseInt($scope.Menidata[$scope.arrayIndexnew1].total_location))
                            {
                                console.log(5);

                                $('#scan_shelve_id').focus();


                                $scope.Message = null;

                                $scope.warning = 'All Stock Location Parts Scanned for ' + $scope.Menidata[$scope.arrayIndexnew1].sku;
                                //responsiveVoisce.speak($scope.warning);   
                            } else
                            {
                               

                                $('#scan_shelve_id').focus();
                                $scope.warning = null;
                                $scope.Message = 'Scaned!';
                                //responsiveVoice.speak($scope.message);    
                                responsiveVoice.speak('Scaned!');
                                console.log(6);
                            }
                        } else
                        {

                            $('#scan_shelve_id').focus();
                            $scope.Message = null;
                            $scope.warning = 'Extra Item Scaned';
                            responsiveVoice.speak($scope.warning);
                            //$scope.warning='Shipment Already scanned';
                            var sound = document.getElementById("audio");
                            sound.play();
                        }
                    } else
                    {
                        $scope.Message = null;
                        $scope.warning = "Stock Location already scanned";
                    }

                }

            }


            $scope.GetCheckShelveNoScan = function () {
               
                $scope.AlltotalCount_shelve = parseInt($scope.AlltotalCount_shelve) + 1;
                $scope.arrayIndexnew1 = $scope.Menidata.findIndex(record => (record.sku === $scope.scan.sku && record.stockLocation.toUpperCase() === $scope.scan.stock_location.toUpperCase()));

                console.log($scope.arrayIndexnew1);
                //$scope.AlltotalCount_shelve=$scope.AlltotalCount_shelve+1;
                            // console.log($scope.TotalCountSameTime+"tttttt"+$scope.Menidata[$scope.arrayIndexnew1].total_location);
                            if (parseInt($scope.AlltotalCount_shelve) == parseInt($scope.countarray))
                            {
                                //console.log("sssssssssss");

                                $scope.newCompeleteArr.push({'sku': $scope.Menidata[$scope.arrayIndexnew1].sku, 'totalqty': $scope.Menidata[$scope.arrayIndexnew1].totalqty,'capacity':$scope.Menidata[$scope.arrayIndexnew1].capacity,'sid':$scope.Menidata[$scope.arrayIndexnew1].sid,'uid':$scope.Menidata[$scope.arrayIndexnew1].uid,'shelveNo':$scope.scan.shelve});

                                $scope.Menidata[$scope.arrayIndexnew1].shelveNo=$scope.scan.shelve;
                                $scope.ExportBtnShow = true;
                                $scope.AddInventoryBtn = true;
                                $scope.cust_nameBtn = false;
                                $scope.location_nameBtn = true;
                                $scope.shelve_nameBtn = true;
                                $scope.nextBtnShow = true;
                                $scope.sku_nameBtn = true;
                                $scope.Message = null;
                                $scope.Menidata[$scope.arrayIndexnew1].s_status = "completed"
                                $('#scan_stocklocation_id').focus();





                                $scope.warning = 'All Parts Scanned for ' + $scope.Menidata[$scope.arrayIndexnew1].sku;
                                //responsiveVoisce.speak($scope.warning);   
                            } else
                            {
                                $scope.Menidata[$scope.arrayIndexnew1].shelveNo=$scope.scan.shelve;
                            $scope.oldshelveArr.push({'sku': $scope.scan.sku, 'shelveNo': $scope.scan.shelve})
                           
                            $scope.TotalCountSameTime = parseInt($scope.TotalCountSameTime) + 1;
                            $scope.Menidata[$scope.arrayIndexnew1].s_status = "completed"
                            $scope.scan.shelve = null;
                            $scope.scan.stock_location = null;
                            $scope.location_nameBtn = false;
                            $scope.cust_nameBtn = true;
                            $scope.shelve_nameBtn = true;
                            

                                $('#scan_stocklocation_id').focus();
                              
                                $scope.warning = null;
                                $scope.Message = 'Scaned!';
                                //responsiveVoice.speak($scope.message);    
                                responsiveVoice.speak('Scaned!');
                            }
                           
                        }



            $scope.EnableLocaion = function ()
            {
                $scope.completeArray.push({'stock_location': $scope.scan.stock_location});
                $scope.scan.stock_location = null;
                $scope.scan.sku = null;
                $scope.location_nameBtn = false;
                $scope.nextBtnShow = false;

                if ($scope.TotalUserCount == $scope.completeArray.length)
                {
                    $scope.ExportBtnShow = true;
                }
            }





            $scope.GetSaveReportInventpry = function ()
            {

                //console.log($scope.shipData);   

                $http({
                    url: URLBASE + "Manifest/GetSaveInventoryManifest_new",
                    method: "POST",
                    data: {locations:$scope.Menidata,skus:$scope.newCompeleteArr},
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    // $scope.Menidata=[];
                    // 



                    // $scope.cust_nameBtn = false;
                    // $scope.location_nameBtn = true;
                    // $scope.shelve_nameBtn = true;


                    // angular.forEach($scope.newCompeleteArr, function (value, key) {

                    //     $scope.GetremoveKeyelement(value.sku);
                       


                    // });
                    // $scope.Menidata = [];
                    // $scope.newCompeleteArr = [];




                    // $scope.UpdateInventoryCheck = 1;

                  //  console.log($scope.skuLoopArr);
                  $window.location.href =URLBASE+'show_assignedlist';
                    
                });
            }

            $scope.GetremoveKeyelement = function (sku)
            {
                $scope.indexskuR = $scope.skuLoopArr.findIndex(record => (record.sku === sku));
                $scope.skuLoopArr.splice($scope.indexskuR, 1);
                //$scope.indexskuR2 = $scope.newCompeleteArr.findIndex(record => (record.sku === sku));
                //$scope.newCompeleteArr.splice($scope.indexskuR2,1);
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
            function () {
                return {
                    link: function (scope, element, attr) {
                        var msg = attr.ngConfirmClick || "Are you sure?";
                        var clickAction = attr.confirmedClick;
                        element.bind('click', function (event) {
                            if (window.confirm(msg)) {
                                scope.$eval(clickAction)
                            }
                        });
                    }
                };
            }])
/*------ /show shipments-----*/