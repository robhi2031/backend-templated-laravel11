<?php

namespace App\Traits;

use App\Models\SiteInfo;

trait SiteCommon
{
        /**
     * get_systeminfo
     *
     * @return void
     */
    protected function get_siteinfo()
    {
        $getRow = SiteInfo::first();
        if($getRow==true){
            //Login Bg Site
            $login_bg = $getRow->login_bg;
            if($login_bg==''){
                $getRow->login_bg_url = NULL;
            } else {
                if (!file_exists(public_path(). '/dist/img/site-img/'.$login_bg)){
                    $getRow->login_bg_url = NULL;
                    $getRow->login_bg = NULL;
                }else{
                    $getRow->login_bg_url = url('dist/img/site-img/'.$login_bg);
                }
            }
            //Login Logo Site
            $login_logo = $getRow->login_logo;
            if($login_logo==''){
                $getRow->login_logo_url = NULL;
            } else {
                if (!file_exists(public_path(). '/dist/img/site-img/'.$login_logo)){
                    $getRow->login_logo_url = NULL;
                    $getRow->login_logo = NULL;
                }else{
                    $getRow->login_logo_url = url('dist/img/site-img/'.$login_logo);
                }
            }
            //Head Backend Logo
            $headbackend_logo = $getRow->headbackend_logo;
            if($headbackend_logo==''){
                $getRow->headbackend_logo_url = NULL;
            } else {
                if (!file_exists(public_path(). '/dist/img/site-img/'.$headbackend_logo)){
                    $getRow->headbackend_logo_url = NULL;
                    $getRow->headbackend_logo = NULL;
                }else{
                    $getRow->headbackend_logo_url = url('dist/img/site-img/'.$headbackend_logo);
                }
            }
            //Head Backend Logo Dark
            $headbackend_logo_dark = $getRow->headbackend_logo_dark;
            if($headbackend_logo_dark==''){
                $getRow->headbackend_logo_dark_url = NULL;
            } else {
                if (!file_exists(public_path(). '/dist/img/site-img/'.$headbackend_logo_dark)){
                    $getRow->headbackend_logo_dark_url = NULL;
                    $getRow->headbackend_logo_dark = NULL;
                }else{
                    $getRow->headbackend_logo_dark_url = url('dist/img/site-img/'.$headbackend_logo_dark);
                }
            }
            //Head Backend Icon
            $headbackend_icon = $getRow->headbackend_icon;
            if($headbackend_icon==''){
                $getRow->headbackend_icon_url = NULL;
            } else {
                if (!file_exists(public_path(). '/dist/img/site-img/'.$headbackend_icon)){
                    $getRow->headbackend_icon_url = NULL;
                    $getRow->headbackend_icon = NULL;
                }else{
                    $getRow->headbackend_icon_url = url('dist/img/site-img/'.$headbackend_icon);
                }
            }
            //Head Backend Icon Dark
            $headbackend_icon_dark = $getRow->headbackend_icon_dark;
            if($headbackend_icon_dark==''){
                $getRow->headbackend_icon_dark_url = NULL;
            } else {
                if (!file_exists(public_path(). '/dist/img/site-img/'.$headbackend_icon_dark)){
                    $getRow->headbackend_icon_dark_url = NULL;
                    $getRow->headbackend_icon_dark = NULL;
                }else{
                    $getRow->headbackend_icon_dark_url = url('dist/img/site-img/'.$headbackend_icon_dark);
                }
            }
            //Keyword to Explode
            $getRow->keyword_explode = explode(',', $getRow->keyword);
            return $getRow;
        } else {
            return null;
        }
    }
}
