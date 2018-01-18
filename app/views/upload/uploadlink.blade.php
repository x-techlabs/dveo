@extends('template.template')
@section('content')

<div class="row center-block height" id="contnet-wrap">
    <div class="col-md-12 height" id="upload" style="text-align: center">
        <div id="upload-body" class="height content">
            <p class="title-name">External video link</p>
            <div class="col-md-12 height">
                <table width='100%'>
                <tr><td> External Video Source </tr></td>
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

<script type="text/javascript">
window.onload = function()
{
    $('#source_video').val('external');
    $('#catGroup').hide();
    ShowForm();
}

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
