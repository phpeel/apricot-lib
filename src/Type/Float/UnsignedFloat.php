<?php
namespace Phpeel\ApricotLib\Type\Float;

/**
 * フレームワークで使用できる符号なし浮動小数点数型を表すクラスです。
 * 
 * @final [継承禁止クラス]
 * @author h-sugawara@m-craft.com
 */
final class UnsignedFloat extends BaseFloat
{
    // ---------------------------------------------------------------------------------------------
    // constructor / destructor
    // ---------------------------------------------------------------------------------------------
    /**
     * UnsignedFloat クラスの新しいインスタンスを初期化します。
     * 
     * @param UnsignedFloat $value [初期値=null] インスタンスが保持する浮動小数点数型の値
     */
    public function __construct($value = null)
    {
        parent::__construct($value, true);
    }
}
