<?php
namespace Phpingguo\ApricotLib\Common;

/**
 * フレームワークで使用する共通の汎用処理を纏めたクラスです。
 * 
 * @final [継承禁止クラス]
 * @author hiroki sugawara
 */
final class General
{
    /**
     * 入力値を解析し、クロージャなら実行後の値を、それ以外はそのままの値を取得します。
     * 
     * @param mixed $value 解析対象となる変数
     * 
     * @return mixed 入力値の解析後の値
     */
    public static function getParsedValue($value)
    {
        return ($value instanceof \Closure) ? $value() : $value;
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
        $values = func_get_args();
        
        foreach ($values as $value) {
            if (false === (is_int($value) && $value >= 0)) {
                return false;
            }
        }
        
        return (func_num_args() > 0);
    }
}
