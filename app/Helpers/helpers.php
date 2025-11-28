<?php
use Carbon\Carbon;
use App\Models\General_setting;

if (! function_exists('preview')) {
    function preview($data) {
        echo "<pre>";
        print_r ($data);
        exit;
    }
}

if (! function_exists('format_date')) {
    function format_date($date) {
        return date("d M, Y",strtotime($date));
    }
}

if (! function_exists('format_date_time')) {
    function format_date_time($date) {
        return date("d M, Y h:i A",strtotime($date));
    }
}

if (! function_exists('currency')) {
    function currency() {
        return "₹";
    }
}

if (! function_exists('general_settings')) {
    function general_settings($key) {
        $row = General_setting::where("setting_key",$key)->first();
        if($row) {
            return $row->setting_val;
        } else {
            return '';
        }
    }
}
