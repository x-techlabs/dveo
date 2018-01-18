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

deleteAllJobs();

// and here is an example that only deletes the completed jobs:
// deleteCompletedJobs();

function delete_job_chunk(filters) {
    //get a list of the jobs -- this returns a max of 1000 jobs, so as long as jobs are returned, call the delete recursively
    var query_args = { 
        fields: ['id'],
        order: 'asc'
    };
    if (filters) query_args.filters = filters;
    return hybrik_api.call_api('GET', '/jobs/info', query_args).then(function (response) {
        numberOfJobs = response.items.length;
        if (numberOfJobs == 0) {
            console.log('No more jobs available to delete');
            return true;
        }
        
        var jobIDs = [];
        for (var i = 0; i < numberOfJobs; i++) {
            jobIDs[i] = response.items[i].id;
        }
        console.log('Deleting ' + numberOfJobs +' jobs...');
        //delete the jobs
        return hybrik_api.call_api('DELETE', '/jobs', null, {ids: jobIDs}).then(function (response) {
            numberOfJobs = response.items.length;
            console.log('Number of jobs successfully deleted: ' + numberOfJobs);
            //call delete_job_chunks() again
            return delete_job_chunk(filters);
        }).catch(function (err) {
            // any error - let it be in the request/network etc. or as a result of the Hybrik API operation, goes here.
            shared.print_error(err);
        });
        
    }).catch(function (err) {
        // any error - let it be in the request/network etc. or as a result of the Hybrik API operation, goes here.
        shared.print_error(err);
    });
}

function deleteAllJobs() {
    //connect to the Hybrik API
    return hybrik_api.connect().then(function () {
        return delete_job_chunk();
    })
    .catch(function (err) {
        // any error - let it be in the request/network etc. or as a result of the Hybrik API operation, goes here.
        shared.print_error(err);
    });
}

// same function as above, but only deletes the jobs marked as 'completed'
function deleteCompletedJobs() {
    return hybrik_api.connect().then(function () {
        return delete_job_chunk([ 
            { 
                field: 'status', 
                values: ['completed'] 
            } 
        ]);
    })
    .catch(function (err) {
        // any error - let it be in the request/network etc. or as a result of the Hybrik API operation, goes here.
        shared.print_error(err);
    });
}