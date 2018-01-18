ace = ace || {};

extend(ace, {
    lastPlaylistEndTime: 0,
    playlists: [],
    playedId: [],
    timeline: null,
    randomString: function (len, charSet) {
        charSet = charSet || 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var randomString = '';
        for (var i = 0; i < len; i++) {
            var randomPoz = Math.floor(Math.random() * charSet.length);
            randomString += charSet.substring(randomPoz, randomPoz + 1);
        }
        return randomString;
    },
    playlistConfigArray: [],
    drawTimeline: function () {

        var container = $("#timeline-playout");
        var self = this;

        // D3Timeline
        ace.timeline = new D3Timeline({
            container: "#timeline-playout",
            unique_id: "_id",
            width: container.width(),
            height: container.height(),
            layout: D3Timeline.VERTICAL,
            shades: false,
            panels: [
                {
                    span: 6,
                    axis: {
                        span: moment.duration(90, "minutes")
                    },
                    zoomable: true
                }
            ]
        });

//        ace.timeline.data.update([], {
//            start: -Infinity,
//            end: Infinity
//        });

//        $(window).resize(function () {
//
//            ace.timeline.resize(container.width(), container.height(), true);
//        });


        $.ajax({
            url: ace.path('get_timeline_data'),
            type: "GET",
            dataType: "json",
            success: function (data) {

                var lastPlaylistEndTime = 0;

                $.each(data.playlist, function (key, val) {


                    if (val.end > lastPlaylistEndTime) {

                        lastPlaylistEndTime = val.end;
                    }

                    var playlist_id = self.randomString(8) + "-" +
                        self.randomString(4) + "-" +
                        self.randomString(4) + "-" +
                        self.randomString(4) + "-" +
                        self.randomString(12);


                    self.playlists.push({
                        'id': val.playlist.id,
                        '_id': playlist_id,
                        'name': val.playlist.title,
                        'duration': val.playlist.duration,
                        'start': val.start,
                        'end': val.end
                    });

                    self.playedId.push(parseInt(val.playlist.id));

                    self.playlistConfigArray.push(
                        {
                            attributes: {
                                _id: playlist_id,
                                allDay: false,
                                end: val.end,
                                event: null,
                                playlist: {
                                    "_id": playlist_id,
                                    "published": false,
                                    "name": val.playlist.title,
                                    "fixed": false,
                                    "duration": 15000,
                                    "pos": 0,
                                    "pieces": [
                                        playlist_id
                                    ],
                                    "occurrences": [],
                                    "transform": null,
                                    "channel": null,
                                    "get": function (attr) {
                                        return this[attr]
                                    }
                                },
                                start: val.start,
                                title: val.playlist.title,
                                transform: null
                            },
                            changed: {},
                            cid: "c" + val.playlist.id,
                            collection: {},
                            id: playlist_id,
                            overlapsWith: [],
                            get: function (attr) {
                                return this.attributes[attr]
                            }
                        }
                    );

                });

                ace.timeline.data.update(self.playlistConfigArray, {
                    start: -Infinity,
                    end: Infinity
                });

                ace.timeline.resize(container.width(), container.height(), true);

                self.lastPlaylistEndTime = lastPlaylistEndTime + 1;

            }
        });
    },

    "addPlaylistToTimeline": function (thisF) {

        var self = this;
        var date = new Date();
        var milliseconds = date.getTime();
        var playlistTimeStart;
        var container = $("#timeline-playout");

        console.log('this.lastPlaylistEndTime ' + this.lastPlaylistEndTime + ' milliseconds' + milliseconds);

        if (self.lastPlaylistEndTime > milliseconds) {
            playlistTimeStart = this.lastPlaylistEndTime + 1;
        } else {
            playlistTimeStart = milliseconds;
        }

        var name = $(thisF).data('name');
        var duration = parseInt($(thisF).data('duration')) * 1000;
        var playlistId = parseInt($(thisF).data('playlist_id'));
        var playlistTimeEnd = playlistTimeStart + duration;


//        var status = jQuery.inArray(playlistId, self.playedId);
//
//        if(parseInt(status) != -1) {
//            return alert("Playlist has in timeline");
//        }


        self.playedId.push(playlistId);


        var playlist_id =
            self.randomString(8) + "-" +
                self.randomString(4) + "-" +
                self.randomString(4) + "-" +
                self.randomString(4) + "-" +
                self.randomString(12);


        self.playlistConfigArray.push(
            {
                attributes: {
                    _id: playlist_id,
                    allDay: false,
                    end: playlistTimeEnd,
                    event: null,
                    playlist: {
                        "_id": playlist_id,
                        "published": false,
                        "name": name,
                        "fixed": false,
                        "duration": duration,
                        "pos": 0,
                        "pieces": [
                            playlist_id
                        ],
                        "occurrences": [],
                        "transform": null,
                        "channel": null,
                        "get": function (attr) {
                            return this[attr]
                        }
                    },
                    start: playlistTimeStart,
                    title: name,
                    transform: null
                },
                changed: {},
                cid: "c" + playlistId,
                collection: {},
                id: playlist_id,
                overlapsWith: [],
                get: function (attr) {
                    return this.attributes[attr]
                }
            }
        );


        ace.timeline.data.update(self.playlistConfigArray, {
            start: -Infinity,
            end: Infinity
        });

        ace.timeline.resize(container.width(), container.height(), true);


        this.lastPlaylistEndTime = playlistTimeEnd + 1;

        $.ajax({
            url: ace.path('insert_in_timeline'),
            type: "POST",
            data: {
                'playlist_id': playlistId,
                'start': playlistTimeStart
            },
            dataType: "json",
            success: function (data) {


                if (data.status == true) {

                }
            }


        });

    }
});


$(window).load(function () {

    ace.drawTimeline();


    $('.section_playlist_playout').click(function () {


        ace.addPlaylistToTimeline(this);
    });
});
