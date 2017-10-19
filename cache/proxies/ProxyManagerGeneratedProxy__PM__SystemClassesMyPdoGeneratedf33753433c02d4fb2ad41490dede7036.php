<?php

namespace ProxyManagerGeneratedProxy\__PM__\System\Classes\MyPdo;

class Generatedf33753433c02d4fb2ad41490dede7036 extends \System\Classes\MyPdo implements \ProxyManager\Proxy\VirtualProxyInterface
{

    /**
     * @var \Closure|null initializer responsible for generating the wrapped object
     */
    private $valueHolder59c8ade56c311654510619 = null;

    /**
     * @var \Closure|null initializer responsible for generating the wrapped object
     */
    private $initializer59c8ade56cb54948902644 = null;

    /**
     * @var bool[] map of public properties of the parent class
     */
    private static $publicProperties59c8ade569eaf631731373 = array(
        'statement' => true,
        'options' => true,
    );

    private static $signaturef33753433c02d4fb2ad41490dede7036 = 'YTozOntzOjk6ImNsYXNzTmFtZSI7czoyMToiXFN5c3RlbVxDbGFzc2VzXE15UGRvIjtzOjc6ImZhY3RvcnkiO3M6NTA6IlByb3h5TWFuYWdlclxGYWN0b3J5XExhenlMb2FkaW5nVmFsdWVIb2xkZXJGYWN0b3J5IjtzOjE5OiJwcm94eU1hbmFnZXJWZXJzaW9uIjtzOjU6IjEuMC4wIjt9';

    /**
     * {@inheritDoc}
     */
    public function setAttr($param, $val = '')
    {
        $this->initializer59c8ade56cb54948902644 && $this->initializer59c8ade56cb54948902644->__invoke($this->valueHolder59c8ade56c311654510619, $this, 'setAttr', array('param' => $param, 'val' => $val), $this->initializer59c8ade56cb54948902644);

        return $this->valueHolder59c8ade56c311654510619->setAttr($param, $val);
    }

    /**
     * {@inheritDoc}
     */
    public function prepare($sql = '')
    {
        $this->initializer59c8ade56cb54948902644 && $this->initializer59c8ade56cb54948902644->__invoke($this->valueHolder59c8ade56c311654510619, $this, 'prepare', array('sql' => $sql), $this->initializer59c8ade56cb54948902644);

        return $this->valueHolder59c8ade56c311654510619->prepare($sql);
    }

    /**
     * {@inheritDoc}
     */
    public function exec($sql)
    {
        $this->initializer59c8ade56cb54948902644 && $this->initializer59c8ade56cb54948902644->__invoke($this->valueHolder59c8ade56c311654510619, $this, 'exec', array('sql' => $sql), $this->initializer59c8ade56cb54948902644);

        return $this->valueHolder59c8ade56c311654510619->exec($sql);
    }

    /**
     * {@inheritDoc}
     */
    public function query($sql)
    {
        $this->initializer59c8ade56cb54948902644 && $this->initializer59c8ade56cb54948902644->__invoke($this->valueHolder59c8ade56c311654510619, $this, 'query', array('sql' => $sql), $this->initializer59c8ade56cb54948902644);

        return $this->valueHolder59c8ade56c311654510619->query($sql);
    }

    /**
     * {@inheritDoc}
     */
    public function beginTA()
    {
        $this->initializer59c8ade56cb54948902644 && $this->initializer59c8ade56cb54948902644->__invoke($this->valueHolder59c8ade56c311654510619, $this, 'beginTA', array(), $this->initializer59c8ade56cb54948902644);

        return $this->valueHolder59c8ade56c311654510619->beginTA();
    }

    /**
     * {@inheritDoc}
     */
    public function commit()
    {
        $this->initializer59c8ade56cb54948902644 && $this->initializer59c8ade56cb54948902644->__invoke($this->valueHolder59c8ade56c311654510619, $this, 'commit', array(), $this->initializer59c8ade56cb54948902644);

        return $this->valueHolder59c8ade56c311654510619->commit();
    }

    /**
     * {@inheritDoc}
     */
    public function rollBack()
    {
        $this->initializer59c8ade56cb54948902644 && $this->initializer59c8ade56cb54948902644->__invoke($this->valueHolder59c8ade56c311654510619, $this, 'rollBack', array(), $this->initializer59c8ade56cb54948902644);

        return $this->valueHolder59c8ade56c311654510619->rollBack();
    }

    /**
     * {@inheritDoc}
     */
    public function lastInertId()
    {
        $this->initializer59c8ade56cb54948902644 && $this->initializer59c8ade56cb54948902644->__invoke($this->valueHolder59c8ade56c311654510619, $this, 'lastInertId', array(), $this->initializer59c8ade56cb54948902644);

        return $this->valueHolder59c8ade56c311654510619->lastInertId();
    }

