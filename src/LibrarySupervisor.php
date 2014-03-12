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
final class LibrarySupervisor
{
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
}
