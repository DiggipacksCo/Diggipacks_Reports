var app = angular.module('ReturnLmApp', [])

        .controller('scanShipment', function ($scope, $http, $interval, $window) {
            $scope.shipData = [];
            $scope.completeShip = [];
            $scope.scan = {};
            $scope.specialtype = {};
            $scope.boxArray = {};
            $scope.showboxArray = {};
            $scope.awbArray = [];
            $scope.shelve = null;
            $scope.tableshow = false;
            $scope.LocationArr = {};
            $scope.awbcolmunBtn = false;
            $scope.btnfinal = true;
            $scope.boxshow1 = false;
            $scope.UpdateArr = {};
            $scope.scan_awb = function () {
                $('#scan_awb').focus();
                $scope.packuShip();
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
            $scope.packuShip = function () {

                $scope.warning = null;
                $scope.Message = null;
                $scope.arrayIndexnew = [];
                $scope.scan.slip_no = $scope.scan.slip_no.toUpperCase()
                $scope.arrayIndex = $scope.awbArray.findIndex(record => record.slip_no.toUpperCase() === $scope.scan.slip_no.toUpperCase());
                if ($scope.arrayIndex == -1)
                {
                    //console.log($scope.scan);
                    $http({
                        url: "PickUp/CheckReturnFulfil",
                        method: "POST",
                        data: $scope.scan,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                        if (response.data.count > 0)
                        {
                            $scope.awbcolmunBtn = true;
                            $scope.tableshow = true;
                        }

                        //console.log(response);
                        //$scope.specialtype.specialpack=true;
                        //$scope.specialtype.specialpacktype="warehouse";
                        if (response.data.count == 0)
                        {
                            $scope.warning = "Order Not available for RTF!";
                            responsiveVoice.speak($scope.warning);
                        }
                        angular.forEach(response.data.result, function (value) {
                            console.log(value)

                            $scope.awbArray.push(value);
                            angular.forEach(JSON.parse(value.sku), function (value1) {
                                console.log(value1)

                                $scope.shipData.push({'slip_no': value.slip_no,'item_path':value1.item_path, 'sku': value1.sku, 'piece': value1.piece, 'scaned': 0});

                                //$scope.Items.push( 'slip_no: ' +value.slip_no);
                            });

                            //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });


                    });
                }

                $scope.scanCheck();
                $scope.checkComplte($scope.shipData, $scope.scan.slip_no);

            }
            $scope.Getallspecialpackstatus = function ()
            {

                // $scope.completeArray.specialtype=$scope.specialtype.specialpacktype;
                //  $scope.completeArray.specialpack=$scope.specialtype.specialpack;
                //$scope.specialtype.specialpack=true;
                //$scope.completeArray.push({'specialpack':$scope.specialtype.specialpack,'specialpacktype':$scope.specialtype.specialpacktype});
                console.log($scope.specialtype);
            }

            $scope.getcheckbutton = function (val)
            {
                if ($scope.boxArray.stock_location)
                {
                    //$scope.btnfinal=false;
                    $scope.boxshow = true;
                }

            }
            $scope.scanCheck = function ()
            {
                $scope.arrayIndexnew = $scope.shipData.findIndex(record => (record.slip_no.toUpperCase() === $scope.scan.slip_no.toUpperCase() && record.sku.toUpperCase() === $scope.scan.sku.toUpperCase()))
                // $scope.arrayIndexnew= $scope.shipData.findIndex( record => (record.slip_no ===$scope.scan.slip_no && record.sku ===$scope.scan.sku ))
                if ($scope.arrayIndexnew != -1)
                {
                    if (parseInt($scope.shipData[$scope.arrayIndexnew].scaned) < parseInt($scope.shipData[$scope.arrayIndexnew].piece))
                    {
                        $scope.shipData[$scope.arrayIndexnew].scaned = parseInt($scope.shipData[$scope.arrayIndexnew].scaned) + 1;
                        if (parseInt($scope.shipData[$scope.arrayIndexnew].scaned) == parseInt($scope.shipData[$scope.arrayIndexnew].piece))
                        {
                            $scope.Message = null;
                            $scope.warning = 'All Parts Scanned for ' + $scope.shipData[$scope.arrayIndexnew].sku;
                            responsiveVoice.speak($scope.warning);
                        } else
                        {
                            $scope.Message = 'Scaned!';
                            //responsiveVoice.speak($scope.message);    
                            responsiveVoice.speak('Scaned!');
                        }


                    } else
                    {

                        //$scope.shipData[$scope.arrayIndexnew].scaned=parseInt($scope.shipData[$scope.arrayIndexnew].scaned)+1; 
                        // $scope.shipData[$scope.arrayIndexnew].extra=parseInt($scope.shipData[$scope.arrayIndexnew].extra)+1;
                        $scope.Message = null;
                        $scope.warning = 'already Scaned all piece';
                        responsiveVoice.speak($scope.warning);
                        //$scope.warning='Shipment Already scanned';
                        var sound = document.getElementById("audio");
                        sound.play();

                    }



                } else
                {
                    if ($scope.scan.sku.length > 0)
                    {
                        $scope.Message = null;
                        $scope.warning = $scope.scan.sku + ', SKU not available for this shipment!';
                        responsiveVoice.speak('SKU not available for this shipment!');
                    } else

                    {

                    }


                }
                $scope.scan.sku = null;
            }

            $scope.completeArray = [];
            // $scope.checkArray=[];  
            $scope.checkComplte = function (dataArray, slip_no)
            {
                $scope.checkArray = [];
                angular.forEach(dataArray, function (value) {

                    if (value.slip_no == slip_no)
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
                    $scope.inxexComp = $scope.completeArray.findIndex(record => (record.slip_no === $scope.scan.slip_no))
                    if ($scope.inxexComp == -1)
                    {

                        $scope.completeArray.push({'slip_no': $scope.checkArray[0].slip_no});
                    }

                    $scope.GetshowStocklocation(dataArray);
                    $scope.boxshow1 = true;
                    $scope.warning = null;
                    var soundsuccess = document.getElementById("audioSuccess");
                    soundsuccess.play();
                    $scope.Message = $scope.checkArray[0].slip_no + ' Completly Scaned Please RTF order!';
                    //responsiveVoice.speak($scope.message);  



                    responsiveVoice.speak('Completly Scaned, Please RTF order!');
                }
                console.log($scope.completeArray);
            }
            $scope.Clountshowlocation = "";
            $scope.LocationError = false;


            $scope.GetshowStocklocation = function (dataArray)
            {


                $http({
                    url: "PickUp/GetallstockLocationUser",
                    method: "POST",
                    data: dataArray,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //console.log(response)
                    // if (response.data.status == '000')
                    // {
                        $scope.btnfinal = false;
                        $scope.LocationArr = response.data;
                        console.log($scope.LocationArr);
                        $scope.LocalItem = response.data;
                        console.log($scope.LocalItem);
                        $scope.LocationError = false;
                    // } else
                    // {
                    //     $scope.btnfinal = true;
                    //     $scope.LocationError = true;
                    // }
                    $scope.Clountshowlocation = response.data.CountLocation;
                    $scope.boxArray.locationcount = response.data.CountLocation;

                })
            };

            $scope.finishScan = function ()
            {

                //$scope.UpdateArr.stock_location=$scope.boxArray.stock_location;
                // $scope.UpdateArr.type=$scope.specialtype.specialpacktype;
                if ($scope.completeArray.length > 0)
                {
                    var isconfirm = confirm('Are You sure? after verication you will have to scan sort shipments again.! ');

                    if (isconfirm)
                    {
                        $http({
                            url: "PickUp/save_details",
                            method: "POST",
                            data: $scope.LocalItem,                              
                            
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
                                $scope.awbcolmunBtn = false;
                                $scope.boxshow1 = false;
                                $scope.tableshow = false;
                                $scope.scan = {};
                                $scope.scan.slip_no = "";
                                $scope.shipData = {};
                                $scope.completeArray = {};
                                $scope.Message = "Completed order RTF!";
                           

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
/*------ /show shipments-----*/