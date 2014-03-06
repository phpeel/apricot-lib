<?php
namespace Phpingguo\ApricotLib;

use Phpingguo\ApricotLib\Common\String as CString;

/**
 * ライブラリを統括するクラスです。
 * 
 * @final [継承禁止クラス]
 * @author hiroki sugawara
 */
final class LibrarySupervisor
{
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
