//=============================================
// (c) 2016 Hybrik, Inc.
// All rights reserved
// Public API example
//=============================================

// import api config. Please edit api_config.js first before running this example.
var api_config = require('./api_config.js');

// the Hybrik Connector lib for submitting POST/GET/DELETE/PUT commands to Hybrik
var HybrikAPI = require('./hybrik_connector.js');

// import shared helpers
var shared = require('./shared.js');

// construct an API access object using your Hybrik account info
var hybrik_api = new HybrikAPI(
    api_config.HYBRIK_URL, 
    api_config.HYBRIK_COMPLIANCE_DATE, 
    api_config.HYBRIK_OAPI_KEY, 
    api_config.HYBRIK_OAPI_SECRET, 
    api_config.HYBRIK_AUTH_KEY, 
    api_config.HYBRIK_AUTH_SECRET
);

// display the jobs
displayJobList();

function displayJobList() {
    // connect to the Hybrik API
    return hybrik_api.connect()
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
            // any error - let it be in the request/network etc. or as a result of the Hybrik API operation, goes here.
            shared.print_error(err);
        });
    })
    .catch(function (err) {
        // any error - let it be in the request/network etc. or as a result of the Hybrik API operation, goes here.
        shared.print_error(err);
    });
}

