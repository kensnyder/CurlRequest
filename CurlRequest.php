<?php

/**
 * Object-oriented cURL requests
 * 
 * List of cURL constants: http://php.net/manual/en/curl.constants.php 
 */
class CurlRequest {
	
	/**
	 * The URL to which to make the connection
	 * @var string
	 */
	protected $_url;
	
	/**
	 * Which kind of request? GET, PUT, DELETE, etc.
	 * @var string 
	 */
	protected $_method = 'GET';
	
	/**
	 * Field-value pairs to pass via GET
	 * @var array
	 */
	protected $_get = array();
	
	/**
	 * Field-value pairs to pass via POST
	 * @var array
	 */
	protected $_post = array();
	
	/**
	 * Field-value pairs to pass via HTTP Cookie header
	 * @var array
	 */
	protected $_cookie = array();
	
	/**
	 * List of headers to pass as HTTP Headers
	 * @var array
	 */
	protected $_headers = array();
	
	/**
	 * Temporary variable used to collect headers in cURL callback
	 * @var string
	 */
	protected $_headerString;
	
	/**
	 * CURLOPT_* options to send
	 * @var type 
	 */
	protected $_options = array();
	
	/**
	 * The handle to the cURL resource
	 * @var type 
	 */
	protected $_handle;
	
	/**
	 * The number of seconds after which to abort the request if no response is received
	 * @var int
	 */
	protected $_timeout = 30;
	
	/**
	 * True if the cURL resource handle has already been closed
	 * @var bool
	 */
	protected $_isClosed = false;
	
	/**
	 * The last response
	 * @var string
	 */
	protected $_lastResponse;
	
