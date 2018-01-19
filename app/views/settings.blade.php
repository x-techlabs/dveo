@extends('template.template')

@section('content')

<script language='javascript'>
    var base_path = "{{ URL::to('/') }}";
    var channel_id = "{{ 'channel_' . BaseController::get_channel_id() . '/'}}";
    var use_mp3 = 1;
    jQuery(document).ready(function ($) {
        $('#options_tab a.tab_link').click(function(e) {
            e.preventDefault();
            $(this).tab('show');
        });
        $("ul#options_tab > li > a.tab_link").on("shown.bs.tab", function(e) {
            var id = $(e.target).attr("href").substr(1);
            window.location.hash = id;
            var current_url_input = $('.current_url');
            current_url_input.val(window.location.href );
            $("html,body").scrollTop(0);
        });
        var hash = window.location.hash;
        $('.tab_wrapper').hide();
        $(hash).show();
        $('#options_tab li').removeClass('active');
        $('#options_tab a.tab_link[href="' + hash + '"]').parent().addClass('active');
    });
    function OnTabChange(x)
    {
        window.location.hash = 'tab'+x;
        document.getElementById('tab1').style.display='none';
        document.getElementById('tab2').style.display='none';
        document.getElementById('tab3').style.display='none';
        document.getElementById('tab5').style.display='none';
        document.getElementById('tab7').style.display='none';
        document.getElementById('tab10').style.display='none';
        document.getElementById('tab6').style.display='none';
        document.getElementById('tab8').style.display='none';
        document.getElementById('tab4').style.display='none';
        document.getElementById('tab9').style.display='none';
        document.getElementById('tab11').style.display='none';
        document.getElementById('tab12').style.display='none';
        document.getElementById('tab13').style.display='none';
        document.getElementById('tab'+x).style.display='block';
        if(x == 5){
            OnTabChangeAnal('1');
        }
    }
