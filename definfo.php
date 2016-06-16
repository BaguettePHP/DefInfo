<?php
namespace PhpDefInfo;

/**
 * PhpDefInfo - CLI
 *
 * @author    USAMI Kenta <tadsan@zonu.me>
 * @copyright 2016 USAMI Kenta
 * @license   MPL-2.0 https://www.mozilla.org/en-US/MPL/2.0/
 */

//This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
//If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.

/**
 * @param  string   $name
 * @param  string   $namespace
 * @param  string[] $use
 * @return array
 */
function getClass($name, $namespace, array $use = [])
{
    $ref = new \ReflectionClass($name);
    $doc = $ref->getDocComment() ?: '';

    $methods = [];
    foreach ($ref->getMethods() as $method) {
        $methods[$method->getName()] = getFunctionAbstractInfo($method);
    }

    $info = [
        'file' => null,
        'type' => getClassType($ref),
        'attribute' => getClassAttribute($ref),
        'parentClass' => $ref->getParentClass() ?: '',
        'methods' => $methods,
        'constants' => mapkv('PhpDefInfo\getConstantInfo', $ref->getConstants()),
    ];

    if ($ref->isInternal()) {
        $info['file'] = [
            'fileName'  => $ref->getFileName(),
            'startLine' => $ref->getStartLine(),
            'endLine'   => $ref->getEndLine(),
        ];
    }

    return $info;
}

/**
 * @param  \ReflectionClass $ref
 * @return string
 */
function getClassType(\ReflectionClass $ref)
{
    switch (true) {
        case $ref->isInterface():
            return 'interface';
        case $ref->isTrait():
            return 'trait';
        default:
            return 'class';
    }
}

/**
 * @param  \ReflectionClass $ref
 * @return string
 */
function getClassAttribute(\ReflectionClass $ref)
{
    switch (true) {
        case $ref->isFinal():
            return 'final';
        case $ref->isAbstract():
            return 'abstract';
        case $ref->isAnonymous():
            return 'anonymous';
        default:
            return '';
    }
}

/**
 * @param  mixed  $value
 * @param  string $name
 * @return array
 */
function getConstantInfo($value, $name)
{
    return [
        'doc' => [
            'heading' => '',
            'desc' => '',
            'type' => _getTypeName($value),
        ],
        'value' => $value,
    ];
}

/**
 * @param  string   $name
 * @param  string   $namespace
 * @param  string[] $use
 * @return array
 */
function getProcedureDwim($name, $namespace, array $use = [])
{
    if (strpos($name, '::') === false) {
        return getFunction($name, $namespace);
    } else {
        return getMethod($name, $namespace, $use);
    }
}

/**
 * @param  string $name
 * @return array|null
 */
function getFunction($name, $namespace = '')
{
    if (substr($name, 0, 1) === '\\') {
        $is_global = true;
        $name = substr($name, 1);
    } else {
        $is_global = false;
    }

    if ($namespace === '' && !function_exists($name)) {
        return null;
    } elseif (!$is_global) {
        if ($namespace !== '' && function_exists($namespace . '\\' .$name)) {
            $name = $namespace . '\\' .$name;
        } elseif (!function_exists($name)) {
            return null;
        }
    }

    return getFunctionAbstractInfo(new \ReflectionFunction($name));
}

/**
 * @param  string $name
 * @return array|null
 */
function getMethod($name, $namespace = '', array $use = [])
{
    list($klass, $method) = explode('::', $name);

    if (!method_exists($klass, $method)) {
        return null;
    }

    return getFunctionAbstractInfo((new \ReflectionClass($klass))->getMethod($method));
}

