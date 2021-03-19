var app = angular.module('Appfinance', [])




.controller('CTR_setuserrate', function($scope,$http,$window,$location,$anchorScroll) {
	$scope.baseUrl = new $window.URL($location.absUrl()).origin;
	   $scope.sellerdata={};
	    $scope.SelectArray={};
		$scope.TypesData={};
		$scope.updteArray={};
		$scope.setrate={};
		$scope.data={};
		  $scope.IsVisible = false;		
		  $scope.getallseller=function()
		  {
			
		  $http({
			url: SITEAPP_PATH+"Finance/getallsellerdata",
			method: "POST",
			data:$scope.sellerdata,
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			
		}).then(function (response) {
			//console.log(response);
			 $scope.sellerdata=response.data;
			  }) 
			  
		  }
		$scope.getallsellerfixtype=function()
		  {
			
		  $http({
			url: SITEAPP_PATH+"Finance/getallsellerfixtypedata",
			method: "POST",
			data:$scope.sellerdata,
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			
		}).then(function (response) {
			//console.log(response);
			 $scope.sellerdata=response.data;
			  }) 
			  
		  }
		  $scope.getallsellerdynamictype=function()
		  {
			
		  $http({
			url: SITEAPP_PATH+"Finance/getallsellerdynamictypedata",
			method: "POST",
			data:$scope.sellerdata,
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			
		}).then(function (response) {
			//console.log(response);
			 $scope.sellerdata=response.data;
			  }) 
			  
		  }




		  $scope.getallfixratedata=function()
		  {
			  $scope.SelectArray.seller_id=$scope.sellerdata.seller_id;
			 //console.log($scope.SelectArray); 
			 $http({
			url: SITEAPP_PATH+"Finance/Getallfixratusercharges",
			method: "POST",
			data:$scope.SelectArray,
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			
		}).then(function (response) {
			console.log(response);
			 $scope.TypesData=response.data;
			   $scope.IsVisible = true;
			   
			   
			  }) 
		  
		  }



		  $scope.getallsetratedata=function()
		  {
			  $scope.SelectArray.seller_id=$scope.sellerdata.seller_id;
			 //console.log($scope.SelectArray); 
			 $http({
			url: SITEAPP_PATH+"Finance/Getallusercharges",
			method: "POST",
			data:$scope.SelectArray,
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			
		}).then(function (response) {
			console.log(response);
			 $scope.TypesData=response.data;
			   $scope.IsVisible = true;
			   
			   
			  }) 
		  
		  }


		   $scope.getUpdateratesdata=function(rateid)
		  {
			 console.log($scope.TypesData);
			  $http({
			url: SITEAPP_PATH+"Finance/GetAllUsersSetFinanceCharges",
			method: "POST",
			data:$scope.TypesData,
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			
		}).then(function (response) {
			console.log(response);
			alert("successfully updated");
			 location.reload();
			   
			   
			  }) 
		  }
 
	})
	
.controller('CTR_catlistfinance', function($scope,$http,$window,$location,$anchorScroll) {
	
$scope.baseUrl = new $window.URL($location.absUrl()).origin;
  $scope.filterData={};
	$scope.showlistData=[];

 
 $scope.loadMore=function(page_no,reset)
    {
     $scope.filterData.page_no=page_no;
     $scope.filterData.status=1;
      if(reset==1)
      {
      $scope.shipData=[];
      $scope.Items=[];
      }
  
  
    $http({
		url: SITEAPP_PATH+"Finance/GetallfinanceCategory",
		method: "POST",
		data:$scope.filterData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           console.log(response)
			
          $scope.showlistData=response.data;
                       
									
		
  })  
  
        
    };  		  
		  
		  
	})
	


