<?php
namespace Phpingguo\ApricotLib\Type\Int;

use Phpingguo\ApricotLib\Common\Number;
use Phpingguo\ApricotLib\LibrarySupervisor;
use Phpingguo\ApricotLib\Type\DefaultValueContainer;
use Phpingguo\ApricotLib\Type\IScalarValue;
use Phpingguo\ApricotLib\Type\TraitScalarValue;
use Phpingguo\ApricotLib\Type\TraitSignedNumber;

/**
 * フレームワークで使用できる整数型を表すための基本となる抽象クラスです。
 * 
 * @abstract
 * @author hiroki sugawara
 */
abstract class BaseInteger implements IScalarValue
{
    // ---------------------------------------------------------------------------------------------
    // import trait
    // ---------------------------------------------------------------------------------------------
    use TraitScalarValue, TraitSignedNumber;
    
    // ---------------------------------------------------------------------------------------------
    // constructor / destructor
    // ---------------------------------------------------------------------------------------------
    /**
     * BaseInteger クラスの新しいインスタンスを初期化します。
     * 
     * @param Integer|UnsignedInt $value [初期値=null] インスタンスが保持する整数型の値
     * @param Boolean $allow_unsigned [初期値=false]   取得する値に符号なしを許すかどうか
     */
    public function __construct($value = null, $allow_unsigned = false)
    {
        $this->setDefaultValue(DefaultValueContainer::get(get_called_class(), 0));
        $this->setAllowUnsigned($allow_unsigned);
        $this->setInstanceValue($value);
    }
    
    // ---------------------------------------------------------------------------------------------
    // public class methods
    // ---------------------------------------------------------------------------------------------
    /**
     * BaseInteger クラスのインスタンスを取得します。
     * 
     * @final [オーバーライド禁止]
     * @return BaseInteger 生成した、または、生成済みのインスタンス
     */
    final public static function getInstance()
    {
        // 初期値設定の再ロードは行わない
        return LibrarySupervisor::getDiContainer()->get(get_called_class());
    }
    
    // ---------------------------------------------------------------------------------------------
    // public member methods
    // ---------------------------------------------------------------------------------------------
    /**
     * @final [オーバーライド禁止]
     * @see \Phpingguo\Exts\Lib\Type\IScalarValue::getValue()
     */
    final public function getValue($base_value = null)
    {
        $value = $this->hasInstanceValue() ? $this->getInstanceValue() : $this->getIntegerValue($base_value);
        
        if (is_null($value)) {
            throw new \InvalidArgumentException(__METHOD__ . ' only accepts integer.');
        }
        
        return $value;
    }
    
    /**
     * @final [オーバーライド禁止]
     * @see \Phpingguo\Exts\Lib\Type\IScalarValue::isValue()
     */
    final public function isValue(&$check_value)
    {
        // 内部処理用のキャッシュ値をクリアする
        $this->clearCacheValue();
        
        // INT型の最小値以上かつ最大値以下、かつ、型チェックなし比較で同じ値であれば、整数型
        // 符号付チェックがある場合、0以上であれば符号なし整数型、それ以外は符号あり整数型
        if ($this->isIntegerValue($check_value)) {
            // 内部処理用にキャッシュ値をセットする
            $this->setCacheValue(intval($check_value));
            
            return true;
        }
        
        return false;
    }
    
    // ---------------------------------------------------------------------------------------------
    // private member methods
    // ---------------------------------------------------------------------------------------------
    /**
     * 指定した引数の値を整数型としての値として取得します。
     * 
     * @param mixed $base_value 整数型の値を取得する変数
     * 
     * @return Integer 値を取得できる場合はその値。そうでない場合は null。
     */
    private function getIntegerValue($base_value)
    {
        return $this->isValue($base_value) ? $this->getCacheValue(true) : null;
    }

    /**
     * 入力値が整数値であるかどうかを調べます。
     * 
     * @param mixed $value 整数値かどうかを調べる入力値
     *
     * @return Boolean 入力値が整数型である場合は true。それ以外の場合は false。
     */
    private function isIntegerValue($value)
    {
        $integer_value = intval($value);
        $float_value   = floatval($value);
        $is_interval   = Number::isInInterval(
            $float_value,
            $this->getAllowUnsigned() ? 0 : floatval(~PHP_INT_MAX),
            floatval(PHP_INT_MAX)
        );
        
        return (is_numeric($value) && $is_interval === true && $integer_value == $float_value);
    }
}
