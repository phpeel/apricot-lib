<?php
namespace Phpingguo\ApricotLib\Type;

/**
 * スカラー値を取り扱うクラスの拡張処理を定義したインターフェイスです。
 * 
 * @author hiroki sugawara
 */
interface IExtendScalarValue extends IScalarValue
{
    /**
     * 指定した値がスカラータイプクラスの値として有効な値かどうかを判別します。
     * 
     * @param mixed $check_value スカラータイプクラスの値であるかどうかを判定する変数
     * 
     * @return Boolean 指定した値がスカラータイプクラスの値として有効の場合は true。それ以外の場合は false。
     */
    public function isValid(&$check_value);
}
