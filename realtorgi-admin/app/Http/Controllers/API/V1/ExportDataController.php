<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Support\Str;
use ZipArchive;
use App\Models\ExportData;
use App\Models\Lot;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\File;

class ExportDataController extends BaseController
{

    protected $data = '';
    protected $lot = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ExportData $data, Lot $lot)
    {
        // $this->middleware('auth:api');
        $this->data = $data;
        $this->lot = $lot;
    }

    public function index()
    {
        $data = $this->data->latest()->with(['lot', 'user'])->get()->toArray();

        $idsArray = [];

        foreach($data as $id){
            if(!in_array($id['lot_id'], $idsArray)){
                $idsArray[] = $id['lot_id'];
            }
        }

        $lots = $this->lot->latest()->where('status', 'Состоявшиеся')->whereIn('id', $idsArray)->paginate(100);

        return $this->sendResponse($lots, 'Archives list');
    }

    public function download($id){

        $export_data = $this->data->where('lot_id', $id)->select('user_id')->distinct('user_id')->get();

        $lot = Lot::where('id', $id)->first();

        $zip = new ZipArchive();

        $lotSlug = Str::slug($lot->name);
        $filename = $lotSlug . $lot->id.'.zip';

        if($zip->open(public_path('/uploads/archives/'.$filename), ZipArchive::CREATE) !== TRUE){
            exit("Невозможно открыть");
        }

        foreach($export_data as $data){
            $user = User::where('id', $data['user_id'])->with(['gallery', 'userFiles'])->first();
            $user_ip = $this->data->where('user_id', $user['id'])->select('user_ip')->first();

            $user_info = "Имя: " . $user['first_name']. "\r\nФамилия: " . $user['last_name'] . "\r\nЛогин: " . $user['login'] . "\r\nEmail: " . $user['email'] . "\r\nIP: " . $user_ip['user_ip'];

            File::put(public_path('uploads/archives/user_info-'.$user['id'].'.txt'), $user_info);

            foreach($user->gallery as $gallery){
                $file = basename( $gallery->path );
                $zip->addFile( public_path($gallery->path), $user->full_name.'/'.$file );
            }

            foreach($user->userFiles as $one_file){
                $file = basename( $one_file->path );
                $zip->addFile( public_path($one_file->path), $user->full_name.'/'.$file );
            }

            $zip->addFile(public_path('uploads/archives/user_info-'.$user['id'].'.txt'), $user->full_name.'/'.'user_info-'.$user['id'].'.txt');
        }

        return $this->sendResponse('Success', 'Success');
        // return response()->download(public_path('/uploads/archives/'.$filename), $filename);

    }
}
