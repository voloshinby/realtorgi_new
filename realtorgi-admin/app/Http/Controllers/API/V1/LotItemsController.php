<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Models\Lot;
use App\Models\LotItemsGallery;
use App\Models\LotItems;
use App\Models\Notification;

class LotItemsController extends BaseController
{
    protected $lot = '';
    protected $gallery = '';
    protected $item = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Lot $lot, LotItemsGallery $gallery, LotItems $item)
    {
        // $this->middleware('auth:api');
        $this->lot = $lot;
        $this->gallery = $gallery;
        $this->item = $item;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $items = $this->item->latest()->with(['lot', 'gallery'])->paginate(1000);

        return $this->sendResponse($items, 'Items list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Auctions\LotRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item = $this->item->create([
            'lot_id' => $request->get('lot_id'),
            'name' => $request->get('name'),
            'info' => $request->get('info'),
        ]);

        Notification::create([
            'user_id' => 0,
            'text' => 'К лоту под номером '.$request->get('lot_id').' был загружен новый предмет',
            'status' => 'new'
        ]);

        return $this->sendResponse($item, 'Item Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $items = $this->item->latest()->with(['gallery', 'lot'])->where('lot_id', $id)->paginate(1000);

        return $this->sendResponse($items, 'Lot Details');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Auction  $lot
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $lot = $this->lot->findOrFail($id);

        $lot->update($request->all());

        return $this->sendResponse($lot, 'Lot Information has been updated');
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

        $gallery = $this->gallery->where('item_id', $id);

        $gallery->delete();

        $lot->delete();

        return $this->sendResponse($lot, 'Lot has been Deleted');
    }

}
