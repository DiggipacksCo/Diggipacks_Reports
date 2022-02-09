var app = angular.module('returnShipment', [])

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
            $scope.remarkBox = false;
            $scope.loadershow = false;
               $scope.error_slip = {};
            $scope.error_slip__status = {};
            $scope.error_slip_succ = {};

            

            $scope.scan_awb = function () {
                $scope.error_slip_succ = {};
                disableScreen(1);
                $scope.loadershow = true;
                $http({
                    url: "ReturnShipment/validatereturn",
                    method: "POST",
                    data: $scope.scan,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    
                    $scope.returnArray = response.data;
                    $scope.scan.validate = response.data.valid;
                    $scope.invalidstring = response.data.invalid;
                    $scope.totalstep1 = response.data.total;
                    $scope.totalstep2 = $scope.totalstep1 - $scope.invalidstring.length;
                    disableScreen(0);
                    $scope.loadershow = false;

                }, function (data) {
                    disableScreen(0);
                    $scope.loadershow = false;
                });
                //console.log($scope.returnArray);
                $scope.warning = null;
                $scope.Message = null;
                $scope.arrayIndexnew = [];


            }
            $scope.step = 1;
            $scope.nextpage = function (id)
            {
                disableScreen(1);
                $scope.loadershow = true;
                $scope.step = id;
                disableScreen(0);
                $scope.loadershow = false;
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


            $scope.checkValue = function (indexValue, checktype)
            {
                $scope.piece = parseInt($scope.scan.validate[indexValue].piece);
                $scope.missing = $scope.scan.validate[indexValue].missing;
                $scope.damage = $scope.scan.validate[indexValue].damage;
                // console.log( ''+$scope.piece)
                // console.log( $scope.piece)


                if (checktype == 'missing')
                {
                    if ($scope.piece < ($scope.missing + $scope.damage))
                    {
                        $scope.scan.validate[indexValue].msgmiss = $scope.missing + ' is  invalid for this order';
                        $scope.scan.validate[indexValue].missing = 0;
                    } else
                    {
                        $scope.scan.validate[indexValue].msgmiss = null;
                    }
                }
                if (checktype == 'damage')
                {
                    if ($scope.piece < ($scope.missing + $scope.damage))
                    {
                        $scope.scan.validate[indexValue].msgmdam = $scope.damage + ' is invalid for this order';
                        $scope.scan.validate[indexValue].damage = 0;
                    } else
                    {
                        $scope.scan.validate[indexValue].msgmdam = null;
                    }



                }
            }

         
            $scope.updateData = function ()
            {
                var isconfirm = confirm('Are You sure? after verication you will have to scan sort shipments again.! ');

                if (isconfirm)
                {
                    disableScreen(1);
                    $scope.loadershow = true;

                    $http({
                        url: "ReturnShipment/updateData",
                        method: "POST",
                        data: {'valid_list':$scope.scan.validate,'comment':$scope.scan.comment},
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                        disableScreen(0);
                        $scope.loadershow = false;
                        $scope.step = 1;
                        //$scope.scan.validate=[];
                        $scope.scan={};                                         
                        $scope.error_slip = response.data.invalid_slip;
                        $scope.error_slip__status = response.data.invalid_status;
                        $scope.error_slip_succ = response.data.success;



                    })
                }
                ;

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