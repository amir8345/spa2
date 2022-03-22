<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\SmsIR_UltraFastSend;

class LoginController extends Controller
{

    public $mobile_email;
    public $kind;

    public function define_kind_and_mobile_email()
    {
        $this->mobile_email = session('mobile');
        $this->kind = session('kind');

        if(! session('mobile')) {
            $this->mobile_email = session('email');
        }
    }


    public function login(Request $request)
    {

        $this->is_user_input_mobile_or_email($request);

        if ($this->IS_new_user()) {
            return 'new_user';
        }

        return 'not_new_user';

    }

    public function is_user_input_mobile_or_email($request)
    {
        if(strlen($request->input) == 11){
            session(['kind' => 'mobile' , 'mobile' => $request->input]);
            return;
        }

        session(['kind' => 'email' , 'email' => $request->input]);
    }
    

    public function IS_new_user()
    {
        $this->define_kind_and_mobile_email();

        if (User::where($this->kind , $this->mobile_email)->exists()) {
            return false;
        }

        return true;
    }
    
    
    public function send_virification_code()
    {
        
        $this->define_kind_and_mobile_email();
        
        $code = rand(10000, 99999);
        session('code' , $code);
        dd($this->mobile_email);

        if($this->mobile_email == 'mobile') {

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
            var_dump($UltraFastSend);
        
        }

    }

    public function check_verificaion_code(Request $request)
    {

        if (! $request->code == session('code')) {
            return 'wrong_code';
        }

        session('code_verified' , true);
        return 'right_code';

    }

    public function set_username_and_password(Request $request)
    {
        
        if (! session('code_virified')) {
            return;
        }

        $this->define_kind_and_mobile_email();

        $id = User::insertGetId([
            $this->kind => $this->mobile_email,
            'username' => $request->username,
            'password' => $request->password
        ]);

        if(Auth::loginUsingId($id)){
            return 'authentication successfull';
        }

    }


    public function passowrd_check(Request $request)
    {

        $this->define_kind_and_mobile_email();
        
        if (! $user = User::where([
            [ $this->kind , '=' , $this->mobile_email],
            [ 'password', '=' , $request->passowrd ]
        ])->exists()) {
            return 'wrong password';
        }
        
        if(Auth::login($user)) {
            return 'authentication successfull';
        }
    }

    public function update_password(Request $request)
    {
        if (! session('code_virified')) {
            return 'code has not verified yet';
        }

        if(! $request->password == $request->repassword) {
            return 'passwords do not match';
        }

        $this->define_kind_and_mobile_email();

        if ($user =  User::where($this->kind , $this->mobile_email)
        ->update(['password' => Hash::make($request->passowrd) ])) {
            Auth::login($user);
            return 'password updated successfully';
        }

    }


    public function login_with_disposable_code(Request $request)
    {
        if (! $request->code == session('code')) {
            return;
        }

        $this->define_kind_and_mobile_email();

        $user = User::where($this->kind , $this->mobile_email)->get();

        if (Auth::login($user)) {
            return 'authentication successfull';
        }
        
    }

}
