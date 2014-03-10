<?php
namespace Phpingguo\ApricotLib\Type\Generics;

use Phpingguo\ApricotLib\Common\Arrays;
use Phpingguo\ApricotLib\Common\String;
use Phpingguo\ApricotLib\Enums\LibEnumName;
use Phpingguo\ApricotLib\Enums\Variable;
use Phpingguo\ApricotLib\Type\Enum\EnumClassGenerator as EnumClassGen;
use Phpingguo\ApricotLib\Type\IScalarValue;

/**
 * 総称型リストとなるクラスです。
 * 
 * @final [継承禁止クラス]
 * @author hiroki sugawara
 */
final class GenericList implements \ArrayAccess, \Iterator, \Countable
{
    // ---------------------------------------------------------------------------------------------
    // private fields
    // ---------------------------------------------------------------------------------------------
    private $obj_list_value = null;
    private $list_type_name = '';
    private $collection     = [];
    private $position       = 0;
    
    // ---------------------------------------------------------------------------------------------
    // constructor / destructor
    // ---------------------------------------------------------------------------------------------
    /**
     * GenericList クラスの新しいインスタンスを初期化します。
     * 
     * @param Variable|String $type_name         リストの要素として許容する型
     * @param Array $collection [初期値=array()] 初期配列
     */
    public function __construct($type_name, array $collection = [])
    {
        list($obj_variable, $obj_value) = EnumClassGen::done(LibEnumName::VARIABLE, $type_name);
        
        $this->setListTypeName($obj_variable);
        $this->setListTypeObject($obj_value);
        $this->setCollection($collection);
    }
    
    // ---------------------------------------------------------------------------------------------
    // public member methods
    // ---------------------------------------------------------------------------------------------
    /**
     * リストが保持するコレクションを配列に変換したものを取得します。
     * 
     * @return Array 配列に変換したリストのコレクション
     */
    public function toArray()
    {
        return $this->collection;
    }
    
    /**
     * @see \ArrayAccess::offsetExists()
     */
    public function offsetExists($offset)
    {
        return isset($this->collection[$offset]);
    }
    
    /**
     * @see \ArrayAccess::offsetGet()
     */
    public function offsetGet($offset)
    {
        return isset($this->collection[$offset]) ? $this->collection[$offset] : null;
    }
    
    /**
     * @see \ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value)
    {
        Arrays::addWhen(true, $this->collection, $this->getCasted($value), $offset);
    }
    
    /**
     * @see \ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset)
    {
        unset($this->collection[$offset]);
    }
    
    /**
     * @see \Iterator::current()
     */
    public function current()
    {
        return $this->collection[$this->position];
    }
    
    /**
     * @see \Iterator::key()
     */
    public function key()
    {
        return key($this->collection)[$this->position];
    }
    
    /**
     * @see \Iterator::next()
     */
    public function next()
    {
        ++$this->position;
    }
    
    /**
     * @see \Iterator::rewind()
     */
    public function rewind()
    {
        $this->position	= 0;
    }
    
    /**
     * @see \Iterator::valid()
     */
    public function valid()
    {
        return $this->offsetExists($this->position);
    }
    
    /**
     * @see \Countable::count()
     */
    public function count()
    {
        return count($this->collection);
    }
    
    // ---------------------------------------------------------------------------------------------
    // private member methods
    // ---------------------------------------------------------------------------------------------
    /**
     * リストの要素として許容する型の名前を設定します。
     * 
     * @param Variable $obj_variable リストの要素として許容する型を表す列挙型のインスタンス
     */
    private function setListTypeName(Variable $obj_variable)
    {
        $this->list_type_name = String::removeNamespace((string)$obj_variable);
    }

    /**
     * リストの要素として許容する型を表すクラスインスタンスを取得します。
     * 
     * @return IScalarValue リストの要素として許容する型を表すクラスインスタンス
     */
    private function getListTypeObject()
    {
        return $this->obj_list_value;
    }
    
    /**
     * リストの要素として許容する型を表すクラスインスタンスを設定します。
     * 
     * @param IScalarValue $obj_value リストの要素として許容する型を表すクラスインスタンス
     */
    private function setListTypeObject(IScalarValue $obj_value)
    {
        $this->obj_list_value = $obj_value;
    }
    
    /**
     * リストのコレクションを設定します。
     * 
     * @param Array $collection リストのコレクションとなる配列
     */
    private function setCollection(array $collection)
    {
        $list = [];
        
        foreach ($collection as $key => $value) {
            Arrays::addWhen(true, $list, $this->getCasted($value), $key);
        }
        
        Arrays::copyWhen(true, $this->collection, $list);
    }
    
    /**
     * 入力値をキャストした値を取得します。
     * 
     * @param mixed $value キャストしたい値
     * 
     * @throws \DomainException キャストした値が総称型リストで許容されない型である場合
     * 
     * @return mixed キャストした入力値
     */
    private function getCasted($value)
    {
        if ($this->getListTypeObject()->isValue($value) === false) {
            throw new \DomainException("Generics list only accepts {$this->list_type_name}.");
        }
        
        return $this->getListTypeObject()->getValue($value);
    }
}
