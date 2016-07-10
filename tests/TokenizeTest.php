<?php

namespace PhpDefInfo;

final class TokenizeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider for_get_use_clauses
     */
    public function test_get_use_clauses(array $expected, $code)
    {
        $this->assertEquals($expected, \PhpDefInfo\get_use_clauses($code));
    }

    public function for_get_use_clauses()
    {
        return array(
            array(
                'expected' => array(
                    'Hoge' => '',
                ),
                '<?php

use Hoge;
',
            ),
            array(
                'expected' => array(
                    'Hoge' => '',
                    'FizzBuzz' => 'fb',
                ),
                '<?php

use Hoge;
use FizzBuzz as fb;
',
            ),
            array(
                'expected' => array(
                    'Hoge' => '',
                    'FizzBuzz' => 'fb',
                ),
                '<?php

use Hoge, FizzBuzz as fb;
',
            ),
            array(
                'expected' => array(
                    'Hoge' => '',
                ),
                '<?php

use Hoge;
use function hoge\fizzbuzz;
use const hoge\fizzbuzz\constant\Fizz;
',
            ),
        );
    }
}