	/**
	 * Array of constants that are used for debugging output
	 * @var array
	 */
	public static $constants = array (
			58 => 'CURLOPT_AUTOREFERER',
		 19914 => 'CURLOPT_BINARYTRANSFER',
			98 => 'CURLOPT_BUFFERSIZE',
		 10065 => 'CURLOPT_CAINFO',
		 10097 => 'CURLOPT_CAPATH',
		   172 => 'CURLOPT_CERTINFO',
			72 => 'CURLOPT_CLOSEPOLICY',
			78 => 'CURLOPT_CONNECTTIMEOUT',
		   156 => 'CURLOPT_CONNECTTIMEOUT_MS',
		 10022 => 'CURLOPT_COOKIE',
		 10031 => 'CURLOPT_COOKIEFILE',
		 10082 => 'CURLOPT_COOKIEJAR',
			96 => 'CURLOPT_COOKIESESSION',
			27 => 'CURLOPT_CRLF',
		 10036 => 'CURLOPT_CUSTOMREQUEST',
			92 => 'CURLOPT_DNS_CACHE_TIMEOUT',
			91 => 'CURLOPT_DNS_USE_GLOBAL_CACHE',
		 10077 => 'CURLOPT_EGDSOCKET',
		 10102 => 'CURLOPT_ENCODING',
			45 => 'CURLOPT_FAILONERROR',
		 10001 => 'CURLOPT_FILE',
			69 => 'CURLOPT_FILETIME',
			52 => 'CURLOPT_FOLLOWLOCATION',
			75 => 'CURLOPT_FORBID_REUSE',
			74 => 'CURLOPT_FRESH_CONNECT',
			50 => 'CURLOPT_FTPAPPEND',
			48 => 'CURLOPT_FTPLISTONLY',
		 10017 => 'CURLOPT_FTPPORT',
		   129 => 'CURLOPT_FTPSSLAUTH',
		   110 => 'CURLOPT_FTP_CREATE_MISSING_DIRS',
		   138 => 'CURLOPT_FTP_FILEMETHOD',
		   137 => 'CURLOPT_FTP_SKIP_PASV_IP',
		   119 => 'CURLOPT_FTP_SSL',
		   106 => 'CURLOPT_FTP_USE_EPRT',
			85 => 'CURLOPT_FTP_USE_EPSV',
			42 => 'CURLOPT_HEADER',
		 20079 => 'CURLOPT_HEADERFUNCTION',
		 10104 => 'CURLOPT_HTTP200ALIASES',
		   107 => 'CURLOPT_HTTPAUTH',
			80 => 'CURLOPT_HTTPGET',
		 10023 => 'CURLOPT_HTTPHEADER',
			61 => 'CURLOPT_HTTPPROXYTUNNEL',
			84 => 'CURLOPT_HTTP_VERSION',
		 10009 => 'CURLOPT_INFILE',
			14 => 'CURLOPT_INFILESIZE',
		 10062 => 'CURLOPT_INTERFACE',
		   113 => 'CURLOPT_IPRESOLVE',
		 10026 => 'CURLOPT_KEYPASSWD',
		 10063 => 'CURLOPT_KRB4LEVEL',
			19 => 'CURLOPT_LOW_SPEED_LIMIT',
			20 => 'CURLOPT_LOW_SPEED_TIME',
			71 => 'CURLOPT_MAXCONNECTS',
			68 => 'CURLOPT_MAXREDIRS',
		 30146 => 'CURLOPT_MAX_RECV_SPEED_LARGE',
		 30145 => 'CURLOPT_MAX_SEND_SPEED_LARGE',
			51 => 'CURLOPT_NETRC',
			44 => 'CURLOPT_NOBODY',
			43 => 'CURLOPT_NOPROGRESS',
			99 => 'CURLOPT_NOSIGNAL',
			 3 => 'CURLOPT_PORT',
			47 => 'CURLOPT_POST',
		 10015 => 'CURLOPT_POSTFIELDS',
		 10039 => 'CURLOPT_POSTQUOTE',
		   161 => 'CURLOPT_POSTREDIR',
		 10103 => 'CURLOPT_PRIVATE',
		 20056 => 'CURLOPT_PROGRESSFUNCTION',
		   181 => 'CURLOPT_PROTOCOLS',
		 10004 => 'CURLOPT_PROXY',
		   111 => 'CURLOPT_PROXYAUTH',
			59 => 'CURLOPT_PROXYPORT',
		   101 => 'CURLOPT_PROXYTYPE',
		 10006 => 'CURLOPT_PROXYUSERPWD',
			54 => 'CURLOPT_PUT',
		 10028 => 'CURLOPT_QUOTE',
		 10076 => 'CURLOPT_RANDOM_FILE',
		 10007 => 'CURLOPT_RANGE',
		 20012 => 'CURLOPT_READFUNCTION',
		   182 => 'CURLOPT_REDIR_PROTOCOLS',
		 10016 => 'CURLOPT_REFERER',
			21 => 'CURLOPT_RESUME_FROM',
		 19913 => 'CURLOPT_RETURNTRANSFER',
		   151 => 'CURLOPT_SSH_AUTH_TYPES',
		 10162 => 'CURLOPT_SSH_HOST_PUBLIC_KEY_MD5',
		 10153 => 'CURLOPT_SSH_PRIVATE_KEYFILE',
		 10152 => 'CURLOPT_SSH_PUBLIC_KEYFILE',
		 10025 => 'CURLOPT_SSLCERT',
		 10086 => 'CURLOPT_SSLCERTTYPE',
		 10089 => 'CURLOPT_SSLENGINE',
			90 => 'CURLOPT_SSLENGINE_DEFAULT',
		 10087 => 'CURLOPT_SSLKEY',
		 10088 => 'CURLOPT_SSLKEYTYPE',
			32 => 'CURLOPT_SSLVERSION',
		 10083 => 'CURLOPT_SSL_CIPHER_LIST',
			81 => 'CURLOPT_SSL_VERIFYHOST',
			64 => 'CURLOPT_SSL_VERIFYPEER',
		 10037 => 'CURLOPT_STDERR',
		   121 => 'CURLOPT_TCP_NODELAY',
			33 => 'CURLOPT_TIMECONDITION',
			13 => 'CURLOPT_TIMEOUT',
		   155 => 'CURLOPT_TIMEOUT_MS',
			34 => 'CURLOPT_TIMEVALUE',
			53 => 'CURLOPT_TRANSFERTEXT',
		   105 => 'CURLOPT_UNRESTRICTED_AUTH',
			46 => 'CURLOPT_UPLOAD',
		 10002 => 'CURLOPT_URL',
		 10018 => 'CURLOPT_USERAGENT',
		 10005 => 'CURLOPT_USERPWD',
			41 => 'CURLOPT_VERBOSE',
		 20011 => 'CURLOPT_WRITEFUNCTION',
		 10029 => 'CURLOPT_WRITEHEADER',
	);
	
