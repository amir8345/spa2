<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\SmsIR_UltraFastSend;
use Carbon\Carbon;

class LoginController extends Controller
{
    
    
    public function is_user_input_mobile_or_email($request)
    {
        if(strlen($request->mobile_email) == 11){
            session(['kind' => 'mobile' , 'mobile_email' => $request->mobile_email]);
            return;
        }
        
        session(['kind' => 'email' , 'mobile_email' => $request->mobile_email]);
    }
    
    
    public function IS_new_user()
    {
        if (User::where(session('kind') , session('mobile_email'))->exists()) {
            return false;
        }
        
        return true;
    }
    
    public function login(Request $request)
    {

        $this->is_user_input_mobile_or_email($request);
        
        if ($this->IS_new_user()) {
            return 'new user';
        }
        
        return 'already a user';
    }
    
    public function send_code()
    {
        
        $code = rand(10000, 99999);
        session(['code' => $code]);
        
        if(session('kind') == 'mobile') {
            
            date_default_timezone_set("Asia/Tehran");
            
            // your sms.ir panel configuration
            $APIKey = "ebd589b45f766ecb146b89b4";
            $SecretKey = "KdjfkGj684UyfYt";
            $APIURL = "https://ws.sms.ir/";
            
            // message data
            $data = array(
                "ParameterArray" => array(
                    array(
                        "Parameter" => "VerificationCode",
                        "ParameterValue" => $code
                    ),
                ),
                "Mobile" => session('mobile'),
                "TemplateId" => "35473"
            );
            
            $SmsIR_UltraFastSend = new SmsIR_UltraFastSend($APIKey, $SecretKey, $APIURL);
            $UltraFastSend = $SmsIR_UltraFastSend->ultraFastSend($data);
            return $UltraFastSend;
            
        }
        
    }
    
    public function code_verification(Request $request)
    {
        
        if ($request->code != session('code')) {
            return 'wrong code';
        }
        
        session(['code_verified' => true]);
        return 'right code';
        
    }
    
    public function set_username_password(Request $request)
    {
        
        if (! session('code_verified')) {
            return 'code is not verified yet';
        }
        
        $id = User::insertGetId([
            session('kind') => session('mobile_email'),
            'username' => $request->username,
            'password' => Hash::make($request->password)
        ]);
        
        if(Auth::loginUsingId($id)){
            return 'authentication successfull';
        }
        return 'authentication failed';
        
    }
    
    
    public function password_check(Request $request)
    {
        $credentials = [session('kind') => session('mobile_email') , 'password' => $request->password];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return 'authentication successfull';
        }
 
        return 'wrong password';
    }

    

    public function update_password(Request $request)
    {
        if (! session('code_verified')) {
            return 'code has not verified yet';
        }
        
        if($request->password != $request->repassword) {
            return 'passwords do not match';
        }
        
        $user = User::where(session('kind') , session('mobile_email'))->first();
        $user->update(['password' => Hash::make($request->passowrd)]);
            
        Auth::login($user);
        return 'password updated successfully';
    }
    
    
    public function disposable_code()
    {

        if (! session('code_verified')) {
            return 'code is not verified yet';
        }

        $user = User::where(session('kind') , session('mobile_email'))->first();
        
        Auth::login($user);
        
        return 'authentication successfull';
        
    }
    
    
    public function logout()
    {
        if (Auth::logout()) {
            return 'logout successfull';
        }
        return 'can not log out';
    }
    
}
