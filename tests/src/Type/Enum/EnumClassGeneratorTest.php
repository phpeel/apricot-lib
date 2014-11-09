<?php
namespace Phpeel\ApricotLib\Tests\Type\Enum;

use Phpeel\ApricotLib\Enums\Variable;
use Phpeel\ApricotLib\LibSupervisor;
use Phpeel\ApricotLib\Type\Enum\EnumClassGenerator;

class EnumClassGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function provider()
    {
        return [
            [ LibSupervisor::ENUM_VARIABLE, Variable::INTEGER ],
            [ LibSupervisor::ENUM_VARIABLE, Variable::FLOAT ],
            [ LibSupervisor::ENUM_VARIABLE, Variable::STRING ],
        ];
    }
    
    /**
     * @dataProvider provider
     */
    public function test($enum_name, $enum_value)
    {
        $full_name = LibSupervisor::getEnumFullName($enum_name);
        $enum_data = EnumClassGenerator::done($full_name, $enum_value);
        
        $this->assertInstanceOf($full_name, $enum_data[0]);
        $this->assertInstanceOf($enum_value, $enum_data[1]);
        $this->assertSame($enum_value, $enum_data[2]);
    }
}
