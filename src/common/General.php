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
     * 入力変数の値が全て指定した条件を満たしているかどうかをチェックします。
     *
     * @param Array $values        入力変数の値の配列
     * @param callable $conditions 入力変数の配列の要素の満たすべき条件
     *
     * @return Boolean 全ての入力値が指定した条件を満たしている場合は true。それ以外の場合は false。
     */
    public static function checkValueValid(array $values, callable $conditions)
    {
        foreach ($values as $value) {
            if (false === $conditions($value)) {
                return false;
            }
        }
        
        return (empty($values) === false);
    }
}
