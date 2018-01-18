<!DOCTYPE html>
<html lang="en">
<head>

    <title>Login ― ACE Playout</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}" />

    {{-- CSS Libraries --}}
    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('css/bootstrap-responsive.css') }}
    {{ HTML::style('bower_components/flat-ui/dist/css/flat-ui.css') }}
    {{ HTML::style('//cdnjs.cloudflare.com/ajax/libs/normalize/3.0.1/normalize.min.css') }}
    {{ HTML::style('css/jQuery_ui_css/jquery-ui.css') }}
    {{ HTML::style('css/font-awesome.min.css') }}

    {{-- JS Libraries --}}
    {{ HTML::script('js/jquery-2.1.1.min.js') }}
    {{ HTML::script('js/jQuery_UI/jquery-ui.js') }}
    {{ HTML::script('js/bootstrap.min.js') }}
    {{ HTML::script('http://www.google.com/jsapi') }}
    {{ HTML::script('js/jquery_file_upload/vendor/jquery.ui.widget.js') }}
    {{ HTML::script('js/jquery_file_upload/jquery.iframe-transport.js') }}
    {{ HTML::script('js/jquery_file_upload/jquery.fileupload.js') }}

    {{-- My CSS files --}}
    {{ HTML::style('css/style.css') }}
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
<body class="login">
    <div class="form-wrapper showLoader" style="display: none;">

        {{ Form::open(array('url' => 'login', 'class' => 'form-horizontal')) }}

            <div class="form-item" @if($errors->first('username')) id="loginError" @endif>
                <label for="text">
                    <span class="fui-user"></span></label>
                {{ Form::text('username', Input::old('username'), array('class' => 'form-control loginUsername', 'placeholder' => 'Username', 'autocomplete' => 'off')) }}
            </div>

            <div class="form-item" @if($errors->first('password')) id="passwordError" @endif>
                <label for="password">
                    <span class="fui-lock"></span></label>
                {{ Form::password('password', array('class' => 'form-control loginPassword', 'placeholder' => 'Password', 'autocomplete' => 'off')) }}
            </div>

            <div class="button-panel">
                {{ Form::submit('Sign In', array('class' => 'button btn-inverse')) }}
            </div>

        {{ Form::close() }}

    </div>

    {{--Page Load--}}
    {{ HTML::style('loading/loading.css') }}
    {{ HTML::script('loading/loading.js') }}
</body>
</html>