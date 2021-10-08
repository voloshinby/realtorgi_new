<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Models\Gallery;
use App\Models\UserFiles;
use App\Models\UserImages;
use App\Models\Notification;

class GalleryController extends BaseController
{

    public function __construct(UserImages $gallery, UserFiles $file)
    {
        $this->gallery = $gallery;
        $this->file = $file;
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
                        'user_id' => $id,
                    ];

                    if(getimagesize($image) != false) {
                        UserImages::create($data);

                        Notification::create([
                            'user_id' => 0,
                            'text' => 'Пользователь под номером '.$id.' загрузил новое изображение',
                            'status' => 'new'
                        ]);

                    } else {
                        UserFiles::create($data);

                        Notification::create([
                            'user_id' => 0,
                            'text' => 'Пользователь под номером '.$id.' загрузил новый файл',
                            'status' => 'new'
                        ]);
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

    public function destroyFiles($id)
    {
        $file = $this->file->findOrFail($id);

        $file->delete();

        return response()->json(['success' => true]);
    }
}
