<?php
namespace Phpingguo\ApricotLib\Tests;

use Phpingguo\ApricotLib\LibSupervisor;

class LibSupervisorTest extends \PHPUnit_Framework_TestCase
{
    public function providerGetEnumFullName()
    {
        return [
            [ LibSupervisor::ENUM_CHARSET, 'Phpingguo\ApricotLib\Enums\Charset', null ],
            [ LibSupervisor::ENUM_VARIABLE, 'Phpingguo\ApricotLib\Enums\Variable', null ],
            [ null, null, 'InvalidArgumentException' ],
            [ true, null, 'InvalidArgumentException' ],
            [ false, null, 'InvalidArgumentException' ],
            [ [], null, 'InvalidArgumentException' ],
            [ [ 'a' ], null, 'InvalidArgumentException' ],
            [ '', null, 'InvalidArgumentException' ],
            [ 0, null, 'InvalidArgumentException' ],
            [ 0.0, null, 'InvalidArgumentException' ],
            [ 0.1, null, 'InvalidArgumentException' ],
            [ '0', null, 'InvalidArgumentException' ],
            [ '0.0', null, 'InvalidArgumentException' ],
            [ '0.1', null, 'InvalidArgumentException' ],
            [ new \stdClass(), null, 'InvalidArgumentException' ],
        ];
    }

    /**
     * @dataProvider providerGetEnumFullName
     */
    public function testGetEnumFullName($enum_name, $expected, $exception)
    {
        isset($exception) && $this->setExpectedException($exception);
        
        $this->assertSame($expected, LibSupervisor::getEnumFullName($enum_name));
    }
}
