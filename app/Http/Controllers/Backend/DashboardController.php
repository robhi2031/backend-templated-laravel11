<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Traits\SiteCommon;
use App\Traits\UserSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    use SiteCommon;
    use UserSession;

    /**
     * index
     *
     * @return void
     */
    public function index(Request $request)
    {
        $getSiteInfo = $this->get_siteinfo();
        $getUserSession = Auth::user();
        //Data WebInfo
        $data = array(
            'title' => 'Dashboard',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSiteInfo->name,
            'user_session' => $getUserSession
        );
        //Data Source CSS
        $data['css'] = array(
            'dist/plugins/bootstrap-select/css/bootstrap-select.min.css',
            'dist/plugins/Magnific-Popup/magnific-popup.css',
            'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css'
        );
        //Data Source JS
        $data['js'] = array(
            'dist/plugins/bootstrap-select/js/bootstrap-select.min.js',
            'dist/plugins/Magnific-Popup/jquery.magnific-popup.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/locales/bootstrap-datepicker.id.min.js',
            'https://code.highcharts.com/highcharts.js',
            'https://code.highcharts.com/modules/series-label.js',
            'https://code.highcharts.com/modules/exporting.js',
            'https://code.highcharts.com/modules/export-data.js',
            'https://code.highcharts.com/modules/accessibility.js',
            config('app.env') === 'production' ? 'dist/js/app.backend.init.min.js' : 'dist/js/app.backend.init.js',
            config('app.env') === 'production' ? 'dist/scripts/backend/main.init.min.js' : 'dist/scripts/backend/main.init.js'
        );

        addToLog('Mengakses halaman ' . $data['title'] . ' - Backend');
        return view('backend.index', compact('data'));
    }
}
