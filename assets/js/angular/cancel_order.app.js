var app = angular.module('CancelApp', [])

        .controller('scanShipment', function ($scope, $http, $interval, $window,$location) {
            $scope.shipData = [];

            $scope.scan = {};
            $scope.awbArray = [];

            $scope.tableshow = false;
            $scope.UpdateArr = {};
            $scope.scan_awb = function () {
               // $('#scan_awb').focus();
               
                $scope.packuShip();
                
            }


            $scope.packuShip = function () {

                $scope.warning = null;
                $scope.Message = null;
                $scope.arrayIndexnew = [];
                $scope.scan.slip_no = $scope.scan.slip_no.toUpperCase()
                $scope.arrayIndex = $scope.shipData.findIndex(record => record.slip_no.toUpperCase() === $scope.scan.slip_no.toUpperCase());
              
                if ($scope.arrayIndex== -1)
                {
                    
                    //console.log($scope.scan);
                    $http({
                        url: "PickUp/CheckCancelOrder",
                        method: "POST",
                        data: $scope.scan,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                         $scope.scan.slip_no="";
                         
                       
                          
                        if (response.data.count > 0)
                        {
                           $scope.Message = "AWB Number Scanned";
                            responsiveVoice.speak($scope.Message);
                            $scope.warning =null;
                        
                            // $scope.awbcolmunBtn = true;
                             $scope.tableshow = true;
                             
                              angular.forEach(response.data.result, function (value) {

                            $scope.shipData.push(value);
                        });
                             
                        }
                        
                        if ($scope.shipData.length > 2)
                        {
                         $scope.Message=null;   
                        }

                        if (response.data.count == 0)
                        {
                             $scope.Message=null;
                            $scope.warning = "Order Not available for Cancel";
                            responsiveVoice.speak($scope.warning);
                        }
                       
                       


                    });
                }
                else
                {
                     if ( $scope.arrayIndex==0)
                        {
                             $scope.Message=null;
                             $scope.warning = "This AWB Number already Scanned";
                            responsiveVoice.speak($scope.warning);
                            $scope.scan.slip_no="";
                            
                        }
                }




            }







            $scope.finishScan = function ()
            {

               if ($scope.shipData.length > 0)
                {
                    var isconfirm = confirm('Are You sure want to cancel order ?');

                    if (isconfirm)
                    {
                        $http({
                            url: "PickUp/GetcancelOrderFinal",
                            method: "POST",
                            data: {
                                shipData: $scope.shipData,
                                postdata: $scope.scan,
                               
                            },
                            headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                        }).then(function (response) {
                            console.log(response);
                           if(response.data.status== true){
                            $scope.Message = "Successfully Order Canceled!";
                           }
                           else {
                             $scope.notice = "Oops! Order can't be Cancel ";
                           }

                          //alert("Successfully Order Canceled!");
                          //$window.location.reload();

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