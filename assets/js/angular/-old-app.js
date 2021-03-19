var app = angular.module('fulfill', [])

 

.controller('shelveView', function($scope,$http,$window,$location) {
  $scope.baseUrl = new $window.URL($location.absUrl()).origin;
  $scope.filterData={};
  $scope.shipData=[];
  $scope.Items=[]

console.log($scope.baseUrl);
 $scope.loadMore=function(page_no,reset)
    {
    console.log(page_no);    
   // console.log($scope.selectedData);    
     $scope.filterData.page_no=page_no;
     $scope.filterData.status=1;
      if(reset==1)
      {
      $scope.shipData=[];
      $scope.Items=[];
      }
  
    $http({
		url: "shelvefilter",
		method: "POST",
		data:$scope.filterData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           console.log(response)
		 $scope.totalCount=response.data.count;
			 if(response.data.result.length > 0){
                        angular.forEach(response.data.result,function(value){
                            //console.log(value)
                           
                                $scope.shipData.push(value);
                            
//                         $scope.dataIndex=  $scope.shipData.findIndex( record => record.slip_no ===value.slip_no);   
//                        $scope.shipData[$scope.dataIndex].skuData=[];  
//                        $scope.shipData[$scope.dataIndex].skuData.push(JSON.parse(JSON.stringify(value.sku)));   
                                //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });
                //.console.log( $scope.shipData[0].skuData[0])
                 //$scope.$broadcast('scroll.infiniteScrollComplete');
                    }else{$scope.nodata=true
                    }					
			 
			 
		
  })  
  
        
    };    
    
   

 

    
  $scope.exportExcel=function()
    {
    console.log($scope.shipData);
      $http({
		url: "pickUp/pickListViewExport",
		method: "POST",
		data:$scope.shipData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
      console.log(response.data.file);
        
         var d = new Date();
    var $a = $("<a>");
    $a.attr("href",response.data.file);
    $("body").append($a);
    $a.attr("download",d+"orders.xls");
    $a[0].click();
    $a.remove();
    
      
      });
    }
	$scope.Shelveviewexportdata=function()
    {
    console.log($scope.shipData);
      $http({
		url: "Shelve/Shelveviewexportdata",
		method: "POST",
		data:$scope.shipData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
      console.log(response.data.file);
        
         var d = new Date();
    var $a = $("<a>");
    $a.attr("href",response.data.file);
    $("body").append($a);
    $a.attr("download","Shelve Table ",d+".xls");
    $a[0].click();
    $a.remove();
    
      
      });
    }
	
})

.controller('stockLocation', function($scope,$http,$window,$location) {
//	alert("ssssssss");
  $scope.baseUrl = new $window.URL($location.absUrl()).origin;
  $scope.filterData={};
  $scope.shipData=[];
  $scope.Items=[]

//console.log($scope.baseUrl);
 $scope.loadMore=function(page_no,reset)
    {
		//alert("sssssss");
		
    //console.log(page_no);    
   // console.log($scope.selectedData);    
     $scope.filterData.page_no=page_no;
     $scope.filterData.status=1;
      if(reset==1)
      {
      $scope.shipData=[];
      $scope.Items=[];
      }
  
    $http({
		url: "stockLocationFilter",
		method: "POST",
		data:$scope.filterData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           //console.log(response)
		 $scope.totalCount=response.data.count;
			 if(response.data.result.length > 0){
                        angular.forEach(response.data.result,function(value){
                            //console.log(value)
                           
                                $scope.shipData.push(value);
                            
//                         $scope.dataIndex=  $scope.shipData.findIndex( record => record.slip_no ===value.slip_no);   
//                        $scope.shipData[$scope.dataIndex].skuData=[];  
//                        $scope.shipData[$scope.dataIndex].skuData.push(JSON.parse(JSON.stringify(value.sku)));   
                                //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });
                //.console.log( $scope.shipData[0].skuData[0])
                 //$scope.$broadcast('scroll.infiniteScrollComplete');
                    }else{$scope.nodata=true
                    }					
			 
			 
		
  })  
  
        
    };    
    
   

 
 
$scope.exportExcelShowstock=function()
    {
		
		
    //console.log($scope.shipData);
      $http({
		url: "shelve/showstockViewExport",
		method: "POST",
		data:$scope.shipData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
    //	  console.log(response.data);
        
         var d = new Date();
    var $a = $("<a>");
    $a.attr("href",response.data.file);
    $("body").append($a);
    $a.attr("download",d+"orders.xls");
    $a[0].click();
    $a.remove();
    
      
      });
    
  
    }
	
    
	
  $scope.exportExcel=function()
    {
    //console.log($scope.shipData);
      $http({
		url: "pickUp/pickListViewExport",
		method: "POST",
		data:$scope.shipData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
		
      console.log(response);
        
         var d = new Date();
    var $a = $("<a>");
    $a.attr("href",response.data.file);
    $("body").append($a);
    $a.attr("download",d+"orders.xls");
    $a[0].click();
    $a.remove();
    
      
      });
    }
})
.controller('dispatch', function($scope,$http,$interval,$window){
$scope.shipData=[];
    $scope.completeShip=[];
    $scope.scan={};
    $scope.invalid=[];
	$scope.awbArray=[];
	$scope.shelve=null;
    $scope.type='DL';
	$scope.scan_awb=function(){
		//$('#scan_awb').focus();
         console.log($scope.scan);
        $scope.scan.awbArray=removeDumplicateValue($scope.scan.slip_no.split("\n"));
        console.log($scope.scan.awbArray); 
        $scope.scan.slip_no=$scope.scan.awbArray.join('\n');
         
        
        $scope.validateOrder();
		}
    
    $scope.dispatchOrder=function()
    {
		
    $scope.scan.type= $scope.type;
        $http({
		url: "PickUp/dispatchOrder",
		method: "POST",
		data:$scope.scan,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
		
	}).then(function (response) {
            console.log(response);
           // $scope.scan.awbArray={};
            $scope.scan={};
          if( response.data=='null')
            {
           
             var sound = document.getElementById("audioSuccess");
             sound.play();
            $scope.Message="Orders Dispatched !";
            responsiveVoice.speak($scope.Message); 
            }
            else
            {
            $scope.warning= response.data;
            }
            
        })
    
    }
	function removeDumplicateValue(myArray){ 
      var newArray = [];
   
      angular.forEach(myArray, function(value, key) {
        var exists = false;
        angular.forEach(newArray, function(val2, key) {
          if(angular.equals(value, val2)){ exists = true }; 
        });
        if(exists == false && value != "") { newArray.push(value); }
      });
    
      return newArray;
    }
     $scope.validateOrder=function(){
         $scope.invalid=[];
            $scope.warning=null;
             $scope.Message=null;
           // alert("ss");
		   
		//console.log($scope.scan);
		$http({
		url: "PickUp/validateDispatch",
		method: "POST",
		data:$scope.scan.awbArray,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           console.log(response);
                        
                        angular.forEach(response.data.invalid,function(value){
                        console.log(value)
                         var index=$scope.scan.awbArray.indexOf(value.slip_no);
                            if (index > -1) {
                            $scope.scan.awbArray.splice(index, 1);
                            }
                        $scope.invalid.push(value.slip_no);
                       
                                
                 }); 
            $scope.scan.slip_no=$scope.scan.awbArray.join('\n');
            $scope.invalidstring=$scope.invalid.join()
              
           
            });
            
            
        
	 	
        }


})

//=====================return from LM======================//
.controller('returnfromlm', function($scope,$http,$interval,$window){
$scope.shipData=[];
    $scope.completeShip=[];
    $scope.scan={};
    $scope.invalid=[];
	$scope.awbArray=[];
	$scope.shelve=null;
    $scope.scan.type='RTC';  
	$scope.scan_awb=function(){
		//$('#scan_awb').focus();
         console.log($scope.scan);
        $scope.scan.awbArray=removeDumplicateValue($scope.scan.slip_no.split("\n"));
        console.log($scope.scan.awbArray); 
        $scope.scan.slip_no=$scope.scan.awbArray.join('\n');
         
        
		
        $scope.validateOrder();
		}
    
    $scope.returnformlmOrder=function()
    {
    
        $http({
		url: "PickUp/returnformlmOrder",
		method: "POST",
		data:$scope.scan,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           console.log(response);    
           // $scope.scan.awbArray=[];
           // $scope.scan=[];
		   
		 /*  $scope.scan={};
            var sound = document.getElementById("audioSuccess");
             sound.play();
            $scope.Message="Orders Return To Fulfilment!";
            responsiveVoice.speak($scope.Message); 
            */
        })
    
    }
	function removeDumplicateValue(myArray){ 
      var newArray = [];
   
      angular.forEach(myArray, function(value, key) {
        var exists = false;
        angular.forEach(newArray, function(val2, key) {
          if(angular.equals(value, val2)){ exists = true }; 
        });
        if(exists == false && value != "") { newArray.push(value); }
      });
    
      return newArray;
    }
     $scope.validateOrder=function(){
         $scope.invalid=[];
            $scope.warning=null;
             $scope.Message=null;
            
		console.log($scope.scan);
		$http({
		url: "PickUp/validatereturn",
		method: "POST",
		data:$scope.scan,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           
                     console.log(response)    
                        angular.forEach(response.data.invalid,function(value){
                       // console.log("///"+value.slip_no+"////")
                         var index=$scope.scan.awbArray.indexOf(value.slip_no);
                            if (index > -1) {
                            $scope.scan.awbArray.splice(index, 1);
                            }
                        $scope.invalid.push(value.slip_no);
                       
                                
                 }); 
            $scope.scan.slip_no=$scope.scan.awbArray.join('\n');
            $scope.invalidstring=$scope.invalid.join()
              
           
            });
            
            
        
	 	
        }


})
//=========================================================//

.controller('bulkUpdate', function($scope,$http,$interval,$window){
$scope.shipData=[];
    $scope.completeShip=[];
    $scope.scan={};
    $scope.invalid=[];
	$scope.awbArray=[];
	$scope.shelve=null;
	$scope.scan_awb=function(){
		//alert($scope.scan.status);
		//$('#scan_awb').focus();
        // console.log($scope.scan);
        $scope.scan.awbArray=removeDumplicateValue($scope.scan.slip_no.split("\n"));
       // console.log($scope.scan.awbArray); 
        $scope.scan.slip_no=$scope.scan.awbArray.join('\n');
         
        
        $scope.validateOrder();
		}
    
    $scope.updateData=function()
    {
    console.log($scope.scan);
        $http({
		url: "updateData",
		method: "POST",
		data:$scope.scan,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
                 console.log(response);
            $scope.scan={};
            $scope.scan.status="";
            $scope.scan.awbArray=[];
            
            if(response.data.error.length>0)
            {
              $scope.warning=response.data.error + " Not Updated!";
            }
            if(response.data.success.length>0)
            {
              var sound = document.getElementById("audioSuccess");
             sound.play();
            $scope.Message="Orders Updated!";

            responsiveVoice.speak($scope.Message); 
            
            }
            
        })
    
    }
	function removeDumplicateValue(myArray){ 
      var newArray = [];
   
      angular.forEach(myArray, function(value, key) {
        var exists = false;
        angular.forEach(newArray, function(val2, key) {
          if(angular.equals(value, val2)){ exists = true }; 
        });
        if(exists == false && value != "") { newArray.push(value); }
      });
    
      return newArray;
    }
     $scope.scan.validate=null;
     $scope.validateOrder=function(){
         $scope.invalid=[];
            $scope.warning=null;
             $scope.Message=null;
             $scope.invalidstring={};
		//console.log($scope.scan);
		$http({
		url: "validateUpdate",
		method: "POST",
		data:$scope.scan,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
		
	}).then(function (response) {
           
                       console.log(response);
                        
                        angular.forEach(response.data.invalid,function(value){
                        console.log(value)
                         var index=$scope.scan.awbArray.indexOf(value.slip_no);
                            if (index > -1) {
                            $scope.scan.awbArray.splice(index, 1);
                            }
                        $scope.invalid.push(value.slip_no);
                       
                                
                 }); 
            $scope.scan.slip_no=$scope.scan.awbArray.join('\n');
            $scope.invalidstring=$scope.invalid.join()
             if($scope.invalidstring)
			{
				$scope.scan={};
			}
           // console.log($scope.scan.status);
            if( $scope.scan.status.length>0 && $scope.scan.slip_no.length>0 )
            {
            $scope.scan.validate=true;
              
            }
            else
            {
            $scope.scan.validate=null;
            }
              
           
            });
            
            
        
	 	
        }


})

.controller('scanShipment', function($scope,$http,$interval,$window){
	$scope.shipData=[];
    $scope.completeShip=[];
    $scope.scan={};
    $scope.scan_new={};
	$scope.specialtype={};
	
	$scope.awbArray=[];
        $scope.SKuMediaArr=[];
	$scope.shelve=null;
        $scope.scan_new.box_no=1;
	$scope.scan_awb=function(){
		$('#scan_awb').focus();
        $scope.packuShip();
		}
		
		
	    $scope.packuShip=function(){
            $scope.warning=null;
             $scope.Message=null;
              $scope.arrayIndexnew=[];
         $scope.scan.slip_no=$scope.scan.slip_no.toUpperCase()
         $scope.arrayIndex=$scope.awbArray.findIndex( record => record.slip_no.toUpperCase() ===$scope.scan.slip_no.toUpperCase()); 
            if( $scope.arrayIndex==-1)
            {
		//console.log($scope.scan);
		$http({
		url: "PickUp/packCheck",
		method: "POST",
		data:$scope.scan,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           //$scope.specialtype.specialpack=true;
		   //$scope.specialtype.specialpacktype="warehouse";
                        if(response.data.count==0)
                        {
                        $scope.warning="Order Not available for packing!";
                        responsiveVoice.speak($scope.warning); 
                        }
                        angular.forEach(response.data.result,function(value){
                        console.log(value)

                        $scope.awbArray.push(value);
                        angular.forEach(JSON.parse(value.sku),function(value1){
                        //console.log(value1)

        $scope.shipData.push({'slip_no':value.slip_no,'sku':value1.sku,'piece':value1.piece,'scaned':0,'extra':0,'print_url':value.print_url,'frwd_company_id':value.frwd_company_id,'frwd_company_awb':value.frwd_company_awb});
        $scope.SKuMediaArr.push({'sku':value1.sku,'piece':value1.piece,'item_path':value1.item_path});
                                
                                //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        }); 
                                
                                //$scope.Items.push( 'slip_no: ' +value.slip_no);
                 }); 
                 
                // console.log( $scope.SKuMediaArr);
                // $scope.GetcheckskuOtherData($scope.shipData[$scope.arrayIndexnew].sku,$scope.shipData[$scope.arrayIndexnew].piece);
                 
                 
              
           
            });
            }
            
         $scope.scanCheck();
        $scope.checkComplte($scope.shipData,$scope.scan.slip_no);    
	 	
        }
        $scope.packBoxArr={};
        
        $scope.GetCheckBoxNo=function()
        {
            $scope.scan_new.slip_no=$scope.scan.slip_no;
        }
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
                        
                     $('#GetSkuId'+$scope.arrayIndexnew).css({"background-color":"green"});
                    $scope.Message=null;    
                   // $scope.warning='All Parts Scanned for '+$scope.shipData[$scope.arrayIndexnew].sku;
					 $scope.warning='All Parts Scanned for '+$scope.shipData[$scope.arrayIndexnew].sku;
                    //responsiveVoice.speak($scope.warning);   
                    }
                    else
                    {
                       // alert($scope.shipData[$scope.arrayIndexnew].sku);
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
            $scope.ShowOtherSkuArr={};
            $scope.showmedia=false;
            $scope.GetcheckskuOtherData=function(sku,qty)
            {
                
                  $http({
		url: "ItemInventory/GetshowingSkuMeadiaData",
		method: "POST",
		data:{sku:sku},
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
             $scope.showmedia=true;
            $scope.ShowOtherSkuArr=response.data;
            $scope.ShowOtherSkuArr.qty=qty;
            
            
        });
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
            //alert($scope.scan_new.box_no);
              $scope.inxexComp= $scope.completeArray.findIndex( record => (record.slip_no ===$scope.scan.slip_no ))
            if($scope.inxexComp==-1)
               {$scope.completeArray.push({'slip_no':$scope.checkArray[0].slip_no,'specialpack':$scope.specialtype.specialpack,'specialpacktype':$scope.specialtype.specialpacktype,'print_url':$scope.checkArray[0].print_url,'frwd_company_id':$scope.checkArray[0].frwd_company_id,'frwd_company_awb':$scope.checkArray[0].frwd_company_id,'box_no':$scope.scan_new.box_no});}
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
            SpecialArr:$scope.specialtype,
            boxArr:$scope.scan_new,
             },
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           console.log(response);  
                 
//         var d = new Date();
//    var $a = $("<a>");
//    $a.attr("href",response.data.file);
//    $("body").append($a);
//    $a.attr("download",response.data.file_name);
//    $a[0].click();
//    $a.remove();
         
          $scope.SKuMediaArr={};   
        $scope.shipData=[];
        $scope.completeArray=[];
        $scope.Message="Completed order Packed!"; 
             
             
         },function(error){console.log(error);});
         }
     
     }
    }
		
})


/*------ show shipments-----*/
.controller('shipment_view', function($scope,$http,$window) {
  
 
	
	
  $scope.filterData={};
$scope.shipData=[];   
$scope.excelshipData=[]; 
 $scope.dropexport=[];
  $scope.Items=[];
 $scope.dropshort={};
     $scope.loadershow=false; 
	$scope.filterData.s_type='AWB';
 $scope.loadMore=function(page_no,reset)  
    {
		//  disableScreen(1);
		 //$scope.loadershow=true; 
    console.log(page_no);    
   // console.log($scope.selectedData);    
     $scope.filterData.page_no=page_no;
      if(reset==1)
      {
      $scope.shipData=[];
      }
         
    $http({
		url: "Shipment/filter",
		method: "POST",
		data:$scope.filterData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           console.log(response)   
           $scope.dropshort=response.data.dropshort;
		 $scope.totalCount=response.data.count;
		 $scope.shipDataexcel=response.data.excelresult;
		  $scope.dropexport=response.data.dropexport;
		 
			 if(response.data.result.length > 0){     
                        angular.forEach(response.data.result,function(value){
                            //console.log(value.slip_no)
                                    
                                $scope.shipData.push(value);

                        });
                //console.log( $scope.shipData)
                 //$scope.$broadcast('scroll.infiniteScrollComplete');
                    }else{$scope.nodata=true
                    }	
					
					  disableScreen(0);
		 $scope.loadershow=false; 				
			 
			 
		
  },function(status,error){
      
       disableScreen(0);
		 $scope.loadershow=false;  
  })  
  
        
    };    
    
    
    $scope.GetProcessOpenOrder=function(slip_no)
    {
         disableScreen(1);
      $scope.loadershow=true; 
         $http({
        url: "Shipment/GetProcessOpenOrder",
        method: "POST",
        data: {slip_no:slip_no},
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }

      }).then(function (response) {
        
 disableScreen(0);
		 $scope.loadershow=false; 
                 alert("successfully status changed");
                 $scope.loadMore(1,1);
      });
    };
   $scope.GetInventoryPopup = function (id) {
	     disableScreen(1);
		 $scope.loadershow=true; 	
      //alert(id); 
      //data:$scope.shipData,
      $scope.filterData.id = id;
      $http({
        url: "Shipment/filterdetail",
        method: "POST",
        data: $scope.filterData,
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }

      }).then(function (response) {
        //console.log(response)

        $scope.shipData1 = response.data;
        console.log($scope.shipData1)
        $("#deductQuantityModal").modal({
          backdrop: 'static',
          keyboard: true
        })

 disableScreen(0);
		 $scope.loadershow=false; 


      })
     	


    }
	
	
	
	 $scope.ExportData = {};
    $scope.listData1 = [];
	$scope.listData2 = {};
	$scope.listDatalist = {};  
    $scope.getExcelDetails = function () {

        $scope.listData1.exportlimit = $scope.filterData.exportlimit;
        $("#excelcolumn").modal({backdrop: 'static',
            keyboard: false})
    };
	
	
	 $scope.checkall = false;
    $scope.checkAll = function () {
        if ($scope.checkall === false) {
            angular.forEach($scope.listData1, function (data) {
                data.checked = true;
            });
            $scope.checkall = true;
        } else {
            angular.forEach($scope.listData1, function (data) {
                data.checked = false;
            });
            $scope.checkall = false;
        }
    };
    
     $scope.selectedAll=false;
     $scope.selectAll = function(val) {
        //alert(val);
         
         
       // console.log("sssssss");
        var newval=val-1;
      angular.forEach($scope.shipData, function(data,key) {
         // console.log(key+"======="+newval);
          if(key<=newval)
          {
              
               //console.log(key+"======="+newval);
               //console.log($scope.selectedAll);
        data.Selected = true;
        
        $scope.Items.push(data.slip_no);
        
          }
          else
          {
                //console.log($scope.selectedAll);
        data.Selected = $scope.selectedAll;
        if($scope.selectedAll==true)  
        $scope.Items.push(data.slip_no);
          else
        $scope.Items=[];   
          }
              
      });
         
         
    };
    
     $scope.checkIfAllSelected = function() {
      $scope.selectedAll = $scope.shipData.every(function(data) {
        return data.Selected == true
      })
        
    };
    
    $scope.removemultipleorder=function()
    {
        console.log($scope.Items);
        if($scope.Items.length>0)
    {
       $http({
		url: "Shipment/GetremoveMultipleOrders",
		method: "POST",
		data:{slipData:$scope.Items,page_check:'ship_view'},
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
                
	}).then(function (response) {
            if(response.data=="succ")
	{
		alert("successfuly Deleted");
		$window.location.reload();
	}
	else
	{
		alert("successfuly Deleted");
		$window.location.reload();
	}
            
        });
    }
    else
    {
        alert("please select atleat one order");
    }
  
    };
	
	
	  $scope.transferShip1 = function () {   

        //$scope.exportlimit
		
		$scope.listDatalist.filterData=$scope.filterData;
		$scope.listDatalist.listData2=$scope.listData2;      
   
		console.log($scope.listDatalist);  
	$http({
		url: "Shipment/getexceldata",
		method: "POST",
		data:$scope.listDatalist,        
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}  
		 }).then(function (results) {
			console.log(results);  
			var $a = $("<a>");  
            $a.attr("href", results.data.file);
            $("body").append($a);
            $a.attr("download", results.data.file_name);
            $a[0].click();
            $a.remove();



        });
		$('#excelcolumn').modal('hide');  
    };	
	
	    
	
	
	
  $scope.exportExcel=function()
    {
        if($scope.filterData.exportlimit>0)
        {
        console.log("ssssssssss");
		  disableScreen(1);
		 $scope.loadershow=true; 
   // console.log($scope.exportlimit);
      $http({
		url: "Shipment/exportPackedExcel",
		method: "POST",
		data:$scope.filterData,     
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
      console.log(response);
        
         var d = new Date(); 
    var $a = $("<a>");
    $a.attr("href",response.data.file); 
    $("body").append($a);
    $a.attr("download",d+"orders.xls");
    $a[0].click();
    $a.remove(); 
      disableScreen(0);
		 $scope.loadershow=false; 
      
      },
       function(data) {
             disableScreen(1);
		 $scope.loadershow=true;
           console.log(data);
       });
  }
  else
  {
      alert("please select export limit");
  }
    }
})


