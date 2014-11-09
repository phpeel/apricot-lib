<?php
namespace Phpeel\ApricotLib\Enums;

use Phpeel\ApricotLib\Type\Enum\Enum;

/**
 * フレームワークで扱うことのできるスカラー変数型を表します。
 * 
 * @final [列挙型属性]
 * @author hiroki sugawara
 */
final class Variable extends Enum
{
    /** 符号あり整数型であることを示す */
    const INTEGER        = 'Phpeel\ApricotLib\Type\Int\Integer';
    
    /** 符号なし整数型であることを示す */
    const UNSIGNED_INT   = 'Phpeel\ApricotLib\Type\Int\UnsignedInt';
    
    /** 符号あり浮動小数点数型であることを示す */
    const FLOAT          = 'Phpeel\ApricotLib\Type\Float\Float';
    
    /** 符号なし浮動小数点数型であることを示す */
    const UNSIGNED_FLOAT = 'Phpeel\ApricotLib\Type\Float\UnsignedFloat';
    
    /** 制限の厳しい文字列型であることを示す */
    const STRING         = 'Phpeel\ApricotLib\Type\String\String';
    
    /** 制限の緩い文字列型であることを示す */
    const TEXT           = 'Phpeel\ApricotLib\Type\String\Text';
}
