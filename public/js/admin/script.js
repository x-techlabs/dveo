$(document).ready(function(){
    // Channels
    function channels() {
        document.title = 'Channels ― 1studio';
        $.get("channels", function(data) {
            $(".panel-append").html(data);
        });
        $('.navbar-nav li').removeClass('active');
        $('#getChannels').parent().addClass('active');
    }

    // Users
    function users() {
        document.title = 'Users ― 1studio';
        $.get("users", function(data) {
            $(".panel-append").html(data);
        });
        $('.navbar-nav li').removeClass('active');
        $('#getUsers').parent().addClass('active');
    }

    // Companies
    function companies() {
        document.title = 'Companies ― 1studio';
        $.get("companies", function(data) {
            $(".panel-append").html(data);
        });
        $('.navbar-nav li').removeClass('active');
        $('#getCompanies').parent().addClass('active');
    }

    // Dveos
    function dveos() {
        document.title = 'DVEO ― 1studio';
        $.get("dveos", function(data) {
            $(".panel-append").html(data);
        });
        $('.navbar-nav li').removeClass('active');
        $('#getDveos').parent().addClass('active');
    }

    $('.panel-append').delegate('.add_channel', 'click', function() { // Add channel
        $('#myModalLabel').html('Create new channel');
        $('.save_channel').html('Create');
        $('#channel #channel_id').val('');
        $('#channel #title').val('');
        $('#channel #dveo_id option').prop('selected', false);
        $('#channel #stream').html('');
        $('#channel #company_id option').prop('selected', false);
        $('#channel #hd').prop('checked', false);
        $('#channel #sd').prop('checked', false);
        $('#channel #timezone option').prop('selected', false);
        $('.has-error').removeClass('has-error');
        $('.noFormat').empty();

        $.ajax({
            url: "addChannel",
            type: "GET",
            dataType: "json",
            success: function(data) {
                $.each(data.streams, function(k, v) {
                    $('#channel #stream').append('<option value="' + k + '">' + v + '</option>').attr('readonly', 'readonly');
                });

                $('#channel #timezone').html('');
                $.each(data.timezones, function(k, v) {
                    $('#channel #timezone').append('<option value="' + k + '">' + v + '</option>');
                });
            }
        });
    }).delegate('.edit_channel', 'click', function() {
        $('#myModalLabel').html('Edit channel');
        $('.save_channel').html('Save');
        $('#channel #channel_id').val('');
        $('#channel #title').val('');
        $('#channel #stream').html('');
        $('#channel #hd').prop('checked', false);
        $('#channel #sd').prop('checked', false);
        $('#channel #timezone option').prop('selected', false);
        $('.has-error').removeClass('has-error');
        $('.noFormat').empty();

        $.ajax({
            url: "editChannel",
            type: "GET",
            data: {
                channel_id: $(this).data('channel_id')
            },
            dataType: "json",
            success: function(data) {
                $('#channel #channel_id').val(data.channel.id);
                $('#channel #title').val(data.channel.title);
                $('#channel #dveo_id option[value="' + data.channel.dveo_id + '"]').prop('selected', true);
                $.each(data.streams, function(k, v) {
                    if(data.channel.stream == v) {
                        $('#channel #stream').append('<option value="' + k + '" selected>' + v + '</option>');
                    } else {
                        $('#channel #stream').append('<option value="' + k + '">' + v + '</option>');
                    }
                });
				if(data.channel.playout_access == 1){
					$('#pl_access').prop('checked','checked');
				}
                $('#channel #company_id option[value="' + data.channel.company_id + '"]').prop('selected', true);
                $('#channel #' + data.channel.format).prop('checked', true);
                $('#channel #timezone').html('');
                $.each(data.timezones, function(k, v) {
                    if(data.channel.timezone == k) {
                        $('#channel #timezone').append('<option value="' + k + '" selected>' + v + '</option>');
                    } else {
                        $('#channel #timezone').append('<option value="' + k + '">' + v + '</option>');
                    }
                });
            }
        });
    }).delegate('.save_channel', 'click', function() { // Save channel
        var channel_id = $('#channel #channel_id').val();
        var title = $('#channel #title').val();
        var stream = $('#channel #stream ').val();
        var company_id = $('#channel #company_id').val();
        var dveo_id = $('#channel #dveo_id').val();
        var format = $('#channel input[name=format]:checked').val();
        var timezone = $('#channel #timezone').val();
		var pl_access = ($("#pl_access").is(':checked')) ? $('#pl_access:checked').val() : 0;

        if(title == '' || company_id == 0 || /* dveo_id == 0 || stream == 0 || */ format === undefined || timezone == 0) {
            if(title == '') {
                $('#channel #title').parent().addClass('has-error');
            }/*
            if(stream == 0) {
                $('#channel #stream').parent().addClass('has-error');
            }*/
            if(company_id == 0) {
                $('#channel #company_id').parent().addClass('has-error');
            }/* dveo_id is optional
            if(dveo_id == 0) {
                $('#channel #dveo_id').parent().addClass('has-error');
            }*/
            if(format === undefined) {
                $('.noFormat').html('<i class="fa fa-exclamation-triangle"></i> The format is not selected');
            }
            if(timezone == 0) {
                $('#channel #timezone').parent().addClass('has-error');
            }
        } else {
            if(channel_id == '') {
                $.ajax({
                    url: "addChannel",
                    type: "POST",
                    data: {
                        title: title,
                        stream: stream,
                        company_id: company_id,
                        dveo_id: dveo_id,
                        format: format,
						pl_access: pl_access,
                        timezone: timezone
                    },
                    dataType: "json",
                    success: function(data) {
                        if(data.status) {
                            $('.close_channel').click();
                        }
                        if(data.channel) {
                            $('.divTable').append('<div class="divTableRow" id="tableData">' +
                            '<div class="divTableCell">' + data.channel.id + '</div>' +
                            '<div class="divTableCell">' + data.channel.title + '</div>' +
                            '<div class="divTableCell">' + data.channel.dveo_id + '</div>' +
                            '<div class="divTableCell">' + data.channel.stream + '</div>' +
                            '<div class="divTableCell">' + data.channel.company_id + '</div>' +
                            '<div class="divTableCell">' + data.channel.format + '</div>' +
                            '<div class="divTableCell">' + data.channel.timezone + '</div>' +
                            '<div class="divTableCell">' +
                            '<button type="button" style="margin-right: 4px;" class="btn btn-default edit_channel disabled" data-toggle="modal" data-target="#myModal" data-channel_id="' + data.channel.id + '" title="Edit channel"><i class="fa fa-lg fa-pencil"></i></button>' +
                            '<button type="button" class="btn btn-default delete_channel disabled" data-channel_id="' + data.channel.id + '" title="Delete channel"><i class="fa fa-lg fa-trash-o"></i></button></div>' +
                            '</div>');
                        }
                    }
                });
            } else {
                $.ajax({
                    url: "editChannel",
                    type: "POST",
                    data: {
                        channel_id: channel_id,
                        title: title,
                        stream: stream,
                        company_id: company_id,
                        dveo_id: dveo_id,
                        format: format,
						pl_access: pl_access,
                        timezone: timezone
                    },
                    dataType: "json",
                    success: function(data) {
                        if(data.status) {
                            $('.close_channel').click();
                        }
                        if(data.channel) {
                            var thisRow = $('.divTableRow[data-row_id = ' + data.channel.id + ']');
                            thisRow.find('#channel_title').html(data.channel.title);
                            thisRow.find('#channel_stream').html(data.channel.stream);
                            thisRow.find('#channel_company_id').html(data.channel.company_id);
                            thisRow.find('#channel_dveo_id').html(data.channel.dveo_id);
                            thisRow.find('#channel_format').html(data.channel.format);
                            thisRow.find('#channel_timezone').html(data.channel.timezone);
                        }
                    }
                });
            }
        }
    }).delegate('.delete_channel', 'click', function() { // Delete channel
        var channel = $(this);
        var channel_id = $(this).data('channel_id');
        bootbox.confirm("Are you sure?", function(result) {
            if(result) {
                $.ajax({
                    url: "deleteChannel",
                    type: "GET",
                    data: {
                        channel_id: channel_id
                    },
                    dataType: "json",
                    success: function (data) {
                        if (data.status) {
                            $(channel).parent().parent().fadeOut();
                        }
                    }
                });
            }
        });
    }).delegate('.add_user', 'click', function() { // Add user
        $('#myModalLabel').html('Create new user');
        $('.save_user').html('Create');
        $('.error').empty();
        $('#user #user_id').val('');
        $('#user #username').val('').attr('readonly', false);
        $('#user #password').val('');
        $('#user #email').val('');
        $('#user #company_id option').prop('selected', false);
        $('#user #channel_id').html('<option value="0">Select company for selecting channel</option>').attr('readonly', 'readonly').find(' option').prop('selected', false);
        $('#user #permissions option').prop('selected', false);
        $('.has-error').removeClass('has-error');
    }).delegate('.edit_user', 'click', function() { // Edit user
        $('#myModalLabel').html('Edit user');
        $('.save_user').html('Save');
        $('.error').empty();
        $('#user #user_id').val('');
        $('#user #username').val('').attr('readonly', 'readonly');
        $('#user #password').val('');
        $('#user #email').val('');
        $('#user #company_id option').prop('selected', false);
        $('#channel_id').html('');
        $('#user #permissions').html('');
        $('.has-error').removeClass('has-error');

        $.ajax({
            url: "editUser",
            type: "GET",
            data: {
                user_id: $(this).data('user_id')
            },
            dataType: "json",
            success: function(data) {
                $('#user #user_id').val(data.user.id);
                $('#user #username').val(data.user.username);
                $('#user #password').val('');
                $('#user #email').val(data.user.email);
                $('#user #company_id option[value="' + data.user.company_id + '"]').prop('selected', true);
                $('#user #channel_id').html(data.channelsOption).attr('readonly', false);
                $('#user #permissions').html(data.permissions);
				if(data.user.playout_access == 1){
					$('#access').prop('checked','checked');
				}
                if(data.channels) {
                    $.each(data.channels, function(k, v) {
                        $('#user #channel_id option[value="' + k + '"]').prop('selected', true);
                    });
                } else if(data.channelsOption == '') {
                    $('#user #channel_id').html('<option value="0">There are no channels in this company</option>').attr('readonly', 'readonly');
                }
                $('#channel_id').select2({ width: '100%',dropdownAutoWidth : true });
                console.log(data.channelsOption);
            }
        });
    }).delegate('.save_user', 'click', function() { // Save user
        var user_id = $('#user #user_id').val();
        var username = $('#user #username').val();
        var password = $('#user #password').val();
        var email = $('#user #email').val();
        var company_id = $('#user #company_id').val();
        var channel_ids = $('#user #channel_id').val();
        var permissions = $('#user #permissions').val();
		var access = ($("#access").is(':checked')) ? $('#access:checked').val() : 0;

        if(username == '' || email == '' || company_id == 0 || permissions == null || (password == '' && user_id == '')) {
            if(username == '') {
                $('#username_error').empty();
                $('#user #username').parent().addClass('has-error');
            }
            if(password == '' && user_id == '') {
                $('#user #password').parent().addClass('has-error');
            }
            if(email == '') {
                $('#email_error').empty();
                $('#user #email').parent().addClass('has-error');
            }
            if(company_id == 0) {
                $('#user #company_id').parent().addClass('has-error');
            }
            if(permissions == null) {
                $('#user #permissions').parent().addClass('has-error');
            }
        } else {
            if(user_id == '') {
                $.ajax({
                    url: "addUser",
                    type: "POST",
                    data: {
                        username: username,
                        password: password,
                        email: email,
                        company_id: company_id,
                        channel_ids: channel_ids,
                        permissions: permissions,
						access: access
                    },
                    dataType: "json",
                    success: function(data) {
                        if(data.status) {
                            $('.close_user').click();

                            if(data.user) {
                                $('.divTable').append('<div class="divTableRow" id="tableData">' +
                                '<div class="divTableCell">' + data.user.id + '</div>' +
                                '<div class="divTableCell">' + data.user.username + '</div>' +
                                '<div class="divTableCell">' + data.user.email + '</div>' +
                                '<div class="divTableCell">' + data.user.company_id + '</div>' +
                                '<div class="divTableCell">' + data.channels + '</div>' +
                                '<div class="divTableCell">' + data.user.type + '</div>' +
                                '<div class="divTableCell">' +
                                '<span style="margin-right: 4px;" class="label label-warning">Inactive</span>' +
                                '<button type="button" style="margin-right: 4px;" class="btn btn-default restore_password disabled" data-user_id="' + data.user.id + '" title="Restore password"><i class="fa fa-envelope"></i></button>' +
                                '<button type="button" style="margin-right: 4px;" class="btn btn-default edit_user disabled" data-toggle="modal" data-target="#myModal" data-user_id="' + data.user.id + '" title="Edit user"><i class="fa fa-lg fa-pencil"></i></button>' +
                                '<button type="button" class="btn btn-default delete_user disabled" data-user_id="' + data.user.id + '" title="Delete user"><i class="fa fa-lg fa-trash-o"></i></button></div>' +
                                '</div>');
                            }
                        } else {
                            if(data.errors) {
                                if(data.errors['username']) {
                                    $('#username_error').html(data.errors['username']);
                                }
                                if(data.errors['email']) {
                                    $('#email_error').html(data.errors['email']);
                                }
                            }
                        }
                    }
                });
            } else {
                $.ajax({
                    url: "editUser",
                    type: "POST",
                    data: {
                        user_id: user_id,
                        username: username,
                        password: password,
                        email: email,
                        company_id: company_id,
                        channel_ids: channel_ids,
                        permissions: permissions,
						access: access
                    },
                    dataType: "json",
                    success: function(data) {
                        if(data.status) {
                            $('.close_user').click();

                            if(data.user) {
                                var thisRow = $('.divTableRow[data-row_id = ' + data.user.id + ']');
                                thisRow.find('#user_username').html(data.user.username);
                                thisRow.find('#user_email').html(data.user.email);
                                thisRow.find('#user_company_id').html(data.user.company_id);
                                thisRow.find('#user_channel_id').html(data.channels);
                                thisRow.find('#user_type').html(data.user.type);
                            }
                        } else {
                            if(data.errors) {
                                if(data.errors['username']) {
                                    $('#username_error').html(data.errors['username']);
                                }
                                if(data.errors['email']) {
                                    $('#email_error').html(data.errors['email']);
                                }
                            }
                        }
                    }
                });
            }
        }
    }).delegate('.delete_user', 'click', function() { // Delete user
        var user = $(this);
        var user_id = $(this).data('user_id');
        bootbox.confirm("Are you sure?", function(result) {
            if(result) {
                $.ajax({
                    url: "deleteUser",
                    type: "GET",
                    data: {
                        user_id: user_id
                    },
                    dataType: "json",
                    success: function(data) {
                        if(data.status) {
                            $(user).parent().parent().fadeOut();
                        }
                    }
                });
            }
        });
    }).delegate('.restore_password', 'click', function() { // Restore password
        var user = $(this);
        var user_id = $(this).data('user_id');
        bootbox.confirm("Are you sure you want to restore password?", function(result) {
            if(result) {
                $.ajax({
                    url: "restore",
                    type: "POST",
                    data: {
                        user_id: user_id
                    },
                    dataType: "json",
                    success: function(data) {
                        if(data.status) {
                            $(user).parent().find('.label-warning').addClass('label-warning').removeClass('label-success').text('Inactive');
                            bootbox.alert("Your message has been sent. Check your email.", function() {
                                //
                            });
                        }
                    }
                });
            }
        });
    }).delegate('.add_company', 'click', function() { // Add company
        $('#myModalLabel').html('Create new company');
        $('.save_company').html('Create');
        $('#company #company_id').val('');
        $('#company #name').val('');
        $('.has-error').removeClass('has-error');
    }).delegate('.edit_company', 'click', function() { // Edit company
        $('#myModalLabel').html('Edit company');
        $('.save_company').html('Save');
        $('#company #company_id').val('');
        $('#company #name').val('');
        $('.has-error').removeClass('has-error');

        $.ajax({
            url: "editCompany",
            type: "GET",
            data: {
                company_id: $(this).data('company_id')
            },
            dataType: "json",
            success: function(data) {
                $('#company #company_id').val(data.company.id);
                $('#company #name').val(data.company.name);
            }
        });
    }).delegate('.save_company', 'click', function() { // Save company
        var company_id = $('#company #company_id').val();
        var name = $('#company #name').val();

        if(name == '') {
            $('#company #name').parent().addClass('has-error');
        } else {
            if(company_id == '') {
                $.ajax({
                    url: "addCompany",
                    type: "POST",
                    data: {
                        name: name
                    },
                    dataType: "json",
                    success: function(data) {
                        if(data.status) {
                            $('.close_company').click();
                        }
                        if(data.company) {
                            $('.divTable').append('<div class="divTableRow" id="tableData">' +
                            '<div class="divTableCell">' + data.company.id + '</div>' +
                            '<div class="divTableCell">' + data.company.name + '</div>' +
                            '<div class="divTableCell">' +
                                '<button type="button" style="margin-right: 4px;" class="btn btn-default edit_company disabled" data-toggle="modal" data-target="#myModal" data-company_id="' + data.company.id + '" title="Edit company"><i class="fa fa-lg fa-pencil"></i></button>' +
                                '<button type="button" class="btn btn-default delete_company disabled" data-company_id="' + data.company.id + '" title="Delete company"><i class="fa fa-lg fa-trash-o"></i></button></div>' +
                            '</div>');
                        }
                    }
                });
            } else {
                $.ajax({
                    url: "editCompany",
                    type: "POST",
                    data: {
                        company_id: company_id,
                        name: name
                    },
                    dataType: "json",
                    success: function(data) {
                        if(data.status) {
                            $('.close_company').click();
                        }
                        if(data.company) {
                            var thisRow = $('.divTableRow[data-row_id = ' + data.company.id + ']');
                            thisRow.find('#company_name').html(data.company.name);
                        }
                    }
                });
            }
        }
    }).delegate('.delete_company', 'click', function() { // Delete company
        var company = $(this);
        var company_id = $(this).data('company_id');
        bootbox.confirm("Are you sure?", function(result) {
            if(result) {
                $.ajax({
                    url: "deleteCompany",
                    type: "GET",
                    data: {
                        company_id: company_id
                    },
                    dataType: "json",
                    success: function(data) {
                        if(data.status) {
                            $(company).parent().parent().fadeOut();
                        }
                    }
                });
            }
        });
    }).delegate('#user #company_id', 'change', function() { // Get channels for companies
        $.ajax({
            url: "getChannelsForCompanies",
            type: "GET",
            data: {
                company_id: $(this).find('option:selected').val()
            },
            dataType: "json",
            success: function(data) {
                if(data.status) {
                    $('#channel_id').html('').removeAttr('readonly');
                    $.each(data.channels, function(k, v) {
                        $('#channel_id').append('<option value="' + k + '">' + v + '</option>');
                    });
                } else {
                    $('#channel_id').html('<option value="0">There are no channels in this company</option>').attr('readonly', 'readonly');
                }
            }
        });
    }).delegate('#channel #dveo_id', 'change', function() { // Get file streams for dveos
        $('#stream').attr('readonly', 'readonly');
        $.ajax({
            url: "GetStreamsForDveos",
            type: "GET",
            data: {
                dveo_id: $(this).find('option:selected').val(),
                channel_id: $('#channel #channel_id').val()
            },
            dataType: "json",
            success: function(data) {
                if(data.status) {
                    $('#stream').html('').removeAttr('readonly');
                    $.each(data.streams, function(k, v) {
                        $('#stream').append('<option value="' + k + '">' + v + '</option>');
                    });
                } else {
                    $('#stream').html('<option value="0">There are no streams in this DVEO</option>').attr('readonly', 'readonly');
                }
            }
        });
    }).delegate('.add_dveo', 'click', function() { // Add dveo
        $('#myModalLabel').html('Create new dveo');
        $('.save_dveo').html('Create');
        $('#dveo #dveo_id').val('');
        $('#dveo #ip').val('');
        $('.has-error').removeClass('has-error');
    }).delegate('.edit_dveo', 'click', function() { // Edit dveo
        $('#myModalLabel').html('Edit company');
        $('.save_dveo').html('Save');
        $('#dveo #dveo_id').val('');
        $('#dveo #ip').val('');
        $('.has-error').removeClass('has-error');

        $.ajax({
            url: "editDveo",
            type: "GET",
            data: {
                dveo_id: $(this).data('dveo_id')
            },
            dataType: "json",
            success: function(data) {
                $('#dveo #dveo_id').val(data.dveo.id);
                $('#dveo #ip').val(data.dveo.ip);
            }
        });
    }).delegate('.save_dveo', 'click', function() { // Save dveo
        var dveo_id = $('#dveo #dveo_id').val();
        var ip = $('#dveo #ip').val();

        if(ip == '') {
            $('#dveo #ip').parent().addClass('has-error');
        } else {
            if(dveo_id == '') {
                $.ajax({
                    url: "addDveo",
                    type: "POST",
                    data: {
                        ip: ip
                    },
                    dataType: "json",
                    success: function(data) {
                        if(data.status) {
                            $('.close_dveo').click();
                        } else {
                            if(data.error) {
                                $('#ip_error').html(data.error);
                            }
                        }
                        if(data.dveo) {
                            $('.divTable').append('<div class="divTableRow" id="tableData">' +
                            '<div class="divTableCell">' + data.dveo.id + '</div>' +
                            '<div class="divTableCell">' + data.dveo.ip + '</div>' +
                            '<div class="divTableCell">' +
                            '<button type="button" style="margin-right: 4px;" class="btn btn-default edit_dveo disabled" data-toggle="modal" data-target="#myModal" data-dveo_id="' + data.dveo.id + '" title="Edit dveo"><i class="fa fa-lg fa-pencil"></i></button>' +
                            '<button type="button" class="btn btn-default delete_dveo disabled" data-dveo_id="' + data.dveo.id + '" title="Delete dveo"><i class="fa fa-lg fa-trash-o"></i></button></div>' +
                            '</div>');
                        }
                    }
                });
            } else {
                $.ajax({
                    url: "editDveo",
                    type: "POST",
                    data: {
                        dveo_id: dveo_id,
                        ip: ip
                    },
                    dataType: "json",
                    success: function(data) {
                        if(data.status) {
                            $('.close_dveo').click();
                        } else {
                            if(data.error) {
                                $('#ip_error').html(data.error);
                            }
                        }
                        if(data.dveo) {
                            var thisRow = $('.divTableRow[data-row_id = ' + data.dveo.id + ']');
                            thisRow.find('#dveo_ip').html(data.dveo.ip);
                        }
                    }
                });
            }
        }
    }).delegate('.delete_dveo', 'click', function() { // Delete dveo
        var dveo = $(this);
        var dveo_id = $(this).data('dveo_id');
        bootbox.confirm("Are you sure?", function(result) {
            if(result) {
                $.ajax({
                    url: "deleteDveo",
                    type: "GET",
                    data: {
                        dveo_id: dveo_id
                    },
                    dataType: "json",
                    success: function(data) {
                        if(data.status) {
                            $(dveo).parent().parent().fadeOut();
                        }
                    }
                });
            }
        });
    }).delegate('#search', 'keyup', function() { // Search in table
        var _this = this;
        $.each($(".divTable #tableData"), function() {
            if($(this).find(".divTableCell:not(':last-child')").text().toLowerCase().indexOf($(_this).val().toLowerCase()) == -1) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });

    // Remove errors
    $(document).delegate('.form-group input', 'click', function() {
        $(this).parent().removeClass('has-error');
    }).delegate('.form-group select', 'click', function() {
        $(this).parent().removeClass('has-error');
    }).delegate('.noFormat', 'click', function() {
        $(this).empty();
    }).delegate('.error', 'click', function() {
        $(this).empty();
    });

    // Navigation
    Path.map("#/channels").to(function(){
        channels();
    });

    Path.map("#/users").to(function(){
        users();
    });

    Path.map("#/companies").to(function(){
        companies();
    });

    Path.map("#/dveos").to(function(){
        dveos();
    });

    Path.root("#/channels");
    Path.listen();
});