.controller('CTR_allinvoicesView', function($scope,$http,$window,$location,$anchorScroll) {
		 $scope.baseUrl = new $window.URL($location.absUrl()).origin;
	     $scope.filterData={};
		 $scope.showlistData=[];
		 $scope.sellerdata={};
	
		 $scope.totalvat=0;
		 $scope.totalamot=0;
		 $scope.totalcharges=0;
		 $scope.disArray={};
		 $scope.showdiscount=0;
		 $scope.tableshow=false;
	     $scope.VatRealtime=0;
		 $scope.loadershow=false; 
		 $scope.summaryArr={};
		 $scope.sellerArr={};
$scope.getallseller=function(type)
		  {
			$scope.sellerdata.type=type;
		  $http({
			url: SITEAPP_PATH+"Finance/getallsellerdata",
			method: "POST",
			data:$scope.sellerdata,
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			
		}).then(function (response) {
			//console.log(response);
			 $scope.sellerdata=response.data;
			  }) 
			  
}
$scope.run_shell_fixrate = function ()
            {

                $http({
                    url: "Finance/run_shell_fixrate",
                    method: "POST",                   
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    
                    alert("Sync process has been start. Please wait for 10 minute to update data. ");
                    

                })

            }  

            $scope.run_shell_dynamic = function ()
            {	
            	
                $http({
                    url: "Finance/run_shell_dynamic",
                    method: "POST",                   
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    
                    alert("Sync process has been start. Please wait for 10 minute to update data. ");
                    

                })

            } 
//  $scope.getallinvoiceseller=function()
// 		  {
// 		  $http({
// 			url: SITEAPP_PATH+"Finance/getallinvoicedata",
// 			method: "POST",
// 			data:$scope.sellerdata,
// 			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			
// 		}).then(function (response) {
// 			//console.log(response);
// 			 $scope.sellerdata=response.data;
// 			  }) 
			  
// }
 
 
 
 $scope.GetalluserdiscountDatashow=function(disval)
 {
	 
	 console.log($scope.disArray);
	
	// alert( $scope.VatRealtime);
	  $scope.totalamot=$scope.disArray.totalamot-$scope.disArray.discountval;
	   $scope.totalvat=$scope.VatRealtime/100*$scope.totalamot;
	  $scope.totalcharges=$scope.totalamot+$scope.totalvat;
	  $scope.showdiscount=$scope.disArray.discountval;
 }
 
 $scope.loadMore=function(page_no,reset)
    {
     $scope.filterData.page_no=page_no;
     $scope.filterData.status=1;
      if(reset==1)
      {
      $scope.shipData=[];
      $scope.Items=[];
      }
  
  console.log($scope.filterData);
    $http({
		url: SITEAPP_PATH+"Finance/GetallinvocieShowData",
		method: "POST",
		data:$scope.filterData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           //console.log(response)
           $scope.summaryArr=response.data.summary_total;
           
            $scope.sellerArr=response.data.sellerArr;
		   $scope.showlistData=response.data.result;
		   $scope.totalvat=response.data.totalvat;
		   $scope.totalamot=response.data.totalamtshow;
		   $scope.totalcharges=response.data.totalcharges;
		   
		    $scope.disArray.totalvat=response.data.totalvat;
		   $scope.disArray.totalamot=response.data.totalamtshow;
		   $scope.disArray.totalcharges=response.data.totalcharges;
		    $scope.VatRealtime=response.data.VatRealtime;
		   
		   $scope.tableshow=true;
			/* if(response.data.result.length > 0){
                        angular.forEach(response.data.result,function(value){
                                $scope.showlistData.push(value);
                        });
                    }else{$scope.nodata=true }	*/
									
		
  })  
  
        
    };    
    
   $scope.dropexport={};
   $scope.transactionReport = function(page_no,reset)
    {
		 disableScreen(1);
		 $scope.loadershow=true; 
	     $scope.filterData.page_no=page_no;
	     $scope.filterData.status=1;
		 $scope.totalCount=0; 
	      if(reset==1)
	      {
	      	$scope.showlistData=[];
	    	// $scope.Items=[];
	      }
  		 console.log($scope.filterData);
   		 $http({
			url: SITEAPP_PATH+"Finance/GettransportReportShowData",
			method: "POST",
			data:$scope.filterData,
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			
			}).then(function (response) {
	           console.log(response)
			  // $scope.showlistData=response.data.result;
			    $scope.totalCount=response.data.count;
			    $scope.dropexport=response.data.dropexport; 
			    $scope.totalamot=response.data.totalamtshow;
			    $scope.totalcharges=response.data.totalcharges;
			    angular.forEach(response.data.result,function(value){
	              // console.log(value.slip_no)                
	            $scope.showlistData.push(value);
	        });
			 disableScreen(0);
			 $scope.loadershow=false; 
  		})  

    }; 

  $scope.invoiceReport = function(page_no,reset)
    {
		disableScreen(1);
		 $scope.loadershow=true; 
	     $scope.filterData.page_no=page_no;
	     $scope.filterData.status=1;
		 $scope.totalCount=0; 
	      if(reset == 1)
	      {
	      	$scope.showlistData=[];
	    	// $scope.Items=[];
	      }
  		 console.log($scope.filterData);
   		 	$http({
				url: SITEAPP_PATH+"Finance/GetinvoiceReportShowData",
				method: "POST",
				data:$scope.filterData,
				headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			}).then(function (response) {
			    angular.forEach(response.data,function(value){    
	            $scope.showlistData.push(value);
	        });
			 disableScreen(0);
			 $scope.loadershow = false; 
  		})  

    }; 
    $scope.dynamicinvoiceReport = function(page_no,reset)
    {
		disableScreen(1);
		 $scope.loadershow=true; 
	     $scope.filterData.page_no=page_no;
	     $scope.filterData.status=1;
		 $scope.totalCount=0; 
	      if(reset==1)
	      {
	      	$scope.showlistData=[];
	    	// $scope.Items=[];
	      }
  		 console.log($scope.filterData);
   		 	$http({
				url: SITEAPP_PATH+"Finance/GetdynamicinvoiceReportShowData",
				method: "POST",
				data:$scope.filterData,
				headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			}).then(function (response) {
			    angular.forEach(response.data,function(value){    
	            $scope.showlistData.push(value);
	        });
			 disableScreen(0);
			 $scope.loadershow = false; 
  		})  

    }; 


	$scope.ExporttransectionReport=function()
    {
		if($scope.filterData.exportlimit>0)
		{
		 disableScreen(1);
		 $scope.loadershow=true; 
    //console.log($scope.exportlimit);
      $http({
			url: SITEAPP_PATH+"Finance/GetTransactionRepotydownload", 
			method: "POST",
			data:$scope.filterData,
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		}).then(function (response) {
	        var d = new Date();
		    var $a = $("<a>");
		    $a.attr("href",response.data.file);
		    $("body").append($a);
		    $a.attr("download",'Transaction Report '+d+".xls");
		    $a[0].click();
		    $a.remove();
    
        disableScreen(0);
		 $scope.loadershow=false; 
      });
		}
		else
		alert("please select export limit");
    
  
    };
	

})
.controller('CTR_StorageinvoiceView', function($scope,$http,$window,$location,$anchorScroll) {
	$scope.baseUrl = new $window.URL($location.absUrl()).origin;
  $scope.filterData={};
	$scope.showlistData=[];
	$scope.sellerdata={};
  $scope.getallseller=function()
		  {
			  
			 
		  $http({
			url: SITEAPP_PATH+"Finance/getallsellerdata",
			method: "POST",
			data:$scope.sellerdata,
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			
		}).then(function (response) {
			//console.log(response);
			 $scope.sellerdata=response.data;
			  }) 
			  
		  }
 
 $scope.loadMore=function(page_no,reset)
    {
     $scope.filterData.page_no=page_no;
     $scope.filterData.status=1;
      if(reset==1)
      {
      $scope.showlistData=[];
      $scope.Items=[];
      }
  
 // $scope.filterData.seller_id=$scope.sellerdata.seller_id;
  //console.log($scope.filterData);
    $http({
		url: SITEAPP_PATH+"Finance/GetallsellerStorageinvocielist",
		method: "POST",
		data:$scope.filterData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           console.log(response)
		    $scope.totalCount=response.data.count;
		   // $scope.showlistData=response.data.result;
			 if(response.data.result.length > 0){
                        angular.forEach(response.data.result,function(value)
						{
                                $scope.showlistData.push(value);
                        });

                    }
					else
					{$scope.nodata=true
                    }		
					
								
		
  })  
  
        
    }; 
	
	 $scope.Pickupchargesdata=function(page_no,reset)
    {
		
     $scope.filterData.page_no=page_no;
     $scope.filterData.status=1;
      if(reset==1)
      {
      $scope.shipData=[];
      $scope.Items=[];
      }
  
 // $scope.filterData.seller_id=$scope.sellerdata.seller_id;
  //console.log($scope.filterData);
    $http({
		url: SITEAPP_PATH+"Finance/GetallUsersPickupChargesData",
		method: "POST",
		data:$scope.filterData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           console.log(response)
		    $scope.showlistData=response.data.result;
		
  })  
  
        
    };    
    
   

})
.filter("month", function($locale) {
    return function(month) {
        return $locale.DATETIME_FORMATS.MONTH[month];
    }
})
 .filter("dateOnly", function(){
        return function(input){
            return input.split(' ')[0]; // you can filter your datetime object here as required.
        };
 })
.directive('ngConfirmClick', [
        function(){
            return {
                link: function (scope, element, attr) {
                    var msg = attr.ngConfirmClick || "Are you sure?";
                    var clickAction = attr.confirmedClick;
                    element.bind('click',function (event) {
                        if ( window.confirm(msg) ) {
                            scope.$eval(clickAction)
                        }
                    });
                }
            };
    }])
