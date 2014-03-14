<?php
namespace Phpingguo\ApricotLib\Caching;

use Phpingguo\ApricotLib\Common\Arrays;
use Phpingguo\ApricotLib\Common\String;

/**
 * キャッシュデータのバージョン管理を行う機能を提供するトレイトです。
 * 
 * @author hiroki sugawara
 */
trait TraitCacheVersion
{
    // ---------------------------------------------------------------------------------------------
    // private fields
    // ---------------------------------------------------------------------------------------------
    private $getter_callback = null;
    private $setter_callback = null;
    
    // ---------------------------------------------------------------------------------------------
    // private member methods
    // ---------------------------------------------------------------------------------------------
    /**
     * キャッシュデータの取得を行うコールバックメソッドを取得します。
     * 
     * @final [オーバーライド禁止]
     * @return callable キャッシュデータの取得を行うコールバックメソッド
     */
    final protected function getCallbackGetter()
    {
        return $this->getter_callback;
    }

    /**
     * キャッシュデータの取得を行うコールバックメソッドを設定します。
     * 
     * @final [オーバーライド禁止]
     * @param callable $getter キャッシュデータの取得を行うコールバックメソッド 
     */
    final protected function setCallbackGetter(callable $getter)
    {
        $this->getter_callback = $getter;
    }

    /**
     * キャッシュデータの設定を行うコールバックメソッドを取得します。
     * 
     * @final [オーバーライド禁止]
     * @return callable キャッシュデータの設定を行うコールバックメソッド
     */
    final protected function getCallbackSetter()
    {
        return $this->setter_callback;
    }

    /**
     * キャッシュデータの設定を行うコールバックメソッドを設定します。
     * 
     * @final [オーバーライド禁止]
     * @param callable $setter キャッシュデータの設定を行うコールバックメソッド 
     */
    final protected function setCallbackSetter(callable $setter)
    {
        $this->setter_callback = $setter;
    }

    /**
     * キャッシュデータに使用する完全なキーの名前またはその一覧を取得します。
     *
     * @final [オーバーライド禁止]
     * @param String $group_name キーが属するグループの名前
     * @param String|Array $key  取得対象となるキーの名前またはその一覧
     * 
     * @return Array キャッシュデータに使用する完全なキーの名前またはその一覧
     */
    final protected function getConvertedKeyName($group_name, $key)
    {
        if (Arrays::isValid($key)) {
            return array_map(
                function ($key_name) use ($group_name) {
                    return $this->generateKeyName($group_name, $key_name);
                },
                $key
            );
        }
        
        return $this->generateKeyName($group_name, $key);
    }
    
    /**
     * キャッシュデータの参照や保存に使用するキーの完全名を生成します。
     * 
     * @final [オーバーライド禁止]
     * @param String $group_name                     キーのグループ名
     * @param String $key                            キーの名前
     * @param Boolean $allow_key_null [初期値=false] キーの名前に無効な値を許すかどうか
     *
     * @throws \InvalidArgumentException パラメータ $allow_key_null が false の時に $key が無効な値の場合
     *
     * @return String キャッシュデータの参照や保存に使用するキーの完全名
     */
    final protected function generateKeyName($group_name, $key, $allow_key_null = false)
    {
        if ($allow_key_null === false && empty($key)) {
            throw new \InvalidArgumentException('$key does not accepts null.');
        }
        
        return $this->generateCacheVersion($group_name) . (empty($key) ? '' : "_{$key}");
    }

    /**
     * 指定したグループのバージョンを表す文字列を生成します。
     * 
     * @final [オーバーライド禁止]
     * @param String $group_name グループの名前
     * 
     * @return string 指定したグループのバージョンを表す文字列
     */
    final protected function generateCacheVersion($group_name)
    {
        $ver_name = $this->getCacheVersion($group_name);
        $version  = call_user_func($this->getCallbackGetter(), $ver_name) ?: 1;
        
        call_user_func($this->getCallbackSetter(), $ver_name, $version);
        
        return "{$group_name}_{$version}";
    }

    /**
     * 指定したグループのバージョン番号を参照するためのキーを取得します。
     * 
     * @final [オーバーライド禁止]
     * @param String $group_name バージョン番号を参照するグループの名前
     *
     * @throws \InvalidArgumentException グループの名前が文字列型ではなかった場合
     * @return String 指定したグループのバージョン番号を参照するためのキー
     */
    final protected function getCacheVersion($group_name)
    {
        if (String::isValid($group_name) === false) {
            throw new \InvalidArgumentException('$group_name only accepts string.');
        }
        
        return "{$group_name}_version";
    }
}
