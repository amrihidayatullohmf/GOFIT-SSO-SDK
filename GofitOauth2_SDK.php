<?php
/**
 * GOFIT OAUTH2 SDK
 * by Amri Hidayatulloh
 * Ver 1.0
 */

class GofitOauth2_SDK {

	private $VER = 1.0;
	private $API_URL = 'http://gofit.stg02.mobileforce.mobi/api/v1/';
	private $APP_ID = '';
	private $APP_SECRET = '';
	private $APP_CALLBACK = '';
	private $APP_SCOPE = 'profile';
	private $ACCESS_TOKEN = "";
	
	function __construct($options = array()) {
		if(isset($options['app_id'])) {
			$this->APP_ID = $options['app_id'];
		}
		if(isset($options['app_secret'])) {
			$this->APP_SECRET = $options['app_secret'];
		}
		if(isset($options['app_callback'])) {
			$this->APP_CALLBACK = $options['app_callback'];
		}
	}

	public function get_login_url($explicit_login = FALSE) {
		if(empty($this->APP_ID) or empty($this->APP_CALLBACK)) {
			echo 'Missing parameter, couldn\'t generate URL !';
			return '';
		}
		$type = ($explicit_login) ? 'access_token' : 'code';
		return $this->API_URL.'oauth2/login?app_id='.$this->APP_ID.'&callback='.$this->APP_CALLBACK.'&scope='.$this->APP_SCOPE."&type=".$type;
	}

	public function get_access_token($code = '') {
		if(empty($code) or empty($this->APP_SECRET)) {
			return 'Missing parameter, couldn\'t process request !';
		}

		$header = array(
						'Content-Type: application/x-www-form-urlencoded',
						'CODE: '.$code,
						'APPID: '.$this->APP_ID,
						'APPSECRET: '.$this->APP_SECRET
					);

		$get = $this->call_post('oauth2/get_access_token',array(),$header);

		return $get;
	}

	public function set_access_token($access_token) {
		$this->ACCESS_TOKEN = $access_token;
	}

	public function call($url = "",$field = array()) {

		$header = array(
						'Content-Type: application/x-www-form-urlencoded',
						'ACCESSTOKEN: '.$this->ACCESS_TOKEN
					);
		return $this->call_post($url,$field,$header);
	}

	private function call_post($url,$fields,$header,$printraw=FALSE) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->API_URL.$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);	
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
		$output = curl_exec($ch);

		if($printraw) {
			var_dump($output);
		}

		return json_decode($output);
	}

}



