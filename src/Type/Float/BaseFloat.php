<?php
namespace Phpingguo\ApricotLib\Type\Float;

use Phpingguo\ApricotLib\LibSupervisor;
use Phpingguo\ApricotLib\Type\DefaultValueContainer;
use Phpingguo\ApricotLib\Type\IScalarValue;
use Phpingguo\ApricotLib\Type\TScalarValue;
use Phpingguo\ApricotLib\Type\TSignedNumber;

/**
 * フレームワークで使用できる浮動小数点数型を表すための基本となる抽象クラスです。
 * 
 * @abstract
 * @author hiroki sugawara
 */
abstract class BaseFloat implements IScalarValue
{
    // ---------------------------------------------------------------------------------------------
    // import trait
    // ---------------------------------------------------------------------------------------------
    use TScalarValue, TSignedNumber;
    
    // ---------------------------------------------------------------------------------------------
    // constructor / destructor
    // ---------------------------------------------------------------------------------------------
    /**
     * BaseFloat クラスの新しいインスタンスを初期化します。
     * 
     * @param Float|UnsignedFloat $value [初期値=null] インスタンスが保持する浮動小数点数型の値
     * @param Boolean $allow_unsigned [初期値=false]   取得する値に符号なしを許すかどうか
     */
    public function __construct($value = null, $allow_unsigned = false)
    {
        $this->setDefaultValue(DefaultValueContainer::get(get_called_class(), 0.0));
        $this->setAllowUnsigned($allow_unsigned);
        $this->setInstanceValue($value);
    }
    
    // ---------------------------------------------------------------------------------------------
    // public class methods
    // ---------------------------------------------------------------------------------------------
    /**
     * BaseFloat クラスのインスタンスを取得します。
     * 
     * @final [オーバーライド禁止]
     * @return BaseFloat 生成した、または、生成済みのインスタンス
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
     * @final [オーバーライド禁止]
     * @see \Phpingguo\Exts\Lib\Type\IScalarValue::getValue()
     */
    final public function getValue($base_value = null)
    {
        $value = $this->hasInstanceValue() ? $this->getInstanceValue() : $this->getFloatValue($base_value);
        
        if (is_null($value)) {
            throw new \InvalidArgumentException(__METHOD__ . ' only accepts float.');
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
        
        if (is_null($check_value) === false && is_numeric($check_value)) {
            $float_value    = floatval($check_value);
            $allow_unsigned = $this->getAllowUnsigned();
            
            // int 型の範囲にある数値も全て float 型として扱う
            if ($allow_unsigned === false || $allow_unsigned === true && $float_value >= 0) {
                // 内部処理用にキャッシュ値をセットする
                $this->setCacheValue($float_value);
                
                return true;
            }
        }
        
        return false;
    }
    
    // ---------------------------------------------------------------------------------------------
    // private member methods
    // ---------------------------------------------------------------------------------------------
    /**
     * 指定した引数の値を浮動小数点数型としての値として取得します。
     * 
     * @param mixed $base_value 浮動小数点数型の値を取得する変数
     * 
     * @return Float 値を取得できる場合はその値。そうでない場合は null。
     */
    private function getFloatValue($base_value)
    {
        return $this->isValue($base_value) ? $this->getCacheValue(true) : null;
    }
}
