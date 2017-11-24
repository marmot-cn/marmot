<?php
//powered by chloroplast
namespace System\Classes;

use Marmot\Core;
use System\Classes\Filter;
use System\Interfaces\IMediaTypeStrategy;
use System\Interfaces\IValidateStrategy;
use System\Strategy\MediaTypes\JsonapiStrategy;

/**
 * 因为我们只是基于接口作框架处理开发.这里这里只是保留了接口的功能.
 *
 * @author chloroplast
 * @version 1.0.20160413
 */
class Request
{
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
     * @var $headers
     */
    private $headers;

    /**
     * @var $mediaTypeStrategy IMediaTypeStrategy
     */
    private $mediaTypeStrategy;

    public function __construct()
    {
        $this->mediaTypeStrategy = new JsonapiStrategy();
    }

    public function __destruct()
    {
        unset($this->queryParams);
        unset($this->bodyParams);
        unset($this->rawBody);
        unset($this->headers);
        unset($this->mediaTypeStrategy);
    }

    public function setMediaTypeStrategy(IMediaTypeStrategy $mediaTypeStrategy)
    {
        $this->mediaTypeStrategy = $mediaTypeStrategy;
    }

    public function getMediaTypeStrategy() : IMediaTypeStrategy
    {
        return $this->mediaTypeStrategy;
    }

    /**
     * Returns the method of the current request (e.g. GET, POST, HEAD, PUT, PATCH, DELETE).
     * @return string request method, such as GET, POST, HEAD, PUT, PATCH, DELETE.
     * The value returned is turned into upper case.
     */
    public function getMethod() : string
    {

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
    public function isGetMethod() : bool
    {
        return $this->getMethod() === 'GET';
    }
    /**
     * Returns whether this is an OPTIONS request.
     * @return boolean whether this is a OPTIONS request.
     */
    public function isOptionsMethod() : bool
    {
        return $this->getMethod() === 'OPTIONS';
    }
    /**
     * Returns whether this is a HEAD request.
     * @return boolean whether this is a HEAD request.
     */
    public function isHeadMethod() : bool
    {
        return $this->getMethod() === 'HEAD';
    }
    /**
     * Returns whether this is a POST request.
     * @return boolean whether this is a POST request.
     */
    public function isPostMethod() : bool
    {
        return $this->getMethod() === 'POST';
    }
    /**
     * Returns whether this is a DELETE request.
     * @return boolean whether this is a DELETE request.
     */
    public function isDeleteMethod() : bool
    {
        return $this->getMethod() === 'DELETE';
    }
    /**
     * Returns whether this is a PUT request.
     * @return boolean whether this is a PUT request.
     */
    public function isPutMethod() : bool
    {
        return $this->getMethod() === 'PUT';
    }
    /**
     * Returns whether this is a PATCH request.
     * @return boolean whether this is a PATCH request.
     */
    public function isPatchMethod() : bool
    {
        return $this->getMethod() === 'PATCH';
    }

    /**
     * Returns the raw HTTP request body.
     * @return string the request body
     */
    public function getRawBody()
    {
        if ($this->rawBody === null) {
            $this->rawBody = file_get_contents('php://input');
        }
        return $this->rawBody;
    }

    /**
     * Sets the raw HTTP request body, this method is mainly used by test scripts to simulate raw HTTP requests.
     * @param string $rawBody the request body
     */
    public function setRawBody($rawBody)
    {
        $this->rawBody = $rawBody;
    }

    /**
     * Returns GET parameter with a given name. If name isn't specified, returns an array of all GET parameters.
     *
     * @param string $name the parameter name
     * @param mixed $defaultValue the default parameter value if the parameter does not exist.
     * @return array|mixed
     */
    public function get($name = null, $defaultValue = null)
    {
        if ($name === null) {
            return $this->getQueryParams();
        }
        return $this->getQueryParam($name, $defaultValue);
    }

    /**
     * Returns POST parameter with a given name. If name isn't specified, returns an array of all POST parameters.
     *
     * @param string $name the parameter name
     * @param mixed $defaultValue the default parameter value if the parameter does not exist.
     * @return array|mixed
     */
    public function post($name = null, $defaultValue = null)
    {

        if (!$this->isPostMethod()) {
            return null;
        }

        if ($name === null) {
            return $this->getBodyParams();
        }
        return $this->getBodyParam($name, $defaultValue);
    }

    /**
     * Returns PUT parameter with a given name. If name isn't specified, returns an array of all POST parameters.
     *
     * @param string $name the parameter name
     * @param mixed $defaultValue the default parameter value if the parameter does not exist.
     * @return array|mixed
     */
    public function put($name = null, $defaultValue = null)
    {

        if (!$this->isPutMethod()) {
            return null;
        }

        if ($name === null) {
            return $this->getBodyParams();
        }
        return $this->getBodyParam($name, $defaultValue);
    }

    /**
     * Sets the request [[queryString]] parameters.
     * @param array $values the request query parameters (name-value pairs)
     * @see getQueryParam()
     * @see getQueryParams()
     */
    public function setQueryParams($values)
    {
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
    public function getQueryParam($name, $defaultValue = null)
    {
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
    public function getQueryParams()
    {
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
     * @see getMethod()
     * @see getBodyParam()
     * @see setBodyParams()
     */
    public function getBodyParams()
    {
        if ($this->bodyParams === null) {
            //兼容HTTP_X_HTTP_METHOD_OVERRIDE
            if ($this->isPostMethod() || $this->isPutMethod()) {
                // PHP has already parsed the body so we have all params in $_POST
                if (!empty($_POST)) {
                    $this->bodyParams = $_POST;
                } else {
                    $this->bodyParams = [];
                    $this->bodyParams = json_decode($this->getRawBody(), true);
                }
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
    public function setBodyParams($values)
    {
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
    public function getBodyParam($name, $defaultValue = null)
    {
        $params = $this->getBodyParams();
        return isset($params[$name]) ? $params[$name] : $defaultValue;
    }

    private function getHeaders()
    {
        foreach ($_SERVER as $name => $value) {
            if (strncmp($name, 'HTTP_', 5) === 0) {
                $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                $this->headers[strtolower($name)] = $value;
            }
        }
    }

    public function getHeader($name, $default = null)
    {
        $this->getHeaders();

        $name = strtolower($name);
        if (isset($this->headers[$name])) {
            return $this->headers[$name];
        }
        return $default;
    }

    public function setHeader($name, $value = '')
    {
        $this->getHeaders();

        $name = strtolower($name);
        $this->headers[$name] = $value;
    }
    
    /**
     * 1. 验证请求的媒体类型
     * 2. 验证数据是否正确
     *
     * @param array $rules
     */
    public function validate(array $rules = array()) : bool
    {
        return $this->validateMediaTypes();
    }

    private function validateMediaTypes() : bool
    {
        return $this->getMediaTypeStrategy()->validate($this);
    }
}
