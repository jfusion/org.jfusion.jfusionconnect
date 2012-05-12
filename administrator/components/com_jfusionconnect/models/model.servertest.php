<?php
/**
 * PHP version 5
 *
 * @category  JFusionConnect
 * @package   Models
 * @author    JFusion Team <webmaster@jfusion.org>
 * @copyright 2008 JFusion. All rights reserved.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link      http://www.jfusion.org
 */
defined('_JEXEC') or die('Restricted access');

class JFusionConnectServerTest
{
	var $results = array();
	function JFusionConnectServerTest($user)
	{
		if (function_exists('curl_init')) {
			$this->results['OPENIDSERVERPOINT'] = $this->Server();
			$this->results['OPENIDSIGNON'] = $this->OpenIDSignon(JFusionConnect::userToURL($user));
			$this->results['OPENIDSERVER'] = $this->OpenIDServer(JFusionConnect::userToURL());
		} else {
			$this->addError('CURLNOTFOUNED',$this->results);
		}
	}

    function getResults()
    {
		return $this->results;
    }	
	
    function Server()
    {
		$r = $this->_getUrl(JFusionConnect::getServerURL());
		$result = array();		
		$this->_addURL($r,$result);
		$this->_checkError($r,$result);
		return $result;
    }
    
    function OpenIDSignon($openid)
    {	
		$r = $this->_getUrl($openid);
		$result = array();
		$this->_addURL($r,$result);		
		$this->_checkXRDS($r,$result);
		$this->_checkError($r,$result);		
		$this->_getXRDS($result);
		return $result;
    }    
	
    function OpenIDServer($openid)
    {
		$r = $this->_getUrl($openid);
		$result = array();
		$this->_addURL($r,$result);
		$this->_checkXRDS($r,$result);
		$this->_checkError($r,$result);
		$this->_getXRDS($result);
		return $result;
    }            
    
    function _checkError($r,&$result) {
    	if ($r['errno']) {
    		$msg = 'Curl Error: ('.$r['errno'].') '.$r['error'];
    		$this->addError($msg,$result);
    	}
    	$result['STATUSCODE'] = $r['http_code'];
    }
    
    function addError($message,&$result) {
    	$message = JText::_($message);
	    if (isset($result['ERROR']) && is_string($result['ERROR'])) {
			$old = $result['ERROR'];
			$result['ERROR'] = array();
			$result['ERROR'][] = $old;
			$result['ERROR'][] = $message;
	    } else if (isset($result['ERROR']) && is_array($result['ERROR'])) {
			$result['ERROR'][] = $message;
	    } else {
	    	$result['ERROR'] = $message;
	    }
    }    
    
    function _checkXRDS($r,&$result) {
		$result['XRDS']['LOCATION']['HEADER'] = null;
    	$result['XRDS']['LOCATION']['HTTPEQUIV'] = null;
    	foreach ($r['header'] as $key => $value) {
			if (strpos($value , 'X-XRDS-Location') === 0) {
				$pattern  = '#X-XRDS-Location: (.*)#i';
	    		if (preg_match( $pattern ,$value ,$matches)) {
					$result['XRDS']['LOCATION']['HEADER'] = $matches[1];
	    		}
			} else if (strpos($value , 'Location') === 0) {
				$result['STATUS'] = 'Error!!! Redirect founed in signon should not be: '.$value;
			}
    	}
    	$pattern  = '#<meta.*?http-equiv=[\'"]x-xrds-location[\'"].*?content=[\'"](.*?)[\'"].*?>#i';
    	if (preg_match( $pattern , $r['body'] ,$matches)) {
    		$result['XRDS']['LOCATION']['HTTPEQUIV'] = $matches[1];
    	}
    }
    
    function _getXRDS(&$result) {
		if (isset($result['XRDS']['LOCATION']['HEADER']) ) {
			$xrds = $result['XRDS']['LOCATION']['HEADER'];
		} else if (isset($result['XRDS']['LOCATION']['HTTPEQUIV']) ) {
			$xrds = $result['XRDS']['LOCATION']['HTTPEQUIV'];
		}
		$result['XRDS']['CONTENT'] = null;
		if (isset($xrds)) {
			$r = $this->_getUrl($xrds);
			if (empty($r['body'])) {
			    $this->addError('NOCONTENTFOUNED',$result['XRDS']);
			}
			$result['XRDS']['CONTENT'] = $r['body'];
			$this->_checkError($r,$result['XRDS']);
		} else {
			$this->addError('NOCONTENTFOUNED',$result['XRDS']);
		}
    }
    
    function _addURL($r,&$result) {
		$result['URL'] = $r['url'];
    }    
        
    function _reset()
    {
        $this->headers = array();
        $this->data = null;
    }
	
    /**
     * @access private
     */
    function _writeHeader($ch, $header)
    {
        array_push($this->headers, rtrim($header));
        return strlen($header);
    }

    /**
     * @access private
     */
    function _writeData($ch, $data)
    {
        if (strlen($this->data) > 1024*Auth_OpenID_FETCHER_MAX_RESPONSE_KB) {
            return 0;
        } else {
            $this->data .= $data;
            return strlen($data);
        }
    }	
	
	function _getUrl($url) {
		$message = null;
		$this->_reset();
		$c = curl_init();
		
		curl_setopt($c, CURLOPT_URL, $url);
		curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
//		curl_setopt($c, CURLOPT_SSL_VERIFYPEER, true);
//		curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 2);
		
		curl_setopt($c, CURLOPT_WRITEFUNCTION,
                        array($this, "_writeData"));
		curl_setopt($c, CURLOPT_HEADERFUNCTION,
                        array($this, "_writeHeader"));
		
        curl_exec($c);
        $code = curl_getinfo($c, CURLINFO_HTTP_CODE);

        $result['url'] = $url;
        $result['header'] = $this->headers;
        $result['body'] = $this->data;
        $result['errno'] = curl_errno($c);
        $result['error'] = str_replace("\n", ' ', curl_error($c));
        $result['http_code'] = $code;
        
        $this->data = null;
        
        curl_close($c);
		return $result;
	}
}