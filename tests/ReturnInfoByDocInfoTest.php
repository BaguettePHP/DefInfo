<?php

namespace PhpDefInfo;

final class ReturnInfoByDocInfoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderFor_test
     */
    public function test($expected, $source)
    {
        $this->assertEquals($expected, getReturnInfoByDocInfo(['tags' => $source]));
    }

    public function dataProviderFor_test()
    {
        return array(
            array(
                'expected' => array(
                    'type' => '',
                    'desc' => '',
                ),
                'source' => array(),
            ),
            array(
                'expected' => array(
                    'desc' => '',
                    'type' => 'string',
                ),
                'source' => array(
                    array(
                        'name' => 'return',
                        'content' => 'string',
                    )
                ),
            ),
            array(
                'expected' => array(
                    'desc' => 'Hoge Fuga',
                    'type' => 'string',
                ),
                'source' => array(
                    array(
                        'name' => 'return',
                        'content' => 'string Hoge Fuga',
                    )
                ),
            ),
            array(
                'expected' => array(
                    'desc' => 'Hoge Fuga',
                    'type' => 'string',
                ),
                'source' => array(
                    array(
                        'name' => 'return',
                        'content' => 'string  Hoge Fuga  ',
                    )
                ),
            ),
        );
    }
}
