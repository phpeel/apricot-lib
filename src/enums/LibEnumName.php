<?php
namespace Phpingguo\ApricotLib\Enums;

use Phpingguo\ApricotLib\Type\Enum\Enum;

/**
 * ライブラリ内で定義されている列挙型クラスのフルネーム（名前空間＋クラス名）を表す列挙型です。
 * 
 * @final [列挙型属性]
 * @author hiroki sugawara
 */
final class LibEnumName extends Enum
{
    /** 列挙型「Charset」であることを示す */
    const CHARSET     = 'Phpingguo\ApricotLib\Enums\Charset';
    
    /** 列挙型「HttpMethod」であることを示す */
    const HTTP_METHOD = 'Phpingguo\ApricotLib\Enums\HttpMethod';
    
    /** 列挙型「Variable」であることを示す */
    const VARIABLE    = 'Phpingguo\ApricotLib\Enums\Variable';
}
