<?php

namespace ruddy\Base\App;

class validation
{
    private $_deviceHTTP    = null;
    private $_data          = null;

    public function __construct(array $data)
    {
        $this->_deviceHTTP = (strtolower($GLOBALS['_HTTP']['DEVICE']) == 'unknown') ? 'computer' : strtolower($GLOBALS['_HTTP']['DEVICE']);
        $this->_data = (array)$data;
    }

    public function isValidURL()
    {
        if(!$this->isValidHost() || !$this->isValidURI()) {
            return false;
        }

        return true;
    }

    public function isValidPlatform()
    {
        $device = true;
        $os = true;
        $browser = true;
        if(isset($this->_data['platforms']) && !empty($this->_data['platforms'])){
            if(isset($this->_data['platforms']['devices'])) {
                $device = $this->isValidDevice();
            }

            if(isset($this->_data['platforms']['os'])) {
                $os = $this->isValidOS();
            }


            if(isset($this->_data['platforms']['browsers'])) {
                $browser = $this->isValidBrowser();
            }
        }

        if(!$device || !$os || !$browser) {
            return false;
        }

        return true;
    }

    private function isValidHost()
    {
        if(isset($this->_data['host']) && !is_null($this->_data['host'])) {
            if($this->_data['host'] != $GLOBALS['_HTTP']['HOST']) {
                return false;
            }
        } else if(isset($this->_data['hosts']) && !empty($this->_data['hosts'])) {
            for($i = 0; $i < count($this->_data['hosts']); $i++)
            {
                if($this->_data['hosts'][$i] == $GLOBALS['_HTTP']['HOST']) {
                    return true;
                }
            }
            return false;
        }

        return true;
    }

    private function isValidURI()
    {
        if(isset($this->_data['uri']) && !is_null($this->_data['uri'])) {
            $length     = strlen($this->_data['uri']);
            $match      = substr($GLOBALS['_PATH']['URI'], 0, $length);
            $lastChar   = isset($GLOBALS['_PATH']['URI'][$length]) ?  $GLOBALS['_PATH']['URI'][$length] : null;

            if($this->_data['uri'] != $match) {
                return false;
            }

            if($lastChar != '/' && $lastChar != null){
                return false;
            }
        } else if(isset($this->_data['uris']) && !empty($this->_data['uris'])) {
            $uris = $this->_data['uris'];
            for($i = 0; $i < count($uris); $i++)
            {
                $length     = strlen($uris[$i]);
                $match      = substr($GLOBALS['_PATH']['URI'], 0, $length);
                $lastChar   = isset($GLOBALS['_PATH']['URI'][$length]) ?  $GLOBALS['_PATH']['URI'][$length] : null;

                if($uris[$i] == $match && ($lastChar == '/' || $lastChar == null)) {
                    return true;
                }
            }
            return false;
        }

        return true;
    }

    private function isValidDevice()
    {
        $valid = true;
        $devices = $this->_data['platforms']['devices'];

        if(is_array($devices)){
            $firstSTR = $devices[0][0];

            switch($firstSTR)
            {
                case '!':
                    for($i = 0; $i < count($devices); $i++) {
                        $valid = true;
                        $device = str_replace($firstSTR, '', $devices[$i]);

                        if (strtolower($device) == $this->_deviceHTTP) {
                            return false;
                            break;
                        }
                    }
                    break;

                default:
                    for($i = 0; $i < count($devices); $i++) {
                        $valid = false;

                        if (strtolower($devices[$i]) == $this->_deviceHTTP) {
                            return true;
                            break;
                        }
                    }
                    break;
            }
        } else if(!is_null($devices)) {
            $firstSTR = $devices[0];

            switch($firstSTR)
            {
                case '!':
                    $valid = true;
                    $device = str_replace($firstSTR, '', $devices);

                    if(strtolower($device) == $this->_deviceHTTP) {
                        $valid = false;
                    }
                    break;

                default:
                    $valid = false;

                    if(strtolower($devices) == $this->_deviceHTTP) {
                        $valid = true;
                    }
                    break;
            }
        }

        return $valid;
    }

    private function isValidOS()
    {
        $valid = true;
        $oss = $this->_data['platforms']['os'];
        $osHTTP = strtolower($GLOBALS['_HTTP']['OS']);

        if(is_array($oss)){
            $firstSTR = $oss[0][0];

            switch($firstSTR)
            {
                case '!':
                    for($i = 0; $i < count($oss); $i++) {
                        $valid = true;
                        $os = str_replace($firstSTR, '', $oss[$i]);

                        if (strtolower($os) == $osHTTP) {
                            return false;
                            break;
                        }
                    }
                    break;

                default:
                    for($i = 0; $i < count($oss); $i++) {
                        $valid = false;

                        if (strtolower($oss[$i]) == $osHTTP) {
                            return true;
                            break;
                        }
                    }
                    break;
            }
        } else if(!is_null($oss)) {
            $firstSTR = $oss[0];

            switch($firstSTR)
            {
                case '!':
                    $valid = true;
                    $os = str_replace($firstSTR, '', $oss);

                    if(strtolower($os) == $osHTTP) {
                        $valid = false;
                    }
                    break;

                default:
                    $valid = false;

                    if(strtolower($oss) == $osHTTP) {
                        $valid = true;
                    }
                    break;
            }
        }

        return $valid;
    }

    private function isValidBrowser()
    {
        $valid = true;
        $browsers = $this->_data['platforms']['browsers'];
        $browserHTTP = strtolower($GLOBALS['_HTTP']['BROWSER']);

        if(is_array($browsers)){
            $firstSTR = $browsers[0][0];

            switch($firstSTR)
            {
                case '!':
                    for($i = 0; $i < count($browsers); $i++) {
                        $valid = true;
                        $browser = str_replace($firstSTR, '', $browsers[$i]);

                        if (strtolower($browser) == $browserHTTP) {
                            return false;
                            break;
                        }
                    }
                    break;

                default:
                    for($i = 0; $i < count($browsers); $i++) {
                        $valid = false;

                        if (strtolower($browsers[$i]) == $browserHTTP) {
                            return true;
                            break;
                        }
                    }
                    break;
            }
        } else if(!is_null($browsers)) {
            $firstSTR = $browsers[0];

            switch($firstSTR)
            {
                case '!':
                    $valid = true;
                    $browser = str_replace($firstSTR, '', $browsers);

                    if(strtolower($browser) == $browserHTTP) {
                        $valid = false;
                    }
                    break;

                default:
                    $valid = false;

                    if(strtolower($browsers) == $browserHTTP) {
                        $valid = true;
                    }
                    break;
            }
        }

        return $valid;
    }
} 