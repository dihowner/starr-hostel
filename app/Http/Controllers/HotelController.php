<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use RealRashid\SweetAlert\Facades\Alert;

class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hotels = Hotel::latest('id')->get();

        // Map product image to the result set...
        $hotels->map(function ($hotels) {
            $hotels->rating_code = self::ratingCode($hotels->rating);
            return $hotels;
        });
        
        
        return view('hotels', compact('hotels'));
    }

    public function viewHostel($id) {
        $viewHotel = Hotel::find($id);
        
        if($viewHotel == NULL) {
            Alert::error("Error", "Hotel not found");
            return redirect()->back();
        }
        
        $viewHotel->rating_code = self::ratingCode($viewHotel->rating);
        $viewHotel["meta"] = self::meta($viewHotel->hotelmeta);
        unset($viewHotel->hotelmeta);
        return view('view-hotel', compact('viewHotel'));
    }

    private function meta($metaDatas) {
        foreach($metaDatas as $metaIndex => $metaData) {
            $newMeta[$metaDatas[$metaIndex]['name']] = $metaDatas[$metaIndex]['value'];
        }
        return $newMeta;
    }
    

    public function ratingCode($rating) {
        $wholeNumber = floor($rating);
        $fractionalPart = $rating - $wholeNumber;

        $wholeRatingCode = '';
        $fractionalRatingCode = '';

        // Generate whole number rating code
        for ($i = 0; $i < $wholeNumber; $i++) {
            $wholeRatingCode .= "<a href='#'><i class='fa fa-star text-yellow fs-16'></i></a>";
        }

        // Generate fractional rating code
        if ($fractionalPart > 0) {
            $fractionalRatingCode = '<a href="#"><i class="fa fa-star-half text-yellow fs-16"></i></a>';
        }

        // Combine the whole and fractional rating codes
        $ratingCode = $wholeRatingCode . $fractionalRatingCode;
        
        return $ratingCode;

    }

}