<?php
namespace Phpeel\ApricotLib\Type\Enum;

use Phpeel\ApricotLib\Common\String;

/**
 * PHP で列挙型を疑似的に再現する抽象クラスです。
 * 
 * @abstract
 * @author hiroki sugawara
 */
abstract class Enum
{
    // ---------------------------------------------------------------------------------------------
    // private fields
    // ---------------------------------------------------------------------------------------------
    private $name   = '';
    private $scalar = null;
    
    // ---------------------------------------------------------------------------------------------
    // constructor
    // ---------------------------------------------------------------------------------------------
    /**
     * Enum クラスの新しいインスタンスを初期化します。
     * 
     * @final [オーバーライド禁止]
     * @param mixed $value このインスタンスの元のクラスに定義されている定数のうちどれかの値
     * 
     * @throws \InvalidArgumentException クラスに定義されていない値を指定した場合
     */
    final public function __construct($value)
    {
        $obj_reflection = new \ReflectionObject($this);
        $constants      = $obj_reflection->getConstants();
        $enum_key_name  = array_search($value, $constants);
        
        if (String::isValid($enum_key_name) === false) {
            throw new \InvalidArgumentException('This class not defined constant value.' . $value);
        }
        
        $this->name   = $enum_key_name;
        $this->scalar = $value;
    }
    
    // ---------------------------------------------------------------------------------------------
    // public member methods
    // ---------------------------------------------------------------------------------------------
    /**
     * 指定した値を持つこのクラスのオブジェクトのインスタンスを取得します。
     * 
     * @final [オーバーライド禁止]
     * @param String $value 取得する要素の値を示す名前
     * @param Array $args   取得する要素の値を示す名前のメソッドに渡す引数配列（未使用）
     * 
     * @return Enum 指定した値を持つこのクラスのオブジェクトのインスタンス
     */
    final public static function __callStatic($value, $args)
    {
        $class = get_called_class();
        
        return new $class(constant("{$class}::{$value}"));
    }
    
    /**
     * 条件付きで Enum クラスの新しいインスタンスを初期化します。
     * 
     * @final [オーバーライド禁止]
     * @param String $value                       取得する要素の値を示す定数名
     * @param String $default_value [初期値=null] 引数 $conditions の条件を満たさない場合に使用される定数名
     * @param callable $conditions [初期値=null]  条件を示すコールバックメソッド
     * 
     * @return Enum 指定した値を持つこのクラスのオブジェクトのインスタンス
     */
    final public static function init($value, $default_value = null, callable $conditions = null)
    {
        $class = get_called_class();
        
        if ($value instanceof $class) {
            return $value;
        }
        
        $scalar = (isset($conditions) && $conditions() === true) ? $value : (
            isset($default_value) ? $default_value : $value);
        
        return new $class($scalar);
    }

    /**
     * 要素の定数としての名前を取得します。
     * 
     * @final [オーバーライド禁止]
     * @return String 要素の定数としての名前
     */
    final public function getName()
    {
        return $this->name;
    }
    
    /**
     * 要素の値を取得します。
     * 
     * @final [オーバーライド禁止]
     * @return mixed このインスタンスが保持する要素の値
     */
    final public function getValue()
    {
        return $this->scalar;
    }
    
    /**
     * インスタンスが文字列として扱われた際に文字列を取得します。
     * 
     * @final [オーバーライド禁止]
     * @return String このインスタンスが保持する要素の値の文字列
     */
    final public function __toString()
    {
        return (string)$this->scalar;
    }
}
