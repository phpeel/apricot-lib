<?php
// -----------------------------------------------------------------------------
// Read Me !!
// 
// To execute this test, it is necessary that a value of apc.enable_cli is "1".
// このテストを実行するためには apc.enable_cli の値が 1 である必要があります。
//
// When testing this class at PHP version 5.5 or after, requires to install APCu instead of APC.
// PHP 5.5 以降でテストを実施する場合、APCの替わりにAPCuをインストールする必要があります。
// -----------------------------------------------------------------------------
namespace Phpeel\ApricotLib\Tests\Caching;

use Phpeel\ApricotLib\Caching\ApcAgent;

class ApcAgentTest extends \PHPUnit_Framework_TestCase
{
    public function providerDefaultExpireTime()
    {
        return [
            [ 60, 60 ],
            [ 300, 300 ],
            [ null, 300 ],
            [ [], 300 ],
            [ true, 300 ],
            [ false, 300 ],
            [ '', 300 ],
            [ '0', 300 ],
            [ '0.0', 300 ],
            [ 0.1, 300 ],
            [ new \stdClass(), 300 ],
        ];
    }

    /**
     * @dataProvider providerDefaultExpireTime
     */
    public function testDefaultExpireTime($expire, $expected)
    {
        ApcAgent::getInstance()->setDefaultExpireTime($expire);
        
        $this->assertSame($expected, ApcAgent::getInstance()->getDefaultExpireTime());
    }
    
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
        
        $this->assertFalse(ApcAgent::getInstance()->isExist($group, $key));
        $this->assertTrue(ApcAgent::getInstance()->set($group, $key, $value));
        $this->assertTrue(ApcAgent::getInstance()->isExist($group, $key));
        $this->assertSame($value, ApcAgent::getInstance()->get($group, $key, null));
        $this->assertTrue(ApcAgent::getInstance()->delete($group, $key));
        $this->assertFalse(ApcAgent::getInstance()->isExist($group, $key));
    }
    
    /**
     * @dataProvider providerCache
     */
    public function testRemoveGroup($group, $key, $value, $exception)
    {
        isset($exception) && $this->setExpectedException($exception);
        
        $this->assertFalse(ApcAgent::getInstance()->isExist($group, $key));
        $this->assertTrue(ApcAgent::getInstance()->set($group, $key, $value));
        $this->assertTrue(ApcAgent::getInstance()->isExist($group, $key));
        $this->assertSame($value, ApcAgent::getInstance()->get($group, $key, null));
        ApcAgent::getInstance()->removeGroup($group);
        $this->assertFalse(ApcAgent::getInstance()->isExist($group, $key));
    }
    
    /**
     * @dataProvider providerCache
     */
    public function testClear($group, $key, $value, $exception)
    {
        isset($exception) && $this->setExpectedException($exception);
        
        $this->assertFalse(ApcAgent::getInstance()->isExist($group, $key));
        $this->assertTrue(ApcAgent::getInstance()->set($group, $key, $value));
        $this->assertTrue(ApcAgent::getInstance()->isExist($group, $key));
        $this->assertSame($value, ApcAgent::getInstance()->get($group, $key, null));
        ApcAgent::getInstance()->clear();
        $this->assertFalse(ApcAgent::getInstance()->isExist($group, $key));
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
        foreach ($add_list as $add_item) {
            list($group, $key, $value) = $add_item;
            $this->assertTrue(ApcAgent::getInstance()->set($group, $key, $value));
        }
        
        $list = ApcAgent::getInstance()->get($group_name, $get_list);
        
        foreach ($result_list as $item) {
            $this->assertContains($item, $list);
        }
    }
}