    /**
     * {@inheritDoc}
     */
    public function execute($param = '')
    {
        $this->initializer59c8ade56cb54948902644 && $this->initializer59c8ade56cb54948902644->__invoke($this->valueHolder59c8ade56c311654510619, $this, 'execute', array('param' => $param), $this->initializer59c8ade56cb54948902644);

        return $this->valueHolder59c8ade56c311654510619->execute($param);
    }

    /**
     * {@inheritDoc}
     */
    public function bindParam($parameter, $variable, $data_type = 2, $length = 6)
    {
        $this->initializer59c8ade56cb54948902644 && $this->initializer59c8ade56cb54948902644->__invoke($this->valueHolder59c8ade56c311654510619, $this, 'bindParam', array('parameter' => $parameter, 'variable' => $variable, 'data_type' => $data_type, 'length' => $length), $this->initializer59c8ade56cb54948902644);

        return $this->valueHolder59c8ade56c311654510619->bindParam($parameter, $variable, $data_type, $length);
    }

    /**
     * {@inheritDoc}
     */
    public function rowCount()
    {
        $this->initializer59c8ade56cb54948902644 && $this->initializer59c8ade56cb54948902644->__invoke($this->valueHolder59c8ade56c311654510619, $this, 'rowCount', array(), $this->initializer59c8ade56cb54948902644);

        return $this->valueHolder59c8ade56c311654510619->rowCount();
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        $this->initializer59c8ade56cb54948902644 && $this->initializer59c8ade56cb54948902644->__invoke($this->valueHolder59c8ade56c311654510619, $this, 'count', array(), $this->initializer59c8ade56cb54948902644);

        return $this->valueHolder59c8ade56c311654510619->count();
    }

    /**
     * {@inheritDoc}
     */
    public function close()
    {
        $this->initializer59c8ade56cb54948902644 && $this->initializer59c8ade56cb54948902644->__invoke($this->valueHolder59c8ade56c311654510619, $this, 'close', array(), $this->initializer59c8ade56cb54948902644);

        return $this->valueHolder59c8ade56c311654510619->close();
    }

    /**
     * {@inheritDoc}
     */
    public function closeCursor()
    {
        $this->initializer59c8ade56cb54948902644 && $this->initializer59c8ade56cb54948902644->__invoke($this->valueHolder59c8ade56c311654510619, $this, 'closeCursor', array(), $this->initializer59c8ade56cb54948902644);

        return $this->valueHolder59c8ade56c311654510619->closeCursor();
    }

    /**
     * {@inheritDoc}
     */
    public function insert($table, array $data)
    {
        $this->initializer59c8ade56cb54948902644 && $this->initializer59c8ade56cb54948902644->__invoke($this->valueHolder59c8ade56c311654510619, $this, 'insert', array('table' => $table, 'data' => $data), $this->initializer59c8ade56cb54948902644);

        return $this->valueHolder59c8ade56c311654510619->insert($table, $data);
    }

    /**
     * {@inheritDoc}
     */
    public function update($table, array $data, $wheresqlArr = '')
    {
        $this->initializer59c8ade56cb54948902644 && $this->initializer59c8ade56cb54948902644->__invoke($this->valueHolder59c8ade56c311654510619, $this, 'update', array('table' => $table, 'data' => $data, 'wheresqlArr' => $wheresqlArr), $this->initializer59c8ade56cb54948902644);

        return $this->valueHolder59c8ade56c311654510619->update($table, $data, $wheresqlArr);
    }

    /**
     * {@inheritDoc}
     */
    public function delete($table, $wheresqlArr = '')
    {
        $this->initializer59c8ade56cb54948902644 && $this->initializer59c8ade56cb54948902644->__invoke($this->valueHolder59c8ade56c311654510619, $this, 'delete', array('table' => $table, 'wheresqlArr' => $wheresqlArr), $this->initializer59c8ade56cb54948902644);

        return $this->valueHolder59c8ade56c311654510619->delete($table, $wheresqlArr);
    }

    /**
     * @override constructor for lazy initialization
     *
     * @param \Closure|null $initializer
     */
    public function __construct($initializer)
    {
        unset($this->statement, $this->options);

        $this->initializer59c8ade56cb54948902644 = $initializer;
    }

    /**
     * @param string $name
     */
    public function & __get($name)
    {
        $this->initializer59c8ade56cb54948902644 && $this->initializer59c8ade56cb54948902644->__invoke($this->valueHolder59c8ade56c311654510619, $this, '__get', array('name' => $name), $this->initializer59c8ade56cb54948902644);

        if (isset(self::$publicProperties59c8ade569eaf631731373[$name])) {
            return $this->valueHolder59c8ade56c311654510619->$name;
        }

        $realInstanceReflection = new \ReflectionClass(get_parent_class($this));

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHolder59c8ade56c311654510619;

            $backtrace = debug_backtrace(false);
            trigger_error('Undefined property: ' . get_parent_class($this) . '::$' . $name . ' in ' . $backtrace[0]['file'] . ' on line ' . $backtrace[0]['line'], \E_USER_NOTICE);
            return $targetObject->$name;;
            return;
        }