	/**
	 * Create a new request object optionally setting URL
	 * @param string $url 
	 */
	public function __construct($url = null) {
		$this->_handle = curl_init();
		if ($url) {
			$this->setUrl($url);
		}
		// by default, follow redirects
		$this->setopt(CURLOPT_FOLLOWLOCATION, 1);
		// by default, store cookies in memory
		$this->setopt(CURLOPT_COOKIEFILE, '');
	}
	
	/**
	 * Shortcut to get file contents via cURL
	 * @param type $url
	 * @return string 
	 */
	public static function getContents($url) {
		$request = new self($url);
		$contents = $request->exec();
		$request->close();
		return $contents;
	}
	
	/**
	 * Create a new instance
	 * @param string $url
	 * @return CurlRequest 
	 */
	public static function factory($url = null) {
		return new self($url);
	}
	
	/**
	 * Set the URL to use
	 * @param string $url
	 * @return \CurlRequest 
	 */
	public function setUrl($url) {
		$this->_url = trim($url);
		return $this;
	}
	
	/**
	 * Get the URL to which this request will be made
	 * @return string
	 */
	public function getUrl() {
		return $this->_url;
	}
	
	/**
	 * Set the HTTP method (GET|POST|PUT|PATCH|DELETE)
	 *   With PUT and DELETE, it will both set CURLOPT_CUSTOMREQUEST and add the header X-HTTP-Method-Override:
	 * @param string $type  One of GET, POST, PUT, or DELETE
	 * @return \CurlRequest
	 * @throws CurlRequestUnknownMethodException  When unknown type is given
	 */
	public function setMethod($type) {
		$type = strtoupper($type);
		if (!preg_match('/GET|POST|PUT|PATCH|DELETE/', $type)) {
			throw new CurlRequestUnknownMethodException("Unknown method `$type`. Expecting GET, POST, PUT, PATCH or DELETE.");
		}
		$this->_method = $type;
		return $this;
	}
	
	/**
	 * Get the HTTP method set in setMethod()
	 * @return string  One of GET, POST, PUT, or DELETE
	 */
	public function getMethod() {
		return $this->_method;
	}
	
	/**
	 * Set all the GET parameters using an array of field-value pairs
	 * @param array $get
	 * @return \CurlRequest 
	 */
	public function setGet($get) {
		$this->_get = $get;
		return $this;
	}
	
	/**
	 * Get all the GET parameters as field-value pairs
	 * @return array
	 */
	public function getGet() {
		return $this->_get;
	}
	
	/**
	 * Set all the POST parameters using an array of field-value pairs
	 * @param array $post
	 * @return \CurlRequest 
	 */
	public function setPost($post) {
		$this->_post = $post;
		return $this;
	}	
	
	/**
	 * Get all the POST parameters as field-value pairs
	 * @return array
	 */	
	public function getPost() {
		return $this->_post;
	}
	
	/**
	 * Set all the COOKIE values using an array of field-value pairs
	 * @param array $post
	 * @return \CurlRequest 
	 */
	public function setCookie($cookie) {
		$this->_cookie = $cookie;
		return $this;
	}
	
	/**
	 * Get all the POST parameters as field-value pairs
	 * @return array
	 */	
	public function getCookie() {
		return $this->_cookie;
	}
	
	/**
	 * Add an HTTP header to the request e.g. 'Accept-Encoding: gzip, default'
	 * @param string $header
	 * @return \CurlRequest 
	 */
	public function addHeader($header) {
		if (is_array($header)) {
			$this->_headers = array_merge($this->_headers, $header);
		}
		else {
			$this->_headers[] = $header;
		}
		return $this;
	}
	
	/**
	 * Unset all HTTP headers
	 * @return \CurlRequest 
	 */
	public function clearHeaders() {
		$this->_headers = array();
		return $this;
	}
	
	/**
	 * Get an array of HTTP headers previously set
	 * @return array 
	 */
	public function getHeaders() {
		return $this->_headers;
	}
	
	/**
	 * After this many seconds, curl will give up
	 * @param int $seconds 
	 */
	public function setTimeout($seconds) {
		$this->_timeout = $seconds;
		return $this;
	}
	
	/**
	 * Return timeout previously set
	 * @return type 
	 */
	public function getTimeout() {
		return $this->_timeout;
	}
	
	/**
	 * Return the handle to the curl resource
	 * @return resource
	 */
	public function getHandle() {
		return $this->_handle;
	}
	
