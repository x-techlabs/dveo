import Immutable from 'immutable';
import _ from 'lodash';

import serializeParams from './serializeParams';

const API_LINK = '/api/';

class Api {

    constructor() {
        this.url = '';
        this.model = new Immutable.Record();
        this.localStorageKey = '';

        this.get = ::this.get;
        this.save = ::this.save;
        this.getList = ::this.getList;
        this.getListFromLocalStorage = ::this.getListFromLocalStorage;
        this.getFetchParams = ::this.getFetchParams;
        this.getHeaders = ::this.getHeaders;
        this.getUrlBase = ::this.getUrlBase;
    }

    getUrlBase() {
        return API_LINK + this.url;
    }

    getHeaders() {
        const headers = {
        };

        return headers;
    }

    getFetchParams() {
        return {
            headers: this.getHeaders(),
            mode: 'cors',
        };
    }

    getList(url, params) {
        return new Promise((resolve, reject) => {
            let link = this.getUrlBase() + url;

            if (params) {
                const query = serializeParams(params);
                link = `${link}?${query}`;
            }

            fetch(link, this.getFetchParams()).then((response) => {
                if (!response.ok) {
                    reject(response);
                    return;
                }

                const linkHeader = response.headers.get('Link');
                let links = null;

                if (linkHeader != null) {
                    links = parseLinkHeader(linkHeader);
                }

                this.meta = {
                    links,
                    page_current: response.headers.get('pagination-current'),
                    page_total: response.headers.get('pagination-total-Pages'),
                    items_total: response.headers.get('Pagination-Total-Items'),
                };

                response.json().then((jsonData) => {
                    const data = [];

                    if (this.localStorageKey !== '') {
                        localStorage.setItem(this.localStorageKey, JSON.stringify(jsonData));
                    }
                    jsonData.forEach((item) => data.push(new Immutable.Map(item)));

                    resolve({
                        data: new Immutable.List(data),
                        meta: this.meta,
                    });
                });
            }).catch((response) => {
                reject(response);
            });
        });
    }

    getListFromLocalStorage(key) {
        const localStorageKey = key || this.localStorageKey;
        const json = localStorage.getItem(localStorageKey);
        const jsonData = JSON.parse(json);

        if (!_.isArray(jsonData)) {
            return null;
        }

        const data = [];
        jsonData.forEach((item) => data.push(new Immutable.Map(item)));
        return data;
    }

    setListToLocalStorage(key, data) {
        const localStorageKey = key || this.localStorageKey;
        const rawData = data.toJS();

        if (!_.isArray(rawData)) {
            return false;
        }

        const jsonData = JSON.stringify(rawData);
        localStorage.setItem(localStorageKey, jsonData);
        return true;
    }

    get(url_, id, params = null) {
        return new Promise((resolve, reject) => {
            let url = this.getUrlBase() + url_;
            if (id !== '') {
                url += `/${id}`;
            }

            if (params) {
                const query = serializeParams(params);
                url = `${url}?${query}`;
            }

            fetch(url, this.getFetchParams()).then((response) => {
                if (!response.ok) {
                    reject(response);
                    return;
                }

                response.json().then((jsonData) => {
                    resolve({
                        data: new Immutable.Map(jsonData),
                        meta: {},
                    });
                });
            }).catch((response) => reject(response));
        });
    }

    save(modelUrl, model) {
        return new Promise((resolve, reject) => {
            let url = `${this.getUrlBase()}${modelUrl}`;

            const params = this.getFetchParams();

            params.method = 'post';
            params.headers['Content-type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
            params.body = serializeParams(model);


            const id = model.id || '';
            if (id !== '') {
                url += `/${id}`;
                params.method = 'put';
            }

            fetch(url, params).then((response) => {
                response.json().then((jsonData) => {
                    const statusCode = response.status;

                    if (statusCode === 200 || statusCode === 201) {
                        resolve({
                            data: jsonData,
                            meta: {},
                        });
                    } else {
                        reject(jsonData);
                    }
                });
            }, (response) => {
                reject(response);
            });
        });
    }

    call(url__, method, data = {}) {
        return new Promise((resolve, reject) => {
            let url = `${this.getUrlBase()}${url__}`;

            const params = this.getFetchParams();
            params.method = method;
            params.headers['Content-type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
            params.body = serializeParams(data);

            fetch(url, params).then((response) => {
                response.json().then((jsonData) => {
                    const statusCode = response.status;

                    if (statusCode === 200 || statusCode === 201) {
                        resolve({
                            data: jsonData,
                            meta: {},
                        });
                    } else {
                        reject(jsonData);
                    }
                });
            }, (response) => {
                reject(response);
            });
        });
    }
}

export default Api;
