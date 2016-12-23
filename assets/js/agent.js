		/* + useful methods */
		/* also see http://javascript.crockford.com/remedial.html for supplant */
		
		String.prototype.supplant = function (o) {
		    return this.replace(/{([^{}]*)}/g,
		        function (a, b) {
		            var r = o[b];
		            return typeof r === 'string' || typeof r === 'number' ? r : a;
		        });
		};		

		// add trim if unavailable
		if (!String.prototype.trim) {
			String.prototype.trim = function () {
		    	return this.replace(/^\s+|\s+$/g, '');
			};
		};
		
		String.prototype.squeeze = function() {
		    return this.replace(/\s/g, '');
		};
		
		String.prototype.isEmpty = function() {
		    return (this.length === 0 || !this.trim());
		};
		
		// angularjs modules
		var agentApp = angular.module('YuktixAgentApp', []);

		// ng-enter directive 
		// copied from http://eric.sau.pe/angularjs-detect-enter-key-ngenter
		agentApp.directive('ngEnter', function () {
		    return function (scope, element, attrs) {
		        element.bind("keydown keypress", function (event) {
		            if(event.which === 13) {
		                scope.$apply(function (){
		                    scope.$eval(attrs.ngEnter);
		                });
		 
		                event.preventDefault();
		            }
		        });
		    };
		});
		
		agentApp.directive('filelistBind', function() {
			return function( scope, elm, attrs ) {
				elm.bind('change', function( evt ) {
				scope.$apply(function() {
					scope[ attrs.name ] = evt.target.files;
					console.log( scope[ attrs.name ] );
				});
				});
			};
		});

		
		agentApp.directive('rickshawChart', function () {
			  return {
			    scope: {
			      data: '=data'
			    },
			    template: '<div></div>',
			    restrict: 'E',
			    link: function postLink(scope, element, attrs) {
			      scope.$watchCollection('[data]', function(newVal, oldVal){
			        if(!newVal[0]){
			          return;
			        }
			
			        element[0].innerHTML ='';
			        
			        var graph = new Rickshaw.Graph({
			          element: element[0],
			          width: scope.data.width,
			          height: scope.data.height,
			          series: [{data: scope.data.series, color: scope.data.color}],
			          renderer: scope.data.renderer,
			          min : scope.data.min,
			          max : scope.data.max
			         
			        });
			
			        // set rickshaw x-axis to use local timezone
			        // Fixtures.Time.Local is derived from Fixtures.Time
			        // time unit values are 
			        // second, minute, hour,day
			        // "15 second", "15 minute", "6 hour" etc.
			        // for more options check 
			        // @see rickshaw github repo /src/js/Rickshaw.Fixtures.Time.Local.js
			        // @see https://github.com/shutterstock/rickshaw/pull/239
			        
			        var time = new Rickshaw.Fixtures.Time.Local(); 
			        
			        var custom_tick = scope.data.tick || 'hour' ;
			        var custom_tu = time.unit(custom_tick); 
			        
			        var xAxis = new Rickshaw.Graph.Axis.Time({ graph: graph, timeUnit: custom_tu }); 
			        // var xAxis = new Rickshaw.Graph.Axis.Time({ graph: graph}); 
			        
			        xAxis.render(); 
			       
			        var yAxis = new Rickshaw.Graph.Axis.Y({graph: graph});
			        yAxis.render();
			        
			        var hoverDetail = new Rickshaw.Graph.HoverDetail( {
			            graph: graph,
			            formatter: function(series, x, y) {
			            
			            	var xdate = new Date(1000*x) ;
			                var date = '<span class="date">' 
			                				+ xdate.getDate() 
			                				+ '-' 
			                				+ (xdate.getMonth() + 1)
			                				+ '-'
			                				+ xdate.getFullYear()
			                				+ ' '
			                				+ xdate.getHours()
			                				+ ':'
			                				+ xdate.getMinutes()
			                				+ ':'
			                				+ xdate.getSeconds()
			                				+ ' '
			                				+ '</span>';
			                var swatch = '<span class="detail_swatch" style="background-color: ' + series.color + '"></span>';
			                var content = swatch  + "value " + y + '<br>' + date;
			                return content;
			            }
			        }); 
			        
			        graph.render();
			      });
			    }
			  };
			});
		 
		
        // All controllers of this module can use the 
        // functions defined  inside run()
        // refactor common functionality of hiding/showing 
        // progress message, message and errors

        agentApp.run(['$rootScope', '$window', '$http' ,function($rootScope,$window,$http) {

            $rootScope.debug = false ;
            $rootScope.showPageMessage = false ;
            $rootScope.showPageError = false ;
            $rootScope.showProgressIcon = false ;
            
            $rootScope.pageMessage = "" ;
            
            $rootScope.setDebug = function(flag) {
                $rootScope.debug = flag ;
            };

            $rootScope.clearPageMessage = function () {
            	
            	$rootScope.showProgressIcon = false ;
				$rootScope.showPageMessage = false ;
		        $rootScope.showPageError = false ;
				$rootScope.pageMessage = "" ;
				return ;
            };
            
            $rootScope.showMessage = function(message) {
            	
            	if(message.isEmpty()) {
            		// duct tape hack for legacy reasons
            		// lot of code calls showMessage("") to 
            		// clear page message. new code should 
            		// call clearPageMessage 
            		$rootScope.clearPageMessage() ;
            		return ;
            	}
            	
				$rootScope.showProgressIcon = false ;
				$rootScope.showPageMessage = true ;
		        $rootScope.showPageError = false ;
				$rootScope.pageMessage = message ;
			};

			$rootScope.showProgress = function(message) {
				$rootScope.showProgressIcon = true ;
				$rootScope.showPageMessage = true ;
		        $rootScope.showPageError = false ;
				$rootScope.pageMessage = message ;
				
			};
			
			
			$rootScope.showError = function(error) {
				$rootScope.showProgressIcon = false ;
				$rootScope.showPageMessage = false ;
		        $rootScope.showPageError = true ;
		        
				error =  error.trim();
				if(error.charAt(error.length-1) != '.' ) {
					error = error + "." ;
				}
				
				$rootScope.pageMessage = error ;
				// scroll to top to show errors
				$window.scrollTo(0,0) ;
				
            };
            
            $rootScope.processResponse = function(response) {
                if($rootScope.debug){
                    console.log("response is:"); console.log(response);
                }

                var status = response.status || 500 ;
                var data = response.data || {} ;

                if (status != 200 || !data || (data.code != 200)) {
					var error = data.error || "request failed" ;
					$rootScope.showError(error) ;
					return;
                } else {
					$rootScope.showMessage(data.token);
                }

            };
            
            // non-zero return code means error 
            // return false if no errors 
	        $rootScope.validateForm = function(errorObject) {

	        	var fields = [] ;
	        	var requiredFields = errorObject['required'];
	        	
	        	if(requiredFields && requiredFields.length > 0) {
	        		for(var i = 0 ; i < requiredFields.length; i++) {
	        			fields.push({"name" : requiredFields[i]['$name'], "message" : "is required"}) ;
	        		}
	        	}
	        	
	        	var maxlenFields = errorObject['maxlength'];
	        	if(maxlenFields && maxlenFields.length > 0) {
	        		for(var i = 0 ; i < maxlenFields.length; i++) {
	        			fields.push({"name" : maxlenFields[i]['$name'], "message" : "exceeds max length"}) ;
	        		}
	        	}
	        	
	        	var minlenFields = errorObject['minlength'];
	        	if(minlenFields && minlenFields.length > 0) {
	        		for(var i = 0 ; i < minlenFields.length; i++) {
	        			fields.push({"name" : minlenFields[i]['$name'], "message" : "violates mininum length requirement"}) ;
	        		}
	        	}
	        	
	        	var emailFields = errorObject['email'];
	        	if(emailFields && emailFields.length > 0) {
	        		for(var i = 0 ; i < emailFields.length; i++) {
	        			fields.push({"name" : emailFields[i]['$name'], "message" : "is not valid email"}) ;
	        		}
	        	}
	        	
	        	
	        	if(fields.length > 0) {
	        		var formError = "" ;
	        		for(var j = 0 ; j < fields.length ; j++) {
	        			formError +=  " " + fields[j].name + " " + fields[j].message + " /" ;
	        		}
	        		
	        		$rootScope.showError(formError);
	        		return true ;
	        	} 
	        	
	        	return false;

	         };
	         

	         $rootScope.unixToHuman = function(unixTime) {
	        	 
	        	 var fd = "" ;
	        	 if(unixTime == 0 || unixTime == "0" ) {
	        		 return "--" ;
	        	 }
	        	 
	        	 if(unixTime) {
	        		 var d = new  Date(unixTime * 1.0);
	        		 months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']
	        		 fd = d.getDate()
	        		 			+ "-" + months[d.getMonth()]
	        		  			+ "-" + d.getFullYear()
	        		  			+ " " + d.getHours() 
	        		  			+ ":" + d.getMinutes() ;
	        		 
	        	 }
	        	 
	        	 return fd ;
	         };
	         
	         $rootScope.unixToHumanDate = function(unixTime) {
	        	 
	        	 var fd = "" ;
	        	 if(unixTime == 0 || unixTime == "0" ) {
	        		 return "--" ;
	        	 }
	        	 
	        	 unixTime = parseInt(unixTime);
	        	 
	        	 if(unixTime) {
	        		 var d = new  Date(unixTime);
	        		 months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']
	        		 fd = d.getDate() + "-" + months[d.getMonth()] + "-" + d.getFullYear() ;
	        	 }
	        	 
	        	 return fd ;
	         };
   
	         $rootScope.unixToHumanTime = function(unixTime) {
	        	 
	        	 var fd = "" ;
	        	 if(unixTime == 0 || unixTime == "0" ) {
	        		 return "--" ;
	        	 }
	        	 
	        	 unixTime = parseInt(unixTime);
	        	 if(unixTime) {
	        		 var d = new  Date(unixTime);
	        		 fd = d.getHours() + ":" + d.getMinutes() ;
	        	 }
	        	 
	        	 return fd ;
	         };
 
	         $rootScope.HumanMonthToNumber = function (m) {
	        	 if( m == 'Jan') return 1 ;
	        	 if( m == 'Feb') return 2 ;
	        	 if( m == 'Mar') return 3 ;
	        	 if( m == 'Apr') return 4 ;
	        	 if( m == 'May') return 5 ;
	        	 if( m == 'Jun') return 6 ;
	        	 if( m == 'Jul') return 7 ;
	        	 if( m == 'Aug') return 8 ;
	        	 if( m == 'Sep') return 9 ;
	        	 if( m == 'Oct') return 10 ;
	        	 if( m == 'Nov') return 11 ;
	        	 if( m == 'Dec') return 12 ;
	        	 
	        	 console.log("error: Unknown month " + m) ;
	        	 return -1 ;
	        	 
	         } ;
	         
	         
	         $rootScope.conditionConverter = function (conditionValue) {
	        	 if(conditionValue) {
	        		 if(conditionValue == "gt") {
	        			 return "Greater than";
	        		 } else if (conditionValue == "lt") {
	        			 return "Less than";
	        		 }else if (conditionValue == "eq") {
	        			 return "equals";
	        		 } else {
	        			 return "";
	        		 }
	        	 }
	        	 return "";
	         }
	               
        }]);

      
        // text service of YuktixApp module returns a promise
        // object. 
        // $http.then(successCallback, errorCallback) 
        // we simply return the response from service
        
        agentApp.factory('text', function($http) {
            var text = {} ;

            text.reverse = function(token) {

                var myurl = '/angular/php/reverse.php?token='+ encodeURI(token) ;
                var promise = $http({
                    method : 'GET',
                    url : myurl,
                    headers : { 'Content-Type' : 'application/json' }
                }).then(
                    function (response) { return response ; }, 
                    function(response) { return response ; }
                );

                return promise;

            };

            return text ;
        });
        

		
        agentApp.factory('calendar', function() {
			 var calendar = {} ;
			 
			 calendar.now = function(debug) {
				 return Math.floor(new Date().getTime() / 1000.0);
			 };
			 
			 calendar.midnightOffset = function(debug) {
				var today = new Date();
				var hh,mm,ss ;
				hh = today.getHours();
				mm = today.getMinutes();
				ss = today.getSeconds();
       		
				return hh*3600 + mm*60 + ss ;
       		
			 } ;
			 
			calendar.timezoneOffset = function(debug) {
				var timeOffsetInSeconds = new Date().getTimezoneOffset() * 60;
				// @see docs : javascript returns offset in MINUS
				timeOffsetInSeconds *= -1 ;
				return timeOffsetInSeconds ;
			 };
			 
			 calendar.getIMDTime = function(day, month, year, debug) {
				// convert day, month, year to unix timestamp in millis
				// javascript month is 0->11
				var d1 = new Date(year, month-1, day,8,30);
				return d1.getTime();
				 
			 } ;
			 
			 calendar.getGraphEndTimestamp = function(day,month,year,debug) {
				 var cdate = new Date(year, month-1, day);
				 
				 var today = new Date();
				 var dd = today.getDate();
				 var mm = today.getMonth();
				 var yyyy = today.getFullYear();
				 var tdate = new Date(yyyy,mm,dd);
				 
				 if(cdate.getTime() >= tdate.getTime()) {
					 // today or future date in box
					 return Math.floor(new Date().getTime() / 1000.0);
				 } else {
					 // past date in box
					 // get date + 23:59:59
					 return Math.floor(new Date(year,month-1,day,23,59,59).getTime() / 1000.0);
				 }
				 
			 }
			 
			 return calendar ;
		 });
		 

        agentApp.factory('agent', function($http) {

            var agent = {} ;

         	 agent.getDevices = function(base,debug) {
				 
	            	var myurl = base + '/test/agent/shim/device/list.php' ;
	            	var postData = {} ;
	            	if(debug) { 
						console.log("POST : " + myurl); 
						console.log(postData);
					}
	            	
	            	var promise = $http({
						method : 'POST',
						url : myurl,
						data : postData,
						headers: {'Content-Type': 'application/json'}
					}).then(
	                    function (response) { return response ; }, 
	                    function(response) { return response ; }
	                );

	            	return promise;
	         };

			 agent.getDevice = function(base,debug, serialNumber) {
				 
	            	var myurl = base + '/test/agent/shim/device/get-object.php' ;
	            	var postData = {"serialNumber" : serialNumber } ;
	            	if(debug) { 
						console.log("POST: data %O to URL %s ",postData, myurl); 
					}
	            	
	            	var promise = $http({
						method : 'POST',
						url : myurl,
						data : postData,
						headers: {'Content-Type': 'application/json'}
					}).then(
	                    function (response) { return response ; }, 
	                    function(response) { return response ; }
	                );

	            	return promise;
	         };

			 agent.updateDevice = function(base,debug, device) {
				 
	            	var myurl = base + '/test/agent/shim/device/update.php' ;
	            	if(debug) { 
						console.log("POST: data %O to URL %s ",device, myurl); 
					}
	            	
	            	var promise = $http({
						method : 'POST',
						url : myurl,
						data : device,
						headers: {'Content-Type': 'application/json'}
					}).then(
	                    function (response) { return response ; }, 
	                    function(response) { return response ; }
	                );

	            	return promise;
	         };
	       
            return agent ;

        });
    
        
        function createHttpRequest($http,url,data) {
      		 var promise = $http({
      				method : 'POST',
      				url : url,
      				data : data,
      				headers: {'Content-Type': 'application/json'}
      			}).then(handleSuccess,handleError);
                return promise;
      	 }
        
        function handleSuccess( response ) {
  			 return( response );
        }
  	 
        function handleError( response ) {
        	return( response );
        }
        
       
