<?php
//echo Hash::make('test');

App::missing(function($exception)
{
    return Response::view('errors.404', array(), 404);
});
Route::get('/test', function() {
//    BaseController::get_dveo_ip();
//    $dveo = new DVEO(BaseController::get_dveo_ip(), 25599, 'apiuser', 'Hn7P67583N9m5sS');
//    $dveo = new DVEO('68.142.96.137', 25599, 'apiuser', 'Hn7P67583N9m5sS');
//    $dveo = DVEO::getInstance('68.142.96.137', 25599, 'Hn7P67583N9m5sS');
    $dveo = DVEO::getInstance('198.241.44.164', 25599, 'Hn7P67583N9m5sS');

    //return View::make('stream.stream');
    print_r($dveo->get_all_streams());

//    print_r($dveo->get_stream_status('file_stream1'));
});

Route::get('/lara-version', function()
{
    $laravel = app();
    return "Your Laravel version is ".$laravel::VERSION;
});

Route::get('video', function() {
    return View::make('videojs');
});
Route::get('hybrik_file_success/{jobId}','UploadController@hybrikFileSuccess');
Route::group(array('name' => 'channel', 'prefix' => 'channel_{channel_id}', 'before' => 'detect_channel_id|auth'), function() {

    // Redirect to videos
    Route::get('/', function() {
        $channel_id = BaseController::get_channel_id();
        return Redirect::to("channel_{$channel_id}/videos");
    });

    // Search
	Route::post('update_xml_btn','TVAppController@update_xml_btn');

    Route::get('searchVideos', 'SearchController@videoSearch');
	Route::get('search', 'SearchController@search');


    //modules from streamvision
    Route::get('addvideos', 'MediaLibraryController@addVideos');
    Route::post('addvideos', 'MediaLibraryController@doAddVideos');
    Route::get('listvideo', 'MediaLibraryController@getVideo');


    //s3 to dveo videos restoring
    Route::get('s3_to_dveo', 'UploadController@s3_to_dveo');

    // Upload
    Route::get('upload', 'UploadController@upload');
    Route::get('uploadTest', 'UploadController@uploadTest');
    Route::get('uploadLink', 'UploadController@uploadLink');
    Route::get('add_video', 'VideoController@add_video');
    Route::get('add_video_s3', 'VideoController@add_video_s3');//hybrik
    Route::post('addVideo', 'UploadController@add_videos');
    Route::post('save_s3_file','UploadController@createVideoFromS3');
    Route::post('authorize_upload', 'UploadController@authorize_upload');
    Route::get('postproc', 'UploadController@post_proc');
    Route::post('send_amazon', 'UploadController@send_amazon');
    Route::post('send_amazon_logo', 'UploadController@send_amazon_logo');
    Route::post('send_amazon_video_logo', 'UploadController@send_amazon_video_logo');
    Route::post('send_amazon_playlist_logo', 'UploadController@send_amazon_playlist_logo');
    Route::post('send_amazon_playlist_banner', 'UploadController@send_amazon_playlist_banner');
    Route::post('send_amazon_mobileweb_image', 'UploadController@send_amazon_mobileweb_image');
    Route::post('send_amazon_mobileweb_image_for_playlist', 'UploadController@send_amazon_mobileweb_image_for_playlist');
    Route::post('send_amazon_tvapp_image', 'UploadController@send_amazon_tvapp_image');
    Route::post('send_amazon_poster_image', 'UploadController@send_amazon_poster_image');
    Route::post('encode', 'UploadController@video_encode');
    Route::get('add_ifame_videos', 'UploadController@add_ifame_videos');
    Route::post('add_ifame_videos', 'UploadController@add_ifame_videos');

    // VodLogin
    Route::post('vodlogin', 'VodLoginController@vodLogin');
    Route::post('vodregister', 'VodLoginController@vodRegister');
    Route::get('vodlogout', 'VodLoginController@vodLogout');


    Route::post('import_videos', 'YoutubeChannelController@import_videos');
    Route::get('download_video/{video_id}/{format}', 'YoutubeChannelController@download_video');
    Route::get('download_all/{video_id}/{format}/{quality}', 'YoutubeChannelController@download_all');

    // VodAmazonS3
    Route::post('accessamazons3', 'VodAmazonS3Controller@accessamazons3');
    Route::get('displayallbuckets', 'VodAmazonS3Controller@displayallbuckets');
    Route::post('downloadrequestmedia', 'VodAmazonS3Controller@downloadrequestmedia');
    Route::get('getmediadownloadstatus','VodAmazonS3Controller@getmediadownloadstatus');
    Route::get('createnewbucket','VodAmazonS3Controller@createnewbucket');
    Route::post('uploadmediaintobucket','VodAmazonS3Controller@uploadmediaintobucket');
    Route::post('uploadmediasingleintobucket','VodAmazonS3Controller@uploadmediasingleintobucket');
    Route::get('getmediauploadstatus','VodAmazonS3Controller@getmediauploadstatus');
    Route::post('deletebucketmedia','VodAmazonS3Controller@deletebucketmedia');
    Route::get('deleteonebucket','VodAmazonS3Controller@deleteonebucket');
    Route::post('pushmediaobject','VodAmazonS3Controller@pushmediaobject');
    Route::post('start_transcode', 'VodAmazonS3Controller@start_transcode');
    Route::post('istranscodeprocesscomplete', 'VodAmazonS3Controller@istranscodeprocessComplete');
    Route::post('uploadfilestoawsserver', 'VodAmazonS3Controller@uploadfilestoawsserver');
    Route::post('video_add_to_table', 'VodAmazonS3Controller@video_add_to_table');

    // VodTranscoder
    Route::put('createtranscodingprofile','VodTranscoderController@createtranscodingprofile');
    Route::get('deletetranscodingprofile', 'VodTranscoderController@deletetranscodingprofile');
    Route::post('modifytranscodingprofile', 'VodTranscoderController@modifytranscodingprofile');
    Route::get('getalltranscodingprofile', 'VodTranscoderController@getalltranscodingprofile');
    Route::post('createtranscodingjobs', 'VodTranscoderController@createtranscodingjobs');
    Route::post('createsingletranscodingjobs', 'VodTranscoderController@createsingletranscodingjobs');
    Route::put('scheduletranscodingjobs', 'VodTranscoderController@scheduletranscodingjobs');
    Route::get('gettranscodingjobsstatus', 'VodTranscoderController@gettranscodingjobsstatus');
    Route::get('gettranscodingjobsresults', 'VodTranscoderController@gettranscodingjobsresults');
    Route::put('generateallthumbnails', 'VodTranscoderController@generateallthumbnails');
    Route::get('canceltranscodingjobs', 'VodTranscoderController@canceltranscodingjobs');
    Route::get('notifytranscodingjobserrors', 'VodTranscoderController@notifytranscodingjobserrors');
    Route::get('gets3mediametadatainfo', 'VodTranscoderController@gets3mediametadatainfo');
    Route::post('vod_add_video', 'VodTranscoderController@vod_add_video');


    // VodUploader
    Route::post('accessftpserver', 'VodUploadFTPController@accessftpserver');
    Route::get('displayallfiles', 'VodUploadFTPController@displayallfiles');
    Route::post('downloadrequestedftpmedia', 'VodUploadFTPController@downloadrequestedftpmedia');
    Route::get('getftpmediadownloadstatus', 'VodUploadFTPController@getftpmediadownloadstatus');
    Route::post('uploadmediaintoftpserver', 'VodUploadFTPController@uploadmediaintoftpserver');
    Route::post('uploadmediasingleintoftpserver', 'VodUploadFTPController@uploadmediasingleintoftpserver');
    Route::get('getftpmediauploadstatus', 'VodUploadFTPController@getftpmediauploadstatus');


    // Playlist
    Route::get('playlists', 'PlaylistController@index');
    Route::get('add_to_playlist', function(){
        return View::make('playlist/playlist');
    });
	Route::post('get_playlist_info', 'PlaylistController@get_playlist_info');
    Route::get('get_videos_by_playlist_id', 'VideoController@get_videos_by_playlist_id');
    Route::get('get_videos_for_playlists', 'VideoController@get_videos_for_playlists');
    Route::get('ajax_get_video_path', 'VideoController@get_video_path');
    Route::get('ajax_get_video_duration', 'VideoController@get_video_duration');
    Route::get('downloadVideo/{video_id}', 'VideoController@downloadVideo');


    Route::post('add_to_playlist', 'PlaylistController@add_to_playlist');
    Route::post('delete_playlist', 'PlaylistController@delete_playlists');
    Route::post('edit_playlist', 'PlaylistController@edit_playlist');
    Route::post('get_playlist_by_id', 'PlaylistController@get_playlist_by_id');

    Route::post('insert_video_in_playlist', 'PlaylistController@insert_video_in_playlist');

    Route::post('playlist_master_loop', 'PlaylistController@master_loop');


    // VOD playlist
    Route::get('vod_playlist', 'VodPlaylistController@index');
	Route::get('vod_playlist/{playlist_id}', 'VodPlaylistController@get_playlist_by_id');
    Route::get('get_playlist_rss/{playlist_id}', 'VodPlaylistController@get_playlist_rss');
    Route::get('get_playlist_videos', 'VodPlaylistController@get_playlist_videos');
    Route::post('get_vod_playlist', 'VodPlaylistController@get_vod_playlist');
    Route::post('genereteEmbedCode', 'VodPlaylistController@genereteEmbedCode');
    
    // TVApp

    Route::get('tvapp_playlists', 'TVAppController@tvapp_playlists');
    
    Route::get('tvapp_add_to_playlist', function(){
        $platforms = TvappPlatform::all();
        $channel_id = BaseController::get_channel_id();
        $collections = Collections::where('channel_id', '=', BaseController::get_channel_id())->orderBy('id', 'DESC')->get();
        $playlist_category = TvappPlaylist::where("channel_id","=",$channel_id)->get(); 
		 return View::make('tvapp/tvapp_playlists')->with(['platforms' => $platforms])->with(['collections' => $collections])->with(['playlist_category' => $playlist_category]);
    });
    Route::get('tvapp_get_videos_by_playlist_id', 'VideoController@tvapp_get_videos_by_playlist_id');
    Route::get('tvapp_get_videos_for_playlists', 'VideoController@tvapp_get_videos_for_playlists');

    Route::get('tvapp', 'TVAppController@index');
    Route::get('tvapp_live', 'TVAppController@tvapp_live');
    Route::get('tvapp_playlists', 'TVAppController@tvapp_playlists');
    Route::get('tvapp_playlists_preview', 'TVAppController@tvapp_playlists_preview');

    Route::get('tvapp_about_us', 'TVAppController@tvapp_about_us');

    Route::post('tvapp_live_update', 'TVAppController@tvapp_live_update');
    Route::post('tvapp_about_us_update', 'TVAppController@tvapp_about_us_update');

    Route::post('tvapp_add_to_playlist', 'TVAppController@tvapp_add_to_playlist');
    Route::post('tvapp_delete_playlist', 'TVAppController@tvapp_delete_playlist');
    Route::post('tvapp_duplicate_playlist', 'TVAppController@tvapp_duplicate_playlist');
    Route::post('tvapp_edit_playlist', 'TVAppController@tvapp_edit_playlist');
    Route::post('tvapp_get_playlist_by_id', 'TVAppController@tvapp_get_playlist_by_id');

    Route::post('tvapp_insert_video_in_playlist', 'TVAppController@tvapp_insert_video_in_playlist');
    Route::post('tvapp_insert_video_in_playlist_new', 'TVAppController@tvapp_insert_video_in_playlist_new');
    Route::post('tvapp_remove_from_playlists', 'TVAppController@tvapp_remove_from_playlists');
    Route::post('tvapp_sort_order_change', 'TVAppController@tvapp_sort_order_change');
    Route::post('tvapp_generate_feed', 'TVAppController@tvapp_generate_feed');
    Route::post('mrssForSchedule', 'TVAppController@mrssForSchedule');

    Route::post('tvapp_help', 'HelpController@tvapp_help');
    Route::post('help_get_subject_list', 'HelpController@help_get_subject_list');

    Route::get('Analytics', 'AnalyticsController@index');
    Route::get('analytics_get_data_for', 'AnalyticsController@analytics_get_data_for');

    //--------------------------------------------------------------------------
    Route::post('aws_files_from_bucket', 'VideoUploadLinkController@getFilesFromBucket');

    Route::post('aws_create_videos', 'VideoUploadLinkController@awsCreateVideos');
    Route::post('dacast_create_videos', 'VideoUploadLinkController@dacastCreateVideos');
    Route::post('wistia_create_videos', 'VideoUploadLinkController@wistiaCreateVideos');

    Route::post('aws_buckets', 'VideoUploadLinkController@getAwsBuckets');
    Route::post('dacast_buckets', 'VideoUploadLinkController@getDacastBuckets');
    Route::post('wistia_buckets', 'VideoUploadLinkController@getWistiaBuckets');

    Route::get('aws', 'AwsController@index');


    //--------------------------------------------------------------------------
    Route::post('tvapp_order_playlist', 'TVAppController@tvapp_order_playlist');

    ///REST
    Route::get('tvapp_get_categories', 'TVAppController@tvapp_get_categories');



    // TVWeb

    Route::get('tvweb_playlists', 'TVWebController@tvweb_playlists');
    Route::get('tvweb_add_to_playlist', function(){
        return View::make('tvweb/tvweb_playlists');
    });
    Route::get('tvweb_get_videos_by_playlist_id', 'VideoController@tvweb_get_videos_by_playlist_id');
    Route::get('tvweb_get_videos_for_playlists', 'VideoController@tvweb_get_videos_for_playlists');

    Route::get('tvweb', 'TVWebController@index');
    Route::get('tvweb_playlists', 'TVWebController@tvweb_playlists');
    Route::get('tvweb_settings', 'TVWebController@tvweb_settings');

    Route::post('tvweb_live_update', 'TVWebController@tvweb_live_update');
    Route::post('tvweb_about_us_update', 'TVWebController@tvweb_about_us_update');

    Route::post('tvweb_add_to_playlist', 'TVWebController@tvweb_add_to_playlist');
    Route::post('tvweb_delete_playlist', 'TVWebController@tvweb_delete_playlist');
    Route::post('tvweb_edit_playlist', 'TVWebController@tvweb_edit_playlist');
    Route::post('tvweb_get_playlist_by_id', 'TVWebController@tvweb_get_playlist_by_id');

    Route::post('tvweb_insert_video_in_playlist', 'TVWebController@tvweb_insert_video_in_playlist');

    //Route::post('tvweb_update_files', 'TVWebController@tvweb_update_files');

    Route::post('tvweb_order_playlist', 'TVWebController@tvweb_order_playlist');

    ///REST
    //Route::get('tvweb_get_categories', 'TVWebController@tvweb_get_categories');
    Route::post('tvweb_get_categories', 'TVWebController@tvweb_get_categories');



	Route::get('/home', 'HomeController@home');
    // Video
    Route::get('/videos', 'VideoController@get_videos');
    Route::get('get_video_description', 'VideoController@get_video_description');
    Route::get('get_videos_by_collections', 'VideoController@get_videos_by_collections');

    Route::post('delete_video', 'VideoController@delete_videos');
    Route::post('get_video_by_id', 'VideoController@get_video_by_id');
    Route::post('edit_video', 'VideoController@edit_video');
	Route::post('get_video_by_id_for_tvapp', 'VideoController@get_video_by_id_for_tvapp');

    Route::post('imgx_purge_image', 'VideoController@imgx_purge_image');

    // Schedule
    Route::get('schedule1', 'ScheduleController@index');
    Route::post('scheduleAdd1', 'ScheduleController@add');
    Route::post('scheduleGetEnd1', 'ScheduleController@getEnd');
    Route::post('deleteEvent1', 'ScheduleController@deleteEvent');

    Route::get('schedule2', 'ScheduleController2@index');
    Route::post('scheduleAddVideos', 'ScheduleController2@add_videos');

    Route::get('schedule', 'ManageScheduleController@index');
    Route::get('timeline', 'ManageScheduleController@showTimeline');
    Route::post('newScheduleDate', 'ManageScheduleController@dateChange');
    Route::post('saveSchedule', 'ManageScheduleController@saveSchedule');
    Route::post('editSchedule', 'ManageScheduleController@editSchedule');
    Route::post('editSaveSchedule', 'ManageScheduleController@saveSchedule');
    Route::post('eventDragged', 'ManageScheduleController@eventDragged');
    Route::post('deleteSchedule', 'ManageScheduleController@deleteSchedule');

    // Playout
    Route::get('playout', 'PlayoutController@playout');
    Route::get('get_timeline_data', 'PlayoutController@get_timeline_data');
    Route::post('insert_in_timeline', 'PlayoutController@insert_in_timeline');

    // Collection
    Route::get('collections', 'CollectionsController@index');
    Route::get('get_videos_by_collection_id', 'CollectionsController@get_videos_by_collection_id');
    Route::get('playlists_for_collections', 'CollectionsController@playlists_for_collections');
    Route::get('add_collection_form', function(){
        return View::make('collections/add_collection');
    });
    Route::post('add_to_collection', 'CollectionsController@add_to_collection');
    Route::post('insert_playlist_in_collection', 'CollectionsController@insert_playlist_in_collection');
    Route::post('delete_collection', 'CollectionsController@delete_collection');
    Route::get('edit_collection_get', 'CollectionsController@edit_collection_get');
    Route::post('edit_collection_post', 'CollectionsController@edit_collection_post');

    //--------------------------------------------------------------------------
    //Settings
    Route::get('settings', 'SettingsController@index');
    Route::post('edit_settings', 'SettingsController@edit');
    Route::post('set_stream_url', 'SettingsController@set_stream_url');
    Route::post('upload_logo', 'SettingsController@upload_logo');
    Route::post('upload_focus_sd', 'SettingsController@upload_focus_sd');
    Route::post('upload_focus_hd', 'SettingsController@upload_focus_hd');

    Route::post('upload_splash_sd', 'SettingsController@upload_splash_sd');
    Route::post('upload_splash_hd', 'SettingsController@upload_splash_hd');
    Route::post('upload_overhang_sd', 'SettingsController@upload_overhang_sd');
    Route::post('upload_overhang_hd', 'SettingsController@upload_overhang_hd');
    Route::post('settings_analytics', 'SettingsController@analytics_set_status');
    Route::post('set_new_launchpad_url', 'SettingsController@set_launchpad_url');
    Route::post('set_preRolls', 'SettingsController@set_preRolls');
    
    // Imagery
    Route::get('image_manager', 'ImagesController@index');
    Route::get('upload_image', 'ImagesController@upload_image_get');
    Route::post('save_s3_image','ImagesController@createImageFromS3');
    Route::post('start_transcode_image','ImagesController@start_transcode_image');
    Route::post('pushmediaobject_img','ImagesController@pushmediaobject_img');
    Route::post('image_add_to_table','ImagesController@image_add_to_table');
    Route::post('uploadimagestoawsserver','ImagesController@uploadimagestoawsserver');
    Route::post('istranscodeprocesscomplete_img','ImagesController@istranscodeprocesscomplete_img');
    Route::post('delete_image','ImagesController@delete_image');
    // Image folders
    Route::get('playlists_for_collections', 'CollectionsController@playlists_for_collections');
    Route::get('add_folder_form', function(){
        return View::make('images/add_folder');
    });
    Route::post('add_to_folder', 'ImagesController@add_to_folder');
    Route::post('delete_folder', 'ImagesController@delete_folder');
    Route::get('edit_folder_get', 'ImagesController@edit_folder_get');
    Route::post('edit_folder_post', 'ImagesController@edit_folder_post');
    Route::post('get_image_by_id', 'ImagesController@get_image_by_id');
    Route::get('get_images_for_folders', 'ImagesController@get_images_for_folders');
    Route::post('edit_image', 'ImagesController@edit_image');

    // Slides
    Route::get('add_to_slide', function(){
        return View::make('images/slides');
    });

    Route::post('add_to_slide', 'ImagesController@add_to_slide');
    Route::post('insert_image_in_playlist', 'ImagesController@insert_image_in_playlist');
    Route::post('delete_slide', 'ImagesController@delete_slide');
    Route::post('get_slide_by_id', 'ImagesController@get_slide_by_id');
    Route::post('edit_slide_post', 'ImagesController@edit_slide_post');

    // End Slides

    // Audio manager
    Route::get('audio_manager', 'AudioController@index');
    
    // Distros
    Route::post('display_distro', 'SettingsController@display_distro');
    Route::post('setMobileUrl', 'SettingsController@setMobileUrl');
    Route::post('setAmazonUrl', 'SettingsController@setAmazonUrl');
    Route::post('setAppleUrl', 'SettingsController@setAppleUrl');
    Route::post('setRokuUrl', 'SettingsController@setRokuUrl');
    


    // Reports
    Route::get('reports', 'ReportsController@index');
    Route::post('getStatsByDate', 'ReportsController@getStatsByDate');

    // Imagery
    Route::get('image_manager', 'ImagesController@index');

    // Live monitor
    Route::get('live_monitor', 'SettingsController@live_monitor');
    Route::post('addMonitor', 'SettingsController@addMonitor');
    Route::post('delete_stream', 'SettingsController@delete_stream');
    Route::post('edit_stream', 'SettingsController@edit_stream');

    


    //--------------------------------------------------------------------------
    Route::post('settings_build_roku_channel', 'SettingsAppBuilderController@build_roku_channel');
    Route::post('settings_build_fireTV_channel', 'SettingsAppBuilderController@build_fireTV_channel');

    // Youtube importer Routes
    Route::get('retrieveJsonInfo/{video_id?}', 'YoutubeImporterController@retrieveJsonInfo');
    Route::get('downloadVideo','YoutubeImporterController@download');
    Route::get('getVideoData/{video_id?}','YoutubeImporterController@getVideoData');
    

    // Chargebee
    Route::post('cancel_subscription', 'SettingsController@cancel_subscription');
    Route::get('show_modal/{action}', 'SettingsController@show_modal');
    Route::post('updateAccount', 'SettingsController@updateAccount');
    Route::post('updateBilling', 'SettingsController@updateBilling');
    Route::post('payment_method', 'SettingsController@payment_method');




    //--------------------------------------------------------------------------
    // Start stop stream
    Route::get('start', 'DveoController@start');
    Route::get('stop', 'DveoController@stop');
    Route::get('status', 'DveoController@status');

	// Tag manager
	Route::post('addTag', 'SettingsController@addTag');
	Route::post('getTag', 'SettingsController@getTag');
	Route::post('editTag', 'SettingsController@editTag');
	Route::post('deleteTag', 'SettingsController@deleteTag');

	Route::post('addShow', 'SettingsController@addShow');
	Route::post('getShow', 'SettingsController@getShow');
	Route::post('editShow', 'SettingsController@editShow');
	Route::post('deleteShow', 'SettingsController@deleteShow');

	Route::post('activate_show', 'SettingsController@activate_show');

});

