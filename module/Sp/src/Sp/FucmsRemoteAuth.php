<?php

namespace Sp;

use SimpleXMLElement;
use Zend\Json\Json;
use Zend\Session\Container;

class FucmsRemoteAuth
{
	protected $_serviceType = 'cms';
	protected $idpReadTokenUrl;
	protected $idpRequestTokenUrl;
	protected $idpRegisterUrl;
	protected $idpLogoutUrl;
	
	public function __construct($sm)
	{
		$config = $sm->get('Config');
		$idpDomain = $config['env']['domain']['idp'];
		
		$this->idpReadTokenUrl = 'http://' . $idpDomain . '/read-token.xml';
		$this->idpRequestTokenUrl = 'http://' . $idpDomain . '/login';
		//$this->idpRegisterUrl = 'http://' . $idpDomain . '/register.json';
		$this->idpLogoutUrl = 'http://' . $idpDomain . '/logout';
	}
	
	public function readToken($assu)
	{
		$container = new Container('remote_auth');
		$token = $container->offsetGet('ssoToken');
		if($token) {
			$curl = curl_init($this->idpReadTokenUrl);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, array(
				'token' => $token
			));
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			
			$xmlBody = curl_exec($curl);
			$responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			if(curl_errno($curl) != 0) {
				throw new Exception("SSO failure: HTTP request to server failed. " . curl_error($curl));
			}
			
			switch($responseCode) {
				case '200':
					$xml = new SimpleXMLElement($xmlBody);
					$assu->login($xml);
					//$redirectUrl = $assu->getRedirectUrl();
					header("Location: /");
					exit(0);
					break;
				case '403':
					// token not exist or expired, try to request with a new token
					echo "error while getting identity from sso server, token not found with given value " . $token . "!";
					exit(1);
					break;
				default:
					echo "error while getting identity from sso server!";
					exit(1);
					break;
			}
		} else {
			echo "token not generated!";
			exit(1);
			break;
		}
	}
	
	
	
	public function requestToken($assu, $loginName = null, $password = null)
	{
// 		$assu->setRedirect($_SERVER["REQUEST_URI"]);
		
		if(! $assu->isLogin()) {
			
			$serviceKey = 'zvmiopav7BbuifbahoUifbqov541huog5vua4ofaweafeq98fvvxreqh';
			
			$container = new Container('remote_auth');
			$ssoToken = md5(time() . rand(0, 10000) . $serviceKey);
			$container->offsetSet('ssoToken', $ssoToken);
			
			
			
// 			$ssoToken = $assu->getSSOToken();
// 			$apiKey = $assu->getServiceKey();
			$loginUrl = 'http://' . HOST_NAME . '/sp/remote-login';
			
			
			
			$ssoLoginUrl = $this->_getRequestTokenUrl('cms', $ssoToken, $serviceKey, $loginUrl);
			
			
// 			if(is_null($loginName)) {
			header("Location: " . $ssoLoginUrl);
// 			} else {
// 				$postLoginPass = md5($ssoToken . $loginName . $password . $apiKey);
// 				$loginName = urlencode($loginName);
// 				header("Location: " . $ssoLoginUrl . '&postLoginName=' . $loginName . '&postLoginPass=' . $postLoginPass);
// 			}
			
			exit(0);
		}
	}
	public function _getRequestTokenUrl($consumer, $token, $apiKey, $loginUrl = null)
	{
		$timeStamp = time();
		$sig = md5($consumer . $timeStamp . $token . $apiKey);
		
		$idRequestUrl = $this->idpRequestTokenUrl . '?' . 'consumer=' . $consumer . '&timeStamp=' . $timeStamp . '&token=' . $token . '&sig=' . $sig;
		
		if(! is_null($loginUrl)) {
			$loginUrl = urlencode($loginUrl);
			$idRequestUrl .= '&loginUrl=' . $loginUrl;
		}
		return $idRequestUrl;
	}
}