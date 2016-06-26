<?php
//powered by chloroplast
namespace System\Classes;
use Core;
use System\Classes\Filter;

/**
 * 这里是基于yii2的request类作的修改,因为我们只是基于接口作框架处理开发.这里这里只是保留了接口的功能.
 * 
 * @author chloroplast
 * @version 1.0.20160413
 */
class Request{

    /**
     * @var array query 数组
     */
    private $queryParams;

    /**
     * @var array post put patch 传参数组
     */
    private $bodyParams;

    /**
     * http 传输源数据
     */
    private $rawBody;

    /**
     * @var array headers, 头数组,用于在最后输出头信息时使用.
     */
    private $headers;

    public function getHeaders(){

        if ($this->headers === null) {
            $this->headers = array();
            if (function_exists('getallheaders')) {
                $headers = getallheaders();
            } elseif (function_exists('http_get_request_headers')) {
                $headers = http_get_request_headers();
            } else {
                foreach ($_SERVER as $name => $value) {
                    if (strncmp($name, 'HTTP_', 5) === 0) {
                        $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                        $this->headers[$name] = $value;
                    }
                }
                return $this->headers;
            }
            foreach ($headers as $name => $value) {
                $this->headers[$name] = $value;
            }
        }
        return $this->headers;
    }

    public function getHeader($name){
        $headers = $this->getHeaders();
        return isset($headers[$name]) ? $headers[$name] : '';
    }

   /**
     * Returns the method of the current request (e.g. GET, POST, HEAD, PUT, PATCH, DELETE).
     * @return string request method, such as GET, POST, HEAD, PUT, PATCH, DELETE.
     * The value returned is turned into upper case.
     */
    public function getMethod(){

        if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
            return strtoupper($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']);
        }
        
        if (isset($_SERVER['REQUEST_METHOD'])) {
            return strtoupper($_SERVER['REQUEST_METHOD']);
        }
        
        return 'GET';
    }

    /**
     * Returns whether this is a GET request.
     * @return boolean whether this is a GET request.
     */
    public function getIsGet(){
        return $this->getMethod() === 'GET';
    }
    /**
     * Returns whether this is an OPTIONS request.
     * @return boolean whether this is a OPTIONS request.
     */
    public function getIsOptions(){
        return $this->getMethod() === 'OPTIONS';
    }
    /**
     * Returns whether this is a HEAD request.
     * @return boolean whether this is a HEAD request.
     */
    public function getIsHead(){
        return $this->getMethod() === 'HEAD';
    }
    /**
     * Returns whether this is a POST request.
     * @return boolean whether this is a POST request.
     */
    public function getIsPost(){
        return $this->getMethod() === 'POST';
    }
    /**
     * Returns whether this is a DELETE request.
     * @return boolean whether this is a DELETE request.
     */
    public function getIsDelete(){
        return $this->getMethod() === 'DELETE';
    }
    /**
     * Returns whether this is a PUT request.
     * @return boolean whether this is a PUT request.
     */
    public function getIsPut(){
        return $this->getMethod() === 'PUT';
    }
    /**
     * Returns whether this is a PATCH request.
     * @return boolean whether this is a PATCH request.
     */
    public function getIsPatch(){
        return $this->getMethod() === 'PATCH';
    }

    /**
     * Returns the raw HTTP request body.
     * @return string the request body
     */
    public function getRawBody(){
        if ($this->_rawBody === null) {
            $this->_rawBody = file_get_contents('php://input');
        }
        return $this->_rawBody;
    }

    /**
     * Sets the raw HTTP request body, this method is mainly used by test scripts to simulate raw HTTP requests.
     * @param string $rawBody the request body
     */
    public function setRawBody($rawBody){
        $this->_rawBody = $rawBody;
    }

    /**
     * Returns GET parameter with a given name. If name isn't specified, returns an array of all GET parameters.
     *
     * @param string $name the parameter name
     * @param mixed $defaultValue the default parameter value if the parameter does not exist.
     * @return array|mixed
     */
    public function get($name = null, $defaultValue = null){
        if ($name === null) {
            return $this->getQueryParams();
        } else {
            return $this->getQueryParam($name, $defaultValue);
        }
    }

