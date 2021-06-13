var app = angular.module('templateApp', [])
app.controller('templatesCtrl', function ($scope, $rootScope, $location, $http,$window) {

    $scope.SmslistArray = [];
    $scope.filterData = {};
    $scope.templateArray = {};
    $scope.totalCount = 0;
    $scope.editnotificationArray = {};

//    angular.element(document).ready(function () {
//
//        $("#datepicker1").datepicker({changeMonth: true, changeYear: true, dateFormat: 'dd-mm-yy'});
//        $("#datepicker2").datepicker({changeMonth: true, changeYear: true, dateFormat: 'dd-mm-yy'});
//
//
//    });


    $scope.add_param = function (PARAM) {

        $rootScope.$broadcast('add', ' ' + PARAM + ' ');
    }
    $scope.statuslist = [];
    $scope.shelvearray = [];
    //$scope.Substatuslist=[];

    $scope.subStatus = function () {
        console.log($scope.templateArray.status_id);
        $http.post('Templates/subStatus', {main_status: $scope.templateArray.status_id}).then(function (results) {
            console.log(results);
            if (results != null)
                $scope.Substatuslist = results.data;
            else
                $scope.Substatuslist = [];

        });
    };
    $scope.showStatusDrop = function () {
        $scope.baseUrl = new $window.URL($location.absUrl()).origin;
        if($scope.baseUrl == "https://localhost"){
            $scope.baseUrl = $scope.baseUrl+"/diggipacks/fullfillment";
        }
        $http.post($scope.baseUrl+'/Templates/getStatusDrop').then(function (results) {
            console.log(results.data);

            $scope.statuslist = results.data;

            //  $scope.shelvearray=[];
            // angular.forEach($scope.statuslist, function(results){      
            // 	$scope.shelvearray.push(results.main_status);          
            // });

            // var input = document.getElementById("show_status_dropdown");
            // var awesomplete = new Awesomplete(input);

            /* ...more code... */

            // awesomplete.list =$scope.shelvearray; 
            // console.log($scope.shelvearray);
        });
    };


    $scope.getSmslist = function (page_no, reset) {
        $scope.filterData.page_no = page_no;
       
        if (reset == 1)
        {
            $scope.SmslistArray = [];
        }
        if($scope.baseUrl == "https://localhost"){
            $scope.baseUrl = $scope.baseUrl+"/diggipacks/fullfillment";
        }
        $http.post( $scope.baseUrl+'/Templates/showSmsList', $scope.filterData).then(function (results) {
            $scope.totalCount = results.data.count;
            //console.log(results);
            if (results.data.result.length > 0)
            {
                angular.forEach(results.data.result, function (value)
                {
                    $scope.SmslistArray.push(value);

                });
            } else
            {
                $scope.nodata = true
            }
        });
    };

    $scope.getNotificationlist_alert = function (page_no, reset) {
        $scope.filterData.page_no = page_no;

        $http.post('Notification/showNotificationlist_alert', $scope.filterData).then(function (results) {

            $scope.NotificationlistArray = results;


        });
    };



    $scope.ShowactiveStatus = function (id, arabic_status) {
        //alert(id);
        $http.post('Templates/GetActivestatusUpdate', {id: id, arabic_status: arabic_status}).then(function (results) {
            console.log(results);
            //alert("Updated Successfully");
            //alert(sssss);  
            $state.reload();
        });
    };

    $scope.UpdateEnglishStatus = function (id, english_status) {
        //alert(id);
        $http.post('Templates/GetEnglishStatusUpdate', {id: id, english_status: english_status}).then(function (results) {
            console.log(results);
            //alert("Updated Successfully");
            //alert(sssss);  
            $state.reload();
        });
    };


    $scope.GetNotifydelete = function (id) {
        //alert (id); 
        $scope.baseUrl = new $window.URL($location.absUrl()).origin;
        if($scope.baseUrl == "http://localhost"){
            $scope.baseUrl = $scope.baseUrl+"/demofulfillment";
        }
        $http.post('Templates/get_delete_notify', {id: id}).then(function (results) {
            console.log(results);
            alert("Deleted Successfully");
           // $state.reload();
             window.location = $scope.baseUrl+"/show_template";
        });
    };
    $scope.submit_val = "Submit";
    $scope.AddTemplateform = function (add_sms) {
        //console.log(add_route);
         $scope.baseUrl = new $window.URL($location.absUrl()).origin;
         if($scope.baseUrl == "https://localhost"){
            $scope.baseUrl = $scope.baseUrl+"/diggipacks/fullfillment";
        }
        $scope.submit_val = "Submitting...";
        $http.post($scope.baseUrl+'/Templates/AddtemplateSave', {
            add_sms: add_sms
        }).then(function (results) {
            console.log(results);

            if (results.data == "true") {
                // $http.toast(results);
                alert("Successfully submited");
               // $state.go('show_template');
                window.location = $scope.baseUrl+"/show_template";
            } else

            {
                alert("all field are required");

            }
        });
    };

    $scope.EditSmsData = function (custdata)
    {
 smsid =$location.absUrl().split('/').pop ();
 console.log(smsid);
 
  $scope.baseUrl = new $window.URL($location.absUrl()).origin;
         if($scope.baseUrl == "https://localhost"){
            $scope.baseUrl = $scope.baseUrl+"/diggipacks/fullfillment";
        }
        $http.post($scope.baseUrl+'/Templates/GetSmsEditData', {smsid: smsid}).then(function (results) {
            console.log(results.data);
            $scope.templateArray = results.data;
            $scope.editsmslist.smsid =  results.data.id;

        });
    };
    $scope.update_val = "Update";
    $scope.EditSmsform = function (edit_sms) {
        //console.log(edit_staff);  
        $scope.update_val = "Updating...";
        $http.post('Templates/EditSmsform', {
            edit_sms: $scope.editsmslist
        }).then(function (results) {
            console.log(results);

            if (results == 'true') {
                alert("Updated Successfully");
                $state.go('show_template');

            } else
            {
                alert("all field are required");
                //$scope.errormess=results.error;
            }
        });
    };
});

app.directive('myText', ['$rootScope', function ($rootScope) {
        return {
            link: function (scope, element, attrs) {
                $rootScope.$on('add', function (e, val) {
                    var domElement = element[0];

                    if (document.selection) {
                        domElement.focus();
                        var sel = document.selection.createRange();
                        sel.text = val;
                        domElement.focus();
                    } else if (domElement.selectionStart || domElement.selectionStart === 0) {
                        var startPos = domElement.selectionStart;
                        var endPos = domElement.selectionEnd;
                        var scrollTop = domElement.scrollTop;
                        domElement.value = domElement.value.substring(0, startPos) + val + domElement.value.substring(endPos, domElement.value.length);
                        domElement.focus();
                        domElement.selectionStart = startPos + val.length;
                        domElement.selectionEnd = startPos + val.length;
                        domElement.scrollTop = scrollTop;
                    } else {
                        domElement.value += val;
                        domElement.focus();
                    }

                });
            }
        }
    }])