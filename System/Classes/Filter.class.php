<?php
namespace System\Classes;

class Filter
{

    //添加转义字符（Array加强版）
    public static function addslashesPlus($string)
    {
        if (is_array($string)) {
            foreach ($string as $key => $val) {
                $string[$key] = Filter::addslashesPlus($val);
            }
            return $string;
        }
        return addslashes($string);
    }
    
    //trim加强版
    public static function trimPlus($string)
    {
        if (is_array($string)) {
            foreach ($string as $key => $val) {
                $string[$key] = Filter::trimPlus($val);
            }
            return $string;
        }
        return trim($string);
    }
    
    //代码转义（Array加强版）
    public static function stripslashesPlus($string)
    {
        if (is_array($string)) {
            foreach ($string as $key => $val) {
                $string[$key] = Filter::stripslashesPlus($val);
            }
            return $string;
        }
        return stripslashes($string);
    }
    
    //取消HTML代码（Array加强版）
    public static function htmlspecialcharsPlus($string)
    {
        if (is_array($string)) {
            foreach ($string as $key => $val) {
                $string[$key] = Filter::htmlspecialcharsPlus($val);
            }

            return $string;
        }
        return preg_replace(
            '/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/',
            '&\\1',
            str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string)
        );
    }

    public static function dhtmlspecialchars($string, $flags = false)
    {
        if (is_array($string)) {
            foreach ($string as $key => $val) {
                $string[$key] = Filter::dhtmlspecialchars($val, $flags);
            }
        } else {
            if (!$flags) {
                $string = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string);
                if (strpos($string, '&amp;#') !== false) {
                    $string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $string);
                }
            } else {
                $string = htmlspecialchars($string, $flags);
            }
        }
        return $string;
    }

    /**
     * 清除标签
     * @param string $string
     * @param string $allowtags
     * @param string $allowattributes
     * @return mixed
     */
    public static function stripTagsAttributes($string, $allowtags = null, $allowattributes = null)
    {

//        $string = strip_tags($string, $allowtags);

//        if (! is_null($allowattributes)) {
//            if (! is_array($allowattributes)) {
//                $allowattributes = explode(',', $allowattributes);
//            }
//            if (is_array($allowattributes)) {
//                $allowattributes = implode(')(?<!', $allowattributes);
//            }
//            if (strlen($allowattributes) > 0) {
//                $allowattributes = '(?<!' . $allowattributes . ')';
//            }
//            $expr = '/ [^ =]*' . $allowattributes . '=(\"[^\"]*\"|\'[^\']*\')/i';
//        }
        // XSS protection: <a href="javascript: alert(...
        $string = preg_replace('/href=([\'"]).*?javascript:(.*)?\\1/i', 'href="#$2"', $string);
        return $string;
    }

    /**
     * 清除标签增强版
     * @param string $string
     * @param string $allowtags
     * @param string $allowattributes
     * @return mixed
     */
    public static function stripTagsAttributesPlus($string, $allowtags = null, $allowattributes = null)
    {
        if (is_array($string)) {
            foreach ($string as $key => $val) {
                $string[$key] = Filter::stripTagsAttributesPlus($val, $allowtags, $allowattributes);
            }
            return $string;
        }
        return Filter::stripTagsAttributes($string, $allowtags, $allowattributes);
    }

    //html过滤器（去掉，转义，部分）
    public static function htmlFilter($html, $remove = false, $replace = true, $allow = true)
    {
        if ($remove) {
            $html = preg_replace("/(\<[^\<]*\>|\r|\n|\s|\[.+?\])/is", ' ', $html);
        }
        if ($replace) {
            $html = Filter::stripslashesPlus($html);
        }
        if ($allow) {
            $html = Filter::trimPlus($html);
            $allowtags = '<b><i><u><blockquote><img><strong><em><font>
                          <p><h1><h2><h3><h4><h5><h6><strike><span><br>
                          <table><tbody><th><tr><td><caption><colgroup><div>';
            $allowattributes = 'target,src,width,height,alt,title,size,face,color,align,style,class,rel,rev';
            $html = Filter::stripTagsAttributesPlus($html, $allowtags, $allowattributes);
            
            $html = Filter::dhtmlspecialchars($html);
            $html = Filter::addslashesPlus($html);
        }
        return $html;
    }
}
