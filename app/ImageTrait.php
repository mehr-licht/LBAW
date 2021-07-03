<?php

namespace App;

use Intervention\Image\ImageManagerStatic as Image;

trait ImageTrait
{    
    public function validateAndSave( $request) {
       
        if ($request->hasFile('photo')) {
            
            $valid = $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if (!$valid){
                return back()->with('error', 'Imagem é demasiado grande ou do tipo errado');
            }            

            $image                   =       $request->file('photo');

            $filename                =       md5(time() . '.' . $image->getClientOriginalExtension() . $image->getClientOriginalExtension()) . '.' . $image->getClientOriginalExtension();
           
            $originalsPath           =       public_path() . '/images/originals/';
            $smallPath               =       public_path() . '/images/small/';  
            $destinationPath         =       public_path() . '/images/';

            $img                     =       Image::make($image->path());
            $thumb                   =       Image::make($image->path());
            //$image_resize = Image::make($image->getRealPath());  

            // --------- [ Resize Image ] ---------------
            $img->fit(500, 500, function ($constraint) {
                $constraint->upsize();
            })->save($destinationPath.$filename);

            // ----------- [ Uploads Image in Original Form ] ----------
            
            //$destinationPath        =        public_path('/uploads/original');

            $image->move($originalsPath, $filename);

            // ----------- [ Upload thumbnail ] ------------

            $thumb->fit(150, 150, function ($constraint) {
                $constraint->upsize();
            })->save($smallPath.$filename);

            return '/images/'.$filename;
        }
    }
}