.controller('itemInvontary_view', function($scope,$http) {
  
  $scope.filterData={};
$scope.shipData=[];   

 
    
 $scope.loadMore=function(page_no,reset)
    {
    console.log(page_no);    
   // console.log($scope.selectedData);    
     $scope.filterData.page_no=page_no;
      if(reset==1)
      {
      $scope.shipData=[];
      }
  
    $http({
		url: "ItemInventory/filter",
		method: "POST",
		data:$scope.filterData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           console.log(response.data.result)
		 $scope.totalCount=response.data.count;
			 if(response.data.result.length > 0){
                        angular.forEach(response.data.result,function(value){
                            console.log(value.slip_no)
                                    
                                $scope.shipData.push(value);

                        });
                //console.log( $scope.shipData)
                 //$scope.$broadcast('scroll.infiniteScrollComplete');
                    }else{$scope.nodata=true
                    }					
			 
			 
		
  })  
  
        
    };    
    
  $scope.exportExcel=function()
    {
    console.log($scope.shipData);
      $http({
		url: "Shipment/exportExcel",
		method: "POST",
		data:$scope.shipData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
      console.log(response.data.file);
        
         var d = new Date();
    var $a = $("<a>");
    $a.attr("href",response.data.file);
    $("body").append($a);
    $a.attr("download",d+"orders.xls");
    $a[0].click();
    $a.remove();
    
      
      });
    }
})





