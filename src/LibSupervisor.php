<?php
namespace Phpingguo\ApricotLib;

use Phpingguo\ApricotLib\Common\String as CString;
use Phpingguo\CitronDI\AuraDIWrapper;

/**
 * ライブラリを統括するクラスです。
 * 
 * @final [継承禁止クラス]
 * @author hiroki sugawara
 */
final class LibSupervisor
{
    // ---------------------------------------------------------------------------------------------
    // const fields
    // ---------------------------------------------------------------------------------------------
    const ENUM_CHARSET  = 'Charset';
    const ENUM_VARIABLE = 'Variable';

    // ---------------------------------------------------------------------------------------------
    // public static methods
    // ---------------------------------------------------------------------------------------------
    /**
     * ライブラリで使用するDIコンテナのインスタンスを取得します。
     *
     * @param String $service_group_name [初期値='library'] 使用するサービスのグループ名
     *
     * @return \Aura\Di\Container ライブラリで使用するDIコンテナのインスタンス
     */
    public static function getDiContainer($service_group_name = 'library')
    {
        return AuraDIWrapper::init($service_group_name, static::getConfigPath());
    }
    
    /**
     * ライブラリのルートディレクトリのファイルパスを取得します。
     * 
     * @return String ライブラリのルートディレクトリのファイルパス
     */
    public static function getBasePath()
    {
        return CString::unionDirectoryPath(__DIR__, '..');
    }

    /**
     * ライブラリの設定ファイルがあるディレクトリのファイルパスを取得します。
     * 
     * @return String ライブラリの設定ファイルがあるディレクトリのファイルパス
     */
    public static function getConfigPath()
    {
        return CString::unionDirectoryPath(static::getBasePath(), 'config');
    }

    /**
     * 列挙型クラスの名前空間付きの完全修飾名を取得します。
     * 
     * @param String $enum_name 完全修飾名を取得する列挙型クラスの名前
     *
     * @throws \InvalidArgumentException 有効な列挙型クラスではなかった場合
     * @return String 列挙型クラスの名前空間付きの完全修飾名
     */
    public static function getEnumFullName($enum_name)
    {
        return CString::concat("Phpingguo\\ApricotLib\\Enums\\", $enum_name);
    }
}
