import React from 'react';
import Select from 'react-select';
import { DropTarget } from 'react-dnd';
// import _ from 'lodash';

import {
    DND_TREEVIEW_VIDEO,
} from '../../containers/PlaylistEditor/constants';

import VideoListItem from './VideoListItem';

const specTarget = {
    drop(props, monitor) {
        const { video: draggedVideo } = monitor.getItem();
        props.onVideoDeleteFromPlaylist(draggedVideo.pivot.tvapp_playlist_id, draggedVideo.id);
    }
};
@DropTarget([DND_TREEVIEW_VIDEO], specTarget, (connect, monitor) => ({
    connectDropTarget: connect.dropTarget(),
    isOver: monitor.isOver(),
}))

export default class VideoList extends React.Component {
    state = {
        selectedCollection: null,
    };

    constructor(props) {
        super(props);

        this.filterByPlaylist = ::this.filterByPlaylist;
    }

    filterByPlaylist(value) {
        if(_.isNil(value)) {
            this.setState({ selectedCollection: null });
            return;
        }

        this.setState({ selectedCollection: value.id })
    }

    render() {
        const { collections } = this.props;
        const videos = _.isNil(this.state.selectedCollection) ?
            this.props.videos :
            this.props.videos.filter((item) => (
                item.collections.findIndex((collection) => (
                    collection.id === this.state.selectedCollection
                )) !== -1
            ));

        const classNames=['b-playlist-videolist'];
        if(this.props.isOver) { classNames.push('b-playlist-videolist--delete-dropzone')}

        return this.props.connectDropTarget(
            <div className={classNames.join(' ')}>
                <div className="b-playlist-videolist__inner">
                <div className="b-playlist-videolist__header">
                    Videos
                </div>
                <div className="b-playlist-videolist__categories">
                    <Select
                        options={collections.toJS()}
                        value={this.state.selectedCollection}
                        valueKey="id"
                        labelKey="title"
                        onChange={this.filterByPlaylist}
                        placeholder="Filter by category..."
                    />
                </div>
                {videos.map((video, i) => (
                    <VideoListItem video={video} key={i} onVideoFindClick={this.props.onVideoFindClick} />
                ))}
                </div>
            </div>
        );
    }
}