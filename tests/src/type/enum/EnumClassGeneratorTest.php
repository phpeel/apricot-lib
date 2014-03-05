<?php
namespace Phpingguo\ApricotLib\Tests\Type\Enum;

use Phpingguo\ApricotLib\Enums\Variable;
use Phpingguo\ApricotLib\Type\Enum\EnumClassGenerator;
use Phpingguo\ApricotLib\Enums\LibEnumName;

class EnumClassGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function provider()
    {
        return [
            [ LibEnumName::VARIABLE, Variable::INTEGER ],
            [ LibEnumName::VARIABLE, Variable::FLOAT ],
            [ LibEnumName::VARIABLE, Variable::STRING ],
        ];
    }
    
    /**
     * @dataProvider provider
     */
    public function test($enum_name, $enum_value)
    {
        $enum_data	= EnumClassGenerator::done($enum_name, $enum_value);
        
        $this->assertInstanceOf($enum_name, $enum_data[0]);
        $this->assertInstanceOf($enum_value, $enum_data[1]);
        $this->assertSame($enum_value, $enum_data[2]);
    }
}