Route::group(array('name' => 'channel', 'prefix' => 'channel_{channel_id}', 'before' => 'detect_channel_id'), function() {
    Route::get('get_thumbnail_notification', 'UploadController@get_thumbnail_notification');
    Route::post('get_from_zencoder', 'UploadController@notification_from_zencoder');

});


Route::group([
    'name' => 'api',
    'prefix' => 'api/channel_{channel_id}',
    'namespace' => '\App\Controllers\Api',
], function () {
    Route::resource('tvapp_playlists', 'TvappPlaylistsController');
    Route::put('tvapp_add_playlists/{title}/{shelf}/{sort_order}/{video_id}/add_playlist', 'TvappPlaylistsController@add_playlist');

    Route::get('getContentDetails/{contentId}/{mediaType}','AssetController@show');

	Route::delete('tvapp_delete_playlists/{id}/{sort_order}/{parent_id}/drop_playlist', 'TvappPlaylistsController@delete_playlist');
	Route::delete('tvapp_delete_playlist_api/{id}/drop_playlist', 'TvappPlaylistsController@delete_one_playlist');

	Route::put('tvapp_playlists/{id}/videos/{video_id}', 'TvappPlaylistsController@addVideo');
	Route::delete('tvapp_playlists/{id}/videos/{video_id}', 'TvappPlaylistsController@deleteVideo');

	Route::resource('tvapp_platforms', 'TvappPlatformsController');
	Route::resource('videos', 'VideosController');
	Route::resource('collections', 'CollectionsController');
	Route::resource('live_info', 'LiveInfoController');
});

