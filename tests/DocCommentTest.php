<?php

namespace PhpDefInfo;

final class DocCommentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderFor_trim
     */
    public function test_trim($expected, $source)
    {
        $this->assertEquals($expected, trimDocComment($source));
    }

    public function dataProviderFor_trim()
    {
        return array(
            array(
                'expected' => '',
                'source' => '',
            ),
            array(
                'expected' => 'abc',
                'source' => '/** abc */',
            ),
            array(
                'expected' => 'abc',
                'source' => '/**
                              * abc
                              */',
            ),
            array(
                'expected' => 'abc
def
ghi',
                'source' => '/**
                              * abc
                              * def
                              * ghi
                              */',
            ),
            array(
                'expected' => <<<EOT
@return string
EOT
,
                'source' => <<<EOD
/**
    * @return string
    */
EOD
,
            ),
        );
    }

    /**
     * @dataProvider dataProviderFor_parse
     */
    public function test_parse($expected, $source)
    {
        $this->assertEquals($expected, parseDocComment($source));
    }

    public function dataProviderFor_parse()
    {
        return array(
            array(
                'expected' => array(
                    'heading' => '',
                    'desc' => '',
                    'tags' => array(),
                ),
                'source' => '',
            ),
            array(
                'expected' => array(
                    'heading' => 'Foo',
                    'desc' => '',
                    'tags' => [],
                ),
                'source' => 'Foo',
            ),
            array(
                'expected' => array(
                    'heading' => '',
                    'desc' => '',
                    'tags' => array(
                        array(
                            'name'    => 'var',
                            'content' => 'string Hoge Fuga'
                        )
                    ),
                ),
                'source' => '@var string Hoge Fuga',
            ),
            array(
                'expected' => array(
                    'heading' => '',
                    'desc' => '',
                    'tags' => array(
                        array(
                            'name'    => 'var',
                            'content' => 'string Hoge
Fuga'
                        )
                    ),
                ),
                'source' => '@var string Hoge
    Fuga',
            ),
            array(
                'expected' => array(
                    'heading' => 'Awesome Method',
                    'desc' => '',
                    'tags' => array(
                        array(
                            'name'    => 'return',
                            'content' => 'string Hoge Fuga'
                        )
                    ),
                ),
                'source' => 'Awesome Method

@return string Hoge Fuga',
            ),
            array(
                'expected' => array(
                    'heading' => 'Awesome
Method',
                    'desc' => '',
                    'tags' => array(),
                ),
                'source' => 'Awesome
Method',
            ),
            array(
                'expected' => array(
                    'heading' => 'awesome',
                    'desc' => 'method',
                    'tags' => array(),
                ),
                'source' => 'awesome

method',
            ),
        );
    }
}
