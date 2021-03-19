var app = angular.module('AppPickedPageSingle', [])



.controller('PickedViewCtr', function($scope,$http,$window,$location) {
	  $scope.baseUrl = new $window.URL($location.absUrl()).origin;
	$scope.shipData=[];
    $scope.completeShip=[];
    $scope.scan={};
	$scope.specialtype={};
	$scope.boxArray={};
	$scope.showboxArray={};
	
	$scope.awbArray=[];
	$scope.shelve=null;
	$scope.btnfinal=true;
	$scope.boxshow=true;
	$scope.boxshow1=true;
        $scope.pickerArray={};
	$scope.filterdata={};
	$scope.scan_awb=function(){
		$('#scan_awb').focus();
        //$scope.packuShip();
		 $scope.scanCheck();
       
		}
		$scope.SingeListArr={};
		$scope.SingeListComArr={};
		$scope.Getlistviewpickingview=function(page_no, reset)
		{
                     if (reset == 1)
                {
                    $scope.SingeListArr = [];
                    $scope.Items = [];
                }
			//alert("sss");
			$scope.filterdata.picked_status='N';
			$http({
		url: "PickUp/PickupSingleListView",
		method: "POST",
		data:$scope.filterdata,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
		console.log(response);
		$scope.SingeListArr=response.data.result;
                 $scope.pickerArray=response.data.picker;
	});
		}
		
		$scope.GetlistbatchpickingView=function(page_no, reset)
		{
			//alert("sss");
                         if (reset == 1)
                {
                    $scope.SingeListArr = [];
                    $scope.Items = [];
                }
			$scope.filterdata.picked_status='N';
			$http({
		url: $scope.baseUrl+"/PickUp/PickedListBatchviewData",
		method: "POST",
		data:$scope.filterdata,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
		console.log(response);
		$scope.SingeListArr=response.data.result;
                 $scope.pickerArray=response.data.picker;
	});
		}
		$scope.PickedCompeletedViewCtr=function()
		{
			$scope.filterdata.picked_status='Y';
			$http({
		url: "PickUp/PickupSingleListView",
		method: "POST",
		data:$scope.filterdata,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
		console.log(response);
		$scope.SingeListComArr=response.data;
	});
		}
		
	    $scope.packuShip=function(slip_url){
			$scope.scan.slip_no=slip_url;
            $scope.warning=null;
             $scope.Message=null;
              $scope.arrayIndexnew=[];
			//  alert($scope.scan.slip_no);
       //  $scope.scan.slip_no=$scope.scan.slip_no.toUpperCase();
       //  $scope.arrayIndex=$scope.awbArray.findIndex( record => record.slip_no.toUpperCase() ===$scope.scan.slip_no.toUpperCase()); 
           // if( $scope.arrayIndex==-1)
           // {
		//console.log($scope.scan);
		$http({
		url: $scope.baseUrl+"/PickUp/PickupCheckSingle",
		method: "POST",
		data:$scope.scan,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
		 console.log(response)
		//$scope.getboxesData();
		$scope.boxshow1=true;
           //$scope.specialtype.specialpack=true;
		   //$scope.specialtype.specialpacktype="warehouse";
		   
                        if(response.data.count==0)
                        {
                        $scope.warning="Order Not available for Pick!";
                        responsiveVoice.speak($scope.warning); 
                        }
                        angular.forEach(response.data.result,function(value){
                        console.log(value)

                        $scope.awbArray.push(value);
                        angular.forEach(JSON.parse(value.sku),function(value1){
                        console.log(value1.piece);
						
						

                        $scope.shipData.push({'slip_no':value.slip_no,'booking_id':value.booking_id,'sku':value1.sku,'sku_show':value1.sku,'piece':value1.piece,'scaned':0,'extra':0});
                                
                                //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        }); 
                                
                                //$scope.Items.push( 'slip_no: ' +value.slip_no);
                 }); 
              
           
            });
            //}
            
         
	 	
        }

        
		
    $scope.scanCheck=function()
     {
		  $scope.arrayIndexnew= $scope.shipData.findIndex( record => (record.slip_no.toUpperCase() ===$scope.scan.slip_no.toUpperCase() && record.sku.toUpperCase() ===$scope.scan.sku.toUpperCase() ))
           // $scope.arrayIndexnew= $scope.shipData.findIndex( record => (record.slip_no ===$scope.scan.slip_no && record.sku ===$scope.scan.sku ))
             if ($scope.arrayIndexnew!=-1 )
            {
                if(parseInt($scope.shipData[$scope.arrayIndexnew].scaned)<parseInt($scope.shipData[$scope.arrayIndexnew].piece))
                {
                    $scope.shipData[$scope.arrayIndexnew].scaned=parseInt($scope.shipData[$scope.arrayIndexnew].scaned)+1;
                    if(parseInt($scope.shipData[$scope.arrayIndexnew].scaned)==parseInt($scope.shipData[$scope.arrayIndexnew].piece))
                    {
                    $scope.Message=null;    
                    $scope.warning='All Parts Scanned for '+$scope.shipData[$scope.arrayIndexnew].sku;
                    responsiveVoice.speak($scope.warning);   
                    }
                    else
                    {
                    $scope.Message='Scanned!';
                    //responsiveVoice.speak($scope.message);    
                    responsiveVoice.speak('Scanned!'); 
                    }

                                
                }
                else
                {
                  
                   //$scope.shipData[$scope.arrayIndexnew].scaned=parseInt($scope.shipData[$scope.arrayIndexnew].scaned)+1; 
                    $scope.shipData[$scope.arrayIndexnew].extra=parseInt($scope.shipData[$scope.arrayIndexnew].extra)+1;
                    $scope.Message=null;    
                    $scope.warning='Extra Item Scanned';
                    responsiveVoice.speak($scope.warning);    
                    //$scope.warning='Shipment Already scanned';
			        var sound = document.getElementById("audio");
         	        sound.play();
                  
                }
                
                
            
         }
         else
    {               if($scope.scan.sku.length >0)
    {
                    $scope.Message=null;    
                    $scope.warning=$scope.scan.sku+ ', SKU not available for this shipment!';
                    responsiveVoice.speak('SKU not available for this shipment!'); 
    }
     else
               
     {
                    
     }
    
    
    }
	 $scope.checkComplte($scope.shipData,$scope.scan.slip_no);   
         $scope.scan.sku=null;
            }
   
    $scope.completeArray=[]; 
   // $scope.checkArray=[];  
	$scope.checkComplte=function(dataArray,slip_no)
    {
    $scope.checkArray=[];  
     angular.forEach(dataArray,function(value){
      
        if(value.slip_no==slip_no) 
        {
           $scope.checkArray.push(value); 
            
       
        }
     });
        $scope.checkqty=0;
      angular.forEach($scope.checkArray,function(value){
          if(value.piece==value.scaned)
          { $scope.checkqty++}
          
          
      });
        if($scope.checkArray.length==$scope.checkqty && $scope.checkqty>0)
        {
			//console.log("sssssssss");
			
              $scope.inxexComp= $scope.completeArray.findIndex( record => (record.slip_no ===$scope.scan.slip_no ))
            if($scope.inxexComp==-1)
               {$scope.completeArray.push({'slip_no':$scope.checkArray[0].slip_no,'specialpack':$scope.specialtype.specialpack,'boxid':$scope.boxArray.boxid,'specialpacktype':$scope.specialtype.specialpacktype});
			    $scope.btnfinal=false;
			  
			   }
        
            $scope.warning=null;
            var soundsuccess= document.getElementById("audioSuccess");
         	  soundsuccess.play();
            $scope.Message=$scope.checkArray[0].slip_no+' Completly Scanned Please Pick this Order!';
			
			
			
			
			
                    //responsiveVoice.speak($scope.message);  
      
            
			
         responsiveVoice.speak('Completly Scanned, Please Pick this Order!'); 
        }
        console.log($scope.completeArray);
    }
	
    $scope.getboxesData=function()
	{
		    $http({
		url: "BoxesM/boxalldatapack",
		method: "POST",
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           console.log(response)
		   $scope.showboxArray=response.data;
		
  })
	};
	
	 $scope.getcheckbutton=function(val)
	 {
		if($scope.boxArray.boxid>0)
		{
		
		//$scope.boxshow=true;
		}
		 
	 }
	 
    $scope.finishScan=function()
    {
    if($scope.completeArray.length>0)
     {
     var isconfirm=confirm('Are You sure? after verication you will have to scan sort shipments again.! ');
         
         if(isconfirm)
         {
         $http({
		url: $scope.baseUrl+"/PickUp/ppickFinishSingle",
		method: "POST",
		data:{
            shipData:$scope.completeArray,
            exportData:$scope.shipData
             },
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           console.log(response);  
                 
         var d = new Date();
    var $a = $("<a>");
    $a.attr("href",response.data.file);
    $("body").append($a);
    $a.attr("download",response.data.file_name);
    $a[0].click();
    $a.remove();
         
             
        $scope.shipData=[];
        $scope.completeArray=[];
        $scope.Message="Completed order Picked!"; 
             
             
         },function(error){console.log(error);});
         }
     
     }
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
