import React from 'react';
import ReactDOM from 'react-dom';
import { List } from 'immutable';

import Api from '../../api/api';

import Playlist from '../../api/models/playlist';
import Video from '../../api/models/video';
import Channel from '../../api/models/channel';
import Collection from '../../api/models/collection';

import TreeView from '../../components/RokuPreview/TreeView';


export default class RokuPreview extends React.Component {
	constructor(props){
		super(props);
        this.api = new Api();

        this.loadPlaylists = ::this.loadPlaylists;
        this.loadPlaylistsSuccess = ::this.loadPlaylistsSuccess;
        
        this.loadPlaylists();
	}

	state = {
        playlists: new List(),
        topPlpaylists: new List(),
        lowPlpaylists: new List(),
	}

	loadPlaylists() {
        this.api.getList(`channel_${this.props.channelId}/tvapp_playlists`).then(this.loadPlaylistsSuccess);
    }

    loadPlaylistsSuccess(response) {
        let newPlaylists = new List();

        response.data.forEach((item) => {
            let playlist = new Playlist(item.toJS());
            let videos = new List();

            playlist.videos.forEach((newVideo) => {
                videos = videos.push(new Video(newVideo))
            });

            playlist = playlist.set('videos', videos);

            newPlaylists = newPlaylists.push(playlist);
        });

        newPlaylists = newPlaylists.toOrderedMap().sortBy((a) => a.sort_order).toList();

    	let topPl = new List();
    	let lowPl = new List();

        newPlaylists.map((item)=>{
        	if( item['level'] >= 2 ) topPl = topPl.push(item);
        	else lowPl = lowPl.push(item);
        	return item;
        });

        this.setState({
            playlists: newPlaylists,
            playlists_loaded: true,
            topPlpaylists: topPl,
            lowPlpaylists: lowPl,
        })
    }

    render() {
      return (
      	<div>
          <TreeView 
            shelf="TopShelf"
            playlists={this.state.topPlpaylists} />
      		<TreeView 
      			shelf="LowShelf"
      			playlists={this.state.lowPlpaylists} />
      	</div>
      );
    }
}