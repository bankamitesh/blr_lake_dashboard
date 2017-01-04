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
		var yuktixApp = angular.module('YuktixApp', []);

		// ng-enter directive 
		// copied from http://eric.sau.pe/angularjs-detect-enter-key-ngenter
		yuktixApp.directive('ngEnter', function () {
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
		
		yuktixApp.directive('filelistBind', function() {
			return function( scope, elm, attrs ) {
				elm.bind('change', function( evt ) {
				scope.$apply(function() {
					scope[ attrs.name ] = evt.target.files;
					console.log( scope[ attrs.name ] );
				});
				});
			};
		});

		yuktixApp.directive('gmap', function ($window) {
            return {
              scope: {
                  data: '=data'    
              },
              template: '<div></div>',
              restrict: 'E',
              link: function postLink(scope, element, attrs) {
               
            	  scope.$watch('data', function() {
                	  // data undefined?
            		  if(!scope.data)
            			  return ;
            		  
                	  var map;
                	  
                	  var el = document.createElement("div");
                      el.style.width = "100%";
                      el.style.height = "100%";
                      element.prepend(el);
                      
                      scope.markerClick = function (marker) {
                          return function () {
                            scope.$apply(function () {
                              $window.location.href= marker.click_url ;
                            });
                          };
                      } ;
                      
                      
                	  var mapOptions = {
                    	      zoom: 12,
                	          center: new google.maps.LatLng(scope.data.center[0],scope.data.center[1])
                	  };
                	  
                	  map = new google.maps.Map(el,mapOptions);
                	 
                	  if(!scope.data.points) {
                		  return ;
            	      }
                	  
                	  // we have points
                	  var bounds = new google.maps.LatLngBounds() ;
                	  var points = scope.data.points ;
                	  
                	  // add markers
                      for (var i=0; i < points.length; i++) {
                    	  
                    	  var point = points[i] ;  
                    	  var myLatlng = new google.maps.LatLng(point.lat,point.lon);
                          var marker = new google.maps.Marker({
                        	  title : point.name,
                              position: myLatlng,
                              map: map,
                              label : point.label,
                              icon : point.icon
                          });
                    	  
                          // extra properties for housekeeping
                          marker.serialNumber = point.serialNumber;
                          marker.click_url = point.click_url ;
                          
                          google.maps.event.addListener(marker, 'click', scope.markerClick(marker));
                          bounds.extend(marker.getPosition());
                          
                          if (point.hasOwnProperty("label")) {
                        	  // add labels to marker
	                          var label = new Label({ map: map });
	                          label.bindTo('position', marker);
	                          label.bindTo('text', marker, 'label');
	                            
                          }
                          
                          // add info-window?
                          
                      }
                      
                      /* 
                       * fitBounds only works for multiple points.
                       * single point case has to be dealt with separately!
                       * 
                       * @see http://stackoverflow.com/questions/2437683/google-maps-api-v3-can-i-setzoom-after-fitbounds
                       * 
                       */
                      if(points.length > 1) {
                    	  map.fitBounds(bounds);
                    	  
                      } else {
                    	  map.setCenter(bounds.getCenter());
                    	  map.setZoom(10);
                      }

                  });
                  
              }
            };
          });
		 
		 yuktixApp.directive('rickshawChart', function () {
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

        yuktixApp.run(['$rootScope', '$window', '$http' ,function($rootScope,$window,$http) {

            $rootScope.debug = false ;
            $rootScope.showPageMessage = false ;
            $rootScope.showPageError = false ;
            $rootScope.showProgressIcon = false ;
            $rootScope.pageMessage = "" ;
            $rootScope.fileIds = [] ;

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

			$rootScope.showToastMessage = function (message) {
				// 
				var snackbarContainer = document.querySelector('#toast-message-container');
                var data = { "message" : message } ;
                snackbarContainer.MaterialSnackbar.showSnackbar(data);

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
	         
	       

        }]);

        // text service of YuktixApp module returns a promise
        // object. 
        // $http.then(successCallback, errorCallback) 
        // we simply return the response from service
        
        yuktixApp.factory('text', function($http) {
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
        

		yuktixApp.factory('fupload', function($http) {

            var fupload = {} ;

			fupload.send_blob = function(debug,myurl,blob) {

				if(debug) { 
					console.log("file upload URL is:" + myurl);
				}

                var promise = $http({
                    method : 'POST',
                    url : myurl,
                    headers : { 'Content-Type' : 'application/octet-stream' },
					data: new Uint8Array(blob),
					transformRequest:  angular.identity
                }).then(
                    function (response) { return response ; }, 
                    function(response) { return response ; }
                );

                return promise;

            };

			fupload.send_mpart = function(debug,myurl,payload) {
				// it is necessary to keep Content-Type: undefined 
				// to let browser fill in the Content-Type 
				// also we tell angularjs not to change any data/headers!

				if(debug) { 
					console.log("file upload URL is:" + myurl);
					console.log("file payload is %O", payload);
				}

                var promise = $http({
                    method : 'POST',
                    url : myurl,
                    headers : { 'Content-Type': undefined},
					data: payload,
					transformRequest:  angular.identity
                }).then(
                    function (response) { return response ; }, 
                    function(response) { return response ; }
                );

                return promise;

            };

            return fupload ;

        });

        
        yuktixApp.factory('user', function($http) {

            var user = {} ;

         	user.login = function(base,debug,loginObj) {
            	
            	var myurl = base + '/admin/shim/login.php' ;
            	  
            	if(debug) {
					console.log("POST " + myurl); 
					console.log(loginObj);
				}
            	
            	var promise = $http({
					method : 'POST',
					url : myurl,
					data : loginObj,
					headers: {'Content-Type': 'application/json'}
				
				}).then(
                    function (response) { return response ; }, 
                    function(response) { return response ; }
                );

                return promise;
            	
            };
            
            return user ;
        });


        yuktixApp.factory('lake', function($http) {

            var lake = {} ;

         	 lake.list = function(base,debug) {
				 
	            	var myurl = base + '/admin/shim/lake/list.php' ;
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


	         lake.create = function(base,debug,lakeObj) {
            	
            	var myurl = base + '/admin/shim/lake/create.php' ;
            	  
            	if(debug) {
					console.log("POST " + myurl); 
					console.log(lakeObj);
				}
            	
            	var promise = $http({
					method : 'POST',
					url : myurl,
					data : lakeObj,
					headers: {'Content-Type': 'application/json'}
				
				}).then(
                    function (response) { return response ; }, 
                    function(response) { return response ; }
                );

                return promise;
            	
            };

			lake.update = function(base,debug,lakeObj) {
            	
            	var myurl = base + '/admin/shim/lake/update.php' ;
            	  
            	if(debug) {
					console.log("POST " + myurl); 
					console.log(lakeObj);
				}
            	
            	var promise = $http({
					method : 'POST',
					url : myurl,
					data : lakeObj,
					headers: {'Content-Type': 'application/json'}
				
				}).then(
                    function (response) { return response ; }, 
                    function(response) { return response ; }
                );

                return promise;
            	
            };

            lake.getCodes = function(base,debug) {

                 var myurl = base + '/admin/shim/codes.php' ;
                 var postData = {} ;

                 if(debug) {
                     console.log("POST " + myurl);
                     console.log(myurl);
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

			 
			lake.findObjectOnCode = function(arr, code, debug) {

				var index = -1 ;
				for (var i = 0 ; i < arr.length; i++) {
					if(debug) {
						console.log("lookup object on code: comparing %O with %d",arr[i], code);
					}

					if(arr[i].id == code) {
						return  i ;
					}
            	}

				return index ;
            }; 

			lake.assignFeatureCodeValues = function(codeMap, featureObj, debug) {

				var code ;
				var index = -1 ;
				
				code = featureObj.featureTypeCode   ;
				for(i = 0 ; i < codeMap.featureTypes.length; i++) {
					if(codeMap.featureTypes[i].id == code ) {
						featureObj.featureTypeValue = codeMap.featureTypes[i].value ;
					}
				}

				code = featureObj.monitoringCode ;
				for(i = 0 ; i < codeMap.featureMonitorings.length; i++) {
					if(codeMap.featureMonitorings[i].id == code ) {
						featureObj.monitoringValue = codeMap.featureMonitorings[i].value ;
					}
				}

				code = featureObj.iocode ;
				for(i = 0 ; i < codeMap.featureIOCodes.length; i++) {
					if(codeMap.featureIOCodes[i].id == code ) {
						featureObj.iocodeValue = codeMap.featureIOCodes[i].value ;
					}
				}
				
            };

			lake.getLakeObject = function(base,debug, lakeId) {

                 var myurl = base + '/admin/shim/lake/get-object.php' ;
                 var postData = {"lakeId" : lakeId} ;

                 if(debug) {
                     console.log("POST " + myurl);
                     console.log(myurl);
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

			lake.getRelationshipFile = function(base,debug, lakeId, fileCode) {

                 var myurl = base + '/admin/shim/lake/file/get-relationship.php' ;
                 var postData = {
					 "lakeId" : lakeId, 
					 "fileCode" : fileCode
				 } ;

                 if(debug) {
                     console.log("POST:%s, data=%O ", myurl, postData);
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

			lake.storeRelationshipFile = function(base,debug, lakeId, fileCode,fileId) {
				 var myurl = base + '/admin/shim/lake/file/store-relationship.php' ;
                 var postData = {
					 "lakeId" : lakeId, 
					 "fileCode" : fileCode,
					 "fileId" : fileId
				 } ;

                 if(debug) {
                     console.log("POST:%s, data=%O ", myurl, postData);
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

			lake.getImages = function(base,debug, lakeId) {

                 var myurl = base + '/admin/shim/lake/file/get-images.php' ;
                 var postData = { "lakeId" : lakeId } 
				 
                 if(debug) {
                     console.log("POST:%s, data=%O ", myurl, postData);
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

			lake.storeImages = function(base,debug, lakeId, imageIds) {

                 var myurl = base + '/admin/shim/lake/file/store-images.php' ;
                 var postData = {
					 "lakeId" : lakeId, 
					 "imageFileIds" : imageIds
				 } ;

                 if(debug) {
                     console.log("POST:%s, data=%O ", myurl, postData);
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

			lake.setWallpaper = function(base,debug, lakeId, imageFileId) {

                 var myurl = base + '/admin/shim/lake/file/set-wallpaper.php' ;
                 var postData = {
					 "lakeId" : lakeId, 
					 "imageFileId" : imageFileId
				 } ;

                 if(debug) {
                     console.log("POST:%s, data=%O ", myurl, postData);
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

			lake.createZone = function(base,debug,lakeId, zoneObj) {
            	
            	var myurl = base + '/admin/shim/lake/create-zone.php' ;
            	var postData = {
					"lakeId" : lakeId,
					"zoneObj" : zoneObj 
				};

            	if(debug) {
					console.log("POST :%s, data: %O",  myurl, postData); 
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

			lake.getZones = function(base,debug,lakeId) {
            	
            	var myurl = base + '/admin/shim/lake/get-zones.php' ;
				var postData = {"lakeId" : lakeId} 

            	if(debug) {
					console.log("POST :%s, data: %O",  myurl, postData); 
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

            return lake ;

        });

		yuktixApp.factory('feature', function($http) {

            var feature1 = {} ;

			feature1.list = function(base,debug, lakeId) {

				var myurl = base + '/admin/shim/lake/feature/list.php' ;
				var postData = {"lakeId" : lakeId} ;

				if(debug) {
					console.log("POST: %s, data: %O ", myurl, postData) ;
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

			feature1.create = function(base,debug,featureObj) {

				var myurl = "/admin/shim/lake/feature/create.php";
				if(debug) { 
					console.log("POST: %s, data : %O", myurl, featureObj);
				}

                var promise = $http({
                    method : 'POST',
                    url : myurl,
                    headers : { 'Content-Type' : 'application/json' },
					data: featureObj
					
                }).then(
                    function (response) { return response ; }, 
                    function(response) { return response ; }
                );

                return promise;

            };

			feature1.update = function(base,debug,featureObj, fileUploadData) {

				var myurl = "/admin/shim/lake/feature/update.php";
				if(debug) { 
					console.log("POST: %s, data : %O", myurl, featureObj);
				}

                var promise = $http({
                    method : 'POST',
                    url : myurl,
                    headers : { 'Content-Type' : 'application/json' },
					data: { 
						"featureObj" : featureObj, 
						"fileUploadData" : fileUploadData 
					} 
					
                }).then(
                    function (response) { return response ; }, 
                    function(response) { return response ; }
                );

                return promise;

            };

			feature1.confirmUpload = function(base,debug, fileIds) {

				var myurl = "/admin/shim/lake/feature/confirm-upload.php";
				if(debug) { 
					console.log("POST: %s, fileIds:%O", myurl, fileIds);
				}

                var promise = $http({
                    method : 'POST',
                    url : myurl,
                    headers : { 'Content-Type' : 'application/json' },
					data: { "fileIds" : fileIds } 
					
                }).then(
                    function (response) { return response ; }, 
                    function(response) { return response ; }
                );

                return promise;

            };

			feature1.uploadData = function(base,debug,lakeId,featureObj, fileIds) {

				var myurl = "/admin/shim/lake/feature/upload-data.php";
				if(debug) { 
					console.log("POST: %s, lakeId:%d, feature: %O, fileIds:%O", myurl, featureObj, fileIds);
				}

                var promise = $http({
                    method : 'POST',
                    url : myurl,
                    headers : { 'Content-Type' : 'application/json' },
					data: { 
						"lakeId" : lakeId,
						"featureObj" : featureObj, 
						"fileIds" : fileIds 
					} 
					
                }).then(
                    function (response) { return response ; }, 
                    function(response) { return response ; }
                );

                return promise;

            };

			feature1.getFeatureObject = function(base,debug,featureId) {

				var myurl = "/admin/shim/lake/feature/get-object.php";
				var postData = {
					"id" :featureId
				};

				if(debug) { 
					console.log("POST: %s, data : %O", myurl, postData);
				}

                var promise = $http({
                    method : 'POST',
                    url : myurl,
                    headers : { 'Content-Type' : 'application/json' },
					data: postData
					
                }).then(
                    function (response) { return response ; }, 
                    function(response) { return response ; }
                );

                return promise;

            };

            return feature1 ;

        });
	
        // controller using text service for reverse func
        // inside the controller we use the promise from service
        // service().then(successCallback, errorCallback)
         
        yuktixApp.controller('text.reverse', function($scope,text) {

            var token = "boohooohoo";
            $scope.setDebug(gDebugFlag);

            $scope.showProgress("reversing the text");

            text.reverse(token).then(function(response) {
                    $scope.processResponse(response) ;
                }, function(response) {
                    $scope.processResponse(response);
                
            });

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
        
       
