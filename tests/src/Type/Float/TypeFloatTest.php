<?php
namespace Phpeel\ApricotLib\Tests\Type\Float;

use Phpeel\ApricotLib\Type\Float\Float;

class TypeFloatTest extends \PHPUnit_Framework_TestCase
{
    public function provider()
    {
        return [
            [ 1, true, null, null ],
            [ 100, true, null, null ],
            [ PHP_INT_MAX - 1, true, null, null ],
            [ PHP_INT_MAX, true, null, null ],
            [ PHP_INT_MAX + 1, true, null, null ],
            [ PHP_INT_MAX + 10, true, null, null ],
            [ -1, true, null, null ],
            [ -100, true, null, null ],
            [ ~PHP_INT_MAX + 1, true, null, null ],
            [ ~PHP_INT_MAX, true, null, null ],
            [ ~PHP_INT_MAX - 1, true, null, null ],
            [ ~PHP_INT_MAX - 10, true, null, null ],
            [ 0.1, true, null, null ],
            [ 0.9, true, null, null ],
            [ 1.0, true, null, null ],
            [ 1.1, true, null, null ],
            [ -0.9, true, null, null ],
            [ -1.0, true, null, null ],
            [ -1.1, true, null, null ],
            [ '1', true, 1.0, null ],
            [ '100', true, 100.0, null ],
            [ '-1', true, -1.0, null ],
            [ '-100', true, -100.0, null ],
            [ 'a', false, null, 'InvalidArgumentException' ],
            [ 'Z', false, null, 'InvalidArgumentException' ],
            [ 'ABC', false, null, 'InvalidArgumentException' ],
            [ 'XYZ', false, null, 'InvalidArgumentException' ],
            [ 'ひらがな', false, null, 'InvalidArgumentException' ],
            [ '漢字', false, null, 'InvalidArgumentException' ],
            [ 'ｶﾀｶﾅ', false, null, 'InvalidArgumentException' ],
            [ 'カタカナ', false, null, 'InvalidArgumentException' ],
            [ 0, true, 0.0, null ],
            [ 0.0, true, null, null ],
            [ '0', true, 0.0, null ],
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
        
        $this->assertSame($result, (new Float())->isValue($value));
        $this->assertSame($result, Float::getInstance()->isValue($value));
        $this->assertSame($expected ?: floatval($value), (new Float())->getValue($value));
        $this->assertSame($expected ?: floatval($value), (new Float($value))->getValue());
    }
    
    public function testDefaultValue()
    {
        $this->assertSame(0.0, (new Float())->getDefaultValue());
    }
}
