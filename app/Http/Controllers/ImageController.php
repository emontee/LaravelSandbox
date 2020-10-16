<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use victory\imageservice\Facades\imageservice;

class ImageController extends Controller
{
    protected $imageService;

    public function __construct(imageservice $imageService) 
    {
        $this->imageService = $imageService;
    }

    public function Image() 
    {
        $allImages = $this->imageService->all();
        return view('images',["allImages" => $allImages]);
    }

    public function ImageUpload(Request $request) 
    {
        $results = ['success' => false, 'reason' => 'Bad Data'];
        $data = $request->all();
        $validated = Validator::make($data, [
            'file' => 'required',
            'description' => 'required',
        ]);
        if ($validated->fails()) {
            foreach($validated->errors()->toArray() as $col => $msg) {
                $results['reason'] = $msg;
                break;
            }
        } else {
            $payload = [
                'file' => $data['file']->path(),
                'file_extenstion' => $data['file']->getClientOriginalExtension(),
                'file_name' => pathinfo($data['file']->getClientOriginalName(),PATHINFO_FILENAME),
                'description' => $data['description']
            ];
            $image = $this->imageService->saveToDatabase($payload);
            if(!empty($image)) {
                $results = ['success' => true , 'imageData' => $image];
            } else {
                $results = ['success' => false, 'reason' => 'Failed to upload the image'];
            }
        }
        return $results;
    }

    public function GetResizedImage(Request $request) 
    {
        $results = ['success' => false, 'reason' => 'Bad Data'];
        $data = $request->all();
        $validated = Validator::make($data, [
            'id' => 'required',
            'size' => 'required'
        ]);
        if ($validated->fails()) {
            foreach($validated->errors()->toArray() as $col => $msg) {
                $results['reason'] = $msg;
                break;
            }
        } else {
            $image = $this->imageService->getImageById($data['id']);

            switch($data['size']){
                case 0:
                    $image = $this->imageService->resize($image->image, 64, 64 );
                    break;
                case 1:
                    $image = $this->imageService->resize($image->image, 300, 300);
                    break;
                case 2:
                    $image = $this->imageService->resize($image->image, 800, 800);
                    break;
                default:
                $image = $image->image;
            }
            $results = ['image' => $image, 'file_extenstion' => 'png', 'success' => true ];
        }
        return $results;
    }

    public function UploadToS3(Request $request) 
    {
        $results = ['success' => false, 'reason' => 'Bad Data'];
        $data = $request->all();
        $validated = Validator::make($data, [
            'id' => 'required',
        ]);
        if ($validated->fails()) {
            foreach($validated->errors()->toArray() as $col => $msg) {
                $results['reason'] = $msg;
                break;
            }
        } else {
            $image = $this->imageService->getImageById($data['id']);
            $image = $this->imageService->uploadToS3($image);
            $results = ['success' => true, "imageData" => $image];
        }
        return $results;
    }
}
