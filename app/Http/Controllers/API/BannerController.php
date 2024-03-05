<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\ImageAnnouncements;
use App\Models\Settings;

class BannerController extends Controller
{
    public function get_banners()
    {
        $banner = Banner::where("visible", "=", 1)->get();
        return $banner;
    }

    public function get_image_banners()
    {
        $banner = ImageAnnouncements::where("visible", "=", 1)->get();
        return $banner;
    }

    public function get_settings()
    {
        $settings = Settings::where("id", "=", 1)->first();
        return $settings;
    }
}
