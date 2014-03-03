<?php
namespace Phpingguo\ApricotLib\Enums;

use Phpingguo\ApricotLib\Type\Enum\Enum;

/**
 * フレームワークで扱うことのできるスカラー変数型を表します。
 * 
 * @final [列挙型属性]
 * @author hiroki sugawara
 */
final class Variable extends Enum
{
    /** 符号あり整数型であることを示す */
    const INTEGER        = 'Phpingguo\ApricotLib\Type\Int\Integer';
    
    /** 符号なし整数型であることを示す */
    const UNSIGNED_INT   = 'Phpingguo\ApricotLib\Type\Int\UnsignedInt';
    
    /** 符号あり浮動小数点数型であることを示す */
    const FLOAT          = 'Phpingguo\ApricotLib\Type\Float\Float';
    
    /** 符号なし浮動小数点数型であることを示す */
    const UNSIGNED_FLOAT = 'Phpingguo\ApricotLib\Type\Float\UnsignedFloat';
    
    /** 制限の厳しい文字列型であることを示す */
    const STRING         = 'Phpingguo\ApricotLib\Type\String\String';
    
    /** 制限の緩い文字列型であることを示す */
    const TEXT           = 'Phpingguo\ApricotLib\Type\String\Text';
}
