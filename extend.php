<?php

/*
 * This file is part of dizy/flarum-custom-smtp.
 *
 * Copyright (c) 2020 Dizy.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use Dizy\FlarumCustomSMTP\CustomSMTPDriver;
use Flarum\Extend;

return [
    (new Extend\Mail())->driver('custom-smtp', CustomSMTPDriver::class)
];
