$(function(){
    var filenameSize = [];
	// Show
	$('.editShow').click(function () {
		var show_id = $(this).data('id');
		var _modal = $('#editShowModal');
		if(show_id != ''){
			$.ajax({
				url: ace.path('getShow'),
				type: 'POST',
				dataType: 'json',
				data: {show_id: show_id },
				success: function (data) {
					if(data.success){
						var result = data.result;
						$('#show_id').val(result.id);
						$('#editshowname').val(result.name);
						_modal.modal('show');
					}
				}
			})
		}
	});
	$('.deleteShow').click(function () {
		var _this = $(this);
		var show_id = $(this).data('id');
		if(show_id != '') {
			$.ajax({
				url: ace.path('deleteShow'),
				type: 'POST',
				dataType: 'json',
				data: {show_id: show_id},
				success: function (data) {
					if (data.success) {
						_this.parent().parent().remove();
					}
				}
			})
		}
	});
	$('.editTag').click(function () {
		var tag_id = $(this).data('id');
		var _modal = $('#editTagModal');
		if(tag_id != ''){
			$.ajax({
				url: ace.path('getTag'),
				type: 'POST',
				dataType: 'json',
				data: {tag_id: tag_id },
				success: function (data) {
					if(data.success){
						var result = data.result;
						$('#tag_id').val(result.id);
						$('#edittagname').val(result.name);
						_modal.modal('show');
					}
				}
			})
		}
	});
	$('.deleteTag').click(function () {
		var _this = $(this);
		var tag_id = $(this).data('id');
		console.log(tag_id);
		if(tag_id != '') {
			$.ajax({
				url: ace.path('deleteTag'),
				type: 'POST',
				dataType: 'json',
				data: {tag_id: tag_id},
				success: function (data) {
					if (data.success) {
						_this.parent().parent().remove();
					}
				}
			})
		}
	});
    // Distros script
    $('.display_distro').click(function(){
        var _this = $(this);
        var option = _this.val();
        var distro = _this.data('distro');
        if(option == 'yes'){
            _this.parent().parent().parent().next().show();
            $.ajax({
                url: ace.path('display_distro'),
                type: 'POST',
                data: {distro: distro, option : option},
                success: function (data) {
                    var response = data;
                    if(response == 'success'){
                        console.log('.top_left_block .'+distro+'_logo');
                        $('.top_left_block .'+distro+'_logo').show();
                    }
                }
            });
        }
        else{
            _this.parent().parent().parent().next().hide();
            $.ajax({
                url: ace.path('display_distro'),
                type: 'POST',
                data: {distro: distro, option : option},
                success: function (data) {
                    var response = data;
                    if(response == 'success'){
                        $('.top_left_block .'+distro+'_logo').hide();
                    }
                }
            });
        }
    });


    setTimeout(function(){
        $('.status_failed').hide();
        $('.status_success').hide();
    }, 6000);

    $('#chkAutoQuality').click(function () {
        var channel_id = $('#youtube_channelId').val();
        var quality;
        if($(this).is(':checked')){
            quality = 1;
        }
        else{
            quality = 0;
        }
        if ($('#download_all').length && channel_id !== '') {
            var format = $('#cbQuality').val();
            var all_url = ace.path('download_all')+"/"+channel_id+"/"+format+"/"+quality;
            $('#download_all').attr('href',all_url);
        }
    });
    $('#cbQuality').change(function () {
        var channel_id = $('#youtube_channelId').val();
        var quality;
        if($('#chkAutoQuality').is(':checked')){
            quality = 1;
        }
        else{
            quality = 0;
        }
        if ($('#download_all').length && channel_id !== '') {
            var format = $(this).val();
            var all_url = ace.path('download_all')+"/"+channel_id+"/"+format+"/"+quality;
            $('#download_all').attr('href',all_url);
        }

    });

    $('#import_btn').click(function(event){
        event.preventDefault();
        event.stopPropagation();
        var channel_id = $('#youtube_channelId').val();
        $('#video_loading').show();
        if(channel_id !== ''){
            var format = $("#cbQuality option:selected").text();
            var format_val = $("#cbQuality").val();
            var quality;
            if($('#chkAutoQuality').is(':checked')){
                quality = 1;
            }
            else{
                quality = 0;
            }
            $.ajax({
                url: ace.path('import_videos'),
                type: 'POST',
                data: {channel_id: channel_id},
                success: function (data) {
                    var response = JSON.parse(data);
                    $('#video_loading').hide();
                    if(response.success){
                        var row = '';
                        var video_format;
                        var all_url = ace.path('download_all')+"/"+channel_id+"/"+format_val+"/"+quality;
                        $('#download_all').attr('href',all_url).show();
                        $.each(response.videos, function( index, value ) {
                            var btn = '';
                            var download_url = ace.path('download_video')+"/"+value.id+"/"+format_val;
                            var step = index+1;
                            // console.log(response.videos);
                            if(value.id == '' || (typeof value.formats === 'undefined')){
                                btn = '<a href = "javascript:void(0)" class = "btn btn-danger" type = "button">Not found</a>';
                            }
                            else if(!$('#chkAutoQuality').is(':checked') && value.id !== '' && !(format_val in value.formats)){
                                btn = '<a href = "javascript:void(0)" class = "btn btn-danger" type = "button">Not found</a>';
                            }
                            else if($('#chkAutoQuality').is(':checked') && value.id !== '' && !(format_val in value.formats)){
                                if('medium-mp4' in value.formats){
                                    video_format = 'MP4 360P';
                                    download_url = ace.path('download_video')+"/"+value.id+"/"+'medium-mp4';
                                    btn = '<a href = "'+download_url+'" class = "btn btn-default" type = "button"><i class = "fa fa-sort-amount-desc"></i>'+video_format+'</a>';
                                }
                                else{
                                    if('medium-webm' in value.formats){
                                        video_format = 'WebM 360P';
                                        download_url = ace.path('download_video')+"/"+value.id+"/"+'medium-webm';
                                        btn = '<a href = "'+download_url+'" class = "btn btn-default" type = "button"><i class = "fa fa-sort-amount-desc"></i>'+video_format+'</a>';
                                    }
                                    else{
                                        if('small-3gpp' in value.formats){
                                            video_format = '3GP 240P';
                                            download_url = ace.path('download_video')+"/"+value.id+"/"+'small-3gpp';
                                            btn = '<a href = "'+download_url+'" class = "btn btn-default" type = "button"><i class = "fa fa-sort-amount-desc"></i>'+video_format+'</a>';
                                        }
                                        else{
                                            btn = '<a href = "javascript:void(0)" class = "btn btn-danger" type = "button">Not found</a>';
                                        }
                                    }
                                }
                            }
                            else{
                                btn = '<a href="'+download_url+'" class="btn btn-default" type="button">'+format+'</a>';
                            }
                            row += '<tr>';
                            row += '<td>'+step+'</td>';
                            row += '<td><img src = "'+value.thumbnails.medium+'"></td>';
                            row += '<td>'+value.title+'</td>';
                            row += '<td>'+btn+'</td>';
                            // row += '<td><a href="'+download_url+'" class="btn btn-default" type="button">'+format+'</a></td>';
                            row += '</tr>';
                        });

                        $('#channel_videos tbody').empty().append(row);
                        $('#channel_videos').show();
                        row = '';

                    }
                    else{
                        $('.status_failed').html('No videos found').show();
                    }
                }
            });
        }
        else{
            $('.status_failed').html('Channel ID is missing').show();
        }
        return false;
    });
    
    // Chargebee scripts
    $('.show_modal').click(function(event) {
        var action = $(this).data('action');
        if(action != ''){
            $.get('show_modal/' + action, function( data ) {
                $('#chargebeeModal').modal();
                $('#chargebeeModal').on('shown.bs.modal', function(){
                    $('#chargebeeModal .load_modal').html(data);
                });
                $('#chargebeeModal').on('hidden.bs.modal', function(){
                    $('#chargebeeModal .modal-body').data('');
                });
            });
            
        }
    });

    // Live monitors

    $('.delete_stream').click(function(event) {
        var stream_id = $(this).data('id');
        var element = $(this);
        console.log(stream_id);
        if(stream_id != ''){
            $.ajax({
                url: ace.path('delete_stream'),
                type: 'POST',
                data: {stream_id: stream_id},
                success: function (data) {
                    var response = JSON.parse(data);
                    if(response.success){
                        element.parent().parent().remove();
                    }
                }
            });
        }
    });

    // Update stream


    $('.edit_stream').click(function(event) {
        event.preventDefault();
        var stream_id = $(this).data('id');
        var url = $(this).data('url');
        var title = $(this).parent().parent().find('.stream_title').text();
        var element = $(this);
        if(title != '' && url != ''){
            $('#stream_id').val(stream_id);
            $('#monitorTitle').val(title);
            $('#monitorStream').val(url);
            $('#updateMonitor').modal('show');
        }
    });



    function validateEmail(email) {
      var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(email);
    }

    // Update account
    $(document).on("submit", "#updateAccount", function (e) {
        e.preventDefault();
        var form = $(this)[0];

        var formData = new FormData(form);
        var email = $('#email').val();
        var email = $('#email').val();
        var valid = true;
        if(email != ''){
            if(validateEmail(email)){
                valid = true;
                $('.email_error').text('');
            }
            else{
                valid = false;
                $('.email_error').text('Email is not valid');
            }
        }

        $('#updateAccount input').each(function(index, el) {
            var name = $(this).attr('name');
            formData.append($(this).attr('name'), $(this).val()); 
        });

        if(valid){
            $.ajax({
                url: ace.path('updateAccount'),
                data: formData,
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    location.reload();
                }
            });
        }
        return false;
    });

    // Update billing info
    $(document).on("submit", "#updateBilling", function (e) {
        e.preventDefault();
        var form = $(this)[0];

        var formData = new FormData(form);
        var email = $('#email').val();
        var email = $('#email').val();
        var valid = true;
        if(email != ''){
            if(validateEmail(email)){
                valid = true;
                $('.email_error').text('');
            }
            else{
                valid = false;
                $('.email_error').text('Email is not valid');
            }
        }

        $('#updateBilling input').each(function(index, el) {
            var name = $(this).attr('name');
            formData.append($(this).attr('name'), $(this).val()); 
        });

        if(valid){
            $.ajax({
                url: ace.path('updateBilling'),
                data: formData,
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    location.reload();
                },
                error: function(xhr, status, error) {
                  var err = eval("(" + xhr.responseText + ")");
                  $('.form_error').text(err.error.message);
                }
                
            });
        }
        return false;
    });
    // Credit card
    $(document).on("submit", "#payment_method", function (e) {
        e.preventDefault();
        var valid = true;
        var form = $(this)[0];
        var card_number = $('#card_number').val();
        var cvv = $('#cvv').val();
        var card_action = $('#card_action').val();
        if(card_action == 'add'){
            
            if(card_number !== ''){
                if(card_number.length < 13 || card_number.length > 19){
                    valid = false;
                    $('.card_error').text('Card number is not valid');
                }
                else{
                    valid = true;
                    $('.card_error').text('');
                }

                if(cvv !== ''){
                    if(!validateCVV(card_number,cvv)){
                        valid = false;
                        $('.cvv_error').text('CVV is not valid');
                    }
                    else{
                        valid = true;
                        $('.cvv_error').text('');
                    }
                    valid = true;
                    $('.cvv_error').text('');
                }
                else{
                    valid = false;
                    $('.cvv_error').text('CVV is required');
                }
                valid = true;
                $('.card_error').text('');
            }
            else{
                valid = false;
                $('.card_error').text('Card number is required');
            }
        }

        var formData = new FormData(form);
        var type = cardType(card_number);
        $('#payment_method input').each(function(index, el) {
            var name = $(this).attr('name');
            formData.append($(this).attr('name'), $(this).val());
        });

        if(valid){
            $.ajax({
                url: ace.path('payment_method'),
                data: formData,
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    // console.log(data);
                    location.reload();
                },
                error: function(xhr, status, error) {
                  var err = eval("(" + xhr.responseText + ")");
                  $('.form_error').text(err.error.message);
                }
            });
        }
        return false;
    });


    function validateCVV($cardNumber, $cvv)
    {
        // Get the first number of the credit card so we know how many digits to look for
        var $firstnumber = Number($cardNumber.substr(0, 1));
        if ($firstnumber === 3)
        {
            if (!$cvv.match(/^\d{4}$/))
            {
                // The credit card is an American Express card but does not have a four digit CVV code
                return false;
            }
        }
        else if (!$cvv.match(/^\d{3}$/))
        {
            // The credit card is a Visa, MasterCard, or Discover Card card but does not have a three digit CVV code
            return false;
        }
        return true;
    }

    function cardType(number)
    {
        // visa
        var re = new RegExp("^4");
        if (number.match(re) != null)
            return "Visa";

        // Mastercard
        re = /^(?:5[1-5][0-9]{14})$/;
        if (number.match(re) != null)
            return "Mastercard";

        // AMEX
        re = new RegExp("^3[47]");
        if (number.match(re) != null)
            return "AMEX";

        // Discover
        re = new RegExp("^(6011|622(12[6-9]|1[3-9][0-9]|[2-8][0-9]{2}|9[0-1][0-9]|92[0-5]|64[4-9])|65)");
        if (number.match(re) != null)
            return "Discover";

        // Diners
        re = new RegExp("^36");
        if (number.match(re) != null)
            return "Diners";

        // Diners - Carte Blanche
        re = new RegExp("^30[0-5]");
        if (number.match(re) != null)
            return "Diners - Carte Blanche";

        // JCB
        re = new RegExp("^35(2[89]|[3-8][0-9])");
        if (number.match(re) != null)
            return "JCB";

        // Visa Electron
        re = new RegExp("^(4026|417500|4508|4844|491(3|7))");
        if (number.match(re) != null)
            return "Visa Electron";

        return "invalid";
    }


    // End Chargebee
    $(".amazon_form_logo").each(function(){
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
                                url: "send_amazon_logo",
                                type: "POST",
                                async: true,
                                data: {
                                    "ext": get_ext[0]
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
                                    ace.logo_ext = data.ext;
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
                var url = $(data).find('Location').text();

                $('#real_file_url').val(url);// Update the real input in the other form
            },
            done: function (event, data) {
                console.log(data);
                $('#uploadLogoFirst').hide();
                if($('.logo1').attr('src').indexOf('images/noLogo1.png') !== -1) {
                    $('.logo1').attr('src', 'https://aceplayout.s3.amazonaws.com/logos/channel_' + ace.channel_id +'.'+ace.logo_ext);
                } else {
                    $('.logo1').attr('src', $('.logo1').attr('src') + Math.random());
                }
                $('.amazon_form_logo #fileupload').removeClass('logoUploadAfter');
                $('.logoLoader').height(10);

            }
        })
    });

    //$(".timezone").select2({dropdownCssClass: 'dropdown-inverse'});
    $('#title').focus(function() {
        $(this).parent().removeClass('has-error');
    });

    $('#settings').submit(function () {
        event.preventDefault();
        $.ajax({
            url: ace.path('edit_settings'),
            type: "POST",
            data: {
                'title': $('#title').val(),
                'stream': $('#stream').val(),
                'login': $('#login').val(),
                'source': $('#source').val(),
                'layout': $('#layout').val(),
                'login_url': $('#login_url').val(),
                'login_signup_text': $('#login_signup_text').val(),
                'bgcolor': $('#bgcolor').val(),
                'ustream_api_key': $('#ustream_api_key').val(),
                'ustream_app_name': $('#ustream_app_name').val(),
                'loginMode': $('#loginMode').val(),
                'activation_url': $('#activation_url').val(),
                'format': $("input[name=format]:checked").val(),
                'timezone': $('#timezone').val()
            },
            dataType: "json",
            success: function (data) {
                if(data.title) {
                    $('#title').parent().addClass('has-error');
                } else if(data.status) {
                    $('.edited').html('<span class="fui-check"></span> Settings updated!');
                    setInterval(function() {
                        $('.edited').empty();
                    }, 1000);
                }
            }
        });
    });

    $('#set_stream').submit(function () {
        event.preventDefault();
        $.ajax({
            url: ace.path('set_stream_url'),
            type: "POST",
            data: {
                'stream_url': $('#stream_url').val()
            },
            dataType: "json",
            success: function (data) {
                if(data.title) {
                    $('#title').parent().addClass('has-error');
                } else if(data.status) {
                    $('.streamSet').html('<span class="fui-check"></span> New Stream URL was set!');
                    setInterval(function() {
                        $('.streamSet').empty().slow();
                    }, 2000);
                }
                $('#stream_url_label').text($('#stream_url').val());
                location.reload(); // to refresh hls
            }
        });
    });

    $('.tabs a').click(function(e) {
        e.preventDefault();
        $(this).tab('show');
    });
});