        $targetObject = $this->valueHolder59c8ade56c311654510619;
        $accessor = function & () use ($targetObject, $name) {
            return $targetObject->$name;
        };
            $backtrace = debug_backtrace(true);
            $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \stdClass();
            $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $returnValue = & $accessor();

        return $returnValue;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->initializer59c8ade56cb54948902644 && $this->initializer59c8ade56cb54948902644->__invoke($this->valueHolder59c8ade56c311654510619, $this, '__set', array('name' => $name, 'value' => $value), $this->initializer59c8ade56cb54948902644);

        if (isset(self::$publicProperties59c8ade569eaf631731373[$name])) {
            return ($this->valueHolder59c8ade56c311654510619->$name = $value);
        }

        $realInstanceReflection = new \ReflectionClass(get_parent_class($this));

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHolder59c8ade56c311654510619;

            return $targetObject->$name = $value;;
            return;
        }

        $targetObject = $this->valueHolder59c8ade56c311654510619;
        $accessor = function & () use ($targetObject, $name, $value) {
            return $targetObject->$name = $value;
        };
            $backtrace = debug_backtrace(true);
            $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \stdClass();
            $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $returnValue = & $accessor();

        return $returnValue;
    }

    /**
     * @param string $name
     */
    public function __isset($name)
    {
        $this->initializer59c8ade56cb54948902644 && $this->initializer59c8ade56cb54948902644->__invoke($this->valueHolder59c8ade56c311654510619, $this, '__isset', array('name' => $name), $this->initializer59c8ade56cb54948902644);

        if (isset(self::$publicProperties59c8ade569eaf631731373[$name])) {
            return isset($this->valueHolder59c8ade56c311654510619->$name);
        }

        $realInstanceReflection = new \ReflectionClass(get_parent_class($this));

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHolder59c8ade56c311654510619;

            return isset($targetObject->$name);;
            return;
        }

        $targetObject = $this->valueHolder59c8ade56c311654510619;
        $accessor = function () use ($targetObject, $name) {
            return isset($targetObject->$name);
        };
            $backtrace = debug_backtrace(true);
            $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \stdClass();
            $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $returnValue = $accessor();

        return $returnValue;
    }

    /**
     * @param string $name
     */
    public function __unset($name)
    {
        $this->initializer59c8ade56cb54948902644 && $this->initializer59c8ade56cb54948902644->__invoke($this->valueHolder59c8ade56c311654510619, $this, '__unset', array('name' => $name), $this->initializer59c8ade56cb54948902644);

        if (isset(self::$publicProperties59c8ade569eaf631731373[$name])) {
            unset($this->valueHolder59c8ade56c311654510619->$name);

            return;
        }

        $realInstanceReflection = new \ReflectionClass(get_parent_class($this));

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHolder59c8ade56c311654510619;

            unset($targetObject->$name);;
            return;
        }

        $targetObject = $this->valueHolder59c8ade56c311654510619;
        $accessor = function () use ($targetObject, $name) {
            unset($targetObject->$name);
        };
            $backtrace = debug_backtrace(true);
            $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \stdClass();
            $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $returnValue = $accessor();

        return $returnValue;
    }

    public function __clone()
    {
        $this->initializer59c8ade56cb54948902644 && $this->initializer59c8ade56cb54948902644->__invoke($this->valueHolder59c8ade56c311654510619, $this, '__clone', array(), $this->initializer59c8ade56cb54948902644);

        $this->valueHolder59c8ade56c311654510619 = clone $this->valueHolder59c8ade56c311654510619;
    }

    public function __sleep()
    {
        $this->initializer59c8ade56cb54948902644 && $this->initializer59c8ade56cb54948902644->__invoke($this->valueHolder59c8ade56c311654510619, $this, '__sleep', array(), $this->initializer59c8ade56cb54948902644);

        return array('valueHolder59c8ade56c311654510619');
    }

    public function __wakeup()
    {
        unset($this->statement, $this->options);
    }

    /**
     * {@inheritDoc}
     */
    public function setProxyInitializer(\Closure $initializer = null)
    {
        $this->initializer59c8ade56cb54948902644 = $initializer;
    }

    /**
     * {@inheritDoc}
     */
    public function getProxyInitializer()
    {
        return $this->initializer59c8ade56cb54948902644;
    }

    /**
     * {@inheritDoc}
     */
    public function initializeProxy()
    {
        return $this->initializer59c8ade56cb54948902644 && $this->initializer59c8ade56cb54948902644->__invoke($this->valueHolder59c8ade56c311654510619, $this, 'initializeProxy', array(), $this->initializer59c8ade56cb54948902644);
    }

    /**
     * {@inheritDoc}
     */
    public function isProxyInitialized()
    {
        return null !== $this->valueHolder59c8ade56c311654510619;
    }

    /**
     * {@inheritDoc}
     */
    public function getWrappedValueHolderValue()
    {
        return $this->valueHolder59c8ade56c311654510619;
    }


}
