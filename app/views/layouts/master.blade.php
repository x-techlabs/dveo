<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <a class="navbar-brand" href="#">Laravel</a>
        </div>
        <!-- Everything you want hidden at 940px or less, place within here -->
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li><a href="{{{ URL::to('') }}}">Home</a></li>
                @if ( Auth::guest() )
                <li>{{ HTML::link('login', 'Login') }}</li>
                @else
                <li>{{ HTML::link('logout', 'Logout') }}</li>
                @endif
            </ul>
        </div>
    </div>
</div>

<!-- Container -->
<div class="container">

    <!-- Success-Messages -->
    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h4>Success</h4>
        {{{ $message }}}
    </div>
    @endif

    <!-- Content -->
    @yield('content')

</div>
