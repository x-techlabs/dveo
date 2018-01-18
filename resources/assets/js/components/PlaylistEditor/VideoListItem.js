import React from 'react';
// import { DropTarget } from 'react-dnd';
import _ from 'lodash';
import { DragSource } from 'react-dnd';

import {
    DND_VIDEOLIST_ITEM,
} from '../../containers/PlaylistEditor/constants';

const specSource = {
    beginDrag: (props) => ({ video: props.video })
};

@DragSource(DND_VIDEOLIST_ITEM, specSource, (connect, monitor) => ({
    connectDragSource: connect.dragSource(),
    isDragging: monitor.isDragging(),
}))


export default class VideoListItem extends React.Component {
    static propTypes = {
        onVideoFindClick: React.PropTypes.func,
        video: React.PropTypes.object.isRequired,
    };

    constructor(props) {
        super(props);

        this.playVideo = ::this.playVideo;
        this.findVideo = ::this.findVideo;
    }

    playVideo() {
        PlayVideoFromID(this.props.video.id);
    }

    findVideo() {
        if(_.isFunction(this.props.onVideoFindClick)){
            this.props.onVideoFindClick(this.props.video.id)
        }
    }

    render() {
        const { video, connectDragSource } = this.props;
        const classNames = ['b-playlist-videolist__item'];
        if (video.playlists.size > 0) {
            classNames.push('b-playlist-videolist__item--used');
        }

        return connectDragSource(
            <div className={classNames.join(' ')}>
                <div className="b-playlist-videolist__item-content">
                    <div className="b-playlist-videolist__item-preview">
                        <img src={video.thumbnail_name} />
                        <div className="b-playlist-videolist__item-icons">
                            <button
                                className="b-playlist-videolist__icon b-playlist-videolist__icon--play"
                                onClick={this.playVideo}
                            />
                            <button
                                className="b-playlist-videolist__icon b-playlist-videolist__icon--search"
                                onClick={this.findVideo}
                            />
                        </div>
                    </div>
                    <div className="b-playlist-videolist__item-title">
                        {video.title}
                    </div>
                    <div className="b-playlist-videolist__counter">
                        <div className="b-playlist-videolist__icon b-playlist-videolist__icon--counter">
                            {video.playlists.size}
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}