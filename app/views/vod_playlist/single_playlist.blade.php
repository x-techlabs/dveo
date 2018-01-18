@extends('template.template')

@section('content')
	@if(isset($playlist))
		<div class="row center-block height" id="contnet-wrap">
			<div class="col-md-12 list-wrap height-inherit" id="playlist_wrapper">
				<div class="height-inherit playlists content" id = "playlist_content">
					<div class="title-name">
						<i class="fa fa-play-circle"></i>
						<div class="title">Playlist</div>
						<div class="input-group searchPlay">
							<input type="text" class="form-control" placeholder="Search" id="search-query-3">
							<span class="input-group-btn">
                        		<button type="submit" class="btn"><span class="fui-search"></span></button>
                    		</span>
						</div>
					</div>

					{{-- Playlist --}}
					<div id="playlist_container"></div>

					<!--  Playlists -->
					<ul id="playlists" style="display:none;">
						<li data-source="playlist_videos" data-playlist-name="{{$playlist->title}}" data-thumbnail-path="{{$playlist->thumbnail_name}}">
							<p class="minimalDarkCategoriesTitle"><span class="minimialDarkBold">Title: </span>{{ $playlist->title }}</p>
							<p class="minimalDarkCategoriesType"><span class="minimialDarkBold">Type: </span>VOD</p>
							<p class="minimalDarkCategoriesDescription"><span class="minimialDarkBold">Description: </span>
								{{ $playlist->description }}
							</p>
						</li>
					</ul>
					<!--  HTML playlist -->
					<ul id="playlist_videos" style="display:none;">
						@foreach($videos as $video)
						<li data-thumb-source="{{$video->thumbnail_name}}" data-video-source="[{source:'{{$video->path}}', label:'small version'}, {source:'{{$video->path}}', label:'hd720'},{source:'{{$video->path}}', label:'hd1080'}]" data-start-at-video="2" data-poster-source="{{$video->thumbnail_name}},{{$video->thumbnail_name}}" data-downloadable="no">
							<div data-video-short-description="">
								<div>
									<p class="classicDarkThumbnailTitle">{{$video->title}}</p>
									<p class="minimalDarkThumbnailDesc">
										{{$video->description}}
									</p>
								</div>
							</div>
							<div data-video-long-description="">
								<div>
									<p class="minimalDarkVideoTitleDesc">{{$video->title}}</p>
									<p class="minimalDarkVideoMainDesc">
										{{$video->description}}
									</p>
								</div>
							</div>
						</li>
						@endforeach
					</ul>
				</div>
			</div>
		</div>
	@endif


	<script>
		// load player
		FWDUVPUtils.onReady(function(){

			new FWDUVPlayer({
				//main settings
				instanceName:"fwd_player1",
				parentId:"playlist_container",
				playlistsId:"playlists",
				mainFolderPath:"/player/content",
				skinPath:"classic_skin_dark",
				displayType:"responsive",
				initializeOnlyWhenVisible:"no",
				fillEntireVideoScreen:"no",
				privateVideoPassword:"428c841430ea18a70f7b06525d4b748a",
				useHEXColorsForSkin:"no",
				normalHEXButtonsColor:"#FF0000",
				selectedHEXButtonsColor:"#000000",
				useDeepLinking:"yes",
				showPreloader:"yes",
				rightClickContextMenu:"developer",
				addKeyboardSupport:"yes",
				autoScale:"yes",
				showButtonsToolTip:"yes",
				stopVideoWhenPlayComplete:"no",
				autoPlay:"no",
				loop:"no",
				shuffle:"no",
				showErrorInfo:"yes",
				maxWidth:980,
				maxHeight:552,
				volume:.8,
				buttonsToolTipHideDelay:1.5,
				backgroundColor:"#000000",
				videoBackgroundColor:"#000000",
				posterBackgroundColor:"#000000",
				buttonsToolTipFontColor:"#5a5a5a",
				//logo settings
				showLogo:"no",
				hideLogoWithController:"yes",
				logoPosition:"topRight",
				logoLink:"#",
				logoMargins:5,
				//playlists/categories settings
				usePlaylistsSelectBox:"yes",
				showPlaylistsButtonAndPlaylists:"yes",
				showPlaylistsByDefault:"no",
				thumbnailSelectedType:"opacity",
				startAtPlaylist:0,
				buttonsMargins:0,
				thumbnailMaxWidth:350,
				thumbnailMaxHeight:350,
				horizontalSpaceBetweenThumbnails:40,
				verticalSpaceBetweenThumbnails:40,
				//playlist settings
				showPlaylistButtonAndPlaylist:"yes",
				playlistPosition:"right",
				showPlaylistByDefault:"yes",
				showPlaylistName:"yes",
				showSearchInput:"yes",
				showLoopButton:"yes",
				showShuffleButton:"yes",
				showNextAndPrevButtons:"yes",
				showThumbnail:"yes",
				forceDisableDownloadButtonForFolder:"yes",
				addMouseWheelSupport:"yes",
				startAtRandomVideo:"no",
				folderVideoLabel:"VIDEO ",
				playlistRightWidth:310,
				playlistBottomHeight:599,
				startAtVideo:0,
				maxPlaylistItems:50,
				thumbnailWidth:70,
				thumbnailHeight:70,
				spaceBetweenControllerAndPlaylist:2,
				spaceBetweenThumbnails:2,
				scrollbarOffestWidth:10,
				scollbarSpeedSensitivity:.5,
				playlistBackgroundColor:"#000000",
				playlistNameColor:"#FFFFFF",
				thumbnailNormalBackgroundColor:"#1b1b1b",
				thumbnailHoverBackgroundColor:"#313131",
				thumbnailDisabledBackgroundColor:"#272727",
				searchInputBackgroundColor:"#000000",
				searchInputColor:"#bdbdbd",
				youtubeAndFolderVideoTitleColor:"#FFFFFF",
				folderAudioSecondTitleColor:"#999999",
				youtubeOwnerColor:"#bdbdbd",
				youtubeDescriptionColor:"#bdbdbd",
				mainSelectorBackgroundSelectedColor:"#FFFFFF",
				mainSelectorTextNormalColor:"#FFFFFF",
				mainSelectorTextSelectedColor:"#000000",
				mainButtonBackgroundNormalColor:"#212021",
				mainButtonBackgroundSelectedColor:"#FFFFFF",
				mainButtonTextNormalColor:"#FFFFFF",
				mainButtonTextSelectedColor:"#000000",
				//controller settings
				showController:"yes",
				showControllerWhenVideoIsStopped:"yes",
				showNextAndPrevButtonsInController:"no",
				showPlaybackRateButton:"yes",
				showVolumeButton:"yes",
				showTime:"yes",
				showQualityButton:"yes",
				showInfoButton:"yes",
				showDownloadButton:"yes",
				showShareButton:"no",
				showEmbedButton:"yes",
				showFullScreenButton:"yes",
				disableVideoScrubber:"no",
				repeatBackground:"no",
				controllerHeight:37,
				controllerHideDelay:3,
				startSpaceBetweenButtons:10,
				spaceBetweenButtons:10,
				scrubbersOffsetWidth:2,
				mainScrubberOffestTop:16,
				timeOffsetLeftWidth:2,
				timeOffsetRightWidth:3,
				timeOffsetTop:0,
				volumeScrubberHeight:80,
				volumeScrubberOfsetHeight:12,
				timeColor:"#bdbdbd",
				youtubeQualityButtonNormalColor:"#bdbdbd",
				youtubeQualityButtonSelectedColor:"#FFFFFF",
				//advertisement on pause window
				aopwTitle:"Advertisement",
				aopwWidth:400,
				aopwHeight:240,
				aopwBorderSize:6,
				aopwTitleColor:"#FFFFFF",
				//subtitle
				subtitlesOffLabel:"Subtitle off",
				//popup add windows
				showPopupAdsCloseButton:"yes",
				//embed window and info window
				embedAndInfoWindowCloseButtonMargins:0,
				borderColor:"#333333",
				mainLabelsColor:"#FFFFFF",
				secondaryLabelsColor:"#bdbdbd",
				shareAndEmbedTextColor:"#5a5a5a",
				inputBackgroundColor:"#000000",
				inputColor:"#FFFFFF",
				//loggin
				isLoggedIn:"yes",
				playVideoOnlyWhenLoggedIn:"yes",
				loggedInMessage:"Please login to view this video.",
				//audio visualizer
				audioVisualizerLinesColor:"#0099FF",
				audioVisualizerCircleColor:"#FFFFFF",
				//playback rate / speed
				defaultPlaybackRate:1, //0.25, 0.5, 1, 1.25, 1.2, 2
				//cuepoints
				executeCuepointsOnlyOnce:"no",
				//annotations
				showAnnotationsPositionTool:"no",
				//ads
				openNewPageAtTheEndOfTheAds:"no",
				adsButtonsPosition:"left",
				skipToVideoText:"You can skip to video in: ",
				skipToVideoButtonText:"Skip Ad",
				adsTextNormalColor:"#bdbdbd",
				adsTextSelectedColor:"#FFFFFF",
				adsBorderNormalColor:"#444444",
				adsBorderSelectedColor:"#FFFFFF"
			});
		});
	</script>

@endsection