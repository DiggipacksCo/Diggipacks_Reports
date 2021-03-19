var app = angular.module('AppPickedPage', [])



        .controller('PickedViewCtr', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            $scope.filterData = {};
            $scope.shipData = [];
            $scope.Items = []
            $scope.awbArray = [];
            $scope.scan = {};
            $scope.btnfinal = true;
            $scope.$totalpicCount = 0;
            $scope.PickerShipmentList = function (pickupId)
            {
                pickupId
                $scope.filterData.pickupId = pickupId;
                $http({
                    url: $scope.baseUrl + "/PickUp/PickedListData",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (response) {
                    console.log(response)
                    //$scope.shipData=response.data;
                    $scope.totalpicCount = response.data.tpiece;
                    if (response.data.result.length > 0) {

                        angular.forEach(response.data.result, function (value) {
                            //console.log(value)


                            // console.log(value1.piece);
                            $scope.shipData.push({'slip_no': value.slip_no, 'sku': value.sku,'sku_view': value.sku, 'piece': value.piece , 'slip_details': value.slip_details, 'location': value.location, 'picker': value.picker, 'scaned': 0, 'extra': 0, 'tscaned': 0});



                        });
                        // $scope.shipData.push({'tscaned':response.data.tpiece});
                    }
                    console.log($scope.shipData);
                });
            }
            $scope.scan_awb = function () {
                $('#scan_awb').focus();
                //console.log($scope.shipData);

                $scope.scanCheck();



            }
            $scope.arrayIndexnew = [];
            $scope.scanCheck = function ()
            {
                //alert($scope.scan.slip_no);


                $scope.arrayIndexnew = $scope.shipData.findIndex(record => (record.sku.toUpperCase() === $scope.scan.slip_no.toUpperCase()))
                //console.log($scope.arrayIndexnew);console.log("tttttttttttt");
                if ($scope.arrayIndexnew != -1)
                {
                    if (parseInt($scope.shipData[$scope.arrayIndexnew].scaned) < parseInt($scope.shipData[$scope.arrayIndexnew].piece))
                    {
                        $scope.shipData[$scope.arrayIndexnew].scaned = parseInt($scope.shipData[$scope.arrayIndexnew].scaned) + 1;
                        $scope.shipData[$scope.arrayIndexnew].tscaned = parseInt($scope.shipData[$scope.arrayIndexnew].tscaned) + 1;

                        if (parseInt($scope.shipData[$scope.arrayIndexnew].scaned) == parseInt($scope.shipData[$scope.arrayIndexnew].piece))
                        {
                            $scope.checkComplte($scope.shipData, $scope.shipData[$scope.arrayIndexnew].piece, $scope.shipData[$scope.arrayIndexnew].sku, $scope.shipData[$scope.arrayIndexnew].tscaned);
                            $scope.scan.slip_no = null;
                            $scope.Message = null;
                            $scope.warning = 'All Parts Scanned for ' + $scope.shipData[$scope.arrayIndexnew].sku;
                            responsiveVoice.speak($scope.warning);
                        } else
                        {

                            $scope.scan.slip_no = null;
                            $scope.Message = 'Scaned!';
                            //responsiveVoice.speak($scope.message);    
                            responsiveVoice.speak('Scaned!');
                        }


                    } else
                    {
                        $scope.scan.slip_no = null;
                        // $scope.scan.slip_no=null;
                        //$scope.shipData[$scope.arrayIndexnew].scaned=parseInt($scope.shipData[$scope.arrayIndexnew].scaned)+1; 
                        //$scope.shipData[$scope.arrayIndexnew].extra=parseInt($scope.shipData[$scope.arrayIndexnew].extra)+1;
                        $scope.Message = null;
                        $scope.warning = 'already scan';
                        responsiveVoice.speak($scope.warning);
                        //$scope.warning='Shipment Already scanned';
                        var sound = document.getElementById("audio");
                        sound.play();

                    }



                } else
                {
                    if ($scope.scan.slip_no.length > 0)
                    {
                        $scope.scan.slip_no = null;
                        //$scope.scan.slip_no=null;
                        $scope.Message = null;
                        $scope.warning = $scope.scan.slip_no + ', SKU not available for this shipment!';
                        responsiveVoice.speak('SKU not available for this shipment!');
                    } else

                    {

                    }


                }
                //console.log($scope.shipData);


            }
            $scope.completeArray = [];
            // $scope.checkArray=[]; 
            $scope.checktscaned = 0;
            $scope.checkComplte = function (dataArray, piece, sku, tscaned)
            {
                //alert(tscaned);
                //console.log(dataArray);
                //console.log("ssssssss");
                $scope.checkArray = [];
                angular.forEach(dataArray, function (value) {
                    console.log(value.sku + "" + sku)
                    if (value.sku == sku)
                    {
                        //alert("sssss");
                        $scope.checkArray.push(value);


                    }
                });
                console.log($scope.checkArray);
                $scope.checkqty = 0;
                angular.forEach($scope.checkArray, function (value) {
                    $scope.checktscaned += tscaned;
                    if (value.piece == value.scaned)
                    {
                        $scope.checkqty++
                    }



                });
                //  $scope.checktscaned

                if ($scope.checkArray.length == $scope.checkqty && $scope.checkqty > 0)
                {


                    //alert($scope.checkqty);
                    //console.log("sssssssss");
                    //alert($scope.filterData.pickupId);

                    $scope.inxexComp = $scope.completeArray.findIndex(record => (record.sku === $scope.scan.slip_no))
                    if ($scope.inxexComp == -1)
                    {
                        $scope.completeArray.push({'pickupId': $scope.filterData.pickupId, 'slip_no': $scope.checkArray[0].sku, 'piece': $scope.checkArray[0].piece, 'tscaned': $scope.checkArray[0].piece, 'location': $scope.checkArray[0].location, 'picker': $scope.checkArray[0].picker});
                        //$scope.btnfinal=false;

                    }

                    // $scope.warning=null;
                    if ($scope.totalpicCount == $scope.checktscaned)
                    {
                        //alert("ssssssss");
                        $scope.btnfinal = false;
                        var soundsuccess = document.getElementById("audioSuccess");
                        soundsuccess.play();
                        $scope.Message = $scope.checkArray[0].sku + ' Completly Scanned Please Pack this Order!';
                    }





                    //responsiveVoice.speak($scope.message);  



                    responsiveVoice.speak('Completly Scanned, Please Pack this Order!');
                }
                console.log($scope.completeArray);
            }

            $scope.finishScan = function ()
            {
                if ($scope.completeArray.length > 0)
                {
                    var isconfirm = confirm('Are You sure? after verication you will have to scan sort shipments again.! ');

                    if (isconfirm)
                    {
                        $http({
                            url: $scope.baseUrl + "/PickUp/PickedBatchFinish",
                            method: "POST",
                            data: {
                                shipData: $scope.completeArray,
                                exportData: $scope.shipData,
                                pickupId: $scope.filterData.pickupId
                            },
                            headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                        }).then(function (response) {
                            console.log(response);

                            var d = new Date();
                            var $a = $("<a>");
                            $a.attr("href", response.data.file);
                            $("body").append($a);
                            $a.attr("download", response.data.file_name);
                            $a[0].click();
                            $a.remove();


                            $scope.shipData = [];
                            $scope.completeArray = [];
                            $scope.Message = "Completed order Packed!";


                        }, function (error) {
                            console.log(error);
                        });
                    }

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
        