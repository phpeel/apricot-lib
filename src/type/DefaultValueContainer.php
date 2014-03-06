<?php
namespace Phpingguo\ApricotLib\Type;

use Phpingguo\ApricotLib\Common\String as CString;
use Phpingguo\ApricotLib\LibrarySupervisor;
use Symfony\Component\Yaml\Parser;

/**
 * スカラータイプクラスのデフォルト値を管理するクラスです。
 * 
 * @final [継承禁止クラス]
 * @author hiroki sugawara
 */
final class DefaultValueContainer
{
    // ---------------------------------------------------------------------------------------------
    // class fields
    // ---------------------------------------------------------------------------------------------
    private static $value_container = [];

    // ---------------------------------------------------------------------------------------------
    // public class methods
    // ---------------------------------------------------------------------------------------------
    /**
     * 指定したスカラータイプクラスのデフォルト値を取得します。
     * 
     * @param String $class_name                 デフォルト値を取得するクラスの名前
     * @param mixed $default_value [初期値=null] デフォルト値が無い場合に使用する値
     * 
     * @return mixed 指定したスカラータイプクラスのデフォルト値
     */
    public static function get($class_name, $default_value = null)
    {
        return static::initContainer(CString::removeNamespace($class_name), $default_value);
    }

    // ---------------------------------------------------------------------------------------------
    // private class methods
    // ---------------------------------------------------------------------------------------------
    /**
     * 初期化済みのデフォルト値を格納したコンテナのリストを取得します。
     * 
     * @param String $class_name   デフォルト値を取得するクラスの名前
     * @param mixed $default_value デフォルト値が無い場合に使用する値
     * 
     * @return Array 初期化済みのデフォルト値を格納したコンテナのリスト
     */
    private static function initContainer($class_name, $default_value)
    {
        if (empty(static::$value_container[$class_name]) === true) {
            static::setContainer(static::parseSettingFile());
        }
        
        return static::getContainerValue($class_name, $default_value);
    }

    /**
     * デフォルト値を保持するコンテナのリストを設定します。
     * 
     * @param Array $container_list [初期値=null] 新しいデフォルト値を保持するコンテナのリスト
     */
    private static function setContainer(array $container_list = null)
    {
        static::$value_container = $container_list;
    }

    /**
     * デフォルト値を保持するコンテナのリストから指定したスカラータイプクラスに一致する値を取得します。
     * 
     * @param String $class_name   デフォルト値を取得するスカラータイプクラスの名前
     * @param mixed $default_value デフォルト値が無い場合に使用する値 
     *  
     * @return mixed 指定したスカラータイプクラスに一致するデフォルト値
     */
    private static function getContainerValue($class_name, $default_value)
    {
        return isset(static::$value_container[$class_name]) ? static::$value_container[$class_name] : $default_value;
    }

    /**
     * ライブラリの設定ファイルを解析します。
     * 
     * @return Array|null 正常に解析できた場合は解析結果の配列。それ以外の場合は null。
     */
    private static function parseSettingFile()
    {
        $path  = LibrarySupervisor::getConfigPath() . DIRECTORY_SEPARATOR . 'default_values.yml';
        $value = is_file($path) ? (new Parser())->parse(file_get_contents($path)) : null;

        return is_array($value) ? $value : null;
    }
}
