<?php
namespace Phpingguo\ApricotLib\Tests\Src\Type\String;

use Phpingguo\ApricotLib\Type\String\Text;

class TypeTextTest extends \PHPUnit_Framework_TestCase
{
    public function provider()
    {
        return [
            [ 1, false, false, null, 'InvalidArgumentException' ],
            [ 100, false, false, null, 'InvalidArgumentException' ],
            [ PHP_INT_MAX - 1, false, false, null, 'InvalidArgumentException' ],
            [ PHP_INT_MAX, false, false, null, 'InvalidArgumentException' ],
            [ PHP_INT_MAX + 1, false, false, null, 'InvalidArgumentException' ],
            [ PHP_INT_MAX + 10, false, false, null, 'InvalidArgumentException' ],
            [ -1, false, false, null, 'InvalidArgumentException' ],
            [ -100, false, false, null, 'InvalidArgumentException' ],
            [ ~PHP_INT_MAX + 1, false, false, null, 'InvalidArgumentException' ],
            [ ~PHP_INT_MAX, false, false, null, 'InvalidArgumentException' ],
            [ ~PHP_INT_MAX - 1, false, false, null, 'InvalidArgumentException' ],
            [ ~PHP_INT_MAX - 10, false, false, null, 'InvalidArgumentException' ],
            [ 0.1, false, false, null, 'InvalidArgumentException' ],
            [ 0.9, false, false, null, 'InvalidArgumentException' ],
            [ 1.0, false, false, null, 'InvalidArgumentException' ],
            [ 1.1, false, false, null, 'InvalidArgumentException' ],
            [ -0.9, false, false, null, 'InvalidArgumentException' ],
            [ -1.0, false, false, null, 'InvalidArgumentException' ],
            [ -1.1, false, false, null, 'InvalidArgumentException' ],
            [ '1', true, true, null, null ],
            [ '100', true, true, null, null ],
            [ '-1', true, true, null, null ],
            [ '-100', true, true, null, null ],
            [ 'a', true, true, null, null ],
            [ 'Z', true, true, null, null ],
            [ 'ABC', true, true, null, null ],
            [ 'XYZ', true, true, null, null ],
            [ 'ひらがな', true,true,  null, null ],
            [ '漢字', true, true, null, null ],
            [ 'ｶﾀｶﾅ', true, true, null, null ],
            [ 'カタカナ', true, true, null, null ],
            [ '%88%c0%95%94%20%8d%d8%81X%0d%0a', true, true, null, null ],
            [ '%bd%ef%ca%fd%20%c3%d2%b3%a8%ce%a4', true, true, null, 'InvalidArgumentException' ],
            [ 0, false, false, null, 'InvalidArgumentException' ],
            [ 0.0, false, false, null, 'InvalidArgumentException' ],
            [ '0', true, true, null, null ],
            [ '0.0', true, true, null, null ],
            [ null, false, false, null, 'InvalidArgumentException' ],
            [ '', true, false, null, null ],
            [ false, false, false, null, 'InvalidArgumentException' ],
            [ [], false, false, null, 'InvalidArgumentException' ],
        ];
    }
    
    /**
     * @dataProvider provider
     */
    public function test($value, $result, $strict_result, $expected, $exception)
    {
        isset($exception) && $this->setExpectedException($exception);
        
        $this->assertSame($result, (new Text())->isValue($value));
        $this->assertSame($result, Text::getInstance()->isValue($value));
        $this->assertSame($strict_result, (new Text())->isValid($value));
        $this->assertSame($strict_result, Text::getInstance()->isValid($value));
        $this->assertSame($expected ?: (is_array($value) ? '' : (new Text())->getValue($value)), (new Text())->getValue($value));
    }
    
    public function testDefaultValue()
    {
        $this->assertSame('', (new Text())->getDefaultValue());
    }
}
