var app = angular.module('Appdispatched', [])



        
        .controller('CTLRdelivered', function ($scope, $http, $window, Excel, $timeout) {

            $scope.filterData = {};
            $scope.shipData = [];
            $scope.Items = []
            $scope.dropexport = [];
            $scope.dropshort = {};
            $scope.loadershow = false;
            $scope.filterData.s_type = "AWB";
            $scope.filterData.seller = "";
            $scope.awbArray = [];
            $scope.scan = {};
            $scope.newarray = [];
            $scope.userselected = {};
            
            $scope.scan_awb = function () {

                $scope.scan.awbArray = removeDumplicateValue($scope.userselected.slip_no.split("\n"));
                // console.log($scope.scan.awbArray); 
                $scope.userselected.slip_no = $scope.scan.awbArray.join('\n');
                if ($scope.scan.awbArray.length > 50) {
                    $scope.userselected = [];
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
            $scope.CreateNewReverseOrder = function ()
            {
                disableScreen(1);
                $scope.loadershow = true;

                $http({
                    method: "POST",
                    url:"ShipmentR/getcreateReverseOrder",
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
                })


            }

            $scope.loadMore = function (page_no, reset)
            {
                disableScreen(1);
                $scope.loadershow = true;
                console.log(page_no);
                // console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                $scope.filterData.status = 22;
                if (reset == 1)
                {
                    $scope.shipData = [];
                    $scope.Items = [];
                }


                $http({
                    url: "ShipmentR/filter",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response.data.result)
                    $scope.dropshort = response.data.dropshort;
                    $scope.totalCount = response.data.count;
                    $scope.dropexport = response.data.dropexport;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {

                            $scope.dataIndex = $scope.shipData.findIndex(record => record.slip_no === value.slip_no);
                            if ($scope.dataIndex != -1)
                            {

                                $scope.shipData[$scope.dataIndex].skuData.push({'sku': value.sku, 'piece': value.piece, 'cod': value.cod});   //scope.shipData[$scope.dataIndex].piece=parseInt($scope.shipData[$scope.dataIndex].piece)+parseInt(value.piece);    
                            } else
                            {

                                $scope.shipData.push(value);
                                $scope.dataIndex = $scope.shipData.findIndex(record => record.slip_no === value.slip_no);
                                $scope.shipData[$scope.dataIndex].skuData = [];
                                $scope.shipData[$scope.dataIndex].skuData.push({'sku': value.sku, 'piece': value.piece, 'cod': value.cod});
                            }
                            //console.log(value.slip_no +'//'+$scope.dataIndex)  

                            //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });
                        console.log($scope.shipData)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }

                    disableScreen(0);
                    $scope.loadershow = false;

                })


            };

 $scope.citylist={};
            $scope.showCity = function ()
            {

                $http({
                    url: "Country/showCity",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/json'}

                }).then(function (response) {

                    // console.log(response);
                     $('.selectpicker').selectpicker('refresh');
                    $scope.citylist = response.data;
                   

                })

            }


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
            $scope.selectedAll = false;
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

            // use the array "every" function to test if ALL items are checked
            $scope.checkIfAllSelected = function () {
                $scope.selectedAll = $scope.shipData.every(function (data) {
                    return data.Selected == true
                })

            };


            $scope.ExportData = {};
            $scope.listData1 = [];
            $scope.listData2 = {};
            $scope.listDatalist = {};
            $scope.getExcelDetailsDelivered = function () {

                $scope.listData1.exportlimit = $scope.filterData.exportlimit;
                $("#excelcolumnDelivered").modal({backdrop: 'static',
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


            $scope.transferShipDelivered = function () {

                //$scope.exportlimit

                $scope.listDatalist.filterData = $scope.filterData;
                $scope.listDatalist.listData2 = $scope.listData2;

                $scope.filterData.status = 22;
                $scope.listDatalist.status = $scope.filterData.status;
                $scope.listDatalist.filterData = $scope.filterData;

                $scope.listDatalist.filterData
                console.log($scope.listDatalist);
                $http({
                    url: "Shipment/getexceldataOrderReturned",
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
                $('#excelcolumnDelivered').modal('hide');
            };


            $scope.exportdispatchForLm = function (exportlimit)
            {

                if ($scope.filterData.exportlimit > 0)
                {
                    disableScreen(1);
                    $scope.loadershow = true;


                    //alert(exportlimit);
                    $scope.filterData.status = 7;

                    $http({
                        url: "Shipment/exportPackedExcel",
                        method: "POST",
                        data: $scope.filterData,
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

                        disableScreen(0);
                        $scope.loadershow = false;
                    }, function (data) {
                        disableScreen(0);
                        $scope.loadershow = false;
                    });
                } else
                    alert("please select export limit");
            }



        })

        .controller('CTLRreturned', function ($scope, $http, $window, Excel, $timeout) {

            $scope.filterData = {};
            $scope.shipData = [];
            $scope.Items = []
            $scope.dropexport = [];
            $scope.dropshort = {};
            $scope.loadershow = false;
            $scope.filterData.s_type = "AWB";
            $scope.filterData.seller = "";


            $scope.loadMore = function (page_no, reset)
            {
                disableScreen(1);
                $scope.loadershow = true;
                console.log(page_no);
                // console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                $scope.filterData.status = 8;
                if (reset == 1)
                {
                    $scope.shipData = [];
                    $scope.Items = [];
                }


                $http({
                    url: "Shipment/filter",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response.data.result)
                    $scope.dropshort = response.data.dropshort;
                    $scope.totalCount = response.data.count;
                    $scope.dropexport = response.data.dropexport;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {

                            $scope.dataIndex = $scope.shipData.findIndex(record => record.slip_no === value.slip_no);
                            if ($scope.dataIndex != -1)
                            {

                                $scope.shipData[$scope.dataIndex].skuData.push({'sku': value.sku, 'piece': value.piece, 'cod': value.cod});   //scope.shipData[$scope.dataIndex].piece=parseInt($scope.shipData[$scope.dataIndex].piece)+parseInt(value.piece);    
                            } else
                            {

                                $scope.shipData.push(value);
                                $scope.dataIndex = $scope.shipData.findIndex(record => record.slip_no === value.slip_no);
                                $scope.shipData[$scope.dataIndex].skuData = [];
                                $scope.shipData[$scope.dataIndex].skuData.push({'sku': value.sku, 'piece': value.piece, 'cod': value.cod});
                            }
                            //console.log(value.slip_no +'//'+$scope.dataIndex)  

                            //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });
                        console.log($scope.shipData)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }

                    disableScreen(0);
                    $scope.loadershow = false;

                })


            };

$scope.citylist={};
            $scope.showCity = function ()
            {

                $http({
                    url: "Country/showCity",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/json'}

                }).then(function (response) {

                    // console.log(response);
                    $scope.citylist = response.data;
                    $('.selectpicker').selectpicker('refresh');

                })

            }

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
            $scope.selectedAll = false;
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

            // use the array "every" function to test if ALL items are checked
            $scope.checkIfAllSelected = function () {
                $scope.selectedAll = $scope.shipData.every(function (data) {
                    return data.Selected == true
                })

            };


            $scope.ExportData = {};
            $scope.listData1 = [];
            $scope.listData2 = {};
            $scope.listDatalist = {};
            $scope.getExcelDetails1 = function () {

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


            $scope.transferShip1 = function () {

                //$scope.exportlimit

                $scope.listDatalist.filterData = $scope.filterData;
                $scope.listDatalist.listData2 = $scope.listData2;

                $scope.filterData.status = 8;
                $scope.listDatalist.status = $scope.filterData.status;
                $scope.listDatalist.filterData = $scope.filterData;

                $scope.listDatalist.filterData
                console.log($scope.listDatalist);
                $http({
                    url: "Shipment/getexceldataOrderReturned",
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



            $scope.exportdispatchForLm = function (exportlimit)
            {

                if ($scope.filterData.exportlimit > 0)
                {
                    disableScreen(1);
                    $scope.loadershow = true;


                    //alert(exportlimit);
                    $scope.filterData.status = 8;

                    $http({
                        url: "Shipment/exportPackedExcel",
                        method: "POST",
                        data: $scope.filterData,
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

                        disableScreen(0);
                        $scope.loadershow = false;
                    }, function (data) {
                        disableScreen(0);
                        $scope.loadershow = false;
                    });
                } else
                    alert("please select export limit");
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