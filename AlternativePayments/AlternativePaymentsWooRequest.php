<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

//include_once('ReflectionClass');
include_once('Error/AlternativePaymentsWooApiException.php');
include_once('Error/AlternativePaymentsWooPaymentException.php');
include_once('Error/AlternativePaymentsWooInvalidParameterException.php');
include_once ("AlternativePaymentsWooConfig.php");
/**
 * Abstract Service class inherented by all services
 */
class AlternativePaymentsWooRequest
{
    /*
     * @param string $key - secret key for authentication
     * @param string $ctrl - controller name
     * @param string $code - requested object id
     * @return stdObj - requested object
     */
    public function get($ctrl, $code, $sms = false, $opt = NULL)
    {
        $res = self::curlRequest($ctrl, "GET", $code, $sms, $opt);
        return json_decode($res);
    }

    /*
     * @param string $key - secret key for authentication
     * @param string $ctrl - controller name
     * @return array of stdObj - array of requested obejcts
     */
    public function getAll($ctrl, $sms = false)
    {
        $res = self::curlRequest($ctrl, "GET", null, $sms);
        return json_decode($res);
    }

    /*
     * @param string $key - secret key for authentication
     * @param string $ctrl - controller name
     * @param string $data - object in json format
     * @return stdObj - requested object
     */
    public function post($ctrl, $data, $sms = false)
    {
        $json = self::jsonEncodePrivate($data);
        $res = self::curlRequest($ctrl, "POST", $json, $sms);
        return json_decode($res);
        //return $res;
    }

    /*
     * @param stdObj $object - object or array for json conversion
     * @return string $json - json representation of stdObj or array
     */
    protected function jsonEncodePrivate($object)
    {
        $array = self::object2ArrayPrivate($object);
        $arrayNoNulls = self::removeNulls($array);
        $json = json_encode($arrayNoNulls);
        return $json;
    }

