<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ActivityLogs;
use App\Traits\SiteCommon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ActivityLogsController extends Controller
{
    use SiteCommon;
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('direct_permission:kelola-pengguna-log-aktivitas-read', only: ['index', 'show']),
            // new Middleware('direct_permission:kelola-pengguna-log-aktivitas-create', only: ['store']),
            // new Middleware('direct_permission:kelola-pengguna-log-aktivitas-update', only: ['update']),
            new Middleware('direct_permission:kelola-pengguna-log-aktivitas-delete', only: ['delete']),
        ];
    }
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
            'title' => 'Log Aktivitas User',
            'url' => url()->current(),
            'app_version' => config('app.version'),
            'app_name' => $getSiteInfo->name,
            'user_session' => $getUserSession
        );
        //Data Source CSS
        $data['css'] = array(
            'dist/plugins/custom/datatables/datatables.bundle.css',
            'dist/plugins/bootstrap-select/css/bootstrap-select.min.css',
            'dist/plugins/Magnific-Popup/magnific-popup.css',
        );
        //Data Source JS
        $data['js'] = array(
            'dist/plugins/custom/datatables/datatables.bundle.js',
            'dist/plugins/bootstrap-select/js/bootstrap-select.min.js',
            'dist/plugins/Magnific-Popup/jquery.magnific-popup.min.js',
            'https://npmcdn.com/flatpickr@4.6.13/dist/l10n/id.js',
            config('app.env') === 'production' ? 'dist/js/app.backend.init.min.js' : 'dist/js/app.backend.init.js',
            config('app.env') === 'production' ? 'dist/scripts/backend/user_activities.init.min.js' : 'dist/scripts/backend/user_activities.init.js'
        );

        addToLog('Mengakses halaman ' .$data['title']. ' - Backend');
        return view('backend.user_activities', compact('data'));
    }
    /**
     * show
     *
     * @param  mixed $request
     * @return void
     */
    public function show(Request $request)
    {
        $query = ActivityLogs::selectRaw("activity_logs.*,
            TO_CHAR(activity_logs.timestamp, 'DD/MM/YYYY') AS datestamp_indo,
            TO_CHAR(activity_logs.timestamp, 'HH24:MI') AS timestamp_indo,
            b.name, 'action' as action")
            ->leftJoin('users_system AS b', 'b.id', '=', 'activity_logs.fid_user');
        if(isset($request->tgl_start) && isset($request->tgl_end)){
            $tgl_start = str_replace('/', '-', $request->tgl_start);
            $tgl_start = date('Y-m-d', strtotime($tgl_start));
            $tgl_end = str_replace('/', '-', $request->tgl_end);
            $tgl_end = date('Y-m-d', strtotime($tgl_end));
            $query = $query->whereRaw('DATE(activity_logs.timestamp) BETWEEN ' ."'".$tgl_start."'". ' AND ' ."'".$tgl_end."'");
        }
        $data = $query->orderBy('activity_logs.timestamp', 'DESC')->get();
        $output = Datatables::of($data)->addIndexColumn()
            ->addColumn('user', function ($row) {
                $userCustom = $row->ip_address;
                if(isset($row->name)) {
                    $userCustom = $row->name.'<br/> <span class="text-muted">'.$row->ip_address.'</span>';
                }
                return $userCustom;
            })
            ->editColumn('description', function ($row) {
                $userCustom = $row->description.'<br/> <span class="text-muted">'.$row->url.'</span>';
                return $userCustom;
            })
            ->editColumn('timestamp', function ($row) {
                $userCustom = $row->datestamp_indo.' '.$row->timestamp_indo.' <br/> <span class="text-muted"><em>'.time_ago($row->timestamp).'</em></span>';
                return $userCustom;
            })
            ->rawColumns(['user', 'description', 'timestamp'])
            ->make(true);

        return $output;
    }
    /**
     * delete
     *
     * @param  mixed $request
     * @return void
     */
    public function delete(Request $request) {
        if(isset($request->startDate) && isset($request->endDate)){
            DB::beginTransaction();
            try {
                $startDate = str_replace('/', '-', $request->startDate);
                $endDate = str_replace('/', '-', $request->endDate);
                ActivityLogs::whereRaw('DATE(activity_logs.timestamp) BETWEEN ' ."'".date('Y-m-d', strtotime($startDate))."'". ' AND ' ."'".date('Y-m-d', strtotime($endDate))."'")->delete();
                addToLog('Delete all user activity log data from the date: '.$request->startDate. ' to '.$request->endDate);
                DB::commit();
                return jsonResponse(true, 'Semua data aktivitas user dari tanggal: <strong>'.$request->startDate.'</strong> sampai dengan <strong>'.$request->endDate.'</strong> berhasil dibersihkan', 200);
            } catch (Exception $exception) {
                DB::rollBack();
                return jsonResponse(false, $exception->getMessage(), 401, [
                    "Trace" => $exception->getTrace()
                ]);
            }
        } else {
            DB::beginTransaction();
            try {
                ActivityLogs::truncate();
                addToLog('Delete all user activity logs has been successfully');
                DB::commit();
                return jsonResponse(true, 'Semua data aktivitas user berhasil dibersihkan', 200);
            } catch (Exception $exception) {
                DB::rollBack();
                return jsonResponse(false, $exception->getMessage(), 401, [
                    "Trace" => $exception->getTrace()
                ]);
            }
        }
    }
}
