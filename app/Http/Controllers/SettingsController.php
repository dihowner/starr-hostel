<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function getAllSettings() {
        $allSettings = Settings::latest('id')->get();
        
        if(count($allSettings) > 0) {
            foreach($allSettings as $settingIndex => $settingValue) {
                $newSetting[$settingValue['name']] = $settingValue['value'];
            }
            return $newSetting;
        }
        return false;
    }

    public function getSettings($key) {
        return Settings::where('name', $key)->first();
    }
}