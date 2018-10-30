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
namespace Qobo\Utils\Utility;

use Cake\Log\Log;
use Exception;
use InvalidArgumentException;
use Qobo\Utils\Utility;

/**
 * Salt class
 *
 * This is an utility class which helps managing the security salt.
 * It expects the salt to be stored in the file.  If the file does
 * not exist or does not contain a valid salt, the new salt will be
 * generated and stored in the file.
 *
 * The best (if not the only) usage of this functionality is within
 * the CakePHP's `config/app.php`, for setting the salt configuration
 * like so:
 *
 * ```
 * 'Security' => [
 *     'salt' => Qobo\Utility\Salt::getSalt(),
 * ]
 * ```
 */
class Salt
{
    /**
     * @var string $saltFile Path to file to use for salt storage
     */
    public static $saltFile = TMP . 'security.salt';

    /**
     * @var int $saltMinLength Minimum length of the salt string
     */
    public static $saltMinLength = 32;

    /**
     * Get configured salt string
     *
     * If the salt was not properly configured, a new salt string
     * will be generated, stored, and returned.
     *
     * @throws \InvalidArgumentException when cannot read or regenerate salt
     * @return string Salt string
     */
    public static function getSalt(): string
    {
        $result = '';

        try {
            $result = static::readSaltFromFile();
        } catch (InvalidArgumentException $e) {
            Log::warning("Failed to read salt from file");
        }

        if ($result) {
            return $result;
        }

        try {
            $salt = static::generateSalt();
            static::writeSaltToFile($salt);
            $result = static::readSaltFromFile();
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException("Failed to regenerate and save new salt: " . $e->getMessage(), 0, $e);
        }
        Log::warning("New salt is generated and stored.  Users might need to logout and clean their cookies.");

        return $result;
    }

    /**
     * Read salt string from file
     *
     * @return string Valid salt string
     */
    protected static function readSaltFromFile(): string
    {
        Utility::validatePath(static::$saltFile);
        $result = file_get_contents(static::$saltFile);
        $result = $result ?: '';
        static::validateSalt($result);

        return $result;
    }

    /**
     * Write a valid salt string to file
     *
     * @throws \InvalidArgumentException when storing fails
     * @param string $salt Valid salt string
     * @return void
     */
    protected static function writeSaltToFile(string $salt): void
    {
        static::validateSalt($salt);
        $result = @file_put_contents(static::$saltFile, $salt);

        if (($result === false) || ($result <> strlen($salt))) {
            throw new InvalidArgumentException("Failed to write salt to file [" . static::$saltFile . "]");
        }
    }

    /**
     * Validate salt string
     *
     * @throws \InvalidArgumentException when the salt string is not valid
     * @param string $salt Salt string to validate
     * @return void
     */
    protected static function validateSalt(string $salt): void
    {
        if (!ctype_print($salt)) {
            throw new InvalidArgumentException("Salt is not a printable string");
        }

        static::validateSaltMinLength();

        $saltLength = strlen($salt);
        if ($saltLength < static::$saltMinLength) {
            throw new InvalidArgumentException("Salt length of $saltLength characters is less than expected " . static::$saltMinLength);
        }
    }

    /**
     * Check that the saltMinLength is correct
     *
     * @throws \InvalidArgumentException if saltMinLength is too short
     * @return void
     */
    protected static function validateSaltMinLength(): void
    {
        $length = static::$saltMinLength;
        if ($length <= 0) {
            throw new InvalidArgumentException("Minimum salt length can't be 0 or less. [$length] given. 32 or more is recommended");
        }
    }

    /**
     * Generate salt string
     *
     * @return string Salt string
     */
    protected static function generateSalt(): string
    {
        $result = '';

        static::validateSaltMinLength();

        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $poolSize = strlen($pool);
        for ($i = 0; $i < static::$saltMinLength; $i++) {
            $result .= $pool[rand(0, $poolSize - 1)];
        }

        return $result;
    }
}
