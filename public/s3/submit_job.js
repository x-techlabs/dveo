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

module.exports.submitFile = function(filename) {

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
    var job = {
        "name": "Hybrik SNS Subscription Test",
        "subscription_key": "test",
        "payload": {
            "elements": [
                {
                    "uid": "source_file",
                    "kind": "source",
                    "payload": {
                        "kind": "asset_url",
                        "payload": {
                            "storage_provider": "s3",
                            "url": "s3://dveo/" + filename
                        }
                    }
                },
                {
                    "uid": "transcode_task",
                    "kind": "transcode",
                    "task": {
                        "retry_method": "fail"
                    },
                    "payload": {
                        "location": {
                            "storage_provider": "s3",
                            "path": "s3://dveo/" + filename
                        },
                        "targets": [
                            {
                                "file_pattern": "{source_basename}.mp4",
                                "existing_files": "replace",
                                "container": {
                                    "kind": "mp4"
                                },
                                "video": {
                                    "codec": "h264",
                                    "width": 640,
                                    "height": 360,
                                    "frame_rate": 23.976,
                                    "bitrate_kb": 600
                                },
                                "audio": [
                                    {
                                        "codec": "heaac_v2",
                                        "channels": 2,
                                        "sample_rate": 44100,
                                        "bitrate_kb": 128
                                    }
                                ]
                            }
                        ]
                    }
                }
            ],
            "connections": [
                {
                    "from": [
                        {
                            "element": "source_file"
                        }
                    ],
                    "to": {
                        "success": [
                            {
                                "element": "transcode_task"
                            }
                        ]
                    }
                }
            ]
        }
    }
    // submit the job
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
