<?php

namespace App\Http\traits;

use Illuminate\Support\Facades\Storage;

trait media {

    public function uploadFile($image , $folder , $id )
    {

        $fileName = time() . "-$id." . $image->extension();
        $path = $image->storeAs($folder,$fileName,'local');

        return $fileName;
    }

    public function uploadPhoto($image , $folder , $id)
    {
        $photoName = time() . "-$id." . $image->extension();
        $image->move(public_path($folder),$photoName);
        return $photoName;
    }


    public function deletePhoto($photoPath , $photoname)
    {
        // if(file_exists($photoPath)){
        //     unlink($photoPath);
        //     return true;
        // }
        // return false;
    }
}