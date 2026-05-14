<?php

/*
 * This file is part of ApiToken42
 *
 * Copyright(c) Akira Kurozumi <info@a-zumi.net>
 *
 * https://a-zumi.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\ApiToken42;

use Eccube\Common\EccubeNav;

class ApiNav implements EccubeNav
{
    public static function getNav(): array
    {
        return [
            'setting' => [
                'children' => [
                    'api' => [
                        'children' => [
                            'token_generator' => [
                                'name' => 'api.admin.token_generator.management',
                                'url' => 'admin_api_token_generator',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
