var amazonS3Upload = {
    files: {},

    cancelUpload : function() {
        if(calculationRequest != null)
            calculationRequest.abort();
        var calculationRequest = $.get("/tests/calc.php", function(data, textStatus)
        {
            alert(data);
        });
    },

    loadVideoForm: function(title, size){
        var thisObj = this;
        $.ajax({
            url: "add_video",
            type: "GET",
            async: true,
            data: {
                "form" : true,
                "title" : title
            },
            dataType: "html",
            success: function (data) {
                var d = $(data);

                d.find('#filename').val(thisObj.files[title + size].fileName);
                d.find('#video-format').val(thisObj.files[title + size].videoFormat);

                var videoName = title.split(".");
                $('#' + videoName[0] + size).removeAttr('class').html(d);
            }
        });
    }
};

var deleteImage = {
    deleteImage: function (id) {
        $.ajax({
            url: ace.path('delete_image'),
            type: "POST",
            data: {
                'id': id
            },
            dataType: "json",
            success: function (data) {
                if(data.status) {
                    $('*[data-img_id="' + id + '"]').fadeOut();
                }
            }
        });
    }
}


$(function() {
    $('.delete_image').click(function (event) {
        event.stopPropagation();
        event.preventDefault();
        var id = $(this).attr('id');
        if(confirm('are you sure?')) {
            deleteImage.deleteImage(id);
        }
        return false;
    });
    var filenameSize = [];

    $(".amazon_form").each(function(){
        var form = $(this);
        var perc = {};

        form.fileupload({
            url: form.attr('action'),
            type: 'POST',
            autoUpload: true,
            async: true,
            dataType: 'xml', // This is really important as s3 gives us back the url of the file in a XML document
            add: function (event, data) {
                //$('.amazon_form').hide();
                //$('#upload-files').removeClass('hide');
                // Get the video full name
                var filename = data.files[0].name.replace(/[^a-zA-Z0-9.]/g,'_')
                var filesiez = data.files[0].size;

                if(filenameSize.indexOf(filename[0] + filesiez) == -1) {
                    var exts = ['flv', 'avi', 'mov', 'mpg', 'wmv', 'm4v', 'mp3', 'mp4', 'wma', '3gp'];
                    // first check if file field has any value
                    if (filename) {
                        // split file name at dot
                        var get_ext = filename.split('.');
                        // reverse name to check extension
                        get_ext = get_ext.reverse();
                        // check file type is valid as given in 'exts' array
                        if ($.inArray(get_ext[0].toLowerCase(), exts) > -1) {
                            // Get the video format string from filename string
                            var pos = filename.lastIndexOf('.');
                            var videoFormat = filename.slice(pos + 1);
                            var dataSubmit = data;

                            // Get the policy  and signature
                            $.ajax({
                                url: "send_amazon",
                                type: "POST",
                                async: true,
                                data: {
                                    "video_format": videoFormat
                                },
                                dataType: "json",
                                success: function (data) {
                                    // Percentage
                                    var videoName = filename.split(".");
                                    $('#videoFormAppend').append('<div class="downloader" id="' + videoName[0] + filesiez + '">' +
                                    '<div class="downloading-progress">' +
                                    '<div class="downloading-progress-bar" data-value="0" data-max="100"></div>' +
                                    '</div>' +
                                    '<div class="percentage">0%</div>' +
                                    '<div class="clear"></div>' +
                                    '</div>');

                                    $("#key").val(data.filename);
                                    $("#policy").val(data.policy_encoded);
                                    $("#signature").val(data.signature);
                                    $('.amazon_form input[name=_token]').remove();
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
                // This is what makes everything really cool, thanks to that callback
                // you can now update the progress bar based on the upload progress
                var percent = Math.round((data.loaded / data.total) * 100);

                var videoName = data.files[0].name.replace(/[^a-zA-Z0-9.]/g,'_').split(".");
                $('#' + videoName[0] + data.files[0].size + ' .downloading-progress-bar').attr('data-value', percent).css("width", percent + '%');
                $('#' + videoName[0] + data.files[0].size + ' .percentage').html(percent + '%');
            },
            fail: function(e, data) {
                console.log(e);

            },
            success: function(data) {

                // Here we get the file url on s3 in an xml doc
                var url = $(data).find('Location').text();

                $('#real_file_url').val(url);// Update the real input in the other form
            },
            done: function (event, data) {
                console.log(data);

                amazonS3Upload.loadVideoForm(data.files[0].name.replace(/[^a-zA-Z0-9.]/g,'_'), data.files[0].size);
            }
        })
    });
});


