<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Payment;
use App\Classes\HttpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PaystackRequest;
use App\Models\ShoppingCart;
use RealRashid\SweetAlert\Facades\Alert;

class PaystackController extends Controller
{
    private $endpoint = "https://api.paystack.co";

    private $paystackInfo, $publicKey, $secretKey, $charges, $chargesType, $headerParams;
    protected $settingsController, $responseBody;
    public function __construct(SettingsController $settingsController) {
        $this->settingsController = $settingsController;

        $this->paystackInfo = $this->settingsController->getAllSettings()['paystack'];
        if($this->paystackInfo !== false) {
            $decodePaystack = json_decode($this->paystackInfo, true);
            $this->publicKey = $decodePaystack['public_key'];
            $this->secretKey = $decodePaystack['secret_key'];
            $this->headerParams = [
                "Authorization" => "Bearer ".$this->secretKey,
                "Content-Type" => "application/json"
            ];
        }
    }

    public function GeneratePaystackLink($referenceId) {
        try {
            $paymentAmount = Payment::where('reference', $referenceId)->where('status', '0')->first();
            
            if($paymentAmount == NULL) {
                return false;
            }
            
            $amountToPay = (float) $paymentAmount->sub_total_amount;
            
            $reserveResult = HttpRequest::sendPost($this->endpoint."/transaction/initialize", [
                "amount" => ($amountToPay * 100),
                "email" => Auth::user()->emailaddress,
                "currency" => "NGN",
                "reference" => $referenceId,
                "callback_url" => route('approve-paystack-payment'),
                "channels" => ["bank", "ussd", "card"]
            ], $this->headerParams);
            
            // Decode the response gotten....
            $decodeReserve = json_decode((string) $reserveResult, true);
            
            if($decodeReserve['status'] === true) {
                $authorizedUrl = $decodeReserve['data']['authorization_url'];

                if (!filter_var($authorizedUrl, FILTER_VALIDATE_URL)) {
                    return false;
                }
                return $authorizedUrl;
            }
            return false;
        }
        catch(Exception $e) {
            return false;
        }
    }

    public function ApprovePayment(PaystackRequest $request) {
        try {
            $paymentData = $request->validated();
            $referenceId = $paymentData['reference'];
            $verifyPayment = $this->verifyPayment($referenceId);
            if(!$verifyPayment) {
                Alert::error('Error', "Your payment was declined. Incase of a debited transaction, Your payment reference is $referenceId");
                Payment::where('reference', $referenceId)->update(['status' => '2']);
                return redirect()->route('view-cart');
            }
            Alert::success('Success', "Your payment was successful. Hotel has been reseerved. Your reserve id is $referenceId");
            Payment::where('reference', $referenceId)->update(['status' => '1']);
            ShoppingCart::where('user_id', Auth::id())->delete();
            return redirect()->route('home');
        }
        catch(Exception $e) {
            return false;
        }
    }

    private function verifyPayment($referenceId) {
        $verifyPayment = HttpRequest::sendGet($this->endpoint."/transaction/verify/".$referenceId, "", [
            "Authorization" => "Bearer ".$this->secretKey,
            "Content-Type" => "application/json",
            "Cache-Control: no-cache",
        ]);

        $decodePayment = json_decode($verifyPayment, true);

        if($decodePayment['status'] === true) {
            if($decodePayment['data']['status'] === "success" AND $decodePayment['data']['reference'] == $referenceId) {
                return true;
            }
            return false;
        }
        return false;
    }
    
}