<div class="col-md-4 height" id="collections-col" style="width: 31.333333%;">

    <div style="background-color: #ffffff; border-radius: 10px; padding: 0 10px 0 10px; margin-top: 8px;"
         class="height">
        <p class="title-name">
            Playlists

        </p>

        <div class="input-group">
            <input type="text" class="form-control" placeholder="Search" id="search-query-3">
      <span class="input-group-btn">
        <button type="submit" class="btn"><span class="fui-search"></span></button>
      </span>
        </div>
        <div class="row center-block list" id="container_content">
            <div class="col-md-12 scrollable">
                <hr class="hr-2">
                @foreach($playlists as $playlist)

                <section class="section_collections cursor">

                    <div class="row center-block" playlist_id="{{$playlist->id}}">

                        <div class="col-md-12" style="">
                            <p style="text-align: left">
                                {{$playlist->title}}
                            </p>

                        </div>
                    </div>
                    <button class="btn btn-success add-playlist">Add playlist</button>
                    <hr class="hr-2-2">
                </section>
                @endforeach
            </div>
        </div>
    </div>
</div>
