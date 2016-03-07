<?php

namespace ruddy\Base\Server;
use Sinergi\BrowserDetector as BD;


class HTTP implements \ArrayAccess
{
    private $var = array();

    public function __construct()
    {
        $device     = new BD\Device();
        $browser    = new BD\Browser();

        $this->var['IP']            = $this->getIP();
        $this->var['USER_AGENT']    = filter_input(INPUT_SERVER, 'HTTP_USER_AGENT');
        $this->var['HOST']          = filter_input(INPUT_SERVER, 'HTTP_HOST');
        $this->var['DEVICE']        = $device->getName();
        $this->var['OS']            = $this->getOS($this->var['USER_AGENT']);
        $this->var['BROWSER']       = $browser->getName();
        $this->var['CONNECTION']    = filter_input(INPUT_SERVER, 'HTTP_CONNECTION');
        $this->var['SECURE']        = filter_input(INPUT_SERVER, 'HTTPS');

    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->var[] = $value;
        } else {
            $this->var[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->var[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->var[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->var[$offset]) ? $this->var[$offset] : null;
    }

    private function getOS($user_agent) {
        $os = new BD\Os();
        $os_platform = "Unknown";
        $os_array = array(
            '/windows nt 10/i'      =>  'Windows 10',
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        );

        foreach ($os_array as $regex => $value) {
            if (preg_match($regex, $user_agent)) {
                $os_platform = $value;
            }
        }

        if($os_platform == 'Unknown'){
            return $os->getName();
        }

        return $os_platform;
    }

    private function getIP()
    {
        $ip = '127.0.0.1';

        if($this->filter('HTTP_CLIENT_IP') && $this->filter('HTTP_CLIENT_IP') != '127.0.0.1') {
            $ip = filter_input(INPUT_SERVER, 'HTTP_CLIENT_IP');

        } else if($this->filter('X-Forwarded-For') && $this->filter('X-Forwarded-For') != '127.0.0.1') {
            $ip = filter_input(INPUT_SERVER, 'X-Forwarded-For');

        } else if($this->filter('HTTP_FORWARDED') && $this->filter('HTTP_FORWARDED') != '127.0.0.1') {
            $ip = filter_input(INPUT_SERVER, 'HTTP_FORWARDED');

        } else if($this->filter('HTTP_X_FORWARDEDX') && $this->filter('HTTP_X_FORWARDEDX') != '127.0.0.1') {
            $ip = filter_input(INPUT_SERVER, 'HTTP_X_FORWARDED');

        } else if($this->filter('HTTP_X_FORWARDED_FOR') && $this->filter('HTTP_X_FORWARDED_FOR') != '127.0.0.1') {
            $ip = filter_input(INPUT_SERVER, 'HTTP_X_FORWARDED_FOR');

        } else if($this->filter($_SERVER['REMOTE_ADDR']) && $this->filter('REMOTE_ADDR') != '127.0.0.1'){
            $ip = filter_input(INPUT_SERVER, 'REMOTE_ADDR');
        }

        return $ip;
    }

    private function filter($variable_name, $filter = FILTER_VALIDATE_IP, $filter2 = FILTER_FLAG_IPV4)
    {
        return filter_input(INPUT_SERVER, $variable_name, $filter, $filter2);
    }
} 