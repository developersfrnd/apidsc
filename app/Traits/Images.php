<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as Image;
use Illuminate\Support\Str;
use Illuminate\Http\File;
use Pawlox\VideoThumbnail\VideoThumbnail;
use Illuminate\Support\Facades\Log;
use FFMpeg\FFProbe;

trait Images {

    protected $image_thumb_width = 100;
    protected $image_thumb_height = null;
    protected $video_max_thumb_width = 551;
    protected $video_max_thumb_height = 310;

    public function isExist($file, $dir = null) {
        return (Storage::exists($dir . '/' . $file));
    }

    public function moveFile($oldLocation, $newLocation) {
        Storage::move($oldLocation, $newLocation);
    }

    private function ffprobe(){
        $ffprobe = FFProbe::create([
                    'ffmpeg.binaries' => config('video-thumbnail.binaries.ffmpeg'),
                    'ffprobe.binaries' => config('video-thumbnail.binaries.ffprobe')
        ]);
        
        return $ffprobe;
    }
    
    private function getAspectRatio($video_url) {
        
        $ffprobe = $this->ffprobe();
        return $ratio = $this
                ->getDimensons($video_url)
                ->getRatio()
                ->getValue();
    }
    
    private function getDimensons($video_url) {
        
        $ffprobe = $this->ffprobe();
        return $dimension = $ffprobe
                ->streams($video_url) // extracts streams informations
                ->videos()                      // filters video streams
                ->first()                       // returns the first video stream
                ->getDimensions();
    }
    
    public function createThumbnail($file, $thumbnail_image_name, $path = null) {

        $height = $this->image_thumb_height;
        $width = $this->image_thumb_width;

        try {

            $image = Image::make($file);

            if (!$height) {
                $image->resize($width, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            } else {

                $image->resize($width, $height);
            }

            $image->save(storage_path($thumbnail_image_name));

            $saved_image_uri = $image->dirname . '/' . $image->basename;

            $path = $path ?? config('constant.paths.THUMBS');
            //Now use laravel filesystem.
            $uploaded_thumbnail_image = Storage::putFileAs($path, new File($saved_image_uri), $thumbnail_image_name);

            //Now delete temporary intervention image as we have moved it to Storage folder with Laravel filesystem.
            $image->destroy();
            unlink($saved_image_uri);
        } catch (Exception $exc) {

            Log::channel('thumbnail')->error('Unable to create thumbnail for ' . $path . ' ' . $exc->getTraceAsString());
        }

        return $thumbnail_image_name;
    }

    public function createVideoThumbnail($video_path, $thumb_path) {

        $thumbnail_name = null;

        try {

            $thumbnail = new VideoThumbnail();

            $path_parts = pathinfo($video_path);
            $thumbnail_name = $path_parts['filename'] . config('constant.video_thumbnail_extension');
            
            $dimension = $this->getDimensons($video_path);
            $isMediaRoteted = $this->isMediaRoteted($video_path);  //video file got rotated
            
            $height = ($isMediaRoteted == 90 || $isMediaRoteted == 270) ? $dimension->getWidth() : $dimension->getHeight();
            $width = ($isMediaRoteted == 90 || $isMediaRoteted == 270) ? $dimension->getHeight() : $dimension->getWidth();
            
            if($width >= $height){
                $ratio = ($height/$width);
                $height =  $ratio * $this->video_max_thumb_width;
                if($width >= $this->video_max_thumb_width){
                    $width = $this->video_max_thumb_width;
                }
                
            } else {
                $ratio =  ($width/$height);
                $width =  $ratio * $this->video_max_thumb_height;
                if($height >= $this->video_max_thumb_height){
                    $height = $this->video_max_thumb_height;
                }
               
            }
            
            $thumbnail->createThumbnail($video_path, storage_path(), $thumbnail_name, 2, $width, $height);
            $uploaded_thumbnail_image = Storage::putFileAs($thumb_path, new File(storage_path($thumbnail_name)), $thumbnail_name);

            //remove file from storage directory
            unlink(storage_path($thumbnail_name));
        } catch (Exception $exc) {

            Log::channel('thumbnail')->error('Unable to create thumbnail for ' . $video_path . ' ' . $exc->getTraceAsString());
        }

        return $thumbnail_name;
    }

    function deleteImage($image, $dir) {

        if ($this->isExist($image, $dir)) {
            Storage::delete($dir . '/' . $image);
        }

        if ($this->isExist($image, config('constant.paths.THUMBS'))) {
            Storage::delete(config('constant.paths.THUMBS') . '/' . $image);
        }
    }

    public function storeImage($file, $dir, $create_thumbnail = false, $picture_name = false) {

        if ($picture_name) {
            $path = Storage::putFileAs($dir, new File($file), $picture_name);
        } else {
            $path = Storage::putFile($dir, new File($file));
        }

        // remove media or avatar prefix from image name
        $saved_file_name = str::replaceFirst(config('constant.paths.AVATARS') . '/', '', $path);
        
        if ($create_thumbnail) {
            $this->createThumbnail($file, $saved_file_name);
        }

        return $saved_file_name;
    }

    public function getS3PresignedUrl($image, $feed, $path = null) {

        if ($path) {
            $key = $path . '/' . $image;
        } else {
            $key = config('constant.paths.MEDIA') . '/' . $feed->id . '/' . $image;
        }
        
        
        if(($feed->IsVideoTypeFeed())){
            
            $client = Storage::disk('rawMediaS3')->getDriver()->getAdapter()->getClient();
            $expiry = "+15 minutes";
            
            $key = $image;
            $command = $client->getCommand('putObject', [
                'Bucket' => config('filesystems.disks.rawMediaS3.bucket'),
                'Key' => $key
            ]);
            
        }else{
        
            $client = Storage::getDriver()->getAdapter()->getClient();
            $expiry = "+15 minutes";

            $command = $client->getCommand('putObject', [
                'Bucket' => config('filesystems.disks.s3.bucket'),
                'Key' => $key
            ]);
        }
        
        $request = $client->createPresignedRequest($command, $expiry);
        return (string) $request->getUri();
    }

    public function getMediaDuraion($media_url) {
        
        $ffprobe = $this->ffprobe();
        $duration = $ffprobe
                ->streams($media_url)
                ->first()
                ->get('duration');

        return ($duration) ? gmdate("H:i:s", $duration) : null;
    }
    
    public function isMediaRoteted($media_url) {
        
        $ffprobe = $this->ffprobe();
        $tags = $ffprobe
                ->streams($media_url)
                ->videos()
                ->first()
                ->get('tags');
        
        /*
            Array
            (
                [rotate] => 90
                [creation_time] => 2019-09-21T09:23:32.000000Z
                [language] => und
                [handler_name] => Core Media Data Handler
                [encoder] => H.264
            )
        * 
        **/
        
        return $rotate = (isset($tags['rotate'])) ? $tags['rotate'] : null;
    }
}
