var app = angular.module('3PLdispatchApp', [])
.controller('CTR_scan3PLDispacth', function($scope,$http,$interval,$window){
	$scope.shipData=[];
    $scope.completeShip=[];
    $scope.scan={};
	$scope.specialtype={};
	
	$scope.awbArray=[];
	$scope.shelve=null;
        $scope.totalshow=0;
	$scope.scan_awb=function(){
		$('#scan_awb').focus();
               $scope.DispatchScan3plData();
		}
		
		
	    $scope.DispatchScan3plData=function(){
            $scope.warning=null;
             $scope.Message=null;
              $scope.Message_new=null;
              $scope.arrayIndexnew=[];
         $scope.scan.slip_no=$scope.scan.slip_no.toUpperCase()
         $scope.arrayIndex=$scope.awbArray.findIndex( record => record.slip_no.toUpperCase() ===$scope.scan.slip_no.toUpperCase()); 
        // alert($scope.arrayIndex);
            if( $scope.arrayIndex==-1)
            {
		//console.log($scope.scan);
		$http({
		url: "PickUp/GetCheck3PLDispatchData",
		method: "POST",
		data:$scope.scan,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
            $scope.scan.slip_no="";
           //$scope.specialtype.specialpack=true;
		   //$scope.specialtype.specialpacktype="warehouse";
                    
                        if(response.data.count==0)
                        {
                          //  alert("ssssss");
                          
                       //   responsiveVoice.speak("dsadsadsa");
                        $scope.warning="Order Not available for 3PL Dispatching!";
                        responsiveVoice.speak($scope.warning); 
                         $scope.Message_new=null;
                         $scope.Message=null;
                        // var sound = document.getElementById("audio");
                           //sound.play();
                        }
                        else
                        {
                             $scope.Message_new=response.data.result[0].frwd_company_id;
                             var jsutshow="Order Dispatched to  "+response.data.result[0].frwd_company_id;
                              responsiveVoice.speak(jsutshow); 
                            var soundsuccess= document.getElementById("audioSuccess");
         	           soundsuccess.play();
                           
                          
                       }
                       
                        angular.forEach(response.data.result,function(value){  
                       
                        $scope.awbArray.push(value);
                       
                                
                                //$scope.Items.push( 'slip_no: ' +value.slip_no);
                      });
                       console.log($scope.awbArray);
                         
              
           
            });
            }
            
         //$scope.scanCheck();
        //$scope.checkComplte($scope.shipData,$scope.scan.slip_no);    
	 	
        }
        $scope.packBoxArr={};
     $scope.Getallspecialpackstatus=function()
	 {
		
		// $scope.completeArray.specialtype=$scope.specialtype.specialpacktype;
		//  $scope.completeArray.specialpack=$scope.specialtype.specialpack;
	//$scope.specialtype.specialpack=true;
	//$scope.completeArray.push({'specialpack':$scope.specialtype.specialpack,'specialpacktype':$scope.specialtype.specialpacktype});
	console.log($scope.specialpacktype);
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
                   // $scope.warning='All Parts Scanned for '+$scope.shipData[$scope.arrayIndexnew].sku;
					 $scope.warning='All Parts Scanned for '+$scope.shipData[$scope.arrayIndexnew].sku;
                    //responsiveVoice.speak($scope.warning);   
                    }
                    else
                    {
                    $scope.Message='Scaned!';
                    //responsiveVoice.speak($scope.message);    
                    responsiveVoice.speak('Scaned!'); 
                    }

                                
                }
                else
                {
                  
                   //$scope.shipData[$scope.arrayIndexnew].scaned=parseInt($scope.shipData[$scope.arrayIndexnew].scaned)+1; 
                    $scope.shipData[$scope.arrayIndexnew].extra=parseInt($scope.shipData[$scope.arrayIndexnew].extra)+1;
                    $scope.Message=null;    
                    $scope.warning='Extra Item Scaned';
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
              $scope.inxexComp= $scope.completeArray.findIndex( record => (record.slip_no ===$scope.scan.slip_no ))
            if($scope.inxexComp==-1)
               {$scope.completeArray.push({'slip_no':$scope.checkArray[0].slip_no,'specialpack':$scope.specialtype.specialpack,'specialpacktype':$scope.specialtype.specialpacktype,'print_url':$scope.checkArray[0].print_url,'frwd_company_id':$scope.checkArray[0].frwd_company_id,'frwd_company_awb':$scope.checkArray[0].frwd_company_id});}
              // alert($scope.specialtype.specialpack);
        
            $scope.warning=null;
            var soundsuccess= document.getElementById("audioSuccess");
         	  soundsuccess.play();
            $scope.Message=$scope.checkArray[0].slip_no+' Completly Scaned Please Pack this Order!';
                    //responsiveVoice.speak($scope.message);  
      
            
			
         responsiveVoice.speak('Completly Scaned, Please Pack this Order!'); 
		 
		   $scope.nindex = $scope.shipData.findIndex(record => (record.slip_no.toUpperCase() === $scope.checkArray[0].slip_no.toUpperCase() ))
        console.log($scope.nindex);
        // $scope.arrayIndexnew= $scope.shipData.findIndex( record => (record.slip_no ===$scope.scan.slip_no && record.sku ===$scope.scan.sku ))
        if ($scope.nindex != -1) {
          $scope.print_url=$scope.shipData[$scope.nindex].print_url
        
              $scope.printToCart( $scope.print_url);
        }
        }
        console.log($scope.completeArray);
    }
        $scope.printToCart = function(print_url) {
      // $window.open("//www.tamex.co/", '', '_blank', 'width=600,height=700,scrollbars=no,menubar=no,toolbar=no,location=no,status=no,titlebar=no');
	    //var innerContents = document.getElementById('test_print').innerHTML;
       // var popupWinindow =  $window.open(print_url, '_blank', 'width=600,height=700,scrollbars=no,menubar=no,toolbar=no,location=no,status=no,titlebar=no');
    
   // WindowObject.close();
	   // popupWinindow.document.close();
		//setTimeout(function () { popupWinindow.close();}, 3000);
	
      }
    $scope.finishScan=function()
    {
       
    if($scope.completeArray.length>0)
     {
     var isconfirm=confirm('Are You sure? after verication you will have to scan sort shipments again.! ');
         
         if(isconfirm)
         {
         $http({
		url: "PickUp/packFinish",
		method: "POST",
		data:{
            shipData:$scope.completeArray,
            exportData:$scope.shipData,
            SpecialArr:$scope.specialtype
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
        $scope.Message="Completed order Packed!"; 
             
             
         },function(error){console.log(error);});
         }
     
     }
    }
		
})


.factory('Excel',function($window){
        var uri='data:application/vnd.ms-excel;base64,',
            template='<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
            base64=function(s){return $window.btoa(unescape(encodeURIComponent(s)));},
            format=function(s,c){return s.replace(/{(\w+)}/g,function(m,p){return c[p];})};
        return {
            tableToExcel:function(tableId,worksheetName){
                var table=$(tableId),
                    ctx={worksheet:worksheetName,table:table.html()},
                    href=uri+base64(format(template,ctx));
                return href;
            }
        };
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
/*------ /show shipments-----*/