	/**
	 * Execute the request and return the string results
	 * @return void
	 */
	public function prepareRequest() {
		$url = $this->_url;
		if (!empty($this->_get)) {
			$url .= '?' . http_build_query($this->_get, '&');
		}
		if ($this->_isClosed) {
			$this->_handle = curl_init();
		}
		if (!empty($this->_post) || $this->_method == 'POST') {
			$this->_method = 'POST';
			$this->setopt(CURLOPT_POST, 1);
		}
		elseif (preg_match('/PUT|DELETE|PATCH/', $this->_method)) {
			$this->setopt(CURLOPT_CUSTOMREQUEST, $this->_method);
			$this->addHeader('X-HTTP-Method-Override: ' . $this->_method);
		}
		else {
			$this->setopt(CURLOPT_POST, 0);
		}
		$this->setopt(CURLOPT_URL, $url);
		$this->setopt(CURLOPT_HEADER, false);
		$this->setopt(CURLOPT_RETURNTRANSFER, true);
		$this->setopt(CURLOPT_CONNECTTIMEOUT, $this->_timeout);
		if (!empty($this->_post)) {
			$this->setopt(CURLOPT_POSTFIELDS, http_build_query($this->_post, null, '&'));
		}
		if (!empty($this->_cookie)) {
			$this->setopt(CURLOPT_COOKIE, http_build_query($this->_cookie, null, ';'));
		}
		if (!empty($this->_headers)) {
			$this->setopt(CURLOPT_HTTPHEADER, $this->_headers);
		}
		$this->_headerString = '';
		$this->setopt(CURLOPT_HEADERFUNCTION, array($this, 'collectHeadersCallback'));
		foreach ($this->_options as $k => $v) {
			curl_setopt($this->_handle, $k, $v);
		}
	}
	
	/**
	 * cURL callback to collect the headers string
	 * @param type $ch
	 * @param type $header
	 * @return type 
	 */
	public function collectHeadersCallback($ch, $header) {
		$this->_headerString .= $header;
		return strlen($header);			
	}
	
	/**
	 * Get a CurlResponse object given the result string
	 * @param string|bool $resultString  False on error
	 * @return CurlResponse
	 */
	public function initResponse($resultString) {
		$this->_lastResponse = new CurlResponse($this, $this->_headerString, $resultString);
		$this->_headerString = null;
		return $this->_lastResponse;
	}
	
	/**
	 * Execute the request and return the CurlResponse (which can be casted to a string to get the string response)
	 * @return CurlResponse
	 */	
	public function exec() {
		$this->prepareRequest();
		return $this->initResponse(curl_exec($this->_handle));
	}
	
	/**
	 * Get the string contents of the last response or false on error
	 * @return string|false
	 */
	public function getLastResponse() {
		return $this->_lastResponse;
	}
		
	/**
	 * Return true if curl_exec returned false
	 * @return bool
	 */	
	public function failed() {
		return $this->_lastResponse->failed();
	}
	
	/**
	 * Shortcut to add Accept-encoding header and setopt for automatic decoding
	 * @return \CurlRequest 
	 */
	public function enableGzip() {
		$this->addHeader('Accept-Encoding: gzip, default');
		$this->setopt(CURLOPT_ENCODING, 1);
		return $this;
	}
	
	/**
	 * Shortcut to setopt CURLOPT_USERPWD to enable basic HTTP Auth
	 * @param string $username
	 * @param string $password
	 * @return \CurlRequest 
	 */
	public function httpBasicAuth($username, $password) {
		$this->setopt(CURLOPT_USERPWD, "$username:$password"); 
		return $this;
	}
	
	/**
	 * Shortcut to setopt CURLOPT_USERAGENT to set user agent string
	 * @param string $uaString
	 * @return \CurlRequest 
	 */
	public function setUserAgent($uaString) {
		// common user agents: http://www.useragentstring.com/pages/useragentstring.php
		$this->setopt(CURLOPT_USERAGENT, $uaString);
		return $this;
	}
	
	/**
	 * Set CURLOPT_* option to be passed to curl_setopt
	 * See available options at http://php.net/curl_setopt
	 * @param int $const
	 * @param mixed $value
	 * @return \CurlRequest 
	 */
	public function setopt($const, $value) {
		$this->_options[$const] = $value;
		return $this;
	}
	