function getFunctionAbstractInfo(\ReflectionFunctionAbstract $ref)
{
    static $has_returntype;
    if (!isset($has_returntype)) {
        $has_returntype = method_exists($ref, 'getReturnType');
    }


    $doc = $ref->getDocComment() ?: '';
    $docinfo = parseDocComment(trimDocComment($doc));
    $return_info = getReturnInfoByDocInfo($docinfo);

    $is_internal = $ref->isInternal();

    if ($has_returntype) {
        $type = $ref->getReturnType();
        if ($type) {
            $prefix = $type->isBuiltin() ? '' : '\\';
            $return_type = $prefix . $type;
        }
    }
    if (!isset($return_type) || $return_type === 0) {
        $return_type = $return_info['type'];
    }

    $info = [
        'file' => null,
        'docComment'   => $doc,
        'doc' => [
            'heading' => isset($docinfo['heading']) ? $docinfo['heading'] : '',
            'desc'    => isset($docinfo['desc'])    ? $docinfo['desc']    : '',
        ],
        'numberOfRequiredParameters' => $ref->getNumberOfRequiredParameters(),
        'return' => [
            'type' => $return_type,
            'description' => $return_info['desc'],
        ],
        'isDeprecated' => $ref->isDeprecated(),
        'isGenerator'  => $ref->isGenerator(),
        'module' => $ref->getExtensionName(),
        'returnsReference' => $ref->returnsReference(),
    ];

    if (!$is_internal) {
        $info['file'] = [
            'fileName'  => $ref->getFileName(),
            'startLine' => $ref->getStartLine(),
            'endLine'   => $ref->getEndLine(),
        ];
    }

    return $info;
}

/**
 * @param  array $docinfo DocComment
 * @return array ReturnInfo
 */
function getReturnInfoByDocInfo(array $docinfo)
{
    static $pattern = "/^([A-Za-z][A-za-z0-9_|]*)[\s]+(.+)/m";

    $type = '';
    $desc = '';

    foreach ($docinfo['tags'] as $i => $tag) {
        if ($tag['name'] === 'return') {
            if (strpos($tag['content'], ' ') !== false) {
                list($type, $desc) = explode(' ', $tag['content'], 2);
            } else {
                $type = $tag['content'];
            }
            break;
        }
    }

    return [
        'type' => trim($type),
        'desc' => trim($desc),
    ];
}

/**
 * @param  string $doc
 * @return array
 */
function trimDocComment($doc)
{
    static $pattern = ['@\A/\*\*[\s]*@m', '/^[\s]*\* /m', '@[\s]*\*/\z@m'];

    return preg_replace($pattern, '', $doc);
}

/**
 * @param  string $doc
 * @return array
 */
function parseDocComment($doc)
{
    static $pattern = '/\A@([-a-zA-Z0-0\\\\]+)[\s]+(.*)\z/m';

    $last = null;
    $heading = null;
    $h = array();
    $desc = array();
    $tags = array();

    foreach (explode("\n", $doc) as $line) {
        if (preg_match($pattern, $line, $matches)) {
            if (!isset($heading)) {
                $heading = implode("\n", $h);
            }
            $tags[] = [
                'name'    => $matches[1],
                'content' => $matches[2],
            ];
            $last = 'tag';

            continue;
        }

        $is_continuas = strncmp($line, '    ', 4) === 0;
        $line = trim($line);

        if ($is_continuas && $last === 'tag') {
            $tags[count($tags) - 1]['content'] .= ("\n" . $line);
        } elseif ($line !== '') {
            if (isset($heading)) {
                $desc[] = $line;
                $last = 'desc';
            } else {
                $h[] = $line;
                $last = 'heading';
            }
        } else {
            if (isset($heading)) {
                $desc[] = "\n";
                $last = 'desc';
            } else {
                $heading = implode("\n", $h);
                $last = 'heading';
            }
        }
    }

    if (!isset($heading)) {
        $heading = implode("\n", $h);
    }

    return array(
        'heading' => $heading,
        'desc' => implode("\n", $desc),
        'tags' => $tags,
    );
}

/**
 * @param  callable           $callback
 * @param  array|\Traversable $iter
 * @return array
 */
function mapkv(callable $callback, $iter)
{
    $result = [];
    foreach ($iter as $k => $v) {
        $result[$k] = $callback($v, $k);
    }

    return $result;
}

/**
 * @param  string $value
 * @return string
 */
function _getTypeName($value)
{
    switch (true) {
        case is_string($value):
            return 'string';
        case is_int($value):
            return 'int';
        case is_float($value):
            return 'float';
        case is_bool($value):
            return 'bool';
        case is_resource($value):
            return 'resource';
        case is_array($value):
            $n = 0;
            $types = [];
            foreach ($value as $k => $v) {
                if ($n !== $k) {
                    return 'array';
                }
                $types[_getTypeName($value)] = true;

                if (count($types) !== 1) {
                    return 'array';
                }

                $n++;
            }

            $type = array_keys($types)[0];
            return "{$type}[]";
        default:
            return get_class($value);
    }
}