    /**
     * Returns POST parameter with a given name. If name isn't specified, returns an array of all POST parameters.
     *
     * @param string $name the parameter name
     * @param mixed $defaultValue the default parameter value if the parameter does not exist.
     * @return array|mixed
     */
    public function post($name = null, $defaultValue = null){

        if(!$this->getIsPost()){
            return null;
        }

        if ($name === null) {
            return $this->getBodyParams();
        } else {
            return $this->getBodyParam($name, $defaultValue);
        }
    }

    /**
     * Returns PUT parameter with a given name. If name isn't specified, returns an array of all POST parameters.
     *
     * @param string $name the parameter name
     * @param mixed $defaultValue the default parameter value if the parameter does not exist.
     * @return array|mixed
     */
    public function put($name = null, $defaultValue = null){

        if(!$this->getIsPut()){
            return null;
        }

        if ($name === null) {
            return $this->getBodyParams();
        } else {
            return $this->getBodyParam($name, $defaultValue);
        }   
    }

    /**
     * Sets the request [[queryString]] parameters.
     * @param array $values the request query parameters (name-value pairs)
     * @see getQueryParam()
     * @see getQueryParams()
     */
    public function setQueryParams($values){
        $this->queryParams = $values;
    }

    /**
     * Returns the named GET parameter value.
     * If the GET parameter does not exist, the second parameter passed to this method will be returned.
     * @param string $name the GET parameter name.
     * @param mixed $defaultValue the default parameter value if the GET parameter does not exist.
     * @return mixed the GET parameter value
     * @see getBodyParam()
     */
    public function getQueryParam($name, $defaultValue = null){
        $params = $this->getQueryParams();
        return isset($params[$name]) ? $params[$name] : $defaultValue;
    }

  	/**
     * Returns the request parameters given in the [[queryString]].
     *
     * This method will return the contents of `$_GET` if params where not explicitly set.
     * @return array the request GET parameter values.
     * @see setQueryParams()
     */
    public function getQueryParams(){
        if ($this->queryParams === null) {
            return Filter::htmlFilter($_GET);
        }
        return Filter::htmlFilter($this->queryParams);
    }

    /**
     * Returns the request parameters given in the request body.
     *
     * Request parameters are determined using the parsers configured in [[parsers]] property.
     * If no parsers are configured for the current [[contentType]] it uses the PHP function `mb_parse_str()`
     * to parse the [[rawBody|request body]].
     * @return array the request parameters given in the request body.
     * @throws \yii\base\InvalidConfigException if a registered parser does not implement the [[RequestParserInterface]].
     * @see getMethod()
     * @see getBodyParam()
     * @see setBodyParams()
     */
    public function getBodyParams(){
        if ($this->bodyParams === null) {
     		if ($this->getMethod() === 'POST') {
                // PHP has already parsed the body so we have all params in $_POST
                $this->bodyParams = $_POST;
            } else if ($this->getMethod() == 'PUT') {
                // 如果是PUT,我们默认传递发送json数组方便用于解析
                $this->bodyParams = [];
                $this->bodyParams = json_decode($this->getRawBody(),true); 
            } else {
                $this->bodyParams = [];
                mb_parse_str($this->getRawBody(), $this->bodyParams);
            }
        }
        return Filter::htmlFilter($this->bodyParams);
    }
    /**
     * Sets the request body parameters.
     * @param array $values the request body parameters (name-value pairs)
     * @see getBodyParam()
     * @see getBodyParams()
     */
    public function setBodyParams($values){
        $this->bodyParams = $values;
    }
    /**
     * Returns the named request body parameter value.
     * If the parameter does not exist, the second parameter passed to this method will be returned.
     * @param string $name the parameter name
     * @param mixed $defaultValue the default parameter value if the parameter does not exist.
     * @return mixed the parameter value
     * @see getBodyParams()
     * @see setBodyParams()
     */
    public function getBodyParam($name, $defaultValue = null){
        $params = $this->getBodyParams();
        return isset($params[$name]) ? $params[$name] : $defaultValue;
    }
}