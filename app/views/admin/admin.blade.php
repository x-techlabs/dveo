<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin ― Dveo</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}" />

    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('css/font-awesome.min.css') }}
    {{ HTML::style('css/admin.css') }}
    {{ HTML::style('youtube_importer/css/select2.min.css') }}

    {{ HTML::script('js/jquery-2.1.1.min.js') }}
    {{ HTML::script('js/bootstrap.min.js') }}
    {{ HTML::script('js/bootbox.min.js') }}
    {{ HTML::script('js/path.min.js') }}

    {{ HTML::script('js/admin/script.js') }}
    {{ HTML::script('youtube_importer/js/select2.min.js') }}
</head>
<body>
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <nav class="navbar navbar-default" role="navigation">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="/">Dveo</a>
                        </div>
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            <ul class="nav navbar-nav">
                                <li class="active"><a href="#/companies" id="getCompanies">Companies</a></li>
                                <li><a href="#/channels" id="getChannels">Channels</a></li>
                                <li><a href="#/users" id="getUsers">Users</a></li>
                                <li><a href="#/dveos" id="getDveos">DVEO</a></li>
                            </ul>
                            <a href="{{ URL::route('logout') }}" class="btn btn-default navbar-btn navbar-right">Log out</a>
                            <a href="{{ URL::route('reload') }}" class="btn btn-default navbar-btn navbar-right" id="loader">
                                <i class="fa fa-refresh"></i>
                                {{--<img src="{{ asset('images/admin_loader.gif') }}">--}}
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
            <div class="panel-append"></div>
        </div>
    </div>
</body>
</html>