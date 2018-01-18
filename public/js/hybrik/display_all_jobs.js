//=============================================
// (c) 2016 Hybrik, Inc.
// All rights reserved
// Public API example
//=============================================

//The Hybrik Connector code for submitting POST/GET/DELETE commands to Hybrik
var HybrikAPI = require('/js/hybrik/hybrik_connector.js');

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


// display the jobs
displayJobList();


function displayJobList() {
    // connect to the Hybrik API
    hybrik_api.connect()
        .then(function () {
            // make a GET call with '/jobs/info' to get a list of all jobs
            return hybrik_api.call_api('GET', '/jobs/info', { fields: [ 'id', 'name','progress', 'status','start_time','end_time'], sort_field: 'id', order: 'desc' })
                .then(function (response) {
                    // the response is an array of job objects
                    var numberOfJobs = response.items.length;
                    console.log('Number of Jobs: ' + numberOfJobs);
                    for (var i = 0; i< numberOfJobs; i++) {
                        console.log('ID: ' + response.items[i].id + '  Name: '+ response.items[i].name + '  Progress: ' + response.items[i].progress + '  Status: ' + response.items[i].status + '  Start: ' + response.items[i].start_time + '  End: ' + response.items[i].end_time);
                    }

                    return true;
                })
                .catch(function (err) {
                    // any error - let it be in the request/netweork etc. or as a result of the Hybrik API operation, goes here.
                    console.log(err);
                });
        })
}

