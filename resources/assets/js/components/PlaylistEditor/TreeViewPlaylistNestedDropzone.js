import React from 'react' ;
import { DropTarget } from 'react-dnd';

import {
    DND_TREEVIEW_PLAYLIST,
} from '../../containers/PlaylistEditor/constants';

const specPlaylistTarget = {
    drop(props, monitor) {
        if(monitor.didDrop()) {
            // if drop proceeded by children, then skip
            return;
        }

        // sort playlists
        const { id: draggedId } = monitor.getItem();
        props.movePlaylist(draggedId, -1, props.playlist.id);
    }
};

@DropTarget([DND_TREEVIEW_PLAYLIST], specPlaylistTarget, (connect, monitor) => ({
    connectDropTargetPlaylist: connect.dropTarget(),
    isOverPlaylist: monitor.isOver({ shallow: true }),
}))

export default class TreeViewPlaylistNestedDropzone extends React.PureComponent {
    render() {
        return this.props.connectDropTargetPlaylist(
            <div className="b-playlist-treeview__playlist-nested-dropzone" />
        )
    }
}