<?php

namespace PhpDefInfo;

final class GetFunctionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderFor_test
     */
    public function test($expected, $source)
    {
        $this->assertEquals($expected, getFunction($source));
    }

    public function dataProviderFor_test()
    {
        return array(
            array(
                'expected' => array(
                    'file' => array(
                        'fileName' => __DIR__ . '/functions.php',
                        'startLine' => 9,
                        'endLine' => 12,
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
                'source' => 'funcSample',
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
                    'return' => [
                        'type' => '',
                        'description' => '',
                    ],
                    'isDeprecated' => false,
                    'isGenerator' => false,
                    'module' => 'standard',
                    'returnsReference' => false,
                ),
                'source' => 'strpos',
            ),
        );
    }
}
