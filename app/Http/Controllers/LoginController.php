<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\SmsIR_UltraFastSend;

class LoginController extends Controller
{

    public $mobile_email;
    public $kind;

    public function __construct()
    {
        
        $this->kind = session('kind');

        $this->mobile_email = session('mobile');

        if(! session('mobile')) {
            $this->mobile_email = session('email');
        }
    }


    public function login(Request $request)
    {

        $this->is_user_input_mobile_or_email($request);

        if ($this->IS_new_user()) {
            $this->send_virificaion_code();
            return;
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
        
      

        if (User::where($this->kind , $this->mobile_email)->exists()) {
            return false;
        }

        return true;
    
    }
    
    
    public function send_virificaion_code()
    {

        $code = rand(10000, 99999);

        session('code' , $code);

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

        if ($request->code == session('code')) {
            session('code_virified' , true);
            return 'true';
        } else {
            return session('mobile');
        }

    }

    public function set_username_and_password(Request $request)
    {
        
        if (! session('code_virified')) {
            return;
        }


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
        
        if ($user = User::where([
            [ $this->kind , '=' , $this->mobile_email],
            [ 'password', '=' , $request->passowrd ]
        ])->exists()) {

            return Auth::login($user);
        }

    }

    public function update_password()
    {
        
    }



}
