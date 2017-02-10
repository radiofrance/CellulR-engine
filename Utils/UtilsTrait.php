<?php

namespace Rf\WebComponent\EngineBundle\Utils;

/**
 * Class UtilsTrait.
 *
 * @author Yoan Guillemin <yoann.guillemin@radiofrance.com>
 */
trait UtilsTrait
{
    /**
     * CamelCase to snake_case.
     *
     * @param string $input
     *
     * @return string
     */
    public function fromCamelCase($input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];

        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }

        return implode('_', $ret);
    }

    /**
     * Get the namespace given its directory.
     *
     * @param string $rootDir
     * @param string $viewObjectDir
     *
     * @return string
     */
    public function getNamespaceFromDir($rootDir, $dir)
    {
        return trim(str_replace(
            array($rootDir, '/'),
            array('', '\\'),
            $dir
        ), '\\');
    }
}
