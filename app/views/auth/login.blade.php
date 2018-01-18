<!DOCTYPE html>
<html lang="en">
<head>

    <title>Login ― Dveo</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}" />

{{-- CSS Libraries --}}
{{--    {{ HTML::style('css/bootstrap.min.css') }}--}}
{{--    {{ HTML::style('css/bootstrap-responsive.css') }}--}}
{{ HTML::style('bower_components/flat-ui/dist/css/flat-ui.css') }}
{{--    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/normalize/3.0.1/normalize.min.css') }}--}}
{{ HTML::style('css/jQuery_ui_css/jquery-ui.css') }}
{{--{{ HTML::style('css/font-awesome.min.css') }}--}}

{{-- JS Libraries --}}
{{ HTML::script('js/jquery-2.1.1.min.js') }}
{{ HTML::script('js/jQuery_UI/jquery-ui.js') }}
{{--{{ HTML::script('js/bootstrap.min.js') }}--}}
{{ HTML::script('https://www.google.com/jsapi') }}
{{ HTML::script('js/jquery_file_upload/vendor/jquery.ui.widget.js') }}
{{ HTML::script('js/jquery_file_upload/jquery.iframe-transport.js') }}
{{ HTML::script('js/jquery_file_upload/jquery.fileupload.js') }}

{{-- My CSS files --}}
{{--{{ HTML::style('css/style.css') }}--}}
{{ HTML::style('css/index.css') }}
{{ HTML::style('css/collections.css') }}
{{ HTML::style('css/add_playlist.css') }}
{{ HTML::style('css/playout.css') }}
{{ HTML::style('css/upload.css') }}

{{-- My JS files --}}
{{ HTML::script('js/ace.js') }}
{{ HTML::script('js/index/ace.js') }}
{{ HTML::script('js/collections/add_collection.js') }}
{{ HTML::script('js/collections/drag_and_drop.js') }}
{{ HTML::script('js/collections/collections_index.js') }}
{{ HTML::script('js/index/script.js') }}
{{ HTML::script('js/playlist/script.js') }}
{{ HTML::script('js/videos/drag_and_drop.js') }}
{{ HTML::script('js/videos/get_video_desc.js') }}



<!-- Initializer -->
    <link rel="stylesheet" href="new_login/css/normalize.css">

    <!-- Web fonts and Web Icons -->
    <link rel="stylesheet" href="new_login/css/pageloader.css">
    <link rel="stylesheet" href="new_login/fonts/opensans/stylesheet.css">
    <link rel="stylesheet" href="new_login/fonts/asap/stylesheet.css">
    <link rel="stylesheet" href="new_login/css/ionicons.min.css">

    <!-- Vendor CSS style -->
    <link rel="stylesheet" href="new_login/css/foundation.min.css">
    <link rel="stylesheet" href="new_login/js/vendor/jquery.fullPage.css">
    <link rel="stylesheet" href="new_login/js/vegas/vegas.min.css">

    <!-- Main CSS files -->
    <link rel="stylesheet" href="new_login/css/main.css">
    <link rel="stylesheet" href="new_login/css/main_responsive.css">
    <link rel="stylesheet" href="new_login/css/style-color1.css">


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

    <script>
		ace = ace || {};

		ace.user_id = 0;

        @if(isset($channel))
			ace.channel_id = '{{$channel['id']}}';
        @endif
    </script>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body id = "menu" class="alt-bg loginWrapper">

    <!-- Page Loader -->
    <div class="page-loader" id="page-loader">
        <div><i class="ion ion-loading-d"></i><p>loading</p></div>
    </div>
    <!-- Begin of timer pane -->
    <div class="pane-when">
        <div class="content">
            <!-- Clock -->
            <div class="clock clock-countdown">
                <div class="site-config"
                     data-date="10/30/2016 00:00:00"
                     data-date-timezone="+0"
                >
                </div>
                <style>
                    .loginForm { position:relative;top:-50px; padding-right: 5px; }
                    .loginText { color: #fff;font-weight: bold; font-size:70px; text-align:center; padding: 10px 0 40px 0; /*text-transform:uppercase; display:inline;*/ }
                </style>
                @if($errors->first('noUser'))
                    <div data-alert class="alert-box alert radius" style="margin-bottom: 50px;">
                        {{ $errors->first('noUser') }}
                        <a href="#" class="close">&times;</a>
                    </div>
                @endif
                <div class="loginForm page loginM20" style='top:-200px;'>
                    <div class='loginText' id = "stud_logo">
                       <img src="/images/imgpsh_fullsize.png" alt="">
                    </div>
                    <div class="clearfix loginM20">
                        <div class="text-input-container">
                            <input type='text' id="username" required placeholder="Username">
                            <input type='password' id="password" required placeholder="Password">
                        </div>
                        <div class="form buttons" style='margin-top:10px;'>
                            <span id = "loginBtn" onclick="Login()" class="btn btn-1 btn-1b">Login to Dveo</span>
                        </div>
                        <small>OR</small>
                        <div class="googleLogin">
                            <a href="{{ $urlSignin }}" target="_blank">
                                <img src="/images/google.png" alt="">
                                <span>Sign in with Google</span>
                                {{--<button class="btn-signup btn btn-1 btn-1b">Use Google account</button>--}}
                            </a>
                        </div>

                        <small>Don't have an a Dveo account?</small>
                        <p>
                            <a href="/stepstolaunch-me" target="_blank">
                                <button class="btn-signup btn btn-1 btn-1b">Signup to Dveo here</button>
                            </a>
                        </p>
                        <div class="googleSignup">
                            <a href="{{ $urlSignup }}" target="_blank">
                                <img src="/images/google_signup.png" alt="">
                                <span>Sign up with Google</span>
                            </a>
                        </div>

                    </div>
                </div>
                <script language='javascript'>
                    function Login()
                    {
                        Login1(document.getElementById('username').value, document.getElementById('password').value);
                    }
                </script>
            </div>

        </div>
    </div>

    <div class="form-wrapper" style="display: none;">

        {{ Form::open(array('url' => 'login', 'class' => 'form-horizontal', 'name' => 'l2')) }}

        <div class="form-item" @if($errors->first('username')) id="loginError" @endif>
            <label for="text">
                <span class="fui-user"></span></label>
            {{ Form::text('username', Input::old('username'), array('class' => 'form-control loginUsername', 'placeholder' => 'Username', 'autocomplete' => 'off', 'id' => 'username1')) }}
        </div>

        <div class="form-item" @if($errors->first('password')) id="passwordError" @endif>
            <label for="password">
                <span class="fui-lock"></span></label>
            {{ Form::password('password', array('class' => 'form-control loginPassword', 'placeholder' => 'Password', 'autocomplete' => 'off', 'id' => 'password1')) }}
        </div>

        <div class="button-panel">
            {{ Form::submit('Sign In', array('class' => 'button btn-inverse')) }}
        </div>

        {{ Form::close() }}

    </div>



    {{--Page Load--}}
    {{ HTML::style('loading/loading.css') }}
    {{ HTML::script('loading/loading.js') }}


    <!-- All vendor scripts -->
    <script src="new_login/js/vendor/all.js"></script>

    <!-- Downcount JS -->
    <script src="new_login/js/jquery.downCount.js"></script>

    <!-- Form script -->
    <script src="new_login/js/form_script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r73/three.min.js"></script>
    <script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/175711/THREE.OrbitControls.js"></script>

    <!-- Javascript main files -->
    <script src="new_login/js/main.js"></script>
    <script type="text/javascript">
        $('#password,#username').on('keydown', function (e) {
            if (e.which == 13) {
                Login();
            }
        });
    </script>

    {{--<canvas id="c"></canvas>--}}

    {{--<script id="shader-fs" type="x-shader/x-fragment">--}}
            {{--#ifdef GL_ES--}}
              {{--precision highp float;--}}
              {{--#endif--}}
        {{--void main(void) {--}}
         {{--gl_FragColor = vec4(0.2, 0.3, 0.4, 1.0);--}}
        {{--}--}}
    {{--</script>--}}

    {{--<script id="shader-vs" type="x-shader/x-vertex">--}}
        {{--attribute vec3 vertexPosition;--}}

        {{--uniform mat4 modelViewMatrix;--}}
        {{--uniform mat4 perspectiveMatrix;--}}

        {{--void main(void) {--}}
            {{--gl_Position = perspectiveMatrix * modelViewMatrix * vec4(  vertexPosition, 1.0);--}}
        {{--}--}}
    {{--</script>--}}



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

<script language='javascript'>
	function Login1(u, p)
	{
		document.getElementById('username1').value = u;
		document.getElementById('password1').value = p;
		document.l2.submit();
	}
</script>

</html>
