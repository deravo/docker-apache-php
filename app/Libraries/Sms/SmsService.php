<?php
namespace App\Libraries\Sms;

use App;

class SmsService implements SmsContract
{
    public $config     = [];

    private $apikey     = '';

    private $username   = '';
    private $password   = '';
    private $proxy      = '';

    public function __construct($config = '')
    {
        if ( $config )
        {
            $this->loadConfig($config);
        }
    }

    public function callMe($controller)
    {
        dd('Call Me From TestServiceProvider In '.$controller);
    }

    public function send($targetPhone, $message)
    {
        $target = $this->proxy . http_build_query(array(
            'apikey'    => $this->apikey,
            'username'  => $this->username,
            'password'  => $this->password,
            'mobile'    => is_array($targetPhone) ? implode(",", $targetPhone) : $targetPhone,
            'content'   => $message
        ));
        $result = file_get_contents($target);
        return strtolower(substr($result, 0, 7)) == 'success';
    }

    public function loadConfig($conf)
    {
        if ( $conf )
        {
            App::configure($conf);
            $this->config = config($conf);
            if ( is_array($this->config) && isset($this->config['apikey']) && isset($this->config['proxy']) )
            {
                $this->apikey = $this->config['apikey'];
                $this->username = !isset($this->config['username'])?:$this->config['username'];
                $this->password = !isset($this->config['password'])?:$this->config['password'];
                $this->proxy = $this->config['proxy'];
            }
        }
    }
}
