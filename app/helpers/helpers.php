<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\GeneralSetting;
use App\Models\SocialNetwork;
use App\Models\Category;
use App\Models\SubCategory;

/**SEND EMAIL FUNCTION USING PHPMAILER LIBLARY */
if(!function_exists('sendMail') ){
    function sendMail($mailConfig){
        require 'PHPMailer/src/Exception.php';
        require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';

        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = env('EMAIL_HOST');
        $mail->SMTPAuth = true;
        $mail->Username = env('EMAIL_USERNAME');
        $mail->Password = env('EMAIL_PASSWORD');
        $mail->SMTPSecure = env('EMAIL_ENCRYPTION');
        $mail->Port = env('EMAIL_PORT');
        $mail->setFrom($mailConfig['mail_from_email'],$mailConfig['mail_from_name']);
        $mail->addAddress($mailConfig['mail_recipient_email'],$mailConfig['mail_recipient_name']);
        $mail->isHTML(true);
        $mail->Subject = $mailConfig['mail_subject'];
        $mail->Body = $mailConfig['mail_body'];
        if($mail->send()){
            return true;
        }else{
            return false;
        }
    }
}

/** Get general settings */
if( !function_exists('get_settings') ){
    function get_settings(){
        $results = null;
        $settings = new GeneralSetting();
        $settings_data = $settings->first();

        if( $settings_data){
            $results = $settings_data;
        }else{
            $settings->insert([
                'site_name'=>'smkn4tngMarketPlace',
                'site_email'=>'azrilmalika@gmail.com'
            ]);
            $new_settings_data = $settings->first();
            $results = $new_settings_data;
        }
        return $results;
    }
}

/** get social networks */
if(!function_exists('get_social_network')){
    function get_social_network(){
        $results = null;
        $social_network = new SocialNetwork();
        $social_network_data = $social_network->first();

        if( $social_network_data){
            $results = $social_network_data;
        }else{
            $social_network->insert([
                'facebook_url'=>null,
                'X_url'=>null,
                'instagram_url'=>null
            ]);
            $new_social_network_data = $social_network->first();
            $results = $new_social_network_data;
        }
        return $results;
    }
}

//frontend::
/** get frontend categories */
if(!function_exists('get_categories')){
    function get_categories(){
        $categories = Category::with('subcategories')->orderBy('ordering','asc')->get();
        return !empty($categories) ? $categories : [];
    }
}