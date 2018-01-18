//=============================================
// (c) 2016 Hybrik, Inc.
// All rights reserved
// Public API example
//=============================================

//The Hybrik Connector code for submitting POST/GET/DELETE commands to Hybrik
var HybrikAPI = require('./hybrik_connector.js');

var fs = require('fs');


// The Hybrik API entry point and compliance date
var HYBRIK_URL = 'https://api_demo.hybrik.com/v1';
var HYBRIK_COMPLIANCE_DATE = '20160517';

// Your Hybrik OAPI Credentials
var HYBRIK_OAPI_KEY = 'qT2ZLYQOonl1POBrVeE@accnt.oapi.hybrik';
var HYBRIK_OAPI_SECRET = 'efBlNYE5N9WEJdCTYlMcRbLq15m1Yg';


// Your Hybrik Authorization Credentials
var HYBRIK_AUTH_KEY = 'dm.kashin@gmail.com';
var HYBRIK_AUTH_SECRET = 'admin360';


// construct an API access object using your Hybrik account info
var hybrik_api = new HybrikAPI(HYBRIK_URL, HYBRIK_COMPLIANCE_DATE, HYBRIK_OAPI_KEY, HYBRIK_OAPI_SECRET, HYBRIK_AUTH_KEY, HYBRIK_AUTH_SECRET);

// read the JSON job file and parse into object
var jsonName = 'sample_job.json';

var jobPayload = JSON.parse(fs.readFileSync(jsonName, 'utf8'));

// submit the job
submitJob(jobPayload);


// function to submit a job through the Hybrik API
function submitJob(theJob) {
	alert("submit job");
    // connect to the API
    hybrik_api.connect()
        .then(function () {
            // submit the job by POSTing the '/jobs' command
            return hybrik_api.call_api('POST', '/jobs', null, theJob)
                .then(function (response) {
                    console.log('Job ID: ' + response.id);
                    return response.id;
                })
                .catch(function (err) {
                    console.log(err);
                });
        })
}