.controller('pickupList', function($scope,$http,$window,Excel,$timeout) {
  $scope.AssignData={};
  $scope.filterData={};
  $scope.shipData=[];
  $scope.Items=[];
    $scope.pickerArray=[];
	 $scope.loadershow=false; 
	
$scope.assignPicker=function(pickup_id)
{
console.log(pickup_id);
$("#exampleModal").modal() 
$scope.pickId= pickup_id;   
}
$scope.savePicker=function()
{
$("#exampleModal").modal('hide');
    
 //$scope.pickerArray;
$scope.arrayIndex=$scope.pickerArray.findIndex( record => record.id ===$scope.AssignData.selectedPicker);
    
$scope.AssignData.pickId = $scope.pickId;
  
$scope.arrayIndexMain=$scope.shipData.findIndex( record => record.pickupId ===$scope.AssignData.pickId); 
$scope.shipData[$scope.arrayIndexMain].assigned_to=  $scope.pickerArray[$scope.arrayIndex].username; 
console.log($scope.shipData[$scope.arrayIndexMain].assigned_to); 
  $http({
		url: "PickUp/assignPicker",
		method: "POST",
		data:$scope.AssignData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
      
      
  })
    
    
}

 $scope.loadMore=function(page_no,reset)
    {
		  disableScreen(1);
		 $scope.loadershow=true; 
    console.log(page_no);    
   // console.log($scope.selectedData);    
     $scope.filterData.page_no=page_no;
     $scope.filterData.status=1;
      if(reset==1)
      {
      $scope.shipData=[];
      $scope.Items=[];
      }
  
    $http({
		url: "PickUp/filter",
		method: "POST",
		data:$scope.filterData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           console.log(response.data.result)
		 $scope.totalCount=response.data.count;
		   $scope.dropexport=response.data.dropexport;  
         $scope.pickerArray=response.data.picker;
			 if(response.data.result.length > 0){
                        angular.forEach(response.data.result,function(value){
                            console.log(value)

                                $scope.shipData.push(value);
                                //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });
                //console.log( $scope.Items)
                 //$scope.$broadcast('scroll.infiniteScrollComplete');
                    }else{$scope.nodata=true
                    }	
					 disableScreen(0);
		 $scope.loadershow=false; 				
			 
			 
		
  })  
  
        
    };    
    
   
    
    $scope.generatePickup=function()
{
    $scope.shipData_new = $scope.shipData.filter(function(item) {
  return $scope.Items.includes(item.slip_no); 
})
        if($scope.shipData_new.length==0)
        {alert('Please select Orders to generate Pickupsheet!');}
        else
        {
  var isConfirmed = confirm("You are going to generate Pickupsheet, This Action will change the Order status! Are you sure?");         
   
        if(isConfirmed)
        {

console.log($scope.shipData_new); 
console.log($scope.Items); 
            
        $http({
		url: "generatePickup",
		method: "POST",
		data:{
            listData:$scope.shipData_new,
            slipData:$scope.Items   
        },
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) { 
        
        $window.location.reload();
        
        })     
        }
        }
}
 

    
  $scope.exportExcel=function()
    {
   // alert('ss');
	  disableScreen(1);
		 $scope.loadershow=true; 
		if($scope.filterData.exportlimit>0)
		{
      $http({
		url: "PickUp/exportExcel_picklist",
		method: "POST",
		data:$scope.filterData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
      console.log(response.data.file);
        
         var d = new Date();
    var $a = $("<a>");
    $a.attr("href",response.data.file);
    $("body").append($a);
    $a.attr("download",d+"Picklist orders.xls");
    $a[0].click();
    $a.remove();
      disableScreen(0);
		 $scope.loadershow=false; 
      
      },function(data){
          disableScreen(0);
		 $scope.loadershow=false; 
      }
      );
		}
		else
		{
			 disableScreen(0);
		 $scope.loadershow=false; 
		alert("Please Select Export Limit");
		
		}
    }
	
	$scope.shipData1=[];
	  $scope.exportExcelpickuplist=function()
    {
		 disableScreen(1);
		 $scope.loadershow=true; 
   console.log($scope.exportlimit);
	
      $http({
		url: "PickUp/exportExcelpick",   
		method: "POST",
		data:$scope.exportlimit, 
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
     // console.log(response);
        
         $scope.pickerArray=response.data.picker;
			 if(response.data.result.length > 0){
                        angular.forEach(response.data.result,function(value){
                            //console.log(value)

                                $scope.shipData1.push(value);
                                //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });
                console.log( $scope.shipData1);
                 //$scope.$broadcast('scroll.infiniteScrollComplete');
                    }else{$scope.nodata=true
                    }	
					 disableScreen(0);
		 $scope.loadershow=false; 
    
      
      });
    }
	
	$scope.exportToExcelpicklistReport = function (testTable_new) { 
      //alert("Hi");   
          $timeout(function () {     
                  var exportHref = Excel.tableToExcel(downloadtable , 'sheet name');
                location.href = exportHref; }, 50000); // trigger download         
        }
})

.controller('pickListView', function($scope,$http,$window,$location) {
  $scope.baseUrl = new $window.URL($location.absUrl()).origin;
  $scope.filterData={};
  $scope.shipData=[];
  $scope.Items=[]

console.log($scope.baseUrl);
 $scope.loadMore=function(page_no,reset)
    {
    console.log(page_no);    
   // console.log($scope.selectedData);    
     $scope.filterData.page_no=page_no;
     $scope.filterData.status=1;
      if(reset==1)
      {
      $scope.shipData=[];
      $scope.Items=[];
      }
  
    $http({
		url: "pickListFilter",
		method: "POST",
		data:$scope.filterData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           console.log(response.data.result)
		 $scope.totalCount=response.data.count;
			 if(response.data.result.length > 0){
                        angular.forEach(response.data.result,function(value){
                            //console.log(value)
                           
                                $scope.shipData.push(value);
                            
//                         $scope.dataIndex=  $scope.shipData.findIndex( record => record.slip_no ===value.slip_no);   
//                        $scope.shipData[$scope.dataIndex].skuData=[];  
//                        $scope.shipData[$scope.dataIndex].skuData.push(JSON.parse(JSON.stringify(value.sku)));   
                                //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });
                //.console.log( $scope.shipData[0].skuData[0])
                 //$scope.$broadcast('scroll.infiniteScrollComplete');
                    }else{$scope.nodata=true
                    }					
			 
			 
		
  })  
  
        
    };    
    
   

 

    
  $scope.exportExcel=function()
    {
    console.log($scope.shipData);
      $http({
		url: "pickUp/pickListViewExport",
		method: "POST",
		data:$scope.shipData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
      console.log(response.data.file);
        
         var d = new Date();
    var $a = $("<a>");
    $a.attr("href",response.data.file);
    $("body").append($a);
    $a.attr("download",d+"orders.xls");
    $a[0].click();
    $a.remove();
    
      
      });
    }
})

 .controller('deliveryManifest', function ($scope, $http, $window, Excel, $timeout) {
            $scope.AssignData = {};
            $scope.filterData = {};
            $scope.shipData = [];
            $scope.Items = [];
            $scope.pickerArray = [];
            $scope.loadershow = false;

            
            

            $scope.loadMore = function (page_no, reset)
            {
                disableScreen(1);
                $scope.loadershow = true;
                console.log(page_no);
                // console.log($scope.selectedData);    
                $scope.filterData.page_no = page_no;
                $scope.filterData.status = 1;
                if (reset == 1)
                {
                    $scope.shipData = [];
                    $scope.Items = [];
                }

                $http({
                    url: "Shipment/manifest_filter",
                    method: "POST",
                    data: $scope.filterData,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    console.log(response.data.result)
                    $scope.totalCount = response.data.count;
                    $scope.dropexport = response.data.dropexport;
                    $scope.pickerArray = response.data.picker;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            console.log(value)

                            $scope.shipData.push(value);
                            //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });
                        //console.log( $scope.Items)
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }
                    disableScreen(0);
                    $scope.loadershow = false;



                },function(status)
                {
                    // console.log(error)
                   disableScreen(0);
                    $scope.loadershow = false;  
                })


            };

            $scope.exportExcel = function ()
            {
                // alert('ss');
                disableScreen(1);
                $scope.loadershow = true;
                if ($scope.filterData.exportlimit > 0)
                {
                    $http({
                        url: "PickUp/exportExcel_picklist",
                        method: "POST",
                        data: $scope.filterData,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                    }).then(function (response) {
                        console.log(response.data.file);

                        var d = new Date();
                        var $a = $("<a>");
                        $a.attr("href", response.data.file);
                        $("body").append($a);
                        $a.attr("download", d + "Picklist orders.xls");
                        $a[0].click();
                        $a.remove();
                        disableScreen(0);
                        $scope.loadershow = false;

                    });
                } else
                {
                    disableScreen(0);
                    $scope.loadershow = false;
                    alert("Please Select Export Limit");

                }
            }

            $scope.shipData1 = [];
            $scope.exportExcelpickuplist = function ()
            {
                disableScreen(1);
                $scope.loadershow = true;
                console.log($scope.exportlimit);

                $http({
                    url: "PickUp/exportExcelpick",
                    method: "POST",
                    data: $scope.exportlimit,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'}

                }).then(function (response) {
                    // console.log(response);

                    $scope.pickerArray = response.data.picker;
                    if (response.data.result.length > 0) {
                        angular.forEach(response.data.result, function (value) {
                            //console.log(value)

                            $scope.shipData1.push(value);
                            //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });
                        console.log($scope.shipData1);
                        //$scope.$broadcast('scroll.infiniteScrollComplete');
                    } else {
                        $scope.nodata = true
                    }
                    disableScreen(0);
                    $scope.loadershow = false;


                });
            }

            $scope.exportToExcelpicklistReport = function (testTable_new) {
                //alert("Hi");   
                $timeout(function () {
                    var exportHref = Excel.tableToExcel(downloadtable, 'sheet name');
                    location.href = exportHref;
                }, 50000); // trigger download         
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
.directive('ngConfirmClick', [
  function(){
    return {
      priority: -1,
      restrict: 'A',
      link: function(scope, element, attrs){
        element.bind('click', function(e){
          var message = attrs.ngConfirmClick;
          // confirm() requires jQuery
          if(message && !confirm(message)){
            e.stopImmediatePropagation();
            e.preventDefault();
          }
        });
      }
    }
  }
])
/*------ /show shipments-----*/