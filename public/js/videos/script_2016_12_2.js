var editVideo = {
    editVideo: function () {
        $.ajax({
            url: ace.path('edit_video'),
            type: "POST",
            data: {
                'id': $( "input[name$='id']" ).val(),
                'title': $( "input[name$='title']" ).val(),
                'description': $( "input[name$='description']" ).val(),
                'collections': $("#collections").val()
            },
            dataType: "json",
            success: function (data) {
                if(data.status) {
                    $('.edited').html('<span class="fui-check"></span> Video successfully edited!');
                    setInterval(function() {
                        window.location.replace("videos");
                    }, 1500);
                }
            }
        });
    }
}

var addVideo = {
    addVideo: function(form) {

        $.ajax({
            url: ace.path('addVideo'),
            type: "POST",
            data: {
                'title': form.find("input[name$='title']").val(),
                'description': form.find("input[name$='description']").val(),
                'collections': form.find("#collections").val(),
                'file_name': form.find("input[name$='file_name']").val(),
                'video_format': form.find("input[name$='video_format']").val(),
                'encoded_video_id': form.find("input[name$='encoded_video_id']").val()
            },
            dataType: "json",
            success: function(data) {
                if(data.status) {
                    form.parent().fadeOut("normal", function() {
                        form.parent().remove();
                    });
                }
            }
        });
    }
}

$(document).ready(function() {

    $('#edit_video').submit(function(event) {
        event.preventDefault();
        editVideo.editVideo();
    });

    $('#downloadedVideosAppend').delegate('.saveVideo', 'submit', function(event) {
        event.preventDefault();
        event.stopPropagation();
        addVideo.addVideo($(event.target));
        //addVideo.addVideo($(this).parent().parent());
    });
    

    
    
});
