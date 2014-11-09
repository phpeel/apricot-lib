<?php
namespace Phpeel\ApricotLib\Type\String;

/**
 * フレームワークで使用できる制限の緩い文字列型を表すクラスです。
 * 
 * @final [継承禁止クラス]
 * @author hiroki sugawara
 */
final class Text extends BaseString
{
    /**
     * @see BaseString::isValue()
     */
    public function isValue(&$check_value)
    {
        return (is_null($check_value) === false && is_string($check_value));
    }
}
