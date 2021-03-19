var app = angular.module('faqAPP', [])

 

.controller('Faqlistview', function($scope,$http,$window,$location) {


  $scope.filterData={};
$scope.listArray=[];   


    
 $scope.loadMore_faq=function(page_no,reset)
    {
    console.log(page_no);    
   // console.log($scope.selectedData);    
     $scope.filterData.page_no=page_no;
	  $scope.filterData.type='S';
	  
      if(reset==1)
      {
      $scope.listArray=[];
      }
  
    $http({
      url: "Faq/filter",
		method: "POST",
		data:$scope.filterData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           console.log(response)
		 $scope.totalCount=response.data.count;
			 if(response.data.result.length > 0){
                        angular.forEach(response.data.result,function(value){
                           
                                    
                                $scope.listArray.push(value);

                        });
                //console.log( $scope.shipData)
                 //$scope.$broadcast('scroll.infiniteScrollComplete');
                    }else{$scope.nodata=true
                    }					
			 
			 
		
  })  
  
        
    }; 
    
    
  
    
  
})
  
        
    
    


.directive('myEnter', function () {
    return function (scope, element, attrs) {
        element.bind("keydown keypress", function (event) {
            if(event.which === 13) {
                scope.$apply(function (){
                    scope.$eval(attrs.myEnter);
                });

                event.preventDefault();
            }
        });
    };
})
/*------ /show shipments-----*/