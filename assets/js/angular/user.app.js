var app = angular.module('usersApp', [])

 

.controller('PickerSettingsCtlr', function($scope,$http,$window,$location) {
  $scope.baseUrl = new $window.URL($location.absUrl()).origin;
  $scope.filterData={};
  $scope.UserArr={};
  $scope.sub_catArr={};
  $scope.sub_catArr_main={};
  
   angular.element(document).ready(function () {
       
       
   });
  
  
 


 $scope.loadMore=function(user_id)
    {
       $scope.filterData.user_id=user_id;
   $http({
		url: $scope.baseUrl+"/Users/getShowpickerListings",
		method: "POST",
		data:$scope.filterData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           
           
		  $scope.UserArr=response.data;
						
			 
			 
		
  })  
  
        
    };    
    
     $scope.showaccesstemplatelist=function()
    {
     
   $http({
		url: URLBASE+"Users/showaccesstemplatelist",
		method: "POST",
		data:$scope.filterData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		
	}).then(function (response) {
           
           
		  $scope.UserArr=response.data;
						
			 
			 
		
  });  
  
        
    };  
    



    $scope.GetSubCatDatashow=function(uid)
    {
        $scope.filterData.privilage_array_sub=[];
        $scope.filterData.uid=uid;
    
         $http({
		url: URLBASE+"Users/GetSubCatDatashow",
		method: "POST",
		data:$scope.filterData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	}).then(function (response) {
           $scope.sub_catArr=response.data.sub_array;
           
           $scope.filterData.privilage_array_sub=response.data.privilage_array_sub;
           //$scope.filterData.privilage_array=response.data.privilage_array;
           
		
  });  
  
    };
    
    $scope.getmaincatVal=function(uid)
    {
        $scope.filterData.privilage_array=[];
        $scope.filterData.uid=uid;
        $scope.sub_catArr_main=[];
    
         $http({
		url: URLBASE+"Users/getmaincatVal",
		method: "POST",
		data:$scope.filterData,
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	}).then(function (response) {
           $scope.sub_catArr_main=response.data.sub_array;
           
           //$scope.filterData.privilage_array_sub=response.data.privilage_array_sub;
           $scope.filterData.privilage_array=response.data.privilage_array;
           
		
  });  
  
    };
    
    
    

	
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
.directive('convertToNumber', function() {
  return {
    require: 'ngModel',
    link: function(scope, element, attrs, ngModel) {
      ngModel.$parsers.push(function(val) {
        return parseInt(val, 10);
      });
      ngModel.$formatters.push(function(val) {
        return '' + val;
      });
    }
  };
});
/*------ /show shipments-----*/