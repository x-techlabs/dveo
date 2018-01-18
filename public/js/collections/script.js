$(document).ready(function() {
    function addCollectionPost() {
        var playlist = [];

        $('.add_videos_in_playlist').each(function(){
            playlist.push($(this).data('video_id'));
        });

        $.ajax({
            url: ace.path('add_to_collection'),
            type: "POST",
            data: {
                'id': $( "input[name$='id']" ).val(),
                'title': $( "input[name$='title']" ).val(),
                'playlist': playlist
            },
            dataType: "json",
            success: function (data) {
                if(data.status) {
                    $('.edited').html('<span class="fui-check"></span> Collection successfully added!');
                    setInterval(function() {
                        window.location.replace("collections");
                    }, 1500);
                }
            }
        });
    }

    function editCollectionPost() {
        var playlist = [];

        $('.add_videos_in_playlist').each(function(){
            playlist.push($(this).data('video_id'));
        });

        $.ajax({
            url: ace.path('edit_collection_post'),
            type: "POST",
            data: {
                'id': $( "input[name$='id']" ).val(),
                'title': $( "input[name$='title']" ).val(),
                'viewing': $('#viewing').val(),
                'preroll': $('#preroll').val(),
                'playlist': playlist
            },
            dataType: "json",
            success: function (data) {
                if(data.status) {
                    $('.edited').html('<span class="fui-check"></span> Collection successfully edited!');
                    setInterval(function() {
                        window.location.replace("collections");
                    }, 1500);
                }
            }
        });
    }

    function editCollectionGet(id) {
        $.ajax({
            url: ace.path('edit_collection_get'),
            type: "get",
            data: {
                'id': id
            },
            dataType: "html",
            success: function (data) {
                $('#collections-col').removeClass('col-md-12').addClass('col-md-4');
                $('.description').remove();

                $('.collectionRight').remove();
                $('.playlists-for-collection-by-id').remove();
                $('.collection-form').remove();
                $('#videos-all').remove();

                $('#contnet-wrap').append(data);

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

                //$('.videoTtitle').css('maxWidth', '230px');

                // Changing columns
                $('.list_item').find('.col-md-2').removeClass('col-md-2').addClass('col-md-4');
                $('.list_item').find('.col-md-10').removeClass('col-md-10').addClass('col-md-4');

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
    }

    function deleteCollection(id) {
        $.ajax({
            url: ace.path('delete_collection'),
            type: "POST",
            data: {
                "collectionId": id
            },
            success: function(data) {
                if(data.status) {
                    $('[data-collection_id="' + id + '"]').fadeOut();
                    $('#' + id).fadeOut();
                }
            }
        });
    }

    $(document).delegate('#add-to-collection', 'submit', function(event) {
        event.preventDefault();
        addCollectionPost();
    });

    $(document).delegate('#edit_collection', 'submit', function(event) {
        event.preventDefault();
        editCollectionPost();
    });

    $('.edit_collection').click(function (event) {
        event.stopPropagation();
        event.preventDefault();
        $('.section_collections').removeClass('active_item');
        $(this).parent().toggleClass('active_item');
        var id = $(this).attr('id');
        editCollectionGet(id);
        return false;
    });

    $(document).on("click",".cancel_collection",function(event) {
        $('#videos-all').remove();
        $('#upload').remove();
        $('#collections-col').removeClass('col-md-4').addClass('col-md-12');
        $('.list_item').find('.col-md-4').removeClass('col-md-4').addClass('col-md-2');
        $('.list_item').find('.col-md-4').removeClass('col-md-4').addClass('col-md-10');
    })

    $('.delete_collection').click(function(event) {
        event.stopPropagation();
        event.preventDefault();
        var id = $(this).attr('id');
        if(confirm('are you sure?')) {
            deleteCollection(id);
        }
        return false;
    });
});