import React from 'react';

export default class TreeViewChannel extends React.PureComponent {
    static propTypes = {
        channel: React.PropTypes.object.isRequired,
    };

    constructor(props) {
        super(props);
    }

    render() {
        const { channel } = this.props;

        const classNames = [ 'b-playlist-treeview__channel' ];
        if (this.props.selected) { classNames.push('b-playlist-treeview__channel--selected'); }

        return (
            <div className={classNames.join(' ')}>
                <div className="b-playlist-treeview__channel-name" onClick={this.props.onChannelNameClick}>
                    {channel.name}
                </div>
                <div className="b-playlist-treeview__channel-tree">
                    {this.props.children}
                </div>
            </div>
        );
    }
}