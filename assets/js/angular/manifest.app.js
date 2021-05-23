var app = angular.module('AppManifest', [])


        .controller('IteminventoryAdd', function ($scope, $http, $window, $location) {
        })
        .controller('Ctrmanifest', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            $scope.AssignData = {};
            $scope.filterData = {};
            $scope.shipData = [];
            $scope.Items = [];
            $scope.message = [];
            $scope.pickerArray = [];
            $scope.MupdateData = {};
            $scope.Updateqtyconf = {};
            $scope.stockLocation = {};
            $scope.UpdateHold = {};
            $scope.StorageData = {};
            $scope.countboxval = false;
            $scope.countboxvalmess = true;
            $scope.StorageArray = {};
            $scope.skuArraydetails = {};
            $scope.loadershow = false;
            $scope.assigndata = {};
            $scope.returnUpdate = {};
            $scope.courierData = {};
            $scope.StaffListArr = {};

            $scope.SeachSkuList = [];

            $scope.staffpage = null;

            $scope.returnUpdate.assign_type = "D";
            $scope.driverbtn = true;
            $scope.crourierbtn = false;




            $scope.updatemanifeststatus = function (id, mid, sid, tqty, cqty, ptqy)
            {
//alert(ptqy);

                $scope.MupdateData.id = id;
                $scope.MupdateData.mid = mid;
                $scope.MupdateData.ptqy = ptqy;
                $scope.MupdateData.cqty = cqty;
                //$scope.MupdateData.sku= sku;  
                $scope.MupdateData.tqty = tqty;
                $scope.MupdateData.seller_id = sid;


                $http({
                    url: "Manifest/GetallskuDetailsByOneGroup",
                    method: "POST",
                    data: {mid: mid},
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //$scope.SeachSkuList = response.data;

                    angular.forEach(response.data, function (value) {
                        $scope.SeachSkuList.push({'qty': value.qty, 'sku': value.sku, 'scan': 0, 'item_path': value.item_path, 'id': $scope.MupdateData.id, 'mid': $scope.MupdateData.mid, 'ptqy': $scope.MupdateData.ptqy, 'cqty': $scope.MupdateData.cqty, 'tqty': $scope.MupdateData.tqty, 'seller_id': $scope.MupdateData.seller_id, 'status': 'pending', 'newtotal': 0});

                    });

                });
                $("#exampleModal").modal({backdrop: 'static',
                    keyboard: false})


                //console.log($scope.MupdateData);


            };

            $scope.invalidSslip_no = {};
            $scope.Success_msg = {};
            $scope.Error_msg = {};
            $scope.saveassigntodriver = function ()
            {

               


                if ($scope.returnUpdate.assign_type == 'D')
                {
                    $scope.returnUpdate.cc_id = "";
                }
                if ($scope.returnUpdate.assign_type == 'CC')
                {
                    $scope.returnUpdate.assignid = "";
                }
                if ($scope.returnUpdate.assignid || $scope.returnUpdate.cc_id)
                {
                    $scope.loadershow = true;
                    disableScreen(1);


                    $scope.returnUpdate.order_type = 'return_o';

                    $http({
                        url: "Manifest/getupdateassign",
                        method: "POST",
                        data: $scope.returnUpdate,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                        //alert(response);
                        console.log(response);
                        // $scope.message=response.data;
                        // $scope.AssignData.assignid="";
                        // $scope.response="";
                        if (response)
                        {
                            if (response.data.status == 'succ')
                            {
                                alert("Successfully Assign");
                                $window.location.href = '/showpickuplist';

                                //location.reload();
                            }
                          
                         else
                            {
                                $scope.invalidSslip_no = response.data.invalid_slipNO;
                                $scope.Success_msg = response.data.Success_msg;
                                $scope.Error_msg = response.data.Error_msg;
                            }
                        } else
                        {
                            alert("try again");
                        }

                    });
                    disableScreen(0);
                    $scope.loadershow = false;
                } else
                {
                    if ($scope.returnUpdate.assign_type == 'D')
                        alert("Please Select Driver");
                    else
                        alert("Please Select Courier Company");
                }
            }


            $scope.Getpickupimgview = function (imgpath)
            {
//alert(ptqy);

                $("#Shopickimgmodel").modal({backdrop: 'static',
                    keyboard: false})
                $scope.MupdateData.imgpath = imgpath;


                //console.log($scope.MupdateData);


            }
            $scope.staffUpdateAssignArr = {};
            $scope.GetpopAssignStafflist = function (m_id)
            {
                $scope.staffUpdateAssignArr.mid = m_id;
                $("#showAssignStaffPOP_id").modal({backdrop: 'static', keyboard: false});
                $http({
                    url: "Manifest/GetStaffListDrop",
                    method: "POST",

                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {

                    $scope.StaffListArr = response.data;

                });
            }


            $scope.GetUpdateStaffAssign = function ()
            {

                $http({
                    url: "Manifest/GetUpdateStaffAssign",
                    method: "POST",
                    data: $scope.staffUpdateAssignArr,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {

                    alert("Successfully Assigned!");
                    $window.location.reload();

                });

            }


            $scope.GetChangeAssignType = function (type)
            {
                if (type == 'D')
                {
                    $scope.driverbtn = true;
                    $scope.crourierbtn = false;
                    $scope.returnUpdate.assign_type = "D";
                } else
                {
                    $scope.driverbtn = false;
                    $scope.crourierbtn = true;
                    $scope.returnUpdate.assign_type = "CC";
                }
                // alert();

            };



            $scope.GetreturnItemsPop = function (mid)
            {

                $scope.returnUpdate.mid = mid;
                $http({
                    url: $scope.baseUrl + "/Manifest/GetreturnCourierDropShow",
                    method: "POST",
                    data: {mid: mid},
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    $("#PopidreturnShowitem").modal({backdrop: 'static',
                        keyboard: false});
                    $scope.assigndata = response.data.assignuser;
                    $scope.courierData = response.data.courierData;
                });
            }

            $scope.showonholdorder_pop = function (uid, sid)
            {
//alert(ptqy);

                //$("#ConfirmPOPid").modal() 

                $scope.UpdateHold.uid = uid;
                $scope.UpdateHold.sid = sid;

                //console.log($scope.MupdateData);
                $http({
                    url: "Manifest/GetupdateOnholdData",
                    method: "POST",
                    data: $scope.UpdateHold,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response);
                    ///$scope.MupdateData.pendingqty= response.data.totalqty; 
                    if (response.data = "true")
                    {
                        alert("successfully Updated Hold On");
                        //$scope.MupdateData.sku="";
                        location.reload();
                    } else
                    {
                        alert("already updated");
                    }

                    //$scope.MupdateData.sku="";


                }, function (error) {
                    console.log(error);
                })



            }
            $scope.warehouseArr = {};
            $scope.showconfirmorder_pop = function (uid, sid)
            {
//alert(ptqy);



                $scope.Updateqtyconf.uid = uid;
                $scope.Updateqtyconf.sid = sid;



                $http({
                    url: "Manifest/Getallsellerstocklocations",
                    method: "POST",
                    data: $scope.Updateqtyconf,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response)
                    //alert("sssss");
                    //$scope.totalCount=response.data.count;
                    $scope.stockLocation = response.data.result;
                    $scope.Updateqtyconf = response.data;
                    // $scope.countbox=response.data.countbox;
                    //$scope.countarray=response.data.countarray;
                    $scope.warehouseArr = response.data.warehouseArr;

                    $scope.StorageData = response.data.sotrageTypes;
                    $("#ConfirmPOPid").modal({backdrop: 'static', keyboard: false});

                    if (response.data.countbox == response.data.countarray)
                    {
                        $scope.countboxval = true;
                        $scope.countboxvalmess = false;
                    } else
                    {
                        $scope.countboxvalmess = true;
                    }
                    //$('.js-example-basic-multiple').trigger('change.select2'); 


                })
                //console.log($scope.MupdateData);


            }
            $scope.addskufielddata_pop = function (sku, uid)
            {
//alert(ptqy);
                $scope.skushow = sku;

                $http({
                    url: "Manifest/GetalladdskuotherDrops",
                    method: "POST",
                    data: {uid: uid},
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response);
                    $scope.StorageArray = response.data.store;
                    $scope.skuArraydetails = response.data.skudetails;
                    $("#addSkudetailspop").modal({backdrop: 'static', keyboard: false});
                })

            }

            $scope.Closewidowprces = function ()
            {
                location.reload();
            }
            $scope.errorinvalidpallet = {};
            $scope.erroralreadypallett = {};
            $scope.erroremptypallet = {};
            $scope.getsaveconfirmOrders = function ()
            {
                disableScreen(1);
                $scope.loadershow = true;

                //console.log($scope.Updateqtyconf);
                //alert("sssssss");
                //console.log($scope.MupdateData);
                $http({
                    url: "Manifest/GetItemInventoryDataadd",
                    method: "POST",
                    data: $scope.Updateqtyconf,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response.data.success);
                    $scope.errorinvalidpallet = response.data.invalidpallet;
                    $scope.erroralreadypallett = response.data.alreadypallet;
                    $scope.erroremptypallet = response.data.emptypallet;
                    //alert(response.data.success.length);

                    //response.data.success.length
                    //invalidpallet
                    disableScreen(0);
                    $scope.loadershow = false;

                    if (response.data.success != undefined)
                    {
                        ///$scope.MupdateData.pendingqty= response.data.totalqty; 
                        alert("successfully Updated");
                        $scope.MupdateData.sku = "";
                        location.reload();

                    }



                    //$scope.MupdateData.sku="";


                })
                /*else
                 {
                 alert("Please Select Location.");
                 }*/
            }
            $scope.messageshow_al = null;
            $scope.messageshow_wl = null;
            $scope.newtotalQty = 0;
            $scope.ConfrmBtnDis = false;
            $scope.getsavemanifestrecevedata = function ()
            {
                // alert($scope.MupdateData.sku);
                console.log($scope.SeachSkuList);
                $scope.arrayIndex = $scope.SeachSkuList.findIndex(record => record.sku.toUpperCase() === $scope.MupdateData.sku.toUpperCase());

                // alert($scope.arrayIndex);
                if ($scope.arrayIndex !== -1)
                {

                    if (parseInt($scope.SeachSkuList[$scope.arrayIndex].qty) > parseInt($scope.SeachSkuList[$scope.arrayIndex].scan))
                    {
                        console.log("ssssssss");


                        $scope.SeachSkuList[$scope.arrayIndex].scan = parseInt($scope.SeachSkuList[$scope.arrayIndex].scan) + 1;
                        $scope.SeachSkuList[$scope.arrayIndex].newtotal = parseInt($scope.SeachSkuList[$scope.arrayIndex].newtotal) + 1;
                        $scope.newtotalQty = parseInt($scope.newtotalQty) + 1;
                        $scope.messageshow_al = "Scanned!";
                        $scope.messageshow_wl = null;
                        $scope.ConfrmBtnDis = true;
                    }
                    // console.log($scope.SeachSkuList[$scope.arrayIndex].tqty+"////////"+ $scope.newtotalQty);



                    if ($scope.SeachSkuList[$scope.arrayIndex].scan == $scope.SeachSkuList[$scope.arrayIndex].qty)
                    {
                        $scope.SeachSkuList[$scope.arrayIndex].status = 'Compeleted';
                        $scope.messageshow_wl = "Completed for " + $scope.SeachSkuList[$scope.arrayIndex].sku;
                        $scope.messageshow_al = null;
                    }

                    if ($scope.SeachSkuList[$scope.arrayIndex].tqty == $scope.newtotalQty)
                    {
                        $scope.messageshow_al = null;
                        $scope.messageshow_wl = "all part are Scanned. Please Confirm";
                        // $scope.ConfrmBtnDis=true;

                    }

                } else
                {
                    $scope.messageshow_al = null;
                    $scope.messageshow_wl = "sku not found";
                }
                $scope.MupdateData.sku = null;
//                
            }
