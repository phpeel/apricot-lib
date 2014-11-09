<?php
namespace Phpeel\ApricotLib\Type\Float;

/**
 * フレームワークで使用できる浮動小数点数型を表すクラスです。
 * 
 * @final [継承禁止クラス]
 * @author hiroki sugawara
 */
final class Float extends BaseFloat
{
    // ---------------------------------------------------------------------------------------------
    // constructor / destructor
    // ---------------------------------------------------------------------------------------------
    /**
     * Float クラスの新しいインスタンスを初期化します。
     * 
     * @param Float $value [初期値=null] インスタンスが保持する浮動小数点数型の値
     */
    public function __construct($value = null)
    {
        parent::__construct($value);
    }
}
