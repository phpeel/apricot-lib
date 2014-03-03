<?php
namespace Phpingguo\ApricotLib\Type;

/**
 * フレームワークでのスカラー値オブジェクトを扱うための基本処理を提供するトレイトです。
 * 
 * @author hiroki sugawara
 */
trait TraitScalarValue
{
    // ---------------------------------------------------------------------------------------------
    // class fields
    // ---------------------------------------------------------------------------------------------
    protected static $service_name = 'library';
    
    // ---------------------------------------------------------------------------------------------
    // private fields
    // ---------------------------------------------------------------------------------------------
    private $has_instance_value = false;
    private $instance_value     = null;
    private $default_value      = null;
    private $cache_value        = null;
    
    // ---------------------------------------------------------------------------------------------
    // public member methods
    // ---------------------------------------------------------------------------------------------
    /**
     * @final [オーバーライド禁止]
     * @see \Phpingguo\Exts\Lib\Type\IScalarValue::getDefaultValue()
     */
    final public function getDefaultValue()
    {
        return $this->default_value;
    }
    
    // ---------------------------------------------------------------------------------------------
    // private member methods
    // ---------------------------------------------------------------------------------------------
    /**
     * スカラータイプクラスのデフォルト値を設定します。
     * 
     * @final [オーバーライド禁止]
     * @param mixed $value スカラータイプクラスのデフォルト値
     */
    final protected function setDefaultValue($value)
    {
        $this->default_value = $value;
    }
    
    /**
     * インスタンスが持つ固有の型の値を取得します。
     * 
     * @final [オーバーライド禁止]
     * @return mixed インスタンスが持つ固有の型の値
     */
    final protected function getInstanceValue()
    {
        return $this->instance_value;
    }
    
    /**
     * インスタンスが持つ固有の型の値を設定します。
     *
     * @final [オーバーライド禁止]
     * @param mixed $value インスタンスが持つ固有の型の値
     */
    final protected function setInstanceValue($value)
    {
        if (is_null($value) === false) {
            $this->instance_value     = $this->getValue($value);
            $this->has_instance_value = true;
        }
    }
    
    /**
     * インスタンスが持つ固有の型の値を所持しているかどうかを判別します。
     * 
     * @final [オーバーライド禁止]
     * @return Boolean インスタンスが値を保持している場合は true。それ以外の場合は false。
     */
    final protected function hasInstanceValue()
    {
        return $this->has_instance_value;
    }
    
    /**
     * インスタンスが使用するキャッシュ値を取得します。
     * 
     * @final [オーバーライド禁止]
     * @param Boolean $is_after_clear [初期値=false] 取得した後にキャッシュ値をクリアするかどうか
     * 
     * @return mixed インスタンスが使用するキャッシュ値
     */
    final protected function getCacheValue($is_after_clear = false)
    {
        $value = $this->cache_value;
        ($is_after_clear === true) && $this->clearCacheValue();
        
        return $value;
    }
    
    /**
     * インスタンスが使用するキャッシュ値を設定します。
     * なお、null は値として指定できません。値をクリアする場合は clearCacheValue をコールして下さい。
     * 
     * @final [オーバーライド禁止]
     * @param mixed $value インスタンスが使用するキャッシュ値
     * 
     * @see TraitScalarValue::clearCacheValue()
     */
    final protected function setCacheValue($value)
    {
        is_null($value) || $this->cache_value = $value;
    }
    
    /**
     * インスタンスが使用するキャッシュ値をクリアします。
     * 
     * @final [オーバーライド禁止]
     */
    final protected function clearCacheValue()
    {
        $this->cache_value = null;
    }
}
