<?php


namespace App\Mailer;

use Mail;

class Mailer
{
    public function sendTo($email,array $data)
    {
        $content = "请将以下地址复制到地址栏以激活您的账号：{$data['url']}";
        Mail::raw($content, function ($message)  use($email){
            $message->from('rosae_tempus@163.com', '知否');
            $message->subject("这是一封来自[知否]的激活邮件...");
            $message->to($email);
        });
    }
}