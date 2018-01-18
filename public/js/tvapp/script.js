$(document).ready(function(){
    var deleteTvappPlaylist = {
        deleteTvappPlaylist: function(id) {
            $.ajax({
                url: ace.path('tvapp_delete_playlist'),
                type: "POST",
                data: {
                    "tvapp_playlistId": id
                },
                success: function (data) {
                    $('[data-playlist_id="' + id + '"]').fadeOut();
                    $('#' + id).fadeOut();
                    window.location.replace("tvapp_playlists");
                }
            });
        }
    }

    // Cancel Subcription
    $('.cancel_sub').click(function(event) {
        var _this = $(this);
        var id = _this.data('id');
        var status = _this.parent().parent().parent().parent().find('.sub_status');
        if(id != ''){
            $.ajax({
                url: ace.path('cancel_subscription'),
                type: "POST",
                data: {
                    "id": id
                },
                success: function (data) {
                    console.log(data);
                    status.text('Cancelled');
                    _this.parent().hide();
                }
            });
        }

    });

    $('.tvapp_delete_playlist').click(function(event) {
        event.stopImmediatePropagation();
        var id = $(this).attr('id');
        if(confirm('are you sure ?')) {
        	deleteTvappPlaylist.deleteTvappPlaylist(id);
        }
        
        return false;
    });
	
	
	$(window).load(function(){
		setTimeout(function(){
			var count_for_top_shelf = 1
			var for_add_number_class = $(".for_add_number_class");
			var count_for_low_shelf = 2;
			if(for_add_number_class.length > 0){
				for(var i = 0 ; i < for_add_number_class.length ; i++){
					var parent_class_name = $(for_add_number_class[i]).parent().parent().attr('class');
					parent_class_name = parent_class_name.split(' ');
					if(parent_class_name[1] == 'top_shelf_part' ){
						var p = i + 1;
                        var number_row = $("<div class='div_for_top_shelf_number_row'>" + 1+"."+ p + "</div>")
                        $(for_add_number_class[i]).append(number_row);
					}
					else if(parent_class_name[1] == 'low_shelf_part'){
						 var number_row = $("<div class='div_for_top_shelf_number_row'>" + count_for_low_shelf + "</div>")
						$(for_add_number_class[i]).append(number_row);
						count_for_low_shelf += 1;
					}
				}
			}
		},1500)
		
	})
	
	   $(document).on("click",".btn_for_refresh_xml",function(){
        var channel_id = $(".channel_id_input").val();
        $.ajax({
            type:"post",
            url:ace.path('update_xml_btn'),
            data:{channel_id:channel_id},
            success:function(res){
				location.reload(true);
            }
        })
    })

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
	
	$(document).on('change','#playlist_type',function(){
        var playlist_type = $("#playlist_type").val();
        if(playlist_type == 6){
            $("#playlist_layout").val(2)
        }
    })
    
	var upload_banner_image_width;
	var upload_banner_image_height;
	var _URL = window.URL || window.webkitURL;

	$("#fileupload1").change(function(e) {
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
	
	 $(".amazon_playlist_banner").each(function(){
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
                                form.find('fileupload1').addClass('logoUploadAfter');
                                // Get the video format string from filename string
                                var pos = filename1.lastIndexOf('.');
                                var videoFormat = filename1.slice(pos + 1);
                                var dataSubmit = data1;
								setTimeout(function(){			
									if(upload_banner_image_width == '1680' && upload_banner_image_height =='366'){				
										// Get the policy  and signature
										$.ajax({
											url: "send_amazon_playlist_banner",
											type: "POST",
											async: true,
											data: {
												"ext": get_ext[0],
												"tvapp_playlist_id": $("#tvapp_playlist_id").val()
											},
											dataType: "json",
											success: function (data2) {
												form.find("#key1").val(data2.filename);
												form.find("#policy1").val(data2.policy_encoded);
												form.find("#signature1").val(data2.signature);
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
										alert('Playlist banner image should be 1680x366');
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
                  

                    if($('.banner1').attr('src').indexOf('images/noLogo.png') !== -1) {
                       $('.banner1').attr('src', 'https://s3.amazonaws.com/aceplayout/banners/channel_' + ace.channel_id+'_tvapp_playlist_'+$("#tvapp_playlist_id").val()+'.jpg');
                    } else {
                       $('.banner1').attr('src', 'https://s3.amazonaws.com/aceplayout/banners/channel_' + ace.channel_id+'_tvapp_playlist_'+$("#tvapp_playlist_id").val() +'.jpg?'+ Math.random());

                        //$('.banner1').attr('src', $('.banner1').attr('src') + Math.random());
                    }
                    
                    //$('.banner1').attr('src', 'http://prolivestream.s3.amazonaws.com/logos/channel_' + ace.channel_id+'_tvapp_playlist_'+$("#tvapp_playlist_id").val());
                    
                   $('.amazon_playlist_banner #fileupload1').removeClass('logoUploadAfter');
                }
            })
    })
	
	$("#fileupload4").change(function(e) {
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
	
	
	
	$(".send_amazon_mobileweb_image_for_playlist").each(function(){
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
							form.find('fileupload4').addClass('logoUploadAfter');
							// Get the video format string from filename string
							var pos = filename1.lastIndexOf('.');
							var videoFormat = filename1.slice(pos + 1);
							var dataSubmit = data1;
							setTimeout(function(){
								if(upload_banner_image_width1 == '1680' && upload_banner_image_height1 =='366'){
									// Get the policy  and signature
									$.ajax({
										url: "send_amazon_mobileweb_image_for_playlist",
										type: "POST",
										async: true,
										data: {
											"ext": get_ext[0],
											"tvapp_playlist_id": $("#tvapp_playlist_id").val()
										},
										dataType: "json",
										success: function (data2) {
											form.find("#key4").val(data2.filename);
											form.find("#policy4").val(data2.policy_encoded);
											form.find("#signature4").val(data2.signature);
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
					$('#mobileweb_image').attr('src', 'https://s3.amazonaws.com/aceplayout/banners/channel_' + ace.channel_id+'_mobileweb_playlist_'+$("#tvapp_playlist_id").val()+'.jpg');
				} else {
					$('#mobileweb_image').attr('src', 'https://s3.amazonaws.com/aceplayout/banners/channel_' + ace.channel_id+'_mobileweb_playlist_'+$("#tvapp_playlist_id").val() +'.jpg?'+ Math.random());
				}
				$('#fileupload4').removeClass('logoUploadAfter');
			}
		})
	})
	 
	 
	
	 
    $(".amazon_playlist_logo").each(function(){
		var filenameSize = [];
		
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
                            form.find('#fileupload').addClass('logoUploadAfter');
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
                                    "tvapp_playlist_id": $("#tvapp_playlist_id").val()
                                },
                                dataType: "json",
                                success: function (data) {
                                	
                                    form.find("#key").val(data.filename);
                                    form.find("#policy").val(data.policy_encoded);
                                    form.find("#signature").val(data.signature);
                                    form.find('input[name=_token]').remove();
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
                //window.location.replace("tvapp_playlists");
            },
            done: function (event, data) {
                console.log(data);

                if($('.logo1').attr('src').indexOf('images/noLogo.png') !== -1) {
                    $('.logo1').attr('src', 'https://s3.amazonaws.com/aceplayout/logos-poster/channel_' + ace.channel_id+'_tvapp_playlist_'+$("#tvapp_playlist_id").val()+'.jpg');
                } else {
                	$('.logo1').attr('src', 'https://s3.amazonaws.com/aceplayout/logos-poster/channel_' + ace.channel_id+'_tvapp_playlist_'+$("#tvapp_playlist_id").val()+'.jpg'+'?'+ Math.random());

                    //$('.logo1').attr('src', $('.logo1').attr('src') + Math.random());
                }
                
                //$('.logo1').attr('src', 'http://prolivestream.s3.amazonaws.com/logos/channel_' + ace.channel_id+'_tvapp_playlist_'+$("#tvapp_playlist_id").val());

                
                $('.amazon_playlist_logo #fileupload').removeClass('logoUploadAfter');
                $('.logoLoader').height(0);

            }
        })
    });


	  
    
});


