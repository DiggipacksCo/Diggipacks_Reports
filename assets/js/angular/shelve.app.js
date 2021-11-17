var app = angular.module('shelve', [])

.controller('shelvSku', function($scope,$http,$interval,$window){
	$scope.shipData=[];
    $scope.completeShip=[];
    $scope.scan={};
	$scope.awbArray=[];
	$scope.shelve=null;
    $scope.StockLocationArray=[];
    
    
	$scope.scan_shelve=function(){
		$('#StockLocation').focus();
        $scope.checkShelve();
		}
	$scope.StockLocation=function(){
        if($scope.scan.shelve_no==null)
        {
        $('#scan_shelve').focus();
        $scope.warning="Scan Shelve First!";
        responsiveVoice.speak($scope.warning);     
        }
        else
        {
		$('#scan_awb').focus();
		
        $scope.checkStockLocation();
        }
		}	
		
	    $scope.checkShelve=function(){
         
            $scope.warning=null;
             $scope.Message=null;
              $scope.arrayIndexnew=[];
         $scope.arrayIndex=$scope.awbArray.findIndex( record => record.shelv_no.toUpperCase() ===$scope.scan.shelve_no.toUpperCase()); 
            if( $scope.arrayIndex==-1)
            {
		//console.log($scope.scan);
		$http({
		url: "checkShelve",
		method: "POST",
		data:$scope.scan,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           
                        if(response.data.count==0)
                        {
                        $scope.warning="Shelve Not Exits!";
                        responsiveVoice.speak($scope.warning); 
                        $('#scan_shelve').focus();    
                        }
            else{
                 $scope.Message="Shelve Scaned!";
                        responsiveVoice.speak($scope.Message); 
                          
                        angular.forEach(response.data.result,function(value){
                        console.log(value)

                        $scope.awbArray.push(value);
                       
                 }); 
        }
              
           
            });
            }else{
                 $scope.Message="Shelve Scaned!";
                        responsiveVoice.speak($scope.Message); 
            }
            
        // $scope.scanCheck();
          
	 	
        }
        
         $scope.checkStockLocation=function(){
            $scope.warning=null;
             $scope.Message=null;
              $scope.arrayIndexnew=[];
             $scope.selectedStockLocationData=[];
         $scope.arrayIndex222=$scope.StockLocationArray.findIndex( record => record.stock_location ===$scope.scan.StockLocation); 
            if( $scope.arrayIndex222==-1)
            {
		//console.log($scope.scan);
		$http({
		url: "checkStockLocation",
		method: "POST",
		data:$scope.scan,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           
                        if(response.data=='null')
                        {
                        $scope.warning="Stock Location Not Exits!";
                        responsiveVoice.speak($scope.warning); 
                        $('#StockLocation').focus();    
                        }
            else{
                 //$scope.Message="Stock Location Scaned!";
                          responsiveVoice.speak("Stock Location Scaned!"); 
                          
                        angular.forEach(response.data,function(value){
                       // console.log(value)

                        $scope.StockLocationArray.push(value);
                       
                 }); 
                
            $scope.selectedStockLocationData= response.data;   
			$scope.shelveSelected();
			
        }
              
           
            });
            }
             else{
                  $scope.selectedStockLocationData=[];
                responsiveVoice.speak("Stock Location Scaned!"); 
                 angular.forEach($scope.StockLocationArray,function(value){
                       // console.log(value.stock_location)
                        if(value.stock_location==$scope.scan.StockLocation)
                        {

                       $scope.selectedStockLocationData.push(value);
                        }
                       
                 }); 
                 $scope.shelveSelected();
                  //$scope.selectedStockLocationData.push($scope.StockLocationArray[$scope.arrayIndex222]);
             }
            
        // $scope.scanCheck();
          
	 	
        }
     
      $scope.scanedArray=[];  
    $scope.shelveSelected=function()
     {
       if($scope.scan.shelve_no==null)

        {
        $('#scan_shelve').focus();
        $scope.warning="Scan Shelve First!";
        responsiveVoice.speak($scope.warning);     
        }
        else
        {
		
            if($scope.scan.StockLocation==null)
        {
        $('#StockLocation').focus();
        $scope.warning="Scan Stock Location First!";
        responsiveVoice.speak($scope.warning);     
        }
        else
        {
        
        $scope.arrayIndex=$scope.awbArray.findIndex( record => record.shelv_no.toUpperCase() ===$scope.scan.shelve_no.toUpperCase()); 
        
            if( $scope.arrayIndex!=-1)
            {
               //console.log( $scope.StockLocationArray);
                $scope.arrayIndex123=$scope.StockLocationArray.findIndex( record => record.stock_location ===$scope.scan.StockLocation); 
               
			  // console.log($scope.arrayIndex123);
            if( $scope.arrayIndex123!=-1)
            { 
                $scope.scan.int_id=$scope.StockLocationArray[$scope.arrayIndex123].id;
                $scope.StockLocationArray[$scope.arrayIndex123].shelve_no=$scope.scan.shelve_no;
				//console.log($scope.scan);
				//console.log("sss");
				
				
               $http({
		url: "shelveSelected",
		method: "POST",
		data:$scope.scan,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           
           if(response.data!='302')
		   {
        $scope.scanedArray.push($scope.StockLocationArray[$scope.arrayIndex123]);
        $scope.Message="Added in Shelve!";           
          responsiveVoice.speak($scope.Message);
          $scope.scan.StockLocation=null;   
	      }
		  else
		  {
			    $scope.warning="this Shelve no used another seller please scan other Shelve no";
                responsiveVoice.speak($scope.warning);   
		  }
           
            });
                
            }
                else
                {
                $scope.warning="Item Not in This Stock Location!";
                responsiveVoice.speak($scope.warning);    
                }
            }
        }
        }
        
            }
   
  
	
    
  $scope.ExportExcelitemshelve=function()
    {
		
    //console.log($scope.shipData);
      $http({
		url: "Shelve/shelveScanExport",
		method: "POST",
		data:$scope.scanedArray,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
    //	  console.log(response.data);
        
         var d = new Date();
    var $a = $("<a>");
    $a.attr("href",response.data.file);
    $("body").append($a);
    $a.attr("download",'Items Inventory '+d+"orders.xls");
	
    $a[0].click();
    $a.remove();
    
      
      });
    
  
    }
		
})

.directive('checkList', function() {
  return {
    scope: {
      list: '=checkList',
      value: '@'
    },
    link: function(scope, elem, attrs) {
      var handler = function(setup) {
        var checked = elem.prop('checked');
        var index = scope.list.indexOf(scope.value);

        if (checked && index == -1) {
          if (setup) elem.prop('checked', false);
          else scope.list.push(scope.value);
        } else if (!checked && index != -1) {
          if (setup) elem.prop('checked', true);
          else scope.list.splice(index, 1);
        }
      };
      
      var setupHandler = handler.bind(null, true);
      var changeHandler = handler.bind(null, false);
            
      elem.on('change', function() {
        scope.$apply(changeHandler);
      });
      scope.$watch('list', setupHandler, true);
    }
  };
})
.filter('reverse', function() {
  return function(items) {
    return items.slice().reverse();
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