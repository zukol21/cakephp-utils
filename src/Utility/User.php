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

use Cake\Core\Configure;

class User
{
    const CONFIG_KEY = 'currentUser';

    /**
     * Current user setter.
     *
     * @param mixed[] $user Current user information
     * @return void
     */
    public static function setCurrentUser(array $user): void
    {
        Configure::write(static::CONFIG_KEY, $user);
    }

    /**
     * Current user getter.
     *
     * @return mixed[]
     */
    public static function getCurrentUser(): array
    {
        return (array)Configure::read(static::CONFIG_KEY);
    }
}
