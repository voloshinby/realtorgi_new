<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Models\LotItemsGallery;

class LotItemsGalleryController extends BaseController
{

    public function __construct(LotItemsGallery $gallery)
    {
        $this->gallery = $gallery;
    }

    public function upload (Request $request, $id){

        if($request->file()){
            if($request->file('images')){
                foreach($request->file('images') as $image){

                    $file_name = time().'_'.$image->getClientOriginalName();
                    $file_path = $image->storeAs('', $file_name, 'uploads');

                    $data = [
                        'name' => time().'_'.$image->getClientOriginalName(),
                        'path' => '/public/uploads/'.$file_path,
                        'item_id' => $id,
                    ];

                    if(getimagesize($image) != false) {
                        LotItemsGallery::create($data);
                    } else {
                        return response()->json(['failed' => 'Please upload images']);
                    }

                }
            }


        }

        return response()->json(['success'=>'File uploaded']);

    }

    public function destroyImage($id)
    {
        $gallery = $this->gallery->findOrFail($id);

        $gallery->delete();

        return response()->json(['success' => true]);
    }
}
