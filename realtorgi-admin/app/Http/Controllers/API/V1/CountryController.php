<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\CountryRequest;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends BaseController
{
    protected $country = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Country $country)
    {
        $this->middleware('auth:api');
        $this->country = $country;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries = $this->country->latest()->paginate();

        return $this->sendResponse($countries, 'Список городов');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\CountryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CountryRequest $request)
    {
        $country = $this->country->create([
            'name' => $request->get('name'),
        ]);

        return $this->sendResponse($country, 'Country Created Successfully');
    }

    public function list()
    {
        $cities = Country::pluck('name', 'id');

        return $this->sendResponse($cities, 'Cities list');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $country = $this->country->findOrFail($id);

        return $this->sendResponse($country, 'Country Details');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Country  $country
     * @return \Illuminate\Http\Response
     */
    public function update(CountryRequest $request, $id)
    {
        $country = $this->country->findOrFail($id);

        $country->update($request->all());

        return $this->sendResponse($country, 'Country Information has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $this->authorize('isAdmin');

        $country = $this->country->findOrFail($id);

        $country->delete();

        return $this->sendResponse($country, 'Country has been Deleted');
    }

    public function upload(Request $request)
    {
        $fileName = time() . '.' . $request->file->getClientOriginalExtension();
        $request->file->move(public_path('upload'), $fileName);

        return response()->json(['success' => true]);
    }
}
