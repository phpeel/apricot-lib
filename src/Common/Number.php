<?php
namespace Phpingguo\ApricotLib\Common;

/**
 * 数字に関する操作を拡張するためのクラスです。
 * 
 * @final [継承禁止クラス]
 * @author hiroki sugawara
 */
final class Number
{
    /**
     * 一つ以上の入力値が全て符号あり整数型の値であるかどうかを調べます。
     * このメソッドは、整数値の文字列を文字列とみなし false を返します。
     * 
     * @param mixed $values 一つ以上の調べる入力値
     * 
     * @return Boolean 全ての入力値が符号あり整数型である場合は true。それ以外の場合は false。
     */
    public static function isValidInt($values)
    {
        return General::checkValueValid(
            func_get_args(),
            function ($value) {
                return is_int($value);
            }
        );
    }
    
    /**
     * 一つ以上の入力値が全て符号なし整数型の値であるかどうかを調べます。
     * このメソッドは、整数値の文字列を文字列とみなし false を返します。
     * 
     * @param mixed $values 一つ以上の調べる入力値
     * 
     * @return Boolean 全ての入力値が符号なし整数型である場合は true。それ以外の場合は false。
     */
    public static function isValidUInt($values)
    {
        return General::checkValueValid(
            func_get_args(),
            function ($value) {
                return (is_int($value) && $value >= 0);
            }
        );
    }

    /**
     * 一つ以上の入力値が全て符号あり小数点数型の値であるかどうかを調べます。
     * このメソッドは、小数点数の文字列を文字列とみなし false を返します。
     * 
     * @param mixed $values 一つ以上の調べる入力値
     * 
     * @return Boolean 全ての入力値が符号あり小数点数型である場合は true。それ以外の場合は false。
     */
    public static function isValidFloat($values)
    {
        return General::checkValueValid(
            func_get_args(),
            function ($value) {
                return is_float($value);
            }
        );
    }

    /**
     * 一つ以上の入力値が全て符号なし小数点数型の値であるかどうかを調べます。
     * このメソッドは、小数点数の文字列を文字列とみなし false を返します。
     * 
     * @param mixed $values 一つ以上の調べる入力値
     * 
     * @return Boolean 全ての入力値が符号なし小数点数型である場合は true。それ以外の場合は false。
     */
    public static function isValidUFloat($values)
    {
        return General::checkValueValid(
            func_get_args(),
            function ($value) {
                return (is_float($value) && $value >= 0);
            }
        );
    }

    /**
     * 一つ以上の入力値が符号ありの実数値であるかどうかを調べます。
     * 
     * @param mixed $values 一つ以上の調べる入力値
     *
     * @return Boolean 全ての入力値が符号ありの実数値である場合は true。それ以外の場合は false。
     */
    public static function isValidNumber($values)
    {
        return General::checkValueValid(
            func_get_args(),
            function ($value) {
                return (is_int($value) || is_float($value));
            }
        );
    }

    /**
     * 入力値が実数かつ指定した区間に含まれるかどうかを調べます。
     *
     * @param mixed $value                            調べる入力値
     * @param Integer $min                            区間の最小値となる端点
     * @param Integer $max                            区間の最大値となる端点
     * @param Boolean $open_end_points [初期値=false] 区間が開区間(端点を区間に含めない)かどうか
     * 
     * @return Boolean 整数かつ区間に含まれる場合は true。それ以外の場合は false。
     */
    public static function isInInterval($value, $min, $max, $open_end_points = false)
    {
        if (static::isValidNumber($value, $min, $max)) {
            if ($open_end_points === true) {
                return ($min < $value && $value < $max);
            }
            
            return ($min <= $value && $value <= $max);
        }
        
        return false;
    }
}
