<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\SmsIR_UltraFastSend;

class SignupController extends Controller
{

    public function mobile(Request $request) {


        $code = rand(10000 , 99999);
        $mobile = $request->mobile;

        session(['code' => $code]);

        $this->sendSMS($mobile , $code);
        // return $code;
    }

    public function sendSMS($mobile , $code)
    {
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
            "Mobile" => $mobile,
            "TemplateId" => "35473"
        );

        $SmsIR_UltraFastSend = new SmsIR_UltraFastSend($APIKey, $SecretKey, $APIURL);
        $UltraFastSend = $SmsIR_UltraFastSend->ultraFastSend($data);
        var_dump($UltraFastSend);
        
    }

    public function code_verification(Request $request)
    {
        if ($request->code == session('code')) {
            return 'yes';
        }
    }

    public function set_username_password(Request $request)
    {
        return $request->username . ' ' . $request->password . ' ' . $request->repassword;
    }
    
}
