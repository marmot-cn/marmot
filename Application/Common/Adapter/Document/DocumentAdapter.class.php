<?php
namespace Common\Adapter\Document;

use Common\Model\Document;
use Common\Adapter\Document\Persistence\DocumentCache;
use Marmot\Core;

/**
 * @author chloroplast
 * @version 1.0:20160227
 */
abstract class DocumentAdapter
{
    /**
     * @var DocumentCache $documentCache mongo文档缓存
     */
    private $documentCache;
    /**
     * @var resource $collection mongo 集合
     */
    private $collection;
    /**
     * @var string $dbName mongo 数据库
     */
    private $dbName;
    /**
     * @var string $collectionName mongo 集合
     */
    private $collectionName;

    public function __construct(string $dbName, string $collectionName)
    {
        $this->documentCache = new DocumentCache($dbName, $collectionName);
        $this->collection = Core::$mongoDriver->$dbName->$collectionName;
    }

    protected function getCollection()
    {
        return $this->collection;
    }

    protected function getDocumentCache() : DocumentCache
    {
        return $this->documentCache;
    }

    public function add(Document $document)
    {
        $data = $document->getData();

        $result = $this->getCollection()->insertOne($data);
        $lastInsertId = $result->getInsertedId();

        $this->getDocumentCache()->save((string)$lastInsertId, $data);

        $document->setId($lastInsertId);
        return true;
    }

    /**
     * 获取mongo数据
     * @param string mongo db
     * @param string collection 集合
     * @param string id mongo id
     */
    public function fetchOne(Document $document)
    {
        $id = $document->getId();

        //查询缓存中是否有数据,根据id
        $data = $this->getDocumentCache()->get($id);
        //如果有数据,返回
        if (empty($data)) {
            $data = $this->getCollection()->findOne(['_id' => new \MongoDB\BSON\ObjectID($id)]);
            if (empty($data)) {
                return false;
            }

            $data = (array)$data;
            $data['_id'] = (string)($data['_id']);
            //数据存入缓存
            $this->getDocumentCache()->save($id, $data);
        }

        $document->setData($data);
        return true;
    }

    /**
     * 获取批量mongo数据
     * @param string mongo db
     * @param string collection 集合
     * @param string id mongo id
     */
    public function fetchList(array $documents)
    {
        $ids = array();
        $documentsByIds = array();

        foreach ($documents as $document) {
            $id = $document->getId();

            $ids[] = $id;
            $documentsByIds[$id] = $document;
        }

        list($hits, $miss) = $this->getDocumentCache()->getList($ids);
        if ($miss) {
            $mongoIds = $missRows = array();
            foreach ($miss as $id) {
                $mongoIds[] = new \MongoDB\BSON\ObjectID($id);
            }

            $missRowCursor = $this->getCollection()->find(['_id' => ['$in'=> $mongoIds]]);
            if ($missRowCursor) {
                foreach ($missRowCursor as $val) {
                    $val = (array)$val;
                    $val['_id'] = (string)($val['_id']);
                    //添加memcache缓存数据
                    $missRows[] = $val;
                    $this->getDocumentCache()->save((string)$val['_id'], $val);
                }
                $hits = array_merge($hits, $missRows);
            }
        }
        $resArray = array();
        if ($hits) {
            //按该页要显示的id排序
            $result = array();
            foreach ($hits as $val) {
                $result[(string)$val['_id']] = $val;
            }
            //按照传入id列表初始顺序排序
            foreach ($ids as $id) {
                if (isset($result[$id])) {
                    $documentsByIds[$id]->setData((array)$result[$id]);
                }
            }
            unset($result);
        }
        return true;
    }
}
