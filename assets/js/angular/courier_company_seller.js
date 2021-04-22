var app = angular.module('CourierAppPageSeller', [])
        .controller('CourierComapnyCRL', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            $scope.CompanyListArr = {};
            $scope.EditDataArr = {};
            $scope.UpdateStatusArr = {};
            $scope.UpdateliveArr = {};
            $scope.filterData = {};
            $scope.companyArr = {};
            $scope.loadershow = false;

            $scope.DeliveryDropArr = {};
            $scope.userselected = {};
            $scope.awbArray = [];
            $scope.scan = {};
            $scope.newarray = [];

            $scope.GetAllCompanyList = function (id)
            {
                // alert("ssssss");
                $http({
                    url: URLBASE + "CourierCompany/GetshowcompanySeller",
                    method: "POST",
                    id:id,


                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    $scope.CompanyListArr = response.data;


                });


            };

            $scope.GetshowEditModelPOp = function (data)
            {
                $scope.EditDataArr = data;
                $("#Showeditpopid").modal({backdrop: 'static', keyboard: false});
            };
            $scope.GetCompanyChnagesSave = function ()
            {

                $http({
                    url: URLBASE + "CourierCompany/GetCompanyChnagesSaveSeller",
                    method: "POST",
                    data: $scope.EditDataArr,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    alert("Successfully Updated!");
                    $('#Showeditpopid').modal('hide');
                    window.location.reload()



                });


            };

            $scope.GetCompanylistDrop = function ()
            {
                $http({
                    url: URLBASE + "CourierCompany/GetCompanylistDrop",
                    method: "POST",
                    data: $scope.UpdateStatusArr,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {

                    $scope.DeliveryDropArr = response.data;

                });




            };
            $scope.Getactivecompany = function (id, status)
            {

                $scope.UpdateStatusArr.id = id;
                $scope.UpdateStatusArr.status = status;

                $http({
                    url: URLBASE + "CourierCompany/GetUpdateActiveStatusSeller",
                    method: "POST",
                    data: $scope.UpdateStatusArr,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    alert("Successfully Updated!");
                    $('#Showeditpopid').modal('hide');
                    window.location.reload()



                });


            };

            $scope.scan_awb = function () {

                $scope.scan.awbArray = removeDumplicateValue($scope.userselected.slip_no.split("\n"));
                // console.log($scope.scan.awbArray); 
                $scope.userselected.slip_no = $scope.scan.awbArray.join('\n');
                if ($scope.scan.awbArray.length > 20) {
                    $scope.userselected = {};
                }

                //  $scope.validateOrder();
            }

            function removeDumplicateValue(myArray) {
                var newArray = [];
                angular.forEach(myArray, function (value, key) {
                    var exists = false;
                    angular.forEach(newArray, function (val2, key) {
                        if (angular.equals(value, val2)) {
                            exists = true
                        }
                        ;
                    });
                    if (exists == false && value != "") {
                        newArray.push(value);
                    }
                });

                return newArray;
            }
            $scope.invalidSslip_no = {};
            $scope.Success_msg = {};
            $scope.Error_msg = {};
            $scope.BulkForwardCompany = function ()
            {
                disableScreen(1);
                $scope.loadershow = true;
                console.log($scope.userselected);
                //   $scope.isVisible.loading = true;

                $http({
                    url: URLBASE + "CourierCompany/BulkForwardCompanyReady",
                    method: "POST",
                    data: $scope.userselected,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    disableScreen(0);
                    $scope.loadershow = false;
                    $scope.userselected = {};
                    $scope.scan.awbArray = {};
                    $scope.invalidSslip_no = response.data.invalid_slipNO;
                    $scope.Success_msg = response.data.Success_msg;
                    $scope.Error_msg = response.data.Error_msg;



                }, function (data) {
                    disableScreen(0);
                    $scope.loadershow = false;
                });


            };


            $scope.Getlivemodecompany = function (id, status)
            {
                //alert(id);


                $scope.UpdateliveArr.id = id;
                $scope.UpdateliveArr.status = status;

                $http({
                    url: URLBASE + "CourierCompany/GetUpdateLIveStatusSeller",
                    method: "POST",
                    data: $scope.UpdateliveArr,

                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (response) {
                    alert("Successfully Updated!");
                    $('#Showeditpopid').modal('hide');
                    window.location.reload()

                });




            };



        })

        .controller('CourierComapnySeller', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            $scope.CompanyListArr = {};
            $scope.EditDataArr = {};
            $scope.UpdateStatusArr = {};
            $scope.UpdateliveArr = {};
            $scope.filterData = {};
            $scope.companyArr = {};
            $scope.loadershow = false;

            $scope.DeliveryDropArr = {};
            $scope.userselected = {};
            $scope.awbArray = [];
            $scope.scan = {};
            $scope.newarray = [];

            $scope.GetAllCompanyList = function(id)
            {
                // alert("ssssss");
                $http({
                    url: URLBASE + "CourierCompany/GetshowcompanySeller",
                    method: "POST",
                    data: { id : id },

                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    $scope.CompanyListArr = response.data;


                });


            };

            $scope.GetshowEditModelPOp = function (data)
            {
                $scope.EditDataArr = data;
                $("#Showeditpopid").modal({backdrop: 'static', keyboard: false});
            };
            $scope.GetCompanyChnagesSave = function ()
            {

                $http({
                    url: URLBASE + "CourierCompany/GetCompanyChnagesSaveSeller",
                    method: "POST",
                    data: $scope.EditDataArr,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    alert("Successfully Updated!");
                    $('#Showeditpopid').modal('hide');
                    window.location.reload()



                });


            };

            $scope.GetCompanylistDrop = function ()
            {
                $http({
                    url: URLBASE + "CourierCompany/GetCompanylistDrop",
                    method: "POST",
                    data: $scope.UpdateStatusArr,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {

                    $scope.DeliveryDropArr = response.data;

                });




            };
            $scope.Getactivecompany = function (id, status)
            {

                $scope.UpdateStatusArr.id = id;
                $scope.UpdateStatusArr.status = status;

                $http({
                    url: URLBASE + "CourierCompany/GetUpdateActiveStatusSeller",
                    method: "POST",
                    data: $scope.UpdateStatusArr,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    alert("Successfully Updated!");
                    $('#Showeditpopid').modal('hide');
                    window.location.reload()



                });


            };

            $scope.scan_awb = function () {

                $scope.scan.awbArray = removeDumplicateValue($scope.userselected.slip_no.split("\n"));
                // console.log($scope.scan.awbArray); 
                $scope.userselected.slip_no = $scope.scan.awbArray.join('\n');
                if ($scope.scan.awbArray.length > 20) {
                    $scope.userselected = {};
                }

                //  $scope.validateOrder();
            }

            function removeDumplicateValue(myArray) {
                var newArray = [];
                angular.forEach(myArray, function (value, key) {
                    var exists = false;
                    angular.forEach(newArray, function (val2, key) {
                        if (angular.equals(value, val2)) {
                            exists = true
                        }
                        ;
                    });
                    if (exists == false && value != "") {
                        newArray.push(value);
                    }
                });

                return newArray;
            }
            $scope.invalidSslip_no = {};
            $scope.Success_msg = {};
            $scope.Error_msg = {};
            $scope.BulkForwardCompany = function ()
            {
                disableScreen(1);
                $scope.loadershow = true;
                console.log($scope.userselected);
                //   $scope.isVisible.loading = true;

                $http({
                    url: URLBASE + "CourierCompany/BulkForwardCompanyReady",
                    method: "POST",
                    data: $scope.userselected,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    disableScreen(0);
                    $scope.loadershow = false;
                    $scope.userselected = {};
                    $scope.scan.awbArray = {};
                    $scope.invalidSslip_no = response.data.invalid_slipNO;
                    $scope.Success_msg = response.data.Success_msg;
                    $scope.Error_msg = response.data.Error_msg;



                }, function (data) {
                    disableScreen(0);
                    $scope.loadershow = false;
                });


            };


            $scope.Getlivemodecompany = function (id, status)
            {
                //alert(id);


                $scope.UpdateliveArr.id = id;
                $scope.UpdateliveArr.status = status;

                $http({
                    url: URLBASE + "CourierCompany/GetUpdateLIveStatusSeller",
                    method: "POST",
                    data: $scope.UpdateliveArr,

                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (response) {
                    alert("Successfully Updated!");
                    $('#Showeditpopid').modal('hide');
                    window.location.reload()

                });




            };



        })


        .controller('forward_shipment_view', function ($scope, $http) {

            $scope.filterData = {};
            $scope.shipData = [];
            $scope.Items = [];
            $scope.excelshipData = [];
            $scope.dropexport = [];
            $scope.loadershow = false;
            $scope.filterData.s_type = 'AWB';
            $scope.selectedData = {};
            $scope.userselected = {};
            $scope.selectedAll = false;
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
                "frwd_company_awb": false,

            };


             $scope.runshell = function ()
            {

                $http({
                    url: "Shipment/runshell",
                    method: "POST",                   
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    
                    alert("Sync process has been start. Please wait for 10 minute to update data. ");
                    

                })

            } 

           




            $scope.getExcelDetails = function () {
                console.log("sdfsdfds");
                // $scope.listData1.exportlimit = $scope.filterData.exportlimit;
                $("#excelcolumn").modal({backdrop: 'static',
                    keyboard: false})
            };

            $scope.checkall = false;
            $scope.toggleAll = function () {
                $scope.checkall = !$scope.checkall;

                for (var key in $scope.listData2) {
                    $scope.listData2[key] = $scope.checkall;
                }
            };

            $scope.forwardShipmentsExport = function () {

                $scope.listDatalist = {};
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
                console.log($scope.listDatalist);
                $http({
                    url: "ExcelExport/forwardShipmentsExport",
                    method: "POST",
                    data: $scope.listDatalist,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (results) {
                    console.log(results);
                    $scope.toggleAll();
                    var $a = $("<a>");
                    $a.attr("href", results.data.file);
                    $("body").append($a);
                    $a.attr("download", results.data.file_name);
                    $a[0].click();
                    $a.remove();



                });
                $('#excelcolumn').modal('hide');
            }


            $scope.selectAll = function () {


                //console.log($scope.selectedAll); 
                angular.forEach($scope.shipData, function (data) {
                    data.Selected = $scope.selectedAll;
                    if ($scope.selectedAll == true)
                        $scope.Items.push(data.slip_no);
                    else
                        $scope.Items = [];
                });


            };

            $scope.GetCanelBplOrder = function (slip_no)
            {
                disableScreen(1);
                $scope.loadershow = true;
                $http({
                    url: "CourierCompany/GetCanelBplOrder",
                    method: "POST",
                    data: {'slip_no': slip_no},
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }

                }).then(function (response) {
                    alert("successfully cancelled");
                    $scope.loadMore_firwarded(1, 1);
                });
                disableScreen(0);
                $scope.loadershow = false;
            }
            $scope.invalidSslip_no = {};
            $scope.Success_msg = {};
            $scope.Error_msg = {};

            $scope.Getforwared3plcompany = function ()
            {

                $scope.userselected.pagelist = 'succ';
                if ($scope.userselected.cc_id)
                {
                    disableScreen(1);
                    $scope.loadershow = true;
                    console.log($scope.userselected);
                    //   $scope.isVisible.loading = true;

                    $http({
                        url: URLBASE + "CourierCompany/BulkForwardCompanyReady",
                        method: "POST",
                        data: {'slip_arr': $scope.Items, 'otherArr': $scope.userselected},
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                        disableScreen(0);
                        $scope.loadershow = false;
                        // $scope.userselected={};
                        // $scope.scan.awbArray={};
                        $scope.invalidSslip_no = response.data.invalid_slipNO;
                        $scope.Success_msg = response.data.Success_msg;
                        $scope.Error_msg = response.data.Error_msg;
                        $scope.loadMore(1, 1);



                    }, function (data) {
                        disableScreen(0);
                        $scope.loadershow = false;
                    });
                } else
                {
                    alert("Please Select 3PL Company");
                }

            }


            $scope.loadMore = function (page_no, reset)
            {
                disableScreen(1);
                $scope.loadershow = true;
                $scope.filterData.forwarded_type = 0;
                // console.log(page_no);
                // console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                if (reset == 1)
                {
                    $scope.shipData = [];
                }

                $http({
                    url: "CourierCompany/forwardedfilter",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response)
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



                })


            };


            $scope.loadMore_firwarded = function (page_no, reset)
            {
                disableScreen(1);
                $scope.loadershow = true;
                console.log(page_no);
                // console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                $scope.filterData.forwarded_type = 1;
                if (reset == 1)
                {
                    $scope.shipData = [];
                }

                $http({
                    url: "CourierCompany/forwardedfilter",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response)
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



                })


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
            $scope.exportExcel = function ()
            {
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

                });
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
                    priority: 1,
                    terminal: true,
                    link: function (scope, element, attr) {
                        var msg = attr.ngConfirmClick || "Are you sure?";
                        var clickAction = attr.ngClick;
                        element.bind('click', function (event) {
                            if (window.confirm(msg)) {
                                scope.$eval(clickAction)
                            }
                        });
                    }
                };
            }]);
/*------ /show shipments-----*/