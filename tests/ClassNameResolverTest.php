<?php

namespace PhpDefInfo;

final class ClassNameResolverTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $f = '/path/to/file.php';

        $resolver = new ClassNameResolver;
        $resolver->addFile($f, 'Path\File', array(
            'Hoge' => 'a\Hoge',
            'fb'   => 'Fizz\Buzz',
            'FizzBuzz' => 'Fizz\Buzz',
        ));

        $this->assertEquals('Path\File\AAAAA', $resolver->resolve($f, 'AAAAA'));
        $this->assertEquals('AAAAA',  $resolver->resolve($f, '\AAAAA'));
        $this->assertEquals('a\Hoge', $resolver->resolve($f, 'Hoge'));
        $this->assertEquals('Hoge',   $resolver->resolve($f, '\Hoge'));
        $this->assertEquals('Path\File\Buzz', $resolver->resolve($f, 'Buzz'));
        $this->assertEquals('Fizz\Buzz', $resolver->resolve($f, 'FizzBuzz'));
        $this->assertEquals('Fizz\Buzz', $resolver->resolve($f, 'fb'));
    }
}
