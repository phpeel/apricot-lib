<?php
namespace Phpingguo\ApricotLib\Tests\Common;

use Phpingguo\ApricotLib\Common\General;
use Phpingguo\ApricotLib\LibSupervisor;

class GeneralTest extends \PHPUnit_Framework_TestCase
{
    public function providerParsedYamlFileFailed()
    {
        return [
            [ LibSupervisor::getConfigPath(), 'default_values', null ],
            [ LibSupervisor::getConfigPath(), 'library_preset_services', null ],
            [ null, 'failed_exception', 'InvalidArgumentException' ],
            [ LibSupervisor::getConfigPath(), null, 'InvalidArgumentException' ],
            [ false, true, 'InvalidArgumentException' ],
            [ 0, 1, 'InvalidArgumentException' ],
            [ 0.0, 0.1, 'InvalidArgumentException' ],
            [ '', [], 'InvalidArgumentException' ],
            [ new \stdClass(), '0.0', 'InvalidArgumentException' ],
        ];
    }

    /**
     * @dataProvider providerParsedYamlFileFailed
     */
    public function testParsedYamlFileFailed($dir_path, $yaml_name, $exception)
    {
        isset($exception) && $this->setExpectedException($exception);
        
        $this->assertNotEmpty(General::getParsedYamlFile($dir_path, $yaml_name));
    }
}
