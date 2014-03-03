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
}
