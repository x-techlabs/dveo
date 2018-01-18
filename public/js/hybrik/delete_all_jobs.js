//=============================================
// (c) 2016 Hybrik, Inc.
// All rights reserved
// Public API example
//=============================================


var fs = require('fs');

//The Hybrik Connector code for submitting POST/GET/DELETE commands to Hybrik
var HybrikAPI = require('./hybrik_connector.js');

// The Hybrik API entry point and compliance date
var HYBRIK_URL = 'https://api_demo.hybrik.com/v1';
var HYBRIK_COMPLIANCE_DATE = '20160517';

// Your Hybrik OAPI Credentials
var HYBRIK_OAPI_KEY = 'oapi key goes here';
var HYBRIK_OAPI_SECRET = 'oapi secret goes here';

// Your Hybrik Authorization Credentials
var HYBRIK_AUTH_KEY = 'your user name';
var HYBRIK_AUTH_SECRET = 'your password';

// construct an API access object using your Hybrik account info
var hybrik_api = new HybrikAPI(HYBRIK_URL, HYBRIK_COMPLIANCE_DATE, HYBRIK_OAPI_KEY, HYBRIK_OAPI_SECRET, HYBRIK_AUTH_KEY, HYBRIK_AUTH_SECRET);

deleteAllJobs();

// And here is an example that only deletes the completed jobs

//deleteCompletedJobs();

function deleteAllJobs() {
    //connect to the Hybrik API
    hybrik_api.connect()
        .then(function () {
            //get a list of all the jobs -- this returns a max of 1000 jobs, so if the result == 1000, call the job recursively
            return hybrik_api.call_api('GET', '/jobs/info', { fields: ['id']})
                .then(function (response) {
                    numberOfJobs = response.items.length;
                    if (numberOfJobs == 0) {
                        console.log('No jobs in system');
                        return true;
                    }
					
                    var jobIDs = [];
                    for (var i = 0; i < numberOfJobs; i++) {
                        jobIDs[i] = response.items[i].id;
                    }
                    console.log('Deleting ' + numberOfJobs +' jobs...');
                    //delete the jobs
                    return hybrik_api.call_api('DELETE', '/jobs', null, {ids: jobIDs})
                        .then(function (response) {
                            numberOfJobs = response.items.length;
                            console.log('Number of jobs successfully deleted: ' + numberOfJobs);
                            if (numberOfJobs == 1000) {
                                //since the '/jobs/info' has a max return of 1000 jobs, call deleteAllJobs() again
                                deleteAllJobs();
                            }

                            return true;
                        })
                        .catch(function (err) {
                            // any error - let it be in the request/netweork etc. or as a result of the Hybrik API operation, goes here.
                            console.log(err);
                        });

                    return true;
                })
                .catch(function (err) {
                    // any error - let it be in the request/netweork etc. or as a result of the Hybrik API operation, goes here.
                    console.log(err);
                });
        });
}

// same function as above, but only deletes the jobs marked as 'completed'

function deleteCompletedJobs() {
    hybrik_api.connect()
        .then(function () {
            return hybrik_api.call_api('GET', '/jobs/info', { fields: ['id'], filters: [ { field: 'status', values: ['completed'] } ], order: 'asc'})
                .then(function (response) {
                    numberOfJobs = response.items.length;
                    if (numberOfJobs == 0) {
                        console.log('No jobs in system');
                        return true;
                    }
					
                    var jobIDs = [];
                    for (var i = 0; i < numberOfJobs; i++) {
                        jobIDs[i] = response.items[i].id;
                    }
                    console.log('Deleting ' + numberOfJobs +' completed jobs...');
                    return hybrik_api.call_api('DELETE', '/jobs', null, {ids: jobIDs})
                        .then(function (response) {
                            numberOfJobs = response.items.length;
                            console.log('Number of jobs deleted: ' + numberOfJobs);
                            if (numberOfJobs == 1000) {
                                deleteCompletedJobs();
                            }

                            return true;
                        })
                        .catch(function (err) {
                            // any error - let it be in the request/network etc. or as a result of the Hybrik API operation, goes here.
                            console.log(err);
                        });

                    return true;
                })
                .catch(function (err) {
                    // any error - let it be in the request/network etc. or as a result of the Hybrik API operation, goes here.
                    console.log(err);
                });
        });
}

