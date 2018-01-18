<!DOCTYPE html>
<html lang="en">
<head>

  <title>{{$title}}</title>
  <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}"/>

    {{-- CSS Libraries --}}
    {{ HTML::style('bower_components/flat-ui/dist/css/flat-ui.css') }}
    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('css/fullcalendar.min.css') }}
    {{ HTML::style('css/bootstrap-datetimepicker.min.css') }}
    {{ HTML::style('css/bootstrap-responsive.css') }}
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/normalize/3.0.1/normalize.min.css') }}
    {{ HTML::style('css/jQuery_ui_css/jquery-ui.css') }}
    {{ HTML::style('css/font-awesome.min.css') }}
    {{ HTML::style('css/schedule/dhtmlxscheduler.css') }}
    {{ HTML::style('css/schedule/dhtmlxtree.css') }}
    {{ HTML::style('css/schedule/terrace/dhtmlxtree.css') }}
    {{ HTML::style('//vjs.zencdn.net/5.8/video-js.min.css') }}
    {{ HTML::style('assets/css/app.css') }}
    {{ HTML::style('css/lightbox.css') }}

    {{ HTML::style('tako/css/style.css') }}

  <!-- youtube importer -->

  {{ HTML::style('youtube_importer/css/select2.min.css') }}
  {{ HTML::style('youtube_importer/css/style.css') }}

  <!-- end youtube importer -->


  {{-- JS Libraries --}}
  {{ HTML::script('//vjs.zencdn.net/5.8/video.min.js') }}
  {{ HTML::script('js/jquery-2.1.1.min.js') }}
  {{ HTML::script('js/jQuery_UI/jquery-ui.js') }}
  {{ HTML::script('js/bootstrap.min.js') }}
  {{ HTML::script('https://www.google.com/jsapi') }}
  {{ HTML::script('js/jquery_file_upload/vendor/jquery.ui.widget.js') }}
  {{ HTML::script('js/jquery_file_upload/jquery.iframe-transport.js') }}
  {{ HTML::script('js/jquery_file_upload/jquery.fileupload.js') }}
  {{-- HTML::script('js/flat-ui.js') --}}
  {{ HTML::script('js/schedule/dhtmlxscheduler.js') }}
  {{ HTML::script('js/schedule/dhtmlxscheduler_outerdrag.js') }}
  {{ HTML::script('js/schedule/dhtmlxtree.js') }}
  {{ HTML::script('js/panda-uploader.min.js') }}
  {{ HTML::script('js/misc.js') }}
  {{ HTML::script('js/lightbox.js') }}
  {{ HTML::script('js/imagery.js') }}


{{ HTML::style('player/global.css') }}
{{ HTML::script('player/FWDUVPlayer.js') }}
  <!-- fine uploader -->
  {{ HTML::script('s3.fine-uploader.min.js') }}
  {{ HTML::style('css/fine-uploader.css') }}


  {{ HTML::script('youtube_importer/js/script.js') }}
  {{ HTML::script('youtube_importer/js/select2.min.js') }}

  {{-- My CSS files --}}
  {{ HTML::style('css/index.css') }}
  {{ HTML::style('css/collections.css') }}
  {{ HTML::style('css/add_playlist.css') }}
  {{ HTML::style('css/playout.css') }}
  {{ HTML::style('css/upload.css') }}
  {{ HTML::style('css/style.css') }}
  {{ HTML::style('css/styles.css') }}
  {{ HTML::style('css/schedule/time_picker.css') }}

    {{ HTML::style('css/menu/slimmenu.min.css') }}
    {{ HTML::style('css/menu/horizontal_menu.css') }}

  <!-- {{ HTML::style('css/videojs.ads.css') }} -->
  <!-- {{ HTML::style('http://bill-williams.org/test/mediaelementplayer.css') }} -->


  {{-- My JS files --}}
  {{ HTML::script('js/ace.js') }}
  {{ HTML::script('js/index/ace.js') }}
  {{ HTML::script('js/collections/add_collection.js') }}
  {{ HTML::script('js/collections/drag_and_drop.js') }}
  {{ HTML::script('js/collections/collections_index.js') }}
  {{ HTML::script('js/collections/script.js') }}
  {{ HTML::script('js/index/script.js') }}
  {{ HTML::script('js/playlist/script.js') }}
  {{ HTML::script('js/tvapp/script.js') }}
  {{ HTML::script('js/tvweb/script.js') }}
  {{ HTML::script('js/videos/drag_and_drop.js') }}
  {{ HTML::script('js/videos/get_video_desc.js') }}
  {{ HTML::script('js/videos/script.js') }}
  {{ HTML::script('js/settings/script.js') }}
  {{ HTML::script('js/schedule/time_picker.js') }}

  <!-- Video Preroll -->
  <!-- {{ HTML::script('http://bill-williams.org/js/mediaelement-and-player.min.js') }} -->


  @if(isset($playout))
  {{ HTML::script('http://cdnjs.cloudflare.com/ajax/libs/d3/3.4.11/d3.min.js') }}
  {{ HTML::script('http://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.3/moment.min.js') }}
  {{ HTML::script('http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.7.0/underscore-min.js') }}
  <!--    {{ HTML::script('packages/timeline/timeline.js') }}-->
  {{ HTML::script('js/d3-timeline/d3-timeline.js') }}
  {{ HTML::style('css/d3-timeline/d3-timeline.css') }}
  {{ HTML::script('js/d3-timeline/utils.js') }}
  {{ HTML::script('js/playout/script.js') }}
  <!--    {{ HTML::style('packages/timeline/timeline.css') }}-->
  @endif

  {{ HTML::script('js/upload/script.js') }}

  <script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/rsmt8vb4';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>

  <script>
    ace = ace || {};
    ace.user_id = 0;
    ace.timestamp = {{ $timestamp }};

    @if(isset($channel))
    ace.channel_id = '{{$channel['id']}}';
    ace.logo_ext = '{{$channel['logo_ext']}}';
    @endif

    @if(!empty($master_loop_playlist))
    ace.master_loop_playlist = {{ json_encode($master_loop_playlist) }}

    $(document).ready(function () {
      $('#playlist-header-wrap #playlist-name-header').html(ace.master_loop_playlist.title);

      var currentTime = ace.timestamp;
      var playlistStartTime = ace.master_loop_playlist.master_looped;
      var duration = ace.master_loop_playlist.duration;
      var colon = true;

      function addZero(i) {
        if (i < 10) {
          i = "0" + i;
      }
      return i;
  }

  setInterval(function () {

    if (IsLiveStreamStarted())
    {
      $('#playlist-header-wrap #playlist-name-header').html(ace.master_loop_playlist.title);
      $('#playlist-header-wrap .progress-bar-success').width((100 * ((currentTime - playlistStartTime) % duration)) / duration + '%');

      var l = 0;
      $.each(ace.master_loop_playlist.videos, function (index, video) {
        l += parseInt(video.duration);
        if (((currentTime - ace.master_loop_playlist.master_looped) % duration) < l) {
          $('#video-header-wrap #video-name-header').html(video.title);
          $('#video-header-wrap .progress-bar-success').width(((video.duration - (l - ((currentTime - playlistStartTime) % duration))) * 100) / video.duration + '%');

          return false;
      }
  });
  }
  else
  {
      $('#playlist-header-wrap #playlist-name-header').html("");
      $('#video-header-wrap #video-name-header').html("");
      $('#playlist-header-wrap .progress-bar-success').width('0%');
      $('#video-header-wrap .progress-bar-success').width('0%');
  }

  var startTime = currentTime * 1000;

  $('.hours').html(addZero(new Date(startTime).getHours()));
  $('.minutes').html(addZero(new Date(startTime).getMinutes()));
  $('.seconds').html(addZero(new Date(startTime).getSeconds()));

//                    currentTime = (parseFloat(currentTime + 0.1)).toFixed(1);
if (IsLiveStreamStarted())
{
  ace.timestamp++;
  currentTime++;
}
}, 1000);

  setInterval(function() {
    if(colon) {
      $('.colon').css('visibility', 'hidden');
      colon = false;
  } else {
      $('.colon').css('visibility', 'visible');
      colon = true;
  }
}, 500);
});
    @else

    $(document).ready(function () {
      var currentTime = ace.timestamp;
      var colon = true;
        $('.darkInfo').hover( function() {
            $(this).next().fadeIn(300);
        }, function() {
            $(this).next().fadeOut(100);
        });

      function addZero(i) {
        if (i < 10) {
          i = "0" + i;
      }
      return i;
  }

  setInterval(function() {
    var startTime = currentTime * 1000;

    $('.hours').html(addZero(new Date(startTime).getHours()));
    $('.minutes').html(addZero(new Date(startTime).getMinutes()));
    $('.seconds').html(addZero(new Date(startTime).getSeconds()));

//                    currentTime = (parseFloat(currentTime + 0.1)).toFixed(1);
ace.timestamp++;
currentTime++;
}, 1000);

  setInterval(function() {
    if(colon) {
      $('.colon').css('visibility', 'hidden');
      colon = false;
  } else {
      $('.colon').css('visibility', 'visible');
      colon = true;
  }
}, 500);
});

    @endif

