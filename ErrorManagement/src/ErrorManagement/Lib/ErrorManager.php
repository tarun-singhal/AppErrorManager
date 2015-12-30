<?php
/**
 * To handle the error, return on the basis of module INI file
 */
namespace ErrorManagement\Lib;

class ErrorManager
{

    /**
     *
     * @param $error The
     *            string containing error
     * @param $that object            
     * @return Error Object 
     */
    public static function setError($error, $that)
    {
        $req = $that->getServiceLocator()->get('Request');
//         $res = $that->getServiceLocator()->get('Response');
//         print_r($res->getApiProblem());
//         print_r($req->getUri());
//         die('here');
        // Get the resource invoking the error/exception
        $uri_arr = explode('/', substr($req->getUri()->getPath(), 0));
        $module = ucfirst($uri_arr[1]);
        
        //custom changes for user repo
        if($module == 'Register' || $module == 'Rules') {
        	$module = 'User';
        }
        $inifile = realpath('.') . "/module/{$module}/config/{$module}Error.ini";
        
        // Get the error codes file
        $errorDetail = self::parseErrorIniFile($inifile);
        
        // Construct the error object
        $errorResponse = $that->getServiceLocator()->get('coreError');
        
        $errorResponse->set("errorCode", $error->getCode());
        
        //in case if error
        if (isset($errorDetail[$module][$error->getMessage()])) {
            $errorResponse->set("userMessage", $errorDetail[$module][$error->getMessage()]);
        } else { // for exception
        	$errorResponse->set("userMessage", $error->getMessage());
        	$errorResponse->set("debugMessage", $error->getMessage());
        }
        if (empty($req->getHeaders())) {
            $errorResponse->set("link", $req->getUri()
                ->getScheme() . '://' . $req->getHeaders('host')
                ->getFieldValue() . "/apigility/documentation/" . ucfirst($uri_arr[1]) . "-" . ucfirst($uri_arr[0]));
        }
        
        //to change response status code
        http_response_code($error->getCode());
        die(json_encode($errorResponse));
    }

    /**
     * Parsing INI File
     * 
     * @param string $inifile            
     * @return multitype array:
     */
    public function parseErrorIniFile($inifile)
    {
        $errorDetail = array();
        if (file_exists($inifile)) {
            $errorDetail = parse_ini_file($inifile, true);
        }
        return $errorDetail;
    }
}
