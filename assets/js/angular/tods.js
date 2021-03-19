var app = angular.module('TodsAPp', [])
        .controller('TodsCtrl', function ($scope, $http, $window, $location) {
//	alert("ssssssss");
            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            $scope.filterData = {};
            $scope.shipData = [];
            $scope.Items = []

//console.log($scope.baseUrl);
            $scope.loadMore = function (page_no, reset, type)
            {

                $scope.filterData.page_no = page_no;
                $scope.filterData.type = type;
                $scope.filterData.status = 1;
                if (reset == 1)
                {
                    $scope.shipData = [];
                    $scope.Items = [];
                }

                $http({
                    url: $scope.baseUrl + "/Shelve/todsfiltershow",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //console.log(response)
                    $scope.totalCount = response.data.count;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            $scope.shipData.push(value);
                        });

                    } else {
                        $scope.nodata = true
                    }
                })
            };

        })

/*------ /show shipments-----*/