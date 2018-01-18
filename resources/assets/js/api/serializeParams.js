import _ from 'lodash';

function serializeParams(input, prefix = null) {
    const pairs = [];

    if (_.isString(input)) {
        return `${prefix}=${encodeURIComponent(input)}`;
    }

    _.forIn(input, (prop, propIndex) => {
        let propName = propIndex;

        if (prefix != null) {
            propName = `${prefix}[${propName}]`;
        }

        if (_.isArray(prop)) {
            prop.forEach((item, i) => {
                pairs.push(serializeParams(item, `${propName}[${i}]`));
            });
            return;
        }

        if (Object.prototype.toString.call(prop) === '[object Object]') {
            pairs.push(serializeParams(prop, propName));
            return;
        }

        pairs.push(`${propName}=${prop}`);
    });

    return pairs.join('&');
}

export default serializeParams;
