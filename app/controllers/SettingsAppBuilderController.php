<?php
class SettingsAppBuilderController extends BaseController {


    public function index() {
    }

    public function GetFilesToZip($dir)
    {
        $fileArray = array();
        if (is_dir($dir))
        {
            //Log::info("GetFilesToZip :".$dir);
            $dh = opendir($dir); 
            while( ($file = readdir($dh)) )
            { 
                //Log::info("GetFilesToZip - File :".$file);
            
                if ($file != '.' && $file != '..') 
                { 
                    $path = $dir.$file;
                    if (is_file($path)) $fileArray[] = $path;
                    else
                    {
                        $a2 = $this->GetFilesToZip($path.'/'); 
                        if (count($a2) > 0) $fileArray = array_merge($fileArray, $a2);
                    }
                } 
            } 
            closedir($dh); 
        }
        return $fileArray;
    }

    public function build_roku_channel() 
    {
        $channel_id = BaseController::get_channel_id(); 	
        $channel = Channel::find($channel_id);

        $zipname = strtolower(str_replace(' ', '_', $channel->title));
    	$path = public_path().'/tvapp/channel_'.$channel_id.'/roku/'.$zipname.'.zip';

        $zip = new ZipArchive;
        $res = $zip->open($path, ZipArchive::OVERWRITE | ZipArchive::CREATE);
        if ($res === TRUE) 
        {
            $zip->addEmptyDir('images'); 
            $zip->addEmptyDir('source'); 

            $manifest  = "title=".$channel->title."\n";
            $manifest .= "major_version=1\n";
            $manifest .= "minor_version=0\n";
            $manifest .= "build_version=0\n";
            $manifest .= "mm_icon_focus_hd=pkg:/images/Focus_HD.png\n";
            $manifest .= "mm_icon_focus_fhd=pkg:/images/Focus_HD.png\n";
            $manifest .= "splash_screen_hd=pkg:/images/Splash_SD.png\n";
            $manifest .= "splash_screen_fhd=pkg:/images/Splash_HD.png\n";
            $manifest .= "ui_resolutions=hd\n";
            $zip->addFromString('manifest', $manifest);

            //--------------------------------------------------------------------  
            // copy brs files to zip
            $root = public_path().'/roku_app/';
            $fileArray = $this->GetFilesToZip($root); 
            foreach($fileArray as $file) $zip->addFile($file, str_replace($root, '', $file));

            $zip->addFromString('source/config.brs', "function getConfig()\n    return {channel:\"channel_".$channel_id."\"}\nend function\n");

            // get images from DB


            $files = Channel_images::where('channel_id',$channel_id)->first();
            $dest_img_path = public_path().'/tvapp/channel_'.$channel_id.'/roku/images/';

            if(!empty($files) && count($files) > 0){
                $arrayKeys = array_keys($files->toArray());
                unset($arrayKeys[0]);
                unset($arrayKeys[1]);
                foreach ($arrayKeys as $key => $value) {
                    if(!empty($files->$value)){
                        $zip->addFile($dest_img_path.$files->$value, "images/".$files->$value."");
                        $success = true;

                    }
                    else{
                        $success = false;
                    }
                } 
            }
            else{
                $success = false;
            }
            $zip->close();

            if($success){
                print str_replace(public_path(), URL::to('/'), $path);
            }
            else{
                print "false";
            }
            // end




            //--------------------------------------------------------------------  
            // copy images to zip
         //    $img = 'http://prolivestream.s3.amazonaws.com/logos/channel_'.$channel_id;
         //    $src_img = imagecreatefromjpeg($img); 
         //    $old_x = imageSX($src_img);
         //    $old_y = imageSY($src_img);

         //    // reference -> https://sdkdocs.roku.com/display/sdkdoc/Manifest+File
        	// $dest_img_path = public_path().'/tvapp/channel_'.$channel_id.'/roku/tempimage';
         //    $files = array('images/CenterFocus_HD.png', 'images/CenterFocus_SD.png', 'images/Side_HD.png', 'images/Side_SD.png', 'images/Splash_HD.png', 'images/Splash_SD.png');
         //    $fileSizeW = array(290, 214, 108, 80, 1280, 720);
         //    $fileSizeH = array(218, 144,  69, 46,  720, 480);

         //    for ($i = 0 ; $i < count($files) ; $i++)
         //    {
         //        $dst_img = imagecreatetruecolor($fileSizeW[$i], $fileSizeH[$i]);
         //        imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $fileSizeW[$i], $fileSizeH[$i], $old_x, $old_y); 
         //        imagepng($dst_img, $dest_img_path.$i.'.png', 8); 
         //        imagedestroy($dst_img); 
         //        $zip->addFile($dest_img_path.$i.'.png', $files[$i]);
         //    }
         //    $zip->close();

         //    imagedestroy($src_img); 
         //    for ($i = 0 ; $i < count($files) ; $i++) unlink($dest_img_path.$i.'.png'); 
        }
    	// print str_replace(public_path(), URL::to('/'), $path);
    }

