import React from 'react';
import { DragSource, DropTarget } from 'react-dnd';

import {
    DND_TREEVIEW_VIDEO,
    DND_TREEVIEW_PLAYLIST,
    DND_VIDEOLIST_ITEM,
} from '../../containers/PlaylistEditor/constants';

const specSource = {
    beginDrag: (props) => ({ video: props.video })
};

const specPlaylistTarget = {
    drop(props, monitor) {
        const { id: draggedId } = monitor.getItem();
        props.movePlaylist(draggedId, 1000, props.video.pivot.tvapp_playlist_id);
    }
};

const specTarget = {
    drop(props, monitor) {
        const { video: draggedVideo } = monitor.getItem();
        const playlistId = props.video.pivot.tvapp_playlist_id;
        const newIndex = props.video.pivot.sort_order;


        props.moveVideo(draggedVideo.id, newIndex, playlistId, draggedVideo.pivot.tvapp_playlist_id);
    }
};

@DragSource(DND_TREEVIEW_VIDEO, specSource, (connect, monitor) => ({
    connectDragSource: connect.dragSource(),
    isDragging: monitor.isDragging(),
}))

@DropTarget([DND_TREEVIEW_PLAYLIST], specPlaylistTarget, (connect, monitor) => ({
    connectDropTargetPlaylist: connect.dropTarget(),
    isOverPlaylist: monitor.isOver(),
}))


@DropTarget([DND_TREEVIEW_VIDEO, DND_VIDEOLIST_ITEM], specTarget, (connect, monitor) => ({
    connectDropTarget: connect.dropTarget(),
    isOver: monitor.isOver(),
}))

export default class TreeViewVideo extends React.Component {
    static propTypes = {
        video: React.PropTypes.object.isRequired,
    };


    render() {
        const { video, connectDragSource, connectDropTarget, connectDropTargetPlaylist } = this.props;

        const classNames = [ 'b-playlist-treeview__video' ];
        if(this.props.isOver) { classNames.push('b-playlist-treeview__video--over') }
        if(this.props.isOverPlaylist) { classNames.push('b-playlist-treeview__video--over') }
        if(this.props.isDragging) { classNames.push('b-playlist-treeview__video--dragging') }
        if(this.props.highlighted) {classNames.push('b-playlist-treeview__video--highlighed')}


        return connectDragSource(connectDropTarget(connectDropTargetPlaylist(
            <div className={classNames.join(' ')}>
                {video.title}
            </div>
        )));
    }
}