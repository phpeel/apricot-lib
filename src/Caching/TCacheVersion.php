<?php
namespace Phpeel\ApricotLib\Caching;

use Phpeel\ApricotLib\Common\Arrays;
use Phpeel\ApricotLib\Common\String;

/**
 * キャッシュデータのバージョン管理を行う機能を提供するトレイトです。
 * 
 * @author hiroki sugawara
 */
trait TCacheVersion
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
        
        return $this->getCacheVersionPrefix($group_name) . (empty($key) ? '' : "_{$key}");
    }

    /**
     * 指定したグループのバージョンを表す文字列を生成します。
     * 
     * @param String $group_name グループの名前
     * 
     * @return string 指定したグループのバージョンを表す文字列
     */
    private function getCacheVersionPrefix($group_name)
    {
        return "{$group_name}_{$this->generateCacheVersion($group_name)}";
    }

    /**
     * 指定したグループのバージョン番号を生成します。
     * 
     * @param String $group_name              グループの名前
     * @param Integer $delta_value [初期値=0] 現在のバージョンを操作するデルタ値
     * 
     * @return Integer 指定したグループのバージョン番号
     */
    private function generateCacheVersion($group_name, $delta_value = 0)
    {
        $new_version = intval($this->getCacheVersion($group_name)) + $delta_value;
        
        $this->setCacheVersion($group_name, $new_version);
        
        return $new_version;
    }

    /**
     * 指定したグループの現在のバージョン番号を取得します。
     * 
     * @param String $group_name グループの名前
     * 
     * @return Integer 指定したグループの現在のバージョン番号
     */
    private function getCacheVersion($group_name)
    {
        return call_user_func($this->getCallbackGetter(), $this->getCacheVersionKey($group_name)) ?: 1;
    }

    /**
     * 指定したグループの現在のバージョン番号を設定します。
     * 
     * @param String $group_name グループの名前
     * @param Integer $version   グループに設定する新しいバージョン番号
     */
    private function setCacheVersion($group_name, $version)
    {
        call_user_func($this->getCallbackSetter(), $this->getCacheVersionKey($group_name), $version);
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
    final protected function getCacheVersionKey($group_name)
    {
        if (String::isValid($group_name) === false) {
            throw new \InvalidArgumentException('$group_name only accepts string.');
        }
        
        return "{$group_name}_version";
    }
}
