<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Hotel;
use App\Models\Payment;
use Illuminate\Support\Str;
use App\Models\ShoppingCart;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\HotelCartRequest;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\HotelReserveRequest;
use App\Http\Controllers\PaystackController;

class ShoppingCartController extends Controller
{
    public function AddRoomCart(HotelCartRequest $request) {
        $hotelData =  $request->validated();
        
        $hotelId = isset($hotelData["hotelId"]) ? $hotelData["hotelId"] : $hotelData["id"];

        // Retrieve the hotel based on its ID
        $findHotel = Hotel::find($hotelId);

        if($findHotel == NULL) {
            Alert::error("Error", "Hotel does not exists");
            return redirect()->back();
        }

        $userId = Auth::id();

        $checkCart = ShoppingCart::where("hotel_id", $hotelId)->where("user_id", $userId)->first();
        if($checkCart != NULL) {
            Alert::info("Info", "Hotel already exists in your shopping bag");
            return redirect()->back();
        }

        ShoppingCart::create([
            "hotel_id" => $hotelId,
            "user_id" => $userId,
        ]);

        Alert::success("Great", "Hotel added successfully to your shopping bag");
        return redirect()->back();
    }

    public function countCart() {

        $userId = Auth::id();

        $totalCart = ShoppingCart::where("user_id", $userId)->count();
        return $totalCart;
    }

    public function getHostelId_InCart() {
        $userId = Auth::id();
        $hotelIds = ShoppingCart::where("user_id", $userId)->get()->pluck('hotel_id')->toArray();
        return $hotelIds;
    }

    public function DeleteCart($hotelId) {
        $userId = Auth::id();
        ShoppingCart::where("user_id", $userId)->where("hotel_id", $hotelId)->delete();
        Alert::success("Item Removed", "Item removed successfully from your cart history");
        return redirect()->back();
    }

    public function viewCart() {
        $userId = Auth::id();
        $cartItems = ShoppingCart::where("user_id", $userId)->get();
        $settingsController = app(SettingsController::class);

        $allSettings = $settingsController->getAllSettings();
        return view('view-cart', compact('cartItems', 'allSettings'));
    }

    public function CheckOutCart(HotelReserveRequest $request) {
        $dates = $request->validated();
        $checkIn = Carbon::createFromFormat('m/d/Y', $dates['check_in_date']);
        $checkOut = Carbon::createFromFormat('m/d/Y', $dates['check_out_date']);
        

        if ($checkIn->isFuture() OR $checkIn->isSameDay(Carbon::now())) {

            $diffInDays = $checkOut->diffInDays($checkIn);

            $userId = Auth::id();
            $cartItems = ShoppingCart::where("user_id", $userId)->get();
            $total_amount = Hotel::whereIn('id', $cartItems->pluck('hotel_id'))->sum('price') * $diffInDays;
            $vat = app(SettingsController::class)->getAllSettings()['vat'];
            $sub_total_amount = $total_amount + $vat;
            
            $paymentReference = Str::random(15);

            // Create payment instance...
            $createPayment = Payment::create([
                "user_id" => $userId,
                "hotel_id" => implode(",", $cartItems->pluck('hotel_id')->toArray()),
                "reference" => $paymentReference,
                "checkin_date" => $checkIn,
                "checkout_date" => $checkOut,
                "total_amount" => $total_amount,
                "vat" => $vat,
                "sub_total_amount" => $sub_total_amount
            ]);

            if(!$createPayment) {
                Alert::error("Error", "Something went wrong. Error creating payment request");
                return redirect()->back();
            }
            
            $getPayLink = app(PaystackController::class)->GeneratePaystackLink($paymentReference);
            
            if($getPayLink === false) {
                Alert::error("Error", "Something went wrong. Error generating payment link");
                return redirect()->back();
            }

            return redirect()->away($getPayLink);
        }
        else {
            Alert::error("Error", "Check-In date is in the past. Select a future date");
            return redirect()->back();
        }
    }

}