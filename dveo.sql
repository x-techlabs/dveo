-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 18, 2018 at 05:48 PM
-- Server version: 5.5.58-0ubuntu0.14.04.1
-- PHP Version: 5.6.33-1+ubuntu14.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dveo`
--

-- --------------------------------------------------------

--
-- Table structure for table `channel`
--

CREATE TABLE IF NOT EXISTS `channel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `stream` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `stream_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `format` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `timezone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `company_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dveo_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `storage` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `source` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1studio',
  `analytics` text COLLATE utf8_unicode_ci NOT NULL,
  `channel_type` text COLLATE utf8_unicode_ci NOT NULL,
  `launchpad_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `prerolls` int(11) NOT NULL DEFAULT '0',
  `mobileWebUrl` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'http://onestudio.tv',
  `apple_tv_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'https://www.apple.com/tv/',
  `amazon_fire_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'https://www.amazon.com/Fire-TV-Apps-All-Models/b?ie=UTF8&node=10208590011',
  `roku_tv_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'https://channelstore.roku.com/browse',
  `display_roku` int(11) NOT NULL DEFAULT '1',
  `display_appletv` int(11) NOT NULL DEFAULT '1',
  `display_firetv` int(11) NOT NULL DEFAULT '1',
  `display_mobileweb` int(11) NOT NULL DEFAULT '1',
  `streamlyzer_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `logo_ext` text COLLATE utf8_unicode_ci NOT NULL,
  `tracking_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `playout_access` int(11) NOT NULL,
  `display_show` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `channel`
--

INSERT INTO `channel` (`id`, `title`, `stream`, `stream_url`, `format`, `timezone`, `type`, `created_at`, `updated_at`, `company_id`, `dveo_id`, `storage`, `source`, `analytics`, `channel_type`, `launchpad_url`, `prerolls`, `mobileWebUrl`, `apple_tv_url`, `amazon_fire_url`, `roku_tv_url`, `display_roku`, `display_appletv`, `display_firetv`, `display_mobileweb`, `streamlyzer_token`, `logo_ext`, `tracking_id`, `playout_access`, `display_show`) VALUES
(2, 'Test channel', '', '', 'sd', 'US/Hawaii', 0, '2018-01-18 09:32:16', '2018-01-18 09:35:06', '1', '', '', '1studio', '', '', NULL, 0, 'http://onestudio.tv', 'https://www.apple.com/tv/', 'https://www.amazon.com/Fire-TV-Apps-All-Models/b?ie=UTF8&node=10208590011', 'https://channelstore.roku.com/browse', 1, 1, 1, 1, '', '', '', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `channel_images`
--

CREATE TABLE IF NOT EXISTS `channel_images` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `channel_id` int(10) DEFAULT NULL,
  `focus_hd` varchar(255) DEFAULT NULL,
  `focus_sd` varchar(255) DEFAULT NULL,
  `splash_hd` varchar(255) DEFAULT NULL,
  `splash_sd` varchar(255) DEFAULT NULL,
  `sides_hd` varchar(255) DEFAULT NULL,
  `sides_sd` varchar(255) DEFAULT NULL,
  `overhang_hd` varchar(255) DEFAULT NULL,
  `overhang_sd` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `channel_id` (`channel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `channel_shows`
--

CREATE TABLE IF NOT EXISTS `channel_shows` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_id` int(11) NOT NULL,
  `name` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `channel_id` (`channel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `channel_tags`
--