Route::get('postproc', 'UploadController@get_post_proc');
Route::post('postproc', 'UploadController@post_proc');

// Invite
Route::get('invite/{token}', 'AuthController@invite');
Route::post('invite/{token}', 'AuthController@register');

// Admin
Route::group(array('before' => 'auth'), function() {

    Route::get('admin', 'AdminController@index');
    Route::get('channels', 'AdminController@channels');
    Route::get('users', 'AdminController@users');
    Route::get('companies', 'AdminController@companies');
    Route::get('dveos', 'AdminController@dveos');

    // Channels action
    Route::get('editChannel', 'AdminController@editChannelGet');
    Route::post('editChannel', 'AdminController@editChannelPost');
    Route::get('addChannel', 'AdminController@addChannelGet');
    Route::post('addChannel', 'AdminController@addChannelPost');
    Route::get('deleteChannel', 'AdminController@deleteChannel');

    // Users action
    Route::get('editUser', 'AdminController@editUserGet');
    Route::post('editUser', 'AdminController@editUserPost');
    Route::post('addUser', 'AdminController@addUserPost');
    Route::get('deleteUser', 'AdminController@deleteUser');
    Route::post('restore', 'AdminController@restore');

    // Companies action
    Route::get('editCompany', 'AdminController@editCompanyGet');
    Route::post('editCompany', 'AdminController@editCompanyPost');
    Route::post('addCompany', 'AdminController@addCompanyPost');
    Route::get('deleteCompany', 'AdminController@deleteCompany');

    // Get channels for companies
    Route::get('getChannelsForCompanies', 'AdminController@getChannelsForCompanies');

    // Get streams for dveos
    Route::get('GetStreamsForDveos', 'AdminController@GetStreamsForDveos');

    // Dveos action
    Route::get('editDveo', 'AdminController@editDveoGet');
    Route::post('editDveo', 'AdminController@editDveoPost');
    Route::post('addDveo', 'AdminController@addDveoPost');
    Route::get('deleteDveo', 'AdminController@deleteDveo');

    // Reload page
    Route::get('reload', array(
        'as' => 'reload',
        'uses' => 'AdminController@reload'
    ));

    //Channels
    Route::get('dashboard', array(
        'as' => 'channels',
        'uses' => 'ChannelsController@channels'
    ));

    Route::get('dashboard_', array('as' => 'channels_', 'uses' => 'ChannelsController@dashboard') );

});

