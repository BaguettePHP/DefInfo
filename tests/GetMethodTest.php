<?php

namespace PhpDefInfo;

final class GetMethodTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderFor_test
     */
    public function test($expected, $source)
    {
        $this->assertEquals($expected, getMethod($source));
    }

    public function dataProviderFor_test()
    {
        if (PHP_MAJOR_VERSION < 7) {
            $additional = array();
        } else {
            $additional = array(
                array(
                    'expected' => array(
                        'file' => array(
                            'fileName' => __DIR__ . '/functions_php7.php',
                            'startLine' => 22,
                            'endLine' => 25,
                        ),
                        'docComment' => '/**
     * @param  string $a
     * @param  \DatetimeInterface $dt
     * @param  array  $c
     * @return bool Hoge Fuga
     */',
                        'doc' => array(
                            'heading' => '',
                            'desc' => '',
                        ),
                        'numberOfRequiredParameters' => 1,
                        'return' => array(
                            'type' => 'string',
                            'description' => 'Hoge Fuga',
                        ),
                        'isDeprecated' => false,
                        'isGenerator' => false,
                        'module' => false,
                        'returnsReference' => false,
                    ),
                    'source' => 'SampleKlass7::funcSample7',
                ),
            );
        }

        return array_merge(array(
            array(
                'expected' => array(
                    'file' => array(
                        'fileName' => __DIR__ . '/functions.php',
                        'startLine' => 22,
                        'endLine' => 25,
                    ),
                    'docComment' => '/**
     * @param  string $a
     * @param  \DatetimeInterface $dt
     * @param  array  $c
     * @return bool
     */',
                    'doc' => array(
                        'heading' => '',
                        'desc' => '',
                    ),
                    'numberOfRequiredParameters' => 1,
                    'return' => array(
                        'type' => 'bool',
                        'description' => '',
                    ),
                    'isDeprecated' => false,
                    'isGenerator' => false,
                    'module' => false,
                    'returnsReference' => false,
                ),
                'source' => 'SampleKlass::funcSample',
            ),
            array(
                'expected' => array(
                    'file' => null,
                    'docComment' => '',
                    'doc' => [
                        'heading' => '',
                        'desc' => '',
                    ],
                    'numberOfRequiredParameters' => 2,
                    'return' => array(
                        'type' => '',
                        'description' => '',
                    ),
                    'isDeprecated' => false,
                    'isGenerator' => false,
                    'module' => 'date',
                    'returnsReference' => false,
                ),
                'source' => '\Datetime::createFromFormat',
            ),
        ), $additional);
    }
}
