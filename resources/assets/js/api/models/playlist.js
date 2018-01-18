import { List, Record } from 'immutable';

const Playlist = new Record({
    id: null,
    channel_id: 35,
    tvapp_id: 0,
    title: null,
    description: null,
    thumbnail_name: null,
    duration: null,
    master_looped: null,
    type: 0,
    level: 0,
    parent_id: null,
    layout: 0,
    stream_url: "",
    status: "",
    created_at: null,
    updated_at: null,
    time: null,
    videos: [],
    sort_order: 0,
    children: new List(),
});

export default Playlist;
