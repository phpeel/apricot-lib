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
     * 入力値が符号なし整数型の値であるかどうかを調べます。
     * 
     * @param mixed $value 調べる入力値
     * 
     * @return Boolean 符号なし整数型である場合は true を、それ以外の場合は false。
     */
    public static function isValidUInt($value)
    {
        return (is_int($value) && $value >= 0);
    }
}
