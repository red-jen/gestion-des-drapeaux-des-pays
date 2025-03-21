<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $countries = Country::all();
        return response()->json([
            'status' => 'success',
            'data' => $countries
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:countries',
            'capital' => 'required|string|max:255',
            'population' => 'required|integer',
            'region' => 'required|string|max:255',
            'flag_url' => 'nullable|string|url',
            'currency' => 'nullable|string|max:100',
            'language' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $country = Country::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Country created successfully',
            'data' => $country
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Country $country)
    {
        return response()->json([
            'status' => 'success',
            'data' => $country
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Country $country)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255|unique:countries,name,' . $country->id,
            'capital' => 'string|max:255',
            'population' => 'integer',
            'region' => 'string|max:255',
            'flag_url' => 'nullable|string|url',
            'currency' => 'nullable|string|max:100',
            'language' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $country->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Country updated successfully',
            'data' => $country
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country)
    {
        $country->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Country deleted successfully'
        ]);
    }

    /**
     * Upload flag for the specified country.
     */
    public function uploadFlag(Request $request, Country $country)
    {
        $validator = Validator::make($request->all(), [
            'flag' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Delete old flag if exists
        if ($country->flag_url && Storage::exists('public/flags/' . basename($country->flag_url))) {
            Storage::delete('public/flags/' . basename($country->flag_url));
        }

        // Store new flag
        $path = $request->file('flag')->store('public/flags');
        $flag_url = Storage::url($path);
        
        $country->flag_url = $flag_url;
        $country->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Flag uploaded successfully',
            'flag_url' => $flag_url
        ]);
    }

    /**
     * Get flag for the specified country.
     */
    public function getFlag(Country $country)
    {
        if (!$country->flag_url) {
            return response()->json([
                'status' => 'error',
                'message' => 'No flag available for this country'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'flag_url' => $country->flag_url
        ]);
    }
}