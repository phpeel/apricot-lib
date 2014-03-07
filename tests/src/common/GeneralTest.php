<?php
namespace Phpingguo\ApricotLib\Tests\Common;

use Phpingguo\ApricotLib\Common\General;
use Phpingguo\ApricotLib\LibrarySupervisor;

class GeneralTest extends \PHPUnit_Framework_TestCase
{
    public function providerParsedYamlFileFailed()
    {
        return [
            [ LibrarySupervisor::getConfigPath(), 'default_values', null ],
            [ LibrarySupervisor::getConfigPath(), 'library_preset_services', null ],
            [ null, 'failed_exception', 'InvalidArgumentException' ],
            [ LibrarySupervisor::getConfigPath(), null, 'InvalidArgumentException' ],
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
