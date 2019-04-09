<?php
namespace Common\View;

use Neomerx\JsonApi\Encoder\Encoder;
use Neomerx\JsonApi\Encoder\EncoderOptions;
use Neomerx\JsonApi\Encoder\Parameters\EncodingParameters;
use Neomerx\JsonApi\Document\Link;

use Marmot\Framework\Classes\Server;

/**
 * @codeCoverageIgnore
 */
trait JsonApiView
{

    private $links = [];
    private $meta = [];

    public function setEncodingParameters($encodingParameters)
    {
        $this->encodingParameters = $encodingParameters;
    }

    public function pagination(string $url, array $conditions, int $num, int $perpage, int $curpage)
    {

        unset($conditions['page']);
        //分页总数
        $pages = @ceil($num / $perpage);

        $url = !empty($conditions) ? $url.'/?'. urldecode(http_build_query($conditions)).'&' : $url.'/?';
        //上一页,如果当前页数大于起始页(first), 则 $prev = 当前页数 - 1, 否则上一页 = 起始页
        //下一页,如果当前页数小于总页数, 则 $next = 当前页数 + 1,  否则下一页 = 总页数
        $prev = ($curpage > 1) ? $curpage - 1 : $curpage;
        $next = ($curpage < $pages) ? $curpage + 1 : $curpage;

        $this->formatLinks($url, $perpage, $curpage, $pages, $prev, $next);
        $this->formatMeta($curpage, $pages, $prev, $next, $num);

        return $this;
    }
    
    /**
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    private function formatLinks(string $url, int $perpage, int $curpage, int $pages, int $prev, int $next)
    {
        $this->links  = [
                Link::FIRST => $pages > 1 ? new Link(
                    Server::get('HTTP_HOST').'/'.$url.'page[number]=1&page[size]='.$perpage,
                    null,
                    true
                ) : null,
                Link::LAST  => $pages > 1 ? new Link(
                    Server::get('HTTP_HOST').'/'.$url.'page[number]='.$pages.'&page[size]='.$perpage,
                    null,
                    true
                ) : null,
                Link::PREV  => ($pages > 1 && $curpage > 1) ? new Link(
                    Server::get('HTTP_HOST').'/'.$url.'page[number]='.$prev.'&page[size]='.$perpage,
                    null,
                    true
                ) : null,
                Link::NEXT  => ($pages > 1 && $curpage < $pages) ? new Link(
                    Server::get('HTTP_HOST').'/'.$url.'page[number]='.$next.'&page[size]='.$perpage,
                    null,
                    true
                ) : null,
        ];
    }

    private function formatMeta(int $curpage, int $pages, int $prev, int $next, int $num)
    {
        $this->meta['count'] = $num;

        $this->meta['links']['first'] = null;
        $this->meta['links']['last'] = null;
        $this->meta['links']['prev'] = null;
        $this->meta['links']['next'] = null;

        if ($pages > 1) {
            $this->meta['links']['first'] = 1;
            $this->meta['links']['last'] = $pages;

            if ($curpage > 1) {
                $this->meta['links']['prev'] = $prev;
            }

            if ($curpage < $pages) {
                $this->meta['links']['next'] = $next;
            }
        }
    }

    public function jsonApiFormat($object, array $objectsSchema, EncodingParameters $encodeParameters = null)
    {

        $encoder = Encoder::instance(
            $objectsSchema,
            new EncoderOptions(JSON_PRETTY_PRINT, Server::get('HTTP_HOST'))
        );

        return $encoder->withLinks($this->links)
                ->withMeta($this->meta)
                ->encodeData($object, $encodeParameters);
    }
}