</script>
<div class="settings content container col-md-12 col-lg-12 col-xs-12 col-sm-12">

    <div class="title-name">
        <i class="fa fa-cog"></i>
        <div class="title">Settings</div>
    </div>
    <div class="clear"></div>
    @if(Session::has('message'))
        <div class="alert alert-danger errorMsg">
            {{ Session::get('message')}}
        </div>      
    @endif
    @if(Session::has('success'))
        <div class="alert alert-success errorMsg">
            {{ Session::get('success')}}
        </div>      
    @endif
    <div class="settings_wrapper">
        <div>
            <div class="container">
                <div class="col-md-3">
                    <nav class="nav-sidebar">
                        <ul class="nav tabs" id = "options_tab">
                            <li class="{{ (!Session::has('tab')) ? 'active' : ''}}" onclick='OnTabChange(1)'>
                                <a href="#tab1" class = "tab_link" data-toggle="tab">General</a>
                            </li>
                            <li onclick='OnTabChange(2)' class="{{ (Session::has('tab') && Session::get('tab') == 2 ) ? 'active' : ''}}">
                                <a href="#tab2" class = "tab_link" data-toggle="tab">Logo And Images</a>
                            </li>
                            <li onclick='OnTabChange(5)'><a href="#tab5" class = "tab_link" data-toggle="tab">Analytics</a></li>
                            <li onclick='OnTabChange(3)'><a href="#tab3" class = "tab_link" data-toggle="tab">Build Channels</a></li>
                            <li onclick='OnTabChange(7)'><a href='#tab7' class = "tab_link" data-toggle='tab'>Launchpad</a></li>
                            <li onclick='OnTabChange(10)'><a href='#tab10' class = "tab_link" data-toggle='tab'>Mobile-Web TV</a></li>
                            <li onclick='OnTabChange(8)'><a href='#tab8' class = "tab_link" data-toggle='tab'>Subscription</a></li>
                            <li onclick='OnTabChange(9)'><a href='#tab9' class = "tab_link" data-toggle='tab'>Advertising</a></li>
                            <li onclick='OnTabChange(4)'><a href='#tab4' class = "tab_link" data-toggle='tab'>Distribution</a></li>
                            <li onclick='OnTabChange(11)'><a href='#tab11' class = "tab_link" data-toggle='tab'>Youtube Channel Downloader</a></li>
                            <li onclick='OnTabChange(12)'><a class = "tab_link" href='#tab12' data-toggle='tab'>Tag manager</a></li>
							<li onclick='OnTabChange(13)'><a class = "tab_link" href='#tab13' data-toggle='tab'>Show manager</a></li>
							<li onclick='OnTabChange(6)'><a href='#tab6' class = "tab_link" data-toggle='tab'>Extras</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-md-9">
                    <div class="tab-content tab_wrapper" id="tab1">
                        {{ Form::open(array(
                            'url' => 'channel_' . BaseController::get_channel_id() . '/edit_settings',
                            'class' => 'form-horizontal',
                            'enctype' => 'multipart/form-data',
                            'id' => 'settings'
                        )) }}

                        <table class="table-contain" cellspacing='20'>
                            <tr class="tableTr-height40">
                                <td class="tableTd-width180">Title :</td>
                                <td>{{ Form::text('title', $channel['title'], array('style' => 'width:90%;', 'id' => 'title')) }}</td>
                            </tr>


                            <tr class="tableTr-height40">
                                <td class="tableTd-width180">Video Formats :</td>
                                <td class="margin-left-radio">
                                    <label class="radio fl">
                                        {{ Form::radio('format', 'hd', $format['hd'], array('class' => 'custom-radio formatInput', 'id' => 'optionsRadios1', 'data-toggle' => 'radio')) }}
                                        <span class="icons">
                                        <span class="icon-unchecked"></span>
                                        <span class="icon-checked"></span>
                                    </span>
                                        HD Video Format
                                    </label>
                                    <label class="radio fl">
                                        {{ Form::radio('format', 'sd', $format['sd'], array('class' => 'custom-radio formatInput', 'id' => 'optionsRadios1', 'data-toggle' => 'radio')) }}
                                        <span class="icons">
                                        <span class="icon-unchecked"></span>
                                        <span class="icon-checked"></span>
                                    </span>
                                        SD Video Format
                                    </label>
                                </td>
                            </tr>

                            <tr class="tableTr-height40">
                                <td class="tableTd-width180">Timezones :</td>
                                <td class="timezone-contain">
                                    <select name="timezone" id="timezone">
                                        @foreach($timezones as $key => $timezone)
                                            @if($key == $selectedTimezone)
                                                <option value="{{ $key }}" selected>{{ $timezone }}</option>
                                            @else
                                                <option value="{{ $key }}">{{ $timezone }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </td>
                            </tr>

                            <tr class="tableTr-height40">
                                <td class="tableTd-width180">Layout :</td>
                                <td>{{ Form::select('layout', ['linear' => 'Linear','grid' => 'Grid'], $layout, array('id' => 'layout', 'style' => 'width:170px;' )) }}</td>
                            </tr>

                            <tr class="tableTr-height40">
                                <td class="tableTd-width180">Content Source :</td>
                                <td>{{ Form::select('source', ['1studio' => 'One Studio','ustream' => 'UStream'], $source, array('id' => 'source', 'style' => 'width:170px;', 'onchange' => "ToggleApiUrl('source')" )) }}</td>
                            </tr>

                            <tr class="tableTr-height40 display-none-col" id='source1'>
                                <td valign='top'>UStream API Key :</td>
                                <td>{{ Form::text('ustream_api_key', $ustream_api_key, array('id' => 'ustream_api_key', 'style' => 'width:300px;')) }} </td>
                            </tr>

                            <tr class="tableTr-height40 display-none-col" id='source2'>
                                <td valign='top'>UStram App Name :
                                </td>
                                <td>{{ Form::text('ustream_app_name', $ustream_app_name, array('id' => 'ustream_app_name', 'style' => 'width:300px;')) }} </td>
                            </tr>

                            <tr class="tableTr-height40">
                                <td class="tableTd-width180">Login Required :</td>
                                <td>{{ Form::select('login', ['no' => 'No','yes' => 'On App Launch (full channel is paid)','yesP' => 'When ever Required (Few videos are paid)'], $login, array('id' => 'login', 'style' => 'width:300px;', 'onchange' => "ToggleApiUrl('login')" )) }}</td>
                            </tr>

                            <tr class="tableTr-height40" id='yesW1'>
                                <td valign='top'>API URL :</td>
                                <td>{{ Form::text('login_url', $login_url, array('id' => 'login_url', 'style' => 'width:300px;display:inline;')) }}
                                    <img class="img-help" src='{{ URL::to('/') }}/images/help.png' title="Show Help" onclick="ShowHelp(3)">
                                    <div id='h3' style='display:none;' class="tab-content grey">Following calls are made to this url by device(s).<br>
                                    1. ApiUrl?action=isRegistered&d=[deviceid]<br>
                                    2. ApiUrl?action=getActivationCode<br>
                                    3. ApiUrl?action=login&u=[username]&p=[password]&d=[deviceid]<br>
                                    4. ApiUrl?action=registerDevice&a=[authorizationCode]&d=[deviceid]<br>
                                    5. ApiUrl?action=canView&v=[categoryname]&d=[deviceid]<br>
                                    </div>
                                </td>
                            </tr>

                            <tr class="tableTr-height40" id='yesW4'>
                                <td class="tableTd-width180">Login Method  :</td>
                                <td>{{ Form::select('loginMode', ['yesW' => 'From Website', 'yesR' => 'From Roku'], $loginMode, array('id' => 'loginMode', 'style' => 'width:170px;', 'onchange' => "ToggleApiUrl('login')" )) }}</td>
                            </tr>

                            <tr class="tableTr-height40" id='yesW3'>
                                <td valign='top'>Signup Page Text :
                                </td>
                                <td>{{ Form::textarea('login_signup_text', $login_signup_text, array('id' => 'login_signup_text', 'size' => '55x6')) }}
                                    <img src='{{ URL::to('/') }}/images/help.png' class="img-help" title="Show Help" onclick="ShowHelp(2)">
                                    <div id='h2' style='margin-bottom:5px;display:none;' class="tab-content grey">Text written here will be shown as a plain text on the registration information page followed by the it is followed by roku registration url</div>
                                </td>
                            </tr>

                            <tr class="tableTr-height40" id='yesW2'>
                                <td valign='top'>Roku Activation URL:</td>
                                <td>{{ Form::text('activation_url', $activation_url, array('id' => 'activation_url', 'style' => 'width:300px;display:inline;')) }}
                                    <img src='{{ URL::to('/') }}/images/help.png' class="img-help" title="Show Help" onclick="ShowHelp(1)">
                                    <div id='h1' style='margin:5px 0 5px 0;display:none;' class="tab-content grey">User will open this url in browser and will enter the registration code to register his roku device with website.<br>Make sure this page and its backend functionality is developed and working</div>
                                </td>
                            </tr>

                            <tr class="tableTr-height40"><td class="tableTd-width180">Background Color :</td><td>{{ Form::text('bgcolor', $bgcolor, array('id' => 'bgcolor', 'style' => 'width:250px;', 'placeholder' => '#000000')) }}</td></tr>
                            <tr class="tableTr-height40"><td colspan=2>{{ Form::submit('Save', array('class' => 'btn btn-inverse btn-success')) }}</td></tr>
                        </table>
                        {{ Form::close() }}
                    </div>

                    <!-- ================================================================= -->
                    <div class="tab-content tab_wrapper" id="tab2">

                        {{ Form::open(array('url' => 'https://dveo.s3.amazonaws.com/', 'class' => 'form-horizontal amazon_form_logo', 'enctype' => 'multipart/form-data')) }}
                        <table class="tab-content table-contain" cellspacing='20'>
                            <tr><td colspan=2 ><p class="title-sec">Channel Logo and Focus-HD (336 x 210):</p></td></tr>
                            <tr>
                                <td class="tableTd-width350"><img onerror="$('#tab2 img').attr('src', '{{asset('images/noLogo.png')}}')" src="http://dveo.s3.amazonaws.com/logos/channel_{{ $channel['id'] }}.{{ $channel['logo_ext'] }}?{{ md5($channel['updated_at']) }}" class="tableImg-width200"></td>
                                <td align='left'>
                                    {{ Form::file('file', array('id' => 'fileupload', 'data-url' => 'server/php/')) }}
                                    {{ Form::hidden('key', 'uploads', array('id' => 'key')) }}
                                    {{ Form::hidden('acl', 'public-read') }}
                                    {{ Form::hidden('AWSAccessKeyId', 'AKIAJWU4DYR6OMHE2YPQ') }}
                                    {{ Form::hidden('Policy', 'policy', array('id' => 'policy')) }}
                                    {{ Form::hidden('Signature', 'signature', array('id' => 'signature')) }}
                                </td>
                            </tr>
                        </table>
                        {{ Form::close() }}


                                    <!-- ============================= Focus SD ==================================== -->

                        {{ Form::open(array('url' => 'channel_'.BaseController::get_channel_id().'/upload_focus_sd','files' => 'true')) }}
                        <table class="tab-content table-contain" cellspacing='20'>
                        <tr><td colspan=2><p class="title-sec">Focus-SD (248 × 140) :</p></td></tr>
                        <tr>
                            <td class="tableTd-width350"><img src='{{$focus_sd}}' class="tableImg-width200"></td>
                            <td class="tableTd-margin5">{{ Form::file('file_focus_sd', array('style' => 'float:left;')) }}
                                {{ Form::submit('Upload File') }}
                            </td>
                        </tr>
                        </table>
                        {{ Form::close() }}


                        <!-- ============================= Splash HD ==================================== -->

                        {{ Form::open(array('url' => 'channel_'.BaseController::get_channel_id().'/upload_splash_hd','files' => 'true')) }}
                        <table class="tab-content table-contain" cellspacing='20'>
                        <tr><td colspan=2><p class="title-sec">Splash-HD (1980 × 1080) :</p></td></tr>
                        <tr>
                            <td class="tableTd-width350"><img src='{{$splash_hd}}' class="tableImg-width200"></td>
                            <td class="tableTd-margin5">{{ Form::file('file_splash_hd', array('style' => 'float:left;')) }}
                                {{ Form::submit('Upload File') }}
                            </td>
                        </tr>
                        </table>
                        {{ Form::close() }}


                        <!-- ============================= Splash SD ==================================== -->

                        {{ Form::open(array('url' => 'channel_'.BaseController::get_channel_id().'/upload_splash_sd','files' => 'true')) }}
                        <table class="tab-content table-contain" cellspacing='20'>
                        <tr><td colspan=2><p class="title-sec">Splash-SD (1280 × 720) :</p></td></tr>
                        <tr>
                            <td class="tableTd-width350"><img src='{{$splash_sd}}' class="tableImg-width200"></td>
                            <td class="tableTd-margin5">{{ Form::file('file_splash_sd', array('style' => 'float:left;')) }}
                                {{ Form::submit('Upload File') }}
                            </td>
                        </tr>
                        </table>
                        {{ Form::close() }}


                        <!-- ================================================================= -->

                        {{ Form::open(array('url' => 'channel_'.BaseController::get_channel_id().'/upload_overhang_sd','files' => 'true')) }}
                        <table class="tab-content table-contain" cellspacing='20'>
                        <tr><td colspan=2 ><p class="title-sec">OverHang Image (SD) :</p></td></tr>
                        <tr>
                            <td class="tableTd-width350"><img src='{{$overhang_sd}}' class="tableImg-width200"></td>
                            <td class="tableTd-margin5">{{ Form::file('file_overhang_sd', array('style' => 'float:left;')) }}
                                {{ Form::submit('Upload File') }}
                            </td>
                        </tr>
                        </table>
                        {{ Form::close() }}

                        <!-- ================================================================= -->

                        {{ Form::open(array('url' => 'channel_'.BaseController::get_channel_id().'/upload_overhang_hd','files' => 'true')) }}
                        <table class="tab-content table-contain" cellspacing='20'>
                        <tr><td colspan=2><p class="title-sec">OverHang Image (HD) :</p></td></tr>
                        <tr>
                            <td class="tableTd-width350"><img src='{{$overhang_hd}}' class="tableImg-width200"></td>
                            <td class="tableTd-margin5">{{ Form::file('file_overhang_hd', array('style' => 'float:left;')) }}
                                {{ Form::submit('Upload File') }}
                            </td>
                        </tr>
                        </table>
                        {{ Form::close() }}

                    </div>

                    <div class="tab-content tab_wrapper" id="tab3">

                        <table cellspacing='20'>
                            <tr>
                                <td class="text-center">{{ Form::button('Build Roku Channel', array('id' => 'build0', 'onclick' => 'OnBuild(0)', 'style' => 'width:200px;float:left;')) }}&nbsp;&nbsp;
                                                        <img id='loader0' style='width:32px;display:none;' src="{{ URL::to('/') }}/images/admin_loader.gif">
                                </td>
                                <td id='drc0_1' style='display:none;'>Roku Channel is ready to download. <a id='drcLink0' href=''>Click here to Download Roku Channel</a></td>
                                <td id='zipError' style='display:none;'>You need to upload all required images</td>
                            </tr>
                        </table>
                        <br>
                        <table cellspacing='20'>
                            <tr>
                                <td class="text-center">{{ Form::button('Build Fire TV Channel', array('id' => 'build1', 'onclick' => 'OnBuild(1)', 'style' => 'width:200px;float:left;')) }}&nbsp;&nbsp;
                                                        <img id='loader1' style='width:32px;display:none;' src="{{ URL::to('/') }}/images/admin_loader.gif">

                                </td>
                                <td id='drc1_1' style='display:none;'>Fire TV Channel apk is Built and is ready to download. <a id='drcLink1' href=''>Click here to Download Fire TV App</a></td>
                            </tr>
                        </table>
                    </div>

                    <div class='tab-content tab_wrapper' id="tab7">

                      {{ Form::open(array(
                           'url' => 'channel_'. BaseController::get_channel_id() . '/set_new_launchpad_url',
                           'class' => 'form-horizontal',
                           'id' => 'set_project_managment_url'
                         ))
                      }}

                        <div class="control-group">
                           {{ Form::label('urlProjectManagment', 'Enter URL for Project Managment', array('class' => 'control-label')) }}
                           <div class='controls'>
                                @if (is_null($launchpad_url))
                                    {{ Form::text('urlProjectManagment', '', array('class' => 'form-control z-index-1', 'id' => 'newLanchpadUrl')) }}
                                @else
                                    {{ Form::text('urlProjectManagment', "$launchpad_url", array('class' => 'form-control z-index-1', 'id' => 'newLanchpadUrl')) }}
                                @endif
                           </div>
                        </div>

                        <div class="input-group saveBtn">
                          <div class="controls">
                            <!-- {{ Form::submit('Set New URL', array('class' => 'btn btn-inverse')) }} -->
                            <input type='button' onclick='SetNewLaunchpadUrl()' value='Set New URL' class='btn btn-inverse' onclic />
                          </div>
                        </div>
                      {{ Form::close() }}
                    </div>
                    <!-- Mobile-web TV -->
                    <div class='tab-content tab_wrapper' id="tab10">

                      {{ Form::open(array(
                           'url' => 'channel_'. BaseController::get_channel_id() . '/set_new_mobileWeb_url',
                           'class' => 'form-horizontal',
                           'id' => 'set_project_managment_url'
                         ))
                      }}

                        <div class="control-group">
                           {{ Form::label('urlMobileWeb', 'Enter URL for Mobile-Web TV', array('class' => 'control-label')) }}
                           <div class='controls'>
                                @if (is_null($mobileWebUrl))
                                    {{ Form::text('urlMobileWeb', '', array('class' => 'form-control z-index-1', 'id' => 'newMobileWebUrl')) }}
                                @else
                                    {{ Form::text('urlMobileWeb', "$mobileWebUrl", array('class' => 'form-control z-index-1', 'id' => 'newMobileWebUrl')) }}
                                @endif
                           </div>
                        </div>

                        <div class="input-group saveBtn">
                          <div class="controls">
                            <!-- {{ Form::submit('Set New URL', array('class' => 'btn btn-inverse')) }} -->
                            <input type='button' onclick='SetNewMobileWebUrl()' value='Set New URL' class='btn btn-inverse' onclic />
                          </div>
                        </div>
                      {{ Form::close() }}
                    </div>

                    <div class="tab-content tab_wrapper" id="tab5">

                        <table class="table-contain" cellspacing='20'><tr><td colspan=2>
                            {{ Form::checkbox('analytics', '1', $analytics[0], array('id' => 'analytics')) }} <span class="analytics-span">Enable Analytics</span>
                        </td></tr>
                        <tr>
                            <td>{{ Form::label('', 'Customer Key', array('class' => 'control-label')) }}</td>
                            <td>{{ Form::text('analytics_customer_key', $analytics[1], array('class' => 'form-control z-index-1', 'id' => 'analytics_customer_key')) }}</td>
                        </tr>
                        <tr>
                            <td>{{ Form::label('', 'Streamlyzer User ID', array('class' => 'control-label')) }}</td>
                            <td>{{ Form::text('analytics_user_id', $analytics[2], array('class' => 'form-control z-index-1', 'id' => 'analytics_user_id')) }}</td>
                        </tr>
                        <tr>
                            <td>{{ Form::label('', 'Streamlyzer Token', array('class' => 'control-label')) }}</td>
                            <td>{{ Form::text('analytics_token', $channel['streamlyzer_token'], array('class' => 'form-control z-index-1', 'id' => 'analytics_token')) }}</td>
                        </tr>
                        <tr>
                            <td>{{ Form::label('', 'Streaming Server Name', array('class' => 'control-label')) }}</td>
                            <td>{{ Form::text('analytics_server_name', $analytics[3], array('class' => 'form-control z-index-1', 'id' => 'analytics_server_name')) }}</td>
                        </tr>
                        <tr>
                            <td>{{ Form::label('', 'Google Analytics Tracking ID', array('class' => 'control-label')) }}</td>
                            <td>{{ Form::text('tracking_id', $channel['tracking_id'], array('class' => 'form-control z-index-1', 'id' => 'tracking_id')) }}</td>
                        </tr>
                        <tr><td colspan=2>
                                {{ Form::button('Save Analytics', array('class' => 'btn btn-inverse btn-success', 'onclick' => 'SaveAnalytics()')) }}
                        </td></tr>
                        </table>
                        <!-- Analytics -->
                        <div class="analyticsWrapper">
                            <ul class="tabs tabChanger">
                                <li class="active" onclick='OnTabChangeAnal(1)'><a href="#tabAnal2">Live Analytics</a></li>
                                <li onclick='OnTabChangeAnal(2)'><a href="#tabAnal1">No Of Viewers</a></li>
                                <li onclick='OnTabChangeAnal(3)'><a href="#tabAnal2">Viewer Time Distribution</a></li>
                                <li onclick='OnTabChangeAnal(5)'><a href="#tabAnal5">VOD Popularity</a></li>
                            </ul>
                            <table width='100%'>
                                <tr><td colspan=2>
                                    <div id="schedule-body" class="height content">
                                        Streamlyzer website is opened in another tab.
                                    </div>
                                </td></tr>
                                <tr>
                                    <td class="" valign='top'>
                                        <div style='border:1px solid #777;padding:5px;' id="tabAnal1">
                                            <canvas id="tab1Chart" style='width:400px;height:300px;'></canvas>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!--  -->

                    </div>
                    <!-- End tab5 -->
                      
                    <!-- Tab 8 - Subscription -->
                    <div class="tab-content tab_wrapper" id="tab8">

                        <div class="panel panel-default account_information">
                            <div class="panel-heading account_info">
                                <div class="sb_header">Account Information</div>
                                <div class="sb_edit">
                                    <a class = "show_modal" href="javascript:void(0)" data-action = "account_info"><span><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>Edit</a>
                                </div>
                                <hr>
                                <div class="sb_content">
                                    <div class="sb_account_information_first">
                                    <input type="hidden" name="customer_id" id = "customer_id" value = "{{ $customer->id }}">
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td class="sb_label">First Name</td>
                                                    <td>{{ $customer->firstName }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="sb_label">Company</td>
                                                    <td>{{ $customer->company }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="sb_label">Phone</td>
                                                    <td>{{ $customer->phone }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="sb_account_information_last">
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td class="sb_label">Last Name</td>
                                                    <td>{{ (isset($customer->lastName) && !empty($customer->lastName)) ? $customer->lastName : ''}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="sb_label">Email</td>
                                                    <td>{{ $customer->email }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="sb_label"></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                     <!--    <div class="panel panel-default account_information">
                            <div class="panel-heading payment_mode">
                                <div class="sb_header">Payment Mode</div>
                                <div class="sb_edit">
                                    <a class = "show_modal" href="javascript:void(0)" data-action = "payment_mode"><span><i class="fa fa-plus" aria-hidden="true"></i></span>Add</a>
                                </div>
                                <hr>
                                <div class="sb_content">
                                </div>
                            </div>
                        </div> -->
                        <div class="panel panel-default account_information">
                            <div class="panel-heading payment_method">
                                <div class="sb_header">Payment Method Information</div>
                                <div class="sb_edit">
                                    <a class = "show_modal" href="javascript:void(0)" data-action = "payment_method"><span><i class="fa fa-plus" aria-hidden="true"></i></span>{{ !empty($card) ? 'Edit' : 'Add' }}</a>
                                </div>
                                <hr>
                                @if(!empty($card))
                                    <div class="sb_content">
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td class="sb_label">Card Type</td>
                                                    <td>{{ $card->cardType }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="sb_label">Card number</td>
                                                    <td>xxxx xxxx xxxx {{ $card->last4 }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="sb_label">Expiry</td>
                                                    <td>{{ $card->expiryMonth }}/{{ $card->expiryYear }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="sb_label">Status</td>
                                                    <td>{{ $card->status }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="panel panel-default account_information">
                            <div class="panel-heading payment_method">
                                <div class="sb_header">Billing Information</div>
                                <div class="sb_edit">
                                    <a class = "show_modal" href="javascript:void(0)" data-action = "billing_info"><span><i class="fa fa-plus" aria-hidden="true"></i></span>{{ empty($billingInfo) ? 'Add' : 'Edit'}}</a>
                                </div>
                                <hr>
                                @if(!empty($billingInfo))
                                <div class="sb_content">
                                <!-- Content -->
                                    <div class="sb_billing_information">
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td>{{ $billingInfo->firstName }} {{ (isset($billingInfo->lastName) && !empty($billingInfo->lastName)) ? $billingInfo->lastName  : ''}}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ $billingInfo->line1 }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ $billingInfo->city }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ $billingInfo->state }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ $billingInfo->phone }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- end -->
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Payment info -->
                        <div class="panel panel-default payment_information">
                            <div class="panel-heading payment_history">
                                <div class="sb_header">Payment history</div>
                                <hr>
                                @if(!empty($invoicesArray) && count($invoicesArray) > 0)
                                <div class="sb_content">
                                <!-- Content -->
                                    <div class="sb_payment_history">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                    <th>Invoice Number</th>
                                                    <th>Amount</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($invoicesArray as $item => $invoice)
                                                    <tr>
                                                        <td>{{ $invoice->status }}</td>
                                                        <td>{{ (isset($invoice->date) && !empty($invoice->date)) ? $invoice->date : '' }}</td>
                                                        <td>{{ $invoice->id }}</td>
                                                        <td>{{ (isset($invoice->total) && !empty($invoice->total)) ? $invoice->total : '' }}</td>
                                                        @if($invoice->status != 'paid')
                                                            <!-- <td><span class="btn btn-success">Pay</span></td> -->
                                                        @endif
                                                        <td></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- end -->
                                </div>
                                @endif
                            </div>
                        </div><!-- End payment info -->

                        <!-- Show Subscriptions -->
                        @if(count($subscriptionsArray) > 0)
                            @foreach($subscriptionsArray as $key => $subscription)
                                <div class="panel panel-default account_information">
                                    <div class="panel-heading payment_method">
                                        <div class="sb_header">Subscription #{{ $subscription->id }} 
                                            <span class="sub_status btn btn-default">{{ $subscription->status }}</span>
                                        </div>
                                        <div class="sb_edit">
                                        </div>
                                        <hr>
                                        <div class="sb_content">
                                        <!-- Content -->
                                            <div class="sb_subsc_information">
                                               <table>
                                                    <tbody>
                                                        <tr>
                                                            <td class="sb_label">Plan - {{ $subscription->planId }}</td>
                                                            <td>{{ $subscription->planQuantity }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="sb_label"></td>
                                                            <td></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="cancel_wrapper">
                                                @if($subscription->status !== 'cancelled')
                                                    <span class="sb_cancel">
                                                        <a class="cancel_sub" href = "javascript:void(0)" data-id = "{{ $subscription->id }}">Cancel </a>this subscription
                                                    </span>
                                                @endif
                                            </div>
                                            <!-- end -->
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        <!-- End Subscriptions -->
                    </div>
                    <!-- End Tab 8 -->

                    <!-- Tab9 Advertising -->
                    <div class='tab-content tab_wrapper' id="tab9">
                        <h4 class="subTitle">PreRolls</h4>
                      {{ Form::open(array(
                           'url' => 'channel_'. BaseController::get_channel_id() . '/set_preRolls',
                           'class' => 'form-horizontal',
                           'id' => 'set_preRolls'
                         ))
                      }}

                        <div class="control-group">
                            {{ Form::label('preroll', 'Do you want to set pre rolls on or off?', array('class' => 'control-label')) }}
                            <div class="controls prerollWrapper">
                                {{ Form::select('preroll', ['0' => 'Off', '1' => 'On'], $channel['prerolls'], array('id' => 'preroll', 'style' => 'width:200px;margin:0 5px 0 5px;height:32px;')) }}
                            </div>
                        </div>

                        <div class="input-group saveBtn">
                          <div class="controls">
                            {{ Form::submit('Save', array('class' => 'btn btn-inverse')) }}
                          </div>
                        </div>
                      {{ Form::close() }}
                    </div>

                    <!-- Tab4 Distribution -->
                    <div class="tab-content distroWrapper tab_wrapper" id="tab4">
                        <div class="distroItem">
                            <div class="col-md-5 distroDisplay">
                                <div class="form-group">
                                    <h5 class="distro_name">
                                       Roku TV
                                    </h5>
                                    <div class="options_box">
                                        <input type = "radio" data-distro = 'roku' name = "checkRoku" value = "yes" class="display_distro" {{ $channel['display_roku'] == 1 ? 'checked' : '' }}> Yes
                                        <input type = "radio" data-distro = 'roku' name = "checkRoku" value = "no" class="display_distro" {{ $channel['display_roku'] == 0 ? 'checked' : '' }}> No
                                    </div>
                                </div> 
                            </div>
                            <div class="col-md-7 distroLink {{ ($channel['display_roku'] == 0) ? 'hidden_distro' : '' }}">
                                <form class="form-inline" action="setRokuUrl" method="post">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="form-group">
                                       <label class="control-label">Enter URL</label>
                                        <input class="form-control" name="rokuUrl" type="text" value="{{ (isset($channel['roku_tv_url']) && !empty($channel['roku_tv_url']))? $channel['roku_tv_url'] : 'https://channelstore.roku.com/browse' }}">
                                    </div>
                                    <input type="submit" value="Set New URL" class="btn btn-inverse">
                                </form>
                            </div>
                        </div>
                        <div class="distroItem">
                            <div class="col-md-5 distroDisplay">
                                <div class="form-group">
                                    <h5 class="distro_name">
                                       Apple TV
                                    </h5>
                                    <div class="options_box">
                                        <input type = "radio" data-distro = 'apple' name = "checkApple" value = "yes" class="display_distro" {{ $channel['display_appletv'] == 1 ? 'checked' : '' }}> Yes
                                        <input type = "radio" data-distro = 'apple' name = "checkApple" value = "no" class="display_distro" {{ $channel['display_appletv'] == 0 ? 'checked' : '' }}> No
                                    </div>
                                </div> 
                            </div>
                            <div class="col-md-7 distroLink {{ ($channel['display_appletv'] == 0) ? 'hidden_distro' : '' }}">
                                <form class="form-inline" action="setAppleUrl" method="post">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="form-group">
                                       <label class="control-label">Enter URL</label>
                                        <input class="form-control" name="appleUrl" type="text" value="{{ (isset($channel['apple_tv_url']) && !empty($channel['apple_tv_url']))? $channel['apple_tv_url'] : 'https://www.apple.com/tv/' }}">
                                    </div>
                                    <input type="submit" value="Set New URL" class="btn btn-inverse">
                                </form>
                            </div>
                        </div>
                        <div class="distroItem">
                            <div class="col-md-5 distroDisplay">
                                <div class="form-group">
                                    <h5 class="distro_name">
                                       Amazon Fire TV
                                    </h5>
                                    <div class="options_box">
                                        <input type = "radio" data-distro = 'amazon' name = "checkFire" value = "yes" class="display_distro" {{ $channel['display_firetv'] == 1 ? 'checked' : '' }}> Yes
                                        <input type = "radio" data-distro = 'amazon' name = "checkFire" value = "no" class="display_distro" {{ $channel['display_firetv'] == 0 ? 'checked' : '' }}> No
                                    </div>
                                </div> 
                            </div>
                            <div class="col-md-7 distroLink {{ ($channel['display_firetv'] == 0) ? 'hidden_distro' : '' }}">
                                <form class="form-inline" action="setAmazonUrl" method="post">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="form-group">
                                       <label class="control-label">Enter URL</label>
                                        <input class="form-control" name="amazonUrl" type="text" value="{{ (isset($channel['amazon_fire_url']) && !empty($channel['amazon_fire_url']))? $channel['amazon_fire_url'] : 'https://www.amazon.com/Fire-TV-Apps-All-Models/b?ie=UTF8&node=10208590011' }}">
                                    </div>
                                    <input type="submit" value="Set New URL" class="btn btn-inverse">
                                </form>
                            </div>
                        </div>
                        <div class="distroItem">
                            <div class="col-md-5 distroDisplay">
                                <div class="form-group">
                                    <h5 class="distro_name">
                                       Mobile - Web TV
                                    </h5>
                                    <div class="options_box">
                                        <input type = "radio" data-distro = 'mobile' name = "checkMobile" value = "yes" class="display_distro" {{ $channel['display_appletv'] == 1 ? 'checked' : '' }}> Yes
                                        <input type = "radio" data-distro = 'mobile' name = "checkMobile" value = "no" class="display_distro" {{ $channel['display_appletv'] == 0 ? 'checked' : '' }}> No
                                    </div>
                                </div> 
                            </div>
                            <div class="col-md-7 distroLink {{ ($channel['display_mobileweb'] == 0) ? 'hidden_distro' : '' }}">
                                <form class="form-inline" action="setMobileUrl" method="post">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="form-group">
                                       <label class="control-label">Enter URL</label>
                                        <input class="form-control" name="mobileUrl" type="text" value="{{ (isset($channel['mobileWebUrl']) && !empty($channel['mobileWebUrl']))? $channel['mobileWebUrl'] : 'http://onestudio.tv/' }}">
                                    </div>
                                    <input type="submit" value="Set New URL" class="btn btn-inverse">
                                </form>
                            </div>
                        </div>

                       
                    </div>
                    <!-- End tab4 -->

                    {{--Youtube channel importer--}}
                    <div class="tab-content youtube_code tab_wrapper" id="tab11">
                        <div class="panel panel-primary" id = "download_wrapper">
                            <div class="panel-heading">
                                <div class="panel-heading">
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a href="#channel_download" data-toggle="tab">
                                                <h3 class="panel-title">Youtube Channel Downloader</h3>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#video_download" data-toggle="tab">
                                                <h3 class="panel-title">Youtube Video Downloader</h3>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="tab-content">
                                    <div class="tab-pane fade in active" id="channel_download">
                                        <div class="status_wrapper">
                                            <p class="status_success text-success"></p>
                                            <p class="status_failed text-danger"></p>
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-addon" id="sizing-addon1">Channel ID:</span>
                                            <input id="youtube_channelId" type="text" class="form-control" placeholder="Enter your channel ID">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button" id = "import_btn">Download</button>
                                            </span>
                                        </div>
                                        <div class="well well-sm">Quality:
                                            <select id="cbQuality" class="combobox">
                                                <option value="hd720-mp4">MP4 720P</option>
                                                <option value="medium-mp4">MP4 360P</option>
                                                <option value="medium-webm">WebM 360P</option>
                                                <option value="small-3gpp">3GP 240P</option>
                                            </select>
                                            <span style="margin-left: 11px;" data-toggle="tooltip" data-placement="bottom" title="Auto add order number in filename like: 01.video, 02.video  ">
                                                <input id="chkAutoOrder" type="checkbox">Add prefix order number
                                            </span>
                                            <span style="margin-left: 11px;" data-toggle="tooltip" data-placement="bottom" title="Auto reduce quality if target quality doesn't exist, ex: 1080P -> 720P -> 480P... ">
                                                <input id="chkAutoQuality" type="checkbox">Auto reduce quality if not exist
                                            </span>
                                        </div>
                                        <div id = "video_loading">
                                            <img src="/images/loader.gif" alt="">
                                        </div>
                                        <a class="btn btn-default pull-right" id = "download_all">Download All</a>
                                        {{-- Videos table --}}
                                        <div class="table-responsive">
                                            <table class="table" id = "channel_videos">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Image</th>
                                                    <th>Title</th>
                                                    <th>Download</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div><!-- End channel tab-->
                                    <div class="tab-pane fade" id="video_download">
                                        <div class="notify main clearfix column">
                                            <form>
                                                <div id="step1" class="step">
                                                    <input id="video_url" type="text" class="textbox" placeholder="Enter Valid Video URL ..."/>
                                                    <!-- <input id="download_btn" class="md-trigger md-setperspective" data-modal="top-scroll" type="submit" value="Download"> -->
                                                    <!-- Add username part-->
                                                    <div class = "options_container">
                                                        <div class = "actions_wrapper text-center">
                                                            <a class = "submit" id = "editVideo" class="md-trigger md-setperspective">Edit Video</a>
                                                            <input id="download_btn" class="md-trigger md-setperspective" data-modal="top-scroll" type="submit" value="Download">
                                                        </div>
                                                        <div id  = "editVideoWrapper" class = "form_wrapper">
                                                            <div class="control-group">
                                                                <label for="video_username" class="control-label">Username</label>
                                                                <div class="controls">
                                                                    <input class="form-control z-index-1" id="video_username" name="username" type="text" value="">
                                                                </div>
                                                            </div>
                                                            <div class="control-group">
                                                                <label for="color" class="control-label">Color</label>
                                                                <div class="controls">
                                                                    <input id="textColor" name="text_color" type="color" value = "#ffffff">
                                                                </div>
                                                            </div>
                                                            <div class="control-group">
                                                                <label for="font" class="control-label">Font size</label>
                                                                <div class="controls">
                                                                    <input id="fontsize" class="form-control z-index-1" name="fontsize" type="number" value = '34'>
                                                                </div>
                                                            </div>
                                                            <div class="control-group">
                                                                <label for="display_time" class="control-label">Time of #username</label>
                                                                <div class="controls">
                                                                    <input id="display_time" class="form-control z-index-1" name="display_time" type="number" value = '10'>
                                                                </div>
                                                            </div>
                                                            <div class="control-group">
                                                                <label for="display_time" class="control-label">Display Username at the:</label>
                                                                <div class="controls">
                                                                    <label>Start of video</label>
                                                                    <input type = 'radio' class="z-index-1" name="start_time" value = 'start' checked> Yes
                                                                </div>
                                                                <div class="controls">
                                                                    <label>End of video</label>
                                                                    <input type = 'radio' class="z-index-1" name="start_time" value = 'end'> Yes
                                                                </div>
                                                            </div>
                                                            <div class="control-group">
                                                                <div class="controls">
                                                                    <input id="saveDownload" class="md-trigger md-setperspective" data-modal="top-scroll" type="submit" value="Save and Download">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- End username part -->
                                                </div>

                                                <div id="step2" class="step">
                                                    <a id='titleVideo' target="_blank"></a> <!-- max len: 60 -->

                                                    <div class='clear'>&nbsp;</div>
                                                    <img src='' alt='thumbnail' id='thumb' class='thumbnail'/>
                                                    <input type = "hidden" id = "format" value = "">
                                                    <a class='color'>Duration</a> &mdash; <span id='duration'></span>
                                                    <br/><br/>
                                                    Author &mdash; <a class='color' id='author'></a>
                                                    <br/><br/>
                                                    <a class='color'>Views</a> &mdash; <span id='view_count'></span>
                                                    <br/><br/>
                                                    Is video public &mdash; <a class='color' id='is_listed'></a>

                                                    <div class="clear">&nbsp;</div>

                                                    <div class='center-align'>
                                                        <select class='' id='formats'></select>

                                                        <div class="clear">&nbsp;</div>
                                                        <div class="clear">&nbsp;</div>
                                                        <div id='after-selection'>
                                                            <a class='submit' id='dwn_anchor'>Download your file</a>
                                                            <div class="clear">&nbsp;</div>
                                                            <div class="clear">&nbsp;</div>
                                                            <a class='submit inverse_color' id='start_over'>Start over</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id='loadbar'><img src='{{ URL::to('/') }}/youtube_importer/images/load.gif'/></div>
                                            </form>
                                        </div>
                                    </div><!-- End video dowsloader-->

                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- + Link video tab--}}
                    <div class="tab-content linkvideoWrapper tab_wrapper" id="tab6">
                        <div class="row center-block height" id="contnet-wrap">
                            <div class="col-md-12 height" id="upload" style="text-align: center">
                                <div id="upload-body" class="height content">
                                    <p class="title-name text-center">Add External +Link for Video (Beta)</p>
                                    <div class="col-md-12 height">
                                        <table width='100%'>
                                            <tr><td id = "errorMsg"></tr></td>
                                            <tr><td>
                                                    <div class="col-md-3"> </div>
                                                    <div class="col-md-6">
                                                        <div class="control-group">
                                                            <div class="controls">
                                                                {{ Form::select('source_dd', [
                                                                   'unknown' => "Select",
                                                                   'vimeo' => 'Vimeo',
                                                                   'aws' => 'Amazon Web Server',
                                                                   'dacast' => 'DaCast Server',
                                                                   'wistia' => 'Wistia Server',
                                                                   ],
                                                                   $source_dd,
                                                                   array('class' => 'form-control', 'id' => 'source_dd', 'onchange' => 'OnSourceChanged(this)')
                                                                ) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td></tr>

                                            <tr style='display:none;' id='source_aws'><td>
                                                    <table width='100%'>
                                                        <tr><td>
                                                                <div class="col-md-3"> </div>
                                                                <div class="col-md-6">
                                                                    <div class="control-group">
                                                                        {{ Form::label('api_key', 'AWS Access Key ID', array('class' => 'control-label')) }}
                                                                        <div class="controls">
                                                                            {{ Form::text('api_key', '', array('class' => 'form-control', 'id' => 'api_key' )) }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td></tr>

                                                        <tr><td>
                                                                <div class="col-md-3"> </div>
                                                                <div class="col-md-6">
                                                                    <div class="control-group">
                                                                        {{ Form::label('api_secret_key', 'AWS Secret Access Key', array('class' => 'control-label')) }}
                                                                        <div class="controls">
                                                                            {{ Form::text('api_secret_key', '', array('class' => 'form-control', 'id' => 'api_secret_key' )) }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td></tr>

                                                        <tr><td>
                                                                <input type='hidden' id='buckets' value=''>
                                                                <input type='hidden' id='bucketFiles' value=''>
                                                                <div class="col-md-3"> </div>
                                                                <div class="col-md-6">
                                                                    <div class="controls">
                                                                        <br>
                                                                        {{ Form::button('Get Buckets', array('class' => 'btn btn-inverse', 'id' => 'aws_button', 'onclick' => 'Aws_fetchBuckets()' )) }}
                                                                        <img id='aws_loader' src="{{ URL::to('/') }}/images/admin_loader.gif" style='width:40px;margin-left:10px;display:none;'>
                                                                    </div>
                                                                </div>
                                                            </td></tr></table>
                                                </td></tr>

                                            <tr style='display:none;' id='source_dacast'><td>
                                                    <table width='100%'>
                                                        <tr><td>
                                                                <div class="col-md-3"> </div>
                                                                <div class="col-md-6">
                                                                    <div class="control-group">
                                                                        {{ Form::label('dacast_api_key', 'Dacast API Key', array('class' => 'control-label')) }}
                                                                        <div class="controls">
                                                                            {{ Form::text('dacast_api_key', '', array('class' => 'form-control', 'id' => 'dacast_api_key' )) }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td></tr>

                                                        <tr><td>
                                                                <input type='hidden' id='vods' value=''>
                                                                <input type='hidden' id='playlists' value=''>
                                                                <div class="col-md-3"> </div>
                                                                <div class="col-md-6">
                                                                    <div class="controls">
                                                                        <br>
                                                                        {{ Form::button('Get Data From DaCast', array('class' => 'btn btn-inverse', 'id' => 'dacast_button', 'onclick' => 'Dacast_fetchData()' )) }}
                                                                        <img id='dacast_loader' src="{{ URL::to('/') }}/images/admin_loader.gif" style='width:40px;margin-left:10px;display:none;'>
                                                                    </div>
                                                                </div>
                                                            </td></tr></table>
                                                </td></tr>

                                            <tr style='display:none;' id='source_wistia'><td>
                                                    <table width='100%'>
                                                        <tr><td>
                                                                <div class="col-md-3"> </div>
                                                                <div class="col-md-6">
                                                                    <div class="control-group">
                                                                        {{ Form::label('wistia_api_key', 'Wistia API Token', array('class' => 'control-label')) }}
                                                                        <div class="controls">
                                                                            {{ Form::text('wistia_api_key', '32edcc0b812a07ef028b0aaf0bc35024457ce360d7b2ba17ab4a101596690bd1', array('class' => 'form-control', 'id' => 'wistia_api_key' )) }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td></tr>

                                                        <tr><td>
                                                                <input type='hidden' id='vods' value=''>
                                                                <input type='hidden' id='playlists' value=''>
                                                                <div class="col-md-3"> </div>
                                                                <div class="col-md-6">
                                                                    <div class="controls">
                                                                        <br>
                                                                        {{ Form::button('Get Data From Wistia', array('class' => 'btn btn-inverse', 'id' => 'wistia_button', 'onclick' => 'Wistia_fetchData()' )) }}
                                                                        <img id='wistia_loader' src="{{ URL::to('/') }}/images/admin_loader.gif" style='width:40px;margin-left:10px;display:none;'>
                                                                    </div>
                                                                </div>
                                                            </td></tr></table>
                                                </td></tr>

                                            <tr id='extLinkGroup'><td>
                                                    <div class="col-md-3"> </div>
                                                    <div class="col-md-6">
                                                        <div class="control-group">
                                                            {{ Form::label('ext_link', 'External Video Link', array('class' => 'control-label')) }}
                                                            <div class="controls">
                                                                {{ Form::text('ext_link', $extLink, array('class' => 'form-control', 'id' => 'ext_link', 'onblur' => 'CopyData()')) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td></tr>

                                            <tr><td id="downloadedVideosAppend">
                                                    {{ $part2 }}
                                                </td></tr>
                                        </table>
                                    </div>
                                </div>
                                <p class="col-md-12" id="resultvup"></p>
                            </div>
                        </div>
                    </div>
                    {{-- Tag manager --}}
                    <div id = "tab12" class="tab-content tab_wrapper">
                        <button id = "add_tag" class="btn btn-primary pull-right" data-toggle="modal" data-target="#addTagModal">New Tag</button>
                        @if(null !== $channel_tags && !empty($channel_tags))
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Channel</th>
                                    <th>Name</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($channel_tags as $tag)
                                    <tr>
                                        <td>{{ $tag->id }}</td>
                                        <td>{{ $tag->channel_id }}</td>
                                        <td>{{ $tag->name }}</td>
                                        <td>
                                            <button data-id = "{{ $tag->id }}" class="btn btn-primary editTag">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                            <button data-id = "{{ $tag->id }}" class="btn btn-primary deleteTag">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <h3>You haven't added any tag</h3>
                        @endif
                    </div>
                    {{-- Show manager --}}
                    <div id = "tab13" class="tab-content tab_wrapper">
                        <div class="activation_wrapper">
                            <h3>Activate Show Manager</h3>
                            <form action="/channel_{{ $channel['id'] }}/activate_show" method="post">
                                <div class="form-group">
                                    <input type="radio" name = "show_status" value = '1' {{ ($channel['display_show'] == 1) ? 'checked' : '' }}> Yes
                                    <input type="radio" name = "show_status" value = '0' {{ ($channel['display_show'] == 0) ? 'checked' : '' }}> No
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                        <button id = "add_show" class="btn btn-primary pull-right" data-toggle="modal" data-target="#addShowModal">New Show</button>
                        @if(null !== $channel_show && count($channel_show) > 0)
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($channel_show as $show)
                                    <tr>
                                        <td>{{ $show->id }}</td>
                                        <td>{{ $show->name }}</td>
                                        <td>
                                            <button data-id = "{{ $show->id }}" class="btn btn-primary editShow">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                            <button data-id = "{{ $show->id }}" class="btn btn-primary deleteShow">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <h3>You haven't added any show</h3>
                        @endif
                    </div>{{-- End tabs --}}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Show modals --}}
<div class="modal fade" id="addShowModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-content load_modal">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Add New Show</h4>
				</div>
				<div class="modal-body">
					<form action="/channel_{{ $channel['id'] }}/addShow" method = "post" class="form-horizontal">
						<div class="form-group">
							<label class="control-label col-sm-2" for="showname">Name:</label>
							<div class="col-sm-10">
								<input type="text" name = "showname" class="form-control" id="showname">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="submit" class="btn btn-primary">Save</button>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>

{{-- Edit show modal--}}
<div class="modal fade" id="editShowModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-content load_modal">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Edit Show</h4>
				</div>
				<div class="modal-body">
					<form action="/channel_{{ $channel['id'] }}/editShow" method = "post" class="form-horizontal">
						<input type="hidden" name = "show_id" id = "show_id" value = ''>
						<div class="form-group">
							<label class="control-label col-sm-2" for="editshowname">Name:</label>
							<div class="col-sm-10">
								<input type="text" name = "editshowname" class="form-control" id="editshowname">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="submit" class="btn btn-primary">Save</button>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>

{{-- Tag modals --}}
<div class="modal fade" id="addTagModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-content load_modal">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add New Tag</h4>
                </div>
                <div class="modal-body">
                    <form action="/channel_{{ $channel['id'] }}/addTag" method = "post" class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="tagname">Name:</label>
                            <div class="col-sm-10">
                                <input type="text" name = "tagname" class="form-control" id="tagname" placeholder="">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Edit tag modal--}}

<div class="modal fade" id="editTagModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-content load_modal">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Tag</h4>
                </div>
                <div class="modal-body">
                    <form action="/channel_{{ $channel['id'] }}/editTag" method = "post" class="form-horizontal">
                        <input type="hidden" name = "tag_id" id = "tag_id" value = ''>
                        <div class="form-group">
                            <label class="control-label col-sm-2" for="edittagname">Name:</label>
                            <div class="col-sm-10">
                                <input type="text" name = "edittagname" class="form-control" id="edittagname" placeholder="">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="chargebeeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-content load_modal"></div>
        </div>
    </div>
</div>

<!-- Analytics JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.min.js"></script>
<script language='javascript'>

var myChart;
var menuid = 0;
var rMin = 0, rMax = 99991231;
var subMenu = "Y";
var historyData = '';

window.onload = function()
{
    window.open('https://dashboard.streamlyzer.com/', 'analytics');
    OnTabChangeAnal('1');

    $("#tab1Chart").click(function(e) {
        var elementsArray = myChart.getElementAtEvent(e);
        if (elementsArray.length > 0)
        {
            e.preventDefault();
            e.stopPropagation();

            k = elementsArray[0]._index;
            if (menuid==2 || menuid==3)
            {
                if (subMenu=='Y') { subMenu = 'M'; rMin = myChart.data.labels[k] + '0101';  rMax = myChart.data.labels[k] + '1231'; DrawGraph(); return;  }
                else if (subMenu=='M') { subMenu = 'D'; rMin = myChart.data.labels[k]; rMax = historyData; DrawGraph(); return; }
                else if (subMenu=='D') { subMenu = 'H'; rMin = myChart.data.labels[k]; rMax = historyData; DrawGraph(); return; }
            }
        }
    });
}

function SetinitialMenuOptions(x)
{
    if (x==1) { menuid = x; rMin = 0; rMax = 0; subMenu = ""; }
    else if (x==2 || x==3) { menuid = x; rMin = 0; rMax = 99991231; subMenu = "Y"; }
}

function OnTabChangeAnal(x)
{
    SetinitialMenuOptions(x);
    DrawGraph();
}

function DrawGraph()
{
    $.ajax({
        url: ace.path('analytics_get_data_for'),
        type: "GET",
        data: { "menuid"  : menuid,
                "rmin"    : rMin,
                "rmax"    : rMax,
                "submenu" : subMenu 
              },
        success: function (msg) {
            if (menuid==1) DrawLiveGraph(msg);
            else DrawGraphResponse(menuid, msg);
        }
    });
}

function DrawLiveGraph(msg)
{
    parts = msg.split('^');
    while(parts.length < 10) parts.push('');

    if (myChart != undefined) myChart.destroy();
    var ctx = document.getElementById('tab1Chart').getContext('2d');
    myChart = new Chart(ctx, { type: 'line',
                               data: { labels: parts[0].split(';'),
                                       datasets: [{ label: parts[1], data: parts[3].split(';'), backgroundColor: parts[2] },
                                                  { label: parts[4], data: parts[6].split(';'), backgroundColor: parts[5] },
                                                  { label: parts[7], data: parts[9].split(';'), backgroundColor: parts[8] }
                                                 ]
                                     },
                               options: { title: { display: true, text: "Live chart of last 30 minutes" },
                                         scales: { yAxes: [{ display: true, 
                                                            ticks: { beginAtZero: true } 
                                                          }]
                                                 }
                                        }
                       });
    window.setTimeout('DrawGraph()', 30000);
}

function DrawGraphResponse(x, msg)
{
    parts = msg.split('^');
    while(parts.length < 5) parts.push('');

    if (myChart != undefined) myChart.destroy();
    var ctx = document.getElementById('tab1Chart').getContext('2d');
    myChart = new Chart(ctx, { type: 'bar',
                               data: { labels: parts[0].split(';'),
                                       datasets: [{ label: parts[1], data: parts[2].split(';'), backgroundColor: "rgba(153,255,51,1)" }]
                                     },
                               options: { title: { display: true, text: parts[3] },
                                         scales: { yAxes: [{ display: true, 
                                                            ticks: { beginAtZero: true } 
                                                          }]
                                                 }
                                        }
                       });

    historyData = parts[4];
}

</script>

<!-- End Analytics JS -->


<script language='javascript'>

function ToggleApiUrl(ctrlID)
{
    if (ctrlID == 'login')
    {
        document.getElementById('yesW1').style.display = 'none';
        document.getElementById('yesW2').style.display = 'none';
        document.getElementById('yesW3').style.display = 'none';
        document.getElementById('yesW4').style.display = 'none';

        if ($('#login').val()=='no') return;
        document.getElementById('yesW4').style.display = '';

        if ($('#loginMode').val()=='yesW') 
        {
            document.getElementById('yesW1').style.display = '';     
            document.getElementById('yesW2').style.display = '';     
            document.getElementById('yesW3').style.display = '';     
        }
        else if ($('#loginMode').val()=='yesR') 
        {
            document.getElementById('yesW1').style.display = '';     
        }
    }
    else if (ctrlID == 'source')
    {
        document.getElementById('source1').style.display = 'none';
        document.getElementById('source2').style.display = 'none';
        if (ctrlID=='ustream') 
        {
            document.getElementById('source1').style.display = '';
            document.getElementById('source2').style.display = '';
        }
    }
}

function OnBuild(x)
{
    var path = 'settings_build_roku_channel';
    if (x==1) path = 'settings_build_fireTV_channel';

    $('#loader'+x).show();
    $.ajax({
        url: ace.path(path), // settings_build'),
        type: "POST",
        success: function (data) {
            $('#loader'+x).hide();
            $('#drc'+x).hide();
            if(data == "false"){
                document.getElementById('drc'+x+'_1').style.display='none';
                document.getElementById('zipError').style.display='';
            }
            else{
                document.getElementById('zipError').style.display='none';
                document.getElementById('drc'+x+'_1').style.display='';
                document.getElementById('drcLink'+x).href = data;
            }
        }
    });
}

function SetNewLaunchpadUrl(){

    var new_url = $('#newLanchpadUrl').val();

    $.ajax({
        url: ace.path('set_new_launchpad_url'),
        type: 'POST',
        data: {url: new_url},
        success: function(res){

            if(res == 'success'){

                location.reload();
            }else{
                alert('Sorry, something goes wrong !');
            }
        }
    })
}

function SetNewMobileWebUrl(){

    var new_url = $('#newMobileWebUrl').val();

    $.ajax({
        url: ace.path('set_new_mobileWeb_url'),
        type: 'POST',
        data: {url: new_url},
        success: function(res){

            if(res == 'success'){

                location.reload();
            }else{
                alert('Sorry, something goes wrong !');
            }
        }
    })
}

function SaveAnalytics()
{
    var param = '0|';
    if ($('#analytics').is(':checked')) param = '1|';  
    param += $('#analytics_customer_key').val() + '|' + $('#analytics_user_id').val() + '|' + $('#analytics_server_name').val();  
    var token = $('#analytics_token').val();
    var tracking_id = $('#tracking_id').val();
    $.ajax({
        url: ace.path('settings_analytics'),
        type: "POST",
        data: {  "status" : param , 'token' : token, 'tracking_id': tracking_id},
        success: function (data) {
            if (data=='success')
            {
                if (param.substring(0,1)=='1') alert('Analytics Saved and Enabled...');
                if (param.substring(0,1)=='0') alert('Analytics Saved and Disabled...');
            }
        }
    });
}

window.onload = function()
{
    ToggleApiUrl('login');
    @if(Session::has('tab'))
        OnTabChange({{ Session::get('tab') }});
    @endif
    $('#source_video').val('external');
    $('#catGroup').hide();
    ShowForm();
}

function ShowHelp(m)
{
    var obj = document.getElementById('h'+m);
    if (obj.style.display=='block') obj.style.display='none';
    else obj.style.display='block';
}

// + link video

function CopyData()
{
    $('#encoded-video-id').val( $('#ext_link').val() );
    $('#filename').val( $('#ext_link').val() );
    $('#video-format').val("mp4");
}

function OnSourceChanged(me)
{
    $('#source_video').val(me.value);
    ShowForm();
}

function ShowForm()
{
    var me_value = $('#source_video').val();

    $('#extLinkGroup').show();
    $('#nameGroup').show();
    $('#descGroup').show();
    $('#saveGroup').show();

    $('#source_aws').hide();
    $('#source_dacast').hide();
    $('#source_wistia').hide();
    if (me_value=='vimeo')
    {
        $('#nameGroup').hide();
        $('#descGroup').hide();
    }
    else if (me_value=='aws')
    {
        $('#nameGroup').hide();
        $('#descGroup').hide();
        $('#extLinkGroup').hide();
        $('#saveGroup').hide();

        $('#source_aws').show();
    }
    else if (me_value=='dacast')
    {
        $('#nameGroup').hide();
        $('#descGroup').hide();
        $('#extLinkGroup').hide();
        $('#saveGroup').hide();

        $('#source_dacast').show();
    }
    else if (me_value=='wistia')
    {
        $('#nameGroup').hide();
        $('#descGroup').hide();
        $('#extLinkGroup').hide();
        $('#saveGroup').hide();

        $('#source_wistia').show();
    }
}

function Wistia_fetchData()
{
    $('#wistia_loader').show();
    $.ajax({
        url: ace.path('wistia_buckets'),
        type: "POST",
        data: { key : $('#wistia_api_key').val(), secret : '' },
        success: function (data) {
            $('#wistia_loader').hide();
            $('#wistia_button').hide();

            out = "<table style='border:1px solid #777;text-align:left;margin-top:20px;margin-bottom:20px;margin-left:25%;background:#fff;width:50%;'>\n";
            out += "<tr><td colspan=2 align='center'><b>Select Wistia Project to pull videos</b></td></tr>\n";
            //out += "<tr><td colspan=2 align='center'><input type='checkbox' value='catBuckets' id='catBuckets'>&nbsp;&nbsp;Create Category for selected buckets<hr></td></tr>\n";
            buckets = data.split('^');
            for (k = 0, i = 0 ; i < buckets.length ; i+=2, k++)
            {
                out += "<tr><td><input id='bucket_"+k+"' style='margin-left:10px;' type='checkbox' value='" + buckets[i] + "'>&nbsp;&nbsp;" + buckets[i+1] + "</td>";
                out += "<td id='" + buckets[i] + "_status' count='0' total='0'></td></tr>\n";
            }
            out += "<tr><td colspan=2 style='border-top:1px solid #000;'></td></tr>\n";
            out += "<tr><td colspan=2 align='center'><input style='margin-bottom:10px;' type='checkbox' class='btn btn-inverse' id='mapSections' value='1'>&nbsp; Map Wistia sections to Onestudio categories</td></tr>\n";
            out += "<tr><td colspan=2 align='center'><input style='margin-bottom:10px;' type='checkbox' class='btn btn-inverse' id='createTree' value='1'>&nbsp; Create Tree Structure</td></tr>\n";
            out += "<tr><td colspan=2 align='center'><input style='margin-bottom:10px;' type='button' class='btn btn-inverse' value='Link Videos' onclick='Wistia_linkVideos()'></td></tr>\n";
            out += "</table>\n";
            document.getElementById('downloadedVideosAppend').innerHTML = out;
        }
    });
}

function Wistia_linkVideos()
{
    buckets = [];
    for ( i = 0 ; i < 9999 ; i++)
    {
        obj = document.getElementById('bucket_'+i);
        if (obj == null) break;
        if (obj.checked) buckets.push(obj.value);
    }
    if (buckets.length == 0) { alert('Atleast one project must be selected'); return; }

    $.ajax({
        url: ace.path('wistia_create_videos'),
        type: "POST",
        data: {         key : $('#wistia_api_key').val(),
            filelist : buckets.join(','),
            createCat : ( (document.getElementById('mapSections').checked) ? '1' : '0' ),
            createTree : ( (document.getElementById('createTree').checked) ? '1' : '0' )
        },
        success: function (data) {
            alert("All videos imported from Wistia server to onestudio");
        }
    });
}

//------------------------------------------------------------------------------

function Dacast_fetchData()
{
    $('#dacast_loader').show();
    $.ajax({
        url: ace.path('dacast_buckets'),
        type: "POST",
        data: { key : $('#dacast_api_key').val(), secret : '' },
        success: function (data) {
            $('#dacast_loader').hide();
            $('#dacast_button').hide();

            out = "<table style='border:1px solid #777;text-align:left;margin-top:20px;margin-bottom:20px;margin-left:25%;background:#fff;width:50%;'>\n";
            out += "<tr><td colspan=2 align='center'><b>Select Videos to pull</b></td></tr>\n";
            //out += "<tr><td colspan=2 align='center'><input type='checkbox' value='catBuckets' id='catBuckets'>&nbsp;&nbsp;Create Category for selected buckets<hr></td></tr>\n";
            buckets = data.split('^');
            for (k = 0, i = 0 ; i < buckets.length ; i+=2, k++)
            {
                out += "<tr><td><input id='bucket_"+k+"' style='margin-left:10px;' type='checkbox' value='" + buckets[i] + "'>&nbsp;&nbsp;" + buckets[i+1] + "</td>";
                out += "<td id='" + buckets[i] + "_status' count='0' total='0'></td></tr>\n";
            }
            out += "<tr><td colspan=2 align='center'><input style='margin-bottom:10px;' type='button' class='btn btn-inverse' value='Link Videos' onclick='Dacast_linkVideos()'></td></tr>\n";
            out += "</table>\n";
            document.getElementById('downloadedVideosAppend').innerHTML = out;
        }
    });
}

function Dacast_linkVideos()
{
    buckets = [];
    for ( i = 0 ; i < 9999 ; i++)
    {
        obj = document.getElementById('bucket_'+i);
        if (obj == null) break;
        if (obj.checked) buckets.push(obj.value);
    }
    if (buckets.length == 0) { alert('Atleast one video must be selected'); return; }

    $.ajax({
        url: ace.path('dacast_create_videos'),
        type: "POST",
        data: {         key : $('#dacast_api_key').val(),
            filelist : buckets.join(','),
            createCat : '0'
        },
        success: function (data) {
            alert("All videos imported from dacast server to onestudio");
        }
    });
}

function Aws_fetchBuckets()
{
    $('#aws_loader').show();
    $.ajax({
        url: ace.path('aws_buckets'),
        type: "POST",
        data: { key : $('#api_key').val(), secret : $('#api_secret_key').val() },
        success: function (data) {
            $('#aws_loader').hide();
            $('#aws_button').hide();

            out = "<table style='border:1px solid #777;text-align:left;margin-top:20px;margin-bottom:20px;margin-left:25%;background:#fff;width:50%;'>\n";
            out += "<tr><td colspan=2 align='center'><b>Select buckets to pull videos from</b></td></tr>\n";
            out += "<tr><td colspan=2 align='center'><input type='checkbox' value='catBuckets' id='catBuckets'>&nbsp;&nbsp;Create Category for selected buckets<hr></td></tr>\n";
            buckets = data.split(',');
            for (i = 0 ; i < buckets.length ; i++)
            {
                out += "<tr><td><input id='bucket_"+i+"' style='margin-left:10px;' type='checkbox' value='" + buckets[i] + "'>&nbsp;&nbsp;" + buckets[i] + "</td>";
                out += "<td id='" + buckets[i] + "_status' count='0' total='0'></td></tr>\n";
            }
            out += "<tr><td colspan=2 align='center'><input style='margin-bottom:10px;' type='button' class='btn btn-inverse' value='Link Videos' onclick='Aws_linkVideos()'></td></tr>\n";
            out += "</table>\n";
            document.getElementById('downloadedVideosAppend').innerHTML = out;
        }
    });
}

function Aws_ShowStatus(mode)
{
    buckets = $('#buckets').val().split(',');

    var obj = document.getElementById(buckets[0]+'_status');
    if (obj != null)
    {
        t = parseInt(obj.getAttribute('total'));

        if (mode==0) obj.innerHTML = "Fetching files from AWS server";
        else if (mode==-1) obj.innerHTML = "All " + t + " files linked to videos";
        else if (mode > 0)
        {
            k = parseInt(obj.getAttribute('count')) + mode;
            obj.setAttribute('count', k);
            obj.innerHTML = k + " out of " + t + " links converted to videos";
        }
    }
}

function Aws_createVideoLink()
{
    buckets = $('#buckets').val().split(',');

    bucketFilesStr = $('#bucketFiles').val();
    if (bucketFilesStr.length > 0)
    {
        bucketFiles = bucketFilesStr.split('^');
        fileList = bucketFiles.shift()
        $('#bucketFiles').val(bucketFiles.join('^'));

        $.ajax({
            url: ace.path('aws_create_videos'),
            type: "POST",
            data: {         key : $('#api_key').val(),
                secret : $('#api_secret_key').val(),
                bucketname : buckets[0],
                filelist : fileList,
                createCat : (( $('#catBuckets').is(':checked') ) ? '1' : '0')
            },
            success: function (data) {
                Aws_ShowStatus(50);

                if (bucketFiles.length==0) Aws_ShowStatus(-1);
                else window.setTimeout('Aws_createVideoLink()', 1000);
            }
        });
    }
}

function Aws_fetchFilesFromBuckets()
{
    bucketStr = $('#buckets').val();
    if (bucketStr.length==0) return;

    Aws_ShowStatus(0);
    buckets = bucketStr.split(',');

    $.ajax({
        url: ace.path('aws_files_from_bucket'),
        type: "POST",
        data: {        key : $('#api_key').val(),
            secret : $('#api_secret_key').val(),
            bucketname : buckets[0],
            createCat : (( $('#catBuckets').is(':checked') ) ? '1' : '0')
        },
        success: function (data) {
            $('#bucketFiles').val(data);

            tFiles = data.split('^');
            var obj = document.getElementById(buckets[0]+'_status');
            if (obj != null) obj.setAttribute('total', tFiles.length);

            Aws_createVideoLink();
        }
    });
}

function Aws_linkVideos()
{
    buckets = [];
    for ( i = 0 ; i < 9999 ; i++)
    {
        obj = document.getElementById('bucket_'+i);
        if (obj == null) break;
        if (obj.checked) buckets.push(obj.value);
    }

    if (buckets.length == 0) { alert('Atleast one bucket must be selected'); return; }

    $('#buckets').val( buckets.join(',') );
    $('#bucketFiles').val('');

    Aws_fetchFilesFromBuckets();
}


</script>

@stop
