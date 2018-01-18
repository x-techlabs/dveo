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

var fs = require('fs');

if (process.argv.length < 3) {
    console.log("Usage: node submit_job.js <job_json_file>")
    return;
}

// construct an API access object using your Hybrik account info
var hybrik_api = new HybrikAPI(
    api_config.HYBRIK_URL, 
    api_config.HYBRIK_COMPLIANCE_DATE, 
    api_config.HYBRIK_OAPI_KEY, 
    api_config.HYBRIK_OAPI_SECRET, 
    api_config.HYBRIK_AUTH_KEY, 
    api_config.HYBRIK_AUTH_SECRET
);

// read the JSON job file and parse into object
var job_json_file = process.argv[2];
var job = JSON.parse(fs.readFileSync(job_json_file, 'utf8'));

// submit the job
submitJob(job);

// function to submit a job through the Hybrik API
function submitJob(job) {
    // connect to the API
    return hybrik_api.connect()
    .then(function () {
        // submit the job by POSTing the '/jobs' command
        return hybrik_api.call_api('POST', '/jobs', null, job)
        .then(function (response) {
            console.log('Job ID: ' + response.id);
            return response.id;
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

