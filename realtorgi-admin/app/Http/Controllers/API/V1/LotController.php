<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\Auctions\LotRequest;
use App\Models\AuctionConfirm;
use App\Models\Category;
use App\Models\File;
use App\Models\Gallery;
use App\Models\Lot;
use App\Models\LotsComment;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LotController extends BaseController
{
    protected $lot = '';
    protected $gallery = '';
    protected $file = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Lot $lot, Gallery $gallery, File $file)
    {
        // $this->middleware('auth:api');
        $this->lot = $lot;
        $this->gallery = $gallery;
        $this->file = $file;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lots = $this->lot->latest()->with([
            'auction_files',
            'auction_gallery',
            'auction',
            'gallery',
            'files',
            'category',
            'confirms',
        ])->withCount('users')->paginate(1000);

        return $this->sendResponse($lots, 'Lots list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param App\Http\Requests\Auctions\LotRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(LotRequest $request)
    {
        $status = 'Предстоящие';

        if (!is_null($request->get('status')) && !empty($request->get('status'))) {
            $status = $request->get('status');
        }

        $lot = $this->lot->create([
            'auction_id' => $request->get('auction_id'),
            'category_id' => $request->get('category_id'),
            'lot_number' => $request->get('lot_number'),
            'name' => $request->get('name'),
            'info' => $request->get('info'),
            'price_start' => $request->get('price_start'),
            'price_min' => $request->get('price_min'),
            'step' => $request->get('step'),
            'deposit' => $request->get('deposit'),
            'additional_info' => $request->get('additional_info'),
            'status' => $status,
            'deleted_at' => $request->get('deleted_at'),
        ]);

        Notification::create([
            'user_id' => 0,
            'text' => 'Был создан новый лот под номером ' . $lot->lot_number,
            'status' => 'new',
        ]);

        return $this->sendResponse($lot, 'Lot Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lot = $this->lot->with([
            'auction_files',
            'auction_gallery',
            'auction',
            'gallery',
            'files',
            'category',
            'confirms' => static function ($query) {
                return $query->where('confirmed_user', 1);
            },
            'comments',
            'comments.user',
        ])->withCount('users')->findOrFail($id);

        return $this->sendResponse($lot, 'Lot Details');
    }

    public function list()
    {
        $lots = Lot::pluck('name', 'id');

        return $this->sendResponse($lots, 'Lots list');
    }

    public function auctionConfirms($id)
    {
        $confirms = AuctionConfirm::latest()->where('lot_id', $id)->get();

        return $this->sendResponse($confirms, 'Список заявок');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Auction $lot
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $lot = $this->lot->findOrFail($id);

        $lot->update($request->all());

        Notification::create([
            'user_id' => 0,
            'text' => 'Был изменен лот под номером ' . $lot->lot_number,
            'status' => 'new',
        ]);

        return $this->sendResponse($lot, 'Lot Information has been updated');
    }

    public function userList($id)
    {

        $userLots = AuctionConfirm::latest()->where('user_id', $id)->where('confirmed_admin', 1)->with('gallery', 'lot',
            'auction')->where('confirmed_user', 1)->get();

        return $this->sendResponse($userLots, 'Lots list');
    }

    public function userCount($id)
    {

        $count = AuctionConfirm::latest()->where('lot_id', $id)->where('confirmed_user',
            1)->select('user_id')->distinct()->count('user_id');

        return $this->sendResponse($count, 'Count');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        // $this->authorize('isAdmin');

        $lot = $this->lot->findOrFail($id);

        $gallery = $this->gallery->where('lot_id', $id);

        $gallery->delete();

        $lot->delete();

        Notification::create([
            'user_id' => 0,
            'text' => 'Был удален лот под номером ' . $lot->lot_number,
            'status' => 'new',
        ]);

        return $this->sendResponse($lot, 'Lot has been Deleted');
    }

    public function upload(Request $request, $id)
    {
        if ($request->file() && $request->file('images')) {
            foreach ($request->file('images') as $image) {
                $file_path = $image->store("lots/{$id}", 'public');

                $data = [
                    'name' => time() . '_' . $image->getClientOriginalName(),
                    'path' => '/admin/storage/' . $file_path,
                    'lot_id' => $id,
                ];

                if (getimagesize($image) != false) {
                    Gallery::create($data);

                    Notification::create([
                        'user_id' => 0,
                        'text' => 'К лоту под номером ' . $id . ' были загружены новые изображения',
                        'status' => 'new',
                    ]);
                } else {
                    File::create($data);

                    Notification::create([
                        'user_id' => 0,
                        'text' => 'К лоту под номером ' . $id . ' были загружены новые файлы',
                        'status' => 'new',
                    ]);
                }
            }
        }

        return response()->json(['success' => true]);
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

    public function categoryFilter(Request $request)
    {
        $category = Category::where('name', $request->get('category'))->first();

        $lots = $this->lot->where('category_id', $category->id)->with([
            'auction',
            'gallery',
            'files',
            'category',
            'confirms',
        ])->withCount('users')->where('status', '<>', 'На модерации')->paginate(1000);

        return $this->sendResponse($lots, 'Lots list');

    }

    final public function userSells(Request $request)
    {

        $lot_id = $request->get('lot_id');
        $user_sell_suggest = $request->get('user_sell_suggest');
        $price_sell_suggest = $request->get('price_sell_suggest');

        Lot::where('id', $lot_id)->update([
            'user_sell_suggest' => $user_sell_suggest,
            'price_sell_suggest' => $price_sell_suggest,
        ]);

        $lot = Lot::findOrFail($lot_id);

        $user = User::findOrFail($user_sell_suggest);

        Notification::create([
            'title' => 'Победа в аукционе',
            'text' => 'Пользователь ' . $user->first_name . ' ' . $user->last_name . ' победил в лоте под номером ' . $lot->lot_number,
            'user_id' => 0,
            'status' => 'new',
        ]);

        Notification::create([
            'title' => 'Участие в аукционе',
            'text' => 'Пользователь ' . $user->first_name . ' ' . $user->last_name . ' победил в лоте под номером ' . $lot->lot_number,
            'user_id' => $user->id,
            'status' => 'new',
        ]);

        $data = [];
        $data['lot_id'] = $lot->lot_number;
        $data['auction_id'] = $lot->auction_id;
        $data['created_at'] = $lot->updated_at;
        $data['bet_amount'] = $price_sell_suggest;

        Mail::send('emails.auctionWinner', $data,
            function ($message) use ($user) {
                $message->to($user['email'])->subject('Победа в аукционе');
            }
        );

        return $this->sendResponse("success", 'Lots list');
    }

    public function userSellsInfo(Request $request, $id)
    {
        $lots = Lot::where('id', $id)->first();

        return $this->sendResponse($lots, 'Suggest sell');

    }

    public function notificateComment(Lot $lot, Request $request)
    {
        $lotComment = LotsComment::create([
            'user_id' => $request->user('api')->id ?? $request->user,
            'lot_id' => $lot->id,
            'comment' => $request->get('comment'),
        ]);

        return $this->sendResponse($lotComment, 'Suggest sell');
    }
}
