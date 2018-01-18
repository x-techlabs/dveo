<div class="vod_playlist_content">
    <input type="hidden" class="rss_url" value = "{{ url('channel_'.$channel_id.'/get_playlist_rss', $playlist->id) }}">

    <div class="col-md-8 appendPlaylist">
        <div class="row">
            <div id = "customize_playlist" class = "col-md-3">
                <ul class="customize_options">
                    <li class="option">
                        <p class="option_name">Embed Type</p>
                        <ul>
                            <li class="sub_option">Menu Options</li>
                            <li>
                                <div>
                                    <input type="radio" name="menu_position" class="menu_type right_box" value = "right">
                                    Show menu to the right
                                </div>
                                <div>
                                    <input type="radio" name="menu_position" class="menu_type bottom_box" checked value = "bottom">
                                    Show menu below the video
                                </div>
                            </li>
                            <li class="sub_option">Video dimensions</li>
                            <li class="dimensions">
                                <input type="text" name="" class="player_width form-control" value = "850">
                                <span>X</span>
                                <input type="text" name="" value="478" class="player_height form-control">
                            </li>
                            <li class="show_advanced">Show advanced options</li>
                            <div class="advanced_options">
                                <li class="sub_option">Embed type</li>
                                <li>
                                    <div>
                                        <input type="radio" name="embed_type" value = "iframe" class="embed_type"> Iframe
                                    </div>
                                    <div>
                                        <input type="radio" name="embed_type" value = "api" class="embed_type" checked> API
                                    </div>
                                </li>
                            </div>
                        </ul>

                    </li>
                    <li class="option">
                        <p class="option_name">Social Sharing</p>
                        <ul>
                            <li>
                               <div>
                                    <input type="checkbox" checked name="social_name" class="social_name" value = "email"> Email
                                </div>
                                <div>
                                    <input type="checkbox" checked name="social_name" class="social_name" value = "twitter"> Twitter
                                </div>
                                <div>
                                    <input type="checkbox" checked name="social_name" class="social_name" value = "reddit"> Reddit
                                </div>
                                <div>
                                    <input type="checkbox" checked name="social_name" class="social_name" value = "linkedin"> LinkedIn
                                </div> 

                                <div>
                                    <input type="checkbox" checked name="social_name" class="social_name" value = "googleplus"> Google Plus
                                </div>
                                <div>
                                    <input type="checkbox" checked name="social_name" class="social_name" value = "facebook"> Facebook
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-12">
                        <div id="get_playlist">
                            <div class="headline">Embed Code</div>
                            <div class="embed_code"></div>
                        </div>
                    </div>
                    <div class = "playlist_wrapper col-md-12">
                        <div id = "playlist"></div>
                    </div>
                    <ul id = "list" class="col-md-12"></ul>
                </div>
            </div>
        </div>
    </div>
    <script src="https://content.jwplatform.com/libraries/DbXZPMBQ.js"></script>
    <script>

        
        var iframeCode = '';
        var popoverCode = '';
        var playerInstance = jwplayer("playlist");
        var social = ['facebook','twitter','linkedin','googleplus','email','reddit'];
        var player_width = $('.player_width').val();
        var player_height = $('.player_height').val();
        var rss_url = $('.rss_url').val();
        console.log(rss_url);
        playerInstance.setup({
            playlist: rss_url,
            sharing: {
                sites: social
            },
            listbar: {
                position: "right",
                size: 234
            }
        });


        var list = document.getElementById("list");
        var html = list.innerHTML;

        playerInstance.on('ready',function(){
            var playlist = playerInstance.getPlaylist();
            for (var index=0;index<playlist.length;index++){
                var playindex = index +1;
                html += "<li><span class='dropt' title='"+playlist[index].title+"'><a href='javascript:playThis("+index+")'><img class = 'video_name' src='" + playlist[index].image + "'</img></br>"+playlist[index].title+"</a></br><span style='width:500px;'</span></span></li>"
                list.innerHTML = html;
            }

            var sharingPlugin = playerInstance.getPlugin('sharing');

        });
        function playThis(index) {
            playerInstance.playlistItem(index);
        }

        $(document).ready(function(){
            $('.menu_type').click(function(event) {
                var position = $(this).val();
                if(position == 'right'){
                    $('#list').removeClass("bottom_list");
                    $('#list').addClass("right_list");
                    $('#list').removeClass("col-md-12");
                    $('#list').addClass("col-md-3");
                    $('.playlist_wrapper').removeClass("col-md-12");
                    $('.playlist_wrapper').addClass("col-md-9");
                    var height = $('.player_height').val();
                    var width = $('.player_width').val();
                    var data = generete_apiCode(width,height,social);
                    $('.embed_code').text(data); 
                }else{
                    $('#list').removeClass("right_list");
                    $('#list').addClass("bottom_list");
                    $('#list').addClass("col-md-12");
                    $('#list').removeClass("col-md-3");
                    $('.playlist_wrapper').removeClass("col-md-9");
                    $('.playlist_wrapper').addClass("col-md-12");
                    var height = $('.player_height').val();
                    var width = $('.player_width').val();
                    var data = generete_apiCode(width,height,social);
                    $('.embed_code').text(data); 

                }
            });
            $('.option_name').click(function(event) {
                $(this).next().slideToggle();
            });
            $('.show_advanced').click(function(event) {
                $('.advanced_options').toggle();
            });
            
            $('.enable_sharing').click(function(event) {
                $('#playlist .jw-sharing-dock-btn').toggle();
            });
            $('.social_name').click(function(event) {
                var checked = $(this).prop('checked');
                var height = $('.player_height').val();
                var width = $('.player_width').val();
                var item = $(this).val();
                if(checked){
                    social.push(item);
                    $('.jw-sharing-icon-'+item+'').show();
                }
                else{
                    social = jQuery.grep(social, function(value) {
                      return value != item;
                    });
                    $('.jw-sharing-icon-'+item+'').hide();
                }
                var data = generete_apiCode(width,height,social);
                $('.embed_code').text(data);  
            });

            $('.player_width').change(function(event) {
                player_width = $(this).val();
                var height = $('.player_height').val();
                var data = generete_apiCode(player_width,height);
                $('.embed_code').text(data);

            });
            $('.player_height').change(function(event) {
                player_height = $(this).val();
                var width = $('.player_width').val();
                var data = generete_apiCode(width,player_height);
                $('.embed_code').text(data);      
            });

            var data = generete_apiCode();

            $('.embed_code').text(data);

            $('.embed_type').click(function(event) {
                var type = $(this).val();
                if(type == "api"){
                    var height = $('.player_height').val();
                    var width = $('.player_width').val();
                    var data = generete_apiCode(width,height.social);
                    $('.embed_code').text(data);
                }
                else if(type == 'iframe'){
                    var height = $('.player_height').val();
                    var width = $('.player_width').val();
                    var type = 'iframe';
                    var data = generete_apiCode(width,height,social,type);
                    // console.log(height,width);
                    $.ajax({
                        url: ace.path('genereteEmbedCode'),
                        type: 'POST',
                        dataType: 'json',
                        data: {html : data},
                        success: function(data) {
                            // console.log(data.fileUrl);
                            var embedCode = "<iframe width = '"+width+"' height = '"+height+"' frameborder = '0' src = '"+data.fileUrl+"'></iframe>";
                            $('.embed_code').text(embedCode);

                        }
                    });
                }
            });

        })//End document ready function

        // Generate API code
        function generete_apiCode(width = 850,height = 478,social,type = 'api',sidebar_style = ''){
            var social_array = (typeof social != 'undefined') ? social : ['facebook','twitter','linkedin','googleplus','email','reddit'];
            if(type == 'iframe'){
                height = Math.round(height/3) + 20;
            }
            // console.log(height);
            var apiCode = '';
            var rightSidebar = $('.right_box').is(':checked') ? 'right_sidebar' : 'bottom_sidebar';
            apiCode += '<div class = "playlist_wrapper"><div id="playlist" class="jwplayer_embed" style="width:'+width+'px;height:'+height+'px;"></div></div><ul id = "list" class="'+rightSidebar+'"></ul><style>.playlist_wrapper{float:left;}#list.right_sidebar{float: left;width:'+Math.round(width/3)+'px ;height: '+height+'px;overflow-y: scroll;}#list.right_sidebar li {width: 100%;float: none;}#list li img{width:100%;}#list{padding-left:0}#list.bottom_sidebar li{width:20%;float:left;font-size:12px;height:'+(height-40)+'}#list.bottom_sidebar{clear:left;width:'+width+'px;list-style: none;margin-left: 0;}</style>';
            apiCode += "<script src='https://content.jwplatform.com/libraries/DbXZPMBQ.js'><" + "/script>";
            apiCode += "<script>" 
                apiCode += "jwplayer('playlist').setup({playlist: 'https://cdn.jwplayer.com/v2/playlists/sPITwr9j?format=mrss','width': "+width+",'height': "+height+",sharing: {sites: "+JSON.stringify(social_array)+"}});";
            
                apiCode += "var list = document.getElementById('list');var html = list.innerHTML;jwplayer('playlist').on('ready',function(){var playlist = jwplayer('playlist').getPlaylist();for (var index=0;index<playlist.length;index++){var playindex = index +1;html += '<li><span class="+'"dropt"'+" title='+playlist[index].title+'><a href="+'"javascript:playThis('+"'+index+'"+')"'+"><img class = "+'"video_name"'+" src='+ playlist[index].image +'</img></br>'+playlist[index].title+'</a></br><span style="+'"width:500px;"'+"</span></span></li>';list.innerHTML = html;}var sharingPlugin = jwplayer('playlist').getPlugin('sharing');});function playThis(index) {jwplayer('playlist').playlistItem(index);}";
            apiCode += "<" + "/script>";

            return apiCode;
        }
    </script>
</div>

