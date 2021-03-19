var app = angular.module('AppTickets', [])




.controller('CTR_ticketlist', function($scope,$http,$window,$location,$anchorScroll) {
	$scope.baseUrl = new $window.URL($location.absUrl()).origin;
  $scope.AssignData={};
  $scope.filterData={};
  $scope.shipData=[];
  $scope.Items=[];
  $scope.message=[];
    $scope.pickerArray=[];
	$scope.MupdateData={};
	$scope.Updateqtyconf={};
	$scope.stockLocation={};
	$scope.UpdateHold={};
	$scope.TicketData={};
	$scope.historyData={};
	$scope.ShowhistoryData={};



$scope.GetUpdateTicketData=function(id)
{
	
	if(id)
	{
	$scope.TicketData.tid=id;	
	//console.log($scope.MupdateData);
	 $http({
		url: "Ticket/GetUpdateticketdatafile",
		method: "POST",
		data:$scope.TicketData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
		//console.log(response);
		
		if(response.data=='true')
		{
		alert("successfully ticket Updated.");
		location.reload();
		}
		else
		{
			
			alert("already Updated.");
			location.reload();
		}
		//$scope.MupdateData.sku="";
      
      
  },function (error){console.log(error);})
	}else
	{
		alert("try again");
	}
}

 $scope.getcheckpopshow=function(tid,oldtcount,interCron)
 {
	 //alert("sssss");
	$scope.historyData.tid=tid; 
	$scope.historyData.oldtcount=oldtcount; 
	$scope.historyData.interCron=interCron; 
	
		 $http({
		url: $scope.baseUrl+"/Ticket/getallticketshitorydata",
		method: "POST",
		data:$scope.historyData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
		//console.log(response);
		$scope.ShowhistoryData=response.data.result;
		//$scope.ShowhistoryData.oldtcount=response.data.tcount;
		$scope.historyData.oldtcount=response.data.oldtcount;
		
	//alert($scope.ShowhistoryData.oldtcount);
		$("#exampleModal").modal({ backdrop: 'static',keyboard: false}) ;
			 var totalcount = response.data.tcount;
			//$location.hash('msg_container_base' + totalcount);
		// var element = angular.element("msg_container_base");
		 
       // element.focus()
		 
		
	})
 }
 $scope.getcheckpopshow_cron=function(tid,oldtcount)
 {
	 //console.log($scope.historyData);
	// $scope.historyData.tid=tid; 
	//$scope.historyData.oldtcount=oldtcount; 
	//$scope.historyData.interCron=interCron; 
	
		 $http({
		url: $scope.baseUrl+"/Ticket/getallticketshitorydata",
		method: "POST",
		data:$scope.historyData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
		//console.log(response);
		$scope.ShowhistoryData=response.data.result;
		//$scope.ShowhistoryData.oldtcount=response.data.tcount;
		//$scope.historyData.oldtcount=response.data.oldtcount;
		 $scope.historyData.oldtcount=response.data.tcount;
		
	//alert($scope.ShowhistoryData.oldtcount);
		//$("#exampleModal").modal({ backdrop: 'static',keyboard: false}) ;
			 var totalcount = response.data.tcount;
		  var oldtcount = response.data.oldtcount;
		  
			 if(totalcount>oldtcount)
		$(".msg_container_base").animate({ scrollTop: $('.msg_container_base').prop("scrollHeight")}, 1000);
			//$location.hash('msg_container_base' + totalcount);
		// var element = angular.element("msg_container_base");
		 
       // element.focus()
		 
		
	})
 }
  $scope.Getreplyadd=function()
 {
	 //alert($scope.ShowhistoryData.oldtcount);
	   // $scope.historyData.oldtcount=$scope.ShowhistoryData.oldtcount; 
		
		//console.log($scope.historyData);
		 $http({
		url: $scope.baseUrl+"/Ticket/getallticketshitorydata",
		method: "POST",
		data:$scope.historyData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
		//console.log(response);
		$scope.ShowhistoryData=response.data.result;
		
		//console.log($scope.ShowhistoryData.oldtcount);
		
		//$("#exampleModal").modal({ backdrop: 'static',keyboard: false}) ;
		//alert(response.data.oldtcount);
		 $scope.historyData.oldtcount=response.data.tcount;
		 var totalcount = response.data.tcount;
		  var oldtcount = response.data.oldtcount;
		
		 //alert($scope.ShowhistoryData.oldtcount);
		  //alert(oldtcount);
		//$location.hash('msg_container_base' + totalcount);
		// var element = angular.element("#scrrclass"+totalcount);
       // element.focus()
		//alert($('.msg_container_base').scrollTop());
		if(totalcount>oldtcount)
		$(".msg_container_base").animate({ scrollTop: $('.msg_container_base').prop("scrollHeight")}, 1000);
		 //$anchorScroll();
		$scope.historyData.replymess="";
		
		
	})
 }
 setInterval(function(){
	 if($scope.historyData.interCron=='interCron')
	 {
     $scope.getcheckpopshow_cron($scope.historyData.tid,$scope.historyData.oldtcount);
	 }
}, 5000)
 
 $scope.loadMore=function(page_no,reset)
    {
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
		url: "Ticket/filter",
		method: "POST",
		data:$scope.filterData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           console.log(response)
		 $scope.totalCount=response.data.count;
          $scope.assigndata=response.data.assignuser;
		  $scope.sellers=response.data.sellers;
		 // $scope.stockLocation=response.data.stockLocation;
		  
			 if(response.data.result.length > 0){
                        angular.forEach(response.data.result,function(value){
                          //  console.log(value)

                                $scope.shipData.push(value);
                                //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });
                //console.log( $scope.Items)
                 //$scope.$broadcast('scroll.infiniteScrollComplete');
                    }else{$scope.nodata=true
                    }					
			 
			 
		
  })  
  
        
    };    
    
   

 

    

})



