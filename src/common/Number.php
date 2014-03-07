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
     * このメソッドは、Type\Int\Integerクラスと異なり、数値文字列は文字列として扱います。
     * 
     * @param mixed $values 一つ以上の調べる入力値
     * 
     * @return Boolean 全ての入力値が符号あり整数型である場合は true。それ以外の場合は false。
     */
    public static function isValidInt($values)
    {
        return General::checkValueValid(func_get_args(), function ($value) {
            return is_int($value);
        });
    }
    
    /**
     * 一つ以上の入力値が全て符号なし整数型の値であるかどうかを調べます。
     * このメソッドは、Type\Int\UnsignedIntクラスと異なり、数値文字列は文字列として扱います。
     * 
     * @param mixed $values 一つ以上の調べる入力値
     * 
     * @return Boolean 全ての入力値が符号なし整数型である場合は true。それ以外の場合は false。
     */
    public static function isValidUInt($values)
    {
        return General::checkValueValid(func_get_args(), function ($value) {
            return (is_int($value) && $value >= 0);
        });
    }
}
