<?php
//powered by chloroplast
namespace System\Classes;

use System\Interfaces\IResponseFormatter;
use Marmot\Core;

/**
 * 这里是基于yii2的request类作的修改,
 * 因为我们只是基于接口作框架处理开发.这里这里只是保留了接口的功能.
 *
 * @author chloroplast
 * @version 1.0.20160413
 */
class Response
{

    const FORMAT_JSON_API = 'jsonapi';

    /**
     * 状态码
     */
    private $statusCode = '200';

    /**
     * @var string the HTTP status description that comes together with the status code.
     * @see httpStatuses
     */
    private $statusText = 'OK';

    /**
     * @var string the response format. This determines how to convert [[data]] into [[content]]
     * when the latter is not set.
     * The value of this property must be one of the keys declared in the [[formatters] array.
     * By default, the following formats are supported:
     *
     * - [[FORMAT_JSON_API]]: the data will be converted into JSONAPI format, and the "Content-Type"
     *   header will be set as "text/javascript". Note that in this case `$data` must be an array
     *   with "data" and "callback" elements. The former refers to the actual data to be sent,
     *   while the latter refers to the name of the JavaScript callback.
     *
     * You may customize the formatting process or support additional formats by configuring [[formatters]].
     * @see formatters
     */
    public $format = self::FORMAT_JSON_API;

    /**
     * @var array the formatters for converting data into the response content of the specified [[format]].
     * The array keys are the format names, and the array values are the corresponding configurations
     * for creating the formatter objects.
     * @see format
     * @see defaultFormatters
     */
    public $formatters = [];

    /**
     * @var string the version of the HTTP protocol to use.
     * If not set, it will be determined via `$SERVER['SERVER_PROTOCOL']`,
     * or '1.1' if that is not available.
     */
    private $version;

    /**
     * @var array headers, 头数组,用于在最后输出头信心时使用.
     */
    private $headers;

    /**
     * 格式化前的响应内容
     */
    public $data;

    /**
     * 格式化后的响应内容
     */
    public $content;

    /**
     * @var array list of HTTP status codes and the corresponding texts
     */
    public static $httpStatuses = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        118 => 'Connection timed out',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        210 => 'Content Different',
        226 => 'IM Used',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Reserved',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        310 => 'Too many Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested range unsatisfiable',
        417 => 'Expectation failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable entity',
        423 => 'Locked',
        424 => 'Method failure',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        449 => 'Retry With',
        450 => 'Blocked by Windows Parental Controls',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway or Proxy Error',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        507 => 'Insufficient storage',
        508 => 'Loop Detected',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
    ];

    public function __construct()
    {

        $this->headers = array();

        if ($this->version === null) {
            $this->version = (isset($_SERVER['SERVER_PROTOCOL']) && $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.0')
                              ? '1.0' : '1.1';
        }

        $this->formatters = array_merge($this->defaultFormatters(), $this->formatters);
    }

    /**
     * @return array the formatters that are supported by default
     */
    private function defaultFormatters()
    {
        return [
            self::FORMAT_JSON_API => 'System\View\JsonApiResponseFormatter',
        ];
    }

    /**
     * @return integer the HTTP status code to send with the response.
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Sets the response status code.
     * This method will set the corresponding status text if `$text` is null.
     * @param integer $value the status code
     * @param string $text the status text. If not set, it will be set automatically based on the status code.
     * @throws InvalidParamException if the status code is invalid.
     */
    public function setStatusCode($value, $text = null)
    {
        if ($value === null) {
            $value = 200;
        }
        $this->statusCode = (int) $value;
        if ($this->getIsInvalid()) {
            throw new InvalidParamException("The HTTP status code is invalid: $value");
        }
        if ($text === null) {
            $this->statusText = isset(static::$httpStatuses[$this->statusCode])
            ? static::$httpStatuses[$this->statusCode] : '';
        } else {
            $this->statusText = $text;
        }
    }
    /**
     * @return boolean whether this response has a valid [[statusCode]].
     */
    public function getIsInvalid()
    {
        return $this->getStatusCode() < 100 || $this->getStatusCode() >= 600;
    }

    /**
     * 添加新的头信息
     * @param string $name 头信息 name
     * @param string $value 头信息 值
     */
    public function addHeader($name, $value)
    {
        $this->headers[$name][] = $value;
    }

    /**
     * 获取头信息
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * 向客户端发送响应
     */
    public function send()
    {
        $this->checkCache();
        $this->prepare();
        $this->sendHeaders();

        if (!$this->isCached()) {
            $this->sendContent();
        }
    }

    public function isCached() : bool
    {
        return $this->getStatusCode() == '304';
    }

    private function checkCache()
    {
        $request = new Request();
        $etag = $request->getHeader('If-None-Match', '');
        if ($etag == $this->getEtag()) {
            $this->setStatusCode(304);
        }
    }

    private function getEtag() : string
    {
        return 'W/'.md5(serialize($this->data));
    }

    /**
     * Prepares for sending the response.
     * The default implementation will convert [[data]] into [[content]] and set headers accordingly.
     */
    public function prepare()
    {
        if (isset($this->formatters[$this->format])) {
            $formatter = $this->formatters[$this->format];
        }

        if (!is_object($formatter)) {
            $this->formatters[$this->format] = $formatter = Core::$container->get($formatter);
        }

        if ($formatter instanceof IResponseFormatter) {
            $formatter->format($this);
        }
        $this->addHeader('ETag', $this->getEtag());
        //如果都不符合情况输出exception
    }

    /**
     * 因为我们现在应用场景只考虑接口传输,所以直接输出内容
     */
    public function sendContent()
    {
        if (!empty($this->content)) {
            echo $this->content;
        }
        return;
    }

    /**
     * Sends the response headers to the client
     */
    public function sendHeaders()
    {
        //判断是否已经发送过头
        if (headers_sent()) {
            return;
        }
        //获取头数据
        $headers = $this->getHeaders();
        if (!empty($headers) && is_array($headers)) {
            foreach ($headers as $name => $values) {
                $name = str_replace(' ', '-', ucwords(str_replace('-', ' ', $name)));
                // set replace for first occurrence of header but false afterwards to allow multiple
                $replace = true;
                foreach ($values as $value) {
                    header("$name: $value", $replace);
                    $replace = false;
                }
            }
        }

        $statusCode = $this->getStatusCode();
        header("HTTP/{$this->version} {$statusCode} {$this->statusText}");
    }
}
