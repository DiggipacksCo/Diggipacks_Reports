var app = angular.module('AppStorage', [])




.controller('CTR_addstoragetype', function($scope,$http,$window,$location,$anchorScroll) {
	$scope.baseUrl = new $window.URL($location.absUrl()).origin;
	   $scope.storedata={};
	   
		  $scope.geteditdatashow=function(id)
		  {
			  if(id)
			  {
		 $scope.storedata.id=id;
		 $http({
			url: SITEAPP_PATH+"Storage/geteditviewdata",
			method: "POST",
			data:$scope.storedata,
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			
		}).then(function (response) {
			console.log(response);
			
			 $scope.storedata=response.data;
			  }) 
			  }
		  }



		  $scope.geteditdatacharges=function(id)
		  {	
			 
			  if(id)
			  {     //console.log("jhjhfjhjf");
			  		//	console.log(id);
					$scope.storedata.id=id;
					$http({
						url: SITEAPP_PATH+"Storage/geteditStoragedata",
						method: "POST",
						data:$scope.storedata,
						headers: {'Content-Type': 'application/x-www-form-urlencoded'}
						
					}).then(function (response) {
						console.log(response);
						
						$scope.storedata=response.data;
						}) 
						}
		  }


	})
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
			url: SITEAPP_PATH+"Storage/getallsellerdata",
			method: "POST",
			data:$scope.sellerdata,
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			
		}).then(function (response) {
			//console.log(response);
			 $scope.sellerdata=response.data;
			  }) 
			  
		  }
		  $scope.getallsetratedata=function()
		  {
			  $scope.SelectArray.seller_id=$scope.sellerdata.seller_id;
			 //console.log($scope.SelectArray); 
			 $http({
			url: SITEAPP_PATH+"Storage/getallsoragetypes",
			method: "POST",
			data:$scope.SelectArray,
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			
		}).then(function (response) {
			//console.log(response);
			 $scope.TypesData=response.data;
			   $scope.IsVisible = true;
			   
			   
			  }) 
		
			  
		  }
		   $scope.getUpdateratesdata=function(rateid)
		  {
			// console.log($scope.TypesData);
			  $http({
			url: SITEAPP_PATH+"Storage/getUpdateRateSetData",
			method: "POST",
			data:$scope.TypesData,
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			
		}).then(function (response) {
			console.log(response);
			alert("successfully updated");
			 //$scope.TypesData=response.data;
			   $scope.IsVisible = true;
			   
			   
			  }) 
		  }
		  
		  
		  
		  
		  
	})
	
	
.controller('CTR_storagelist', function($scope,$http,$window,$location,$anchorScroll) {
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
		url: SITEAPP_PATH+"Storage/getliststorage",
		method: "POST",
		data:$scope.filterData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           console.log(response)
		//  $scope.totalCount=response.data.count;
         // $scope.listdata=response.data.assignuser;
		  
			 if(response.data.result.length > 0){
                        angular.forEach(response.data.result,function(value){
                                $scope.showlistData.push(value);
                        });
                    }else{$scope.nodata=true
                    }					
		
  })  
  
        
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
