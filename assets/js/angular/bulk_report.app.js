var app = angular.module('BulkreportApp', ['betsol.timeCounter'])

  .controller('BulkreportCtrl', function ($scope, $http, $window,$location) {
            $scope.filterData = {};
            $scope.shipData = [];
            $scope.excelshipData = [];
            $scope.dropexport = [];
            $scope.Items = [];
            $scope.dropshort = {};
            $scope.loadershow = false;
          
            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            document.getElementById('Newaddfrm').disabled=false;
          
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
            $scope.setFocus=function(id,type)
            { 
               /// console.log();
                if(type=='sh')
                {
                    document.getElementById('st_'+id).value='';
                    document.getElementById('st_'+id).focus();
                }
                else
                {
                   if(document.getElementById('sh_'+(id+1))!=undefined) 
                   {
                    document.getElementById('sh_'+(id+1)).value='';
                    document.getElementById('sh_'+(id+1)).focus();
                   }
                   
                }
             
            }
            $scope.checkall = false;
            $scope.toggleAll = function () {
                $scope.checkall = !$scope.checkall;
                console.log("?dsfsdf");

                for (var key in $scope.listData2) {
                    $scope.listData2[key] = $scope.checkall;
                }
            };

            $scope.Getcheckbutton=function()
            {
                document.getElementById('Newaddfrm').disabled=false;
            }
            $scope.loadMore = function (page_no, reset)
            {
                document.getElementById('Newaddfrm').disabled=false;
                disableScreen(1);
                    $scope.loadershow = true;
                $scope.filterData.page_no = page_no;
                if (reset == 1)
                {
                    $scope.count=1;
                    $scope.shipData = [];
                }

                $http({
                    url: "Bulkdownload/filter",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                  
                    $scope.dropshort = response.data.dropshort;
                    $scope.totalCount = response.data.count;
                    $scope.shipDataexcel = response.data.excelresult;
                    $scope.dropexport = response.data.dropexport;

                    disableScreen(0);
                    $scope.loadershow = false;



                }, function (status, error) {

                    disableScreen(0);
                    $scope.loadershow = false;
                })


            };

            $scope.loadMoreReverse = function (page_no, reset)
            {
                //  disableScreen(1);
                //$scope.loadershow=true; 
                console.log(page_no);
                // console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                if (reset == 1)
                {
                    $scope.count=1;
                    $scope.shipData = [];
                }

                $http({
                    url: "Shipment/filterReverse",
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
                            console.log(value['3pl_pickup_date'])
                            value.mydate = new Date(value['3pl_pickup_date']);
                            //if(value['3pl_pickup_date'])
                            $scope.shipData.push(value);

                        });
                        console.log( $scope.shipData)
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



             $scope.runshell_tracking = function ()
            {
                
                $http({
                    url: "Shipment/runshell_tracking",
                    method: "POST",                   
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    
                    alert("Sync process has been start. Please wait for 30 minute to update data. ");
                    

                })

            }



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
                
                $("#StocklocationModal").modal('hide');
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


            $scope.GetexportReport = function () {

              
         

                $http({
                    url: "Bulkdownload/getexceldata",
                    method: "POST",
                    data:$scope.filterData,
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