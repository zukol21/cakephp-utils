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

/**
 * @deprecated 7.1.3 Added BC alias.
 */
class_alias('Qobo\Utils\Utility\Lock\FileLock', 'Qobo\Utils\Utility\FileLock');

trigger_error(
    'Use Qobo\Utils\Utility\Lock\FileLock instead of Qobo\Utils\Utility\FileLock.',
    E_USER_DEPRECATED
);
