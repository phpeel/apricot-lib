<?php
namespace Phpingguo\ApricotLib\Common;

/**
 * 配列操作を拡張するためのクラスです。
 * 
 * @final [継承禁止クラス]
 * @author hiroki sugawara
 */
final class Arrays
{
    // ---------------------------------------------------------------------------------------------
    // public class methods
    // ---------------------------------------------------------------------------------------------
    /**
     * 指定された変数が空要素の配列かそれ以外であるかを調べます。<br />
     * <font color="red">注意！指定した変数が配列以外の場合は false を返します。</font>
     * 
     * @param mixed $value 空要素配列かどうか調べる変数
     * 
     * @return Boolean 指定された変数が空要素の配列である場合は true。それ以外の場合は false。
     */
    public static function isEmpty(&$value)
    {
        return (is_array($value) && count($value) === 0);
    }
    
    /**
     * 指定された変数が有効な配列かどうかを調べます。<br />
     * <font color="red">注意！指定した変数が配列でも空要素の場合は false を返します。</font>
     * 
     * @param mixed $value 有効な配列かどうか調べる変数
     * 
     * @return Boolean 指定された変数が配列型かつ要素数が1以上の時に true。それ以外の場合は false。
     */
    public static function isValid(&$value)
    {
        return (is_array($value) && count($value) > 0);
    }
    
    /**
     * 入力配列のサイズが指定した上限値及び下限値の範囲内であるかどうかを調べます。
     * 
     * @param Array $list                 上限及び下限のチェックを行う配列
     * @param Int $upper_limit            許容する上限のサイズを表す値
     * @param Int $lower_limit [初期値=0] 許容する下限のサイズを表す値
     * 
     * @throws \InvalidArgumentException 上限値または下限値が正の整数ではなかった場合
     * 
     * @return Boolean サイズが範囲内である場合は true。それ以外の場合は false。
     */
    public static function checkSize(array $list, $upper_limit, $lower_limit = 0)
    {
        if (General::isValidUInt($upper_limit, $lower_limit) === false) {
            throw new \InvalidArgumentException('$upper_limit and $lower_list accepts unsigned integer.');
        }
        
        $length = count($list);
        
        return ($lower_limit <= $length && $length <= $upper_limit);
    }
    
    /**
     * 指定した値が配列に使用できるキーとして妥当かどうか（0以上の整数値、又は文字列）を調べます。
     * 
     * @param mixed $value キーとしての妥当性を調べる変数
     * 
     * @return Boolean 指定した値がキーとして妥当である場合は true。それ以外の場合は false。
     */
    public static function isValidKey($value)
    {
        return (General::isValidUInt($value) || String::isValid($value));
    }
    
    /**
     * 入力配列に指定したキーの要素が存在するかどうかを調べます。
     * 
     * @param Array $list         キーの存在を調べる配列
     * @param String|Integer $key 存在を調べるキーの名前
     * 
     * @return Boolean 入力配列に指定したキーの要素が存在する場合は true。それ以外の場合は false。
     */
    public static function isExistKey(array $list, $key)
    {
        return static::isValidKey($key) && isset($list[$key]);
    }
    
    /**
     * 入力配列から指定したキーに該当する値を取得します。
     * 
     * @param Array $list                  値を取得する配列
     * @param String|Integer $key          値を取得するキーの名前
     * @param mixed $default [初期値=null] 値が取得できなかった場合に使用するデフォルト値
     * 
     * @return mixed 入力配列から指定したキーに該当する値
     */
    public static function getValue(array $list, $key, $default = null)
    {
        return static::isExistKey($list, $key) ? $list[$key] : $default;
    }
    
    /**
     * 条件を満たす場合に入力配列へ新しく項目を追加します。
     * 
     * @param Boolean $conditions               追加実行を満たすための条件
     * @param Array $list                       項目を追加する配列
     * @param mixed $item                       配列へ新しく追加する項目
     * @param Integer|String $key [初期値=null] 配列に追加する位置を示すインデックス番号またはキー名
     * 
     * @return Boolean 入力配列へ新しく項目を追加できた場合は true。それ以外の場合は false。
     */
    public static function addWhen($conditions, array &$list, $item, $key = null)
    {
        if ($conditions !== true || isset($item) === false) {
            return false;
        }
        
        if (static::isValidKey($key)) {
            $list[$key] = General::getParsedValue($item);
        } else {
            $list[]     = General::getParsedValue($item);
        }
        
        return true;
    }
    
    /**
     * 条件を満たす場合に入力配列から指定したキーを持つ項目を削除します。
     * 
     * @param Boolean $conditions 削除実行を満たすための条件
     * @param Array $list         項目を削除する配列
     * @param Integer|String $key 配列から削除する項目を示すインデックス番号またはキー名
     * 
     * @return boolean 入力配列から項目を削除できた場合は true。それ以外の場合は false。
     */
    public static function removeWhen($conditions, array &$list, $key)
    {
        if ($conditions !== true || static::isExistKey($list, $key) === false) {
            return false;
        }
        
        unset($list[$key]);
        
        return true;
    }
    
    /**
     * 条件を満たす場合に入力配列に対して再帰的に条件に一致する項目を削除します。
     * 
     * @param Boolean $loop_conditions    再帰削除処理実行を満たすための条件
     * @param Callable $remove_conditions 項目削除の実行を満たすための条件
     * @param Array $list                 項目を削除する配列
     * 
     * @return Boolean 入力配列から条件を満たす項目を一つでも削除できた場合は true。それ以外の場合は false。
     */
    public static function removeEach($loop_conditions, callable $remove_conditions, array &$list)
    {
        if ($loop_conditions !== true) {
            return false;
        }
        
        $result = false;
        
        foreach ($list as $key => $value) {
            $result |= static::removeWhen($remove_conditions($value, $key), $list, $key);
        }
        
        return (bool)$result;
    }
    
    /**
     * 条件を満たす場合に入力配列同士のコピーを行います。
     * 
     * @param Boolean $conditions  コピー実行を満たすための条件
     * @param Array $to            コピー先となる配列
     * @param Array|Callable $from コピー元となる配列
     * 
     * @return Boolean 入力配列同士のコピーを行った場合は true。それ以外の場合は false。
     */
    public static function copyWhen($conditions, array &$to, $from)
    {
        $from_list = static::getParsedArray($from);
        
        if ($conditions !== true || is_null($from_list)) {
            return false;
        }
        
        $to = $from_list;
        
        return true;
    }
    
    /**
     * 条件を満たす場合に入力配列にもう一方の入力配列を統合します。
     * 
     * @param Boolean $conditions 統合実行を満たすための条件
     * @param Array $target       統合先となる配列
     * @param Array $merged       統合させる配列
     * 
     * @return Boolean 入力配列同士の統合を行った場合は true。それ以外の場合は false。
     */
    public static function mergeWhen($conditions, array &$target, $merged)
    {
        $marge_list = static::getParsedArray($merged);
        
        if ($conditions !== true || is_null($marge_list)) {
            return false;
        }
        
        foreach ($marge_list as $key => $value) {
            static::partialMerge($target, $key, $value);
        }
        
        return true;
    }
    
    /**
     * 入力配列の特定のキーが持つ既存の値に指定した値を統合します。
     * 
     * @param Array $list         値を統合する配列
     * @param Integer|String $key 値を統合するキー
     * @param mixed $value        統合する値
     */
    public static function partialMerge(array &$list, $key, $value)
    {
        if (isset($list[$key]) && is_array($list[$key]) && is_array($value)) {
            static::mergeWhen(true, $list[$key], $value);
        } else {
            static::addWhen(true, $list, $value, $key);
        }
    }
    
    /**
     * 入力配列の要素を全て削除します。
     * 
     * @param Array $list 要素を全て削除する配列
     */
    public static function clear(array &$list)
    {
        static::isValid($list) && $list = [];
    }
    
    // ---------------------------------------------------------------------------------------------
    // private class methods
    // ---------------------------------------------------------------------------------------------
    /**
     * 入力値を配列変数として解析したものを取得します。
     * 
     * @param mixed $value 解析する変数
     * 
     * @return Array|null 入力値が配列である場合はその配列。それ以外の場合は null。
     */
    private static function getParsedArray($value)
    {
        $parsed = General::getParsedValue($value);
        
        return is_array($parsed) ? $parsed : null;
    }
}
