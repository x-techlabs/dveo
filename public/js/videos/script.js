var editVideo = {

    editVideo: function () {
        var ts = '0';
        if (document.getElementById('thumbnail_source_1').checked) ts='1';
        $.ajax({
            url: ace.path('edit_video'),
            type: "POST",
            data: {
                'id': $( "input[name$='id']" ).val(),
                'title': $( "input[name$='title']" ).val(),
                'description': $( "textarea[name$='description']" ).val(),
                'collections': $("#collections").val(),
                'duration': $("#duration").val(),
                'viewing': $('#viewing').val(),
                'thumbnail_source': ts,
				'tags': $('#tags').val(),
				'show': $('#show').val(),
				'season': $('#season').val(),
				'episod': $('#episod').val()

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

        source = form.find("input[name$='source']").val(); 
        $('#addVideoLoader').show(); 
        $.ajax({
            url: ace.path('addVideo'),
            type: "POST",
            data: {
                'title': form.find("input[name$='title']").val(),
                'description': form.find("textarea[name$='description']").val(),
                'collections': form.find("#collections").val(),
                'file_name': form.find("input[name$='file_name']").val(),
                'video_format': form.find("input[name$='video_format']").val(),
                'encoded_video_id': form.find("input[name$='encoded_video_id']").val(),
                'source': source,
            },
            dataType: "json",
            success: function(data) {
                if(data.status) {
                    $('#errorMsg').hide();
                    form.parent().fadeOut("normal", function() {
                        form.parent().remove();

                        $('#addVideoLoader').hide(); 

                        if (source != 'internal')
                        {
                            url = window.location.href;
                            url2 = url.replace('uploadLink', 'videos');
                            window.location = url2;
                        }
                    });
                }
                else{
                    var message = data.message;
                    $('#errorMsg').text(message).show();
                }
            }
        });
    }
}
var upload_banner_image_width;
var upload_banner_image_width1;
var upload_banner_image_height;
var upload_banner_image_height1;
var _URL = window.URL || window.webkitURL;
var _URL1 = window.URL || window.webkitURL;

$("#fileupload2").change(function(e) {
	var image, file;
	if ((file = this.files[0])) {
		image = new Image();
		image.onload = function() {
			upload_banner_image_height = this.height;
			upload_banner_image_width = this.width;
		};
		image.src = _URL.createObjectURL(file);
	}
})
$("#fileupload3").change(function(e) {
	var image, file;
	if ((file = this.files[0])) {
		image = new Image();
		image.onload = function() {
			upload_banner_image_height1 = this.height;
			upload_banner_image_width1 = this.width;
		};
		image.src = _URL1.createObjectURL(file);
	}
})

$(document).ready(function() {
	//Poster image
	$(".amazon_playlist_poster_image").each(function(){
		var filenameSize_image =[];
		var form = $(this);
		form.fileupload({
			url: form.attr('action'),
			type: 'POST',
			autoUpload: true,
			async: true,
			dataType: 'xml',
			add: function(event, data1) {
				var filename1 = data1.files[0].name.replace(/[^a-zA-Z0-9.]/g,'_')
				var filesiez1 = data1.files[0].size;
				if(filenameSize_image.indexOf(filename1[0] + filesiez1) == -1) {
					var exts = ['jpg', 'jpeg', 'png'];
					// first check if file field has any value
					if(filename1) {
						// split file name at dot
						var get_ext = filename1.split('.');
						// reverse name to check extension
						get_ext = get_ext.reverse();
						// check file type is valid as given in 'exts' array
						if($.inArray(get_ext[0].toLowerCase(), exts) > -1) {
							form.find('fileupload5').addClass('logoUploadAfter');
							// Get the video format string from filename string
							var pos = filename1.lastIndexOf('.');
							var videoFormat = filename1.slice(pos + 1);
							var dataSubmit = data1;
							// setTimeout(function(){
								// Get the policy  and signature
								$.ajax({
									url: "send_amazon_poster_image",
									type: "POST",
									async: true,
									data: {
										"ext": get_ext[0],
										"video_id": $("#video_id").val()
									},
									dataType: "json",
									success: function (data2) {
										form.find("#key5").val(data2.filename);
										form.find("#policy5").val(data2.policy_encoded);
										form.find("#signature5").val(data2.signature);
										form.find('input[name=_token]').remove();
										var amazon_filename = data2.filename;
										var pos = amazon_filename.lastIndexOf('/')
										amazon_filename = amazon_filename.slice(pos + 1);
										pos = amazon_filename.lastIndexOf('.');
										amazon_filename = amazon_filename.slice(0, pos);

										amazonS3Upload.files[filename1 + filesiez1] = {};
										amazonS3Upload.files[filename1 + filesiez1].fileName = amazon_filename;
										amazonS3Upload.files[filename1 + filesiez1].videoFormat = videoFormat;

										dataSubmit.submit();
									}
								});
							// },500)
						} else {
							console.log('no');
						}
					}
					filenameSize_image.push(filename1[0] + filesiez1);
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
				//window.location.replace("tvapp_playlists");
			},
			done: function (event, data) {
				if($('#poster_image').attr('src').indexOf('images/noLogo.png') !== -1) {
					$('#poster_image').attr('src', 'https://s3.amazonaws.com/aceplayout/banners/channel_' + ace.channel_id+'_poster_video_'+$("#video_id").val()+'.jpg');
				} else {
					$('#poster_image').attr('src', 'https://s3.amazonaws.com/aceplayout/banners/channel_' + ace.channel_id+'_poster_video_'+$("#video_id").val() +'.jpg?'+ Math.random());
				}
				$('#fileupload5').removeClass('logoUploadAfter');
			}
		})
	})

	// Tv App image
	$(".amazon_playlist_tvapp_image").each(function(){
		var filenameSize_image =[];
		var form = $(this);
		form.fileupload({
			url: form.attr('action'),
			type: 'POST',
			autoUpload: true,
			async: true,
			dataType: 'xml',
			add: function(event, data1) {
				var filename1 = data1.files[0].name.replace(/[^a-zA-Z0-9.]/g,'_')
				var filesiez1 = data1.files[0].size;
				if(filenameSize_image.indexOf(filename1[0] + filesiez1) == -1) {
					var exts = ['jpg', 'jpeg', 'png'];
					// first check if file field has any value
					if(filename1) {
						// split file name at dot
						var get_ext = filename1.split('.');
						// reverse name to check extension
						get_ext = get_ext.reverse();
						// check file type is valid as given in 'exts' array
						if($.inArray(get_ext[0].toLowerCase(), exts) > -1) {
							form.find('fileupload2').addClass('logoUploadAfter');
							// Get the video format string from filename string
							var pos = filename1.lastIndexOf('.');
							var videoFormat = filename1.slice(pos + 1);
							var dataSubmit = data1;
							setTimeout(function(){
								if(upload_banner_image_width == '1680' && upload_banner_image_height =='366'){
									// Get the policy  and signature
									$.ajax({
										url: "send_amazon_tvapp_image",
										type: "POST",
										async: true,
										data: {
											"ext": get_ext[0],
											"video_id": $("#video_id").val()
										},
										dataType: "json",
										success: function (data2) {
											form.find("#key2").val(data2.filename);
											form.find("#policy2").val(data2.policy_encoded);
											form.find("#signature2").val(data2.signature);
											form.find('input[name=_token]').remove();
											var amazon_filename = data2.filename;
											var pos = amazon_filename.lastIndexOf('/')
											amazon_filename = amazon_filename.slice(pos + 1);
											pos = amazon_filename.lastIndexOf('.');
											amazon_filename = amazon_filename.slice(0, pos);

											amazonS3Upload.files[filename1 + filesiez1] = {};
											amazonS3Upload.files[filename1 + filesiez1].fileName = amazon_filename;
											amazonS3Upload.files[filename1 + filesiez1].videoFormat = videoFormat;

											dataSubmit.submit();
										}
									});
								}
								else{
									alert('TV app banner image should be 1680x366');
								}
							},500)
						} else {
							console.log('no');
						}
					}
					filenameSize_image.push(filename1[0] + filesiez1);
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
				//window.location.replace("tvapp_playlists");
			},
			done: function (event, data) {
				if($('#tvapp_image').attr('src').indexOf('images/noLogo.png') !== -1) {
					$('#tvapp_image').attr('src', 'https://s3.amazonaws.com/aceplayout/banners/channel_' + ace.channel_id+'_tvapp_video_'+$("#video_id").val()+'.jpg');
				} else {
					$('#tvapp_image').attr('src', 'https://s3.amazonaws.com/aceplayout/banners/channel_' + ace.channel_id+'_tvapp_video_'+$("#video_id").val() +'.jpg?'+ Math.random());
				}
				$('#fileupload2').removeClass('logoUploadAfter');
			}
		})
	})

	// Mobile Web image
	$(".amazon_playlist_mobileweb_image").each(function(){
		var filenameSize_image =[];
		var form = $(this);
		form.fileupload({
			url: form.attr('action'),
			type: 'POST',
			autoUpload: true,
			async: true,
			dataType: 'xml',
			add: function(event, data1) {
				var filename1 = data1.files[0].name.replace(/[^a-zA-Z0-9.]/g,'_')
				var filesiez1 = data1.files[0].size;
				if(filenameSize_image.indexOf(filename1[0] + filesiez1) == -1) {
					var exts = ['jpg', 'jpeg', 'png'];
					// first check if file field has any value
					if(filename1) {
						// split file name at dot
						var get_ext = filename1.split('.');
						// reverse name to check extension
						get_ext = get_ext.reverse();
						// check file type is valid as given in 'exts' array
						if($.inArray(get_ext[0].toLowerCase(), exts) > -1) {
							form.find('fileupload3').addClass('logoUploadAfter');
							// Get the video format string from filename string
							var pos = filename1.lastIndexOf('.');
							var videoFormat = filename1.slice(pos + 1);
							var dataSubmit = data1;
							setTimeout(function(){
								if(upload_banner_image_width1 == '1680' && upload_banner_image_height1 =='366'){
									// Get the policy  and signature
									$.ajax({
										url: "send_amazon_mobileweb_image",
										type: "POST",
										async: true,
										data: {
											"ext": get_ext[0],
											"video_id": $("#video_id").val()
										},
										dataType: "json",
										success: function (data2) {
											form.find("#key3").val(data2.filename);
											form.find("#policy3").val(data2.policy_encoded);
											form.find("#signature3").val(data2.signature);
											form.find('input[name=_token]').remove();
											var amazon_filename = data2.filename;
											var pos = amazon_filename.lastIndexOf('/')
											amazon_filename = amazon_filename.slice(pos + 1);
											pos = amazon_filename.lastIndexOf('.');
											amazon_filename = amazon_filename.slice(0, pos);

											amazonS3Upload.files[filename1 + filesiez1] = {};
											amazonS3Upload.files[filename1 + filesiez1].fileName = amazon_filename;
											amazonS3Upload.files[filename1 + filesiez1].videoFormat = videoFormat;

											dataSubmit.submit();
										}
									});
								}
								else{
									alert('Mobile-Web image should be 1680x366');
								}
							},500)
						} else {
							console.log('no');
						}
					}
					filenameSize_image.push(filename1[0] + filesiez1);
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
				//window.location.replace("tvapp_playlists");
			},
			done: function (event, data) {
				if($('#mobileweb_image').attr('src').indexOf('images/noLogo.png') !== -1) {
					$('#mobileweb_image').attr('src', 'https://s3.amazonaws.com/aceplayout/banners/channel_' + ace.channel_id+'_mobileweb_video_'+$("#video_id").val()+'.jpg');
				} else {
					$('#mobileweb_image').attr('src', 'https://s3.amazonaws.com/aceplayout/banners/channel_' + ace.channel_id+'_mobileweb_video_'+$("#video_id").val() +'.jpg?'+ Math.random());
				}
				$('#fileupload3').removeClass('logoUploadAfter');
			}
		})
	})
	// End Mobile Web image

	$('#edit_video').submit(function(event) {
        event.preventDefault();
        editVideo.editVideo();
    });
	$('#tags').select2({ width: '100%',dropdownAutoWidth : true });
	$('#show').select2({ width: '100%',dropdownAutoWidth : true });
    $('#downloadedVideosAppend').delegate('.saveVideo', 'submit', function(event) {
        event.preventDefault();
        event.stopPropagation();
        addVideo.addVideo($(event.target));
        //addVideo.addVideo($(this).parent().parent());
    });
});
