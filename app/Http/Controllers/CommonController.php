<?php

namespace App\Http\Controllers;

use App\Models\ProfilInstansi;
use App\Traits\SiteCommon;
use App\Traits\UserSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommonController extends Controller
{
    use SiteCommon;
    use UserSession;

    /**
     * site_info
     *
     * @param  mixed $request
     * @return void
     */
    public function site_info(Request $request)
    {
        try {
            $getRow = $this->get_siteinfo();
            if ($getRow != null) {
                $getRow->instansiInfo = ProfilInstansi::selectRaw("name, short_description, email, phone_number, office_address, office_address_coordinate")->whereId(1)->first();
                return jsonResponse(true, 'Success', 200, $getRow);
            } else {
                return jsonResponse(false, "Credentials not match", 401);
            }
        } catch (Exception $exception) {
            return jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    /**
     * user_info
     *
     * @param  mixed $request
     * @return void
     */
    public function user_info(Request $request)
    {
        $username = Auth::user()->username;
        try {
            $getRow = $this->get_userinfo($username);
            if ($getRow != null) {
                return jsonResponse(true, 'Success', 200, $getRow);
            } else {
                return jsonResponse(false, "Credentials not match", 401);
            }
        } catch (Exception $exception) {
            return jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
}
