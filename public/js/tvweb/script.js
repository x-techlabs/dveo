$(document).ready(function(){
    var deleteTvwebPlaylist = {
        deleteTvwebPlaylist: function(id) {
            $.ajax({
                url: ace.path('tvweb_delete_playlist'),
                type: "POST",
                data: {
                    "tvweb_playlistId": id
                },
                success: function (data) {
                    $('[data-playlist_id="' + id + '"]').fadeOut();
                    $('#' + id).fadeOut();
                    window.location.replace("tvweb_playlists");
                }
            });
        }
    }

    $('.tvweb_delete_playlist').click(function(event) {
        event.stopImmediatePropagation();
        var id = $(this).attr('id');
        if(confirm('are you sure ?')) {
        	deleteTvwebPlaylist.deleteTvwebPlaylist(id);
        }
        
        return false;
    });

    $('.master_loop').click(function(event){
        var id = $(this).attr('id');
        event.stopPropagation();
        $.ajax({
            url: ace.path('playlist_master_loop'),
            type: "POST",
            data: {
                "id" : id
            },
            dataType: "json",
            success: function (data) {
                if(data.status) {
                    $('.btn-primary').addClass('btn-warning').removeClass('btn-primary');
                    $('.duration .master_looped').remove();
                    if(data.loopOff) {
                        $('[data-playlist_id="' + id + '"]').find('.master_loop').addClass('btn-primary').removeClass('btn-warning');
                        $('.duration').append('<span class="master_looped">&nbsp;|&nbsp;Master looped</span>');
                    }
                }
            }
        });
    });
    
    var filenameSize = [];

    $(".amazon_playlist_logo").each(function(){
        var form = $(this);

        form.fileupload({
            url: form.attr('action'),
            type: 'POST',
            autoUpload: true,
            async: true,
            dataType: 'xml',
            add: function(event, data) {
                var filename = data.files[0].name.replace(/[^a-zA-Z0-9.]/g,'_')
                var filesiez = data.files[0].size;

                if(filenameSize.indexOf(filename[0] + filesiez) == -1) {
                    var exts = ['jpg', 'jpeg', 'png'];
                    // first check if file field has any value
                    if(filename) {
                        // split file name at dot
                        var get_ext = filename.split('.');
                        // reverse name to check extension
                        get_ext = get_ext.reverse();
                        // check file type is valid as given in 'exts' array
                        if($.inArray(get_ext[0].toLowerCase(), exts) > -1) {
                            $('.amazon_playlist_logo #fileupload').addClass('logoUploadAfter');
                            $('.logoLoader').height(100);

                            // Get the video format string from filename string
                            var pos = filename.lastIndexOf('.');
                            var videoFormat = filename.slice(pos + 1);
                            var dataSubmit = data;

                            // Get the policy  and signature
                            $.ajax({
                                url: "send_amazon_playlist_logo",
                                type: "POST",
                                async: true,
                                data: {
                                    "ext": get_ext[0],
                                    "tvweb_playlist_id": $("#tvweb_playlist_id").val()
                                },
                                dataType: "json",
                                success: function (data) {
                                	
                                    $("#key").val(data.filename);
                                    $("#policy").val(data.policy_encoded);
                                    $("#signature").val(data.signature);
                                    $('.amazon_playlist_logo input[name=_token]').remove();
                                    var amazon_filename = data.filename;
                                    var pos = amazon_filename.lastIndexOf('/')
                                    amazon_filename = amazon_filename.slice(pos + 1);
                                    pos = amazon_filename.lastIndexOf('.');
                                    amazon_filename = amazon_filename.slice(0, pos);

                                    amazonS3Upload.files[filename + filesiez] = {};
                                    amazonS3Upload.files[filename + filesiez].fileName = amazon_filename;
                                    amazonS3Upload.files[filename + filesiez].videoFormat = videoFormat;

                                    dataSubmit.submit();

                                }
                            });
                        } else {
                            console.log('no');
                        }
                    }
                    filenameSize.push(filename[0] + filesiez);
                }
            },
            send: function(e, data) {

            },
            progress: function(e, data){

            },
            fail: function(e, data) {
                console.log(e);
            },
            success: function(data) {
                //var url = $(data).find('Location').text();
                //$('#real_file_url').val(url);// Update the real input in the other form
                //$('#real_file_url').attr("src", url);
                //window.location.replace("tvweb_playlists");
            },
            done: function (event, data) {
                console.log(data);

                if($('.logo1').attr('src').indexOf('images/noLogo.png') !== -1) {
                    $('.logo1').attr('src', 'http://prolivestream.s3.amazonaws.com/logos/channel_' + ace.channel_id+'_tvweb_playlist_'+$("#tvweb_playlist_id").val());
                } else {
                	$('.logo1').attr('src', 'http://prolivestream.s3.amazonaws.com/logos/channel_' + ace.channel_id+'_tvweb_playlist_'+$("#tvweb_playlist_id").val() +'?'+ Math.random());

                    //$('.logo1').attr('src', $('.logo1').attr('src') + Math.random());
                }
                
                //$('.logo1').attr('src', 'http://prolivestream.s3.amazonaws.com/logos/channel_' + ace.channel_id+'_tvweb_playlist_'+$("#tvweb_playlist_id").val());

                
                $('.amazon_playlist_logo #fileupload').removeClass('logoUploadAfter');
                $('.logoLoader').height(0);

            }
        })
    });



    
});


