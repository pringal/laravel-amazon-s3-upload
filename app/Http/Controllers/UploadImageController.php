<?php

namespace App\Http\Controllers;

use App\UploadImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadImageController extends Controller
{
    public function imageUpload(Request $request)
    {
        $input = $request->all();

        //Gether file data here
        $file_parts = pathinfo($input['fileToUpload']->getClientOriginalName()); //$file_parts['extension']
        $file_size = $request->file('fileToUpload')->getSize();

        //check the file extensions
        if(in_array($file_parts['extension'], ['jpg', 'JPG', 'png' ,'PNG' ,'jpeg' ,'JPEG', 'gif', 'GIF'])){
            $name = uniqid(). "_" . $input['fileToUpload']->getClientOriginalName();

            $fileToUpload = $name;
            $filePath = env('AWS_BUCKET').'/' . $name;

            //Upload file in Amazon s3 bucket from here
            $response = Storage::disk('s3')->put($filePath, file_get_contents($input['fileToUpload']), ['visibility' => 'public']);
        }

        //Here we will insert the data into database
        $uploadImage = UploadImage::create([
           'image_name'=> $fileToUpload,
           'image_path'=>$filePath,
           'image_size'=>$file_size,
        ]);

        return back()->with('success', 'Image Successfully Uploaded !!');
    }

}