</script>

    <script>
        function noLogoError() {
            $('#logo img').attr('src', '{{asset('images/noLogo1.png')}}');
            $('#uploadLogoFirst').show();
        }
    </script>

<style>
    body {
      padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
  }

  .progress {
      background-color: #d9dbdd;
  }
  .td-filename {
      width: 400px;
  }
  .td-filesize {
      width: 40px;
  }
  .td-progress {
      width: 300px;
  }

  .td-action .btn {
      width: 75px;
      padding-left: 0px;
      padding-right: 0px;
  }
  #player1.video-js {
      width: 100% !important;
      /* height: 100% ; */
  }

  .mejs-ads a {   display: block;     position: absolute; right: 0;   top: 0; width: 100%;    height: 100%;   display: block; }.mejs-ads .mejs-ads-skip-block {   display: block;     position: absolute; right: 0;   top: 0; padding: 10px;  background: #000;   background: rgba(0,0,0,0.5);    color: #fff; }.mejs-ads .mejs-ads-skip-button { cursor: pointer; }.mejs-ads .mejs-ads-skip-button:hover {   text-decoration: underline; }

</style>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">


{{ HTML::script('js/stream/mediaelement-and-player.min.js') }}
{{ HTML::script('js/stream/mep-feature-ads.js') }}
{{ HTML::style('js/stream/mediaelementplayer.min.css') }}
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
<link rel="stylesheet" media="print" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.print.css"/>


<script src="https://sdk.amazonaws.com/js/aws-sdk-2.3.17.min.js"></script>

<script src="https://content.jwplatform.com/libraries/DbXZPMBQ.js"></script>

</head>
<body id="plugins_snapshot">
    <div id="wrapper" class="container showLoader" style="display: none;">
        <header>
            <div class="row center-block">
                  <div class="header_wrapper">
                      <div class="col-md-10 col-sm-10 col-xs-12" id = "navBlock">
                          <div class="top_menu">
                              <nav>
                                  <div class="row center-block">
                                      <div id = "reports_nav">
                                          <!-- <div class=""> -->
                                          <a class="logoLink btn menu_item" href="../channel_{{ $channel['id'] }}">
                                              <div class='loginText' id = "stud_logo">
                                                  <img src="/images/imgpsh_fullsize.png" alt="">
                                              </div>
                                          </a>
                                          <a class="btn menu_item " href="javascript:void(0)">Concurrent<br> Audience
                                              @if($channel['streamlyzer_token'] != '')
                                                  <span class="streamlyzer_count">{{ $audience }}</span>
                                                  <img class="darkInfo" src="/images/info_dark_grey.png">
                                                  <span class="popupReport">The number of audience that actually watched at least one or more content for last 15 mins</span>
                                              @else
                                                  <img class="darkInfo" src="/images/info_dark_grey.png">
                                                  <span class="popupReport">Your Data is Coming Soon</span>
                                              @endif
                                          </a>
                                          <a class="btn menu_item " href="javascript:void(0)">Concurrent <br>Video View
                                              @if($channel['streamlyzer_token'] != '')
                                                  <span class="streamlyzer_count">{{ $videoView }}</span>
                                                  <img class="darkInfo" src="/images/info_dark_grey.png">
                                                  <span class="popupReport">The number of view watch content for last 15 mins</span>
                                              @else
                                                  <img class="darkInfo" src="/images/info_dark_grey.png">
                                                  <span class="popupReport">Your Data is Coming Soon</span>
                                              @endif
                                          </a>
                                          <a class="btn menu_item " href="javascript:void(0)">Today <br>Bounce Rate
                                              @if($channel['streamlyzer_token'] != '')
                                                  <span class="streamlyzer_count">{{ $bounceRate }} %</span>
                                                  <img class="darkInfo" src="/images/info_dark_grey.png">
                                                  <span class="popupReport">The percentage of users who clicked play button and exited within 5 seconds</span>
                                              @else
                                                  <img class="darkInfo" src="/images/info_dark_grey.png">
                                                  <span class="popupReport">Your Data is Coming Soon</span>
                                              @endif
                                          </a>
                                          <a class="btn menu_item " href="javascript:void(0)">Today <br>View Hour
                                              @if($channel['streamlyzer_token'] != '')
                                                  <span class="streamlyzer_count">{{ $viewHour }} ms</span>
                                                  <img class="darkInfo" src="/images/info_dark_grey.png">
                                                  <span class="popupReport">The amount of view time in today</span>
                                              @else
                                                  <img class="darkInfo" src="/images/info_dark_grey.png">
                                                  <span class="popupReport">Your Data is Coming Soon</span>
                                              @endif
                                          </a>
                                          <a class="btn menu_item " href="javascript:void(0)">Today <br>Completion Rate
                                              @if($channel['streamlyzer_token'] != '')
                                                  <span class="streamlyzer_count">{{ $complRate }} %</span>
                                                  <img class="darkInfo" src="/images/info_dark_grey.png">
                                                  <span class="popupReport">The rate of audiences who watched whole content from start to end (with or without skipping)</span>
                                              @else
                                                  <img class="darkInfo" src="/images/info_dark_grey.png">
                                                  <span class="popupReport">Your Data is Coming Soon</span>
                                              @endif
                                          </a>
                                          <a class="btn menu_item " href="javascript:void(0)">Revenue
                                              <div style = "height:20px"></div>
                                              @if($channel['streamlyzer_token'] != '')
                                                  <span class="streamlyzer_count">{{ $complRate }} %</span>
                                                  <img class="darkInfo" src="/images/info_dark_grey.png">
                                                  <span class="popupReport">The rate of audiences who watched whole content from start to end (with or without skipping)</span>
                                              @else
                                                  <img class="darkInfo" src="/images/info_dark_grey.png">
                                                  <span class="popupReport">Your Data is Coming Soon</span>
                                              @endif
                                          </a>
                                          <!-- </div> -->
                                      </div>
                                  </div>
                              </nav>
                          </div>
                          {{-- New header --}}
                          <div class="row horizontal_menu" id = "horizontal_menu">
                              <div class="col-md-12">
                                  <ul id="navigation" class="slimmenu">
                                      <li class="main-menu option-one">
                                          <a href="javascript:void(0)" class="menu-list">Dveo App Suite</a>
                                          <ul>
                                              <li>
                                                  <a href="https://docs.1stud.io/v1.0/docs/about" target="_blank" class="sub-list">
                                                      About
                                                  </a>
                                              </li>
                                              <li>
                                                  <a href="../channel_{{ $channel['id'] }}/settings" class="sub-list">
                                                      Channel Settings
                                                  </a>
                                                  <ul>
                                                      <li>
                                                          <a href="../channel_{{ $channel['id'] }}/settings/#tab1">
                                                              General
                                                          </a>
                                                      </li>
                                                      <li>
                                                          <a href="../channel_{{ $channel['id'] }}/settings/#tab2">
                                                              Logo and Images
                                                          </a>
                                                      </li>
                                                      <li>
                                                          <a href="../channel_{{ $channel['id'] }}/settings/#tab5">
                                                              Analytics
                                                          </a>
                                                      </li>
                                                      <li>
                                                          <a href="../channel_{{ $channel['id'] }}/settings/#tab7">
                                                              Launch Pad
                                                          </a>
                                                      </li>
                                                      <li>
                                                          <a href="../channel_{{ $channel['id'] }}/settings/">
                                                              Mobile-Web TV
                                                          </a>
                                                      </li>
                                                      <li>
                                                          <a href="../channel_{{ $channel['id'] }}/settings/#tab8">
                                                              Subscription
                                                          </a>
                                                      </li>
                                                      <li>
                                                          <a href="../channel_{{ $channel['id'] }}/settings/#tab9">
                                                              Advertising
                                                          </a>
                                                      </li>
                                                      <li>
                                                          <a href="../channel_{{ $channel['id'] }}/settings/#tab4">
                                                              Distribution
                                                          </a>
                                                      </li>
                                                      <li>
                                                          <a href="../channel_{{ $channel['id'] }}/settings/#tab12">
                                                              Tag Manager
                                                          </a>
                                                      </li>
                                                      <li>
                                                          <a href="../channel_{{ $channel['id'] }}/settings/#tab13">
                                                              Show Manager
                                                          </a>
                                                      </li>
                                                      <li>
                                                          <a href="http://1stud.io/stepstolaunch-me" target = "_blank">
                                                              New Channel
                                                          </a>
                                                      </li>
                                                  </ul>
                                              </li>
                                              <li>
                                                  <a href="{{ URL::route('logout') }}" class="sub-list">
                                                      Logout
                                                  </a>
                                              </li>
                                          </ul>
                                      </li>
                                      <li class="main-menu">
                                          <a href="javascript:void(0)" class="menu-list">File</a>
                                          <ul>
                                              <li>
                                                  <a href="../channel_{{$channel['id']}}/upload" class="sub-list">Upload</a>
                                              </li>
                                              <li>
                                                  <a href="../channel_{{$channel['id']}}/search" class="sub-list">Search</a>
                                              </li>
                                          </ul>
                                      </li>
                                      <li class="main-menu">
                                          <a href="javascript:void(0)" class="menu-list">Edit </a>
                                          <ul>
                                              <li>
                                                  <a href="../channel_{{ $channel['id'] }}/videos" class="sub-list">Video Library</a>
                                              </li>
                                              <li>
                                                  <a href="javascript:void(0)" class="sub-list">Folders</a>
                                                  <ul>
                                                      <li>
                                                          <a href="../channel_{{ $channel['id'] }}/collections">
                                                              Manager
                                                          </a>
                                                      </li>
                                                      <li>
                                                          <a href="../channel_{{ $channel['id'] }}/collections">
                                                              New Folder
                                                          </a>
                                                      </li>
                                                  </ul>
                                              </li>
                                              @if( Auth::user()->playout_access !== '1' && $channel['playout_access'] !== '1')
                                              <li>
                                                  <a href="javascript:void(0)" class="sub-list">Channel</a>
                                                  <ul>
                                                      <li>
                                                          <a href="../channel_{{ $channel['id'] }}/tvapp_playlists">
                                                              <i class="fa fa-fw fa-file-image-o"></i> Manager
                                                          </a>
                                                      </li>
                                                      <li>
                                                          <a href="../channel_{{ $channel['id'] }}/tvapp_playlists">
                                                              <i class="fa fa-fw fa-cloud-upload"></i> New Top Shelf
                                                          </a>
                                                      </li>
                                                      <li>
                                                          <a href="../channel_{{ $channel['id'] }}/tvapp_playlists">
                                                              <i class="fa fa-fw fa-dropbox"></i> New Low Shelf
                                                          </a>
                                                      </li>
                                                      <li>
                                                          <a href="../channel_{{ $channel['id'] }}/tvapp_playlists">
                                                              <i class="fa fa-file-image-o"></i> Refresh Feed
                                                          </a>
                                                      </li>
                                                      <li>
                                                          <a href="../channel_{{ $channel['id'] }}/tvapp_playlists">
                                                              <i class="fa fa-filter"></i> Preview
                                                          </a>
                                                      </li>
                                                  </ul>
                                              </li>
                                              @endif
                                          </ul>
                                      </li>
                                      @if( Auth::user()->playout_access !== '1' && $channel['playout_access'] !== '1')
                                      <li class="main-menu">
                                          <a href="javascript:void(0)" class="menu-list">View </a>
                                          <ul>
                                              <li>
                                                  <a href="javascript:void(0)" class="sub-list">
                                                      Reports
                                                  </a>
                                                  <ul>
                                                      <li>
                                                          <a href="https://dashboard.streamlyzer.com" target = "_blank">
                                                              <i class="fa fa-fw fa-dropbox"></i> Streamlyzer
                                                          </a>
                                                      </li>
                                                      <li>
                                                          <a href="javascript:void(0)">
                                                              <i class="fa fa-fw fa-dropbox"></i> Revenue
                                                          </a>
                                                      </li>
                                                  </ul>
                                              </li>
                                              <li>
                                                  <a target = "_blank" href="{{ (isset($channel['roku_tv_url']) && !empty($channel['roku_tv_url'])) ? $channel['roku_tv_url'] : 'https://channelstore.roku.com/browse' }}" class="sub-list">
                                                      Roku
                                                  </a>
                                              </li>
                                              <li>
                                                  <a target = "_blank" href="{{ (isset($channel['amazon_fire_url']) && !empty($channel['amazon_fire_url']))? $channel['amazon_fire_url'] : 'https://www.amazon.com/Fire-TV-Apps-All-Models/b?ie=UTF8&node=10208590011' }}" class="sub-list">
                                                      Fire TV
                                                  </a>
                                              </li>
                                              <li>
                                                  <a target = "_blank" href="{{ (isset($channel['apple_tv_url']) && !empty($channel['apple_tv_url']))? $channel['apple_tv_url'] : 'https://www.apple.com/tv/' }}" class="sub-list">
                                                      Apple TV
                                                  </a>
                                              </li>
                                              <li>
                                                  <a target = "_blank" href="{{ (isset($channel['mobileWebUrl']) && !empty($channel['mobileWebUrl']))? $channel['mobileWebUrl'] : 'http://onestudio.tv/' }}" class="sub-list">
                                                      Mobile
                                                  </a>
                                              </li>
                                          </ul>
                                      </li>
                                      @endif
                                      <li class="main-menu">
                                          <a href="javascript:void(0)" class="menu-list">Tools </a>
                                          <ul>
                                              <li>
                                                  <a href="javascript:void(0)" class="sub-list">
                                                      Live Playout
                                                  </a>
                                                  <ul>
                                                      <li>
                                                          <a href="../channel_{{ $channel['id'] }}/playlists">
                                                              <i class="fa fa-fw fa-dropbox"></i> Manager
                                                          </a>
                                                      </li>
                                                      <li>
                                                          <a href="../channel_{{ $channel['id'] }}/playlists">
                                                              <i class="fa fa-fw fa-dropbox"></i> New Playlist
                                                          </a>
                                                      </li>
                                                      <li>
                                                          <a href="../channel_{{ $channel['id'] }}/live_monitor">
                                                              <i class="fa fa-fw fa-dropbox"></i> Live Montior
                                                          </a>
                                                      </li>
                                                      <li>
                                                          <a href="../channel_{{ $channel['id'] }}/schedule">
                                                              <i class="fa fa-fw fa-dropbox"></i> Schedule
                                                          </a>
                                                      </li>
                                                  </ul>
                                              </li>
                                              @if( Auth::user()->playout_access !== '1' && $channel['playout_access'] !== '1')
                                              <li>
                                                  <a href="javascript:void(0)" class="sub-list">
                                                      Image
                                                  </a>
                                                  <ul>
                                                      <li>
                                                          <a href="../channel_{{ $channel['id'] }}/image_manager">
                                                              <i class="fa fa-fw fa-dropbox"></i> Manager
                                                          </a>
                                                      </li>
                                                      <li>
                                                          <a href="../channel_{{ $channel['id'] }}/image_manager">
                                                              <i class="fa fa-fw fa-dropbox"></i> Library
                                                          </a>
                                                      </li>
                                                      <li>
                                                          <a href="../channel_{{ $channel['id'] }}/image_manager">
                                                              <i class="fa fa-fw fa-dropbox"></i> New Folder
                                                          </a>
                                                      </li>
                                                  </ul>
                                              </li>
                                              @endif
                                              <li>
                                                  <a href="http://email.1stud.io/" target="_blank" class="sub-list">
                                                      Email Manager
                                                  </a>
                                              </li>
                                              @if( Auth::user()->playout_access !== '1' && $channel['playout_access'] !== '1')
                                              <li>
                                                  <a href="" class="sub-list">
                                                      Tools in Beta
                                                  </a>
                                                  <ul>
                                                      <li>
                                                          <a href="../channel_{{ $channel['id'] }}/settings/#tab10">
                                                              <i class="fa fa-fw fa-dropbox"></i> Youtube Channel Downloader
                                                          </a>
                                                      </li>
                                                      <li>
                                                          <a href="../channel_{{ $channel['id'] }}/vod_playlist">
                                                              <i class="fa fa-fw fa-dropbox"></i> VOD Playlist
                                                          </a>
                                                      </li>
                                                      <li>
                                                          <a href="../channel_{{ $channel['id'] }}/audio_manager">
                                                              <i class="fa fa-fw fa-dropbox"></i> Audio Manager
                                                          </a>
                                                      </li>
                                                      <li>
                                                          <a href="/arloopa" target="_blank">
                                                              <i class="fa fa-fw fa-dropbox"></i> Arloopa
                                                          </a>
                                                      </li>
                                                  </ul>
                                              </li>
                                              @endif
                                          </ul>
                                      </li>

                                      <li><a href="javascript:void(0)" class="menu-list">Help </a>
                                          <ul>
                                              <li>
                                                  <a href="https://docs.1stud.io/" target="_blank" class="sub-list">
                                                      Help Center
                                                  </a>
                                              </li>
                                              <li>
                                                  <a href="https://3.basecamp.com/sign_in" target="_blank" class="sub-list">
                                                      Basecamp
                                                  </a>
                                              </li>
                                          </ul>
                                      </li>
                                  </ul>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-2 col-sm-2 col-xs-12" id = "logoBlock">
                            <div class="company_logo">
                                <a href="/" id="logo">
                                    @if($channel['id'] == 86)
                                        <img onerror="$('#logo img').attr('src', '{{asset('images/noLogo.png')}}')" src="/images/imgpsh_fullsize.jpg" alt="1studio" class="logo1 center-block">
                                    @else

                                        @if(isset($channel['logo_ext']) && !empty($channel['logo_ext']))
                                            <img onerror="noLogoError()" src="https://aceplayout.s3.amazonaws.com/logos/channel_{{ $channel['id'] }}.{{ $channel['logo_ext'] }}?{{ md5($channel['updated_at']) }}" alt="1studio" class="logo1 center-block">
                                        @else
                                            <img onerror="noLogoError()" src="https://aceplayout.s3.amazonaws.com/logos/channel_{{ $channel['id'] }}?{{ md5($channel['updated_at']) }}" alt="1studio" class="logo1 center-block">
                                        @endif
                                    @endif
                                    <div id = "uploadLogoFirst">
                                        <h1 class="text-danger nologoAlert">No TV App Logo</h1>
                                        <form method="POST" action="https://aceplayout.s3.amazonaws.com/" accept-charset="UTF-8" class="form-horizontal amazon_form_logo" enctype="multipart/form-data">
                                            <input name="_token" type="hidden" value="{{ csrf_token() }}">
                                            <input id="fileupload" data-url="server/php/" name="file" type="file">
                                            <input id="key" name="key" type="hidden" value="uploads">
                                            <input name="acl" type="hidden" value="public-read">
                                            <input name="AWSAccessKeyId" type="hidden" value="AKIAJWU4DYR6OMHE2YPQ">
                                            <input id="policy" name="Policy" type="hidden" value="policy">
                                            <input id="signature" name="Signature" type="hidden" value="signature">
                                        </form>
                                        <h1 class = "text-danger nologoAlert">Upload</h1>
                                    </div>
                                </a>
                            </div>

                      </div>

                </div>

            </div>
        </header>
  <div class="div_for_black_parth">
  @if(Request::segment(2) =='collections')
    <div class="div_for_center_parth_in_black">
	   <h1 class="on-air-sign on-air" onclick="toggle(this)">Folders</h1>
    </div>
  @endif
   @if(Request::segment(2) =='settings')
    <div class="div_for_center_parth_in_black">
	   <h1 class="on-air-sign on-air" onclick="toggle(this)">Settings</h1>
    </div>
  @endif
  @if(Request::segment(2) =='videos')
    <div class="div_for_center_parth_in_black_video_library">
		<h1 class="on-air-sign on-air" onclick="toggle(this)">Video Library</h1>
    </div>
  @endif
  @if(Request::segment(2) =='vod_playlist')
    <div class="div_for_center_parth_in_black_vod_playlist">
      <h1 class="on-air-sign on-air" onclick="toggle(this)">VOD Playlist</h1>
    </div>
  @endif
  @if(Request::segment(2) =='image_manager')
    <div class="div_for_center_parth_in_black_image_manager">
      <h1 class="on-air-sign on-air" onclick="toggle(this)">Image Manager</h1>
    </div>
  @endif
   @if(Request::segment(2) =='audio_manager')
    <div class="div_for_center_parth_in_black_audio_manager">
      <h1 class="on-air-sign on-air" onclick="toggle(this)">Audio Manager</h1>
    </div>
  @endif
  @if(Request::segment(2) =='home')
      <div class="div_for_on_air_part homeNavbar">
          <h1 class="on-air-sign on-air" onclick="toggle(this)">
              <a href="../channel_{{ $channel['id'] }}/videos">
                  Video Library
              </a>
          </h1>
          <h1 class="on-air-sign on-air" onclick="toggle(this)">
              <a href="../channel_{{ $channel['id'] }}/collections">
                  Folders
              </a>
          </h1>
          <h1 class="on-air-sign on-air" onclick="toggle(this)">
              <a href="../channel_{{ $channel['id'] }}/playlists">
                  Live Playout
              </a>
          </h1>
          <h1 class="on-air-sign on-air" onclick="toggle(this)">
              <a href="../channel_{{ $channel['id'] }}/tvapp_playlists">
                  Channel Manager
              </a>
          </h1>
      </div>
  @endif

  {{--@if(Request::segment(2) =='upload')--}}
      {{--<div class="div_for_center_parth_in_black_audio_manager" style = "text-align: center;">--}}
          {{--<h1 class="on-air-sign on-air" onclick="toggle(this)">Upload Video</h1>--}}
      {{--</div>--}}
  {{--@endif--}}
  @if(Request::segment(2) !=='vod_playlist' && Request::segment(2) !=='home' && Request::segment(2) !=='upload' && Request::segment(2) !=='videos' && Request::segment(2) !== 'collections'  && Request::segment(2) !== 'tvapp_playlists' && Request::segment(2) !== 'tvapp_playlists_preview' && Request::segment(2) !=='image_manager' && Request::segment(2) !== 'audio_manager' && Request::segment(2) !=='settings' )
    <div class="div_for_on_air_part schedule-item">
          <h1 class="on-air-sign" onclick="toggle(this)">Live Playout</h1>
          <h1 class="on-air-sign" onclick="toggle(this)">
              <a href="../channel_{{ $channel['id'] }}/schedule">
              Schedule
              </a>
          </h1>
    </div>
  @endif


  @if(Request::segment(2) =='tvapp_playlists')
    <div class="div_for_tvapp_preview_page_on_air">
      <div class='div_for_left_icon_live_part'>
         <div id="box">

            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="18" viewBox="0 0 24 18">
            <g fill="#d60300" fill-rule="evenodd">
              <ellipse cx="12" cy="8.705" rx="3" ry="3"/>
              <path id="on-air-out" d="M3.51471863.219669914C-1.17157288 4.90596141-1.17157288 12.5039412 3.51471863 17.1902327 3.80761184 17.4831259 4.28248558 17.4831259 4.5753788 17.1902327 4.86827202 16.8973394 4.86827202 16.4224657 4.5753788 16.1295725.474873734 12.0290674.474873734 5.38083515 4.5753788 1.28033009 4.86827202.987436867 4.86827202.512563133 4.5753788.219669914 4.28248558-.0732233047 3.80761184-.0732233047 3.51471863.219669914zM20.4852814 17.1902327C25.1715729 12.5039412 25.1715729 4.90596141 20.4852814.219669914 20.1923882-.0732233047 19.7175144-.0732233047 19.4246212.219669914 19.131728.512563133 19.131728.987436867 19.4246212 1.28033009 23.5251263 5.38083515 23.5251263 12.0290674 19.4246212 16.1295725 19.131728 16.4224657 19.131728 16.8973394 19.4246212 17.1902327 19.7175144 17.4831259 20.1923882 17.4831259 20.4852814 17.1902327z"/>
              <path id="on-air-in" d="M17.3033009 14.0082521C18.7217837 12.5897693 19.4928584 10.6983839 19.4999509 8.73215792 19.507111 6.74721082 18.7352286 4.8335782 17.3033009 3.40165043 17.0104076 3.10875721 16.5355339 3.10875721 16.2426407 3.40165043 15.9497475 3.69454365 15.9497475 4.16941738 16.2426407 4.4623106 17.3890249 5.6086948 18.0056933 7.13752465 17.9999607 8.72674718 17.9942823 10.30094 17.3782748 11.8119579 16.2426407 12.947592 15.9497475 13.2404852 15.9497475 13.7153589 16.2426407 14.0082521 16.5355339 14.3011454 17.0104076 14.3011454 17.3033009 14.0082521zM6.69669914 3.40165043C3.76776695 6.33058262 3.76776695 11.07932 6.69669914 14.0082521 6.98959236 14.3011454 7.46446609 14.3011454 7.75735931 14.0082521 8.05025253 13.7153589 8.05025253 13.2404852 7.75735931 12.947592 5.41421356 10.6044462 5.41421356 6.80545635 7.75735931 4.4623106 8.05025253 4.16941738 8.05025253 3.69454365 7.75735931 3.40165043 7.46446609 3.10875721 6.98959236 3.10875721 6.69669914 3.40165043z"/>
            </g>
          </svg>

        </div>
      </div>
      <div class="div_for_on_air_parth_tvapp_preview_page">
          <div class="div_for_on_air_part_preview_tvapp">
             <h1 class="on-air-sign on-air" onclick="toggle(this)">Channel  Manager</h1>
          </div>
      </div>
      <div class="div_for_right_icon_live_part">
           <div id="box">

            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="18" viewBox="0 0 24 18">
            <g fill="#d60300" fill-rule="evenodd">
              <ellipse cx="12" cy="8.705" rx="3" ry="3"/>
              <path id="on-air-out" d="M3.51471863.219669914C-1.17157288 4.90596141-1.17157288 12.5039412 3.51471863 17.1902327 3.80761184 17.4831259 4.28248558 17.4831259 4.5753788 17.1902327 4.86827202 16.8973394 4.86827202 16.4224657 4.5753788 16.1295725.474873734 12.0290674.474873734 5.38083515 4.5753788 1.28033009 4.86827202.987436867 4.86827202.512563133 4.5753788.219669914 4.28248558-.0732233047 3.80761184-.0732233047 3.51471863.219669914zM20.4852814 17.1902327C25.1715729 12.5039412 25.1715729 4.90596141 20.4852814.219669914 20.1923882-.0732233047 19.7175144-.0732233047 19.4246212.219669914 19.131728.512563133 19.131728.987436867 19.4246212 1.28033009 23.5251263 5.38083515 23.5251263 12.0290674 19.4246212 16.1295725 19.131728 16.4224657 19.131728 16.8973394 19.4246212 17.1902327 19.7175144 17.4831259 20.1923882 17.4831259 20.4852814 17.1902327z"/>
              <path id="on-air-in" d="M17.3033009 14.0082521C18.7217837 12.5897693 19.4928584 10.6983839 19.4999509 8.73215792 19.507111 6.74721082 18.7352286 4.8335782 17.3033009 3.40165043 17.0104076 3.10875721 16.5355339 3.10875721 16.2426407 3.40165043 15.9497475 3.69454365 15.9497475 4.16941738 16.2426407 4.4623106 17.3890249 5.6086948 18.0056933 7.13752465 17.9999607 8.72674718 17.9942823 10.30094 17.3782748 11.8119579 16.2426407 12.947592 15.9497475 13.2404852 15.9497475 13.7153589 16.2426407 14.0082521 16.5355339 14.3011454 17.0104076 14.3011454 17.3033009 14.0082521zM6.69669914 3.40165043C3.76776695 6.33058262 3.76776695 11.07932 6.69669914 14.0082521 6.98959236 14.3011454 7.46446609 14.3011454 7.75735931 14.0082521 8.05025253 13.7153589 8.05025253 13.2404852 7.75735931 12.947592 5.41421356 10.6044462 5.41421356 6.80545635 7.75735931 4.4623106 8.05025253 4.16941738 8.05025253 3.69454365 7.75735931 3.40165043 7.46446609 3.10875721 6.98959236 3.10875721 6.69669914 3.40165043z"/>
            </g>
          </svg>

        </div>
      </div>
    </div>
  @endif

  @if(Request::segment(2) == 'tvapp_playlists_preview')
    <div class="div_for_left_parth_black_border">
      <div class='div_for_left_part_in_left_parth'></div>
      <div class="div_for_logo_left_part">
		@if(isset($channel['logo_ext']) && !empty($channel['logo_ext']))
			<img onerror="$('#logo img').attr('src', '{{asset('images/noLogo.png')}}')" src="http://aceplayout.s3.amazonaws.com/logos/channel_{{ $channel['id'] }}.{{ $channel['logo_ext'] }}?{{ md5($channel['updated_at']) }}" alt="1studio" class="logo1 center-block image_left_part__preview_page">
		@else
			<img onerror="$('#logo img').attr('src', '{{asset('images/noLogo.png')}}')" src="http://aceplayout.s3.amazonaws.com/logos/channel_{{ $channel['id'] }}?{{ md5($channel['updated_at']) }}" alt="1studio" class="logo1 center-block image_left_part__preview_page">
		@endif
	  
	</div>
    </div>
    <div class="div_for_tvapp_preview_page_on_air_preview">
      <div class="div_for_center_parth_three">
          
        <div class='div_for_left_icon_live_part_preview_part'>
           <div id="box">

              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="18" viewBox="0 0 24 18">
              <g fill="#d60300" fill-rule="evenodd">
                <ellipse cx="12" cy="8.705" rx="3" ry="3"/>
                <path id="on-air-out" d="M3.51471863.219669914C-1.17157288 4.90596141-1.17157288 12.5039412 3.51471863 17.1902327 3.80761184 17.4831259 4.28248558 17.4831259 4.5753788 17.1902327 4.86827202 16.8973394 4.86827202 16.4224657 4.5753788 16.1295725.474873734 12.0290674.474873734 5.38083515 4.5753788 1.28033009 4.86827202.987436867 4.86827202.512563133 4.5753788.219669914 4.28248558-.0732233047 3.80761184-.0732233047 3.51471863.219669914zM20.4852814 17.1902327C25.1715729 12.5039412 25.1715729 4.90596141 20.4852814.219669914 20.1923882-.0732233047 19.7175144-.0732233047 19.4246212.219669914 19.131728.512563133 19.131728.987436867 19.4246212 1.28033009 23.5251263 5.38083515 23.5251263 12.0290674 19.4246212 16.1295725 19.131728 16.4224657 19.131728 16.8973394 19.4246212 17.1902327 19.7175144 17.4831259 20.1923882 17.4831259 20.4852814 17.1902327z"/>
                <path id="on-air-in" d="M17.3033009 14.0082521C18.7217837 12.5897693 19.4928584 10.6983839 19.4999509 8.73215792 19.507111 6.74721082 18.7352286 4.8335782 17.3033009 3.40165043 17.0104076 3.10875721 16.5355339 3.10875721 16.2426407 3.40165043 15.9497475 3.69454365 15.9497475 4.16941738 16.2426407 4.4623106 17.3890249 5.6086948 18.0056933 7.13752465 17.9999607 8.72674718 17.9942823 10.30094 17.3782748 11.8119579 16.2426407 12.947592 15.9497475 13.2404852 15.9497475 13.7153589 16.2426407 14.0082521 16.5355339 14.3011454 17.0104076 14.3011454 17.3033009 14.0082521zM6.69669914 3.40165043C3.76776695 6.33058262 3.76776695 11.07932 6.69669914 14.0082521 6.98959236 14.3011454 7.46446609 14.3011454 7.75735931 14.0082521 8.05025253 13.7153589 8.05025253 13.2404852 7.75735931 12.947592 5.41421356 10.6044462 5.41421356 6.80545635 7.75735931 4.4623106 8.05025253 4.16941738 8.05025253 3.69454365 7.75735931 3.40165043 7.46446609 3.10875721 6.98959236 3.10875721 6.69669914 3.40165043z"/>
              </g>
            </svg>

          </div>
        </div>
        <div class="div_for_on_air_parth_tvapp_preview_page_center_part">
            <div class="div_for_on_air_part_preview_tvapp_center_part">
               <h1 class="on-air-sign on-air" onclick="toggle(this)">Live Preview</h1>
            </div>
        </div>
        <div class="div_for_right_icon_live_part_preview_part">
             <div id="box">

              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="18" viewBox="0 0 24 18">
              <g fill="#d60300" fill-rule="evenodd">
                <ellipse cx="12" cy="8.705" rx="3" ry="3"/>
                <path id="on-air-out" d="M3.51471863.219669914C-1.17157288 4.90596141-1.17157288 12.5039412 3.51471863 17.1902327 3.80761184 17.4831259 4.28248558 17.4831259 4.5753788 17.1902327 4.86827202 16.8973394 4.86827202 16.4224657 4.5753788 16.1295725.474873734 12.0290674.474873734 5.38083515 4.5753788 1.28033009 4.86827202.987436867 4.86827202.512563133 4.5753788.219669914 4.28248558-.0732233047 3.80761184-.0732233047 3.51471863.219669914zM20.4852814 17.1902327C25.1715729 12.5039412 25.1715729 4.90596141 20.4852814.219669914 20.1923882-.0732233047 19.7175144-.0732233047 19.4246212.219669914 19.131728.512563133 19.131728.987436867 19.4246212 1.28033009 23.5251263 5.38083515 23.5251263 12.0290674 19.4246212 16.1295725 19.131728 16.4224657 19.131728 16.8973394 19.4246212 17.1902327 19.7175144 17.4831259 20.1923882 17.4831259 20.4852814 17.1902327z"/>
                <path id="on-air-in" d="M17.3033009 14.0082521C18.7217837 12.5897693 19.4928584 10.6983839 19.4999509 8.73215792 19.507111 6.74721082 18.7352286 4.8335782 17.3033009 3.40165043 17.0104076 3.10875721 16.5355339 3.10875721 16.2426407 3.40165043 15.9497475 3.69454365 15.9497475 4.16941738 16.2426407 4.4623106 17.3890249 5.6086948 18.0056933 7.13752465 17.9999607 8.72674718 17.9942823 10.30094 17.3782748 11.8119579 16.2426407 12.947592 15.9497475 13.2404852 15.9497475 13.7153589 16.2426407 14.0082521 16.5355339 14.3011454 17.0104076 14.3011454 17.3033009 14.0082521zM6.69669914 3.40165043C3.76776695 6.33058262 3.76776695 11.07932 6.69669914 14.0082521 6.98959236 14.3011454 7.46446609 14.3011454 7.75735931 14.0082521 8.05025253 13.7153589 8.05025253 13.2404852 7.75735931 12.947592 5.41421356 10.6044462 5.41421356 6.80545635 7.75735931 4.4623106 8.05025253 4.16941738 8.05025253 3.69454365 7.75735931 3.40165043 7.46446609 3.10875721 6.98959236 3.10875721 6.69669914 3.40165043z"/>
              </g>
            </svg>

          </div>
        </div>

      </div>


      
    </div>
    <div class="div_for_right_parth_black_border">
      
	  <div class="div_empty_parth_right"></div>
      <div class="div_for_border_parth_right">
        
      </div>
	  <div class="div_for_logo_1studio">
        <a href="{{asset('')}}">
          <div class='div_for_text_icon_part'>
            <h1>1studi</h1>
            <div id="box">

                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="18" viewBox="0 0 24 18">
                <g fill="#d60300" fill-rule="evenodd">
                  <ellipse cx="12" cy="8.705" rx="3" ry="3"/>
                  <path id="on-air-out" d="M3.51471863.219669914C-1.17157288 4.90596141-1.17157288 12.5039412 3.51471863 17.1902327 3.80761184 17.4831259 4.28248558 17.4831259 4.5753788 17.1902327 4.86827202 16.8973394 4.86827202 16.4224657 4.5753788 16.1295725.474873734 12.0290674.474873734 5.38083515 4.5753788 1.28033009 4.86827202.987436867 4.86827202.512563133 4.5753788.219669914 4.28248558-.0732233047 3.80761184-.0732233047 3.51471863.219669914zM20.4852814 17.1902327C25.1715729 12.5039412 25.1715729 4.90596141 20.4852814.219669914 20.1923882-.0732233047 19.7175144-.0732233047 19.4246212.219669914 19.131728.512563133 19.131728.987436867 19.4246212 1.28033009 23.5251263 5.38083515 23.5251263 12.0290674 19.4246212 16.1295725 19.131728 16.4224657 19.131728 16.8973394 19.4246212 17.1902327 19.7175144 17.4831259 20.1923882 17.4831259 20.4852814 17.1902327z"/>
                  <path id="on-air-in" d="M17.3033009 14.0082521C18.7217837 12.5897693 19.4928584 10.6983839 19.4999509 8.73215792 19.507111 6.74721082 18.7352286 4.8335782 17.3033009 3.40165043 17.0104076 3.10875721 16.5355339 3.10875721 16.2426407 3.40165043 15.9497475 3.69454365 15.9497475 4.16941738 16.2426407 4.4623106 17.3890249 5.6086948 18.0056933 7.13752465 17.9999607 8.72674718 17.9942823 10.30094 17.3782748 11.8119579 16.2426407 12.947592 15.9497475 13.2404852 15.9497475 13.7153589 16.2426407 14.0082521 16.5355339 14.3011454 17.0104076 14.3011454 17.3033009 14.0082521zM6.69669914 3.40165043C3.76776695 6.33058262 3.76776695 11.07932 6.69669914 14.0082521 6.98959236 14.3011454 7.46446609 14.3011454 7.75735931 14.0082521 8.05025253 13.7153589 8.05025253 13.2404852 7.75735931 12.947592 5.41421356 10.6044462 5.41421356 6.80545635 7.75735931 4.4623106 8.05025253 4.16941738 8.05025253 3.69454365 7.75735931 3.40165043 7.46446609 3.10875721 6.98959236 3.10875721 6.69669914 3.40165043z"/>
                </g>
              </svg>

            </div>
          </div>
       </a>
      </div>
    </div>
  @endif

    
  </div>
<main>
  @yield('content')
</main>
</div>

<!-- Help -->
<div id="help" style='position:fixed;left:15%;top:25%;width:70%;height:60%;border:1px solid #777;background:#fff;z-index:10;display:none;'>
  <div style='margin:15px;border-bottom:1px solid #aaa;padding-bottom:15px;'>
    <b>Selct Topic:</b>&nbsp;<select id='helpTopics' onchange="ShowHelpOf(this)"><option value='0'>Select Topic</option></select>
    <input type='button' value='Close' onclick="ShowHelp(0)" style='float:right;'>
</div>
<div id='help_1' style='margin:15px;'></div>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      {{--<div class="modal-header">--}}
      {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
      {{--<h4 class="modal-title" id="myModalLabel">Video stream</h4>--}}
      {{--</div>--}}
      <div id='divVideo' class="modal-body">
        <video width="640" height="360" id="player1">
          <!-- Pseudo HTML5 -->
          {{--<source type="application/x-mpegURL" src="http://198.241.44.164:8888/hls/master-acetest2.m3u8"/>--}}
          <source type="application/x-mpegURL" src="{{ $channel['stream_url'] }}"/>
      </video>
  </div>
</div>
</div>
</div>
<script>
function toggle(el) {
          el.classList.toggle('on-air');
        }
  $(document).ready(function() {
    $('.watch_now').click(function() {
      if (IsLiveStreamStarted())
      {
        var videoFile = "{{ $channel['stream_url'] }}";
        PlayVideo(videoFile);
    }
    else alert("There is no live stream to play...");
});

    $('.edit_view').click(function(event) {
      $('#head-logo').slideToggle();
  }); 
    $('.live_view').click(function(event) {
      $('#head-logo').slideDown();
  });
    $('#myModal').click(function(e) {
      window.setTimeout("StopVideo()", 1000);
  });


        // jwplayer('live_video').setup ({
        //     file: "{{ $channel['stream_url'] }}"
        // });
    });

  function StopVideo()
  {
    hd = $('#myModal').attr('aria-hidden');
    if (hd=='true') $("#divVideo video")[0].stop();
}

function IsLiveStreamStarted()
{
        // txt = $('#ss1').html();
        // if (txt.indexOf('STOP STREAM') > 0) return 1;
        // return 0;
    }

    function PlayVideo(videoFile)
    {
        /*
           $('#divVideo video source').attr('src', videoFile);
           if (videoFile.indexOf('.mp4') != -1)
           $('#divVideo video source').attr('type', 'video/mp4');
           else
           $('#divVideo video source').attr('type', 'application/x-mpegURL');

        */
		if(videoFile.v_source == 'dacast'){
			videoHtml = "<div id= 'videoContainer'><iframe width = '100%' height = '400' src = '"+videoFile.videoUrl+"'></iframe></div>";
			$('#divVideo').html(videoHtml);
			$('#myModal').modal('toggle');
		}
		else{

			videoHtml = "<video width='640' height='360' id='player1'><source type='";
			videoHtml += "video/mp4";
			videoHtml += "' src='" + videoFile.videoUrl + "'></video>";
			$('#divVideo').html(videoHtml);

			//$("#divVideo video")[0].load();
			$('#myModal').modal('toggle');

			if(videoFile.prerollUrl != ''){

				$('video').mediaelementplayer({
					// adsPrerollMediaUrl: 'http://media.productionhub.com.s3.amazonaws.com/preroll.mp4',
					adsPrerollMediaUrl: videoFile.prerollUrl,
					// adsPrerollAdUrl: 'http://www.github.com/',
					features: ['playpause', 'current', 'progress', 'duration', 'tracks', 'volume', 'fullscreen', 'ads', 'postroll'],
					startVolume: 1.0,
					// vastSkipSeconds: 5,
					success: function(player, node) {
						player.stop();
						player.play();
					}
				});
			}
			else{
				$('video').mediaelementplayer({
					success: function (media) {
						media.stop();
						media.play();
					}
				});

			}
		}

  }

  function PlayVideoFromID(vid)
  {
    $.ajax({
      url: "ajax_get_video_path",
      type: "GET",
      async: true,
      data: { "vid" : vid },
      success: function (data)
      {
        console.log(data);
        data = JSON.parse(data);
        PlayVideo(data);
    }
});
}

$('.playVideoInPopup').click(function (event) {
    event.stopPropagation();
    event.preventDefault();

    var vid = $(this).attr('video_id');
    PlayVideoFromID(vid);
    return false;
});

function OpenSituationRoom()
{
    window.open('https://dashboard.streamlyzer.com/overview#/app/sroom1');
}

function OnSeachClicked()
{
    if (typeof OnSearch !== "undefined")
    {
      OnSearch( document.getElementById('tSearch').value );
  }
  else alert("Search is not available for this page...");
}

</script>

    {{--Page Load--}}
    {{ HTML::style('loading/new_loading.css') }}
    <!-- {{ HTML::style('loading/loading.css') }} -->
    {{ HTML::script('loading/loading.js') }}
    {{ HTML::script('assets/js/app.js') }}
    {{ HTML::script('js/menu/jquery.slimmenu.min.js') }}
    {{ HTML::script('js/menu/horizontal_menu.js') }}

  <!-- begin olark code -->
  <script type="text/javascript" async>
      (function(o,l,a,r,k,y){if(o.olark)return;
      r="script";y=l.createElement(r);
      r=l.getElementsByTagName(r)[0];
      y.async=1;y.src="//"+a;r.parentNode.insertBefore(y,r);
      y=o.olark=function(){k.s.push(arguments);k.t.push(+new Date)};
      y.extend=function(i,j){y("extend",i,j)};
      y.identify=function(i){y("identify",k.i=i)};
      y.configure=function(i,j){y("configure",i,j);
      k.c[i]=j}; k=y._={s:[],t:[+new Date],c:{},l:a}; })
      (window,document,"static.olark.com/jsclient/loader.js");
      /* custom configuration goes here (www.olark.com/documentation) */
      olark.identify('9095-865-10-8908');
  </script>
  <!-- end olark code -->
</body>
</html>
