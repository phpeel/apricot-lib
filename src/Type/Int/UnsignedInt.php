<?php
namespace Phpeel\ApricotLib\Type\Int;

/**
 * フレームワークで使用できる符号なし整数型を表すクラスです。
 * 
 * @final [継承禁止クラス]
 * @author hiroki sugawara
 */
final class UnsignedInt extends BaseInteger
{
    // ---------------------------------------------------------------------------------------------
    // constructor / destructor
    // ---------------------------------------------------------------------------------------------
    /**
     * UnsignedInt クラスの新しいインスタンスを初期化します。
     * 
     * @param UnsignedInt $value [初期値=null] インスタンスが保持する符号なし整数型の値
     */
    public function __construct($value = null)
    {
        parent::__construct($value, true);
    }
}
