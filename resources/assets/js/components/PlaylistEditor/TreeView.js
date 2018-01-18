import React from 'react';
import _ from 'lodash';
import { DropTarget } from 'react-dnd';

import {
    DND_TREEVIEW_VIDEO,
    DND_TREEVIEW_PLAYLIST,
} from '../../containers/PlaylistEditor/constants';

import TreeViewPlaylist from './TreeViewPlaylist';
import TreeViewChannel from './TreeViewChannel';


const emptyTreeTarget = {
    drop(props, monitor) {
        if(monitor.didDrop()) {
            // if drop proceeded by children, then skip
            return;
        }
        const { id: draggedId } = monitor.getItem();
        let playlists = props.playlists;

        let level = (props.view=="Top"? 1 : 2);
        var index = null, parent = null;
        playlists.map((item)=>{
            if(item.id === draggedId) {
                index = item.sort_order;
                parent = item.parent_id;
            }

            return item;
        });
        props.movePlaylist(draggedId, index, level, parent);
    }
};

@DropTarget([DND_TREEVIEW_VIDEO, DND_TREEVIEW_PLAYLIST], emptyTreeTarget, (connect, monitor) => ({
    connectDropTarget: connect.dropTarget(),
    isOver: monitor.isOver(),
}))

export default class TreeView extends React.Component {

    constructor(props) {
        super(props);

        this.movePlaylist = ::this.movePlaylist;
    }

    movePlaylist(id, atIndex, parentId) {
        const { playlist, index } = this.props.findPlaylist(id);

        if (index === atIndex) {
            return;
        }
        let level = (this.props.view=="Top"? 1 : 2);
        this.props.movePlaylist(id, atIndex, level, parentId);
    }


    render() {
        const { channel, playlists } = this.props;
        const classNames = ['b-playlist-treeview'];
        if (this.props.isOver) {
            classNames.push('b-playlist-treeview--over');
        }

        return this.props.connectDropTarget(
            <div className={classNames.join(' ')}>
                <div className="b-playlist-treeview__header">
                    Playlist {this.props.view} Shelf
                </div>

                {playlists.map((item, i) => {
                    const isOpened = _.includes(this.props.openedPlaylists, item.id);
                    if( (item['level'] < 2 && this.props.view == "Top") || (item['level'] >= 2 && this.props.view == "Lower") )
                    return (
                        <TreeViewPlaylist
                            key={i}
                            playlist={item}
                            findPlaylist={this.props.findPlaylist}
                            movePlaylist={this.movePlaylist}
                            moveVideo={this.props.moveVideo}
                            deletePlaylistVideo={this.props.deletePlaylistVideo}
                            addPlaylistVideo={this.props.addPlaylistVideo}
                            isOpened={ isOpened }
                            selectedPlaylist={this.props.selectedPlaylist}
                            toggleSelected={this.props.toggleSelected}
                            toggleOpened={this.props.toggleOpened}
                            videoToFind={this.props.videoToFind}
                            openedPlaylists={this.props.openedPlaylists}
                        />
                    )
                })}
            </div>
        );
    }
}