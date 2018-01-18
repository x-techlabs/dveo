import React from 'react';
import _ from 'lodash';
import TreeViewPlaylst from './TreeViewPlaylist';

export default function TreeViewPlaylistChildren(props) {
    return (
        <div className="b-playlist-treeview__children-list">
            {props.playlists.map((item, i) => {
                const isOpened = _.includes(props.openedPlaylists, item.id);
                const playlistProps = _.omit(props, ['playlists']);

                return (<TreeViewPlaylst
                    key={i}
                    playlist={item}
                    isOpened={ isOpened }
                    {...playlistProps}
                />);
            })}
        </div>
    );
}
