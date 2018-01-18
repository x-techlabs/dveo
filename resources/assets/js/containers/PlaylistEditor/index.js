import React from 'react';
import _ from 'lodash';
import { List } from 'immutable';
import HTML5Backend from 'react-dnd-html5-backend';
import { DragDropContext, DropTarget } from 'react-dnd';

import Api from '../../api/api';

import Playlist from '../../api/models/playlist';
import Video from '../../api/models/video';
import Channel from '../../api/models/channel';
import Collection from '../../api/models/collection';

import TreeView from '../../components/PlaylistEditor/TreeView';
import VideoList from '../../components/PlaylistEditor/VideoList';


@DragDropContext(HTML5Backend)

export default class PlaylistEditor extends React.Component {

    constructor(props) {
        super(props);
        this.api = new Api();

        this.loadPlaylists = ::this.loadPlaylists;
        this.loadPlaylistsSuccess = ::this.loadPlaylistsSuccess;
        this.loadVideos = ::this.loadVideos;
        this.loadVideosSuccess = ::this.loadVideosSuccess;
        this.loadCollections = ::this.loadCollections;
        this.loadCollectionsSuccess = ::this.loadCollectionsSuccess;
        this.buildPlaylistsTree = ::this.buildPlaylistsTree;
        this.toggleOpened = ::this.toggleOpened;
        this.toggleSelected = ::this.toggleSelected;
        this.toggleChannelSelection = ::this.toggleChannelSelection;

        this.movePlaylist = ::this.movePlaylist;
        this.findPlaylist = ::this.findPlaylist;
        this.moveVideo = ::this.moveVideo;
        this.findVideo = ::this.findVideo;

        this.deletePlaylistVideo = ::this.deletePlaylistVideo;
        this.addPlaylistVideo = ::this.addPlaylistVideo;

        this.loadPlaylists();
        this.loadVideos();
        this.loadCollections();
    }

    state = {
        channel: new Channel(),
        channelSelected: false,
        playlists: new List(),
        playlistsOpened: [],
        playlistSelected: null,
        playlists_loaded: false,

        videos: new List(),
        videos_loaded: false,
        videoListVisible: true,
        videoToFind: null,

        collections: new List(),
        collections_loaded: false,
    };

    componentDidMount() {
        this.setState({
            channel: new Channel({
                id: this.props.channelId,
                name: this.props.channelName,
            })
        })
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

        this.setState({
            playlists: newPlaylists,
            playlists_loaded: true,
        })
    }

    buildPlaylistsTree(playlists) {
        return this.buildPlaylistsTreeBranch(playlists, null);
    }

    buildPlaylistsTreeBranch(playlists, itemId) {
        let tree = playlists.filter((item) => item.parent_id === itemId);
        if (tree.size === 0) {
            return new List();
        }

        tree = tree.toOrderedMap().sortBy((a) => parseInt(a.sort_order, 10)).toList();
        return tree.map((item) => item.set('children', this.buildPlaylistsTreeBranch(playlists, item.id)));
    }

    loadVideos() {
        this.api.getList(`channel_${this.props.channelId}/videos`).then(this.loadVideosSuccess);
    }

    loadVideosSuccess(response) {
        let newVideos = new List();
        response.data.forEach((item) => {
            let video = new Video(item.toJS());
            let playlists = new List();

            video.playlists.forEach((newPlaylist) => {
                playlists = playlists.push(new Playlist(newPlaylist))
            });

            video = video.set('playlists', playlists);

            newVideos = newVideos.push(video);
        });

        this.setState({
            videos: newVideos,
            videos_loaded: true,
        })
    }

    loadCollections() {
        this.api.getList(`channel_${this.props.channelId}/collections`).then(this.loadCollectionsSuccess);
    }

    loadCollectionsSuccess(response) {
        let newCollections = new List();
        response.data.forEach((item) => {
            let collection = new Collection(item.toJS());
            newCollections = newCollections.push(collection);
        });

        this.setState({
            collections: newCollections,
            collections_loaded: true,
        })
    }

