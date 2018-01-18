'use strict';

var vodApp = angular.module('vodApp', ['ngAnimate','ngTouch','ngRoute','ngResource']);

vodApp.config(['$routeProvider', function($routeProvider) {
		$routeProvider.when('/login',
            {
                templateUrl: 'templates/login.html',
                controller: 'loginController'
            });
        $routeProvider.when('/register',
            {
                templateUrl: 'templates/register.html',
                controller:  'registerController'

            });
        $routeProvider.when('/dashboard',
            {
                templateUrl: 'templates/dashboard.html',
                controller:  'dashboardController'
            });
        $routeProvider.when('/amazons3',
            {
                templateUrl:  'templates/amazon.html',
                controller:   'amazonController'
            });
        $routeProvider.when('/transcode',
            {
                templateUrl:  'templates/transcode.html',
                controller:   'transcodeController'
            });
        $routeProvider.when('/upload',
            {
                templateUrl:  'templates/upload.html',
                controller:   'uploadController'
            });
        $routeProvider.when('/selftest',
            {
                templateUrl:  'templates/selftest.html',
                controller:   'selftestController'
            });
        $routeProvider.otherwise({redirectTo: '/login'});
        }]);


