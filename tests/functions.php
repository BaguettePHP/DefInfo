<?php

/**
 * @param  string $a
 * @param  \DatetimeInterface $dt
 * @param  array  $c
 * @return bool
 */
function funcSample($a, \DatetimeInterface $dt = null, array $c = [])
{
    return true;
}

class SampleKlass
{
    /**
     * @param  string $a
     * @param  \DatetimeInterface $dt
     * @param  array  $c
     * @return bool
     */
    public function funcSample($a, \DatetimeInterface $dt = null, array $c = array())
    {
        return true;
    }
}