	/**
	 * Get CURLOPT_* option that was previously set
	 * @param int $const  If omitted, return all options
	 * @return mixed 
	 */
	public function getopt($const = null) {
		if ($const === null) {
			return $this->_options;
		}
		return isset($this->_options[$const]) ? $this->_options[$const] : null;
	}
	
	/**
	 * Check if the given CURLOPT_* option has been set
	 * @param int $const
	 * @return bool
	 */
	public function hasopt($const) {
		return isset($this->_options[$const]);
	}
	
	/**
	 * Get network info and headers from the last request
	 * @param string $opt
	 *   Known parameters: http://www.php.net/manual/en/function.curl-getinfo.php#refsect1-function.curl-getinfo-parameters
	 * @return mixed 
	 */
	public function info($opt = null) {
		return $this->_lastResponse ? $this->_lastResponse->info($opt) : null;
	}
	
	/**
	 * Alias for info()
	 * @param string $opt
	 * @return mixed 
	 */
	public function getinfo($opt) {
		return $this->info($opt);
	}
	
	/**
	 * Release the curl handle
	 * @return void
	 */
	public function close() {
		if (!$this->_isClosed && is_resource($this->_handle)) {
			$this->_isClosed = true;
			curl_close($this->_handle);
		}
	}
	
	/**
	 * On destruct, close connection if not closed already 
	 */
	public function __destruct() {
		$this->close();
	}	
	
	/**
	 * Return dump of information about all requests
	 * @return array
	 */
	public function debug() {
		$data = get_object_vars($this);
		unset($data['_headerString']);
		unset($data['_handle']);
		if ($this->_lastResponse) {
			$data['_lastResponse'] = $this->_lastResponse->debug();
		}
		else {
			unset($data['_lastResponse']);
		}
		$strOpts = array();
		foreach ($this->_options as $num => $val) {
			$strOpts[ isset(self::$constants[$num]) ? self::$constants[$num] : $num ] = $val;
		}
		$data['_options'] = $strOpts;
		return $data;
	}
	
	/**
	 * Debug method for ppr library
	 * @return array
	 */
	public function pprGetObjectVars() {
		return $this->debug();
	}	
	
}

class CurlRequestUnknownMethodException extends Exception {}

/**
 * Batch multiple CurlRequest objects to run simultaneously using curl_multi_exec 
 * 
 * @example
 *  
 * $req1 = new CurlRequest($url1);
 * $req2 = new CurlRequest($url2);
 * 
 * $batch = new CurlRequestBatch();
 * $batch->add($req1);
 * $batch->add($req2);
 * $responses = $batch->exec();
 * 
 */
class CurlRequestBatch {
	
	/**
	 * An array of CurlRequest objects
	 * @var array
	 */
	protected $_requests = array();
	
	/**
	 * Create a batch, optionally passing in an array of CurlRequest requests
	 * @param array $requests  Array of CurlRequest requests
	 */
	public function __construct($requests = null) {
		if (is_array($requests)) {
			$this->addAll($requests);
		}
	}

	/**
	 * Add a new CurlRequest to the batch
	 * @param CurlRequest $request
	 * @return \CurlRequestBatch 
	 */
	public function add(CurlRequest $request) {
		// ensure that we don't already have that request in queue
		$this->remove($request);
		$this->_requests[] = $request;
		return $this;
	}
	
	/**
	 * Add an array of CurlRequest requests
	 * @param array $requests  Array of CurlRequest requests
	 * @return CurlRequestBatch
	 */	
	public function addAll(array $requests) {
		foreach ($requests as $r) {
			$this->add($r);
		}
		return $this;
	}
	
	/**
	 * Remove a CurlRequest from the batch
	 * @param CurlRequest $request
	 * @return \CurlRequestBatch 
	 */
	public function remove(CurlRequest $request) {
		$newlist = array();
		foreach ($this->_requests as $r) {
			if ($r !== $request) {
				$newlist = $r;
			}
		}
		$this->_requests = $newlist;
		return $this;
	}
	
