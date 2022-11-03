<?php

namespace App\Controllers\Website;

class WebsiteController{

    public static function index(){
        return view("website/index");
    }

    public static function about(){
        return view("website/about");
    }

    public static function contact(){
        return view("website/contact");
    }

    public static function user(){
        return view("website/user");
    }

}
