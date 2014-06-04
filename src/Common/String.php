<?php
namespace Phpingguo\ApricotLib\Common;

/**
 * 文字列操作を拡張するためのクラスです。
 * 
 * @final [継承禁止クラス]
 * @author hiroki sugawara
 */
final class String
{
    /**
     * 入力文字列が有効な文字列であるかどうかを調べます。
     * 
     * @param mixed $value                      有効であるかどうかを調べる文字列
     * @param Boolean $is_strict [初期値=false] 数値文字列を文字列として扱わないかどうか
     * 
     * @return Boolean 有効な文字列の場合は true。それ以外の場合は false。
     */
    public static function isValid($value, $is_strict = false)
    {
        if ($is_strict === true) {
            return (is_numeric($value) === false && static::isValid($value));
        } else {
            return (is_string($value) && $value !== '');
        }
    }
    
    /**
     * キーワードリストのうち一つが指定した文字列に含まれるかどうかを検出します。
     * 
     * @param String $haystack                   検索対象の文字列
     * @param Array $needle_list                 検索するキーワードのリスト
     * @param Boolean $ignore_case [初期値=true] 大文字小文字を区別しないかどうか
     * 
     * @return Boolean キーワードリストのうち一つが検索対象の文字列に含まれる場合は true。
     * それ以外の場合は false。
     */
    public static function isContains($haystack, array $needle_list, $ignore_case = true)
    {
        if (static::isValid($haystack)) {
            $exec_method = ($ignore_case === true) ? 'stripos' : 'strpos';
            
            foreach ($needle_list as $needle) {
                if ($exec_method($haystack, $needle) !== false) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * 名前空間を伴うクラス名から名前空間を削除したものを取得します。
     * 
     * @param String $class_name 名前空間を削除したいクラス名
     * 
     * @return String 指定したクラスが存在する場合は名前空間を削除した名前。
     * それ以外の場合は空白文字列。
     */
    public static function removeNamespace($class_name)
    {
        if (static::isValid($class_name, true)) {
            return (false !== ($pos = strrpos($class_name, '\\'))) ? substr($class_name, $pos + 1) : $class_name;
        }
        
        return '';
    }
    
    /**
     * 正規表現検索を行い、期待しない値以外の値と一致するかどうかを判別します。
     * 
     * @param String $search      検索対象の文字列
     * @param String $regex       正規表現文字列
     * @param mixed $not_expected 正規表現検索の実行結果の期待しない値
     * (0=マッチする又は失敗, 1=マッチしない又は失敗, false=マッチする又はマッチしない)
     * 
     * @return Boolean 期待しない値以外の値と一致する場合は true。それ以外の場合は false。
     */
    public static function isNotRegexMatched($search, $regex, $not_expected)
    {
        return (isset($regex) && $not_expected !== preg_match($regex, $search));
    }

    /**
     * ディレクトリのファイルパスにサブディレクトリのファイル名を結合したものを取得します。
     *
     * @param String $parent_path                結合先のディレクトリのファイルパス
     * @param String $sub_directory [初期値=null] 結合するサブディレクトリの名前
     *
     * @throws \RuntimeException 存在しないディレクトリを指定した場合
     * @return String サブディレクトリのファイル名を結合したディレクトリのファイルパス
     */
    public static function unionDirectoryPath($parent_path, $sub_directory = null)
    {
        $full_path = static::getRealPath($parent_path, $sub_directory);

        if ($full_path !== false && is_dir($full_path)) {
            return $full_path;
        }

        throw new \RuntimeException('Directory was not to be found.');
    }

    /**
     * ディレクトリのファイルパスにファイルの名前を結合したものを取得します。
     *
     * @param String $parent_path            結合先のディレクトリのファイルパス
     * @param String $sub_file [初期値=false] 結合するファイルの名前
     *
     * @throws \RuntimeException 存在しないファイルを指定した場合
     * @return String ディレクトリのファイルパスにファイルの名前
     */
    public static function unionFilePath($parent_path, $sub_file = null)
    {
        $full_path = static::getRealPath($parent_path, $sub_file);

        if ($full_path !== false && is_file($full_path)) {
            return $full_path;
        }

        throw new \RuntimeException('File was not to be found.');
    }

    /**
     * 指定したディレクトリのパスとそれに属するファイルおよびディレクトリから絶対パスを取得します。
     *
     * @param String $parent_directory 取得する絶対パスの親ディレクトリのパス
     * @param String $child_item       親ディレクトリに属するファイル及びディレクトリの名前
     *
     * @return String|Boolean 成功した場合は正規化した絶対パス。失敗した場合は false。
     */
    public static function getRealPath($parent_directory, $child_item)
    {
        $full_path = false;

        if (static::isValid($parent_directory)) {
            if (static::isValid($child_item)) {
                $full_path = realpath($parent_directory . DIRECTORY_SEPARATOR . $child_item);
            } else if (is_null($child_item)) {
                $full_path = realpath($parent_directory);
            }
        }

        return $full_path;
    }

    /**
     * 二つの文字列を連結します。
     * 
     * @param String $front_str 前に連結される文字列
     * @param String $back_str 後ろに連結される文字列
     *
     * @throws \InvalidArgumentException 両方のパラメータが有効な列挙型クラスではなかった場合
     * @return String 連結した文字列
     */
    public static function concat($front_str, $back_str)
    {
        if (static::isValid($front_str, true) === false || static::isValid($back_str, true) === false) {
            throw new \InvalidArgumentException('All parameters only accepts string type.');
        }
        
        return "{$front_str}{$back_str}";
    }
}
