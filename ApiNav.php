<?php

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
