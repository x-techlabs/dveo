import _ from 'lodash';
import React from 'react';
import { DragSource, DropTarget } from 'react-dnd';

import {
    DND_TREEVIEW_VIDEO,
    DND_TREEVIEW_PLAYLIST,
    DND_VIDEOLIST_ITEM,
} from '../../containers/PlaylistEditor/constants';

import TreeViewPlaylistChildren from './TreeViewPlaylistChildren';
import TreeViewVideo from './TreeViewVideo';
import TreeViewPlaylistNestedDropzone from './TreeViewPlaylistNestedDropzone';

const specSource = {
    beginDrag(props) {
        return {
            id: props.playlist.id,
        };
    }
};

const specPlaylistTarget = {
    drop(props, monitor) {
        if(monitor.didDrop()) {
            // if drop proceeded by children, then skip
            return;
        }

        // sort playlists
        const { id: draggedId } = monitor.getItem();
        const overId = props.playlist.id;

        if (draggedId !== overId) {
            const { index: overIndex } = props.findPlaylist(overId);
            const { index: itemIndex } = props.findPlaylist(draggedId);

            let newIndex = overIndex + 1; // next to over
            if(newIndex > itemIndex) {
                newIndex-=1 // because we are removing item from its original position and all indexes after it will get offset -1
            }
            props.movePlaylist(draggedId, newIndex, props.playlist.parent_id);
        }
    }
};

const specVideoTarget = {
    drop(props, monitor) {
        if(monitor.didDrop()) {
            // if drop proceeded by children, then skip
            return;
        }

        const { video } = monitor.getItem();
        const playlistId = props.playlist.id;

        if(_.isNil(video.pivot.tvapp_playlist_id)) {
            // add video from another source

            props.addPlaylistVideo(playlistId, video.id);
        }
        else if (video.pivot.tvapp_playlist_id === playlistId) {
            // move video to the end of current playlist

            props.moveVideo(video.id, -1, playlistId, playlistId)
        } else {
            // move video to another playlist

            props.deletePlaylistVideo(video.pivot.tvapp_playlist_id, video.id);
            props.addPlaylistVideo(playlistId, video.id);
        }
    }
};


@DragSource(DND_TREEVIEW_PLAYLIST, specSource, (connect, monitor) => ({
    connectDragSource: connect.dragSource(),
    isDragging: monitor.isDragging(),
}))

@DropTarget([DND_TREEVIEW_PLAYLIST], specPlaylistTarget, (connect, monitor) => ({
    connectDropTargetPlaylist: connect.dropTarget(),
    isOverPlaylist: monitor.isOver({ shallow: true }),
}))

@DropTarget([DND_TREEVIEW_VIDEO, DND_VIDEOLIST_ITEM], specVideoTarget, (connect, monitor) => ({
    connectDropTargetVideo: connect.dropTarget(),
    isOverVideo: monitor.isOver({ shallow: true }),
}))

export default class TreeViewPlaylist extends React.Component {
    static propTypes = {
        playlist: React.PropTypes.object.isRequired,
        connectDragSource: React.PropTypes.func.isRequired,
        connectDropTargetPlaylist: React.PropTypes.func.isRequired,
    };

    constructor(props) {
        super(props);

        this.toggleOpened = ::this.toggleOpened;
        this.isOpened = ::this.isOpened;
        this.toggleSelected = _.debounce(::this.toggleSelected, 300, { leading: true, trailing: false });
        this.editPlaylist = ::this.editPlaylist;

        this.startIsOverTimeout = ::this.startIsOverTimeout;
        this.resetIsOverTimeout = ::this.resetIsOverTimeout;
    }

    state = {
        opened: false,
    };


    componentDidUpdate(prevProps) {
        // open playlist on long hold other playlist over it
        if (!this.isOpened() && !prevProps.isOverPlaylist && this.props.isOverPlaylist) {
            this.startIsOverTimeout();
        }
        if (!this.isOpened() && prevProps.isOverPlaylist && !this.props.isOverPlaylist) {
            this.resetIsOverTimeout();
        }
    }

    startIsOverTimeout() {
        this.isOverPlaylistTimeout = setTimeout(this.toggleOpened, 3000)
    }

    resetIsOverTimeout() {
        clearTimeout(this.isOverPlaylistTimeout);
    }

    toggleOpened() {
        this.props.toggleOpened(this.props.playlist.id);
    }

    toggleSelected() {
        this.props.toggleSelected(this.props.playlist.id);
    }

    editPlaylist() {
        tvapp_columns3.tvappEditPlaylist(this.props.playlist.id)
    }

    isOpened() {
        return this.props.isOpened || (this.props.playlist.videos.findIndex((item) => item.id === this.props.videoToFind) !== -1);
    }

    render() {
        const { playlist, connectDragSource, connectDropTargetPlaylist, connectDropTargetVideo } = this.props;

        const classNames = [ 'b-playlist-treeview__playlist' ];
        if(this.props.isOverPlaylist) { classNames.push('b-playlist-treeview__playlist--over') }
        if(this.props.isOverVideo) { classNames.push('b-playlist-treeview__playlist--over-video') }
        if(this.props.isDragging) { classNames.push('b-playlist-treeview__playlist--dragging') }
        if(this.props.selectedPlaylist === playlist.id) { classNames.push('b-playlist-treeview__playlist--selected') }

        const isOpened = this.isOpened();
        const hasChildren = playlist.videos.size !== 0 || playlist.children.size !== 0;

        const childrenProps = _.pick(this.props,[
                'findPlaylist', 'movePlaylist', 'moveVideo', 'deletePlaylistVideo', 'addPlaylistVideo',
                'toggleOpened', 'toggleSelected', 'selectedPlaylist', 'videoToFind', 'openedPlaylists',
            ]);


        return connectDropTargetVideo(connectDropTargetPlaylist(connectDragSource(
            <div className={classNames.join(' ')} >
                <div className="b-playlist-treeview__playlist-title">
                    <div
                        className={`b-playlist-treeview__playlist-link ${this.props.isOpened ? 'b-playlist-treeview__playlist-link--open' : ''}`}
                        onClick={this.toggleSelected}
                        onDoubleClick={this.toggleOpened}
                    >
                        {playlist.title}
                    </div>
                    <button
                        className="btn btn-xs btn-warning b-playlist-treeview__playlist-edit"
                        onClick={this.editPlaylist}
                    >
                        Edit
                    </button>
                    {/*<button className="btn btn-xs btn-danger b-playlist-treeview__playlist-delete">*/}
                        {/*Delete*/}
                    {/*</button>*/}
                </div>
                {isOpened && !hasChildren && <TreeViewPlaylistNestedDropzone
                    playlist={playlist}
                    movePlaylist={this.props.movePlaylist}
                />}
                {isOpened && hasChildren && <TreeViewPlaylistChildren
                    playlists={playlist.children}
                    {...childrenProps}
                />}
                {isOpened && hasChildren &&
                    <div className="b-playlist-treeview__video-list">
                        {playlist.videos.map((item, i) => (
                            <TreeViewVideo
                                video={item}
                                key={i}
                                movePlaylist={this.props.movePlaylist}
                                moveVideo={this.props.moveVideo}
                                highlighted={this.props.videoToFind === item.id}
                            />
                        ))}
                    </div>
                }
            </div>
        )));
    }
}