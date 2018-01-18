@extends('template.template')
@section('content')
    <div id = "search_wrapper">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <form action="searchVideos" method="get" accept-charset="utf-8" class="form-horizontal">
                    <div class="col-md-10">
                        <input class="form-control" type="text" name="query" placeholder = "Search">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-info">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop