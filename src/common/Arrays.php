<?php
namespace Phpingguo\ApricotLib\Common;

use Phpingguo\ApricotLib\Enums\Charset;

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
        if (Number::isValidUInt($upper_limit, $lower_limit) === false) {
            throw new \InvalidArgumentException('$upper_limit and $lower_list accepts unsigned integer.');
        }
        
        return Number::isInInterval(count($list), $lower_limit, $upper_limit);
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
        return (Number::isValidUInt($value) || String::isValid($value));
    }
    
    /**
     * 入力配列に指定したキーの要素が存在するかどうかを調べます。
     * 
     * @param Array $list         キーの存在を調べる配列
     * @param String|Integer $key 存在を調べるキーの名前
     * 
     * @return Boolean 入力配列に指定したキーの要素が存在する場合は true。それ以外の場合は false。
     */
    public static function isExistKey($list, $key)
    {
        return (static::isValidKey($key) && is_array($list) && isset($list[$key]));
    }
    
    /**
     * 入力配列から指定したキーに該当する値を取得します。
     * 
     * @param Array $list                  値を取得する配列
     * @param String|Integer $key          値を取得するキーの名前
     * @param mixed $default [初期値=null] 値が取得できなかった場合に使用するデフォルト値
     * 
     * @return mixed 指定したキーの値が存在する場合はその値。それ以外は引数 $default の値。
     */
    public static function getValue($list, $key, $default = null)
    {
        return static::isExistKey($list, $key) ? $list[$key] : $default;
    }

    /**
     * 入力配列から指定したキーに該当する値を検索してその値を取得します。
     * 
     * @param Array $list                  キーを検索する配列
     * @param String|Integer $key          検索するキー、またはそれらから成る配列。<br>
     * 連想配列にアクセスする場合は、配列を使用する。<br>
     * (例) $list = [ 1 => [ 2 => 'name' => 'abc' ] ] の場合は、findValue($list, '1 => 2 => name');
     * @param mixed $default [初期値=null] 見つからなかった場合に使用する値
     * 
     * @return mixed 検索したキーの値が存在する場合はその値。それ以外は引数 $default の値。
     */
    public static function findValue(array $list, $key, $default = null)
    {
        $temp   = $list;
        $result = $default;
        $keys   = explode('=>', $key);
        
        foreach ($keys as $key_value) {
            $temp_key = trim(mb_convert_kana($key_value, 's', Charset::UTF8));
            
            if (static::isExistKey($temp, $temp_key) === false) {
                $result = $default;
                
                break;
            }
            
            $result = static::getValue($temp, $temp_key, $default);
            $temp   = $result;
        }
        
        return $result;
    }
    
    /**
     * 入力配列の全ての要素に対してユーザー関数を適用します。
     * 
     * @param Array $list                          ユーザー関数を適用する入力配列
     * @param Callable(value, key, result) $walker 配列の要素に適用するユーザー関数。
     * 第一引数は入力配列の値、第二引数はキー、第三引数は前回のユーザー関数の戻り値
     * @param Boolean $force_result [初期値=null]  指定値を戻り値として強制的に返すかどうか
     * 
     * @return Boolean ユーザー関数を適用した戻り値。引数 $force_result に値を指定している場合はその値。
     */
    public static function eachWalk(array &$list, callable $walker, $force_result = null)
    {
        $result = false;
        
        foreach ($list as $key => $value) {
            $result = $walker($value, $key, $result);
        }
        
        return is_bool($force_result) ? $force_result : $result;
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
     * 条件を満たす場合に入力配列に対して条件に一致する複数の項目を追加します。
     * 
     * @param Boolean $loop_conditions 再帰追加処理実行を満たすための条件
     * @param Array $list              項目を追加する配列
     * @param Callable $add_conditions 項目追加を満たすための条件
     * @param Array $add_list          追加する項目の配列
     * 
     * @return Boolean 入力配列へ一つ以上新しい項目を追加できた場合は true。それ以外の場合は false。
     */
    public static function addEach($loop_conditions, array &$list, callable $add_conditions, $add_list)
    {
        if ($loop_conditions !== true || static::isValid($add_list) === false) {
            return false;
        }
        
        return static::eachWalk(
            $add_list,
            function ($value, $key, $result) use (&$list, $add_conditions) {
                return (bool)($result | static::addWhen($add_conditions($value, $key), $list, $value));
            }
        );
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
     * @param Array $list                 項目を削除する配列
     * @param Callable $remove_conditions 項目削除の実行を満たすための条件
     * 
     * @return Boolean 入力配列から条件を満たす項目を一つでも削除できた場合は true。それ以外の場合は false。
     */
    public static function removeEach($loop_conditions, array &$list, callable $remove_conditions)
    {
        if ($loop_conditions !== true) {
            return false;
        }
        
        return static::eachWalk(
            $list,
            function ($value, $key, $result) use (&$list, $remove_conditions) {
                return (bool)($result | static::removeWhen($remove_conditions($value, $key), $list, $key));
            }
        );
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
        
        return static::eachWalk(
            $marge_list,
            function ($value, $key, $result) use (&$target) {
                return (bool)($result | static::partialMerge($target, $key, $value));
            }
        );
    }
    
    /**
     * 入力配列の特定のキーが持つ既存の値に指定した値を統合します。
     * 
     * @param Array $list         統合先となる入力配列
     * @param Integer|String $key 統合対象となるキー
     * @param mixed $value        統合する値
     * 
     * @return Boolean 統合に成功した場合は true。それ以外の場合は false。
     */
    public static function partialMerge(array &$list, $key, $value)
    {
        if (static::isMergeable($list, $key, $value)) {
            return static::mergeWhen(true, $list[$key], $value);
        } else {
            return static::addWhen(true, $list, $value, $key);
        }
    }

    /**
     * 入力配列の全ての要素にコールバックを使用してフィルタリングを行います。
     * 
     * @param Array $list                        フィルタリングの対象となる入力配列
     * @param Callable $filter [初期値=null]     実行するフィルタリングとなるコールバック
     * @param Boolean $is_reindex [初期値=false] フィルタリング後にインデックスを振り直すかどうか
     *
     * @return Array フィルタリングを行った状態の入力配列
     */
    public static function filter(array $list, callable $filter = null, $is_reindex = false)
    {
        $result = is_null($filter) ? array_filter($list) : array_filter($list, $filter);
        
        return ($is_reindex === true) ? array_values($result) : $result;
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
    
    /**
     * 入力配列の対象のキーの値に指定した値が統合可能かどうかを判定します。
     * 
     * @param Array $list         統合先となる入力配列
     * @param Integer|String $key 統合対象となるキー
     * @param mixed $value        統合する値
     * 
     * @return Boolean 対象のキーへ統合が可能な場合は true。それ以外の場合は false。
     */
    private static function isMergeable(array $list, $key, $value)
    {
        return (isset($list[$key]) && is_array($list[$key]) && is_array($value));
    }
}
