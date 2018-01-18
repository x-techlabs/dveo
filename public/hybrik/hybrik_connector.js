//=============================================
// (c) 2016 Hybrik, Inc.
// All rights reserved
// Public API example
//=============================================

var requestp = require('request-promise');
var Promise = require('bluebird');

function HybrikAPI(api_url, compliance_date, oapi_key, oapi_secret, user_key, user_secret) {

    if (!api_url || api_url.indexOf('http') != 0 || api_url.indexOf(':') < 0 || api_url.indexOf('//') < 0)
        throw new Error('HybrikAPI requires a valid API url');

    if (!user_key)
        throw new Error('HybrikAPI requires a user_key');
    if (!user_secret)
        throw new Error('HybrikAPI requires a user_secret');
    if (!compliance_date || !/^\d{8}$/.test(compliance_date))
        throw new Error('HybrikAPI requires a compliance date in "YYYYMMDD" format.');

    this.user_key = user_key;
    this.user_secret = user_secret;
    this.compliance_date = compliance_date;

    var url_parts = api_url.split('//');
    if (url_parts.length != 2)
        throw new Error('HybrikAPI requires a valid API url');

    this.oapi_url = url_parts[0] + '//' + oapi_key + ':' + oapi_secret + '@' + url_parts[1];

    if (this.oapi_url[this.oapi_url.length - 2] == '/')
        this.oapi_url = this.oapi_url.substring(0, this.oapi_url.length - 1);
}

HybrikAPI.prototype.connect = function () {
    var self = this;

    return requestp({
        uri : self.oapi_url + '/login',
        method : 'POST',
        qs: {
            auth_key: self.user_key,
            auth_secret: self.user_secret
        },
        headers: {
            'X-Hybrik-Compliance': self.compliance_date
        }

    })
    .then(function (response) {
        self.login_data = JSON.parse(response);
        return Promise.resolve(true);
    })
}

HybrikAPI.prototype.call_api = function (http_method, api_method, url_params, body_params) {
    var self = this;

    var request_options = {
        uri : self.oapi_url + (api_method[0] === '/' ? api_method : api_method.substring(1)),
        method : http_method,
        headers: {
            'X-Hybrik-Sapiauth': self.login_data.token,
            'X-Hybrik-Compliance': self.compliance_date
        }
    };

    if (url_params) {
        request_options.qs = url_params;
    }
    if (body_params) {
        request_options.headers['Content-Type'] = 'application/json';
        request_options.body = JSON.stringify(body_params);
    }

    return requestp(request_options)
        .then(function (response) {
            var resp_obj = JSON.parse(response)
            return Promise.resolve(resp_obj);
        })
}

module.exports = HybrikAPI;