$scope.data={};
 $scope.check_shelve = function ()
            {



                //console.log($scope.MupdateData);
                $http({
                    url: "Manifest/check_shelve",
                    method: "POST",
                    data: $scope.data.shelveNo,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {

                    if(response.data.status==false)
                    {
                        $scope.shelveError=true;
                    }
                    else
                    {
                          $scope.shelveError=false;
                    }


                }, function (error) {
                    console.log(error);
                });


            }

            $scope.GetConfirmUpdateStatusData = function ()
            {



                //console.log($scope.MupdateData);
                $http({
                    url: "Manifest/getmanifestrecviedUpdate",
                    method: "POST",
                    data: $scope.SeachSkuList,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {

                    alert("Successfully Updated!");
                    //$window.location.reload();
                    location.href = 'show_assignedlist';




                }, function (error) {
                    console.log(error);
                });


            }
            $scope.savePicker = function ()
            {
//$("#exampleModal").modal('hide');

                //$scope.pickerArray;
//console.log($scope.AssignData);


                $scope.AssignData.mid = $scope.mid;


                $http({
                    url: "Manifest/getupdateManifestStatus",
                    method: "POST",
                    data: $scope.AssignData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    $scope.message = response.data;
                    setTimeout(function () {
                        $("#errhide").hide();
                    }, 5000);
                    console.log(response);


                })



            }

            $scope.loadMore = function (page_no, reset, staffpage)
            {
                // alert(staffpage);
                //console.log(page_no);    
                // console.log($scope.selectedData);   
                $scope.filterData.staffpage = staffpage;
                $scope.filterData.page_no = page_no;
                $scope.filterData.status = 1;
                if (reset == 1)
                {
                    $scope.count=1;
                    $scope.shipData = [];
                    $scope.Items = [];
                }


                $http({
                    url: "Manifest/filter",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response)
                    $scope.totalCount = response.data.count;
                    $scope.assigndata = response.data.assignuser;
                    $scope.sellers = response.data.sellers;
                    // $scope.stockLocation=response.data.stockLocation;

                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            //  console.log(value)

                            $scope.shipData.push(value);
                            //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });
                        //console.log( $scope.Items)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }



                })


            };


            $scope.loadMore_return = function (page_no, reset)
            {
                //console.log(page_no);    
                // console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                $scope.filterData.status = 1;
                if (reset == 1)
                {
                    $scope.shipData = [];
                    $scope.Items = [];
                }


                $http({
                    url: "Manifest/filter_return",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response)
                    $scope.totalCount = response.data.count;
                    $scope.assigndata = response.data.assignuser;
                    $scope.sellers = response.data.sellers;
                    // $scope.stockLocation=response.data.stockLocation;

                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            //  console.log(value)

                            $scope.shipData.push(value);
                            //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });
                        //console.log( $scope.Items)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }



                })


            };



            $scope.generatePickup = function ()
            {
                $scope.shipData_new = $scope.shipData.filter(function (item) {
                    return $scope.Items.includes(item.slip_no);
                })
                if ($scope.shipData_new.length == 0)
                {
                    alert('Please select Orders to generate Pickupsheet!');
                } else
                {
                    var isConfirmed = confirm("You are going to generate Pickupsheet, This Action will change the Order status! Are you sure?");

                    if (isConfirmed)
                    {

                        console.log($scope.shipData_new);
                        console.log($scope.Items);

                        $http({
                            url: "generatePickup",
                            method: "POST",
                            data: {
                                listData: $scope.shipData_new,
                                slipData: $scope.Items
                            },
                            headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                        }).then(function (response) {

                            $window.location.reload();

                        })
                    }
                }
            }



            $scope.exportmanifestlist = function ()
            {
                console.log($scope.shipData);
                $http({
                    url: $scope.baseUrl + "/Manifest/manifestlistexportview",
                    method: "POST",
                    data: $scope.shipData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response.data.file);

                    var d = new Date();
                    var $a = $("<a>");
                    $a.attr("href", response.data.file);
                    $("body").append($a);
                    $a.attr("download11", d + "orders.xls");
                    $a[0].click();
                    $a.remove();


                });
            }
        })

        .controller('CTR_newmanifestrequest', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            $scope.AssignData = {};
            $scope.filterData = {};
            $scope.shipData = [];
            $scope.Items = [];
            $scope.message = [];
            $scope.pickerArray = [];
            $scope.courierData = {};
            $scope.AssignData.assign_type = "D";
            $scope.driverbtn = true;
            $scope.crourierbtn = false;
            $scope.loadershow = false;

            $scope.GetChangeAssignType = function (type)
            {
                if (type == 'D')
                {
                    $scope.driverbtn = true;
                    $scope.crourierbtn = false;
                    $scope.AssignData.assign_type = "D";
                } else
                {
                    $scope.driverbtn = false;
                    $scope.crourierbtn = true;
                    $scope.AssignData.assign_type = "CC";
                }
                // alert();

            };
            $scope.updatemanifeststatus = function (index, mid)
            {
//alert(mid);

                $("#exampleModal").modal()
                $scope.mid = mid;
            }

            $scope.invalidSslip_no = {};
            $scope.Success_msg = {};
            $scope.Error_msg = {};
            $scope.saveassigntodriver = function ()
            {
                location.href = 'showpickuplist';
                if ($scope.AssignData.assign_type == 'D')
                {
                    $scope.AssignData.cc_id = "";
                }
                if ($scope.AssignData.assign_type == 'CC')
                {
                    $scope.AssignData.assignid = "";
                }
                if ($scope.AssignData.assignid || $scope.AssignData.cc_id)
                {
                    $scope.loadershow = true;
                    disableScreen(1);


                    $scope.AssignData.mid = $scope.mid;

                    $http({
                        url: "Manifest/getupdateassign",
                        method: "POST",
                        data: $scope.AssignData,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                        //alert(response);
                        console.log(response);
                        // $scope.message=response.data;
                        // $scope.AssignData.assignid="";
                        // $scope.response="";
                        if (response)
                        {
                             if (response.data.status == 'succ')
                            {
                                $scope.Success_msg = response.data.Success_msg;
                                 location.href = 'showpickuplist';
                            } else
                            {
                                //$scope.invalidSslip_no = response.data.invalid_slipNO;
                                $scope.Error_msg = response.data.Error_msg;
                                 setTimeout(function() {
                                    $('.alert-danger').fadeOut();
                                    location.reload();
                                }, 2000);
                                
                            }
                        } else
                        {
                            alert("try again");
                        }

                    });
                    disableScreen(0);
                    $scope.loadershow = false;
                } else
                {
                    if ($scope.AssignData.assign_type == 'D')
                        alert("Please Select Driver");
                        
                    else
                        alert("Please Select Courier Company");
                }
            }

            $scope.loadMore = function (page_no, reset)
            {
                //console.log(page_no);    
                // console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                $scope.filterData.status = 1;
                if (reset == 1)
                {
                    $scope.count=1;
                    $scope.shipData = [];
                    $scope.Items = [];
                }

                $http({
                    url: "Manifest/GetnewmanifestreqShow",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response)
                    $scope.totalCount = response.data.count;
                    $scope.assigndata = response.data.assignuser;
                    $scope.courierData = response.data.courierData;

                    $scope.sellers = response.data.sellers;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            //  console.log(value)

                            $scope.shipData.push(value);
                            //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });
                        //console.log( $scope.Items)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }



                })


            };







            $scope.exportmanifestlistnew = function ()
            {

                $http({
                    url: $scope.baseUrl + "/Manifest/manifestlistexportview",
                    method: "POST",
                    data: $scope.shipData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (response) {
                    console.log(response);

                    var d = new Date();
                    var $a = $("<a>");
                    $a.attr("href", response.data.file);
                    $("body").append($a);
                    $a.attr("download11", d + "orders.xls");
                    $a[0].click();
                    $a.remove();


                });
            }
        })

        .controller('CTR_picluplist', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            $scope.AssignData = {};
            $scope.filterData = {};
            $scope.shipData = [];
            $scope.Items = [];
            $scope.message = [];
            $scope.pickerArray = [];
            $scope.file_data2 = [];
            $scope.form = [];
            $scope.files = [];
            $scope.updatemanifeststatus = function (index, mid)
            {
//alert(mid);
                $("#exampleModal").modal()
                $scope.mid = mid;
            }

            $scope.saveassigntodriver = function ()
            {
                $scope.form.image = $scope.files[0];

                if (!$scope.form.image)
                {
                    //alert($scope.form.image);
                    alert("Please Select File");
                } else
                {
                    $http({
                        url: "Manifest/getupdatepickupimagedata",
                        method: "POST",
                        processData: false,
                        transformRequest: function (data) {
                            var formData = new FormData();
                            formData.append("imagepath", $scope.form.image);
                            formData.append("manifestid", $scope.mid);
                            return formData;
                        },
                        data: $scope.form,
                        headers: {
                            'Content-Type': undefined
                        }
                        //headers: {'Content-Type': false}


                    }).then(function (response) {
                        console.log(response);
                        if (response.data == "true")
                        {
                            alert("Successfully Status Updated");
                            location.href = 'showmenifest';
                            
                        } else
                        {
                            alert("try again");
                        }

                    })
                }


            }
            $scope.uploadedFile = function (element) {
                $scope.currentFile = element.files[0];
                var reader = new FileReader();


                reader.onload = function (event) {
                    $scope.image_source = event.target.result
                    $scope.$apply(function ($scope) {
                        $scope.files = element.files;
                    });
                }
                reader.readAsDataURL(element.files[0]);
            }

            $scope.loadMore = function (page_no, reset)
            {
                //console.log(page_no);    
                // console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                $scope.filterData.status = 1;
                if (reset == 1)
                {
                    $scope.count=1;
                    $scope.shipData = [];
                    $scope.Items = [];

                }

                $http({
                    url: "Manifest/Getpickuplistshow",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response)
                    $scope.totalCount = response.data.count;
                    $scope.assigndata = response.data.assignuser;
                    $scope.sellers = response.data.sellers;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            //  console.log(value)

                            $scope.shipData.push(value);
                            //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });
                        //console.log( $scope.Items)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }



                })


            };


            $scope.exportmanifestlistnew = function ()
            {
                //alert("ssssss");
                $http({
                    url: $scope.baseUrl + "/Manifest/manifestlistexportview",
                    method: "POST",
                    data: $scope.shipData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                }).then(function (response) {
                    console.log(response);

                    var d = new Date();
                    var $a = $("<a>");
                    $a.attr("href", response.data.file);
                    $("body").append($a);
                    $a.attr("download11", d + "orders.xls");
                    $a[0].click();
                    $a.remove();


                });
            }


        })
        .directive('fileUpload', function () {})




        .controller('manifestView', function ($scope, $http, $window, $location) {
            $scope.baseUrl = new $window.URL($location.absUrl()).origin;
            $scope.filterData = {};
            $scope.shipData = [];
            $scope.Items = [];
            $scope.UpdateData = {};
             $scope.UpdateData_new = {};


//console.log($scope.baseUrl);
            $scope.loadMore = function (page_no, reset, manifest_id, type)
            {
                $scope.filterData.manifest_id = manifest_id;
                $scope.filterData.type = type;
                $scope.filterData.page_no = page_no;
                $scope.filterData.status = 1;

                if (reset == 1)
                {
                    $scope.shipData = [];
                    $scope.Items = [];
                }

                $http({
                    url: $scope.baseUrl + "/manifestdetails",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
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


            $scope.selectedAll = false;
            $scope.selectAll = function (val) {
                //alert(val);
                $scope.Items = [];

                // console.log("sssssss");
                var newval = val - 1;
                angular.forEach($scope.shipData, function (data, key) {
                    // console.log(key+"======="+newval);
                    if (key <= newval)
                    {

                        //console.log(key+"======="+newval);
                        //console.log($scope.selectedAll);
                        data.Selected = true;

                        $scope.Items.push(data.id);

                    } else
                    {
                        //console.log($scope.selectedAll);
                        data.Selected = $scope.selectedAll;
                        if ($scope.selectedAll == true)
                            $scope.Items.push(data.id);
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



            $scope.GetUpdateMissingdamageAll = function (type)
            {

                $http({
                    url: $scope.baseUrl + "/Manifest/GetUpdateMissingdamageAll",
                    method: "POST",
                    data: {listIds:$scope.Items,type:type},
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                     alert("Successfully Updated");
                        location.reload();

                });
            }


            $scope.getUpdatenotfoundStatus = function (id) {
                $scope.UpdateData.upid = id;
                // console.log($scope.UpdateData);
                $http({
                    url: $scope.baseUrl + "/Manifest/GetnotfoundstausCtr",
                    method: "POST",
                    data: $scope.UpdateData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    //console.log(response);
                    if (response.data == 'true')
                    {
                        alert("Successfully Updated");
                        location.reload();
                    }
                }, function (error) {
                    console.log(error);
                });

            }


            $scope.manifestexport = function ()
            {
                //console.log($scope.shipData);
                $http({
                    url: $scope.baseUrl + "/Manifest/manifestlistexportview",
                    method: "POST",
                    data: $scope.shipData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response.data.file);

                    var d = new Date();
                    var $a = $("<a>");
                    $a.attr("href", response.data.file);
                    $("body").append($a);
                    $a.attr("manifestlist", d + "orders.xls");
                    $a[0].click();
                    $a.remove();



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
                    link: function (scope, element, attr) {
                        var msg = attr.ngConfirmClick || "Are you sure?";
                        var clickAction = attr.confirmedClick;
                        element.bind('click', function (event) {
                            if (window.confirm(msg)) {
                                scope.$eval(clickAction)
                            }
                        });
                    }
                };
            }])
