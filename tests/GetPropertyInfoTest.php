<?php

namespace PhpDefInfo;

final class GetPropertyInfoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderFor_test
     */
    public function test($expected, \ReflectionProperty $source)
    {
        $this->assertEquals($expected, getPropertyInfo($source));
    }

    public function dataProviderFor_test()
    {
        $sample = new \ReflectionClass('PhpDefInfo\sample\SampleKlass');

        return array(
            array(
                'expected' => array(
                    'desc' => 'ID real description',
                    'type' => 'int',
                ),
                'source' => $sample->getProperty('id'),
            ),
        );
    }
}
