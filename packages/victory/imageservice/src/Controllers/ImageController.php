<?php
    // File: packages/Acme/PageReview/src/Models/Review.php

    namespace victory\imageservice\Controllers;
    use Illuminate\Http\Request;
    use Illuminate\Routing\Controller;
    use victory\imageservice\Models\Image;

    class ImageController extends Controller
    {
        protected $image;

        public function __construct(Image $image)
        {
            $this->image = $image;
        }

        public function index()
        {
            return 'Image Stuff';
        }
    }