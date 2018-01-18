//=============================================
// (c) 2016 Hybrik, Inc.
// All rights reserved
// Public API example
//=============================================

function print_error(err) {
    if(err.message)
        console.log(err.message);
    else
        console.log(err);
}

module.exports.print_error = print_error;