// For Home Page, detect the page to redirect to
Route::get('/', 'HomeController@detect_page');

Route::get('login', 'AuthController@showLogin');
Route::post('login', 'AuthController@postLogin');
Route::get('logout', array(
    'as' => 'logout',
    'uses' => 'AuthController@getLogout'
));

Route::get('go', 'AuthController@showSignup');
Route::post('signup_user', 'AuthController@postSignup');



Route::post('rest_login', 'AuthController@rest_login');

Route::get('404', function() {
    return View::make('errors.404');
});

// Roku
Route::get('RokuIsRegistered', 'RokuController@IsRegistered');
Route::get('RokuGetActivationCode', 'RokuController@GetActivationCode');
Route::get('RokuRegisterDevice', 'RokuController@RegisterDevice');
Route::get('RokuGetData', 'RokuController@GetData');

Route::get('track_start', 'AnalyticsController@playbackStart');
Route::get('track_end', 'AnalyticsController@playbackEnd');

Route::get('secret', 'HomeController@showSecret');

///TVAPP REST
Route::post('tvapp_get_categories', 'TVAppController@tvapp_get_categories');

///TVAPP REST FOR VIDEO WEBSITE
Route::post('tvapp_get_section', 'TVAppController@tvapp_get_section'); //not decided to implement yet...
Route::post('tvapp_get_playlists', 'TVAppController@tvapp_get_playlists');
Route::post('tvapp_get_videos', 'TVAppController@tvapp_get_videos');
Route::post('tvapp_get_playlists_with_videos', 'TVAppController@tvapp_get_playlists_with_videos');
Route::post('tvapp_get_playlists_with_videos2', 'TVAppController@tvapp_get_playlists_with_videos2');
Route::post('tvapp_get_channel_live_url', 'TVAppController@tvapp_get_channel_live_url');

