<?php

namespace victory\imageservice\Facades;

use Illuminate\Support\Facades\Facade;
use victory\imageservice\Models\Image;

class imageservice extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'imageservice';
    }

    public static function all() {
        return Image::all();
    }

    public static function getImageById($id) {
        return Image::find($id);
    }

    public static function saveToDatabase($data) 
    {
        $payload = [
            'image' => base64_encode(file_get_contents($data['file'])),
            'file_name' => $data['file_name'],
            'file_extenstion' => $data['file_extenstion'],
            'description' => $data['description']
        ];
        return Image::create($payload);
    }

    public static function getImageByName($name) 
    {
        return Image::where('name', $name);
    }

    public static function resize($image, $height, $width) 
    {
        return Image::resizeImage($image, $height, $width);
    }

    public static function uploadToS3($image) 
    {
        return Image::uploadToS3($image);
    }
}