CREATE TABLE IF NOT EXISTS `channel_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_id` int(11) NOT NULL,
  `name` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `channel_id` (`channel_id`),
  KEY `channel_id_2` (`channel_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `collections`
--

CREATE TABLE IF NOT EXISTS `collections` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `channel_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `viewing` varchar(10) CHARACTER SET utf8 NOT NULL DEFAULT 'inherit',
  `pre_roll` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE IF NOT EXISTS `companies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=75 ;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `type`, `created_at`, `updated_at`) VALUES
(1, 'X-TECH', 0, '2014-12-11 10:25:52', '2014-12-11 10:25:52');

-- --------------------------------------------------------

--
-- Table structure for table `dveo`
--

CREATE TABLE IF NOT EXISTS `dveo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8_unicode_ci NOT NULL,
  `queue` text COLLATE utf8_unicode_ci NOT NULL,
  `payload` text COLLATE utf8_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `help`
--

CREATE TABLE IF NOT EXISTS `help` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section` varchar(50) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `display_order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `help`
--

INSERT INTO `help` (`id`, `section`, `title`, `description`, `display_order`) VALUES
(1, 'tvapp_playlists', 'How to create playlist', '<table width=''99%''><tr><td> \r\n1.Click on ''New Playlist'' button.<img src=''HOMEURL/images/help/new_playlist_button.png'' style=''width:100px;''><br> \r\n2.Playlist data entry box will open<br> \r\n3.Fill in all information<br> \r\n4.Make sure that layout dropdown is set to collection (linear / grid)<br> \r\n5.Level dropdown has five levels.<br> \r\n&nbsp;&nbsp;&nbsp;&nbsp;Level 0 (top level) playlists can be added under Root <br> \r\n&nbsp;&nbsp;&nbsp;&nbsp;Level 1 playlists can be added under Level 0 playlists only<br> \r\n&nbsp;&nbsp;&nbsp;&nbsp;Level 2 playlists can be added under Level 1 playlists only<br> \r\n&nbsp;&nbsp;&nbsp;&nbsp;Level 3 playlists can be added under Level 2 playlists only<br> \r\n&nbsp;&nbsp;&nbsp;&nbsp;Level 4 playlists can be added under Level 3 playlists only<br> \r\n</td><td style=''width:210px;''><img src=''HOMEURL/images/help/playlist_screen.png'' style=''width:200px;''></td> \r\n</tr></table> \r\n', 1),
(2, 'tvapp_playlists', 'How to create Live Streaming playlist', '<table width=''99%''><tr><td> \r\n1.Click on ''New Playlist'' button.<img src=''HOMEURL/images/help/new_playlist_button.png'' style=''width:100px;''><br> \r\n2.Playlist data entry box will open<br> \r\n3.Fill in all information<br> \r\n4.Make sure that layout dropdown is set to Live Stream Link<br> \r\n5.Type actual streaming url in the box below description<br> \r\n6.Level dropdown has five levels.<br> \r\n&nbsp;&nbsp;&nbsp;&nbsp;Level 0 (top level) playlists can be added under Root <br> \r\n&nbsp;&nbsp;&nbsp;&nbsp;Level 1 playlists can be added under Level 0 playlists only<br> \r\n&nbsp;&nbsp;&nbsp;&nbsp;Level 2 playlists can be added under Level 1 playlists only<br> \r\n&nbsp;&nbsp;&nbsp;&nbsp;Level 3 playlists can be added under Level 2 playlists only<br> \r\n&nbsp;&nbsp;&nbsp;&nbsp;Level 4 playlists can be added under Level 3 playlists only<br> \r\n</td><td style=''width:210px;''><img src=''HOMEURL/images/help/playlist_screen_live_streaming.png'' style=''width:200px;''></td> \r\n</tr></table> \r\n', 2),
(3, 'tvapp_playlists', 'How to create Information page (text Page)', '<table width=''99%''><tr><td> \r\n1.Click on ''New Playlist'' button.<img src=''HOMEURL/images/help/new_playlist_button.png'' style=''width:100px;''><br> \r\n2.Playlist data entry box will open<br> \r\n3.Fill in all information<br> \r\n4.Make sure that layout dropdown is set to Text Page<br> \r\n&nbsp;&nbsp;&nbsp;&nbsp;Text in description box will be shown as Text in ROKU.<br> \r\n&nbsp;&nbsp;&nbsp;&nbsp;ROKU does not support HTML. Please enter plain text.<br> \r\n5.Level dropdown has five levels.<br> \r\n&nbsp;&nbsp;&nbsp;&nbsp;Level 0 (top level) playlists can be added under Root <br> \r\n&nbsp;&nbsp;&nbsp;&nbsp;Level 1 playlists can be added under Level 0 playlists only<br> \r\n&nbsp;&nbsp;&nbsp;&nbsp;Level 2 playlists can be added under Level 1 playlists only<br> \r\n&nbsp;&nbsp;&nbsp;&nbsp;Level 3 playlists can be added under Level 2 playlists only<br> \r\n&nbsp;&nbsp;&nbsp;&nbsp;Level 4 playlists can be added under Level 3 playlists only<br> \r\n</td><td style=''width:210px;''><img src=''HOMEURL/images/help/playlist_screen.png'' style=''width:200px;''></td> \r\n</tr></table> \r\n', 3),
(4, 'tvapp_playlists', 'How to add playlist / videos in TREE structure', '<table width=''99%''><tr><td> \r\n1.Click on Tree Icon to swich to TREE View. <img src=''HOMEURL/images/tree.png'' style=''width:40px;''><br> \r\n2.Click on the playlist under which you want to add videos / playlists<br> \r\n3.Video / Playlist selection box will open on right side<br> \r\n4.To add playlists, select playlist from dropdown in the selection box<br> \r\n&nbsp;&nbsp;&nbsp;&nbsp;selection box will show playlists who are one level lower than selected playlist.<br> \r\n&nbsp;&nbsp;&nbsp;&nbsp;To add only one playlist, click on [+] next to playlist.<br> \r\n&nbsp;&nbsp;&nbsp;&nbsp;To add multiple playlists, check playlists and click on the Add button at the end of selection box.<br> \r\n5.To add videos, select videos from dropdown in the selection box<br> \r\n6.Rest everything is same as playlist addition.<br> \r\n</td><td style=''width:210px;''><img src=''HOMEURL/images/help/playlist_video_selection.png'' style=''width:200px;''></td> \r\n</tr></table> \r\n', 4),
(5, 'tvapp_playlists', 'How to reference a UStream channel\r\n', '1.login to ustream account.<br> \r\n2.select ustream channel that you want to reference.<br>\r\n3.Find out its id (which is a number) from the address bar at top.<br>\r\n4.Create new playlist in 1studio and set its name and description as that number.<br>\r\n5.switch to tree structure and add that playlist in tree.<br>\r\n6.Select that playlist and click on "Generate Feed" button.<img src=''HOMEURL/images/feed.png'' width=''24''><br>&nbsp;&nbsp;It takes some time (around 10-15 seconds).<br>\r\n7.Goto settings, make sure source is selected as UStream and not onestudio.<br>\r\n8.Click on Build settings Button at bottom end.<br>\r\n9.Start ROKU, you should get new Ustream channel in ROKU.<br>', 5);

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `playlist_id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `thumbnail_name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `image_format` varchar(255) NOT NULL,
  `encode_status` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `hd_width` int(11) NOT NULL,
  `hd_height` int(11) NOT NULL,
  `hd_file_size` int(11) NOT NULL,
  `hd_mime_type` varchar(255) NOT NULL,
  `sd_file_name` varchar(255) NOT NULL,
  `sd_width` int(11) NOT NULL,
  `sd_height` int(11) NOT NULL,
  `sd_file_size` int(11) NOT NULL,
  `sd_mime_type` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` date NOT NULL,
  `storage` varchar(255) NOT NULL,
  `source` varchar(255) NOT NULL,
  `thumbnail_source` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `image_folders`
--

CREATE TABLE IF NOT EXISTS `image_folders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` int(11) NOT NULL,
  `viewing` varchar(255) NOT NULL,
  `pre_roll` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `channel_id` (`channel_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `image_in_folders`
--

CREATE TABLE IF NOT EXISTS `image_in_folders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image_id` int(11) NOT NULL,
  `folder_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `image_id` (`image_id`),
  KEY `folder_id` (`folder_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- Table structure for table `image_in_slide`
--

CREATE TABLE IF NOT EXISTS `image_in_slide` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image_id` int(11) NOT NULL,
  `slide_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `image_id` (`image_id`),
  KEY `slide_id` (`slide_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `image_slide`
--

CREATE TABLE IF NOT EXISTS `image_slide` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `thumbnail_name` varchar(255) NOT NULL,
  `type` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL,
  `mrss_url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `live_info`
--

CREATE TABLE IF NOT EXISTS `live_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `channel_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `live_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `details` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `live_monitors`
--

CREATE TABLE IF NOT EXISTS `live_monitors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `stream_url` varchar(255) NOT NULL,
  `title` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2014_12_06_120519_create_channels', 1),
('2014_12_06_121719_create_companies', 1),
('2014_12_06_124725_create_playlists', 2),
('2014_12_06_125359_create_playlists_in_timeline', 2),
('2014_12_06_125628_create_schedule', 2),
('2014_12_06_125914_create_users', 3),
('2014_12_06_130131_create_users_in_channels', 3),
('2014_12_06_130438_create_videos', 4),
('2014_12_06_131459_create_videos_in_collections', 4),
('2014_12_06_131626_create_videos_in_playlists', 4),
('2014_12_11_143227_change_channel_column_name', 5),
('2014_12_11_160616_change_column_name_in_channel', 6),
('2014_12_13_133840_create_dveo', 7),
('2014_12_15_125621_add_dveo_id_column_in_channels', 8),
('2015_01_14_141518_create_collections', 9),
('2015_01_30_125749_add_storage_column_in_videos', 10),
('2015_01_30_135835_add_storage_column_in_channels', 10),
('2015_06_19_182014_add_stream_url_to_channel_table', 11),
('2015_06_29_222458_add_start_time_to_video_table', 11),
('2015_07_01_115937_create_schedule_video_table', 11),
('2015_08_19_060542_reshape_schedule_video_schema', 12),
('2016_04_21_162757_create_tvapps', 12),
('2016_04_21_162810_create_tvapp_playlist', 12),
('2016_04_21_233303_create_tvapp_videos_in_playlist', 12),
('2016_04_21_162757_create_tvwebs', 13),
('2016_04_21_162810_create_tvweb_playlist', 13),
('2016_04_21_233303_create_tvweb_videos_in_playlist', 13),
('2016_08_14_044634_create_live_info_table', 14),
('2016_08_14_044634_create_live_infos_table', 15),
('2016_09_20_083410_add_level_to_tvapp_playlist_table', 16),
('2016_09_23_063111_add_sort_order_to_tvapp_video_in_playlist', 16),
('2016_09_27_031224_add_layout_url_to_tvapp_playlist', 16),
('2017_02_22_095710_add_playlist_parent_id', 17),
('2017_03_12_074039_create_failed_jobs_table', 17),
('2017_04_18_182221_create_tvapp_platforms', 18);

-- --------------------------------------------------------

--
-- Table structure for table `playlist`
--

CREATE TABLE IF NOT EXISTS `playlist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `channel_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `thumbnail_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `duration` int(11) NOT NULL,
  `master_looped` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `stream_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `playlists_in_timeline`
--

CREATE TABLE IF NOT EXISTS `playlists_in_timeline` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `playlist_id` int(11) NOT NULL,
  `start` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE IF NOT EXISTS `schedule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `playlist_id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `type` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `schedule_video`
--

CREATE TABLE IF NOT EXISTS `schedule_video` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `channel_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `genere` text COLLATE utf8_unicode_ci NOT NULL,
  `url` text COLLATE utf8_unicode_ci NOT NULL,
  `video_id_list` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `track_usage`
--

CREATE TABLE IF NOT EXISTS `track_usage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `content_url` text NOT NULL,
  `content_type` int(11) NOT NULL,
  `device_id` varchar(50) NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `start_date` int(11) NOT NULL COMMENT 'start_date is in yyyymmdd format',
  `start_time` int(11) NOT NULL COMMENT 'no of seconds since beginning of start_date',
  `duration` int(11) NOT NULL,
  `secret_code` varchar(40) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tvapps`
--

CREATE TABLE IF NOT EXISTS `tvapps` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `channel_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `live_stream_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `about_us` text COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tvapp_platforms`
--

CREATE TABLE IF NOT EXISTS `tvapp_platforms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tvapp_platforms`
--

INSERT INTO `tvapp_platforms` (`id`, `slug`, `title`) VALUES
(1, 'web', 'Mobile/Web TV'),
(2, 'roku', 'Roku'),
(3, 'firetv', 'Fire TV'),
(4, 'appletv', 'Apple TV');

-- --------------------------------------------------------

--
-- Table structure for table `tvapp_playlist`
--

CREATE TABLE IF NOT EXISTS `tvapp_playlist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `channel_id` int(11) NOT NULL,
  `tvapp_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `thumbnail_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `duration` int(11) NOT NULL,
  `master_looped` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `level` int(11) NOT NULL DEFAULT '0',
  `sort_order` int(11) NOT NULL DEFAULT '1',
  `parent_id` int(10) unsigned DEFAULT NULL,
  `layout` int(11) NOT NULL DEFAULT '0',
  `web_layout` int(11) NOT NULL DEFAULT '0',
  `stream_url` text COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `viewing` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'inherit',
  `shelf` int(11) NOT NULL DEFAULT '0',
  `video_is` int(1) NOT NULL DEFAULT '0',
  `playlist_category` int(11) DEFAULT '0',
  `featured_image_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mobileweb_image_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `playlist_type` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1540 ;

--
-- Dumping data for table `tvapp_playlist`
--

INSERT INTO `tvapp_playlist` (`id`, `channel_id`, `tvapp_id`, `title`, `description`, `thumbnail_name`, `duration`, `master_looped`, `type`, `level`, `sort_order`, `parent_id`, `layout`, `web_layout`, `stream_url`, `status`, `created_at`, `updated_at`, `viewing`, `shelf`, `video_is`, `playlist_category`, `featured_image_url`, `mobileweb_image_url`, `playlist_type`) VALUES
(2, 35, 0, 'ALONE IN THE DARK', 'No Crew, No Silly Gadgets, So Sound Effects or Scary Music.. Just One man and a Camera in some of the nation''s most "Haunted" Locations.', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_2', 27187, 8, 0, 1, 25, NULL, 0, 0, '', '', '2016-05-31 23:47:15', '2018-01-08 22:47:46', 'inherit', 0, 0, 0, '', NULL, NULL),
(4, 35, 0, 'BEST DAY EVER', 'Join Gigi on her never ending quest for Food, Fun and Adventure. Travel across the country in search of the Best Day EVER!', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_4.jpg', 15371, 9, 0, 1, 16, NULL, 0, 0, '', '', '2016-05-31 23:50:52', '2018-01-08 22:47:51', 'inherit', 0, 0, 0, '', NULL, NULL),
(5, 35, 0, 'A FRESH TAKE With Chef Stevie', 'Chef Stevie puts together healthy alternatives to usually not so healthy dishes! Check out all of her recipes at ChefStevie.com', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_5', 90, 15, 0, 2, 39, NULL, 0, 0, '', '', '2016-05-31 23:53:53', '2018-01-08 22:47:31', 'inherit', 0, 0, 0, '', NULL, NULL),
(9, 35, 0, 'HELLSCREAM INC.', '(NEW SEASON IN JANUARY!) (3 Seasons avail.) \nGo behind the scenes and behind the SCREAMS of HELLSCREAM & SINISTER Haunted Houses!', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_35_tvapp_playlist_9.jpg', 21683, 2, 0, 1, 2, NULL, 0, 0, '', '', '2016-06-01 00:03:35', '2018-01-03 13:11:40', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_35_tvapp_playlist_9.jpg', NULL, NULL),
(15, 35, 0, 'VANDALA''S EPIC ADVENTURES!', 'Join Vandala''s in one super epic fun or delicious adventure after another! A kids take on food & travel!', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_15', 1500, 5, 0, 2, 30, NULL, 0, 0, '', '', '2016-06-01 00:11:59', '2018-01-08 22:47:46', 'inherit', 0, 0, 0, '', NULL, NULL),
(20, 35, 0, 'SHOO! Adventures in Pest Control', 'Join Jeff Mokol as he rids the cute & creepy animals and insects plaguing homeowners. Have unwanted guests? Just say SHOO!', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_20', 3965, 10, 0, 0, 27, NULL, 0, 0, '', '', '2016-06-01 00:18:00', '2018-01-08 22:47:46', 'inherit', 0, 0, 0, '', NULL, NULL),
(34, 38, 0, 'Maija Playlist', 'Maija Playlist Description', 'http://prolivestream.s3.amazonaws.com/logos/channel_38_tvapp_playlist_34', 7732, 0, 0, 0, 1, NULL, 0, 0, '', '', '2016-06-30 11:55:47', '2016-10-02 11:07:45', 'inherit', 0, 0, 0, '', NULL, NULL),
(70, 34, 0, 'Test Category # 1', 'Test Category # 1 Description', 'https://onestudio.imgix.net/db0a408f3bc1d8a55e5a25f65db1f03b_1.jpg?w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60', 2525, 0, 0, 0, 1, NULL, 0, 0, '', '', '2016-07-26 08:32:49', '2016-07-26 08:32:49', 'inherit', 0, 0, 0, '', NULL, NULL),
(72, 34, 0, 'Test tvapp playlist', 'test description', 'https://onestudio.imgix.net/db0a408f3bc1d8a55e5a25f65db1f03b_1.jpg?w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60', 2573, 0, 0, 0, 1, NULL, 0, 0, '', '', '2016-07-26 11:23:40', '2016-07-26 11:23:40', 'inherit', 0, 0, 0, '', NULL, NULL),
(73, 17, 0, 'Exclusives', '', '', 42, 5, 0, 0, 7, NULL, 0, 0, '', '', '2016-07-29 06:00:16', '2017-11-28 18:54:37', 'inherit', 0, 0, 0, '', NULL, NULL),
(78, 17, 0, 'Classics', '', '', 54, 10, 0, 0, 3, NULL, 0, 0, '', '', '2016-07-31 18:22:11', '2017-11-28 18:54:37', 'inherit', 0, 0, 0, '', NULL, NULL),
(79, 17, 0, 'Adventure', '', '', 36, 7, 0, 0, 2, NULL, 0, 0, '', '', '2016-07-31 18:26:15', '2017-11-28 18:54:37', 'inherit', 0, 0, 0, '', NULL, NULL),
(80, 17, 0, 'Western', '', '', 48, 8, 0, 0, 19, NULL, 0, 0, '', '', '2016-07-31 18:27:26', '2017-11-28 18:54:37', 'inherit', 0, 0, 0, '', NULL, NULL),
(81, 17, 0, 'Thrillers', '', '', 48, 9, 0, 0, 18, NULL, 0, 0, '', '', '2016-07-31 18:30:00', '2017-11-28 18:54:37', 'inherit', 0, 0, 0, '', NULL, NULL),
(82, 17, 0, 'Originals', '', '', 42, 6, 0, 0, 15, NULL, 0, 0, '', '', '2016-07-31 18:31:05', '2017-11-28 18:54:37', 'inherit', 0, 0, 0, '', NULL, NULL),
(83, 17, 0, 'Musicals', '', '', 48, 11, 0, 0, 13, NULL, 0, 0, '', '', '2016-07-31 18:32:43', '2017-11-28 18:54:37', 'inherit', 0, 0, 0, '', NULL, NULL),
(84, 17, 0, 'Comedy', '', '', 120, 13, 0, 0, 6, NULL, 0, 0, '', '', '2016-07-31 18:34:11', '2017-11-28 18:54:37', 'inherit', 0, 0, 0, '', NULL, NULL),
(85, 17, 0, 'Romance', '', '', 54, 12, 0, 0, 16, NULL, 0, 0, '', '', '2016-07-31 18:35:34', '2017-11-28 18:54:37', 'inherit', 0, 0, 0, '', NULL, NULL),
(86, 17, 0, 'Horror', '', '', 96, 14, 0, 0, 10, NULL, 0, 0, '', '', '2016-07-31 18:36:47', '2017-11-28 18:54:37', 'inherit', 0, 0, 0, '', NULL, NULL),
(87, 17, 0, 'Film Noir', '', '', 138, 15, 0, 0, 9, NULL, 0, 0, '', '', '2016-07-31 18:38:01', '2017-11-28 18:54:37', 'inherit', 0, 0, 0, '', NULL, NULL),
(88, 17, 0, 'Sci-FI', '', '', 60, 16, 0, 0, 17, NULL, 0, 0, '', '', '2016-07-31 18:39:17', '2017-11-28 18:54:37', 'inherit', 0, 0, 0, '', NULL, NULL),
(89, 17, 0, 'Drama', '', '', 96, 17, 0, 0, 5, NULL, 0, 0, '', '', '2016-07-31 18:39:55', '2017-11-28 18:54:37', 'inherit', 0, 0, 0, '', NULL, NULL),
(90, 17, 0, 'Action', '', '', 48, 18, 0, 0, 4, NULL, 0, 0, '', '', '2016-07-31 18:40:54', '2017-11-28 18:54:37', 'inherit', 0, 0, 0, '', NULL, NULL),
(104, 42, 0, 'Latest Videos', '', 'https://onestudio.imgix.net/9973505a39428850dc093e3c27f2c59f_1.jpg?w=336&h=210&fit=crop&crop=entropy&auto=format,enhance&q=60', 418, 0, 1, 0, 5, NULL, 0, 0, '', '', '2016-08-06 19:36:05', '2018-01-08 19:41:20', 'inherit', 0, 0, 0, '', NULL, NULL),
(107, 43, 0, 'Featured', 'Featured Descriptions go here', 'http://prolivestream.s3.amazonaws.com/logos/channel_43_tvapp_playlist_107', 6, 0, 2, 0, 1, 261, 0, 0, 'http://techlabs-midas.adsparx.net:8888/mrss/published/e48ce1cd-6fa7-46c4-9bad-ff05b8b72447/839aa570-d076-402a-a3bb-7b5ef2d964c0', '', '2016-08-09 08:18:26', '2017-07-14 16:52:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(115, 46, 0, 'Test', '', 'https://onestudio.imgix.net/27347dc3109d1bb21740a6761169abc0_1.jpg?w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60', 7587, 0, 0, 0, 1, NULL, 0, 0, '', '', '2016-08-12 03:00:10', '2016-08-23 04:10:57', 'inherit', 0, 0, 0, '', NULL, NULL),
(116, 45, 0, 'Internal Medicine', 'Practice Demo', 'https://onestudio.imgix.net/ed8f106895bbd18f83dcabaef0e77932_1.jpg?w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60', 24, 0, 0, 0, 1, NULL, 0, 0, '', '', '2016-08-16 22:41:05', '2016-08-16 22:44:05', 'inherit', 0, 0, 0, '', NULL, NULL),
(120, 37, 0, 'DMTV Eps (EDM-Dance Music Show)', 'Watch the world''s biggest EDM/Dance Music show DMTV', 'https://onestudio.imgix.net/DMTVEp1_1440068430_1.jpg?w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60', 2169, 4, 0, 2, 2, NULL, 0, 0, '', '', '2016-08-28 09:39:18', '2017-10-23 20:29:43', 'inherit', 0, 0, 0, '', NULL, NULL),
(121, 37, 0, 'EDM/Dance Music Videos', 'Watch the best EDM/Dance Music videos!', 'https://onestudio.imgix.net/1e2fac5cc4478d9755e0fa818c6aef3c_1.jpg?w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60', 3719, 6, 0, 2, 22, NULL, 0, 0, '', '', '2016-08-28 09:42:59', '2017-11-02 03:25:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(122, 37, 0, 'Hip Hop Music Videos', 'Watch the best indie hip hop videos!', 'https://onestudio.imgix.net/6a564e150296de871324c68758cc413e_1.jpg?w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60', 8219, 7, 0, 2, 21, NULL, 0, 0, '', '', '2016-08-28 09:53:35', '2017-11-02 03:25:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(127, 37, 0, 'Rock and Pop Music Videos', 'Rock and Pop Music Videos', 'https://onestudio.imgix.net/DrawnbyAlexVonZ_1442569816_1.jpg?w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60', 988, 12, 0, 2, 24, NULL, 0, 0, '', '', '2016-08-29 10:59:35', '2017-11-02 03:25:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(129, 37, 0, 'Freestyle Music (90''s Dance Music)', '', 'https://onestudio.imgix.net/NiceWildDiamondGirllive_1440769326_1.jpg?w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60', 54, 14, 0, 2, 19, NULL, 0, 0, '', '', '2016-08-29 11:06:26', '2017-11-02 03:25:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(130, 37, 0, 'Live Performances', '', 'https://onestudio.imgix.net/MarthasVineyardSummerMadnessfeatBigDaddyKane_1440492929_1.jpg?w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60', 5581, 16, 0, 2, 25, NULL, 0, 0, '', '', '2016-08-29 11:07:52', '2017-11-02 03:25:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(131, 35, 0, 'ECTOVISION PARANORMAL', 'Join the EVP team as they investigate  paranormal activities', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_131', 6753, 6, 0, 1, 44, NULL, 0, 0, '', '', '2016-08-31 02:08:53', '2018-01-08 22:47:31', 'inherit', 1, 0, 0, '', NULL, NULL),
(132, 37, 0, 'TV Shows', 'We Talk Weekly (formerly "Talk The Talk")', 'https://onestudio.imgix.net/170f72df8e2cd7abfee1b1687109f9e3_1.jpg?w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60', 11200, 15, 0, 1, 6, NULL, 0, 0, '', '', '2016-08-31 09:21:29', '2017-11-02 03:25:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(133, 37, 0, 'Gus Fink - Adult Comedy', 'The Miko and Cola Show -S2 Ep2 - Return of Tiko', 'https://onestudio.imgix.net/03428877e9b6495e4cb301c8047dcc96_1.jpg?w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60', 857, 17, 0, 2, 15, NULL, 0, 0, '', '', '2016-08-31 10:09:36', '2017-11-02 03:25:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(134, 48, 0, 'Featured', '', 'https://onestudio.imgix.net/d84bae796775d300eaa324676ab67f54_1.jpg?w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60', 1036, 0, 0, 0, 1, NULL, 0, 0, '', '', '2016-09-01 11:33:55', '2016-09-01 11:33:55', 'inherit', 0, 0, 0, '', NULL, NULL),
(137, 17, 0, 'Featured Videos', '', '', 54, 1, 2, 0, 8, NULL, 0, 0, '', '', '2016-09-11 04:11:46', '2017-11-28 18:54:37', 'inherit', 0, 0, 0, '', NULL, NULL),
(138, 17, 0, 'Latest', '', '', 42, 2, 3, 0, 11, NULL, 0, 0, '', '', '2016-09-11 04:12:41', '2017-11-28 18:54:37', 'inherit', 0, 0, 0, '', NULL, NULL),
(139, 17, 0, 'Most Viewed', '', '', 36, 3, 4, 0, 14, NULL, 0, 0, '', '', '2016-09-11 04:13:27', '2017-11-28 18:54:37', 'inherit', 0, 0, 0, '', NULL, NULL),
(140, 17, 0, 'Most Popular', '', '', 36, 4, 5, 0, 12, NULL, 0, 0, '', '', '2016-09-11 04:14:12', '2017-11-28 18:54:37', 'inherit', 0, 0, 0, '', NULL, NULL),
(143, 49, 0, 'Featured', '', 'https://onestudio.imgix.net/cd8bc328ca8eee71d5c9bc1e771697f2_1.jpg?w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60', 17466, 0, 0, 0, 1, NULL, 0, 0, '', '', '2016-09-22 05:50:11', '2016-09-22 05:50:11', 'inherit', 0, 0, 0, '', NULL, NULL),
(144, 39, 0, 'Featured', '', 'https://onestudio.imgix.net/b7bc84e7394e8982869438efd6ead9a6_1.jpg?w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60', 46, 3, 2, 0, 4, NULL, 0, 0, '', '', '2016-09-23 06:16:06', '2017-11-28 18:58:12', 'inherit', 0, 0, 0, '', NULL, NULL),
(146, 39, 0, 'Latest', '', 'https://onestudio.imgix.net/a00c16b15c6e8076e984bc0c430432d0_1.jpg?w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60', 94, 2, 3, 0, 9, NULL, 0, 0, '', '', '2016-09-23 07:21:43', '2017-11-28 18:58:12', 'inherit', 0, 0, 0, '', NULL, NULL),
(147, 39, 0, 'Most Viewed', '', 'https://onestudio.imgix.net/a00c16b15c6e8076e984bc0c430432d0_1.jpg?w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60', 66, 4, 4, 0, 10, NULL, 0, 0, '', '', '2016-09-23 07:22:12', '2017-11-28 18:58:12', 'inherit', 0, 0, 0, '', NULL, NULL),
(148, 39, 0, 'Hero Top Page Videos', '', 'https://onestudio.imgix.net/02e4861356946188775cfb24481d4e93_1.jpg?w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60', 37, 1, 1, 0, 6, NULL, 0, 0, '', '', '2016-09-23 07:23:54', '2017-11-28 18:58:12', 'inherit', 0, 0, 0, '', NULL, NULL),
(151, 39, 0, 'A.R.T. Workouts', 'WholyFit original Cardio with weight class, low impact, to music.  WholyFit  G.B.P. stretching at the end. Appropriate for anyone. Various levels offered.', 'http://prolivestream.s3.amazonaws.com/logos/channel_39_tvapp_playlist_151', 75, 7, 0, 0, 1, NULL, 0, 0, '', '', '2016-09-25 19:47:57', '2017-11-28 18:58:12', 'inherit', 0, 0, 0, '', NULL, NULL),
(155, 39, 0, 'Gentle Body Power Workouts', 'WholyFit original stretching and core strength workouts set to scripture, bible-based. Appropriate for anyone. Multi-level, self-paced. Various levels offered.', 'http://prolivestream.s3.amazonaws.com/logos/channel_39_tvapp_playlist_155', 77, 15, 0, 0, 5, NULL, 0, 0, '', '', '2016-09-27 21:02:28', '2017-11-28 18:58:12', 'inherit', 0, 0, 0, '', NULL, NULL),
(156, 39, 0, 'Interviews', '', 'https://onestudio.imgix.net/afce2fbef1a0ceafec03eda10f2e5779_1.jpg?w=266&h=150&fit=crop&crop=entropy&auto=format,enhance&q=60', 86, 14, 0, 0, 7, NULL, 0, 0, '', '', '2016-09-27 21:03:19', '2017-11-28 18:58:12', 'inherit', 0, 0, 0, '', NULL, NULL),
(157, 39, 0, 'WholyFit Certified Fitness Pros Only', 'Continuing Education and Re-certification, New GBP heirloom routines and Fusion classes.', 'http://prolivestream.s3.amazonaws.com/logos/channel_39_tvapp_playlist_157', 81, 12, 0, 0, 12, NULL, 0, 0, '', '', '2016-09-27 21:03:50', '2017-11-28 18:58:12', 'inherit', 0, 0, 0, '', NULL, NULL),
(161, 13, 0, 'About Us', 'REAL PEOPLE! REAL STORIES! REAL TV! ASY TV welcomes you inside the lives of the ordinary extraordinary! From Food and Travel to Documentary Series, Comedy, Paranormal and SO MUCH MORE! Hundreds of hours of all original ASY TV programming is just a click away!  Subscribe now and join tens of thousands of ASY TV viewers! Full On Demand Service, 24/7 Livestream and Various interactive show! New Shows and New Episodes almost weekly! FIND US ON FACEBOOK! Contact ASY TV at asytvnet@gmail.com', 'http://prolivestream.s3.amazonaws.com/logos/channel_13_tvapp_playlist_161.jpg', 0, 0, 0, 0, 1, NULL, 3, 0, '', '', '2016-09-19 07:44:54', '2016-09-27 01:54:55', 'inherit', 0, 0, 0, '', NULL, NULL),
(166, 38, 0, 'About Us', 'REAL PEOPLE! REAL STORIES! REAL TV! ASY TV welcomes you inside the lives of the ordinary extraordinary! From Food and Travel to Documentary Series, Comedy, Paranormal and SO MUCH MORE! Hundreds of hours of all original ASY TV programming is just a click away!  Subscribe now and join tens of thousands of ASY TV viewers! Full On Demand Service, 24/7 Livestream and Various interactive show! New Shows and New Episodes almost weekly! FIND US ON FACEBOOK! Contact ASY TV at asytvnet@gmail.com', 'http://prolivestream.s3.amazonaws.com/logos/channel_38_tvapp_playlist_166.jpg', 0, 0, 0, 0, 1, NULL, 3, 0, '', '', '2016-09-19 07:44:54', '2016-09-27 01:54:55', 'inherit', 0, 0, 0, '', NULL, NULL),
(167, 39, 0, 'About Us', 'More than exercise. WHOLYFIT TV welcomes you to experience the WholyFit Life for body, soul and spirit! REAL WORKOUTS FROM GENUINE CERTIFIED WHOLYFIT TRAINERS!  Subscribe to FREE WholyFit TV for videos about fitness for the whole YOU! From fitness, nutrition and health to Team WholyFit Reality Series,  and SO MUCH MORE all from a Biblical worldview! Sign up for the Premium subscription and get full WholyFit workouts - Gentle Body Power Christian Alternative to yoga, Aerobic Resistance Training, Kickboxing and more. WHOLYFIT TV programming is just a click away!  Subscribe now and join  WHOLYFIT TV viewers from all over the world! Full On Demand Service, 24/7 Livestream and Various interactive shows! New Shows and New Episodes almost weekly! FIND US ON FACEBOOK at WholyFit Christian Fitness! Contact WHOLYFIT TV at wholyfit@live.com', 'http://prolivestream.s3.amazonaws.com/logos/channel_39_tvapp_playlist_167.jpg', 0, 0, 0, 0, 3, NULL, 3, 0, '', '', '2016-09-19 07:44:54', '2017-11-28 18:58:12', 'inherit', 0, 0, 0, '', NULL, NULL),
(169, 42, 0, 'About Us', '42 channel', 'http://prolivestream.s3.amazonaws.com/logos/channel_42_tvapp_playlist_169.jpg', 0, 0, 0, 0, 3, NULL, 3, 0, '', '', '2016-09-19 07:44:54', '2017-11-28 19:00:14', 'inherit', 0, 0, 0, '', NULL, NULL),
(171, 44, 0, 'About Us', 'Welcome to the Mt. Olive Seventh-day Adventist Church in Etobicoke, ON. We are a Christian community and would love to have you join our family. To learn more about what we believe you can visit our About Us page. Please join us for Bible study, worship, and prayer.\n\nPastor David Rogers\nMt. Olive Adventist Church\n\nJoin Us This Saturday\nService times:\nSabbath School: 9:15 am\nWorship Service: 11:00 am\n\nAdventist Youth Service: 90 minutes before sunset', 'http://prolivestream.s3.amazonaws.com/logos/channel_44_tvapp_playlist_171.jpg', 0, 0, 0, 1, 2, NULL, 3, 0, '', '', '2016-09-19 07:44:54', '2017-06-17 18:29:00', 'inherit', 0, 0, 0, '', NULL, NULL),
(172, 46, 0, 'About Us', '47 channel', 'http://prolivestream.s3.amazonaws.com/logos/channel_46_tvapp_playlist_172.jpg', 0, 0, 0, 0, 1, NULL, 3, 0, '', '', '2016-09-19 07:44:54', '2016-09-27 01:54:55', 'inherit', 0, 0, 0, '', NULL, NULL),
(173, 47, 0, 'KITV Live Feed  Watch Live', 'KITV4 Nowcast  Watch live news 24/7 for Oahu, Hawaii, Maui and Kauai', 'https://prolivestream.imgix.net/logos-poster/channel_47_tvapp_playlist_173.jpg', 0, 0, 0, 0, 2, NULL, 2, 0, 'http://w3.cdn.anvato.net/live/manifests/bOP2ERal4PBSO02BF65uADvXvlD0Mzq5/kitv/master.m3u8', '', '2016-09-19 07:44:54', '2017-12-27 12:58:18', 'inherit', 1, 0, 0, 'https://prolivestream.imgix.net/banners/channel_47_tvapp_playlist_173.jpg', NULL, NULL),
(174, 48, 0, 'About Us', '48 channel', 'http://prolivestream.s3.amazonaws.com/logos/channel_48_tvapp_playlist_174.jpg', 0, 0, 0, 0, 1, NULL, 3, 0, '', '', '2016-09-19 07:44:54', '2016-09-27 01:54:55', 'inherit', 0, 0, 0, '', NULL, NULL),
(175, 49, 0, 'About Us', '49 channel', 'http://prolivestream.s3.amazonaws.com/logos/channel_49_tvapp_playlist_175.jpg', 0, 0, 0, 0, 1, NULL, 3, 0, '', '', '2016-09-19 07:44:54', '2016-09-27 01:54:55', 'inherit', 0, 0, 0, '', NULL, NULL),
(176, 13, 0, 'Watch Live', '24/7 Laughs!', 'http://prolivestream.s3.amazonaws.com/logos/channel_13_tvapp_playlist_176.jpg', 0, 0, 0, 0, 1, NULL, 2, 0, 'http://www.digitaldiamonds.tv/hls/master-maija.m3u8', '', '2016-09-19 07:44:54', '2016-09-27 01:54:55', 'inherit', 0, 0, 0, '', NULL, NULL),
(181, 38, 0, 'Watch Live', '49 channel', 'http://prolivestream.s3.amazonaws.com/logos/channel_38_tvapp_playlist_181.jpg', 0, 0, 0, 0, 1, NULL, 2, 0, 'http://www.digitaldiamonds.tv/hls/master-maija.m3u8', '', '2016-09-19 07:44:54', '2016-09-27 01:54:55', 'inherit', 0, 0, 0, '', NULL, NULL),
(182, 39, 0, 'Watch Live', 'Live workouts with real time social networking interaction.', 'http://prolivestream.s3.amazonaws.com/logos/channel_39_tvapp_playlist_182.jpg', 0, 0, 0, 0, 11, NULL, 2, 0, 'http://www.onestudio360.com/media/hls/wholyfitlive/playlist.m3u8', '', '2016-09-19 07:44:54', '2017-11-28 18:58:12', 'inherit', 0, 0, 0, '', NULL, NULL),
(184, 42, 0, 'Watch Live', 'Watch Live Description Goes Here', 'http://prolivestream.s3.amazonaws.com/logos/channel_42_tvapp_playlist_184.jpg', 0, 0, 0, 0, 7, NULL, 2, 0, 'http://wicu-lh.akamaihd.net/i/WICU_621@78350/master.m3u8', '', '2016-09-19 07:44:54', '2018-01-08 19:41:34', 'inherit', 0, 0, 0, '', NULL, NULL),
(185, 43, 0, 'Watch Live', 'Watch Live Description Goes Here', 'http://prolivestream.s3.amazonaws.com/logos/channel_43_tvapp_playlist_185', 0, 0, 0, 0, 4, 261, 2, 0, 'http://www.s360.tv/media/hls/oanlive/playlist.m3u8', '', '2016-09-19 07:44:54', '2017-07-14 16:52:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(186, 44, 0, 'Watch Live', 'Watch Live Description Goes Here', 'http://prolivestream.s3.amazonaws.com/logos/channel_44_tvapp_playlist_186.jpg', 0, 0, 0, 0, 1, NULL, 2, 0, 'http://104.236.141.191:8081/master/olivebranchlive/playlist.m3u8', '', '2016-09-19 07:44:54', '2017-06-17 18:45:07', 'inherit', 0, 0, 0, '', NULL, NULL),
(187, 46, 0, 'Watch Live', 'Watch the best in inspirational television right here on Preach The Word Worldwide Network TV', 'http://prolivestream.s3.amazonaws.com/logos/channel_46_tvapp_playlist_187.jpg', 0, 0, 0, 0, 1, NULL, 2, 0, 'http://104.131.55.9:8081/dashtv/preachtheword/playlist.m3u8', '', '2016-09-19 07:44:54', '2017-01-18 14:58:36', 'inherit', 0, 0, 0, '', NULL, NULL),
(200, 50, 0, 'On Demand', 'Power Endurance Training – no equipment is required!', 'http://prolivestream.s3.amazonaws.com/logos/channel_50_tvapp_playlist_200', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2016-10-01 17:03:18', '2016-10-02 08:01:19', 'inherit', 0, 0, 0, '', NULL, NULL),
(201, 37, 0, 'The Hand I Was Dealt', 'Urban Drama', '', 0, 0, 0, 1, 20, NULL, 0, 0, '', '', '2016-10-03 10:46:25', '2017-11-02 03:25:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(202, 42, 0, '18844875', 'UStream channel ID 18844875', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2016-10-06 10:26:02', '2017-11-28 19:00:14', 'inherit', 0, 0, 0, '', NULL, NULL),
(206, 49, 0, 'Watch LIve', '', '', 0, 0, 0, 0, 1, NULL, 2, 0, 'http://www.onestudio360.com/media/hls/taxemlive/playlist.m3u8', '', '2016-10-18 04:27:06', '2016-10-18 04:27:06', 'inherit', 0, 0, 0, '', NULL, NULL),
(207, 42, 0, 'Featured Videos', '', '', 0, 0, 0, 0, 4, NULL, 0, 0, '', '', '2016-10-18 05:36:29', '2018-01-08 19:41:20', 'inherit', 0, 0, 0, '', NULL, NULL),
(208, 42, 0, 'Latest Videos', '', '', 0, 0, 0, 0, 6, NULL, 0, 0, '', '', '2016-10-18 05:36:45', '2018-01-08 19:41:20', 'inherit', 0, 0, 0, '', NULL, NULL),
(210, 49, 0, 'Featured Videos', '', '', 0, 0, 2, 1, 1, NULL, 0, 0, '', '', '2016-10-18 05:37:32', '2016-11-05 22:44:00', 'inherit', 0, 0, 0, '', NULL, NULL),
(211, 49, 0, 'Latest Videos', '', '', 0, 0, 3, 0, 1, NULL, 0, 0, '', '', '2016-10-18 05:37:39', '2016-10-18 05:37:52', 'inherit', 0, 0, 0, '', NULL, NULL),
(212, 49, 0, 'Most Popular Videos', '', '', 0, 0, 5, 0, 1, NULL, 0, 0, '', '', '2016-10-18 05:38:50', '2016-11-05 22:54:40', 'inherit', 0, 0, 0, '', NULL, NULL),
(213, 49, 0, 'Most Viewed', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2016-10-18 05:39:13', '2016-10-18 05:39:13', 'inherit', 0, 0, 0, '', NULL, NULL),
(214, 49, 0, 'CA NHẠC', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2016-10-18 05:44:46', '2016-10-18 05:44:46', 'inherit', 0, 0, 0, '', NULL, NULL),
(215, 49, 0, 'DU LỊCH', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2016-10-18 05:45:08', '2016-10-18 05:45:08', 'inherit', 0, 0, 0, '', NULL, NULL),
(216, 49, 0, 'HÀI HƯỚC', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2016-10-18 05:45:27', '2016-10-18 05:45:27', 'inherit', 0, 0, 0, '', NULL, NULL),
(217, 49, 0, 'PHIM BỘ', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2016-10-18 05:45:42', '2016-10-18 05:45:42', 'inherit', 0, 0, 0, '', NULL, NULL),
(218, 49, 0, 'PHIM ĐẠI HÀN', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2016-10-18 05:46:00', '2016-10-18 05:46:00', 'inherit', 0, 0, 0, '', NULL, NULL),
(219, 49, 0, 'PHIM HOT', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2016-10-18 05:46:19', '2016-10-18 05:46:19', 'inherit', 0, 0, 0, '', NULL, NULL),
(220, 49, 0, 'PHIM LẺ', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2016-10-18 05:46:33', '2016-10-18 05:46:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(221, 49, 0, 'PHIM MỚI ĐĂNG', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2016-10-18 05:46:46', '2016-10-18 05:46:46', 'inherit', 0, 0, 0, '', NULL, NULL),
(223, 49, 0, 'PHIM THÁI LAN', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2016-10-18 05:47:32', '2016-10-18 05:47:32', 'inherit', 0, 0, 0, '', NULL, NULL),
(224, 49, 0, 'PHIM TRUNG QUỐC/HK', '', '', 0, 0, 0, 1, 1, NULL, 0, 0, '', '', '2016-10-18 05:47:49', '2016-11-05 22:44:41', 'inherit', 0, 0, 0, '', NULL, NULL),
(225, 49, 0, 'Main Videos', '', '', 0, 0, 1, 0, 1, NULL, 0, 0, '', '', '2016-10-18 05:52:04', '2016-11-05 22:54:15', 'inherit', 0, 0, 0, '', NULL, NULL),
(227, 51, 0, 'Watch Live', 'Watch the best indie music videos 24/7', '', 0, 0, 0, 0, 22, NULL, 2, 0, '', '', '2016-10-25 20:31:08', '2017-09-18 11:14:54', 'inherit', 0, 0, 0, '', NULL, NULL),
(228, 51, 0, 'Browse', '', '', 0, 0, 0, 0, 5, NULL, 1, 0, '', '', '2016-10-25 20:35:16', '2017-09-18 11:14:54', 'inherit', 0, 0, 0, '', NULL, NULL),
(229, 51, 0, 'About Us', 'From the owners of iFame TV (check Roku under Music Section) comes their new 24/7 streaming music video channel.  Make sure to also download our channel iFame TV to watch the best in music TV and movies including WHO?MAG TV, DMTV, Video Vision. as well as classic music shows like Dancin'' On Air. Dance Party USA, One House Street, and much more!', '', 0, 0, 0, 0, 4, NULL, 3, 0, '', '', '2016-10-25 20:35:35', '2017-09-18 11:14:54', 'inherit', 0, 0, 0, '', NULL, NULL),
(230, 35, 0, 'LOST IN AMERICA', 'Join Garrett Coon as he Travels the Nation rediscovering lost American History.', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_230.jpg', 0, 0, 0, 1, 42, NULL, 0, 0, '', '', '2016-10-29 07:26:02', '2018-01-08 22:47:31', 'inherit', 1, 0, 0, '', NULL, NULL),
(235, 37, 0, 'We Talk Weekly', 'We Talk Weekly (formerly Talk the Talk)', '', 0, 0, 0, 1, 11, NULL, 0, 0, '', '', '2016-11-01 08:46:27', '2017-11-02 03:25:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(243, 42, 0, '20876986', '20876986', '', 0, 0, 0, 0, 2, NULL, 0, 0, '', '', '2016-11-11 22:15:16', '2017-11-28 19:00:14', 'inherit', 0, 0, 0, '', NULL, NULL),
(245, 43, 0, 'Freedom Fighters', 'Category descriptions go here', 'http://prolivestream.s3.amazonaws.com/logos/channel_43_tvapp_playlist_245', 0, 0, 0, 0, -1, 106, 0, 0, '', '', '2016-11-14 01:49:41', '2017-07-14 10:56:38', 'inherit', 0, 0, 0, '', NULL, NULL),
(246, 43, 0, 'Freedom Fighters Season 1', 'Freedom Fighters Season 1', 'http://prolivestream.s3.amazonaws.com/logos/channel_43_tvapp_playlist_246', 0, 0, 0, 1, 1, 245, 0, 0, '', '', '2016-11-14 01:49:59', '2017-03-31 10:12:18', 'inherit', 0, 0, 0, '', NULL, NULL),
(247, 43, 0, 'Freedom Fighters Season 2', 'Freedom Fighters Season 2', 'http://prolivestream.s3.amazonaws.com/logos/channel_43_tvapp_playlist_247', 0, 0, 0, 1, 2, 245, 0, 0, '', '', '2016-11-14 01:50:13', '2017-03-31 10:13:00', 'inherit', 0, 0, 0, '', NULL, NULL),
(248, 43, 0, 'Freedom Fighters Season 3', 'Category descriptions go here', 'http://prolivestream.s3.amazonaws.com/logos/channel_43_tvapp_playlist_248', 0, 0, 0, 1, 4, 245, 0, 0, '', '', '2016-11-14 01:50:49', '2017-03-31 10:13:00', 'inherit', 0, 0, 0, '', NULL, NULL),
(249, 43, 0, 'Freedom Fighters Season 4', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_43_tvapp_playlist_249', 0, 0, 0, 1, 5, 245, 0, 0, '', '', '2016-11-14 01:51:08', '2017-03-31 10:12:46', 'inherit', 0, 0, 0, '', NULL, NULL),
(251, 43, 0, 'Bear Whisperer 1', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_43_tvapp_playlist_251', 0, 0, 0, 0, 1, 250, 0, 0, '', '', '2016-11-14 01:52:06', '2017-08-05 02:59:22', 'inherit', 1, 0, 0, '', NULL, NULL),
(252, 43, 0, 'Bear Whisperer 2', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_43_tvapp_playlist_252', 0, 0, 0, 0, 4, 250, 0, 0, '', '', '2016-11-14 01:52:29', '2017-08-05 02:59:22', 'inherit', 1, 0, 0, '', NULL, NULL),
(253, 43, 0, 'Bear Whisperer 3', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_43_tvapp_playlist_253', 0, 0, 0, 0, 2, 250, 0, 0, '', '', '2016-11-14 01:52:44', '2017-08-05 02:59:48', 'inherit', 1, 0, 0, '', NULL, NULL),
(264, 35, 0, 'NEW HORIZONS "Living Life to the Fullest"', 'Join Tom Rasmussen & Lori Schardt on ageless adventures and informative journeys!', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_264', 0, 0, 0, 1, 20, NULL, 0, 0, '', '', '2016-11-16 08:24:12', '2018-01-08 22:47:51', 'inherit', 0, 0, 0, '', NULL, NULL),
(265, 41, 0, 'Watch OCTV Live!', '24x7 live news, weather and entertainment', 'https://prolivestream.imgix.net/logos-poster/channel_41_tvapp_playlist_265.jpg', 0, 0, 0, 0, 1, NULL, 2, 0, 'http://wicu-lh.akamaihd.net/i/WICU_1369@78350/master.m3u8', '', '2016-11-16 20:26:27', '2017-12-21 03:16:54', 'free', 1, 0, 0, 'https://prolivestream.imgix.net/banners/channel_41_tvapp_playlist_265.jpg', NULL, NULL),
(267, 48, 0, 'Live', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_48_tvapp_playlist_267', 0, 0, 0, 0, 1, NULL, 2, 0, 'http://techlabs.adsparx.net/hlslive/asylive.m3u8?chname=asylive&pub=e48ce1cd-6fa7-46c4-9bad-ff05b8b72447&acsurl=techlabs-acs.adsparx.net', '', '2016-11-17 04:12:48', '2016-11-17 04:13:29', 'inherit', 0, 0, 0, '', NULL, NULL),
(273, 35, 0, 'The BB OXMOWZER SHOW', 'BB OXMOWZER (Brandon Bishop Jr.) teaches him dad how to play popular video games.', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_273', 0, 0, 0, 2, 32, NULL, 0, 0, '', '', '2016-11-30 00:32:04', '2018-01-08 22:47:46', 'inherit', 0, 0, 0, '', NULL, NULL),
(276, 35, 0, 'SICK DREAMS TV', 'This Collection of films and shorts by Independent filmmakers will surely reside within your most disturbing nightmares.', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_276', 0, 0, 0, 1, 26, NULL, 0, 0, '', '', '2016-12-02 02:12:31', '2018-01-08 22:47:46', 'inherit', 0, 0, 0, '', NULL, NULL),
(280, 51, 0, 'Hip Hop/Rap', '', '', 0, 0, 0, 1, 11, NULL, 0, 0, '', '', '2016-12-21 10:22:07', '2017-09-18 11:14:54', 'inherit', 0, 0, 0, '', NULL, NULL),
(281, 51, 0, 'EDM/Dance Music', 'EDM/Dance Music', 'http://prolivestream.s3.amazonaws.com/logos/channel_51_tvapp_playlist_281', 0, 0, 0, 0, 6, NULL, 0, 0, '', '', '2016-12-21 10:22:39', '2017-09-18 11:14:54', 'inherit', 0, 0, 0, '', NULL, NULL),
(282, 51, 0, 'Other Videos', '', '', 0, 0, 0, 0, 14, NULL, 0, 0, '', '', '2016-12-21 10:25:39', '2017-09-18 11:14:54', 'inherit', 0, 0, 0, '', NULL, NULL),
(283, 51, 0, 'Rock', '', '', 0, 0, 0, 0, 20, NULL, 0, 0, '', '', '2016-12-21 10:26:30', '2017-09-18 11:14:54', 'inherit', 0, 0, 0, '', NULL, NULL),
(284, 51, 0, 'R&B/Soul', '', '', 0, 0, 0, 0, 16, NULL, 0, 0, '', '', '2016-12-21 10:26:47', '2017-09-18 11:14:54', 'inherit', 0, 0, 0, '', NULL, NULL),
(285, 51, 0, 'Reggae', '', '', 0, 0, 0, 0, 18, NULL, 0, 0, '', '', '2016-12-21 10:27:05', '2017-09-18 11:14:54', 'inherit', 0, 0, 0, '', NULL, NULL),
(287, 51, 0, 'WHO?MAG Video Vision Eps', '', '', 0, 0, 0, 0, 24, NULL, 0, 0, '', '', '2016-12-21 10:30:34', '2017-09-18 11:14:54', 'inherit', 0, 0, 0, '', NULL, NULL),
(291, 51, 0, 'Hip Hop/Rap', 'Hip Hop/Rap', '', 0, 0, 0, 0, 12, NULL, 0, 0, '', '', '2016-12-21 13:45:23', '2017-09-18 11:14:54', 'inherit', 0, 0, 0, '', NULL, NULL),
(296, 51, 0, 'Hip Hop/Rap', 'Hip Hop/Rap', '', 0, 0, 0, 0, 13, NULL, 0, 0, '', '', '2016-12-23 12:35:26', '2017-09-18 11:14:54', 'inherit', 0, 0, 0, '', NULL, NULL),
(297, 51, 0, 'Hip Hop and Rap Videos', 'Hip Hop and Rap Videos', '', 0, 0, 0, 1, 10, NULL, 0, 0, '', '', '2016-12-23 12:41:49', '2017-09-18 11:14:54', 'inherit', 0, 0, 0, '', NULL, NULL),
(298, 51, 0, 'WHO?MAG Video Vision Episodes', 'WHO?MAG Video Vision Episodes', '', 0, 0, 0, 1, 23, NULL, 0, 0, '', '', '2016-12-23 12:52:26', '2017-09-18 11:14:54', 'inherit', 0, 0, 0, '', NULL, NULL),
(299, 51, 0, '#Trending', '#Trending', '', 0, 0, 0, 1, 2, NULL, 0, 0, '', '', '2016-12-23 12:54:06', '2017-09-18 11:15:03', 'inherit', 0, 0, 0, '', NULL, NULL),
(300, 51, 0, 'Other Videos', 'Other Videos', '', 0, 0, 0, 1, 15, NULL, 0, 0, '', '', '2016-12-23 12:54:36', '2017-09-18 11:14:54', 'inherit', 0, 0, 0, '', NULL, NULL),
(301, 51, 0, 'EDM/Dance Music Videos', 'EDM/Dance Music Videos', '', 0, 0, 0, 0, 7, NULL, 0, 0, '', '', '2016-12-23 12:55:03', '2017-09-18 11:14:54', 'inherit', 0, 0, 0, '', NULL, NULL),
(302, 51, 0, 'Reggae Music Videos', 'Reggae Music Videos', '', 0, 0, 0, 1, 19, NULL, 0, 0, '', '', '2016-12-23 12:55:35', '2017-09-18 11:14:54', 'inherit', 0, 0, 0, '', NULL, NULL),
(303, 51, 0, 'Featured Music Videos', '', '', 0, 0, 0, 1, 9, NULL, 0, 0, '', '', '2016-12-23 12:56:00', '2017-09-18 11:14:54', 'inherit', 0, 0, 0, '', NULL, NULL),
(304, 51, 0, 'R&B/Soul Music Videos', 'R&B/Soul Music Videos', '', 0, 0, 0, 1, 17, NULL, 0, 0, '', '', '2016-12-23 12:56:29', '2017-09-18 11:14:54', 'inherit', 0, 0, 0, '', NULL, NULL),
(305, 51, 0, 'Rock Music Videos', 'Rock Music Videos', '', 0, 0, 0, 1, 21, NULL, 0, 0, '', '', '2016-12-23 12:57:00', '2017-09-18 11:14:54', 'inherit', 0, 0, 0, '', NULL, NULL),
(306, 51, 0, 'EDM/Dance Music Videos', 'EDM/Dance Music Videos', '', 0, 0, 0, 1, 8, NULL, 0, 0, '', '', '2016-12-23 12:58:58', '2017-09-18 11:14:54', 'inherit', 0, 0, 0, '', NULL, NULL),
(308, 37, 0, 'WHO?MAG TV Hip Hop Talk Podcast 1', 'Hosted by Rob Schwartz (WHO?MAG TV), DJ Ready Red (of The Geto Boys), Andrew Fox, Phil Jackson, and Mike Trampe', '', 0, 0, 0, 1, 9, NULL, 0, 0, '', '', '2016-12-23 13:56:12', '2017-11-02 03:25:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(313, 37, 0, 'R&B/Soul Music Videos', 'R&B/Soul Music Videos', '', 0, 0, 0, 1, 14, NULL, 0, 0, '', '', '2016-12-30 02:59:20', '2017-11-02 03:25:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(314, 39, 0, 'K700 Workouts', 'WholyFit Kickboxing with real martial arts technique taught by Laura Monica, WholyFit Founder, Black Belt in Chun Kuk Do Mixed Martial Arts and Health Fitness Specialist with American College of Sports Medicine.', '', 0, 0, 0, 0, 8, NULL, 0, 0, '', '', '2017-01-01 04:56:54', '2017-11-28 18:58:12', 'inherit', 0, 0, 0, '', NULL, NULL),
(316, 50, 0, 'Body Blast 1', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_50_tvapp_playlist_316', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-01-02 02:21:43', '2017-01-02 02:24:59', 'inherit', 0, 0, 0, '', NULL, NULL),
(317, 50, 0, 'Body Blast 2', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_50_tvapp_playlist_317', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-01-02 02:22:09', '2017-01-02 02:26:27', 'inherit', 0, 0, 0, '', NULL, NULL),
(318, 50, 0, 'Body Blast 3', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_50_tvapp_playlist_318', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-01-02 02:22:18', '2017-01-02 02:26:38', 'inherit', 0, 0, 0, '', NULL, NULL),
(319, 50, 0, 'Body Blast 4', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_50_tvapp_playlist_319', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-01-02 02:22:26', '2017-01-02 02:26:50', 'inherit', 0, 0, 0, '', NULL, NULL),
(320, 50, 0, 'Body Blast 5', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_50_tvapp_playlist_320', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-01-02 02:22:37', '2017-01-02 02:27:04', 'inherit', 0, 0, 0, '', NULL, NULL),
(321, 50, 0, 'Body Blast 6', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_50_tvapp_playlist_321', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-01-02 02:22:45', '2017-01-02 02:27:15', 'inherit', 0, 0, 0, '', NULL, NULL),
(322, 50, 0, 'Body Blast 7', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_50_tvapp_playlist_322', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-01-02 02:22:55', '2017-01-02 02:27:25', 'inherit', 0, 0, 0, '', NULL, NULL),
(323, 50, 0, 'Body Blast 8', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_50_tvapp_playlist_323', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-01-02 02:23:03', '2017-01-02 02:27:37', 'inherit', 0, 0, 0, '', NULL, NULL),
(324, 50, 0, 'Body Blast 9', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_50_tvapp_playlist_324', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-01-02 02:23:11', '2017-01-02 02:28:03', 'inherit', 0, 0, 0, '', NULL, NULL),
(325, 50, 0, 'Body Blast 10', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_50_tvapp_playlist_325', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-01-02 02:23:20', '2017-01-02 02:25:12', 'inherit', 0, 0, 0, '', NULL, NULL),
(326, 50, 0, 'Body Blast 12', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_50_tvapp_playlist_326', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-01-02 02:23:31', '2017-01-02 02:25:38', 'inherit', 0, 0, 0, '', NULL, NULL),
(327, 50, 0, 'Body Blast 11', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_50_tvapp_playlist_327', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-01-02 02:23:39', '2017-01-02 02:25:26', 'inherit', 0, 0, 0, '', NULL, NULL),
(328, 50, 0, 'Body Blast 13', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_50_tvapp_playlist_328', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-01-02 02:24:06', '2017-01-02 02:25:52', 'inherit', 0, 0, 0, '', NULL, NULL),
(329, 50, 0, 'Body Blast 14', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_50_tvapp_playlist_329', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-01-02 02:24:15', '2017-01-02 02:26:04', 'inherit', 0, 0, 0, '', NULL, NULL),
(330, 50, 0, 'Body Blast 15', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_50_tvapp_playlist_330', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-01-02 02:24:26', '2017-01-02 02:26:16', 'inherit', 0, 0, 0, '', NULL, NULL),
(331, 50, 0, 'Round 22', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_50_tvapp_playlist_331', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-01-02 02:28:39', '2017-01-02 02:29:09', 'inherit', 0, 0, 0, '', NULL, NULL),
(332, 50, 0, 'Round 20', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_50_tvapp_playlist_332', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-01-02 02:29:28', '2017-01-02 02:29:42', 'inherit', 0, 0, 0, '', NULL, NULL),
(333, 50, 0, 'Round 21', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_50_tvapp_playlist_333', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-01-02 02:29:51', '2017-01-02 02:30:15', 'inherit', 0, 0, 0, '', NULL, NULL),
(334, 50, 0, 'Round 23', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_50_tvapp_playlist_334', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-01-02 02:30:33', '2017-01-02 02:30:47', 'inherit', 0, 0, 0, '', NULL, NULL),
(335, 50, 0, 'Round 24', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_50_tvapp_playlist_335', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-01-02 02:31:04', '2017-01-02 02:31:18', 'inherit', 0, 0, 0, '', NULL, NULL),
(336, 50, 0, 'Round 19', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_50_tvapp_playlist_336', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-01-02 02:31:30', '2017-01-02 02:31:44', 'inherit', 0, 0, 0, '', NULL, NULL),
(337, 50, 0, 'Round 5', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-01-02 02:33:16', '2017-01-02 02:33:16', 'inherit', 0, 0, 0, '', NULL, NULL),
(338, 50, 0, 'Round 12', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-01-02 02:33:27', '2017-01-02 02:33:27', 'inherit', 0, 0, 0, '', NULL, NULL),
(339, 50, 0, 'Round 15', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-01-02 02:33:39', '2017-01-02 02:33:39', 'inherit', 0, 0, 0, '', NULL, NULL),
(340, 50, 0, 'Round 16', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-01-02 02:33:50', '2017-01-02 02:33:50', 'inherit', 0, 0, 0, '', NULL, NULL),
(341, 50, 0, 'Round 17', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-01-02 02:34:04', '2017-01-02 02:34:04', 'inherit', 0, 0, 0, '', NULL, NULL),
(342, 50, 0, 'Round 18', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-01-02 02:34:20', '2017-01-02 02:34:20', 'inherit', 0, 0, 0, '', NULL, NULL),
(343, 37, 0, 'Grimoire (Horror TV Show)', 'Grimoire (Horror TV Show)', '', 0, 0, 0, 1, 16, NULL, 0, 0, '', '', '2017-01-08 23:01:18', '2017-11-02 03:25:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(344, 37, 0, 'Single On A Saturday Night', '', '', 0, 0, 0, 1, 17, NULL, 0, 0, '', '', '2017-01-09 07:09:50', '2017-11-02 03:25:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(346, 37, 0, 'Whatz Going On', 'Whatz Going On', '', 0, 0, 0, 1, 12, NULL, 0, 0, '', '', '2017-01-09 12:28:17', '2017-11-02 03:25:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(349, 37, 0, 'The DJ', 'Interviews, live performances, and specials from your favorite DJs in Hip Hop and EDM', '', 0, 0, 0, 1, 26, NULL, 0, 0, '', '', '2017-01-26 00:14:12', '2017-11-02 03:25:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(350, 37, 0, 'Freestyles', '', '', 0, 0, 0, 1, 28, NULL, 0, 0, '', '', '2017-01-27 12:31:38', '2017-11-02 03:25:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(351, 37, 0, 'PartyNerdz Podcast', '', '', 0, 0, 0, 1, 10, NULL, 0, 0, '', '', '2017-01-28 02:49:50', '2017-11-02 03:25:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(353, 37, 0, 'New Edition - Lost & Exclusive', '', '', 0, 0, 0, 1, 18, NULL, 0, 0, '', '', '2017-01-28 13:42:52', '2017-11-02 03:25:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(360, 48, 0, 'Staff Picks', 'Staff Picks', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-02-02 12:22:42', '2017-02-02 12:22:42', 'inherit', 0, 0, 0, '', NULL, NULL),
(361, 48, 0, 'Jury Picks', 'Jury Picks', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-02-02 12:22:51', '2017-02-02 12:22:51', 'inherit', 0, 0, 0, '', NULL, NULL),
(362, 48, 0, 'Contest Winners', 'Contest Winners', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-02-02 12:23:00', '2017-02-02 12:23:00', 'inherit', 0, 0, 0, '', NULL, NULL),
(364, 48, 0, 'Love Shorts', 'Love Shorts', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-02-02 12:23:10', '2017-02-02 12:23:10', 'inherit', 0, 0, 0, '', NULL, NULL),
(365, 48, 0, 'Dramatic Shorts', 'Dramatic Shorts', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-02-02 12:23:21', '2017-02-02 12:23:21', 'inherit', 0, 0, 0, '', NULL, NULL),
(366, 48, 0, 'Documentaries', 'Documentaries', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-02-02 12:23:30', '2017-02-02 12:23:30', 'inherit', 0, 0, 0, '', NULL, NULL),
(367, 63, 0, 'NHK G', 'Chi-deji\n\nTerrestrial Digital Broadcasting', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_367.jpg', 0, 0, 6, 0, 1, NULL, 2, 0, 'http://120.50.40.67:1935/jlive/j001/playlist.m3u8', '', '2017-02-04 11:28:11', '2017-12-11 15:27:33', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_367.jpg', NULL, NULL),
(368, 63, 0, 'NHK E', 'Chi-deji\n\nTerrestrial Digital Broadcasting', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_368.jpg', 0, 0, 6, 0, 2, NULL, 2, 0, 'http://120.50.40.67:1935/jlive/j002/playlist.m3u8', '', '2017-02-04 11:29:04', '2017-12-11 15:27:57', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_368.jpg', NULL, NULL),
(369, 63, 0, 'NTV', 'Chi-deji\n\nTerrestrial Digital Broadcasting', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_369.jpg', 0, 0, 6, 0, 3, NULL, 2, 0, 'http://120.50.40.68:1935/jlive/j003/playlist.m3u8', '', '2017-02-04 11:31:15', '2017-12-12 00:53:43', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_369.jpg', NULL, NULL),
(370, 63, 0, 'TBS', 'Chi-deji\n\nTerrestrial Digital Broadcasting', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_370.jpg', 0, 0, 0, 0, 5, NULL, 2, 0, 'http://120.50.40.68:1935/jlive/j004/playlist.m3u8', '', '2017-02-04 11:31:35', '2017-12-12 00:53:43', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_370.jpg', NULL, NULL),
(372, 63, 0, 'TV Asahi', 'Chi-deji\n\nTerrestrial Digital Broadcasting', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_372.jpg', 0, 0, 0, 0, 4, NULL, 2, 0, 'http://120.50.40.68:1935/jlive/j006/playlist.m3u8', '', '2017-02-04 11:32:15', '2017-12-12 00:53:43', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_372.jpg', NULL, NULL),
(373, 63, 0, 'TV TOKYO', 'Chi-deji\n\nTerrestrial Digital Broadcasting', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_373.jpg', 0, 0, 0, 0, 6, NULL, 2, 0, 'http://120.50.40.67:1935/jlive/j007/playlist.m3u8', '', '2017-02-04 11:32:35', '2017-12-12 00:53:43', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_373.jpg', NULL, NULL),
(374, 63, 0, 'OUJ', 'Chi-deji\n\nTerrestrial Digital Broadcasting', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_374.jpg', 0, 0, 0, 0, 8, NULL, 2, 0, 'http://120.50.40.67:1935/jlive/j023/playlist.m3u8', '', '2017-02-04 11:33:20', '2017-12-12 00:53:43', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_374.jpg', NULL, NULL),
(375, 63, 0, 'Fuji TV', 'Chi-deji\n\nTerrestrial Digital Broadcasting', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_375.jpg', 0, 0, 0, 0, 7, NULL, 2, 0, 'http://120.50.40.68:1935/jlive/j005/playlist.m3u8', '', '2017-02-04 12:44:53', '2017-12-12 00:53:43', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_375.jpg', NULL, NULL),
(376, 63, 0, 'WOWOW Prime', 'BS (Broadcasting Satellites)', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_376.jpg', 0, 0, 0, 0, 9, NULL, 2, 0, 'http://120.50.40.69:1935/klive/j008/playlist.m3u8', '', '2017-02-04 12:58:58', '2017-12-12 00:53:43', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_376.jpg', NULL, NULL),
(377, 63, 0, 'WOWOW Cinema', 'BS (Broadcasting Satellites)', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_377.jpg', 0, 0, 0, 0, 10, NULL, 2, 0, 'http://120.50.40.69:1935/klive/j009/playlist.m3u8', '', '2017-02-04 13:01:32', '2017-12-12 00:53:43', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_377.jpg', NULL, NULL),
(378, 63, 0, 'WOWOW Live', 'BS (Broadcasting Satellites)', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_378.jpg', 0, 0, 0, 0, 11, NULL, 2, 0, 'http://120.50.40.69:1935/klive/j010/playlist.m3u8', '', '2017-02-04 13:04:14', '2017-12-12 00:53:43', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_378.jpg', NULL, NULL),
(379, 63, 0, 'STAR 1', 'BS (Broadcasting Satellites)', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_379.jpg', 0, 0, 0, 0, 13, NULL, 2, 0, 'http://120.50.40.69:1935/klive/j011/playlist.m3u8', '', '2017-02-04 13:05:24', '2017-12-12 00:53:43', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_379.jpg', NULL, NULL),
(380, 63, 0, 'STAR 2', 'BS (Broadcasting Satellites)', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_380.jpg', 0, 0, 0, 0, 14, NULL, 2, 0, 'http://120.50.40.69:1935/klive/j012/playlist.m3u8', '', '2017-02-04 13:18:24', '2017-12-12 00:53:43', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_380.jpg', NULL, NULL),
(381, 63, 0, 'STAR 3', 'BS (Broadcasting Satellites)', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_381.jpg', 0, 0, 0, 0, 15, NULL, 2, 0, 'http://120.50.40.69:1935/klive/j013/playlist.m3u8', '', '2017-02-04 13:20:09', '2017-12-12 00:53:39', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_381.jpg', NULL, NULL),
(382, 63, 0, 'TBS 2', 'CS (Communications Satellites)', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_382.jpg', 0, 0, 0, 0, 16, NULL, 2, 0, 'http://120.50.40.69:1935/klive/j025/playlist.m3u8', '', '2017-02-04 13:22:37', '2017-12-12 00:52:18', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_382.jpg', NULL, NULL),
(383, 63, 0, 'ONE', 'ONE', 'http://prolivestream.s3.amazonaws.com/logos/channel_63_tvapp_playlist_383.jpg', 0, 0, 0, 0, -1, 382, 2, 0, 'http://120.50.40.69:1935/klive/j014/playlist.m3u8', '', '2017-02-04 13:30:53', '2017-12-09 10:45:10', 'inherit', 1, 0, 0, '', NULL, NULL),
(384, 63, 0, 'TWO', 'CS (Communications Satellites)', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_384.jpg', 0, 0, 0, 0, 18, NULL, 2, 0, 'http://120.50.40.69:1935/klive/j015/playlist.m3u8', '', '2017-02-04 13:31:15', '2017-12-11 21:02:24', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_384.jpg', NULL, NULL),
(385, 63, 0, 'NEXT', 'CS (Communications Satellites)', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_385.jpg', 0, 0, 0, 0, 19, NULL, 2, 0, 'http://120.50.40.69:1935/klive/j016/playlist.m3u8', '', '2017-02-04 13:31:34', '2017-12-11 21:02:19', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_385.jpg', NULL, NULL),
(388, 63, 0, 'J Sports 2', 'BS (Broadcasting Satellites)', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_388.jpg', 0, 0, 0, 0, 22, NULL, 2, 0, 'http://120.50.40.69:1935/klive/j019/playlist.m3u8', '', '2017-02-04 13:32:46', '2017-12-11 20:41:14', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_388.jpg', NULL, NULL),
(389, 63, 0, 'J Sports 3', 'BS (Broadcasting Satellites)', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_389.jpg', 0, 0, 0, 0, 23, NULL, 2, 0, 'http://120.50.40.69:1935/klive/j020/playlist.m3u8', '', '2017-02-04 13:33:08', '2017-12-11 20:39:52', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_389.jpg', NULL, NULL),
(390, 63, 0, 'J Sports 4', 'BS (Broadcasting Satellites)', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_390.jpg', 0, 0, 0, 0, 24, NULL, 2, 0, 'http://120.50.40.69:1935/klive/j021/playlist.m3u8', '', '2017-02-04 13:33:24', '2017-12-11 19:46:04', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_390.jpg', NULL, NULL),
(393, 63, 0, 'ANIMAX', 'BS (Broadcasting Satellites)', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_393.jpg', 0, 0, 0, 0, 20, NULL, 2, 0, 'http://120.50.40.69:1935/klive/j017/playlist.m3u8', '', '2017-02-04 13:36:06', '2017-12-11 20:58:29', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_393.jpg', NULL, NULL),
(394, 63, 0, 'J Sports 1', 'BS (Broadcasting Satellites)', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_394.jpg', 0, 0, 0, 0, 21, NULL, 2, 0, 'http://120.50.40.69:1935/klive/j018/playlist.m3u8', '', '2017-02-04 13:36:23', '2017-12-11 20:44:33', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_394.jpg', NULL, NULL);
INSERT INTO `tvapp_playlist` (`id`, `channel_id`, `tvapp_id`, `title`, `description`, `thumbnail_name`, `duration`, `master_looped`, `type`, `level`, `sort_order`, `parent_id`, `layout`, `web_layout`, `stream_url`, `status`, `created_at`, `updated_at`, `viewing`, `shelf`, `video_is`, `playlist_category`, `featured_image_url`, `mobileweb_image_url`, `playlist_type`) VALUES
(395, 63, 0, 'GOLF NETWORK', 'CS (Communications Satellites)', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_395.jpg', 0, 0, 0, 0, 25, NULL, 2, 0, 'http://120.50.40.69:1935/klive/j024/playlist.m3u8', '', '2017-02-04 13:43:18', '2017-12-11 19:25:03', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_395.jpg', NULL, NULL),
(396, 63, 0, 'BS NHK 1', 'BS (Broadcasting Satellites)', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_396.jpg', 0, 0, 0, 0, 26, NULL, 2, 0, 'http://120.50.40.69:1935/klive/j026/playlist.m3u8', '', '2017-02-04 13:43:37', '2017-12-11 19:33:17', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_396.jpg', NULL, NULL),
(397, 63, 0, 'BS NHK 2', 'BS (Broadcasting Satellites)', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_397.jpg', 0, 0, 0, 0, 27, NULL, 2, 0, 'http://120.50.40.69:1935/klive/j027/playlist.m3u8', '', '2017-02-04 13:44:04', '2017-12-11 19:29:37', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_397.jpg', NULL, NULL),
(398, 63, 0, 'BS NTV', 'BS (Broadcasting Satellites)', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_398.jpg', 0, 0, 0, 0, 28, NULL, 2, 0, 'http://120.50.40.69:1935/klive/j028/playlist.m3u8', '', '2017-02-04 13:44:30', '2017-12-11 19:16:19', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_398.jpg', NULL, NULL),
(399, 63, 0, 'BS TBS', 'BS (Broadcasting Satellites)', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_399.jpg', 0, 0, 0, 0, 30, NULL, 2, 0, 'http://120.50.40.69:1935/klive/j029/playlist.m3u8', '', '2017-02-04 13:44:49', '2017-12-11 19:21:19', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_399.jpg', NULL, NULL),
(400, 63, 0, 'BS Fuji', 'BS (Broadcasting Satellites)', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_400.jpg', 0, 0, 0, 0, 32, NULL, 2, 0, 'http://120.50.40.69:1935/klive/j030/playlist.m3u8', '', '2017-02-04 13:45:08', '2017-12-11 19:22:00', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_400.jpg', NULL, NULL),
(401, 63, 0, 'BS ABS', 'BS (Broadcasting Satellites)', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_401.jpg', 0, 0, 0, 0, 29, NULL, 2, 0, 'http://120.50.40.69:1935/klive/j031/playlist.m3u8', '', '2017-02-04 13:45:33', '2017-12-11 19:32:20', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_401.jpg', NULL, NULL),
(402, 63, 0, 'BS JAPAN', 'BS (Broadcasting Satellites)', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_402.jpg', 0, 0, 0, 0, 31, NULL, 2, 0, 'http://120.50.40.69:1935/klive/j032/playlist.m3u8', '', '2017-02-04 13:45:51', '2017-12-11 19:21:39', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_402.jpg', NULL, NULL),
(403, 63, 0, 'BS 11', 'BS (Broadcasting Satellites)', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_403.jpg', 0, 0, 0, 0, 33, NULL, 2, 0, 'http://120.50.40.69:1935/klive/j033/playlist.m3u8', '', '2017-02-04 14:39:48', '2017-12-11 19:22:18', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_403.jpg', NULL, NULL),
(404, 63, 0, 'BS TwellV', 'BS (Broadcasting Satellites)', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_404.jpg', 0, 0, 0, 0, 34, NULL, 2, 0, 'http://120.50.40.69:1935/klive/j034/playlist.m3u8', '', '2017-02-04 14:40:00', '2017-12-11 19:22:42', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_404.jpg', NULL, NULL),
(405, 63, 0, 'D Life', 'BS (Broadcasting Satellites)', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_405.jpg', 0, 0, 0, 0, 35, NULL, 2, 0, 'http://120.50.40.69:1935/klive/j035/playlist.m3u8', '', '2017-02-04 14:41:30', '2017-12-11 19:23:02', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_405.jpg', NULL, NULL),
(406, 63, 0, 'CHIBA TV', 'Chi-deji\n\nTerrestrial Digital Broadcasting', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_406.jpg', 0, 0, 0, 0, 36, NULL, 2, 0, 'http://120.50.40.67:1935/jlive/j022/playlist.m3u8', '', '2017-02-04 14:41:49', '2017-12-11 17:26:29', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_406.jpg', NULL, NULL),
(409, 35, 0, 'FIT TV 365 With Candlelynn', 'Get your Daily Home Workout, Health & Fitness Tips, Nutrition advice and more all here with Candlelynn on FOT TV 365!', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_409', 0, 0, 0, 2, 35, NULL, 0, 0, '', '', '2017-02-06 05:02:57', '2018-01-08 22:47:46', 'inherit', 0, 0, 0, '', NULL, NULL),
(411, 37, 0, 'Documentaries', '', '', 0, 0, 0, 1, 5, NULL, 0, 0, '', '', '2017-02-07 11:35:21', '2017-11-02 03:25:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(412, 37, 0, 'Movies', '', '', 0, 0, 0, 1, 91, NULL, 0, 0, '', '', '2017-02-07 11:37:33', '2017-11-02 03:25:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(414, 37, 0, 'Horror Films', '', '', 0, 0, 0, 1, 4, NULL, 0, 0, '', '', '2017-02-07 11:39:59', '2017-11-02 03:25:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(415, 37, 0, 'Comedy', '', '', 0, 0, 0, 1, 3, NULL, 0, 0, '', '', '2017-02-08 00:22:05', '2017-11-02 03:25:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(416, 62, 0, 'The Designer''s Corner', 'Video On Demand', 'http://prolivestream.s3.amazonaws.com/logos/channel_62_tvapp_playlist_416', 0, 0, 0, 0, 5, NULL, 0, 0, '', '', '2017-02-09 02:53:34', '2017-11-28 19:03:30', 'inherit', 0, 0, 0, '', NULL, NULL),
(419, 62, 0, 'From Soil to Store', 'Video On Demand', 'http://prolivestream.s3.amazonaws.com/logos/channel_62_tvapp_playlist_419', 0, 0, 0, 0, 4, NULL, 0, 0, '', '', '2017-02-09 02:54:12', '2017-11-28 19:03:30', 'inherit', 0, 0, 0, '', NULL, NULL),
(420, 62, 0, 'What''s Your Story?', 'Video On Demand', 'http://prolivestream.s3.amazonaws.com/logos/channel_62_tvapp_playlist_420', 0, 0, 0, 0, 6, NULL, 0, 0, '', '', '2017-02-09 02:54:37', '2017-11-28 19:03:30', 'inherit', 0, 0, 0, '', NULL, NULL),
(421, 62, 0, 'America''s TOP Salon', 'Video On Demand', 'http://prolivestream.s3.amazonaws.com/logos/channel_62_tvapp_playlist_421', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-02-09 02:54:52', '2017-11-28 19:03:30', 'inherit', 0, 0, 0, '', NULL, NULL),
(432, 48, 0, 'Open Contests &amp; Accepting Submissions', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_48', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-02-13 12:56:06', '2017-02-13 12:56:06', 'inherit', 0, 0, 0, '', NULL, NULL),
(433, 48, 0, 'Past Video Contests', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_48', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-02-13 12:56:06', '2017-02-13 12:56:06', 'inherit', 0, 0, 0, '', NULL, NULL),
(434, 48, 0, 'Aud TV', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_48', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-02-13 12:56:06', '2017-02-13 12:56:06', 'inherit', 0, 0, 0, '', NULL, NULL),
(435, 48, 0, 'Film Commission &amp; Interviews', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_48', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-02-13 12:56:06', '2017-02-13 12:56:06', 'inherit', 0, 0, 0, '', NULL, NULL),
(455, 41, 0, '100% Caribbean News', 'We bring you news you can count on', 'http://prolivestream.s3.amazonaws.com/logos/channel_41_tvapp_playlist_455', 0, 0, 6, 0, 4, NULL, 2, 0, 'http://www.erienewsnow.com/category/211897/ocwevents?clienttype=mrss', '', '2017-03-27 18:32:30', '2017-09-25 22:21:45', 'inherit', 0, 0, 0, '', NULL, NULL),
(456, 41, 0, 'Caribbean Weather', '24x7 conditions and forecasts', 'http://prolivestream.s3.amazonaws.com/logos/channel_41_tvapp_playlist_456', 0, 0, 6, 0, 3, NULL, 2, 0, 'http://www.erienewsnow.com/category/211870/ocw525home?clienttype=mrss', '', '2017-03-27 18:38:24', '2017-09-25 22:21:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(457, 62, 0, 'All That Zaz!', 'Video On Demand', 'http://prolivestream.s3.amazonaws.com/logos/channel_62_tvapp_playlist_457', 0, 0, 0, 0, 7, NULL, 0, 0, '', '', '2017-03-27 22:08:39', '2017-11-28 19:03:30', 'inherit', 0, 0, 0, '', NULL, NULL),
(458, 62, 0, 'Watch Live', '24/7 VSN !', 'http://prolivestream.s3.amazonaws.com/logos/channel_62_tvapp_playlist_458', 0, 0, 0, 0, 2, NULL, 2, 0, 'http://198.241.44.164:8888/hls/m3u8/vsnlive.m3u8', '', '2017-03-29 03:27:03', '2017-11-28 19:03:30', 'inherit', 0, 0, 0, '', NULL, NULL),
(460, 37, 0, 'Trailers', '', '', 0, 0, 0, 0, 29, NULL, 0, 0, '', '', '2017-03-29 08:53:39', '2017-11-02 03:25:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(483, 37, 0, 'Chicago Steam - ABA Basketball Team', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_37_tvapp_playlist_483', 0, 0, 0, 2, 13, NULL, 0, 0, '', '', '2017-04-05 21:58:45', '2017-11-02 03:25:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(484, 37, 0, 'WHO?MAG TV', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_37_tvapp_playlist_484', 0, 0, 0, 2, 23, NULL, 0, 0, '', '', '2017-04-05 22:05:43', '2017-11-02 03:25:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(486, 43, 0, 'Just Killn Time', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_43_tvapp_playlist_486', 0, 0, 0, 0, -1, 495, 0, 0, '', '', '2017-04-18 22:05:49', '2017-07-14 16:52:17', 'inherit', 0, 0, 0, '', NULL, NULL),
(487, 43, 0, 'Livin Life  Season 1', 'Livin Life.\n Gator Season is only one of the many hunting seasons that keep RJ and Jay Paul Molinere busy. Now, RJ and Jay Paul are joined by Uncle Al and other family and friends as they head out .', 'http://prolivestream.s3.amazonaws.com/logos/channel_43_tvapp_playlist_487', 0, 0, 2, 2, 1000, 614, 0, 1, '', '', '2017-04-19 23:44:01', '2017-08-05 03:07:07', 'inherit', 1, 0, 0, '', NULL, NULL),
(488, 43, 0, 'Love of the Hunt Season 1', 'Season 1 Description goes here', '', 0, 0, 0, 0, 1, 257, 0, 0, '', '', '2017-04-20 01:03:56', '2017-08-05 02:59:35', 'inherit', 1, 0, 0, '', NULL, NULL),
(489, 43, 0, 'Love of the Hunt Season 2', '', '', 0, 0, 0, 0, 1000, 257, 0, 0, '', '', '2017-04-20 01:39:41', '2017-08-05 02:59:35', 'inherit', 1, 0, 0, '', NULL, NULL),
(502, 32, 0, 'La Plebada', 'La Plebada', 'http://prolivestream.s3.amazonaws.com/logos/channel_32_tvapp_playlist_502', 0, 0, 6, 0, 2, 1206, 2, 0, 'https://nimble2.prolivestream.tv/master/laplebada/playlist.m3u8', '', '2017-07-06 08:22:15', '2017-11-23 11:04:00', 'inherit', 0, 0, 0, '', NULL, NULL),
(524, 35, 0, 'THE DRINK With Hillary Harris', 'Travel the nation on a spirited journey featuring incredible Bars, Brewers, Distillers, Wineries and more!', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_524.jpg', 0, 0, 0, 1, 7, NULL, 0, 0, '', '', '2017-07-18 00:40:38', '2018-01-14 02:54:40', 'inherit', 1, 0, 0, 'https://prolivestream.imgix.net/banners/channel_35_tvapp_playlist_524.jpg', NULL, NULL),
(525, 35, 0, 'OLD COLORADO CITY', '"Where History Comes to Life!" Enjoy this in depth series about this historic slice of Colorado Springs!', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_525.jpg', 0, 0, 0, 1, 47, NULL, 0, 0, '', '', '2017-07-18 00:43:08', '2018-01-08 22:47:31', 'inherit', 1, 0, 0, '', NULL, NULL),
(528, 35, 0, 'TOO SWEET', 'The Sweetest Show on TV! Not just because of the host, but for every piece of candy, slice of cake or sweet treat she features!', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_528.jpg', 0, 0, 0, 1, 33, NULL, 0, 0, '', '', '2017-07-18 00:46:10', '2018-01-08 22:47:46', 'inherit', 0, 0, 0, '', NULL, NULL),
(531, 37, 0, 'Interviews', 'Interviews for today''s top artists and celebrities', '', 0, 0, 0, 1, 27, NULL, 0, 0, '', '', '2017-07-18 07:53:45', '2017-11-02 03:25:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(546, 32, 0, 'Mas Latin Pop Musica', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_32_tvapp_playlist_546', 0, 0, 6, 0, 2, 544, 0, 0, 'http://104.131.157.125/pop_musica.m3u8', '', '2017-07-24 05:23:32', '2017-07-24 05:41:48', 'inherit', 0, 0, 0, '', NULL, NULL),
(548, 32, 0, 'Mas Salsa 24/7', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_32_tvapp_playlist_548', 0, 0, 6, 0, 5, 544, 0, 0, 'http://104.131.157.125/salsa_mix.m3u8', '', '2017-07-24 05:24:52', '2017-07-24 05:40:45', 'inherit', 0, 0, 0, '', NULL, NULL),
(549, 32, 0, 'Mas Regional Mexican', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_32_tvapp_playlist_549', 0, 0, 6, 0, 7, 544, 0, 0, 'http://104.131.157.125/regionalmexican.m3u8', '', '2017-07-24 05:27:02', '2017-07-24 05:41:45', 'inherit', 0, 0, 0, '', NULL, NULL),
(551, 32, 0, 'Mas Comedia Network', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_32_tvapp_playlist_551', 0, 0, 0, 0, 4, 544, 0, 0, 'http://104.131.157.125/ml-comedy-1.m3u8', '', '2017-07-24 05:34:28', '2017-07-24 05:54:12', 'inherit', 0, 0, 0, '', NULL, NULL),
(552, 32, 0, 'Mas Viajes y Comida', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_32_tvapp_playlist_552', 0, 0, 6, 0, 6, 544, 0, 0, 'http://104.131.157.125/Viajes_y_Comida.m3u8', '', '2017-07-24 05:36:33', '2017-07-24 05:41:45', 'inherit', 0, 0, 0, '', NULL, NULL),
(553, 32, 0, 'Mas Deportes', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_32_tvapp_playlist_553', 0, 0, 6, 0, 1, 544, 2, 0, 'http://104.131.157.125/Sports.m3u8', '', '2017-07-24 05:38:11', '2017-07-27 00:21:29', 'inherit', 0, 0, 0, '', NULL, NULL),
(617, 43, 0, 'Bear Whisperer Season 2', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_43_tvapp_playlist_617', 0, 0, 0, 0, 2, 621, 0, 0, '', '', '2017-08-05 03:26:38', '2017-09-18 07:31:37', 'inherit', 0, 0, 0, '', NULL, NULL),
(629, 43, 0, 'Bear Whisperer Season 1', '', '', 0, 0, 0, 0, 1, 621, 0, 0, '', '', '2017-08-05 03:36:59', '2017-09-18 07:31:37', 'inherit', 0, 0, 0, '', NULL, NULL),
(630, 43, 0, 'Bear Whisperer Season 3', '', '', 0, 0, 0, 0, 5, 621, 0, 0, '', '', '2017-08-05 03:39:37', '2017-09-18 07:31:37', 'inherit', 0, 0, 0, '', NULL, NULL),
(633, 43, 0, 'Love of the Hunt Season 1', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_43_tvapp_playlist_633.jpg', 0, 0, 0, 0, 1, 767, 0, 0, '', '', '2017-08-05 03:40:55', '2017-08-22 04:05:09', 'inherit', 0, 0, 0, '', NULL, NULL),
(636, 43, 0, 'Love of the Hunt Season 2', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_43_tvapp_playlist_636.jpg', 0, 0, 0, 0, 2, 767, 0, 0, '', '', '2017-08-05 03:43:38', '2017-09-28 01:44:38', 'inherit', 0, 0, 0, '', NULL, NULL),
(692, 75, 0, 'Watch Live', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_75_tvapp_playlist_692.jpg', 0, 0, 0, 0, 1, NULL, 0, 0, 'http://104.131.157.125/alianza_live.m3u8', '', '2017-08-11 09:39:34', '2017-09-06 22:59:03', 'inherit', 1, 0, 0, '', NULL, NULL),
(780, 35, 0, 'MAGIC TOWN "The Michael Garman Story"', 'Experience the journey of one of America''s greatest artists.', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_780.jpg', 0, 0, 0, 0, 40, NULL, 0, 0, '', '', '2017-08-29 21:43:31', '2018-01-08 22:47:31', 'inherit', 1, 0, 2, '', NULL, NULL),
(781, 35, 0, 'THE HEART OF ADDICTION', '(Season One Underway) Stories of The COREVISION Recovery Program.', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_781.jpg', 0, 0, 0, 0, 4, NULL, 0, 0, '', '', '2017-08-29 21:47:24', '2018-01-14 02:54:31', 'inherit', 1, 0, 2, 'https://s3.amazonaws.com/aceplayout/banners/channel_35_tvapp_playlist_781.jpg', NULL, NULL),
(782, 35, 0, 'BREAK the SILENCE', '(1 Season Available) Stories of those who''ve lost loved ones from Domestic Violence.', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_35_tvapp_playlist_782.jpg', 0, 0, 0, 0, 5, NULL, 0, 0, '', '', '2017-08-29 21:48:25', '2018-01-14 02:54:40', 'inherit', 1, 0, 2, 'https://s3.amazonaws.com/aceplayout/banners/channel_35_tvapp_playlist_782.jpg', NULL, NULL),
(786, 35, 0, 'SPARKLE NATION', 'Explore counter culture with Ruby Sparkle.', '', 0, 0, 0, 0, 21, NULL, 0, 0, '', '', '2017-08-29 22:05:02', '2018-01-08 22:47:51', 'inherit', 0, 0, 2, '', NULL, NULL),
(788, 35, 0, 'The SOURCE H20', 'Water, The essential to life. The SOURCE H20 makes it even better.', '', 0, 0, 0, 0, 37, NULL, 0, 0, '', '', '2017-08-29 22:18:44', '2018-01-08 22:47:46', 'inherit', 0, 0, 2, '', NULL, NULL),
(789, 35, 0, 'WITH SIGN IN HAND', '( SEASON ONE AVAILABLE) (Season Two Underway!) Garrett Coon interviews the homeless living with sign in hand.', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_789.jpg', 0, 0, 0, 0, 19, NULL, 0, 0, '', '', '2017-08-29 22:22:40', '2018-01-08 22:47:51', 'inherit', 1, 0, 2, 'https://s3.amazonaws.com/aceplayout/banners/channel_35_tvapp_playlist_789.jpg', NULL, NULL),
(803, 35, 0, 'TOO SWEET', 'The Sweetest Show on TV! Not just because of the host, but for every piece of candy, slice of cake or sweet treat she features!', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_803.jpg', 0, 0, 0, 1, 51, NULL, 0, 0, '', '', '2017-09-01 04:37:02', '2018-01-08 22:47:31', 'inherit', 1, 0, 0, '', NULL, NULL),
(804, 35, 0, 'BEST DAY EVER', '(SEASON ONE AVAIL!) (New Season Filming!) Join Gigi on her never ending quest for Food, Fun and Adventure. Travel across the country in search of the Best Day EVER!', 'https://prolivestream.imgix.net/logos-poster/channel_35_tvapp_playlist_804.jpg', 0, 0, 0, 1, 18, NULL, 0, 0, '', '', '2017-09-01 04:37:27', '2018-01-08 22:47:51', 'inherit', 1, 0, 0, 'https://prolivestream.imgix.net/banners/channel_35_tvapp_playlist_804.jpg', NULL, NULL),
(807, 35, 0, 'GO THERE EAT THAT', '(NEW SEASON! NEW EPISODES AVAIL!) (2 Seasons) Travel the Nation with Brandon Bishop finding iconic restaurants that tell the story of each city!', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_807.jpg', 0, 0, 0, 1, 3, NULL, 0, 0, '', '', '2017-09-01 04:58:58', '2018-01-03 13:11:40', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_35_tvapp_playlist_807.jpg', NULL, NULL),
(809, 35, 0, 'ECTOVISION PARANORMAL', 'Join the EVP team as they investigate  paranormal activities', '', 0, 0, 0, 1, 24, NULL, 0, 0, '', '', '2017-09-01 11:00:15', '2018-01-08 22:47:46', 'inherit', 0, 0, 0, '', NULL, NULL),
(810, 35, 0, 'SPARKLE NATION', 'Explore counter culture with Ruby Sparkle.', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_810.jpg', 0, 0, 0, 0, 46, NULL, 0, 0, '', '', '2017-09-01 11:00:28', '2018-01-08 22:47:31', 'inherit', 1, 0, 0, '', NULL, NULL),
(811, 35, 0, 'NEW HORIZONS "Living Life to the Fullest"', 'Join Tom Rasmussen & Lori Schardt on ageless adventures and informative journeys!', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_811.jpg', 0, 0, 0, 1, 41, NULL, 0, 0, '', '', '2017-09-01 11:00:53', '2018-01-08 22:47:31', 'inherit', 1, 0, 0, '', NULL, NULL),
(813, 35, 0, 'SICK DREAMS TV', 'This Collection of films and shorts by Independent filmmakers will surely reside within your most disturbing nightmares.', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_813.jpg', 0, 0, 0, 1, 49, NULL, 0, 0, '', '', '2017-09-01 11:01:37', '2018-01-08 22:47:31', 'inherit', 1, 0, 0, '', NULL, NULL),
(814, 35, 0, 'ALONE IN THE DARK', 'No Crew, No Silly Gadgets, So Sound Effects or Scary Music.. Just One man and a Camera in some of the nation''s most "Haunted" Locations.', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_814.jpg', 0, 0, 0, 1, 45, NULL, 0, 0, '', '', '2017-09-01 11:01:59', '2018-01-08 22:47:31', 'inherit', 1, 0, 0, '', NULL, NULL),
(815, 35, 0, 'SHOO! Adventures in Pest Control', 'Join Jeff Mokol as he rids the cute & creepy animals and insects plaguing homeowners. Have unwanted guests? Just say SHOO!', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_815.jpg', 0, 0, 0, 0, 52, NULL, 0, 0, '', '', '2017-09-01 11:02:20', '2018-01-08 22:47:31', 'inherit', 1, 0, 0, '', NULL, NULL),
(816, 35, 0, 'VANDALA''S EPIC ADVENTURES!', 'Join Vandala''s in one super epic fun or delicious adventure after another! A kids take on food & travel!', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_816.jpg', 0, 0, 0, 2, 55, NULL, 0, 0, '', '', '2017-09-01 11:02:37', '2018-01-08 22:47:31', 'inherit', 1, 0, 0, '', NULL, NULL),
(817, 35, 0, 'The BB OXMOWZER SHOW', 'BB OXMOWZER (Brandon Bishop Jr.) teaches him dad how to play popular video games.', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_817.jpg', 0, 0, 0, 2, 50, NULL, 0, 0, '', '', '2017-09-01 11:02:56', '2018-01-08 22:47:31', 'inherit', 1, 0, 0, '', NULL, NULL),
(818, 35, 0, 'FIT TV 365 With Candlelynn', 'Get your Daily Home Workout, Health & Fitness Tips, Nutrition advice and more all here with Candlelynn on FOT TV 365!', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_818.jpg', 0, 0, 0, 2, 54, NULL, 0, 0, '', '', '2017-09-01 11:03:14', '2018-01-08 22:47:31', 'inherit', 1, 0, 0, '', NULL, NULL),
(820, 35, 0, 'The SOURCE H20', 'Water, The essential to life. The SOURCE H20 makes it even better.', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_820.jpg', 0, 0, 0, 0, 60, NULL, 0, 0, '', '', '2017-09-01 11:03:59', '2018-01-07 06:41:55', 'inherit', 1, 0, 0, '', NULL, NULL),
(822, 35, 0, 'A FRESH TAKE With Chef Stevie', 'Chef Stevie puts together healthy alternatives to usually not so healthy dishes! Check out all of her recipes at ChefStevie.com', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_822.jpg', 0, 0, 0, 2, 57, NULL, 0, 0, '', '', '2017-09-01 11:04:42', '2018-01-08 22:47:31', 'inherit', 1, 0, 0, '', NULL, NULL),
(823, 35, 0, 'GO THERE EAT THAT', 'Travel the Nation with Brandon Bishop finding iconic restaurants that tell the story of each city!', '', 0, 0, 0, 1, 8, NULL, 0, 0, '', '', '2017-09-01 11:05:01', '2018-01-14 02:54:40', 'inherit', 0, 0, 0, '', NULL, NULL),
(824, 35, 0, 'HELLSCREAM INC.', 'Go Behind the scenes & Behind the screams at the nations #1 rated Haunted Attraction.  Join the Cast & Crew of Hellscream Haunted House.', '', 0, 0, 0, 1, 9, NULL, 0, 0, '', '', '2017-09-01 11:05:25', '2018-01-14 02:54:48', 'inherit', 0, 0, 0, '', NULL, NULL),
(825, 35, 0, 'THE DRINK With Hillary Harris', 'Travel the nation  on a spirited journey featuring incredible Bars, Brewers, Distillers, Wineries and more!', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_825.jpg', 0, 0, 0, 1, 12, NULL, 0, 0, '', '', '2017-09-01 11:05:39', '2018-01-14 02:54:40', 'inherit', 0, 0, 0, 'https://prolivestream.imgix.net/banners/channel_35_tvapp_playlist_825.jpg', NULL, NULL),
(826, 35, 0, 'LOST IN AMERICA', 'Join Garrett Coon as he Travels the Nation rediscovering lost American History.', '', 0, 0, 0, 1, 17, NULL, 0, 0, '', '', '2017-09-01 11:08:01', '2018-01-08 22:47:51', 'inherit', 0, 0, 0, '', NULL, NULL),
(827, 35, 0, 'The PUPPET SHOW', '', '', 0, 0, 0, 0, 14, NULL, 0, 0, '', '', '2017-09-01 11:08:14', '2018-01-08 22:47:51', 'inherit', 0, 0, 0, '', NULL, NULL),
(828, 35, 0, 'THE HEART OF ADDICTION', 'Stories of The COREVISION Recovery Program.', '', 0, 0, 0, 0, 11, NULL, 0, 0, '', '', '2017-09-01 11:08:27', '2018-01-14 02:54:48', 'inherit', 0, 0, 0, '', NULL, NULL),
(829, 35, 0, 'OLD COLORADO CITY', '"Where History Comes to Life!" Enjoy this in depth series about this historic slice of Colorado Springs!', '', 0, 0, 0, 1, 28, NULL, 0, 0, '', '', '2017-09-01 11:08:41', '2018-01-08 22:47:46', 'inherit', 0, 0, 0, '', NULL, NULL),
(830, 35, 0, 'BREAK the SILENCE', 'Stories of those who''ve lost loved ones from Domestic Violence.', '', 0, 0, 0, 0, 13, NULL, 0, 0, '', '', '2017-09-01 11:08:53', '2018-01-14 02:54:40', 'inherit', 0, 0, 0, '', NULL, NULL),
(831, 35, 0, 'MAGIC TOWN "The Michael Garman Story"', 'Experience the journey of one of America''s greatest artists.', '', 0, 0, 0, 0, 22, NULL, 0, 0, '', '', '2017-09-01 11:09:14', '2018-01-08 22:47:46', 'inherit', 0, 0, 0, '', NULL, NULL),
(832, 35, 0, 'WITH SIGN IN HAND', 'Garrett Coon interviews the homeless living with sign in hand.', '', 0, 0, 0, 0, 15, NULL, 0, 0, '', '', '2017-09-01 11:09:28', '2018-01-08 22:47:51', 'inherit', 0, 0, 0, '', NULL, NULL),
(842, 80, 0, 'Featured', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_80_tvapp_playlist_842.jpg', 0, 0, 0, 0, 2, NULL, 0, 0, '', '', '2017-09-04 09:53:44', '2017-11-28 19:16:24', 'inherit', 1, 0, 0, '', NULL, NULL),
(843, 80, 0, 'Most Recent', '', '', 0, 0, 0, 0, 0, NULL, 0, 0, '', '', '2017-09-04 09:54:06', '2017-11-28 19:16:24', 'inherit', 0, 0, 842, '', NULL, NULL),
(882, 74, 0, 'Watch Live Test', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_74_tvapp_playlist_882.jpg', 0, 0, 6, 0, 2, NULL, 0, 0, 'http://104.131.157.125/afinarte_watch_live.m3u8', '', '2017-09-06 03:30:41', '2017-11-28 19:06:54', 'inherit', 1, 0, 0, '', NULL, NULL),
(883, 74, 0, 'Featured', '', '', 0, 0, 0, 0, 0, NULL, 0, 0, '', '', '2017-09-06 04:53:35', '2017-11-28 19:06:54', 'inherit', 0, 0, 0, '', NULL, NULL),
(892, 35, 0, 'WRESTLING WITH GHOSTS', 'A paranormal investigator loses his team to a "demonic" attack, then hires 3 smartass pro wrestlers to protect him on future investigations.', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_892.jpg', 0, 0, 0, 0, 43, NULL, 0, 0, '', '', '2017-09-07 23:19:38', '2018-01-08 22:47:31', 'inherit', 1, 0, 0, '', NULL, NULL),
(893, 35, 0, 'WRESTLING WITH GHOSTS', 'A paranormal investigator loses his team to a "demonic" attack, then hires 3 smartass pro wrestlers to protect him on future investigations.', 'http://prolivestream.s3.amazonaws.com/logos/channel_35_tvapp_playlist_893.jpg', 0, 0, 0, 0, 23, NULL, 0, 0, '', '', '2017-09-07 23:20:08', '2018-01-08 22:47:46', 'inherit', 0, 0, 0, '', NULL, NULL),
(911, 52, 0, 'Watch Live', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_52_tvapp_playlist_911.jpg', 0, 0, 6, 0, 61, NULL, 2, 0, 'http://studio.huntchannel.tv:8081/live/playout/playlist.m3u8', '', '2017-09-09 07:43:03', '2017-10-05 12:13:04', 'inherit', 1, 0, 0, '', NULL, NULL),
(952, 80, 0, 'Charles Darwin''s Marine Iguanas [720p]', '', '', 0, 0, 0, 0, 3, NULL, 0, 0, '', '', '0000-00-00 00:00:00', '2017-11-28 19:16:24', 'inherit', 1, 1, 0, '', NULL, NULL),
(957, 52, 0, 'Whitetail Edge', '', '', 0, 0, 0, 0, 57, NULL, 0, 0, '', '', '2017-09-11 20:30:18', '2017-10-05 12:13:04', 'inherit', 0, 0, 422, '', NULL, NULL),
(958, 52, 0, 'Trapping Time TV', '', '', 0, 0, 0, 0, 56, NULL, 0, 0, '', '', '2017-09-11 20:31:06', '2017-10-05 12:13:04', 'inherit', 0, 0, 421, '', NULL, NULL),
(959, 52, 0, 'Silent Draw Outdoors', '', '', 0, 0, 0, 0, 46, NULL, 0, 0, '', '', '2017-09-11 20:31:32', '2017-10-05 12:13:04', 'inherit', 0, 0, 420, '', NULL, NULL),
(960, 52, 0, 'Whitetail Journey', '', '', 0, 0, 0, 0, 60, NULL, 0, 0, '', '', '2017-09-11 20:44:50', '2017-10-05 12:13:04', 'inherit', 0, 0, 419, '', NULL, NULL),
(961, 52, 0, 'Team NXG', '', '', 0, 0, 0, 0, 51, NULL, 0, 0, '', '', '2017-09-11 20:46:09', '2017-10-05 12:13:04', 'inherit', 0, 0, 418, '', NULL, NULL),
(962, 52, 0, 'Big "E" TV', '', '', 0, 0, 0, 0, 9, NULL, 0, 0, '', '', '2017-09-11 20:46:43', '2017-10-05 12:13:04', 'inherit', 0, 0, 417, '', NULL, NULL),
(963, 52, 0, 'Wild Bout Hunting', '', '', 0, 0, 0, 0, 59, NULL, 0, 0, '', '', '2017-09-11 20:50:35', '2017-10-05 12:13:04', 'inherit', 0, 0, 415, '', NULL, NULL),
(964, 52, 0, 'Total Outdoor Pursuit', '', '', 0, 0, 0, 0, 55, NULL, 0, 0, '', '', '2017-09-11 20:51:04', '2017-10-05 12:13:04', 'inherit', 0, 0, 414, '', NULL, NULL),
(965, 52, 0, 'Top Ten Percent', '', '', 0, 0, 0, 0, 54, NULL, 0, 0, '', '', '2017-09-11 20:51:28', '2017-10-05 12:13:04', 'inherit', 0, 0, 413, '', NULL, NULL),
(966, 52, 0, 'The Outdoor Experience', '', '', 0, 0, 0, 0, 53, NULL, 0, 0, '', '', '2017-09-11 20:52:01', '2017-10-05 12:13:04', 'inherit', 0, 0, 412, '', NULL, NULL),
(967, 52, 0, 'The Bearded Buck', '', '', 0, 0, 0, 0, 52, NULL, 0, 0, '', '', '2017-09-11 20:52:43', '2017-10-05 12:13:04', 'inherit', 0, 0, 411, '', NULL, NULL),
(968, 52, 0, 'Swift Waters', '', '', 0, 0, 0, 0, 50, NULL, 0, 0, '', '', '2017-09-11 20:53:04', '2017-10-05 12:13:04', 'inherit', 0, 0, 410, '', NULL, NULL),
(969, 52, 0, 'Spook Nation', '', '', 0, 0, 0, 0, 49, NULL, 0, 0, '', '', '2017-09-11 20:53:30', '2017-10-05 12:13:04', 'inherit', 0, 0, 409, '', NULL, NULL),
(970, 52, 0, 'Southern Drawl Outdoors', '', '', 0, 0, 0, 0, 47, NULL, 0, 0, '', '', '2017-09-11 20:54:07', '2017-10-05 12:13:04', 'inherit', 0, 0, 408, '', NULL, NULL),
(971, 52, 0, 'Southern Drawl Outdoors', '', '', 0, 0, 0, 0, 48, NULL, 0, 0, '', '', '2017-09-11 20:54:13', '2017-10-05 12:13:04', 'inherit', 0, 0, 408, '', NULL, NULL),
(972, 52, 0, 'Shoot Straight TV', '', '', 0, 0, 0, 0, 45, NULL, 0, 0, '', '', '2017-09-11 20:54:57', '2017-10-05 12:13:04', 'inherit', 0, 0, 407, '', NULL, NULL),
(973, 52, 0, 'Real South Hunting', '', '', 0, 0, 0, 0, 44, NULL, 0, 0, '', '', '2017-09-11 20:55:28', '2017-10-05 12:13:04', 'inherit', 0, 0, 406, '', NULL, NULL),
(974, 52, 0, 'PSE The Wild Outdoors', '', '', 0, 0, 0, 0, 43, NULL, 0, 0, '', '', '2017-09-11 20:55:52', '2017-10-05 12:13:04', 'inherit', 0, 0, 405, '', NULL, NULL),
(975, 52, 0, 'Outdoor Junkies', '', '', 0, 0, 0, 0, 41, NULL, 0, 0, '', '', '2017-09-11 20:56:19', '2017-10-05 12:13:04', 'inherit', 0, 0, 404, '', NULL, NULL),
(976, 52, 0, 'Outdoor Junkies', '', '', 0, 0, 0, 0, 42, NULL, 0, 0, '', '', '2017-09-11 20:56:31', '2017-10-05 12:13:04', 'inherit', 0, 0, 404, '', NULL, NULL),
(977, 52, 0, 'Outdoor Edge''s The Great Outdoors', '', '', 0, 0, 0, 0, 40, NULL, 0, 0, '', '', '2017-09-11 20:57:06', '2017-10-05 12:13:04', 'inherit', 0, 0, 403, '', NULL, NULL),
(978, 52, 0, 'Open Season TV', '', '', 0, 0, 0, 0, 39, NULL, 0, 0, '', '', '2017-09-11 20:57:33', '2017-10-05 12:13:04', 'inherit', 0, 0, 402, '', NULL, NULL),
(979, 52, 0, 'Most Wanted List', '', '', 0, 0, 0, 0, 38, NULL, 0, 0, '', '', '2017-09-11 20:57:58', '2017-10-05 12:13:04', 'inherit', 0, 0, 401, '', NULL, NULL),
(980, 52, 0, 'Monster Trophy Whitetails', '', '', 0, 0, 0, 0, 36, NULL, 0, 0, '', '', '2017-09-11 20:58:43', '2017-10-05 12:13:04', 'inherit', 0, 0, 400, '', NULL, NULL),
(981, 52, 0, 'Mass Pursuit TV', '', '', 0, 0, 0, 0, 35, NULL, 0, 0, '', '', '2017-09-11 21:00:05', '2017-10-05 12:13:04', 'inherit', 0, 0, 399, '', NULL, NULL),
(982, 52, 0, 'Maineiac Outdoors', '', '', 0, 0, 0, 0, 37, NULL, 0, 0, '', '', '2017-09-11 21:05:13', '2017-10-05 12:13:04', 'inherit', 0, 0, 398, '', NULL, NULL),
(983, 52, 0, 'Love of the Hunt', '', '', 0, 0, 0, 0, 34, NULL, 0, 0, '', '', '2017-09-11 21:05:38', '2017-10-05 12:13:04', 'inherit', 0, 0, 397, '', NULL, NULL),
(984, 52, 0, 'Keepin'' It In the Ozarks', '', '', 0, 0, 0, 0, 33, NULL, 0, 0, '', '', '2017-09-11 21:06:15', '2017-10-05 12:13:04', 'inherit', 0, 0, 396, '', NULL, NULL),
(985, 52, 0, 'Hunting with HECS', '', '', 0, 0, 0, 0, 22, NULL, 0, 0, '', '', '2017-09-14 01:43:39', '2017-10-05 12:13:04', 'inherit', 0, 0, 395, '', NULL, NULL),
(986, 52, 0, 'Grace Camo & Lace', '', '', 0, 0, 0, 0, 21, NULL, 0, 0, '', '', '2017-09-14 01:45:50', '2017-10-05 12:13:04', 'inherit', 0, 0, 394, '', NULL, NULL),
(987, 52, 0, 'Frontier Unlimited', '', '', 0, 0, 0, 0, 19, NULL, 0, 0, '', '', '2017-09-14 01:48:14', '2017-10-05 12:13:04', 'inherit', 0, 0, 393, '', NULL, NULL),
(988, 52, 0, 'Dirt Road Outdoors', '', '', 0, 0, 0, 0, 17, NULL, 0, 0, '', '', '2017-09-14 01:48:51', '2017-10-05 12:13:04', 'inherit', 0, 0, 392, '', NULL, NULL),
(989, 52, 0, 'DeerHunterFan.com TV', '', '', 0, 0, 0, 0, 16, NULL, 0, 0, '', '', '2017-09-14 01:49:57', '2017-10-05 12:13:04', 'inherit', 0, 0, 391, '', NULL, NULL),
(990, 52, 0, 'ConQuest 200', '', '', 0, 0, 0, 0, 15, NULL, 0, 0, '', '', '2017-09-14 01:50:22', '2017-10-05 12:13:04', 'inherit', 0, 0, 390, '', NULL, NULL),
(991, 52, 0, 'Boomtime with Bob & Archie', '', '', 0, 0, 0, 0, 13, NULL, 0, 0, '', '', '2017-09-14 01:51:06', '2017-10-05 12:13:04', 'inherit', 0, 0, 389, '', NULL, NULL),
(992, 52, 0, 'Bird Dog Wars', '', '', 0, 0, 0, 0, 10, NULL, 0, 0, '', '', '2017-09-14 02:10:38', '2017-10-05 12:13:04', 'inherit', 0, 0, 388, '', NULL, NULL),
(993, 52, 0, 'Bible Belt Outdoors', '', '', 0, 0, 0, 0, 8, NULL, 0, 0, '', '', '2017-09-14 02:11:08', '2017-10-05 12:13:04', 'inherit', 0, 0, 387, '', NULL, NULL),
(994, 52, 0, 'American Valor', '', '', 0, 0, 0, 0, 3, NULL, 0, 0, '', '', '2017-09-14 02:11:36', '2017-10-05 12:13:04', 'inherit', 0, 0, 386, '', NULL, NULL),
(996, 52, 0, 'Backwoods Life', '', '', 0, 0, 0, 0, 6, NULL, 0, 0, '', '', '2017-09-14 02:13:33', '2017-10-05 12:13:04', 'inherit', 0, 0, 336, '', NULL, NULL),
(997, 52, 0, 'Backwoods Heritage', '', '', 0, 0, 0, 0, 7, NULL, 0, 0, '', '', '2017-09-14 02:14:17', '2017-10-05 12:13:04', 'inherit', 0, 0, 335, '', NULL, NULL),
(998, 52, 0, 'All Season Pursuit', '', '', 0, 0, 0, 0, 5, NULL, 0, 0, '', '', '2017-09-14 02:14:51', '2017-10-05 12:13:04', 'inherit', 0, 0, 334, '', NULL, NULL),
(999, 52, 0, 'Aaron Outdoors', '', '', 0, 0, 0, 0, 2, NULL, 0, 0, '', '', '2017-09-14 02:15:30', '2017-10-05 12:13:04', 'inherit', 0, 0, 333, '', NULL, NULL),
(1000, 52, 0, 'Spiritual Outdoor Adventures', '', '', 0, 0, 0, 0, 29, NULL, 0, 0, '', '', '2017-09-14 02:16:41', '2017-10-05 12:13:04', 'inherit', 0, 0, 332, '', NULL, NULL),
(1001, 52, 0, 'Southern Hog Slayers', '', '', 0, 0, 0, 0, 28, NULL, 0, 0, '', '', '2017-09-14 02:18:36', '2017-10-05 12:13:04', 'inherit', 0, 0, 331, '', NULL, NULL),
(1002, 52, 0, 'Blitz TV', '', '', 0, 0, 0, 0, 11, NULL, 0, 0, '', '', '2017-09-14 02:20:05', '2017-10-05 12:13:04', 'inherit', 0, 0, 330, '', NULL, NULL),
(1003, 52, 0, 'Jimmy Houston Outdoors', '', '', 0, 0, 0, 0, 25, NULL, 0, 0, '', '', '2017-09-14 02:20:44', '2017-10-05 12:13:04', 'inherit', 0, 0, 329, '', NULL, NULL),
(1004, 52, 0, 'Team Greene Outdoors', '', '', 0, 0, 0, 0, 31, NULL, 0, 0, '', '', '2017-09-14 02:21:24', '2017-10-05 12:13:04', 'inherit', 0, 0, 328, '', NULL, NULL),
(1005, 52, 0, 'American Outdoors TV', '', '', 0, 0, 0, 0, 4, NULL, 0, 0, '', '', '2017-09-14 02:22:19', '2017-10-05 12:13:04', 'inherit', 0, 0, 327, '', NULL, NULL),
(1006, 52, 0, 'In the Blood', '', '', 0, 0, 0, 0, 23, NULL, 0, 0, '', '', '2017-09-14 02:23:12', '2017-10-05 12:13:04', 'inherit', 0, 0, 326, '', NULL, NULL),
(1007, 52, 0, 'In the Blood', '', '', 0, 0, 0, 0, 24, NULL, 0, 0, '', '', '2017-09-14 02:23:14', '2017-10-05 12:13:04', 'inherit', 0, 0, 326, '', NULL, NULL),
(1008, 52, 0, 'Double Lung Outdoors', '', '', 0, 0, 0, 0, 18, NULL, 0, 0, '', '', '2017-09-14 02:24:09', '2017-10-05 12:13:04', 'inherit', 0, 0, 325, '', NULL, NULL),
(1009, 52, 0, 'Sweet Point Setter Tales', '', '', 0, 0, 0, 0, 30, NULL, 0, 0, '', '', '2017-09-14 02:25:58', '2017-10-05 12:13:04', 'inherit', 0, 0, 324, '', NULL, NULL),
(1010, 52, 0, 'Giving Bank', '', '', 0, 0, 0, 0, 20, NULL, 0, 0, '', '', '2017-09-14 02:27:18', '2017-10-05 12:13:04', 'inherit', 0, 0, 323, '', NULL, NULL),
(1011, 52, 0, 'Keystone Wild Outdoors', '', '', 0, 0, 0, 0, 26, NULL, 0, 0, '', '', '2017-09-14 02:28:22', '2017-10-05 12:13:04', 'inherit', 0, 0, 322, '', NULL, NULL),
(1012, 52, 0, 'Bone Cold TV', '', '', 0, 0, 0, 0, 12, NULL, 0, 0, '', '', '2017-09-14 02:29:12', '2017-10-05 12:13:04', 'inherit', 0, 0, 321, '', NULL, NULL),
(1013, 52, 0, 'Boundless Hunting', '', '', 0, 0, 0, 0, 14, NULL, 0, 0, '', '', '2017-09-14 02:30:08', '2017-10-05 12:13:04', 'inherit', 0, 0, 320, '', NULL, NULL),
(1014, 52, 0, 'Texas Fisherman TV', '', '', 0, 0, 0, 0, 32, NULL, 0, 0, '', '', '2017-09-14 02:31:35', '2017-10-05 12:13:04', 'inherit', 0, 0, 319, '', NULL, NULL),
(1015, 52, 0, 'O''Neill Outside', '', '', 0, 0, 0, 0, 27, NULL, 0, 0, '', '', '2017-09-14 02:32:16', '2017-10-05 12:13:04', 'inherit', 0, 0, 317, '', NULL, NULL),
(1016, 52, 0, 'Whitetail Faktor', '', '', 0, 0, 0, 0, 58, NULL, 0, 0, '', '', '2017-09-14 02:32:52', '2017-10-05 12:13:04', 'inherit', 0, 0, 316, '', NULL, NULL),
(1024, 82, 0, 'Watch Live', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_82_tvapp_playlist_1024.jpg', 0, 0, 6, 0, 2, NULL, 2, 0, 'http://104.131.157.125/upflow_tv_watch_live_82.m3u8', '', '2017-09-18 02:07:17', '2017-11-29 10:06:22', 'inherit', 1, 0, 432, '', NULL, NULL),
(1026, 77, 0, 'All Videos', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-09-18 06:19:29', '2017-09-18 19:14:37', 'inherit', 0, 0, 427, '', NULL, NULL),
(1027, 17, 0, 'Watch Live', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_17_tvapp_playlist_1027.jpg', 0, 0, 6, 0, 0, NULL, 2, 0, 'http://104.131.157.125/tremtv_watch_live_master_17.m3u8', '', '2017-09-18 08:39:55', '2017-11-28 18:54:37', 'inherit', 1, 0, 115, '', NULL, NULL),
(1035, 77, 0, 'Watch Live Young Hollywood', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_77_tvapp_playlist_1035.jpg', 0, 0, 6, 0, 1, NULL, 2, 0, 'http://165.227.14.170/yh-watch-live-9-27.m3u8', '', '2017-09-18 19:15:42', '2017-10-07 05:27:02', 'inherit', 1, 0, 427, '', NULL, NULL),
(1038, 37, 0, 'Watch Live', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_37_tvapp_playlist_1038.jpg', 0, 0, 6, 0, 1, NULL, 2, 0, 'http://165.227.14.170/ifame_tv_watch_live.m3u8', '', '2017-09-19 06:26:11', '2017-09-30 10:38:05', 'inherit', 1, 0, 366, '', NULL, NULL),
(1040, 75, 0, 'Recently Added', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-09-20 00:01:34', '2017-09-20 00:01:34', 'inherit', 0, 0, 0, '', NULL, NULL),
(1042, 75, 0, 'Featured Videos', '', '', 0, 0, 1, 0, 1, NULL, 0, 0, '', '', '2017-09-20 00:02:55', '2017-09-20 00:03:23', 'inherit', 0, 0, 0, '', NULL, NULL),
(1045, 32, 0, 'Mas Regional Mex', '', '', 0, 0, 6, 0, 5, 596, 2, 0, 'http://165.227.14.170/mas_regional_mex_live.m3u8', '', '2017-09-20 10:59:33', '2017-09-20 11:17:24', 'inherit', 0, 0, 384, '', NULL, NULL),
(1048, 32, 0, 'Mas Comedy', '', '', 0, 0, 6, 0, 1, 596, 2, 0, 'http://165.227.14.170/mascomedy_watch_live.m3u8', '', '2017-09-20 11:01:17', '2017-09-20 11:03:49', 'inherit', 0, 0, 384, '', NULL, NULL),
(1049, 32, 0, 'Mas Food Travel', '', '', 0, 0, 6, 0, 2, 596, 2, 0, 'http://165.227.14.170/masfoodtravel_live.m3u8', '', '2017-09-20 11:03:36', '2017-09-20 11:06:26', 'inherit', 0, 0, 384, '', NULL, NULL),
(1050, 32, 0, 'Mas Pop', '', '', 0, 0, 6, 0, 3, 596, 2, 0, 'http://165.227.14.170/mas_pop_live.m3u8', '', '2017-09-20 11:06:17', '2017-09-20 11:09:55', 'inherit', 0, 0, 384, '', NULL, NULL),
(1051, 32, 0, 'Mas Salsa', '', '', 0, 0, 6, 0, 4, 596, 2, 0, 'http://165.227.14.170/mas_salsa.m3u8', '', '2017-09-20 11:09:36', '2017-09-20 11:09:55', 'inherit', 0, 0, 384, '', NULL, NULL),
(1053, 32, 0, 'Mas Sportes', '', '', 0, 0, 6, 0, 6, 596, 2, 0, 'http://165.227.14.170/Mas_Sportes.m3u8', '', '2017-09-20 11:16:53', '2017-09-20 11:17:24', 'inherit', 0, 0, 384, '', NULL, NULL),
(1069, 86, 0, 'Featured', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_86_tvapp_playlist_1069.jpg', 0, 0, 0, 0, 2, NULL, 0, 0, '', '', '2017-09-27 19:41:18', '2017-12-05 12:09:59', 'inherit', 1, 0, 0, '', NULL, NULL),
(1070, 86, 0, 'All Videos', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-09-27 20:02:51', '2017-09-27 20:02:51', 'inherit', 0, 0, 0, '', NULL, NULL),
(1072, 37, 0, 'Dance Party USA', 'Watch your favorite performers from the biggest TV dance show from 1986-1992', '', 0, 0, 0, 0, 7, NULL, 0, 0, '', '', '2017-09-30 09:45:44', '2017-11-02 03:25:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(1073, 37, 0, 'Dancin On Air', 'Watch eps from the legendary TV dance show from 1981-1992', '', 0, 0, 0, 0, 8, NULL, 0, 0, '', '', '2017-09-30 09:52:38', '2017-11-02 03:25:33', 'inherit', 0, 0, 0, '', NULL, NULL),
(1082, 52, 0, 'Featured', '', '', 0, 0, 1, 0, 631, NULL, 0, 0, '', '', '2017-10-02 23:48:27', '2017-10-05 12:13:04', 'inherit', 1, 0, 0, '', NULL, NULL),
(1083, 77, 0, 'Watch Live Ampd', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_77_tvapp_playlist_1083.jpg', 0, 0, 6, 0, 1, NULL, 2, 0, 'http://165.227.14.170/ampd_master_live.m3u8', '', '2017-10-03 23:21:41', '2017-10-03 23:22:59', 'inherit', 1, 0, 0, '', NULL, NULL),
(1084, 77, 0, 'Watch Live Food Feed', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_77_tvapp_playlist_1084.jpg', 0, 0, 6, 0, 1, NULL, 2, 0, 'http://165.227.14.170/food_feed_master_live_1.m3u8', '', '2017-10-03 23:25:18', '2017-10-03 23:26:45', 'inherit', 1, 0, 0, '', NULL, NULL),
(1093, 77, 0, 'Most Recent', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-10-16 21:24:45', '2017-10-16 21:24:45', 'inherit', 0, 0, 0, '', NULL, NULL),
(1094, 52, 0, 'A Fishing Story', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-10-16 21:29:00', '2017-10-16 21:29:00', 'inherit', 0, 0, 385, '', NULL, NULL),
(1102, 97, 0, 'Wath Live', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-10-19 20:28:12', '2017-10-19 20:28:12', 'inherit', 1, 0, 0, '', NULL, NULL),
(1103, 97, 0, 'All Videos', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-10-19 20:28:22', '2017-10-19 20:28:22', 'inherit', 0, 0, 0, '', NULL, NULL),
(1127, 32, 0, 'Mas Sports', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_32_tvapp_playlist_1127.jpg', 0, 0, 6, 0, 1, 1128, 2, 1, 'http://165.227.14.170/Mas_Deportes.m3u8', '', '2017-10-26 21:47:50', '2017-10-26 22:10:06', 'inherit', 0, 0, 0, '', NULL, NULL),
(1129, 32, 0, 'Mas Sports 2', '', '', 0, 0, 0, 0, 3, 1128, 0, 0, 'http://165.227.14.170/Mas_Deportes.m3u8', '', '2017-10-26 22:01:25', '2017-10-26 22:02:28', 'inherit', 0, 0, 0, '', NULL, NULL),
(1130, 32, 0, 'Numero Uno', 'Numero Uno', '', 0, 0, 6, 0, 4, 1206, 2, 0, 'https://nimble2.prolivestream.tv/master/numerouno/playlist.m3u8', '', '2017-10-27 12:11:48', '2017-11-23 11:03:37', 'inherit', 0, 0, 0, '', NULL, NULL),
(1132, 32, 0, 'Kepadre Radio', 'Kepadre Radio', '', 0, 0, 6, 0, 1, 1206, 2, 0, 'http://138.68.48.112:8081/master/vive/playlist.m3u8', '', '2017-10-27 12:13:05', '2017-11-27 14:11:34', 'inherit', 0, 0, 0, '', NULL, NULL),
(1133, 32, 0, 'Vive', 'Vive', '', 0, 0, 6, 0, 5, 1206, 2, 0, 'https://nimble2.prolivestream.tv/master/vive/playlist.m3u8', '', '2017-10-27 12:13:21', '2017-11-23 11:04:20', 'inherit', 0, 0, 0, '', NULL, NULL),
(1138, 106, 0, 'Channel 1', '', '', 0, 0, 6, 0, 3, 1147, 2, 0, 'https://prolivestream.tv/malimar_live_loop_test.m3u8', '', '2017-11-14 05:53:05', '2017-11-21 04:31:52', 'inherit', 0, 0, 0, '', NULL, NULL),
(1142, 106, 0, 'Channel 2', '', '', 0, 0, 6, 0, 5, NULL, 2, 0, 'https://prolivestream.tv/malimar_live_loop_test.m3u8', '', '2017-11-14 05:54:18', '2017-11-21 04:33:29', 'inherit', 0, 0, 0, '', NULL, NULL),
(1143, 106, 0, 'Channel 3', '', '', 0, 0, 6, 0, 10, NULL, 2, 0, 'https://prolivestream.tv/malimar_live_loop_test.m3u8', '', '2017-11-14 05:54:32', '2017-11-21 04:33:29', 'inherit', 1, 0, 0, '', NULL, NULL),
(1144, 106, 0, 'Channel 4', '', '', 0, 0, 6, 0, 12, NULL, 2, 0, 'https://prolivestream.tv/malimar_live_loop_test.m3u8', '', '2017-11-14 05:55:07', '2017-11-21 04:33:16', 'inherit', 1, 0, 0, '', NULL, NULL),
(1145, 106, 0, 'All  Featured TV Netowrks', '', '', 0, 0, 0, 0, 4, NULL, 0, 0, '', '', '2017-11-14 05:55:46', '2017-11-21 04:33:29', 'inherit', 0, 0, 0, '', NULL, NULL),
(1146, 106, 0, 'Most Recent', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-11-14 05:55:56', '2017-11-21 04:31:43', 'inherit', 0, 0, 0, '', NULL, NULL),
(1147, 106, 0, 'Channels you might like!', '', '', 0, 0, 0, 0, 7, NULL, 0, 0, '', '', '2017-11-14 05:56:17', '2017-11-21 04:33:29', 'inherit', 0, 0, 0, '', NULL, NULL),
(1148, 106, 0, 'All Free Channels', '', '', 0, 0, 0, 0, 16, NULL, 0, 0, '', '', '2017-11-14 05:56:30', '2017-11-21 04:33:16', 'inherit', 0, 0, 0, '', NULL, NULL),
(1149, 106, 0, 'Channel 5', '', '', 0, 0, 6, 0, 13, NULL, 2, 0, 'https://prolivestream.tv/malimar_live_loop_test.m3u8', '', '2017-11-14 05:56:42', '2017-11-21 04:33:16', 'inherit', 1, 0, 0, '', NULL, NULL),
(1150, 106, 0, 'Channel 6', '', '', 0, 0, 6, 0, 9, NULL, 2, 0, 'https://prolivestream.tv/malimar_live_loop_test.m3u8', '', '2017-11-14 05:56:57', '2017-11-21 04:33:29', 'inherit', 1, 0, 0, '', NULL, NULL),
(1151, 106, 0, 'Channel 7', '', '', 0, 0, 6, 0, 15, NULL, 2, 0, 'https://prolivestream.tv/malimar_live_loop_test.m3u8', '', '2017-11-14 05:57:01', '2017-11-21 04:33:16', 'inherit', 1, 0, 0, '', NULL, NULL),
(1152, 106, 0, 'Channel 8', '', '', 0, 0, 6, 0, 14, NULL, 2, 0, 'https://prolivestream.tv/malimar_live_loop_test.m3u8', '', '2017-11-14 05:58:26', '2017-11-21 04:33:16', 'inherit', 1, 0, 0, '', NULL, NULL),
(1153, 106, 0, 'Channel 11', '', '', 0, 0, 6, 0, 1000, 1146, 2, 0, 'https://prolivestream.tv/malimar_live_loop_test.m3u8', '', '2017-11-14 05:58:38', '2017-11-21 04:33:40', 'inherit', 0, 0, 0, '', NULL, NULL),
(1154, 106, 0, 'Channel 10', '', '', 0, 0, 6, 0, 2, 1147, 2, 0, 'https://prolivestream.tv/malimar_live_loop_test.m3u8', '', '2017-11-14 05:58:41', '2017-11-21 04:31:52', 'inherit', 0, 0, 0, '', NULL, NULL),
(1155, 106, 0, 'Channel 12', '', '', 0, 0, 6, 0, -1, 1145, 2, 0, 'https://prolivestream.tv/malimar_live_loop_test.m3u8', '', '2017-11-14 05:58:45', '2017-11-21 04:31:57', 'inherit', 0, 0, 0, '', NULL, NULL),
(1156, 106, 0, 'Channel 13', '', '', 0, 0, 6, 0, 8, NULL, 2, 0, 'https://prolivestream.tv/malimar_live_loop_test.m3u8', '', '2017-11-14 05:58:49', '2017-11-21 04:33:29', 'inherit', 1, 0, 0, '', NULL, NULL),
(1157, 106, 0, 'Channel  14', '', '', 0, 0, 6, 0, 3, 1148, 2, 0, 'https://prolivestream.tv/malimar_live_loop_test.m3u8', '', '2017-11-14 05:58:54', '2017-11-14 06:26:44', 'inherit', 0, 0, 0, '', NULL, NULL),
(1158, 106, 0, 'Channel 15', '', '', 0, 0, 6, 0, 1, 1148, 2, 0, 'https://prolivestream.tv/malimar_live_loop_test.m3u8', '', '2017-11-14 05:58:58', '2017-11-14 06:26:44', 'inherit', 0, 0, 0, '', NULL, NULL),
(1159, 106, 0, 'Channel 16', '', '', 0, 0, 6, 0, 6, NULL, 2, 0, 'https://prolivestream.tv/malimar_live_loop_test.m3u8', '', '2017-11-14 05:59:03', '2017-11-21 04:33:29', 'inherit', 0, 0, 0, '', NULL, NULL),
(1160, 106, 0, 'Channel 18', '', '', 0, 0, 6, 0, 11, NULL, 2, 0, 'https://prolivestream.tv/malimar_live_loop_test.m3u8', '', '2017-11-14 06:03:30', '2017-11-21 04:33:16', 'inherit', 1, 0, 0, '', NULL, NULL),
(1161, 63, 0, 'ONE', 'CS (Communications Satellites)', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_63_tvapp_playlist_1161.jpg', 0, 0, 0, 0, 17, NULL, 2, 0, 'http://120.50.40.69:1935/klive/j014/playlist.m3u8', '', '2017-11-15 20:27:03', '2017-12-11 23:22:47', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_63_tvapp_playlist_1161.jpg', NULL, NULL),
(1167, 82, 0, 'Lifestyles', '', '', 0, 0, 0, 0, 2, 1024, 0, 0, '', '', '2017-11-17 03:25:03', '2017-11-17 03:37:21', 'inherit', 1, 0, 432, '', NULL, NULL),
(1170, 82, 0, 'Ministries', '', 'http://prolivestream.s3.amazonaws.com/logos/channel_82_tvapp_playlist_1170.jpg', 0, 0, 0, 0, 1, 1024, 1, 0, '', '', '2017-11-17 03:31:51', '2017-11-17 03:37:21', 'inherit', 1, 0, 452, '', NULL, NULL),
(1182, 82, 0, 'Decision Ministries International', 'Fred & Irene Hughes\nTeaching the truth, in love and power.', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_82_tvapp_playlist_1182.jpg', 0, 0, 0, 0, 1000, 1243, 0, 0, '', '', '2017-11-17 05:06:37', '2017-11-29 08:33:20', 'free', 0, 0, 431, '', NULL, NULL),
(1198, 35, 0, 'WHAT''S IN MY MOUTH?', 'America''s.. No, The WORLDS Favorite Low Budget Game Show where contestants have to guess what Host Chris Feely and Gigi (Of Supreme Best Day Ever Fame) put into their mouths!', '', 0, 0, 0, 0, 31, NULL, 0, 0, '', '', '2017-11-23 08:14:33', '2018-01-08 22:47:46', 'inherit', 0, 0, 0, '', NULL, NULL),
(1199, 35, 0, 'WHAT''S IN MY MOUTH?', 'America''s... No, The WORLDS Favorite Low Budget Game Show where contestants have to guess what Host Chris Feely and Gigi (Of Supreme Best Day Ever Fame) put into their mouths!', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_35_tvapp_playlist_1199.jpg', 0, 0, 0, 0, 53, NULL, 0, 0, '', '', '2017-11-23 08:18:12', '2018-01-08 22:47:31', 'inherit', 1, 0, 0, '', NULL, NULL),
(1200, 35, 0, 'THE CREEPER FILES', 'Join Kiki & Trans Glitter as they expose online Creepers and online Creeper behavior.', '', 0, 0, 0, 0, 38, NULL, 0, 0, '', '', '2017-11-23 08:19:54', '2018-01-08 22:47:46', 'inherit', 0, 0, 0, '', NULL, NULL),
(1203, 35, 0, 'The CREEPER FILES', 'Join Kiki & TRans Glitter as they expose online creepers & online creeper behavior.', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_35_tvapp_playlist_1203.jpg', 0, 0, 0, 0, 56, NULL, 0, 0, '', '', '2017-11-23 08:23:55', '2018-01-08 22:47:31', 'inherit', 1, 0, 0, '', NULL, NULL),
(1204, 35, 0, 'Mr. QUACKLES LIFE - (TV-MA)', 'Mr. Quackles is an awful Duck... This is an Awful puppet show... Everything about this is just awful.. But you''ll still watch every episode... (MATURE CONTENT)', '', 0, 0, 0, 0, 34, NULL, 0, 0, '', '', '2017-11-23 08:26:55', '2018-01-08 22:47:46', 'inherit', 0, 0, 0, '', NULL, NULL),
(1205, 35, 0, 'Mr. QUACKLES LIFE - (TV-MA)', 'Mr. Quackles is an awful Duck... This is an Awful puppet show... Everything about this is just awful.. But you''ll still watch every episode... (MATURE CONTENT)', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_35_tvapp_playlist_1205.jpg', 0, 0, 0, 0, 59, NULL, 0, 0, '', '', '2017-11-23 08:29:51', '2018-01-07 06:41:55', 'inherit', 1, 0, 0, '', NULL, NULL),
(1207, 32, 0, 'Watch Live Test 1', '', '', 0, 0, 6, 0, 6, 1206, 2, 0, 'https://prolivestream.tv/mas_latino_test.m3u8', '', '2017-11-23 10:50:09', '2017-11-23 11:02:05', 'inherit', 0, 0, 0, '', NULL, NULL),
(1222, 112, 0, '1985 Evil Horde Commercial Masters of the Universe.mp4', '', '', 0, 0, 0, 0, 3, NULL, 0, 0, '', '', '0000-00-00 00:00:00', '2017-11-28 00:50:30', 'inherit', 1, 1, 0, '', NULL, NULL),
(1223, 112, 0, '80''s Lego Commercial Castle Collection.mp4', '', '', 0, 0, 0, 0, 4, NULL, 0, 0, '', '', '0000-00-00 00:00:00', '2017-11-28 00:50:30', 'inherit', 1, 1, 0, '', NULL, NULL);
INSERT INTO `tvapp_playlist` (`id`, `channel_id`, `tvapp_id`, `title`, `description`, `thumbnail_name`, `duration`, `master_looped`, `type`, `level`, `sort_order`, `parent_id`, `layout`, `web_layout`, `stream_url`, `status`, `created_at`, `updated_at`, `viewing`, `shelf`, `video_is`, `playlist_category`, `featured_image_url`, `mobileweb_image_url`, `playlist_type`) VALUES
(1224, 112, 0, '80''S WWE (WWF) TOY COMMERCIAL.mp4', '', '', 0, 0, 0, 0, 5, NULL, 0, 0, '', '', '0000-00-00 00:00:00', '2017-11-28 00:50:30', 'inherit', 1, 1, 0, '', NULL, NULL),
(1230, 112, 0, 'Low One', '', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_112_tvapp_playlist_1230.jpg', 0, 0, 0, 0, 2, NULL, 1, 1, '', '', '2017-11-28 00:31:27', '2017-11-28 00:50:30', 'inherit', 0, 0, 0, '', NULL, NULL),
(1232, 112, 0, 'SW Commercials', '', '', 0, 0, 0, 0, 8, NULL, 0, 0, '', '', '2017-11-28 00:46:03', '2017-11-28 01:06:44', 'inherit', 0, 0, 0, '', NULL, NULL),
(1233, 112, 0, '80s Commercials', '', '', 0, 0, 0, 0, 7, NULL, 0, 0, '', '', '2017-11-28 00:50:23', '2017-11-28 01:06:44', 'inherit', 0, 0, 0, '', NULL, NULL),
(1234, 112, 0, '2600 Commercials', 'Atari', '', 0, 0, 0, 0, 91, NULL, 0, 0, '', '', '2017-11-28 01:06:37', '2017-11-28 01:06:44', 'inherit', 0, 0, 0, '', NULL, NULL),
(1236, 35, 0, 'THE PUPPET SHOW', '(MA-TV) (Season One Coming Soon!) Join Steve "Puppet" Lee in this sketch comedy adventure!', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_35_tvapp_playlist_1236.jpg', 0, 0, 0, 0, 6, NULL, 0, 0, '', '', '2017-11-28 16:55:26', '2018-01-14 02:54:40', 'inherit', 1, 0, 428, 'https://s3.amazonaws.com/aceplayout/banners/channel_35_tvapp_playlist_1236.jpg', NULL, NULL),
(1238, 32, 0, 'Musica Regional Mexicano', '', '', 0, 0, 6, 0, 1, 1237, 2, 0, 'https://prolivestream.tv/Musica_Regional_Mexicano.m3u8', '', '2017-11-29 03:04:12', '2017-11-29 03:06:47', 'inherit', 0, 0, 0, '', NULL, NULL),
(1239, 32, 0, 'Pop Music', '', '', 0, 0, 6, 0, 5, 1237, 2, 0, 'https://prolivestream.tv/Pop_music.m3u8', '', '2017-11-29 03:06:17', '2017-11-29 03:12:16', 'inherit', 0, 0, 0, '', NULL, NULL),
(1240, 32, 0, 'Viajes y Comida', '', '', 0, 0, 6, 0, 2, 1237, 2, 0, 'https://prolivestream.global.ssl.fastly.net/Viajes_y_Comida.m3u8', '', '2017-11-29 03:07:13', '2017-11-30 07:00:05', 'inherit', 0, 0, 0, '', NULL, NULL),
(1241, 32, 0, 'Desportes', '', '', 0, 0, 0, 0, 4, 1237, 0, 0, 'https://prolivestream.tv/Desportes.m3u8', '', '2017-11-29 03:07:58', '2017-11-29 03:12:16', 'inherit', 0, 0, 0, '', NULL, NULL),
(1242, 32, 0, 'Comida', '', '', 0, 0, 0, 0, 3, 1237, 0, 0, 'https://prolivestream.tv/Comedia.m3u8', '', '2017-11-29 03:08:41', '2017-11-29 03:12:16', 'inherit', 0, 0, 0, '', NULL, NULL),
(1243, 82, 0, 'Decision Ministries International', 'Fred & Irene Hughes\nTeaching the truth, in love and power.', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_82_tvapp_playlist_1243.jpg', 0, 0, 0, 0, 1000, 1246, 0, 0, '', '', '2017-11-29 07:31:55', '2017-11-29 09:28:34', 'free', 0, 0, 431, '', NULL, NULL),
(1244, 82, 0, 'Grace Thru Faith Fellowship', 'A Great Church Located in Dumas, Texas', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_82_tvapp_playlist_1244.jpg', 0, 0, 0, 0, 1, 1182, 0, 0, '', '', '2017-11-29 07:48:22', '2017-11-29 07:58:33', 'free', 0, 0, 431, '', NULL, NULL),
(1245, 82, 0, 'Grace Thru Faith Fellowship', 'A Great Church Located in Dumas, Texas', '', 0, 0, 0, 0, 3, 1182, 0, 0, '', '', '2017-11-29 07:57:38', '2017-11-29 07:58:39', 'free', 0, 0, 460, '', NULL, NULL),
(1247, 82, 0, 'Ministries', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-11-29 10:03:02', '2017-11-29 10:06:15', 'inherit', 0, 0, 431, '', NULL, NULL),
(1248, 82, 0, 'Faith Builders', '', '', 0, 0, 0, 0, 6, NULL, 0, 0, '', '', '2017-11-29 10:04:03', '2017-12-01 04:53:35', 'inherit', 0, 0, 430, '', NULL, NULL),
(1249, 82, 0, 'Lifestyles', '', '', 0, 0, 0, 0, 4, NULL, 0, 0, '', '', '2017-11-29 10:04:33', '2017-12-01 04:53:35', 'inherit', 0, 0, 432, '', NULL, NULL),
(1250, 32, 0, 'Comedia', '', '', 0, 0, 0, 0, 13, NULL, 0, 0, '', '', '2017-11-30 03:09:52', '2018-01-08 14:49:46', 'inherit', 0, 0, 339, '', NULL, NULL),
(1251, 32, 0, 'Documentales', '', '', 0, 0, 0, 0, 5, NULL, 0, 0, '', '', '2017-11-30 03:10:06', '2018-01-08 15:27:56', 'inherit', 0, 0, 314, '', NULL, NULL),
(1252, 32, 0, 'Deportes', '', '', 0, 0, 0, 0, 8, NULL, 0, 0, '', '', '2017-11-30 03:10:30', '2018-01-08 15:27:56', 'inherit', 0, 0, 81, '', NULL, NULL),
(1253, 32, 0, 'Viajes y Comida', '', '', 0, 0, 0, 0, 9, NULL, 0, 0, '', '', '2017-11-30 03:10:46', '2018-01-08 15:27:56', 'inherit', 0, 0, 373, '', NULL, NULL),
(1254, 32, 0, 'Musica Pop', '', '', 0, 0, 0, 0, 10, NULL, 0, 0, '', '', '2017-11-30 03:11:16', '2018-01-08 15:27:56', 'inherit', 0, 0, 372, '', NULL, NULL),
(1255, 32, 0, 'Musica Regional Mexicano', '', '', 0, 0, 0, 0, 12, NULL, 0, 0, '', '', '2017-11-30 03:11:54', '2018-01-08 15:27:56', 'inherit', 0, 0, 371, '', NULL, NULL),
(1256, 32, 0, 'Musica Salsa', '', '', 0, 0, 0, 0, 15, NULL, 0, 0, '', '', '2017-11-30 03:12:22', '2018-01-08 14:48:33', 'inherit', 0, 0, 370, '', NULL, NULL),
(1257, 32, 0, 'Cartoons', '', '', 0, 0, 0, 0, 18, NULL, 0, 0, '', '', '2017-11-30 03:12:35', '2018-01-05 08:48:26', 'inherit', 0, 0, 455, '', NULL, NULL),
(1260, 69, 0, 'Watch Live', '', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_69_tvapp_playlist_1260.jpg', 0, 0, 6, 0, 1, NULL, 2, 0, 'https://prolivestream.tv/dl_watch_live_3.m3u8', '', '2017-12-04 04:00:07', '2017-12-04 04:48:54', 'inherit', 1, 0, 0, '', NULL, NULL),
(1261, 69, 0, 'All Videos', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-12-04 04:03:30', '2017-12-04 04:03:30', 'inherit', 0, 0, 0, '', NULL, NULL),
(1262, 69, 0, 'Featured 1', '', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_69_tvapp_playlist_1262.jpg', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-12-04 04:33:05', '2017-12-04 04:34:05', 'inherit', 1, 0, 0, '', NULL, NULL),
(1264, 35, 0, 'ASY TV BEHIND THE SCENES', 'Take a peek on the other side of the camera at special events, road trips and more!', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_35_tvapp_playlist_1264.jpg', 0, 0, 0, 0, 48, NULL, 0, 0, '', '', '2017-12-05 09:08:14', '2018-01-08 22:47:31', 'inherit', 1, 0, 0, '', NULL, NULL),
(1265, 35, 0, 'ASY TV BEHIND THE SCENES', 'Take a peek on the other side of the camera at special events, road trips and more!', '', 0, 0, 0, 0, 29, NULL, 0, 0, '', '', '2017-12-05 09:19:05', '2018-01-08 22:47:46', 'inherit', 0, 0, 0, '', NULL, NULL),
(1271, 63, 0, 'J Sports 1', 'J Sports 1', '', 0, 0, 0, 0, 2, 1270, 2, 0, 'http://120.50.40.69:1935/klive/j018/playlist.m3u8', '', '2017-12-09 10:49:27', '2017-12-09 10:52:18', 'inherit', 0, 0, 0, '', NULL, NULL),
(1272, 63, 0, 'J Sports 2', 'J Sports 2', '', 0, 0, 0, 0, 3, 1270, 2, 0, 'http://120.50.40.69:1935/klive/j019/playlist.m3u8', '', '2017-12-09 10:49:45', '2017-12-09 10:52:18', 'inherit', 0, 0, 0, '', NULL, NULL),
(1273, 63, 0, 'J Sports 3', 'J Sports 3', '', 0, 0, 0, 0, 4, 1270, 2, 0, 'http://120.50.40.69:1935/klive/j020/playlist.m3u8', '', '2017-12-09 10:49:59', '2017-12-09 10:52:18', 'inherit', 0, 0, 0, '', NULL, NULL),
(1274, 63, 0, 'J Sports 4', 'J Sports 4', '', 0, 0, 0, 0, 5, 1270, 2, 0, 'http://120.50.40.69:1935/klive/j021/playlist.m3u8', '', '2017-12-09 10:50:16', '2017-12-09 10:52:18', 'inherit', 0, 0, 0, '', NULL, NULL),
(1275, 63, 0, 'GOLF NETWORK', 'GOLF NETWORK', '', 0, 0, 0, 0, 6, 1270, 2, 0, 'http://120.50.40.69:1935/klive/j024/playlist.m3u8', '', '2017-12-09 10:50:29', '2017-12-09 10:52:18', 'inherit', 0, 0, 0, '', NULL, NULL),
(1285, 76, 0, 'All Videos', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-12-12 01:23:49', '2017-12-12 01:23:49', 'inherit', 0, 0, 0, '', NULL, NULL),
(1286, 76, 0, 'Watch Live', '', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_76_tvapp_playlist_1286.jpg', 0, 0, 6, 0, 1, NULL, 2, 0, 'https://prolivestream.tv/raw_science_watch_live.m3u8', '', '2017-12-12 01:24:18', '2017-12-12 03:26:06', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_76_tvapp_playlist_1286.jpg', NULL, NULL),
(1288, 86, 0, 'DaCast Videos', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-12-18 09:15:47', '2017-12-18 09:15:47', 'inherit', 1, 0, 0, '', NULL, NULL),
(1290, 116, 0, 'All Videos', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-12-20 02:11:12', '2017-12-20 02:11:12', 'inherit', 0, 0, 0, '', NULL, NULL),
(1291, 116, 0, 'Watch Live', 'Description goes here', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_116_tvapp_playlist_1291.jpg', 0, 0, 0, 0, 1, NULL, 0, 0, 'https://prolivestream.tv/asy_watch_live_1.m3u8', '', '2017-12-20 02:12:08', '2017-12-20 02:12:42', 'inherit', 1, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_116_tvapp_playlist_1291.jpg', NULL, NULL),
(1292, 117, 0, 'Island Weather', 'KITV4 latest weather for Oahu, Hawaii, Maui and Kauai', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_117_tvapp_playlist_1292.jpg', 0, 0, 0, 0, 6, 1295, 2, 0, 'http://www.kitv.com/category/305773/kitv-video-forecast?clienttype=mrss', '', '2017-12-20 03:36:12', '2018-01-02 00:55:23', 'inherit', 0, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_117_tvapp_playlist_1292.jpg', NULL, NULL),
(1293, 117, 0, 'Watch Live KITV Feed', 'KITV4 Nowcast  Watch live news 24/7 for Oahu, Hawaii, Maui and Kauai', 'https://prolivestream.imgix.net/logos-poster/channel_117_tvapp_playlist_1293.jpg', 0, 0, 0, 0, 11, NULL, 2, 0, 'http://w3.cdn.anvato.net/live/manifests/bOP2ERal4PBSO02BF65uADvXvlD0Mzq5/kitv/master.m3u8', '', '2017-12-20 03:37:34', '2018-01-02 00:56:56', 'inherit', 1, 0, 0, 'https://prolivestream.imgix.net/banners/channel_117_tvapp_playlist_1293.jpg', NULL, NULL),
(1294, 117, 0, 'Island Sports', 'KITV4 latest sports for Oahu, Hawaii, Maui and Kauai', 'http://aceplayout.s3.amazonaws.com/logos-poster/channel_117_tvapp_playlist_1294.jpg', 0, 0, 0, 0, 1, 1295, 2, 0, 'http://www.kitv.com/category/306321/sports-video?clienttype=mrss', '', '2017-12-20 06:15:54', '2018-01-02 00:53:39', 'inherit', 0, 0, 0, 'https://s3.amazonaws.com/aceplayout/banners/channel_117_tvapp_playlist_1294.jpg', NULL, NULL),
(1295, 117, 0, 'All Shows', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-12-20 06:16:11', '2018-01-02 00:55:33', 'inherit', 0, 0, 0, 'https://prolivestream.imgix.net/banners/channel_117_tvapp_playlist_1295.jpg', NULL, NULL),
(1305, 83, 0, 'test2 low', '', '', 0, 0, 0, 0, -1, 1303, 0, 0, 'test2', '', '2017-12-20 10:54:58', '2017-12-20 13:25:07', 'inherit', 1, 0, 0, '', NULL, NULL),
(1322, 118, 0, 'Watch Live', '24x7 live news, weather and entertainment', 'https://prolivestream.imgix.net/logos-poster/channel_118_tvapp_playlist_1322.jpg', 0, 0, 0, 0, 2, NULL, 2, 0, 'http://wicu-lh.akamaihd.net/i/WICU_1369@78350/master.m3u8', '', '2017-12-21 03:34:52', '2017-12-21 03:40:50', 'inherit', 1, 0, 0, 'https://prolivestream.imgix.net/banners/channel_118_tvapp_playlist_1322.jpg', NULL, NULL),
(1323, 118, 0, 'All Videos', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-12-21 03:37:26', '2017-12-21 03:37:26', 'inherit', 0, 0, 0, '', NULL, NULL),
(1324, 118, 0, 'Caribbean Weather', '24x7 conditions and forecasts', 'https://prolivestream.imgix.net/logos-poster/channel_118_tvapp_playlist_1324.jpg', 0, 0, 0, 0, 3, 1323, 0, 0, 'http://www.erienewsnow.com/category/211870/ocw525home?clienttype=mrss', '', '2017-12-21 03:37:45', '2017-12-21 04:02:45', 'inherit', 0, 0, 0, 'https://prolivestream.imgix.net/banners/channel_118_tvapp_playlist_1324.jpg', NULL, NULL),
(1325, 118, 0, '100% Caribbean News', 'We bring you news you can count on', 'https://prolivestream.imgix.net/logos-poster/channel_118_tvapp_playlist_1325.jpg', 0, 0, 0, 0, 2, 1323, 0, 0, 'http://www.erienewsnow.com/category/211897/ocwevents?clienttype=mrss', '', '2017-12-21 04:02:01', '2017-12-21 04:09:20', 'inherit', 0, 0, 0, 'https://prolivestream.imgix.net/banners/channel_118_tvapp_playlist_1325.jpg', NULL, NULL),
(1327, 81, 0, 'All Episodes', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-12-21 06:23:31', '2018-01-12 11:03:15', 'inherit', 0, 0, 0, 'https://prolivestream.imgix.net/banners/channel_81_tvapp_playlist_1327.jpg', NULL, NULL),
(1331, 81, 0, 'Cymatics   ', '', '', 0, 0, 0, 0, 1, 1431, 0, 0, '', '', '0000-00-00 00:00:00', '2018-01-08 15:54:12', 'inherit', 1, 1, 0, '', NULL, NULL),
(1332, 81, 0, 'Watch Live 2', '', 'https://prolivestream.imgix.net/logos-poster/channel_81_tvapp_playlist_1332.jpg', 0, 0, 6, 0, 7, NULL, 2, 0, 'http://onairstream.tv:8077/media/hls/master/numero1rtmp.m3u8', '', '2017-12-21 06:32:27', '2018-01-12 01:41:46', 'inherit', 1, 0, 0, 'https://prolivestream.imgix.net/banners/channel_81_tvapp_playlist_1332.jpg', NULL, NULL),
(1369, 47, 0, 'All Videos', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2017-12-27 12:52:23', '2017-12-27 12:52:23', 'inherit', 0, 0, 0, '', NULL, NULL),
(1370, 47, 0, 'Island Weather', 'KITV4 latest weather for Oahu, Hawaii, Maui and Kauai', 'https://prolivestream.imgix.net/logos-poster/channel_47_tvapp_playlist_1370.jpg', 0, 0, 0, 0, 4, 1369, 2, 0, 'http://www.kitv.com/category/305773/kitv-video-forecast?clienttype=mrss', '', '2017-12-27 12:53:13', '2017-12-27 12:57:33', 'inherit', 0, 0, 0, 'https://prolivestream.imgix.net/banners/channel_47_tvapp_playlist_1370.jpg', NULL, NULL),
(1371, 47, 0, 'Island News', 'KITV4 latest news for Oahu, Hawaii, Maui and Kauai', 'https://prolivestream.imgix.net/logos-poster/channel_47_tvapp_playlist_1371.jpg', 0, 0, 0, 0, 1, 1369, 2, 0, 'http://www.kitv.com/category/304207/pop-video-top?clienttype=mrss', '', '2017-12-27 12:54:23', '2017-12-27 12:57:07', 'inherit', 0, 0, 0, 'https://prolivestream.imgix.net/banners/channel_47_tvapp_playlist_1371.jpg', NULL, NULL),
(1372, 47, 0, 'Island Sports', 'KITV4 latest sports for Oahu, Hawaii, Maui and Kauai', 'https://prolivestream.imgix.net/logos-poster/channel_47_tvapp_playlist_1372.jpg', 0, 0, 0, 0, 2, 1369, 2, 0, 'http://www.kitv.com/category/306321/sports-video?clienttype=mrss', '', '2017-12-27 12:55:54', '2017-12-27 12:57:22', 'inherit', 0, 0, 0, 'https://prolivestream.imgix.net/banners/channel_47_tvapp_playlist_1372.jpg', NULL, NULL),
(1383, 117, 0, 'Surfing', 'Go surfing with KITV4', 'https://prolivestream.imgix.net/logos-poster/channel_117_tvapp_playlist_1383.jpg', 0, 0, 0, 0, 4, 1295, 0, 0, 'http://www.kitv.com/category/310983/surf-video-for-surf-page?clienttype=mrss', '', '2018-01-02 00:52:53', '2018-01-02 00:56:02', 'inherit', 0, 0, 0, 'https://prolivestream.imgix.net/banners/channel_117_tvapp_playlist_1383.jpg', NULL, NULL),
(1384, 117, 0, 'Hokulea', 'Voyage around the world!', 'https://prolivestream.imgix.net/logos-poster/channel_117_tvapp_playlist_1384.jpg', 0, 0, 0, 0, 2, 1295, 0, 0, 'http://www.kitv.com/category/313574/hokulea-videos?clienttype=mrss', '', '2018-01-02 00:54:13', '2018-01-02 00:56:02', 'inherit', 0, 0, 0, 'https://prolivestream.imgix.net/banners/channel_117_tvapp_playlist_1384.jpg', NULL, NULL),
(1385, 117, 0, 'Pearl Harbor 75 Years', 'Remembering Pearl Harbor', 'https://prolivestream.imgix.net/logos-poster/channel_117_tvapp_playlist_1385.jpg', 0, 0, 0, 0, 3, 1295, 0, 0, 'http://www.kitv.com/category/319476/pearl-75th-videos?clienttype=mrss', '', '2018-01-02 00:55:16', '2018-01-02 00:58:48', 'inherit', 0, 0, 0, 'https://prolivestream.imgix.net/banners/channel_117_tvapp_playlist_1385.jpg', NULL, NULL),
(1386, 114, 0, 'Numero Uno', '', 'https://prolivestream.imgix.net/logos-poster/channel_114_tvapp_playlist_1386.jpg', 0, 0, 0, 0, 2, NULL, 2, 0, 'https://nimble2.prolivestream.tv/master/numerouno/playlist.m3u8', '', '2018-01-02 02:12:35', '2018-01-02 02:38:09', 'inherit', 1, 0, 0, 'https://prolivestream.imgix.net/banners/channel_114_tvapp_playlist_1386.jpg', NULL, NULL),
(1388, 114, 0, 'La Plebada', '', 'https://prolivestream.imgix.net/logos-poster/channel_114_tvapp_playlist_1388.jpg', 0, 0, 0, 0, 4, 1389, 0, 0, 'http://199.217.118.97:9302/stream.mp3', '', '2018-01-02 02:14:06', '2018-01-02 02:38:08', 'inherit', 0, 0, 0, 'https://prolivestream.imgix.net/banners/channel_114_tvapp_playlist_1388.jpg', NULL, NULL),
(1389, 114, 0, 'All Radio Stations', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2018-01-02 02:14:45', '2018-01-02 02:38:09', 'inherit', 0, 0, 0, '', NULL, NULL),
(1390, 114, 0, 'La Número 1', '', 'https://prolivestream.imgix.net/logos-poster/channel_114_tvapp_playlist_1390.jpg', 0, 0, 0, 0, 3, NULL, 0, 0, 'http://199.217.118.97:9980/stream.mp3', '', '2018-01-02 02:16:55', '2018-01-02 02:38:09', 'inherit', 1, 0, 0, 'https://prolivestream.imgix.net/banners/channel_114_tvapp_playlist_1390.jpg', NULL, NULL),
(1391, 114, 0, 'Pop Station', '', 'https://prolivestream.imgix.net/logos-poster/channel_114_tvapp_playlist_1391.jpg', 0, 0, 0, 0, 4, NULL, 0, 0, 'http://199.217.118.97:9300/stream.mp3', '', '2018-01-02 02:17:25', '2018-01-02 02:38:09', 'inherit', 1, 0, 0, 'https://prolivestream.imgix.net/banners/channel_114_tvapp_playlist_1391.jpg', NULL, NULL),
(1394, 114, 0, 'Kepadre radio', '', '', 0, 0, 0, 0, 6, NULL, 0, 0, 'http://199.217.118.97:9998/stream.mp3', '', '2018-01-02 02:18:35', '2018-01-02 02:38:09', 'inherit', 1, 0, 0, 'https://prolivestream.imgix.net/banners/channel_114_tvapp_playlist_1394.jpg', NULL, NULL),
(1395, 114, 0, 'Christian Station', '', 'https://prolivestream.imgix.net/logos-poster/channel_114_tvapp_playlist_1395.jpg', 0, 0, 0, 0, 2, 1389, 0, 0, 'http://199.217.118.97:9306/stream.mp3', '', '2018-01-02 02:20:11', '2018-01-02 02:38:08', 'inherit', 0, 0, 0, 'https://prolivestream.imgix.net/banners/channel_114_tvapp_playlist_1395.jpg', NULL, NULL),
(1396, 114, 0, 'Tropical Station', '', 'https://prolivestream.imgix.net/logos-poster/channel_114_tvapp_playlist_1396.jpg', 0, 0, 0, 0, 3, 1389, 0, 0, 'http://199.217.118.97:9308/stream.mp3', '', '2018-01-02 02:20:45', '2018-01-02 02:38:08', 'inherit', 0, 0, 0, 'https://prolivestream.imgix.net/banners/channel_114_tvapp_playlist_1396.jpg', NULL, NULL),
(1398, 32, 0, 'KePadre Radio', 'KePadre Radio is an Internet radio station based on the Salinas website that reproduces the Latin Jazz music genre.', 'https://prolivestream.imgix.net/logos-poster/channel_32_tvapp_playlist_1398.jpg', 0, 0, 6, 0, 11, NULL, 2, 0, 'http://onairstream.tv:8077/media/hls/master/kepadre-audio.m3u8', '', '2018-01-02 03:32:04', '2018-01-08 15:27:56', 'inherit', 1, 0, 0, 'https://prolivestream.imgix.net/banners/channel_32_tvapp_playlist_1398.jpg', NULL, NULL),
(1399, 32, 0, 'Christian Radio', 'Christian Radio', 'https://prolivestream.imgix.net/logos-poster/channel_32_tvapp_playlist_1399.jpg', 0, 0, 6, 0, 17, NULL, 2, 0, 'http://onairstream.tv:8077/media/hls/master/christian-station.m3u8', '', '2018-01-02 03:32:50', '2018-01-05 08:48:26', 'inherit', 1, 0, 0, 'https://prolivestream.imgix.net/banners/channel_32_tvapp_playlist_1399.jpg', NULL, NULL),
(1400, 32, 0, 'Tropical Radio', 'Tropical Radio', 'https://prolivestream.imgix.net/logos-poster/channel_32_tvapp_playlist_1400.jpg', 0, 0, 6, 0, 6, NULL, 2, 0, 'http://onairstream.tv:8077/media/hls/master/tropical-station.m3u8', '', '2018-01-02 03:35:01', '2018-01-08 15:27:56', 'inherit', 1, 0, 0, 'https://prolivestream.imgix.net/banners/channel_32_tvapp_playlist_1400.jpg', NULL, NULL),
(1401, 32, 0, 'La Plebada Radio', 'La Plebada Network - Lo mejor de la música banda en español.', 'https://prolivestream.imgix.net/logos-poster/channel_32_tvapp_playlist_1401.jpg', 0, 0, 6, 0, 7, NULL, 2, 0, 'http://onairstream.tv:8077/media/hls/master/La_Plebada.m3u8', '', '2018-01-02 03:35:55', '2018-01-08 15:27:56', 'inherit', 1, 0, 0, 'https://prolivestream.imgix.net/banners/channel_32_tvapp_playlist_1401.jpg', NULL, NULL),
(1403, 32, 0, 'La Numero Uno Radio7', '', 'https://prolivestream.imgix.net/logos-poster/channel_32_tvapp_playlist_1403.jpg', 0, 0, 0, 0, 16, NULL, 2, 0, 'https://prolivestream.tv/raw_science_watch_live_2.m3u8', '', '2018-01-02 04:09:48', '2018-01-08 14:48:33', 'inherit', 1, 0, 0, 'https://prolivestream.imgix.net/banners/channel_32_tvapp_playlist_1403.jpg', NULL, NULL),
(1404, 32, 0, 'Inspiracion', '', '', 0, 0, 0, 0, 19, NULL, 0, 0, '', '', '2018-01-02 05:15:26', '2018-01-05 08:48:26', 'inherit', 0, 0, 0, 'https://prolivestream.imgix.net/banners/channel_32_tvapp_playlist_1404.jpg', NULL, NULL),
(1405, 32, 0, 'Pop Music Radio', 'Pop Music', 'https://prolivestream.imgix.net/logos-poster/channel_32_tvapp_playlist_1405.jpg', 0, 0, 6, 0, 14, NULL, 2, 0, 'http://onairstream.tv:8077/media/hls/master/popstation.m3u8', '', '2018-01-02 05:22:03', '2018-01-08 14:49:46', 'inherit', 1, 0, 0, 'https://prolivestream.imgix.net/banners/channel_32_tvapp_playlist_1405.jpg', NULL, NULL),
(1408, 35, 0, 'LIVESTREAM 24/7', 'Watch the Best of ASY TV 24/7 Right Here!', 'https://prolivestream.imgix.net/logos-poster/channel_35_tvapp_playlist_1408.jpg', 0, 0, 6, 0, 1, NULL, 2, 0, 'https://prolivestream.tv/VOD_Playlist_test.m3u8', '', '2018-01-03 07:19:51', '2018-01-04 00:46:36', 'inherit', 1, 0, 0, 'https://prolivestream.imgix.net/banners/channel_35_tvapp_playlist_1408.jpg', NULL, NULL),
(1409, 32, 0, 'New Low shelf Test', '', '', 0, 0, 0, 0, 20, NULL, 0, 0, '', '', '2018-01-04 06:16:22', '2018-01-05 08:48:26', 'inherit', 0, 0, 0, '', NULL, NULL),
(1412, 32, 0, 'Radio', '', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2018-01-05 00:24:42', '2018-01-08 15:27:51', 'inherit', 0, 0, 0, '', NULL, NULL),
(1413, 32, 0, 'Podcasts', '', '', 0, 0, 0, 0, 2, NULL, 0, 0, '', '', '2018-01-05 00:24:56', '2018-01-08 15:27:56', 'inherit', 0, 0, 0, 'https://prolivestream.imgix.net/banners/channel_32_tvapp_playlist_1413.jpg', NULL, NULL),
(1414, 32, 0, 'Numero Uno Radio', '', '', 0, 0, 0, 0, 1, 1412, 0, 0, 'https://nimble2.prolivestream.tv/master/numerouno/playlist.m3u8', '', '2018-01-05 00:25:23', '2018-01-11 05:12:02', 'inherit', 0, 0, 0, 'https://prolivestream.imgix.net/banners/channel_32_tvapp_playlist_1414.jpg', NULL, NULL),
(1420, 32, 0, 'Comedia', '', '', 0, 0, 0, 0, 3, NULL, 0, 0, '', '', '2018-01-05 00:39:23', '2018-01-08 15:27:56', 'inherit', 0, 0, 0, '', NULL, NULL),
(1423, 35, 0, 'THE ASY MUSIC VIDEO MIX', 'Music Videos from artists all around the world! All Genre''s, All Awesome! Look for new Videos DAILY!', 'https://prolivestream.imgix.net/logos-poster/channel_35_tvapp_playlist_1423.jpg', 0, 0, 0, 0, 58, NULL, 0, 0, '', '', '2018-01-07 05:01:37', '2018-01-08 22:47:31', 'inherit', 1, 0, 0, 'https://prolivestream.imgix.net/banners/channel_35_tvapp_playlist_1423.jpg', NULL, NULL),
(1425, 35, 0, 'THE ASY MUSIC VIDEO MIX', '', 'https://prolivestream.imgix.net/logos-poster/channel_35_tvapp_playlist_1425.jpg', 0, 0, 0, 0, 36, NULL, 0, 0, '', '', '2018-01-07 06:40:56', '2018-01-08 22:47:46', 'inherit', 0, 0, 0, 'https://prolivestream.imgix.net/banners/channel_35_tvapp_playlist_1425.jpg', NULL, NULL),
(1442, 81, 0, 'Ants IV', '', '', 0, 0, 0, 0, 2, 1431, 0, 0, '', '', '0000-00-00 00:00:00', '2018-01-09 10:28:41', 'inherit', 1, 1, 0, '', NULL, NULL),
(1443, 81, 0, 'Ants V   ', '', '', 0, 0, 0, 0, 6, 1432, 0, 0, '', '', '0000-00-00 00:00:00', '2018-01-08 15:54:49', 'inherit', 1, 1, 0, '', NULL, NULL),
(1462, 81, 0, 'testtt', '', '', 0, 0, 0, 0, 4, 1431, 0, 0, '', '', '2018-01-08 15:53:20', '2018-01-08 16:27:23', 'inherit', 1, 0, 0, '', NULL, NULL),
(1528, 32, 0, 'Kepadre Radio', 'Kepadre Radio', '', 0, 0, 0, 0, -1, 1527, 0, 0, 'http://199.217.118.97:9998/stream.mp3', '', '2018-01-11 05:18:29', '2018-01-11 05:18:37', 'inherit', 0, 0, 0, '', NULL, NULL),
(1529, 32, 0, 'Tropical - Radio', '', '', 0, 0, 0, 0, 6, 1412, 2, 0, 'http://199.217.118.97:9308/stream.mp3', '', '2018-01-11 05:24:21', '2018-01-11 05:38:19', 'inherit', 0, 0, 0, 'https://prolivestream.imgix.net/banners/channel_32_tvapp_playlist_1529.jpg', NULL, NULL),
(1531, 32, 0, 'KePadre - Radio', 'KePadre Radio is an Internet radio station based on the Salinas website that reproduces the Latin Jazz music genre.', '', 0, 0, 0, 0, 2, 1412, 2, 0, 'http://199.217.118.97:9998/stream.mp3', '', '2018-01-11 05:25:55', '2018-01-11 05:39:07', 'inherit', 0, 0, 0, 'https://prolivestream.imgix.net/banners/channel_32_tvapp_playlist_1531.jpg', NULL, NULL),
(1532, 32, 0, 'Pop Music - Radio', '', '', 0, 0, 0, 0, 3, 1412, 2, 0, 'http://199.217.118.97:9300/stream.mp3', '', '2018-01-11 05:26:46', '2018-01-11 05:39:23', 'inherit', 0, 0, 0, 'https://prolivestream.imgix.net/banners/channel_32_tvapp_playlist_1532.jpg', NULL, NULL),
(1533, 32, 0, 'Christian - Radio', '', '', 0, 0, 0, 0, 7, 1412, 2, 0, 'http://199.217.118.97:9306/stream.mp3', '', '2018-01-11 05:27:22', '2018-01-11 05:40:06', 'inherit', 0, 0, 0, 'https://prolivestream.imgix.net/banners/channel_32_tvapp_playlist_1533.jpg', NULL, NULL),
(1534, 32, 0, 'La Plebada - Radio', 'KePadre Radio is an Internet radio station based on the Salinas website that reproduces the Latin Jazz music genre.', '', 0, 0, 0, 0, 5, 1412, 2, 0, 'http://199.217.118.97:9302/stream.mp3', '', '2018-01-11 05:33:32', '2018-01-11 05:39:41', 'inherit', 0, 0, 0, 'https://prolivestream.imgix.net/banners/channel_32_tvapp_playlist_1534.jpg', NULL, NULL),
(1536, 81, 0, 'Ants I', '', '', 0, 0, 0, 0, 71, NULL, 0, 0, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'inherit', 1, 1, 0, '', NULL, NULL),
(1537, 81, 0, 'Episodes with Narration', 'wonder science tv videos with the added benefit of informative narration and interviews.', '', 0, 0, 0, 0, 1, NULL, 0, 0, '', '', '2018-01-12 11:03:02', '2018-01-12 11:03:02', 'inherit', 0, 0, 0, '', NULL, NULL),
(1538, 81, 0, 'Ants II  ', '', '', 0, 0, 0, 0, 71, NULL, 0, 0, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'inherit', 1, 1, 0, '', NULL, NULL),
(1539, 81, 0, 'Cymatics   ', '', '', 0, 0, 0, 0, 71, NULL, 0, 0, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'inherit', 1, 1, 0, '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tvapp_playlist_platforms`
--

CREATE TABLE IF NOT EXISTS `tvapp_playlist_platforms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tvapp_playlist_id` int(10) unsigned NOT NULL,
  `tvapp_platform_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tvapp_playlist_platforms_tvapp_playlist_id_foreign` (`tvapp_playlist_id`),
  KEY `tvapp_playlist_platforms_tvapp_platform_id_foreign` (`tvapp_platform_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tvapp_video_in_playlist`
--

CREATE TABLE IF NOT EXISTS `tvapp_video_in_playlist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `video_id` int(11) NOT NULL,
  `tvapp_playlist_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tvwebs`
--

CREATE TABLE IF NOT EXISTS `tvwebs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `channel_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `live_stream_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `about_us` text COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tvweb_playlist`
--

CREATE TABLE IF NOT EXISTS `tvweb_playlist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `channel_id` int(11) NOT NULL,
  `tvweb_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `thumbnail_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `duration` int(11) NOT NULL,
  `master_looped` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tvweb_video_in_playlist`
--

CREATE TABLE IF NOT EXISTS `tvweb_video_in_playlist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `video_id` int(11) NOT NULL,
  `tvweb_playlist_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `playout_access` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=95 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `company_id`, `channel_id`, `username`, `password`, `email`, `remember_token`, `token`, `type`, `playout_access`, `created_at`, `updated_at`) VALUES
(1, 1, 0, 'root', '$2y$10$AgeQ0QcyfUqPX7UGJw2go.3oWUvzQXBW1ZaupUlQKosKNqsPlHGcu', 'hamlet@x-tech.am', 'ycneYUBtGLgLijU9InbfDjwYeaiAa6vOKH5XEJpcvaKEVIfzRt1GMasXXy4Y', '', 2, 0, '0000-00-00 00:00:00', '2018-01-18 09:37:20'),
(25, 1, 0, 'admin', '$2y$10$YOuLeg.D4FUim2Wk76HvVeaKp/BSJ7sb0AEfvkcw/OYyc6w.f7kg2', 'info@prolivestream.com', 'WBkmJwq4lJDmLRnzPornUBhM1sTXnUGcZDTzzmtbCuMsIJlAdtGcJ7HTjbVP', '', 60, 0, '2015-07-21 09:21:12', '2018-01-18 09:40:17');

-- --------------------------------------------------------

--
-- Table structure for table `users_in_channels`
--

CREATE TABLE IF NOT EXISTS `users_in_channels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users_in_channels`
--

INSERT INTO `users_in_channels` (`id`, `user_id`, `channel_id`, `type`, `created_at`, `updated_at`) VALUES
(2, 25, 2, 6, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `video`
--

CREATE TABLE IF NOT EXISTS `video` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `playlist_id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `thumbnail_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `duration` int(11) NOT NULL,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `video_format` text COLLATE utf8_unicode_ci NOT NULL,
  `job_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `encode_status` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `hd_width` int(11) NOT NULL,
  `hd_height` int(11) NOT NULL,
  `hd_file_size` int(11) NOT NULL,
  `hd_video_bitrate` int(11) NOT NULL,
  `hd_audio_codec` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hd_video_codec` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hd_mime_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sd_file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sd_duration` int(11) NOT NULL,
  `sd_width` int(11) NOT NULL,
  `sd_height` int(11) NOT NULL,
  `sd_file_size` int(11) NOT NULL,
  `sd_video_bitrate` int(11) NOT NULL,
  `sd_audio_codec` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sd_video_codec` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sd_mime_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mb_file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mb_duration` int(11) NOT NULL,
  `mb_width` int(11) NOT NULL,
  `mb_height` int(11) NOT NULL,
  `mb_file_size` int(11) NOT NULL,
  `mb_video_bitrate` int(11) NOT NULL,
  `mb_audio_codec` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mb_video_codec` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mb_mime_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `storage` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `source` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'internal',
  `viewing` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'inherit',
  `thumbnail_source` int(4) NOT NULL DEFAULT '0',
  `tvapp_image_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobileweb_image_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `custom_poster` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `show` int(11) NOT NULL,
  `season` int(11) NOT NULL,
  `episode` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `videos_in_collections`
--

CREATE TABLE IF NOT EXISTS `videos_in_collections` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `video_id` int(11) NOT NULL,
  `collection_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `video_back`
--

CREATE TABLE IF NOT EXISTS `video_back` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `playlist_id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `thumbnail_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `duration` int(11) NOT NULL,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `video_format` text COLLATE utf8_unicode_ci NOT NULL,
  `job_id` int(11) NOT NULL,
  `encode_status` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `storage` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `video_ex`
--

CREATE TABLE IF NOT EXISTS `video_ex` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `playlist_id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `thumbnail_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `duration` int(11) NOT NULL,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `video_format` text COLLATE utf8_unicode_ci NOT NULL,
  `job_id` int(11) NOT NULL,
  `encode_status` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `storage` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `video_in_playlist`
--

CREATE TABLE IF NOT EXISTS `video_in_playlist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `video_id` int(11) NOT NULL,
  `playlist_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `video_shows`
--

CREATE TABLE IF NOT EXISTS `video_shows` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `video_id` int(11) NOT NULL,
  `show_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `video_id` (`video_id`),
  KEY `show_id` (`show_id`),
  KEY `show_id_2` (`show_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `video_tags`
--

CREATE TABLE IF NOT EXISTS `video_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `video_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `video_id` (`video_id`,`tag_id`),
  KEY `video_id_2` (`video_id`),
  KEY `tag_id` (`tag_id`),
  KEY `tag_id_2` (`tag_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tvapp_playlist_platforms`
--
ALTER TABLE `tvapp_playlist_platforms`
  ADD CONSTRAINT `tvapp_playlist_platforms_tvapp_platform_id_foreign` FOREIGN KEY (`tvapp_platform_id`) REFERENCES `tvapp_platforms` (`id`),
  ADD CONSTRAINT `tvapp_playlist_platforms_tvapp_playlist_id_foreign` FOREIGN KEY (`tvapp_playlist_id`) REFERENCES `tvapp_playlist` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
