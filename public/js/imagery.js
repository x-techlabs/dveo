function addCollection() {
    $.ajax({
        url: ace.path('add_folder_form'),
        type: "GET",
        async: true,
        data: {
            "form": true
        },
        dataType: "html",
        success: function (data) {
            $('.description').addClass('hide');
            $('#tabContainer').removeClass('col-md-12').addClass('col-md-4');
           // $('.col-md-6').width('49%');

            //$('.playlists').addClass('hide');
            $('.description').addClass('hide');

            $('.collectionRight').remove();
            $('.playlists-for-collection-by-id').remove();
            $('.collection-form').remove();
            $('#images-all').remove();

            $('#tabContainer').after(data);

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

            $.ajax({
                url: "get_images_for_folders",
                type: "GET",
                async: true,
                data: {
                    "form" : true
                },
                dataType: "html",
                success: function (data) {
                    $('#tab2').append(data);
                }
            });

        }
    });

};


function addCollectionPost() {
    var playlist = [];

    $('.add_videos_in_playlist').each(function(){
        playlist.push($(this).data('video_id'));
    });

    $.ajax({
        url: ace.path('add_to_folder'),
        type: "POST",
        data: {
            'id': $( "input[name$='id']" ).val(),
            'title': $( "input[name$='title']" ).val(),
            'playlist': playlist
        },
        dataType: "json",
        success: function (data) {
            if(data.status) {
                var folder = data.folder;
                $('.edited').html('<span class="fui-check"></span> Folder successfully added!');
                $('#tabContainer').removeClass('col-md-4').addClass('col-md-12');
                $('.addFolderWrapper,#images-all').remove();
                var str = '';
                str += '<section data-collection_id="'+folder.id+'" class="list_item section_collections">';
                    str += ' <button id="'+folder.id+'" class="delete_collection editDelete fr btn btn-block btn-lg btn-danger" title="Delete collection">';
                        str += '<span class="fui-trash"></span>';
                    str += '</button>';
                    str += '<button id="'+folder.id+'" class="edit_collection editDelete fr btn btn-block btn-lg btn-inverse" title="Edit collection">';
                        str += '<span class="fui-new"></span>';
                    str += '</button>';
                    str += '<div class="row center-block" style="margin-left:20px">';
                        str += '<div class="col-md-12">';
                            str += '<h1 class="videoTtitle">'+folder.title+'</h1>';
                        str += '</div>';
                    str += '</div>';
                str += '</section>';
                $('.folder_container').append(str);
                str = '';
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
        url: ace.path('edit_folder_post'),
        type: "POST",
        data: {
            'id': $( "input[name$='id']" ).val(),
            'title': $( "input[name$='title']" ).val(),
            'playlist': playlist
        },
        dataType: "json",
        success: function (data) {
            if(data.status) {
                var item = data.folder;
                $('.edited').html('<span class="fui-check"></span> Folder successfully edited!');
                $('#tabContainer').removeClass('col-md-4').addClass('col-md-12');
                $('.editFolderWrapper,#images-all').remove();
                $(".folder_item[data-collection_id="+item.id+"]").find('.folder_title').text(item.title);
            }
        }
    });
}

function editCollectionGet(id) {
    $.ajax({
        url: ace.path('edit_folder_get'),
        type: "get",
        data: {
            'id': id
        },
        dataType: "html",
        success: function (data) {
            $('#tabContainer').removeClass('col-md-12').addClass('col-md-4');
            $('.description').remove();

            $('.collectionRight').remove();
            $('.playlists-for-collection-by-id').remove();
            $('.collection-form').remove();
            $('#images-all').remove();

            $('#tab2').append(data);

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

            $('.list_item').find('.col-md-2').removeClass('col-md-2').addClass('col-md-4');
            $('.list_item').find('.col-md-10').removeClass('col-md-10').addClass('col-md-4');

            $.ajax({
                url: "get_images_for_folders",
                type: "GET",
                async: true,
                data: {
                    "form" : true
                },
                dataType: "html",
                success: function (data) {
                    $('#tab2').append(data);
                }
            });

        }
    });
}

function deleteCollection(id) {
    $.ajax({
        url: ace.path('delete_folder'),
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

function getImageById(id) {
    $.ajax({
        url: ace.path('get_image_by_id'),
        type: "POST",
        data: {
            'id': id
        },
        dataType: "html",
        success: function (data) {
            $('.videoRight').remove();
            $('#images-col').removeClass('col-md-12').addClass('col-md-6');
            $('.description').remove();
            $('#tab1').append(data);

            $('.search').animate({
                'width': "192px"
            }, 200);

            // $('.videoTtitle').css('maxWidth', '300px');
            $('.videoTtitle').css({
                'maxWidth': '305px',
                'width': 'calc(100% - 60px)'
            });

            // Changing columns
            $('.list_item').find('.col-md-2').removeClass('col-md-2').addClass('col-md-4');
            $('.list_item').find('.col-md-10').removeClass('col-md-10').addClass('col-md-8');
        }
    });
}


function editImage() {
    var ts = '0';
    // if (document.getElementById('thumbnail_source_1').checked) ts='1';
    $.ajax({
        url: ace.path('edit_image'),
        type: "POST",
        data: {
            'id': $( "input[name$='id']" ).val(),
            'title': $( "input[name$='title']" ).val(),
            'description': $( "textarea[name$='description']" ).val(),
            'collections': $("#collections").val()
            // 'thumbnail_source': ts

        },
        dataType: "json",
        success: function (data) {
            if(data.status) {
                $('.edited').html('<span class="fui-check"></span> Image successfully edited!');
                setInterval(function() {
                    window.location.replace("image_manager");
                }, 1500);
            }
        }
    });
}

function addSlide(){
    $.ajax({
        url: ace.path('add_to_slide'),
        type: "GET",
        async: true,
        data: {
            "form" : true
        },
        dataType: "html",
        success: function (data) {
            // $('#video-md-col').removeClass('col-md-6').addClass('col-md-4');
            // $('.description').addClass('hide');
            $('.plusPtnCol').fadeOut();
            $('#slides').removeClass('col-md-12').removeClass('col-md-6').addClass('col-md-4');

            $('.playlists-for-collection-by-id').remove();
            $('#editPlaylistHide').remove();
            $('#addPlaylistHide').remove();


            $('.searchPlay').animate({
                'width': "174px"
            }, 150);

            //$('.playlists').addClass('hide');
            $('.description').addClass('hide');

            $.ajax({
                url: "get_images_for_folders",
                type: "GET",
                async: true,
                data: {
                    "form" : true
                },
                dataType: "html",
                success: function (data) {
                    $('#add-playlist').after(data);
                }
            });
            $('#slides').after(data);
        }
    });
}

function addToSlidePost(){

    var title = $('#title').val();
    var description = $('#description').val();

    $.ajax({
        url: ace.path('add_to_slide'),
        type: "POST",
        data: {
            'title' : title,
            'description' : description
        },
        dataType: "json",
        success: function (data) {
            if(data.status) {
                var playlist = {};

                playlist.playlist_id = data.playlist_id;
                playlist.playlists = [];

                $('.add_videos_in_playlist').each(function(){
                    playlist.playlists.push($(this).data('video_id'));
                });

                $.ajax({
                    url: "insert_image_in_playlist",
                    type: "POST",
                    data: {
                        "playlist" : playlist
                    },
                    dataType: "json",
                    success: function (data) {
                        if(data.status) {
                            $('.edited').html('<span class="fui-check"></span> Playlist successfully added!');
                            var slide = data.slide;
                            var item = '';
                            if(slide !== ''){
                                item += '<div class="lists">';
                                item += '<section data-playlist_id="'+slide.id+'" class="list_item section_slide">';
                                    item += '<button id="'+slide.id+'" class="edit_playlist editDelete fr btn btn-block btn-lg btn-inverse" title="Edit playlist">';
                                        item += '<span class="fui-new"></span>';
                                    item += '</button>';
                                    item += '<button id="'+slide.id+'" class="delete_playlist editDelete fr btn btn-block btn-lg btn-danger" title="Delete playlist">';
                                        item += '<span class="fui-trash"></span>';
                                    item += '</button>';
                                    item += '<div class="clear"></div>';
                                    item += '<div class="row center-block">';
                                        item += '<div class="col-md-2 playlist_thumb">';
                                            if(slide.thumbnail_name != ''){
                                                item += '<img style="width:100%;" src="'+ slide.thumbnail_name +'">';
                                            }
                                            else{
                                                item += '<img style="width:100%;" src="http://speakingagainstabuse.com/wp-content/themes/AiwazMag/images/no-img.png">';
                                            }
                                        item += '</div>';
                                        item += '<div class="col-md-10 playlist_thumb">';
                                        item += '<h1 class="videoTtitle">'+slide.title+'</h1>';
                                        item += '<p class="duration">';
                                            item += '<img src="/images/time_icon.png" style="margin-top: -4px;"> '+slide.created_at+'';
                                        item += '</p>';
                                        item += '</div>';
                                    item += '</div>';
                                item += '</section>';
                                item += '</div>';
                                $('#slides').removeClass('col-md-6').removeClass('col-md-4').addClass('col-md-12');
                                $('#editPlaylistHide').remove();
                                $('#images-all').remove();
                                $('#addPlaylistHide').remove();
                                $('#slide_container').append(item);


                            }
                        }
                    }
                });
            }
        }
    });
}

function deleteSlide(id) {
    $.ajax({
        url: ace.path('delete_slide'),
        type: "POST",
        data: {
            "playlistId": id
        },
        success: function (data) {
            $('[data-playlist_id="' + id + '"]').fadeOut();
            $('#' + id).fadeOut();
        }
    });
}

function editSlidePost() {
    var id = $('#id').val();
    var title = $('#title').val();
    var description = $('#description').val();

    $.ajax({
        url: ace.path('edit_slide_post'),
        type: "POST",
        data: {
            'id' : id,
            'title' : title,
            'description' : description
        },
        dataType: "json",
        success: function (data) {
            if(data.status) {
                var playlist = {};

                playlist.playlist_id = data.playlist_id;
                playlist.playlists = [];

                $('.add_videos_in_playlist').each(function(){
                    playlist.playlists.push($(this).data('video_id'));
                });

                $.ajax({
                    url: "insert_image_in_playlist",
                    type: "POST",
                    data: {
                        "playlist" : playlist
                    },
                    dataType: "json",
                    success: function (data) {
                        if(data.status) {
                            $('.edited').html('<span class="fui-check"></span> Slide successfully edited!');
                            var item = data.slide;
                            $('.edited').html('<span class="fui-check"></span> Folder successfully edited!');
                            $('#slides').removeClass('col-md-4').addClass('col-md-12');
                            $('#editPlaylistHide,#addPlaylistHide,#images-all').remove();
                            $(".section_slide[data-playlist_id="+item.id+"]").find('.slideTitle').text(item.title);
                            // setInterval(function() {
                            //     window.location.replace("playlists");
                            // }, 1500);
                        }
                    }
                });

            }
        }
    });
}


function editSlide(id) {
    $.ajax({
        url: ace.path('get_slide_by_id'),
        type: "POST",
        async: true,
        data: {
            "form" : true,
            "id" : id
        },
        dataType: "html",
        success: function (data) {
            $('#video-md-col').removeClass('col-md-6').addClass('col-md-4');
            $('.description').addClass('hide');
            $('#slides').removeClass('col-md-12').removeClass('col-md-6').addClass('col-md-4');

            $('.playlists-for-collection-by-id').remove();
            $('#editPlaylistHide').remove();
            $('#addPlaylistHide').remove();

            $('.plusPtnCol').fadeOut();
            //$('.playlists').addClass('hide');
            //$('.description').addClass('hide');

            $('.searchPlay').animate({
                'width': "174px"
            }, 150);

            $.ajax({
                url: "get_images_for_folders",
                type: "GET",
                async: true,
                data: {
                    "form" : true
                },
                dataType: "html",
                success: function (data) {
                    $('#add-playlist').after(data);
                }
            });
            $( ".vod_playlist_content" ).remove();
            $('#slides').after(data);
        }
    });
}




$(document).ready(function () {
    $('#add-folder').click(function(){
        addCollection();
    });

    $(document).delegate('#add-to-folder', 'submit', function(event) {
        event.preventDefault();
        addCollectionPost();
    });

    $('.edit_image').click(function (event) {
        event.stopPropagation();
        event.preventDefault();
        $('.image_item').removeClass('active_item');
        $(this).parent().toggleClass('active_item');
        var id = $(this).attr('id');
        getImageById(id);
        return false;
    });

    $(document).delegate('#edit_image_form', 'submit', function(event) {
        event.stopPropagation();
        event.preventDefault();
        editImage();
        return false;
    });

    $(document).delegate('#edit_folder', 'submit', function(event) {
        event.preventDefault();
        editCollectionPost();
    });

    $('.edit_folder').click(function (event) {
        event.stopPropagation();
        event.preventDefault();
        $('.section_collections').removeClass('active_item');
        $(this).parent().toggleClass('active_item');
        var id = $(this).attr('id');
        editCollectionGet(id);
        return false;
    });
    $(document).on("click",".cancel_collection",function(event) {
        $('#images-all').remove();
        $('#upload').remove();
        $('#tabContainer').removeClass('col-md-4').addClass('col-md-12');
        $('.list_item').find('.col-md-4').removeClass('col-md-4').addClass('col-md-2');
        $('.list_item').find('.col-md-4').removeClass('col-md-4').addClass('col-md-10');
    })

    $('.delete_folder').click(function(event) {
        event.stopPropagation();
        event.preventDefault();
        var id = $(this).attr('id');
        if(confirm('are you sure?')) {
            deleteCollection(id);
        }
        return false;
    });

    $('#add-slide').click(function(event){
        event.stopPropagation();
        event.preventDefault();
        addSlide();
    });

    $(document).on("click","#addSlideBtn",function(event) {
        event.stopPropagation();
        event.preventDefault();
        addToSlidePost();
    });
    $(document).on("click","#editSlideBtn",function(event) {
        event.stopPropagation();
        event.preventDefault();
        editSlidePost();
    });

    $(document).on("click",".delete_slide",function(event) {
        event.stopPropagation();
        event.preventDefault();
        var id = $(this).attr('id');
        if(confirm('are you sure?')) {
            deleteSlide(id);
        }
        return false;
    });

    $('.edit_slide').click(function(event){
        event.stopPropagation();
        event.preventDefault();
        $('.section_slide').removeClass('active_item');
        $(this).parent().toggleClass('active_item');
        var id = $(this).attr('id');
        editSlide(id);
    });



});

