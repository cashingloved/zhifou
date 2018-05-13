<?php


namespace App\Mailer;

use Mail;

class Mailer
{
    public function sendTo($email,array $data)
    {
        $data['url'] = $this->curl($data['url'],'get','');
        $data['url'] = json_decode($data['url'], true)[0]['url_short'];
        $content = $data['url'];
        Mail::send('mail.mail',['content'=>$content], function ($message)  use($email,$content){
            $message->from('rosae_tempus@163.com', '知否');
            $message->subject("这是一封来自[知否]的激活邮件...");
            $message->to($email,$content);
        });
    }

    public function curl($url,$method,$post_data = 0){
        $url = 'http://api.t.sina.com.cn/short_url/shorten.json?source=3271760578&url_long='.$url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($method == 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }elseif($method == 'get'){
            curl_setopt($ch, CURLOPT_HEADER, 0);
        }
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}