    addPlaylistVideo(playlistId, videoId, index = null) {
        const newVideo = this.state.videos.find((c) => c.id === videoId);
        const playlistIndex = this.state.playlists.findIndex((item) => (item.id === playlistId));
        const videoSortOrder = index || (this.state.playlists.get(playlistIndex).videos.size + 1);
        const pivot = {
            video_id: videoId,
            tvapp_playlist_id: playlistId,
            type: 0,
            sort_order: videoSortOrder,
        };

        const newPlaylists = this.state.playlists.updateIn([playlistIndex, 'videos'], (videos) => {
            // get video index
            const videoIndex = videos.findIndex(c => c.id === newVideo.id);
            let newVideos = videos;

            // append video
            if(videoIndex !== -1) {
                newVideos = newVideos.update(videoIndex, (video) => video.set('pivot',pivot));
            } else {
                newVideos = newVideos.push(newVideo.set('pivot', pivot))
            }

            // reorder
            newVideos = newVideos.sort((a, b) => {
                if (a.pivot.sort_order > b.pivot.sort_order) { return 1; }
                if ((a.pivot.sort_order === b.pivot.sort_order) && (b.id === videoId)) { return 1; }

                return -1
            });

            return this.normalizeVideoList(newVideos);
        });


        // update playlist list in the video
        const videoIndex = this.state.videos.findIndex((item) => item.id === videoId);
        const newVideos = this.state.videos.updateIn([videoIndex, 'playlists'], (playlists) => {
            if(playlists.findIndex((item) => item.id === playlistId) !== -1) {
                return playlists;
            }

            const playlist = this.state.playlists.find((item) => item.id === playlistId);
            return playlists.push(playlist);
        });

        this.setState({
            playlists: newPlaylists,
            videos: newVideos,
        });

        this.api.call(`channel_${this.props.channelId}/tvapp_playlists/${playlistId}/videos/${videoId}`, 'put', pivot);
    }

    normalizeVideoList(videos) {
        // normalize sort_order values
        let sortOrderIterator = 0;
        return videos.map((video) => {
            sortOrderIterator = sortOrderIterator + 1;
            let newPivot = video.pivot;
            newPivot.sort_order = sortOrderIterator;
            return video.set('pivot', newPivot);
        });
    }


    deletePlaylistVideo(playlistId, videoId) {
        const playlistIndex = this.state.playlists.findIndex((item) => (item.id === playlistId));

        const newPlaylists = this.state.playlists.updateIn([playlistIndex, 'videos'], (videos) => {
            return this.normalizeVideoList(videos.filterNot(c => c.id === videoId));
        });

        // update playlist list in the video
        const videoIndex = this.state.videos.findIndex((item) => item.id === videoId);
        const newVideos = this.state.videos.updateIn([videoIndex, 'playlists'], (playlists) => {
            const playlist = this.state.playlists.find((item) => item.id === playlistId);
            return playlists.filter((item) => item.id !== playlistId);
        });

        this.setState({
            playlists: newPlaylists,
            videos: newVideos,
        });

        this.api.call(`channel_${this.props.channelId}/tvapp_playlists/${playlistId}/videos/${videoId}`, 'delete');
    }

    toggleOpened(playlistId) {
        this.setState({
            playlistsOpened: _.xor(this.state.playlistsOpened, [ playlistId ]),
        });
    }

    toggleSelected(playlistId) {
        let newSelectedPlaylist = playlistId;
        if (this.state.playlistSelected === playlistId) {
            newSelectedPlaylist = null;
            $("#parent_playlist_id").val(0);
        } else {
            $("#parent_playlist_id").val(newSelectedPlaylist);
        }

        this.setState({
            playlistSelected: newSelectedPlaylist,
            channelSelected: false,
        });
    }

    toggleChannelSelection() {
        if (this.state.channelSelected) {
            this.setState({
                channelSelected: false,
            });
        } else {
            this.setState({
                channelSelected: true,
                playlistSelected: null,
            })
        }
        $("#parent_playlist_id").val(0);
    }

