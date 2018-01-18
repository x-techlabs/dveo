require('./foundation');
import React from 'react';
import ReactDOM from 'react-dom';
import Promise from 'promise-polyfill';

import PlaylistEditor from './containers/PlaylistEditor';
import RokuPreview from './containers/RokuPreview';

if (!window.Promise) {
    window.Promise = Promise;
}

const render = () => {
    let element;

    element = document.getElementById('js-playlists-treeview');
    if(element) {
        ReactDOM.render(<PlaylistEditor
            channelId={element.dataset.id}
            channelName={element.dataset.name}/>, element);
    }
};

export const renderPreview = () => {
    let element;

    element = document.querySelectorAll("#preview_modal .modal-body")[0];
    if(element) {
        ReactDOM.render(<RokuPreview
            channelId={element.dataset.id}
            channelName={element.dataset.name}/>, element);
    }
};

$(document).ready(function(){
	render();

    $('#tvapp_preview').click(function(event){
        renderPreview();
    });
});
