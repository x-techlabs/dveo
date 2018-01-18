<html>
    <head>
        <title>Channel ― 1studio</title>
        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}" />
        {{ HTML::style('css/bootstrap.min.css') }}
        {{ HTML::style('bower_components/flat-ui/dist/css/flat-ui.css') }}
        {{ HTML::style('css/channels.css') }}

        {{ HTML::script('js/jquery-2.1.1.min.js') }}
        {{ HTML::script('js/jquery.transit.min.js') }}
        {{ HTML::script('js/channels/channels.js') }}
    </head>
    <body>
        <div id="wrapper" class="container showLoader" style="display: none;">
            <header>
                <div class="dashboard" style="justify-content: center">
                    <a id="dashboard" href="{{ asset('dashboard') }}"><img src="{{ asset('images/dveologo.gif') }}" alt="" class="logo center-block"><span>{{ $company->name }}</span></a>
                </div>
                <div class="user">
                    <span class="userName">{{ $user->username }}</span>
                    <span class="vert vertLeft"></span>
                    <a id="userSettings" class="header_icon" href="#"><span class="fui-user"></span></a>
                    <a id="settings" class="header_icon" href="#"><span class="fui-gear"></span></a>
                    <span class="vert vertRight"></span>
                    <a id="userExit" class="header_icon" href="{{ URL::route('logout') }}"><span class="fui-exit"></span></a>
                </div>
                <div class="border"></div>
            </header>
            <div class="settings absoluteSettings">
                <div class="arrow-down"></div>
            </div>
            <div class="userSettings absoluteSettings">
                <div class="arrow-down"></div>
            </div>
            <section>
                <div class="content">
                    @if(Auth::user()->is(User::USER_MANAGE_COMPANY))
                    <div class="col-md-3">
                        <div class="channel_box_new">
                            <a href="http://1stud.io/stepstolaunch-me" target = "_blank" class="cross">
                                <div class="horizontal"></div>
                                <div class="vertical"></div>
                            </a>
                            <a href="#">Add channel</a>
                        </div>
                    </div>
                    @endif
                    @foreach($channels as $channel)
                    <div class="col-md-3">
                        <a href="@if(Auth::user()->is(User::USER_MANAGE_MEDIA)) channel_{{ $channel->id }}/home @elseif(Auth::user()->is(User::USER_MANAGE_CHANNEL)) channel_{{ $channel->id }}/settings @else # @endif" class="channel_box">
                            <div class="title">{{ $channel->title }}</div>
                            <span class="fui-new"></span>
                            @if(isset($channel['logo_ext']) && !empty($channel['logo_ext']))
                                <img onerror="$(this).attr('src', '{{asset('images/noLogo.png')}}')" src="http://aceplayout.s3.amazonaws.com/logos/channel_{{ $channel['id'] }}.{{ $channel['logo_ext'] }}?{{ md5($channel['updated_at']) }}" class="channel_image">
                                {{--<img onerror="$(this).attr('src', '{{asset('images/noLogo.png')}}')" src="http://prolivestream.s3.amazonaws.com/logos/channel_{{ $channel['id'] }}.{{ $channel['logo_ext'] }}?{{ md5($channel['updated_at']) }}" class="channel_image">--}}
                            @else
                                <img onerror="$(this).attr('src', '{{asset('images/noLogo.png')}}')" src="http://aceplayout.s3.amazonaws.com/logos/channel_{{ $channel['id'] }}?{{ md5($channel['updated_at']) }}" class="channel_image">
                                {{--<img onerror="$(this).attr('src', '{{asset('images/noLogo.png')}}')" src="http://prolivestream.s3.amazonaws.com/logos/channel_{{ $channel['id'] }}?{{ md5($channel['updated_at']) }}" class="channel_image">--}}
                            @endif
                            <div class="created"><span>Created at: </span>{{ $channel->date }}</div>
                        </a>
                    </div>
                    @endforeach
                    <div class="clear"></div>
                </div>
            </section>
        </div>

        {{--Page Load--}}
        {{ HTML::style('loading/loading.css') }}
        {{ HTML::script('loading/loading.js') }}

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