    movePlaylist (id, atIndex, atLevel, parentId = null) {

        const playlistIndex = this.state.playlists.findIndex(c => c.id === id);

        let playlists = this.state.playlists.updateIn([playlistIndex], (item) => (
            item.merge({
                parent_id: parentId,
                sort_order: atIndex,
                level: atLevel,
            })
        ));

        playlists = playlists.toOrderedMap().sortBy((a) => parseInt(a.sort_order, 10)).toList();

        const playlist = playlists.find(c => c.id === id);

        let sortOrderIterator = 0;
        playlists = playlists.map((item) => {
            if(item.id === id) return item.set("level", atLevel);
            if(item.parent_id !== playlist.parent_id) {
                return item;
            }

            sortOrderIterator++;

            if(sortOrderIterator === atIndex) {
                sortOrderIterator++;
            }

            return item.set('sort_order', sortOrderIterator);
        });

        playlists = playlists.toOrderedMap().sortBy((a) => a.sort_order).toList();

        this.api.save(`channel_${this.props.channelId}/tvapp_playlists`, playlist.set('videos', []).toJS());

        this.setState({ playlists });
    }

    findPlaylist(id) {
        const playlist = this.state.playlists.find(c => c.id === id);
        const index = this.state.playlists.filter((item) => (
            item.parent_id === playlist.parent_id
        )).indexOf(playlist) + 1;

        return {
            playlist,
            index,
        };
    }

    moveVideo (videoId, newIndex, playlistId, oldPlaylistId) {
        if(!_.isNil(oldPlaylistId) && playlistId !== oldPlaylistId) {
            this.deletePlaylistVideo(oldPlaylistId, videoId);
        }
        this.addPlaylistVideo(playlistId, videoId, newIndex);
    }

    findVideo(videoId) {
        this.setState({
            videoToFind: videoId,
        });
    }

    render() {
        const classNames=['b-playlist-editor'];
        if (this.props.isOver) {
            classNames.push('b-playlist-editor--video-dragging')
        }

        return (
            <div className={classNames.join(' ')}>
                <TreeView
                    view="Top"
                    channel={this.state.channel}
                    channelSelected={this.state.channelSelected}
                    onChannelNameClick={this.toggleChannelSelection}
                    movePlaylist={this.movePlaylist}
                    findPlaylist={this.findPlaylist}
                    playlists={this.buildPlaylistsTree(this.state.playlists)}
                    addPlaylistVideo={this.addPlaylistVideo}
                    deletePlaylistVideo={this.deletePlaylistVideo}
                    openedPlaylists={this.state.playlistsOpened}
                    toggleSelected={this.toggleSelected}
                    selectedPlaylist={this.state.playlistSelected}
                    toggleOpened={this.toggleOpened}
                    moveVideo={this.moveVideo}
                    videoToFind={this.state.videoToFind} />
                <TreeView
                    view="Lower"
                    channel={this.state.channel}
                    channelSelected={this.state.channelSelected}
                    onChannelNameClick={this.toggleChannelSelection}
                    movePlaylist={this.movePlaylist}
                    findPlaylist={this.findPlaylist}
                    playlists={this.buildPlaylistsTree(this.state.playlists)}
                    addPlaylistVideo={this.addPlaylistVideo}
                    deletePlaylistVideo={this.deletePlaylistVideo}
                    openedPlaylists={this.state.playlistsOpened}
                    toggleSelected={this.toggleSelected}
                    selectedPlaylist={this.state.playlistSelected}
                    toggleOpened={this.toggleOpened}
                    moveVideo={this.moveVideo}
                    videoToFind={this.state.videoToFind} />
                {this.state.videoListVisible && <VideoList
                    videos={this.state.videos}
                    collections={this.state.collections}
                    onVideoFindClick={this.findVideo}
                    onVideoDeleteFromPlaylist={this.deletePlaylistVideo} />}
            </div>
        );
    }
}
