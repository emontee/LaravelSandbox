<?php

    namespace victory\imageservice\Models;

    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\Storage;

    class Image extends Model
    {
        protected $table = 'images';
        protected $fillable = [
            'file_name', 
            'file_extenstion',
            'image',
            'is_uploaded_to_s3',
            's3_path', 
            'description'
        ];

        public static function resizeImage($image,$newHeight,$newWidth)
        {
            $decodeImg = base64_decode($image);
            $tmpImage = imagecreatefromstring($decodeImg);
            $newimage = imagecreatetruecolor($newWidth, $newHeight);
            list($width, $height) = getimagesizefromstring($decodeImg);
            imagecopyresampled($newimage, $tmpImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            ob_start();
            imagepng($newimage);
            $contents = ob_get_clean();
            ob_end_clean();
            return base64_encode($contents);
        }

        public static function uploadToS3($image) 
        {
            if($upload = Storage::disk('s3')->put('test/'.$image->file_name . '.' . $image->file_extenstion, base64_decode($image->image) )) {
                $image->is_uploaded_to_s3 = $upload;
                $image->s3_path = 'test/'.$image->file_name . '.' . $image->file_extenstion;
                $image->save();
            }
            return $image;
        }
    }