    public function build_fireTV_channel() 
    {
        $channel_id = BaseController::get_channel_id(); 	
        $channel = Channel::find($channel_id);

        $zipname = strtolower(str_replace(' ', '_', $channel->title));
    	$base_path = public_path().'/tvapp/channel_'.$channel_id;
        $raw_apk = 'firetv_'.$zipname.'_unaligned.apk';  
        $final_apk = 'firetv_'.$zipname.'.apk';  
        $keyfilename = $zipname.'_release_key.jks';

        // ---------------------------------------------------------------------   
    	$path_unaligned = $base_path.'/'.$raw_apk;
        if (file_exists($path_unaligned)) unlink($path_unaligned);

        $path = $base_path.'/'.$final_apk;
        if (file_exists($path)) unlink($path);

        $keyfilepath = $base_path.'/'.$keyfilename;
        if (!file_exists($keyfilepath)) 
        {
            // lets create a signing key for this apk
            $cmd = "keytool -genkeypair -keystore $keyfilepath -alias $zipname -keypass onestudio -storepass onestudio -keyalg RSA -keysize 2048 -validity 10000 -dname \"CN=jerry caloroso, OU=Development, O=x-tech, L=Oceanside, S=California, C=US\""; 
            exec($cmd);
            //Log::info($cmd);
        }

        // ---------------------------------------------------------------------   
        copy(public_path().'/applications/onestudio_firetv.apk', $path_unaligned);

        $zip = new ZipArchive;
        $res = $zip->open($path_unaligned);
        if ($res === TRUE) 
        {
            $json1 = "{\n";
            $json1 .= "  \"oneStudioChannelID\" : \"$channel_id\",\n";
            $json1 .= "  \"oneStudioChannelName\" : \"".$channel->title."\",\n";
            $json1 .= "  \"oneStudioCopyRightMessage\" : \"\",\n";
            $json1 .= "  \"oneStudioCompanyLogo\" : \"\"\n";
            $json1 .= "}\n";

            $zip->addFromString('assets/configurations/BasicHttpBasedDownloaderConfig.json', $json1);
            //--------------------------------------------------------------------  

            // copy images to zip
            $img = 'http://prolivestream.s3.amazonaws.com/logos/channel_'.$channel_id;
            $src_img = imagecreatefromjpeg($img); 
            $old_x = imageSX($src_img);
            $old_y = imageSY($src_img);

            // reference -> https://sdkdocs.roku.com/display/sdkdoc/Manifest+File
        	$dest_img_path = public_path().'/tvapp/channel_'.$channel_id.'/roku/tempimage';
            $files = array('res/mipmap-hdpi-v4/ic_launcher.png', 
                           'res/mipmap-mdpi-v4/ic_launcher.png', 
                           'res/mipmap-xhdpi-v4/ic_launcher.png', 
                           'res/mipmap-xxhdpi-v4/ic_launcher.png', 
                           'res/drawable/logo.png');
            $fileSizeW = array(72, 48, 96, 144, 432);
            $fileSizeH = array(72, 48, 96, 144, 243);

            for ($i = 0 ; $i < count($files) ; $i++)
            {
                $dst_img = imagecreatetruecolor($fileSizeW[$i], $fileSizeH[$i]);
                imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $fileSizeW[$i], $fileSizeH[$i], $old_x, $old_y); 
                imagepng($dst_img, $dest_img_path.$i.'.png', 8); 
                imagedestroy($dst_img); 
                $zip->addFile($dest_img_path.$i.'.png', $files[$i]);
            }
            $zip->close();

            imagedestroy($src_img); 
            for ($i = 0 ; $i < count($files) ; $i++) unlink($dest_img_path.$i.'.png'); 

            
            // -----------------------------------------------------------------
            exec("zipalign -f 4 $path_unaligned $path");
            //Log::info("zipalign -f 4 $path_unaligned $path");

            $signer = "jarsigner -tsa http://timestamp.digicert.com -sigalg SHA1withRSA -digestalg SHA1 -storepass onestudio -keystore $keyfilepath $path $zipname";
            //Log::info($signer);
            exec($signer);

            // -----------------------------------------------------------------
        }
    	print str_replace(public_path(), URL::to('/'), $path);
    }
}