	/**
	 * Run the batch
	 * @return array  Array of CurlResponse response objects
	 */
	public function exec() {
		//create the multiple cURL handle
		$multiHandle = curl_multi_init();

		foreach ($this->_requests as $request) {
			$request->prepareRequest();
			curl_multi_add_handle($multiHandle, $request->getHandle());
		}

		$active = null;
		// execute the handles
		do {
			$mrc = curl_multi_exec($multiHandle, $active);
		} while ($mrc == CURLM_CALL_MULTI_PERFORM);

		while ($active && $mrc == CURLM_OK) {
			if (curl_multi_select($multiHandle) != -1) {
				do {
					$mrc = curl_multi_exec($multiHandle, $active);
				} while ($mrc == CURLM_CALL_MULTI_PERFORM);
			}
		}
		foreach ($this->_requests as $request) {
			$resultText = curl_multi_getcontent($request->getHandle());
			$request->initResponse($resultText);
			curl_multi_remove_handle($multiHandle, $request->getHandle());
		}
		curl_multi_close($multiHandle);	
		return $this->getResponses();
	}
	
	/**
	 * Get all the CurlRequest requests that belong to this batch
	 * @return array 
	 */
	public function getRequests() {
		return $this->_requests;
	}
	
	/**
	 * Get all the CurlResponse responses generated from this batch
	 * @return array 
	 */	
	public function getResponses() {
		$responses = array();
		foreach ($this->_requests as $req) {
			$responses[] = $req->getLastResponse();
		}
		return $responses;
	}
	
	/**
	 * If cloned, clone all the CurlRequest requests in this batch 
	 */
	public function __clone() {
		foreach ($this->_requests as $i => $request) {
			$this->_requests[$i] = clone $request;
		}
	}
	
	/**
	 * Return dump of information about all requests
	 * @return array
	 */
	public function debug() {
		$data = array();
		foreach ($this->_requests as $request) {
			$data[] = $request->debug();
		}
		return $data;
	}
	
	/**
	 * Debug method for ppr library
	 * @return array
	 */
	public function pprGetObjectVars() {
		return $this->debug();
	}		
	
}

/**
 * Object-oriented cURL response 
 */
class CurlResponse {
	
	/**
	 * The curl request object used to make the request
	 * @var CurlRequest 
	 */
	public $request;
	
	/**
	 * The handle to the cURL resource
	 * @var resource 
	 */
	protected $_handle;
		
	/**
	 * List of URLs and response headers in name=>value format for all hops (e.g. 301 redirects)
	 * @var type 
	 */
	protected $_hops;
	
	/**
	 * If true, curl_exec returned false
	 * @var bool 
	 */
	protected $_failed;
	
	/**
	 * The raw text returned from the request
	 * @var string
	 */
	protected $_text;
	
	/**
	 * Run curl_exec, collect and parse headers
	 * @param CurlRequest $request  The CurlRequest object that initiated this response
	 * @param string $headerString  the raw headers
	 * @param string $responseString  The raw response string
	 */
	public function __construct(CurlRequest $request, $headerString, $responseString) {
		$this->request = $request;
		$this->_handle = $request->getHandle();
		$this->_rawHeaders = $headerString;
		$this->_parseHeaders();
		$this->_failed = ($responseString === false);
		$this->_text = (string) $responseString;
	}
	
	/**
	 * Return true if curl_exec returned false
	 * @return bool
	 */
	public function failed() {
		return $this->_failed;
	}
	
	/**
	 * Parse all the headers for this request
	 * @param array $headers 
	 * @return void
	 */
	protected function _parseHeaders() {
		$hopsHeaders = explode("\r\n\r\n", trim($this->_rawHeaders));
		$this->_hops = array();
		$nextLocation = $this->request->getUrl();
		foreach ($hopsHeaders as $headers) {
			$hop = (object) array(
				'location' => $nextLocation,
				'http_code' => null,
				'headers' => array(),
				'raw_headers' => $headers
			);
			foreach (explode("\r\n", $headers) as $header) {
				if (preg_match('~^HTTP/.+?(\d{3})~', $header, $match)) {
					$hop->http_code = (int) $match[1];
				}
				elseif (preg_match('/^location:(.+)/i', $header, $match)) {
					$nextLocation = trim($match[1]);
				}
				elseif (preg_match('/^([^:]+):(.+)$/', $header, $match)) {
					$hop->headers[trim($match[1])] = trim($match[2]);
				}
			}
			$this->_hops[] = $hop;
		}
	}
	
	/**
	 * Get the final URL from which this response came
	 * @return string
	 */
	public function getEffectiveUrl() {
		return $this->getLastHop()->location;
	}
	