    /*
     * @param stdObj $object - object for conversion to array including objects private properties
     * @return array - array represenation of an object
     */
    protected function object2ArrayPrivate($object)
    {
        $public = array();
        $reflection = new ReflectionClass($object);
        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);
            $key = $property->getName();
            $value = $property->getValue($object);
            if (is_object($value) || is_array($value)) {
                $public[$key] = self::object2ArrayPrivate($value);
            } else {
                $public[$key] = $value;
            }
        }
        return $public;
    }

    /*
     * @param array $info - response header
     * @param array $result - response body
     */
    protected function parseResponse($info, $result)
    {
        $httpCode = $info["http_code"];
        if (substr($httpCode, 0, 2) != "20") {
            /*$error = json_decode($result);
            if ($error == null) {
                throw new ApiException($httpCode.' '.self::httpCodeParser($httpCode));
            }
            $errorCode = $error->Code;
            $errorType = $error->Type;
            $statusCode = $error->StatusCode;
            $message = $error->Message;
            $parameter = $error->Param;
            
            switch ($errorType) {
                case "payment_error":
                    throw new PaymentException($message, $errorCode, $statusCode);
                case "api_error":
                    throw new ApiException($message, $errorCode, $statusCode);
                case "invalid_parameter_error":
                    throw new InvalidParameterException($message, $errorCode, $statusCode, $parameter);
                default:
                    throw new ApiException($message, $errorCode, $statusCode);
            }*/
            return "{ 'error':'Response error!' }";
        }
    }

    /*
     * @param array $array - contains elements with value null
     * @return array - do not contains elements with null values
     */
    protected function removeNulls($array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = self::removeNulls($value);
            } elseif ($value == null || $value == "") {
                unset($array[$key]);
            } else {
                $array[$key] = $value;
            }
        }
        return $array;
    }

    /*
     * @param string $key - secret key for authentication
     * @param string $ctrl - controller name
     * @param string $method - post, get
     * @param string $param
     * @return stdObj or array od stdObjs - request dependent
     */
    protected function curlRequest($ctrl, $method, $param, $sms = false, $opt = NULL)
    {
        $opt = (object) array(
            'country' => isset($opt['country'])?$opt['country']:''
        );
        
        $api_url = AlternativePaymentsWooConfig::getApiUrl();
        switch ($sms){
            case 'check_is_active':
                $key = AlternativePaymentsWooConfig::getProductKey();
                $url = "$api_url/websites/$key/paymentoptions/$ctrl";
                break;
            case 'send_verification_code':
                $key = AlternativePaymentsWooConfig::getProductKey();
                $url = $api_url."/".$ctrl;
                break;
            default :
                $key = AlternativePaymentsWooConfig::getApiKey();
                $url = $api_url."/".$ctrl;
                break;
        }
        
        if($opt->country){
            /* https://api.alternativepayments.com/api/websites/pk_test_bNirANlwRY38VZKaRXM579c8GMf0yRFCirIyfC69/countries/NL/currencies/USD/amount/1.10 */
            $key = AlternativePaymentsWooConfig::getProductKey();
            $url = "$api_url/websites/$key/countries/{$opt->country}/currencies/USD/amount/1.10";
        }
        
        if ($key == null) {
            //throw new ApiException("API Key not defined.");
            return "{'error':'API Key not defined.'}";
        }
        $encodedKey = base64_encode($key);
        if ($method == "GET" && ($param != null && $param != "")) {
            $url = $url."/".$param;
        }
        
        //return $key." ### ".$url." $$$ ".print_r($param,true);
        $curl = curl_init($url);
        
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($param),
            'Authorization: Basic ' . $encodedKey
            )
        );
        
        $result = curl_exec($curl);
        if (!$result) {
            throw new ApiException("Connection Error - Invalid URL");
        }
        
        self::parseResponse(curl_getinfo($curl), $result);
        curl_close($curl);
        return $result;
    }
    
    /*
     * @param integer $httpCode
     * @return string - error message
     */
    private function httpCodeParser($httpCode)
    {
        switch ($httpCode) {
            case 100: $message = 'Continue'; break;
            case 101: $message = 'Switching Protocols'; break;
            case 200: $message = 'OK'; break;
            case 201: $message = 'Created'; break;
            case 202: $message = 'Accepted'; break;
            case 203: $message = 'Non-Authoritative Information'; break;
            case 204: $message = 'No Content'; break;
            case 205: $message = 'Reset Content'; break;
            case 206: $message = 'Partial Content'; break;
            case 300: $message = 'Multiple Choices'; break;
            case 301: $message = 'Moved Permanently'; break;
            case 302: $message = 'Moved Temporarily'; break;
            case 303: $message = 'See Other'; break;
            case 304: $message = 'Not Modified'; break;
            case 305: $message = 'Use Proxy'; break;
            case 400: $message = 'Bad Request'; break;
            case 401: $message = 'Unauthorized'; break;
            case 402: $message = 'Payment Required'; break;
            case 403: $message = 'Forbidden'; break;
            case 404: $message = 'Not Found'; break;
            case 405: $message = 'Method Not Allowed'; break;
            case 406: $message = 'Not Acceptable'; break;
            case 407: $message = 'Proxy Authentication Required'; break;
            case 408: $message = 'Request Time-out'; break;
            case 409: $message = 'Conflict'; break;
            case 410: $message = 'Gone'; break;
            case 411: $message = 'Length Required'; break;
            case 412: $message = 'Precondition Failed'; break;
            case 413: $message = 'Request Entity Too Large'; break;
            case 414: $message = 'Request-URI Too Large'; break;
            case 415: $message = 'Unsupported Media Type'; break;
            case 500: $message = 'Internal Server Error'; break;
            case 501: $message = 'Not Implemented'; break;
            case 502: $message = 'Bad Gateway'; break;
            case 503: $message = 'Service Unavailable'; break;
            case 504: $message = 'Gateway Time-out'; break;
            case 505: $message = 'HTTP Version not supported'; break;
            default:  $message = 'Unknown http status code'; break;
        }
        return $message;
    }
}
