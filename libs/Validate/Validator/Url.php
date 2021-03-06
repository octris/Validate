<?php

/*
 * This file is part of the 'octris/validate' package.
 *
 * (c) Harald Lapp <harald@octris.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Octris\Validate\Validator;

/**
 * Validator for testing if a string is a valid URL.
 *
 * @copyright   copyright (c) 2010-2018 by Harald Lapp
 * @author      Harald Lapp <harald@octris.org>
 */
class Url extends \Octris\Validate\Validator
{
    /**
     * Validation pattern.
     *
     * @type    string
     */
    protected $pattern = "/^%s:\/\/(([a-z0-9\$\-\_\.\+\!\*'\(\)\,\;\?\&\=]|(\%%[0-9a-f]{2}))+(\:([a-z0-9\$\-\_\.\+\!\*'\(\)\,\;\?\&\=]|(\%%[0-9a-f]{2}))+)?\@)?((([a-z0-9]|([a-z0-9]([a-z0-9\-])*[a-z0-9]))\.)*([a-z]|([a-z][a-z0-9\-]*[a-z0-9]))|[0-9]{1,3}(\.[0-9]{1,3}){3})(\:[0-9]+)?(\/([a-z0-9\$\-\_\.\+\!\*'\(\)\,\;\:\@\&\=]|(\%%[0-9a-f]{2}))*(\/([a-z0-9\$\-\_\.\+\!\*'\(\)\,\;\:\@\&\=]|(\%%[0-9a-f]{2}))*)*(\?([a-z0-9\$\-\_\.\+\!\*'\(\)\,\;\:\@\&\=]|(\%%[0-9a-f]{2}))*)?)?$/i";

    /**
     * Constructor.
     *
     * @param   array       $options        Optional options for validator.
     */
    public function __construct(array $options = [])
    {
        if (!isset($options['schemes']) || !is_array($options['schemes'])) {
            $options['schemes'] = ['http', 'https'];
        }

        if (!isset($options['default_scheme']) || !is_string($options['default_scheme'])) {
            $options['default_scheme'] = 'http://';
        }

        parent::__construct($options);
    }

    /**
     * Overwrite preFilter of superclass to add a default scheme, if no scheme is specified.
     *
     * @param   string      $value      The provided URL.
     */
    public function preFilter($value)
    {
        $value = parent::preFilter($value);

        if (trim($value) != '' && !preg_match('|^[^:]+://|', $value)) {
            $value = $this->options['default_scheme'] . $value;
        }

        return $value;
    }

    /**
     * Validator implementation.
     *
     * @param   mixed       $value          Value to validate.
     * @return  bool                        Returns true if value is valid.
     */
    public function validate($value)
    {
        $pattern = sprintf(
            $this->pattern,
            (count($this->options['schemes']) > 0
             ? '(' . implode('|', $this->options['schemes']) . ')'
             : '(.+)')
        );

        return preg_match($pattern, $value);
    }
}
