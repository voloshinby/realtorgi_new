<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\Auctions\AuctionRequest;
use App\Models\Auction;
use App\Models\AuctionFiles;
use App\Models\AuctionGallery;
use App\Models\Country;
use App\Models\Feedback;
use App\Models\Lot;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class AuctionController extends BaseController
{
    protected $auction = '';
    protected $gallery = '';
    protected $file = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Auction $auction, AuctionGallery $gallery, AuctionFiles $file)
    {
        // $this->middleware('auth:api');
        $this->auction = $auction;
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
        $auctions = $this->auction->latest()->with(['feedback_seller', 'gallery', 'files'])->paginate(1000);

        return $this->sendResponse($auctions, 'Auctions list');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param App\Http\Requests\Auctions\AuctionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AuctionRequest $request)
    {

        if (!is_null($request->get('starts_at')) && $request->get('starts_at') != '') {
            $starts_at = date("U", strtotime($request->get('starts_at')));
        } else {
            $starts_at = date('d-m-Y H:i:s', strtotime('+30 days', strtotime('8:00:00')));
            $starts_at = date('U', strtotime($starts_at));
        }

        if (!is_null($request->get('ends_at')) && $request->get('ends_at') != '') {
            $ends_at = date("U", strtotime($request->get('ends_at')));
        } else {
            $ends_at = date('d-m-Y H:i:s', strtotime('+30 days', strtotime('17:00:00')));
            $ends_at = date('U', strtotime($ends_at));
        }

        if (!is_null($request->get('start_selling')) && $request->get('start_selling') != '') {
            $start_selling = date("U", strtotime($request->get('start_selling')));
        } else {
            $start_selling = date('d-m-Y H:i:s', strtotime('+31 days', strtotime('9:00:00')));
            $start_selling = date('U', strtotime($start_selling));
        }

        if (!is_null($request->get('end_selling')) && $request->get('end_selling') != '') {
            $end_selling = date("U", strtotime($request->get('end_selling')));
        } else {
            $end_selling = date('d-m-Y H:i:s', strtotime('+31 days', strtotime('16:00:00')));
            $end_selling = date('U', strtotime($end_selling));
        }

        if (!is_null($request->get('city_id')) && !empty($request->get('city_id'))) {
            $city = Country::where('id', $request->get('city_id'))->first();
            $city = $city['name'];
        } else {
            $city = '';
        }

        $auction = $this->auction->create([
            'name' => $request->get('name'),
            'type' => $request->get('type'),
            'auction_number' => $request->get('auction_number'),
            'organizer_requisites' => $request->get('organizer_requisites'),
            'seller_id' => $request->get('seller_id'),
            'seller_phone' => $request->get('seller_phone'),
            'requisites' => $request->get('requisites'),
            'anticrisis_manager' => $request->get('anticrisis_manager'),
            'anticrisis_manager_phone' => $request->get('anticrisis_manager_phone'),
            'how_to_view_the_property' => $request->get('how_to_view_the_property'),
            'contact_person' => $request->get('contact_person'),
            'compensation' => $request->get('compensation'),
            'step' => $request->get('step'),
            'additional_info' => $request->get('additional_info'),
            'starts_at' => $starts_at,
            'ends_at' => $ends_at,
            'start_selling' => $start_selling,
            'end_selling' => $end_selling,
            'city_id' => $request->get('city_id'),
            'city_name' => $city,
            'seller_custom' => $request->get('seller_custom'),
        ]);

        if (!is_null($request->get('seller_id')) && !empty($request->get('seller_id'))) {
            Notification::create([
                'user_id' => $request->get('seller_id'),
                'title' => 'Создание аукциона',
                'text' => 'Ваш аукцион был успешно создан, его номер: ' . $auction->auction_number,
                'status' => 'new',
            ]);
        }

        return $this->sendResponse($auction, 'Auction Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $auction = $this->auction->with(['feedback_seller', 'gallery', 'files'])->findOrFail($id);

        return $this->sendResponse($auction, 'Auction Details');
    }

    public function lots($id)
    {
        $lot = Lot::latest()->where('auction_id', $id)->with([
            'auction',
            'gallery',
            'files',
            'category',
        ])->withCount('users')->paginate(8);

        return $this->sendResponse($lot, 'Lots List');

    }

    public function list()
    {
        $auctions = Auction::pluck('name', 'id');

        return $this->sendResponse($auctions, 'Auctions list');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Auction $auction
     * @return \Illuminate\Http\Response
     */
    public function update(AuctionRequest $request, $id)
    {
        $auction = $this->auction->findOrFail($id);

        // $starts_at = date('U', strtotime($request->get('starts_at')));
        if ($auction->starts_at == $request->get('starts_at')) {
            $starts_at = $auction->starts_at;
        } else {
            $starts_at = date('U', strtotime($request->get('starts_at')));
        }

        // $ends_at = date('U', strtotime($request->get('ends_at')));
        if ($auction->ends_at == $request->get('ends_at')) {
            $ends_at = $auction->ends_at;
        } else {
            $ends_at = date('U', strtotime($request->get('ends_at')));
        }

        // $start_selling = date('U', strtotime($request->get('start_selling')));
        if ($auction->start_selling == $request->get('start_selling')) {
            $start_selling = $auction->start_selling;
        } else {
            $start_selling = date('U', strtotime($request->get('start_selling')));
        }

        // $end_selling = date('U', strtotime($request->get('end_selling')));
        if ($auction->end_selling == $request->get('end_selling')) {
            $end_selling = $auction->end_selling;
        } else {
            $end_selling = date('U', strtotime($request->get('end_selling')));
        }

        if (!is_null($request->get('city_id')) && !empty($request->get('city_id'))) {
            $city = Country::where('id', $request->get('city_id'))->first();
            $city = $city['name'];
        } else {
            $city = '';
        }

        $auction->update([
            'name' => $request->get('name'),
            'type' => $request->get('type'),
            'auction_number' => $request->get('auction_number'),
            'organizer_requisites' => $request->get('organizer_requisites'),
            'seller_id' => $request->get('seller_id'),
            'seller_phone' => $request->get('seller_phone'),
            'requisites' => $request->get('requisites'),
            'anticrisis_manager' => $request->get('anticrisis_manager'),
            'anticrisis_manager_phone' => $request->get('anticrisis_manager_phone'),
            'how_to_view_the_property' => $request->get('how_to_view_the_property'),
            'contact_person' => $request->get('contact_person'),
            'compensation' => $request->get('compensation'),
            'step' => $request->get('step'),
            'additional_info' => $request->get('additional_info'),
            'starts_at' => $starts_at,
            'ends_at' => $ends_at,
            'start_selling' => $start_selling,
            'end_selling' => $end_selling,
            'city_id' => $request->get('city_id'),
            'city_name' => $city,
            'seller_custom' => $request->get('seller_custom'),
        ]);

        return $this->sendResponse($auction, 'Auction Information has been updated');
    }

    public function userList(User $user)
    {
        $feedback = Feedback::where('user_id', $user->id)->first();
        $auctions = null;

        if (!empty($feedback)) {
            $auctions = $this->auction->latest()->where('seller_id', $feedback->id)->paginate(1000);
        }

        return $this->sendResponse($auctions, 'Auctions list');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $this->authorize('isAdmin');

        $auction = $this->auction->findOrFail($id);

        $auction->delete();

        return $this->sendResponse($auction, 'Auction has been Deleted');
    }

    public function upload(Request $request, $id)
    {
        if ($request->file() && $request->file('images')) {
            foreach ($request->file('images') as $image) {
                $file_path = $image->store("auctions/{$id}", 'public');

                $data = [
                    'name' => time() . '_' . $image->getClientOriginalName(),
                    'path' => '/admin/storage/' . $file_path,
                    'auction_id' => $id,
                ];

                if (getimagesize($image) != false) {
                    AuctionGallery::create($data);
                } else {
                    AuctionFiles::create($data);
                }

            }
        }

        return response()->json(['success' => 'File uploaded']);

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
