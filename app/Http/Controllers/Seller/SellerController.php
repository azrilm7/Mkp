<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\Seller;
use App\Models\VerificationToken;

class SellerController extends Controller
{
    public function login(Request $request){
        $data = [
            'pageTitle'=>'Seller login'
        ];
        return view('back.pages.seller.auth.login',$data);
    }

    public function register(Request $request){
        $data = [
            'pageTitle'=>'create seller account'
        ];
        return view('back.pages.seller.auth.register',$data);
    }

    public function home(Request $request){
        $data = [
            'pageTitle'=>'Seller dashboard'
        ];
        return view('back.pages.seller.home',$data);
    }

    public function createSeller(Request $request){
        //validate
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:sellers',
            'password'=>'min:5|required_with:confirm_password|same:confirm_password',
            'confirm_password'=>'min:5'
        ]);
        $seller = new Seller();
        $seller->name = $request->name;
        $seller->email = $request->email;
        $seller->password = Hash::make($request->password);
        $saved = $seller->save();

        if($saved){
            //generate token
            $token = base64_encode(Str::random(64));

            VerificationToken::create([
                'user_type'=>'seller',
                'email'=>$request->email,
                'token'=>$token
            ]);

            $actionLink = route('seller.verify',['token'=>$token]);
            $data['action_link'] = $actionLink;
            $data['seller_name'] = $request->name;
            $data['seller_email'] = $request->email;

            //send activation link
            $mail_body = view('email-templates.seller-verify-template',$data)->render();

            $mailConfig = array(
                'mail_from_email'=>env('EMAIL_FROM_ADDRES'),
                'mail_from_name'=>env('EMAIL_FROM_NAME'),
                'mail_recipient_email'=>$request->email,
                'mail_recipient_name'=>$request->name,
                'mail_subject'=>'verify seller account',
                'mail_body'=>$mail_body
            );
            if( sendMail($mailConfig)){
                return redirect()->route('seller.register-success');
            }else{
                return redirect()->route('seller.register')->with('fail','something went wrong while sending');
            }

        }else{
            return redirect()->route('seller.register')->with('fail','something went wrong');
        }
    }

    public function registerSuccess(Request $request){
        return view('back.pages.seller.register-success');
    }
}