.controller('CTR_ticketlist_fulfil', function($scope,$http,$window,$location,$anchorScroll) {
	$scope.baseUrl = new $window.URL($location.absUrl()).origin;
  $scope.AssignData={};
  $scope.filterData={};
  $scope.shipData=[];
  $scope.Items=[];
  $scope.message=[];
    $scope.pickerArray=[];
	$scope.MupdateData={};
	$scope.Updateqtyconf={};
	$scope.stockLocation={};
	$scope.UpdateHold={};
	$scope.TicketData={};
	$scope.historyData={};
	$scope.ShowhistoryData={};
	


$scope.GetfulfulTicketUpdate=function()
{
	if($scope.TicketData.upstatus=='error')
	{
		alert("please select status");
	}
	else
	{
	 $http({
		url: "Ticket/GetUpdateticketdatafile_fm",
		method: "POST",
		data:$scope.TicketData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
		//console.log(response);
		
		if(response.data=='true')
		{
		alert("successfully ticket Updated.");
		$scope.loadMore(1,1);
		$("#showskuformviewid2").modal('hide');
		}
		else
		{
			
			alert("successfully ticket Updated.");
			$scope.loadMore(1,1);
			 $("#showskuformviewid2").modal('hide');
		}
		//$scope.MupdateData.sku="";
      
      
  },function (error){console.log(error);})
	}
}
$scope.GetUpdateTicketData=function(id,ticket_id)
{
	$scope.TicketData.upstatus="error";
	$scope.TicketData.ticket_id=ticket_id;
	$scope.TicketData.id=id;
	//alert("sssssss");
	 $("#showskuformviewid2").modal({ backdrop: 'static',keyboard: false}) 
	if(id)
	{
	//$scope.TicketData.tid=id;	
	//console.log($scope.MupdateData);
	/* $http({
		url: "Ticket/GetUpdateticketdatafile",
		method: "POST",
		data:$scope.TicketData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
		//console.log(response);
		
		if(response.data=='true')
		{
		alert("successfully ticket Updated.");
		location.reload();
		}
		else
		{
			
			alert("successfully ticket Updated.");
			location.reload();
		}
		//$scope.MupdateData.sku="";
      
      
  },function (error){console.log(error);})*/
	}else
	{
		alert("try again");
	}
}

 $scope.getcheckpopshow=function(tid,oldtcount,interCron)
 {
	 //alert("sssss");
	$scope.historyData.tid=tid; 
	$scope.historyData.oldtcount=oldtcount; 
	$scope.historyData.interCron=interCron; 
	
		 $http({
		url: $scope.baseUrl+"/Ticket/getallticketshitorydata_fm",
		method: "POST",
		data:$scope.historyData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
		//console.log(response);
		$scope.ShowhistoryData=response.data.result;
		//$scope.ShowhistoryData.oldtcount=response.data.tcount;
		$scope.historyData.oldtcount=response.data.oldtcount;
		
	//alert($scope.ShowhistoryData.oldtcount);
		$("#exampleModal").modal({ backdrop: 'static',keyboard: false}) ;
			 var totalcount = response.data.tcount;
			//$location.hash('msg_container_base' + totalcount);
		// var element = angular.element("msg_container_base");
		 
       // element.focus()
		 
		
	})
 }
 $scope.getcheckpopshow_cron=function(tid,oldtcount)
 {
	 //console.log($scope.historyData);
	// $scope.historyData.tid=tid; 
	//$scope.historyData.oldtcount=oldtcount; 
	//$scope.historyData.replymess=""; 
	
		 $http({
		url: $scope.baseUrl+"/Ticket/getallticketshitorydata_fm",
		method: "POST",
		data:$scope.historyData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
		//console.log(response);
		$scope.ShowhistoryData=response.data.result;
		//$scope.ShowhistoryData.oldtcount=response.data.tcount;
		//$scope.historyData.oldtcount=response.data.oldtcount;
		 $scope.historyData.oldtcount=response.data.tcount;
		
	//alert($scope.ShowhistoryData.oldtcount);
		//$("#exampleModal").modal({ backdrop: 'static',keyboard: false}) ;
			 var totalcount = response.data.tcount;
		  var oldtcount = response.data.oldtcount;
		  
			 if(totalcount>oldtcount)
		$(".msg_container_base").animate({ scrollTop: $('.msg_container_base').prop("scrollHeight")}, 1000);
			//$location.hash('msg_container_base' + totalcount);
		// var element = angular.element("msg_container_base");
		 
       // element.focus()
		 
		
	})
 }
  $scope.Getreplyadd=function()
 {
	 //alert($scope.ShowhistoryData.oldtcount);
	   // $scope.historyData.oldtcount=$scope.ShowhistoryData.oldtcount; 
		
		//console.log($scope.historyData);
		 $http({
		url: $scope.baseUrl+"/Ticket/replyaddchat_fm",
		method: "POST",
		data:$scope.historyData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
		//console.log(response);
		$scope.ShowhistoryData=response.data.result;
		
		//console.log($scope.ShowhistoryData.oldtcount);
		
		//$("#exampleModal").modal({ backdrop: 'static',keyboard: false}) ;
		//alert(response.data.oldtcount);
		 $scope.historyData.oldtcount=response.data.tcount;
		 var totalcount = response.data.tcount;
		  var oldtcount = response.data.oldtcount;
		
		 //alert($scope.ShowhistoryData.oldtcount);
		  //alert(oldtcount);
		//$location.hash('msg_container_base' + totalcount);
		// var element = angular.element("#scrrclass"+totalcount);
       // element.focus()
		//alert($('.msg_container_base').scrollTop());
		if(totalcount>oldtcount)
		$(".msg_container_base").animate({ scrollTop: $('.msg_container_base').prop("scrollHeight")}, 1000);
		 //$anchorScroll();
		$scope.historyData.replymess="";
		
		
	})
 }
 setInterval(function(){
	 if($scope.historyData.interCron=='interCron')
	 {
     $scope.getcheckpopshow_cron($scope.historyData.tid,$scope.historyData.oldtcount);
	 }
}, 5000)
 
 $scope.loadMore=function(page_no,reset)
    {
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
		url: "Ticket/filter_fulfil",
		method: "POST",
		data:$scope.filterData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           console.log(response)
		 $scope.totalCount=response.data.count;
          $scope.assigndata=response.data.assignuser;
		  $scope.sellers=response.data.sellers;
		 // $scope.stockLocation=response.data.stockLocation;
		  
			 if(response.data.result.length > 0){
                        angular.forEach(response.data.result,function(value){
                          //  console.log(value)

                                $scope.shipData.push(value);
                                //$scope.Items.push( 'slip_no: ' +value.slip_no);
                        });
                //console.log( $scope.Items)
                 //$scope.$broadcast('scroll.infiniteScrollComplete');
                    }else{$scope.nodata=true
                    }					
			 
			 
		
  })  
  
        
    };    
    
   

 

    

})

.run(['$anchorScroll', function($anchorScroll) {
  $anchorScroll.yOffset = 200;   // always scroll by 50 extra pixels
}])

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
