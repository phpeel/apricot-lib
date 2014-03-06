<?php
namespace Phpingguo\ApricotLib;

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
        return realpath(__DIR__) . DIRECTORY_SEPARATOR . '..';
    }

    /**
     * ライブラリの設定ファイルがあるディレクトリのファイルパスを取得します。
     * 
     * @return String ライブラリの設定ファイルがあるディレクトリのファイルパス
     */
    public static function getConfigPath()
    {
        return static::getBasePath() . DIRECTORY_SEPARATOR . 'config';
    }
}
