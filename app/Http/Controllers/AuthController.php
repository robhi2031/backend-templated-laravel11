<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\SiteCommon;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    use SiteCommon;

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //Data Site Info
        $getSiteInfo = $this->get_siteinfo();
        $data = array(
            'title' => 'Login',
            'desc' => 'Halaman login aplikasi ' . $getSiteInfo->name,
            'keywords' => 'login, sign in, masuk ke aplikasi, ' . $getSiteInfo->keyword,
            'url' => url()->current(),
            'thumb' => $getSiteInfo->randomThumb_url,
            'app_version' => config('app.version'),
            'app_name' => $getSiteInfo->name
        );
        //Data Source CSS
        $data['css'] = null;
        //Data Source JS
        $data['js'] = array(
            config('app.env') === 'production' ? 'dist/js/app.auth.init.min.js' : 'dist/js/app.auth.init.js'
        );
        addToLog('Mengakses halaman ' . $data['title']);
        return view('auth.index', compact('data'));
    }
    /**
     * first_login
     *
     * @param  mixed $request
     * @return void
     */
    public function first_login(Request $request)
    {
        try {
            $username = $request->username;
            $user = User::where('email', $username)->first();
            if ($user) {
                $output = array(
                    'username' => $user->username,
                    'name' => $user->name,
                    'email' => $user->email,
                    'is_active' => $user->is_active,
                );
            } else {
                $user = User::where('username', $username)->first();
                if ($user) {
                    $output = array(
                        'username' => $user->username,
                        'name' => $user->name,
                        'email' => $user->email,
                        'is_active' => $user->is_active,
                    );
                } else {
                    addToLog('First step login failed, System cannot find user according to the Username or Email entered!');
                    return jsonResponse(false, 'Sistem tidak dapat menemukan akun user', 200);
                }
            }
            if ($user->is_active == 'N') {
                addToLog('First step login failed, The user is currently disabled!');
                return jsonResponse(false, 'Akun user sedang dinonactifkan, silahkan coba lagi nanti atau hubungi admin untuk mengatasi masalah ini.', 200);
            }
            addToLog('First step login was successful, username and email found');
            return jsonResponse(true, 'Success', 200, $output);
        } catch (Exception $exception) {
            addToLog($exception->getMessage());
            return jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    /**
     * second_login
     *
     * @param  mixed $request
     * @return void
     */
    public function second_login(Request $request)
    {
        try {
            $email = $request->hideMail;
            $password = $request->password;
            $user = User::where('email', $email)->first();
            if(!$user) {
                addToLog('Second step login failed, The system could not find the user according to the account Email!');
                return jsonResponse(false, 'Akun user tidak sesuai, Silahkan gunakan akun yang terdaftar pada sistem!', 200, ['error_code' => 'EMAIL_NOT_VALID']);
            }
            $hashedPassword = $user->password;
            if (!Hash::check($password, $hashedPassword)) {
                addToLog('Second step login failed, System cannot find user according to the Password entered!');
                return jsonResponse(false, 'Password yang dimasukkan tidak sesuai, coba lagi dengan password yang benar!', 200, ['error_code' => 'PASSWORD_NOT_VALID']);
            }
            Auth::login($user);
            //Created Token Sanctum
            $request->user()->createToken('api-token')->plainTextToken;
            addToLog('Second step login successful, the user session has been created');
            //Update Data User Session
            User::where('id', auth()->user()->id)->update([
                'is_login' => 'Y',
                'ip_login' => getUserIp(),
                'last_login' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
            //Set Cookie
            $arrColor = ["text-primary", "text-success", "text-info", "text-warning", "text-danger", "text-dark"];
            $randomColor = array_rand($arrColor, 2);
            $expCookie = 86400; //24Jam
            Cookie::queue('username', Auth::user()->username, $expCookie);
            Cookie::queue('email', Auth::user()->email, $expCookie);
            Cookie::queue('userThumb_color', $arrColor[$randomColor[0]], $expCookie);
            Cookie::queue('remember', TRUE, $expCookie);

            $lastVisitedUrl = null;
            // Cek apakah ada URL terakhir dalam session
            if (Session::has('last_user') && Session::get('last_user') == Auth::user()->username) {
                if (Session::has('last_visited_url')) {
                    $lastVisitedUrl = Session::get('last_visited_url');
                    Session::forget('last_visited_url');
                }
            }
            return jsonResponse(true, 'Success', 200, ['last_visited_url' => $lastVisitedUrl]);
        } catch (Exception $exception) {
            return jsonResponse(false, $exception->getMessage(), 401, [
                "Trace" => $exception->getTrace()
            ]);
        }
    }
    /**
     * logout_sessions
     *
     * @param  mixed $request
     * @return void
     */
    public function logout_sessions(Request $request)
    {
        $arrayCookie = array();
        foreach (Cookie::get() as $key => $item) {
            $arrayCookie[] = cookie($key, null, -2628000, null, null);
        }
        $username = auth()->user()->username;
        auth()->user()->tokens()->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        session()->flush();
        Artisan::call('cache:clear');
        Session::put('last_user', $username);
        Session::put('last_visited_url', url()->previous());
        return redirect('/auth');
    }
}
