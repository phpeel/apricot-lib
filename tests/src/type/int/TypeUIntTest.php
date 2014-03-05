<?php
namespace Phpingguo\ApricotLib\Tests\Type\Int;

use Phpingguo\ApricotLib\Type\Int\UnsignedInt;

class TypeUIntTest extends \PHPUnit_Framework_TestCase
{
    public function provider()
    {
        return [
            [ 1, true, null, null ],
            [ 100, true, null, null ],
            [ PHP_INT_MAX - 1, true, null, null ],
            [ PHP_INT_MAX, true, null, null ],
            [ PHP_INT_MAX + 1, false, null, 'InvalidArgumentException' ],
            [ PHP_INT_MAX + 10, false, null,'InvalidArgumentException' ],
            [ -1, false, null, 'InvalidArgumentException' ],
            [ -100, false, null, 'InvalidArgumentException' ],
            [ ~PHP_INT_MAX + 1, false, null, 'InvalidArgumentException' ],
            [ ~PHP_INT_MAX, false, null, 'InvalidArgumentException' ],
            [ ~PHP_INT_MAX - 1, false, null, 'InvalidArgumentException' ],
            [ ~PHP_INT_MAX - 10, false, null, 'InvalidArgumentException' ],
            [ 0.1, false, null, 'InvalidArgumentException' ],
            [ 0.9, false, null, 'InvalidArgumentException' ],
            [ 1.0, true, 1, null ],
            [ 1.1, false, null, 'InvalidArgumentException' ],
            [ -0.9, false, null, 'InvalidArgumentException' ],
            [ -1.0, false, null, 'InvalidArgumentException' ],
            [ -1.1, false, null, 'InvalidArgumentException' ],
            [ '1', true, null, null ],
            [ '100', true, null, null ],
            [ '-1', false, null, 'InvalidArgumentException' ],
            [ '-100', false, null, 'InvalidArgumentException' ],
            [ 'a', false, null, 'InvalidArgumentException' ],
            [ 'Z', false, null, 'InvalidArgumentException' ],
            [ 'ABC', false, null, 'InvalidArgumentException' ],
            [ 'XYZ', false, null, 'InvalidArgumentException' ],
            [ 'ひらがな', false, null, 'InvalidArgumentException' ],
            [ '漢字', false, null, 'InvalidArgumentException' ],
            [ 'ｶﾀｶﾅ', false, null, 'InvalidArgumentException' ],
            [ 'カタカナ', false, null, 'InvalidArgumentException' ],
            [ 0, true, null, null ],
            [ 0.0, true, null, null ],
            [ '0', true, null, null ],
            [ '0.0', true, null, null ],
            [ null, false, null, 'InvalidArgumentException' ],
            [ '', false, null, 'InvalidArgumentException' ],
            [ false, false, null, 'InvalidArgumentException' ],
            [ [], false, null, 'InvalidArgumentException' ],
        ];
    }
    
    /**
     * @dataProvider provider
     */
    public function test($value, $result, $expected, $exception)
    {
        isset($exception) && $this->setExpectedException($exception);
        
        $this->assertSame($result, (new UnsignedInt())->isValue($value));
        $this->assertSame($result, UnsignedInt::getInstance()->isValue($value));
        $this->assertSame($expected ?: intval($value), (new UnsignedInt())->getValue($value));
        $this->assertSame($expected ?: intval($value), (new UnsignedInt($value))->getValue());
    }
    
    public function testDefaultValue()
    {
        $this->assertSame(0, (new UnsignedInt())->getDefaultValue());
    }
}
