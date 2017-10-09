<?php
/**
 * Copyright (c) Qobo Ltd. (https://www.qobo.biz)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Qobo Ltd. (https://www.qobo.biz)
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Qobo\Utils;

use RuntimeException;

/**
 * Error Trait
 *
 * This trait assists with keeping track of errors
 * and warnings.
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
trait ErrorTrait
{
    /**
     * @var array $errors List of errors
     */
    protected $errors = [];

    /**
     * @var array $warnings List of warnings
     */
    protected $warnings = [];

    /**
     * Fail execution with a given error
     *
     * * Adds error to the list of errors
     * * Throws an exception with the error message
     *
     * @todo Switch to Throwable once the move to PHP 7 is complete
     * @throws \RuntimeException if error message given as a string
     * @param string|\Exception $message Error message or exception
     * @return void
     */
    protected function fail($message)
    {
        if (is_string($message)) {
            $message = new RuntimeException($message);
        }
        $this->errors[] = $message->getMessage();
        throw $message;
    }

    /**
     * Get errors
     *
     * @return array List of errors
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Get warnings
     *
     * @return array List of warnings
     */
    public function getWarnings()
    {
        return $this->warnings;
    }
}
