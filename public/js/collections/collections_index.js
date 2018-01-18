$(document).ready(function () {
    var ACE = function () {

    };
    /*
     * Get the playlists by collection id
     *
     * @param thisF
     */
    ACE.prototype.getPlaylists = function (thisF) {

        var collection_id = $(thisF).data('collection_id');

        var data = {
            'collection_id': collection_id
        };
        var thisFunc = this;
        $.ajax({
            url: ace.path('get_videos_by_collection_id'),
            type: 'GET',
            data: data,
            dataType: "html",
            success: function (data) {
                $('#collections-col').removeClass('col-md-12').removeClass('col-md-3').addClass('col-md-6');
                $('.playlists').remove();
                $('.playlists').remove();

                $('.collectionRight').remove();
                $('.playlists-for-collection-by-id').remove();
                $('.collection-form').remove();

                $('#collections-col').after(data);

                $('.searchCol').animate({
                    'padding-left' : '10px',
                    'padding-right': '10px',
                    'width': "200px"
                }, 150);
                $('.plusPtnCol').animate({
                    'width': '120px',
                    'padding-left': '5px',
                    'padding-right': '5px',
                    'font-size': '15px'
                }, 150);
            }
        });

    };

    /*
     * Get the collection form by ajax
     */
    ACE.prototype.addCollection = function () {

        $.ajax({
            url: ace.path('add_collection_form'),
            type: "GET",
            async: true,
            data: {
                "form": true
            },
            dataType: "html",
            success: function (data) {
                $('#video-md-col').removeClass('col-md-6').addClass('col-md-3');
                $('.description').addClass('hide');
                $('#collections-col').removeClass('col-md-12').addClass('col-md-4');
               // $('.col-md-6').width('49%');

                //$('.playlists').addClass('hide');
                $('.description').addClass('hide');

                $('.collectionRight').remove();
                $('.playlists-for-collection-by-id').remove();
                $('.collection-form').remove();
                $('#videos-all').remove();

                $('#collections-col').after(data);

                $('.searchCol').animate({
                    'padding-left' : '10px',
                    'padding-right': '10px',
                    'width': "200px"
                }, 150);
                $('.plusPtnCol').animate({
                    'width': '120px',
                    'padding-left': '5px',
                    'padding-right': '5px',
                    'font-size': '15px'
                }, 150);

                // --------------    added by vinay     ------------------------
                $.ajax({
                    url: "get_videos_for_playlists",
                    type: "GET",
                    async: true,
                    data: {
                        "form" : true
                    },
                    dataType: "html",
                    success: function (data) {
                        $('#contnet-wrap').append(data);
                    }
                });

            }


        });
    };

    ACE.prototype.addToCollection =
        $('.section_collections').click(function () {
            ACE.prototype.getPlaylists(this);
        });

    $('#add-collection').click(function (event) {

        ACE.prototype.addCollection();
    });


});