	/**
	 * Get the given HTTP Header sent in response
	 * @param string $name
	 * @return string 
	 */
	public function getHeader($name) {
		$name = strtolower($name);
		foreach ($this->getHeaders() as $k => $v) {
			if (strtolower($k) == $name) {
				return $v;
			}
		}
		return null;
	}
	
	/**
	 * Get the headers for the last hop of this response
	 * @return type 
	 */
	public function getHeaders() {
		return $this->getLastHop()->headers;
	}
	
	/**
	 * Get the complete raw headers
	 * @return type 
	 */
	public function getRawHeaders() {
		return $this->getLastHop()->raw_headers;
	}
	
	/**
	 * Return a list of stdClass objects for each leg of the response
	 *   ->location    The URL for this leg
	 *   ->http_code   The integer HTTP code
	 *   ->headers     Name-value pairs of headers
	 *   ->raw_headers Raw header text
	 * @return array
	 */
	public function getHops() {
		return $this->_hops;
	}
	
	/**
	 * Get information about last leg of response
	 * @return stdClass
	 */
	public function getLastHop() {
		return $this->_hops[ count($this->_hops) - 1 ];
	}
	
	/**
	 * When cast as string, return raw text returned from request
	 * @return string 
	 */
	public function __toString() {
		return $this->_text;
	}
	
	/**
	 * Get network info and headers from the last request
	 * @param string $opt
	 *   Known parameters: http://www.php.net/manual/en/function.curl-getinfo.php#refsect1-function.curl-getinfo-parameters
	 * @return mixed 
	 */
	public function info($opt = null) {
		if (is_numeric($opt)) {
			return $this->_handle ? curl_getinfo($this->_handle, $opt) : null;
		}
		else {
			$all = $this->_handle ? curl_getinfo($this->_handle) : array();
			return $opt ? @$all[$opt] : $all;
		}
	}
	
	public function getinfo($opt) {
		return $this->info($opt);
	}	
	
	public function error() {
		return curl_error($this->_handle);
	}
	
	/**
	 * Get the raw text returned from request
	 * @return string
	 */
	public function raw() {
		return $this->_text;
	}
	
	/**
	 * Run json_decode on the raw text returned from request
	 * @param bool $toAssoc  If true, return as array instead of stdClass
	 * @return stdClass|array  Returns stdClass object, or array if $toAssoc is true
	 */
	public function jsonDecode($toAssoc = false) {
		return json_decode($this->_text, $toAssoc);
	}
	
	/**
	 * Return response as an XML Document
	 * @return \DOMDocument 
	 */
	public function toXmlDocument() {
		$doc = new DOMDocument();
		$doc->loadXml($this->_text);
		return $doc;
	}
	
	/**
	 * Return response as an HTML Document
	 * @return \DOMDocument 
	 */
	public function toHtmlDocument() {
		$doc = new DOMDocument();
		$doc->loadHtml($this->_text);
		return $doc;
	}
	
	/**
	 * Return the HTTP Code of the response
	 * @return type 
	 */
	public function httpCode() {
		return $this->getLastHop()->http_code;
	}
		
	/**
	 * Get CURLINFO_* constant when properties are accessed
	 * OR Http Header with that name
	 * @param string $property
	 * @return mixed
	 */
	public function __get($property) {
		$curlConst = @constant('CURLINFO_' . strtoupper($property));
		if ($curlConst !== null) {
			return $this->info($curlConst);
		}
		$this->getHeader(str_replace('_', '-', $property));
	}
	
	/**
	 * Return true if http code between 200 and 299
	 * @return bool 
	 */
	public function isHttpSuccess() {
		$code = $this->httpCode();
		return $code >= 200 && $code < 300;
	}
	
	/**
	 * Return true if httpcode is 400 or above
	 * @return bool
	 */
	public function isHttpError() {
		return $this->httpCode() >= 400;
	}
	
	/**
	 * Return response and metadata
	 * @return array
	 */
	public function debug() {
		return array(
			'_hops' => $this->_hops,
			'_text' => $this->_text,
			'_failed' => $this->_failed,
			'_error' => $this->error(),
			'info()' => $this->info()
		);
	}
	
	/**
	 * Debug method for ppr library
	 * @return array
	 */
	public function pprGetObjectVars() {
		return $this->debug();
	}	
	
}
