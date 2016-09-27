<?php

namespace PhpDefInfo;

final class GetVarInfoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderFor_test
     */
    public function test($expected, $input)
    {
        $this->assertEquals($expected, getVarInfoByDocInfo($input));
    }

    public function dataProviderFor_test()
    {
        return array(
            array(
                'expected' => array(
                    'type' => '',
                    'name' => '',
                    'desc' => '',
                ),
                'input' => array(
                    'tags' => array(
                        array('name' => 'var', 'content' => '')
                    )
                ),
            ),
            array(
                'expected' => array(
                    'type' => 'string[]',
                    'name' => '',
                    'desc' => '',
                ),
                'input' => array(
                    'tags' => array(
                        array('name' => 'var', 'content' => 'string[]')
                    )
                ),
            ),
            array(
                'expected' => array(
                    'type' => 'string[]|false',
                    'name' => '',
                    'desc' => '',
                ),
                'input' => array(
                    'tags' => array(
                        array('name' => 'var', 'content' => 'string[]|false')
                    )
                ),
            ),
            array(
                'expected' => array(
                    'type' => 'string[]',
                    'name' => '',
                    'desc' => 'Description',
                ),
                'input' => array(
                    'tags' => array(
                        array('name' => 'var', 'content' => 'string[] Description')
                    )
                ),
            ),
            array(
                'expected' => array(
                    'type' => 'string[]',
                    'name' => '$var',
                    'desc' => 'Description',
                ),
                'input' => array(
                    'tags' => array(
                        array('name' => 'var', 'content' => 'string[] $var Description')
                    )
                ),
            ),
        );
    }
}
