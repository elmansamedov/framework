<?php

namespace vendor\libs\email;


include "libmail.php";

class Email{

    private static $lang;

    public static function email($to, $them, $text, $from = null,){

        if($from === null)$from = mailServer;
        $text = self::head() . self::main($text) . self::footerZayki();
        $m = new \Mail('', '', ''); // начинаем
        $m->From($from); // от кого отправляется почта
        $m->To($to); // кому адресованно
        $m->Subject($them);
        $m->Body($text, 'html');
        $m->smtp_on(smtpServer, smtpLogin, smtpPass, 465); // если указана эта команда, отправка пойдет через SMTP
        $m->autoCheck(false);
        $m->Send();    // а теперь пошла отправка
    }

    private static function main($mess){
        return "<div style='background-color: white;padding: 17px 14px;margin:14px;border-radius: 4px;box-shadow: 0 1px 0 0 #d7d8db, 0 0 0 1px #e3e4e8;'>
        " . $mess . "
        </div>
        ";
    }

    private static function footerZayki(){
        return "<br><a href='" . ssl . subd . "." .  domain . "' target='_blank'>" . ssl . domain . "</a><br><p>Не отвечайте на это письмо, оно создано автоматически. С уважением " . siteName . "</p>";
    }

    private static function head(){
        return "<img style='border-radius:5px;' src='" . ssl . domain . "/public/images/logo.jpg'><br>";
    }
}