///TVWEB REST

Route::post('tvweb_get_section', 'TVWebController@tvweb_get_section');
Route::post('tvweb_get_playlists', 'TVWebController@tvweb_get_playlists');
Route::post('tvweb_get_videos', 'TVWebController@tvweb_get_videos');
//Route::post('tvweb_get_section_updated_at', 'TVWebController@tvweb_get_section_updated_at');

///////////////////////////////////////////////////


Route::get('playlist', 'HomeController@playlist');
Route::get('getTimelineData', 'HomeController@getTimelineData');
Route::get('removeVideo', 'HomeController@removeVideo');
Route::get('removePlaylist', 'HomeController@removePlaylist');
Route::get('getVideo', 'HomeController@getVideo');
Route::get('getVideos', 'HomeController@getVideos');
Route::get('getPlaylist', 'HomeController@getPlaylist');
Route::get('getPlaylists', 'HomeController@getPlaylists');
Route::get('getChannel', 'HomeController@getChannel');
Route::get('getChannels', 'HomeController@getChannels');
Route::get('change_playlists_order', 'HomeController@change_playlists_order');
Route::get('add_to_timeline', 'HomeController@add_to_timeline');
Route::get('change_timeline_order', 'HomeController@change_timeline_order');
Route::get('get_timeline_by_id', 'HomeController@get_timeline_by_id ');

Route::post('addVideo', 'UploadController@add_videos');
Route::post('addLogo', 'UploadController@add_logos');
//Route::post('add_videos', 'UploadController@add_videos');
//    Route::get('login', 'AuthController@showLogin');
//Route::get('add_video', 'UploadController@addVideo');
Route::get('stepstolaunch-me', 'PagesController@stepstolaunch');
Route::get('calorosomedia', 'PagesController@calorosomedia');
Route::get('appletv', 'PagesController@appletv');
Route::get('categories', 'PagesController@categories');
Route::get('arloopa', 'PagesController@arloopa');
Route::get('grow', 'PagesController@grow');
