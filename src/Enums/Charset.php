<?php
namespace Phpingguo\ApricotLib\Enums;

use Phpingguo\ApricotLib\Type\Enum\Enum;

/**
 * 文字コードセットの種類を示します。
 * 
 * @final [列挙型属性]
 * @author hiroki sugawara
 */
final class Charset extends Enum
{
    /** 文字コードが「ASCII」であることを示す */
    const ASCII    = 'ASCII';
    
    /** 文字コードが「UTF-8」であることを示す */
    const UTF8     = 'UTF-8';
    
    /** 文字コードが「Shift_JIS」であることを示す */
    const SJIS     = 'Shift_JIS';
    
    /** 文字コードが Windows の「Shift_JIS」であることを示す */
    const SJIS_WIN = 'SJIS-win';
}
