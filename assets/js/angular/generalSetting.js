var app = angular.module('log', [])

        /*------ show log shipments-----*/
        .controller('shipment_log_view', function ($scope, $http, $window) {




            $scope.filterData = {};
            $scope.shipData = [];
            $scope.excelshipData = [];
            $scope.dropexport = [];
            $scope.Items = [];
            $scope.dropshort = {};
            $scope.loadershow = false;
            $scope.filterData.s_type = 'AWB';

            $scope.listData2 = {
                "entrydate": false,
                "booking_id": false,
                "shippers_ref_no": false,
                "slip_no": false,
                "origin": false,
                "destination": false,
                "sender_name": false,
                "sender_address": false,
                "sender_phone": false,
                "reciever_name": false,
                "reciever_address": false,
                "reciever_phone": false,
                "mode": false,
                "delivered": false,
                "total_cod_amt": false,
                "cust_id": false,
                "pieces": false,
                "weight": false,
                "status_describtion": false,
                "frwd_awb_no": false,
                "transaction_no": false,
                "pay_Invoice_status": false,
                "sub_category": false,
                "onHold_Confirm": false,
                "onHold_Date": false,
                "onHold_Reason": false,
                "shelv_no": false,
                "schedule_date": false,
                "time_slot": false,
                "area_street": false,
                "area": false,
                "dest_lat": false,
                "dest_lng": false,
                "delever_date": false,
                "frwd_throw": false,
                "payable_status": false,
                "receivable_status": false,
                "receivable_invoice_no": false,
                "show_code": false,
                "messenger_name": false,
            };


            $scope.checkall = false;
            $scope.toggleAll = function () {
                $scope.checkall = !$scope.checkall;
                console.log("?dsfsdf");

                for (var key in $scope.listData2) {
                    $scope.listData2[key] = $scope.checkall;
                }
            };

            $scope.loadMore = function (page_no, reset)
            {
                //  disableScreen(1);
                //$scope.loadershow=true; 
                console.log(page_no);
                // console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                if (reset == 1)
                {
                    $scope.shipData = [];
                }

                $http({
                    url: "Generalsetting/filter",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response)
                    $scope.dropshort = response.data.dropshort;
                    $scope.totalCount = response.data.count;
                    $scope.shipDataexcel = response.data.excelresult;
                    $scope.dropexport = response.data.dropexport;

                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            //console.log(value.slip_no)

                            $scope.shipData.push(value);

                        });
                        //console.log( $scope.shipData)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }

                    disableScreen(0);
                    $scope.loadershow = false;



                }, function (status, error) {

                    disableScreen(0);
                    $scope.loadershow = false;
                })


            };


            $scope.GetProcessOpenOrder = function (slip_no)
            {
                disableScreen(1);
                $scope.loadershow = true;
                $http({
                    url: "Shipment/GetProcessOpenOrder",
                    method: "POST",
                    data: {slip_no: slip_no},
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }

                }).then(function (response) {

                    disableScreen(0);
                    $scope.loadershow = false;
                    alert("successfully status changed");
                    $scope.loadMore(1, 1);
                });
            };
            $scope.GetInventoryPopup = function (id) {
                disableScreen(1);
                $scope.loadershow = true;
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
                    $scope.loadershow = false;


                })



            }

 $scope.GetStockLocationPopup = function (id) {
                disableScreen(1);
                $scope.loadershow = true;
                //alert(id); 
                //data:$scope.shipData,
                $scope.itemData1 = [];
                $scope.filterData.id = id;
                $http({
                    url: "Shipment/GetStockLocation",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }

                }).then(function (response) {
                    console.log(response)

                    $scope.itemData1 = response.data;
                    console.log($scope.itemData1)
                    $("#StocklocationModal").modal({
                        backdrop: 'static',
                        keyboard: true
                    })

                    disableScreen(0);
                    $scope.loadershow = false; 


                })



            }

            $scope.chnagelocation= function(St_id)
            {
               // $scope.stockdata = undefined;
                 console.log(St_id);
                // console.log(dop);
                 console.log ($scope.itemData1[St_id].stockdata);
                if($scope.stockdata == undefined)
                {
                    console.log($scope.itemData1[St_id]);
                 
                }
               
            }

        
        $scope.savedetails = function ()
            {
                disableScreen(1);
                $scope.loadershow = true;
                $http({
                    url: "Shipment/save_details",
                    method: "POST",
                    data: $scope.itemData1,
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                }).then(function (response) {

                    disableScreen(0);
                    $scope.loadershow = false;
                    console.log(response);
                   alert("successfully order open");
                     $scope.loadMore(1, 1);
                });
            };



            $scope.ExportData = {};
            $scope.listData1 = [];

            $scope.listDatalist = {};
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

            $scope.selectedAll = false;
            $scope.selectAll = function (val) {
                //alert(val);


                // console.log("sssssss");
                var newval = val - 1;
                angular.forEach($scope.shipData, function (data, key) {
                    // console.log(key+"======="+newval);
                    if (key <= newval)
                    {

                        //console.log(key+"======="+newval);
                        //console.log($scope.selectedAll);
                        data.Selected = true;

                        $scope.Items.push(data.slip_no);

                    } else
                    {
                        //console.log($scope.selectedAll);
                        data.Selected = $scope.selectedAll;
                        if ($scope.selectedAll == true)
                            $scope.Items.push(data.slip_no);
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

            $scope.removemultipleorder = function ()
            {
                console.log($scope.Items);
                if ($scope.Items.length > 0)
                {
                    $http({
                        url: "Shipment/GetremoveMultipleOrders",
                        method: "POST",
                        data: {slipData: $scope.Items, page_check: 'ship_view'},
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}


                    }).then(function (response) {
                        if (response.data == "succ")
                        {
                            alert("successfuly Deleted");
                            $window.location.reload();
                        } else
                        {
                            alert("successfuly Deleted");
                            $window.location.reload();
                        }

                    });
                } else
                {
                    alert("please select atleat one order");
                }

            };


            $scope.transferShip1 = function () {

                //$scope.exportlimit

                $scope.listDatalist.filterData = $scope.filterData;
                //$scope.listDatalist.listData2 = $scope.listData2;
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

                $scope.listDatalist.listData2 = selected_cols;

                $http({
                    url: "Shipment/getexceldata",
                    method: "POST",
                    data: $scope.listDatalist,
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
                $('#excelcolumn').modal('hide');
            };





            $scope.exportExcel = function ()
            {
                if ($scope.filterData.exportlimit > 0)
                {
                    console.log("ssssssssss");
                    disableScreen(1);
                    $scope.loadershow = true;
                    // console.log($scope.exportlimit);
                    $http({
                        url: "Shipment/exportPackedExcel",
                        method: "POST",
                        data: $scope.filterData,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                        console.log(response);

                        var d = new Date();
                        var $a = $("<a>");
                        $a.attr("href", response.data.file);
                        $("body").append($a);
                        $a.attr("download", d + "orders.xls");
                        $a[0].click();
                        $a.remove();
                        disableScreen(0);
                        $scope.loadershow = false;

                    },
                            function (data) {
                                disableScreen(1);
                                $scope.loadershow = true;
                                console.log(data);
                            });
                } else
                {
                    alert("please select export limit");
                }
            }
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
        });

        app .directive('myEnter', function () {
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
        });
        app.directive('ngConfirmClick', [
            function () {
                return {
                    priority: -1,
                    restrict: 'A',
                    link: function (scope, element, attrs) {
                        element.bind('click', function (e) {
                            var message = attrs.ngConfirmClick;
                            // confirm() requires jQuery
                            if (message && !confirm(message)) {
                                e.stopImmediatePropagation();
                                e.preventDefault();
                            }
                        });
                    }
                }
            }
        ])
/*------ /show shipments-----*/