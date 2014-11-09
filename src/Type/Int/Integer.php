<?php
namespace Phpeel\ApricotLib\Type\Int;

/**
 * フレームワークで使用できる整数型を表すクラスです。
 * 
 * @final [継承禁止クラス]
 * @author hiroki sugawara
 */
final class Integer extends BaseInteger
{
    // ---------------------------------------------------------------------------------------------
    // constructor / destructor
    // ---------------------------------------------------------------------------------------------
    /**
     * Integer クラスの新しいインスタンスを初期化します。
     * 
     * @param Integer $value [初期値=null] インスタンスが保持する整数型の値
     */
    public function __construct($value = null)
    {
        parent::__construct($value);
    }
}
