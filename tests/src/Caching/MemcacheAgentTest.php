<?php
namespace Phpingguo\ApricotLib\Tests\Caching;

use Phpingguo\ApricotLib\Caching\MemcacheAgent;

class MemcacheAgentTest extends \PHPUnit_Framework_TestCase
{
    public function providerCache()
    {
        return [
            [ 'test_group', 'test_key', 'test_value', null ],
            [ 'test_group', null, 'test_value', 'InvalidArgumentException' ],
            [ null, 'test_key', 'test_value', 'InvalidArgumentException' ],
        ];
    }
    
    /**
     * @dataProvider providerCache
     */
    public function testNormal($group, $key, $value, $exception)
    {
        isset($exception) && $this->setExpectedException($exception);
        
        MemcacheAgent::getInstance()->setClustering();
        $this->assertSame(null, MemcacheAgent::getInstance()->get($group, $key, null));
        $this->assertTrue(MemcacheAgent::getInstance()->set($group, $key, $value));
        $this->assertSame($value, MemcacheAgent::getInstance()->get($group, $key, null));
        $this->assertTrue(MemcacheAgent::getInstance()->delete($group, $key));
        $this->assertSame(null, MemcacheAgent::getInstance()->get($group, $key, null));
    }
    
    /**
     * @dataProvider providerCache
     */
    public function testRemoveGroup($group, $key, $value, $exception)
    {
        isset($exception) && $this->setExpectedException($exception);
        
        MemcacheAgent::getInstance()->setClustering();
        $this->assertSame(null, MemcacheAgent::getInstance()->get($group, $key, null));
        $this->assertTrue(MemcacheAgent::getInstance()->set($group, $key, $value));
        $this->assertSame($value, MemcacheAgent::getInstance()->get($group, $key, null));
        MemcacheAgent::getInstance()->removeGroup($group);
        $this->assertSame(null, MemcacheAgent::getInstance()->get($group, $key, null));
    }
    
    /**
     * @dataProvider providerCache
     */
    public function testClear($group, $key, $value, $exception)
    {
        isset($exception) && $this->setExpectedException($exception);
        
        MemcacheAgent::getInstance()->setClustering();
        $this->assertSame(null, MemcacheAgent::getInstance()->get($group, $key, null));
        $this->assertTrue(MemcacheAgent::getInstance()->set($group, $key, $value));
        $this->assertSame($value, MemcacheAgent::getInstance()->get($group, $key, null));
        MemcacheAgent::getInstance()->clear();
        $this->assertSame(null, MemcacheAgent::getInstance()->get($group, $key, null));
    }
    
    public function providerMultiGet()
    {
        return [
            [
                [ [ 't_g', 't1_k', 't1_v' ], [ 't_g', 't2_k', 't2_v' ] ],
                't_g',
                [ 't1_k', 't2_k' ],
                [ 't1_v', 't2_v' ]
            ],
        ];
    }

    /**
     * @dataProvider providerMultiGet
     */
    public function testMultiGet($add_list, $group_name, $get_list, $result_list)
    {
        MemcacheAgent::getInstance()->setClustering();
        
        foreach ($add_list as $add_item) {
            list($group, $key, $value) = $add_item;
            $this->assertTrue(MemcacheAgent::getInstance()->set($group, $key, $value));
        }
        
        $list = MemcacheAgent::getInstance()->get($group_name, $get_list);
        
        foreach ($result_list as $item) {
            $this->assertContains($item, $list);
        }
    }

    public function providerGetClustering()
    {
        return [
            [ 'tcp', [ 'tcp://localhost:11211?persistent=1&weight=1' ] ],
            [ null, [ 'localhost:11211?persistent=1&weight=1' ] ],
        ];
    }

    /**
     * @dataProvider providerGetClustering
     */
    public function testGetClustering($protocol, $expected)
    {
        $this->assertSame($expected, MemcacheAgent::getInstance()->getClustering($protocol));
    }
}
