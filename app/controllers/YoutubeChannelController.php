<?php

class YoutubeChannelController extends BaseController
{
    public function import_videos()
    {
        $channel_id = Input::get('channel_id');
        $info = App::make('YTChannel')->channel_videos($channel_id);
        if(isset($info['videos']) && !empty($info['videos'])){
            echo json_encode(array(
                'success' => true,
                'videos' => $info['videos']
            ));
            die;
        }
        else{
            echo json_encode(array(
                'success' => false
            ));
            die;
        }
    }

    public function download_video($channel_id,$video_id,$format){

        if(isset($video_id) && !empty($video_id)){
            $info = App::make('YTChannel')->download_video($video_id);

            $title = $info['title'];
            $file_name = $info[$format]['url'];
            $type = $info[$format]['type'];
            $file_format = explode('/',$type);
            $file_format = end($file_format);
            set_time_limit(0);
            header('Content-Description: File Transfer');
            header('Content-Type: '.$type.'');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"" . basename($title.'.'.$file_format) . "\"");
            readfile($file_name);
            exit();
        }
        else{
            return;
        }
    }

    public function download_all($channel_id, $youtube_channel, $format,$quality){
        $channel_info = App::make('YTChannel')->channel_info($youtube_channel);
        $channel_videos = App::make('YTChannel')->channel_videos($youtube_channel);
        $channel_name = $channel_info['name'];
        $files = array();
        if(isset($channel_videos) && count($channel_videos) > 0){
            foreach ($channel_videos['videos'] as $key => $video){
                if(!empty($video['id'])){
                    if(isset($video['formats'][$format])){

                        $file_format = explode('/',$video['formats'][$format]['type']);
                        $file_format = end($file_format);
                        $array = array(
                            'url' => $video['formats'][$format]['url'],
                            'name' => $video['title'].'.'.$file_format
                        );

                        array_push($files,$array);
                    }
                    else{
                        if($quality == 1){
                            if(isset($video['formats']['medium-mp4'])){
                                $format = 'medium-mp4';
                            }
                            else{
                                if(isset($video['formats']['medium-webm'])){
                                    $format = 'medium-webm';
                                }
                                else{
                                    if(isset($video['formats']['small-3gpp'])){
                                        $format = 'small-3gpp';
                                    }
                                }
                            }
                            $file_format = explode('/',$video['formats'][$format]['type']);
                            $file_format = end($file_format);
                            $array = array(
                                'url' => $video['formats'][$format]['url'],
                                'name' => $video['title'].'.'.$file_format
                            );

                            array_push($files,$array);
                        }

                    }
                }
            }
        }

        # create new zip object
        $zip = new ZipArchive();

        # create a temp file & open it
        $tmp_file = tempnam('.', '');
        $zip->open($tmp_file, ZipArchive::CREATE);

        # loop through each file
        foreach ($files as $file) {

            # download file
            $download_file = file_get_contents($file['url']);

            #add it to the zip
            $zip->addFromString(basename($file['name']), $download_file);
        }

        # close zip
        $zip->close();

        # send the file to the browser as a download
        header('Content-disposition: attachment; filename="'.$channel_name.'.zip"');
        header('Content-type: application/zip');
        readfile($tmp_file);
        unlink($tmp_file);
        exit();
    }

}
