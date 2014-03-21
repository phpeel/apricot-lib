<?php
namespace Phpingguo\ApricotLib\Type\String;

use Phpingguo\ApricotLib\Common\String as CString;
use Phpingguo\ApricotLib\Enums\Charset;
use Phpingguo\ApricotLib\LibSupervisor;
use Phpingguo\ApricotLib\Type\DefaultValueContainer;
use Phpingguo\ApricotLib\Type\IExtendScalarValue;
use Phpingguo\ApricotLib\Type\TScalarValue;

/**
 * フレームワークで使用できる文字列型を表すための基本となる抽象クラスです。
 * 
 * @abstract
 * @author hiroki sugawara
 */
abstract class BaseString implements IExtendScalarValue
{
    // ---------------------------------------------------------------------------------------------
    // import trait
    // ---------------------------------------------------------------------------------------------
    use TScalarValue;
    
    // ---------------------------------------------------------------------------------------------
    // constructor / destructor
    // ---------------------------------------------------------------------------------------------
    /**
     * BaseString クラスの新しいインスタンスを初期化します。
     * 
     * @final [オーバーライド禁止]
     */
    final public function __construct()
    {
        $this->setDefaultValue(DefaultValueContainer::get(get_called_class(), ''));
    }
    
    // ---------------------------------------------------------------------------------------------
    // public class methods
    // ---------------------------------------------------------------------------------------------
    /**
     * BaseString クラスのインスタンスを取得します。
     * 
     * @final [オーバーライド禁止]
     * @return BaseString 生成した、または、生成済みのインスタンスを返します。
     */
    final public static function getInstance()
    {
        // 初期値設定の再ロードは行わない
        return LibSupervisor::getDiContainer()->get(get_called_class());
    }
    
    // ---------------------------------------------------------------------------------------------
    // public member methods
    // ---------------------------------------------------------------------------------------------
    /**
     * @see \Phpingguo\Exts\Lib\Type\IScalarValue::getValue()
     */
    final public function getValue($base_value)
    {
        $value = $this->hasInstanceValue() ? $this->getInstanceValue() : $this->getStringValue($base_value);
        
        if (is_null($value)) {
            throw new \InvalidArgumentException(__METHOD__ . ' only accepts string.');
        }
        
        return $value;
    }
    
    /**
     * @see \Phpingguo\Exts\Lib\Type\IScalarValue::isValue()
     */
    public function isValue(&$check_value)
    {
        return (is_null($check_value) === false && is_numeric($check_value) === false && is_string($check_value));
    }
    
    /**
     * @see \Phpingguo\Exts\Lib\Type\IExtendScalarValue::isValid()
     */
    final public function isValid(&$check_value)
    {
        return ($this->isValue($check_value) && $check_value !== '');
    }
    
    // ---------------------------------------------------------------------------------------------
    // private member methods
    // ---------------------------------------------------------------------------------------------
    /**
     * 引数 $base_value の値を文字列型の値として取得します。
     * 
     * @param mixed $base_value 文字列型の値を取得する変数
     * 
     * @return String|null 値を取得できる場合は文字列型としての値。そうでない場合は null。
     */
    private function getStringValue($base_value)
    {
        if ($this->isValue($base_value)) {
            // urlデコードした文字列を返す（デコードできない文字はそのまま返す挙動を利用）
            $raw_string = urldecode($base_value);
            $str_encode = mb_detect_encoding($raw_string, 'ASCII, UTF-8, SJIS-win', true);
            
            // 文字コードが ascii/utf-8/sjis-win と判定された場合のみ値を正常とみなして返す
            if (CString::isContains($str_encode, [ Charset::UTF8, Charset::ASCII ])) {
                return $raw_string;
            } elseif (CString::isContains($str_encode, [ Charset::SJIS_WIN ])) {
                return mb_convert_encoding($raw_string, Charset::UTF8, $str_encode);
            }
        }
        
        return null;
    }
}
