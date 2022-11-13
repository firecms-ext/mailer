<?php

declare(strict_types=1);
/**
 * This file is part of FirecmsExt Mailer.
 *
 * @link     https://www.klmis.cn
 * @document https://www.klmis.cn
 * @contact  zhimengxingyun@klmis.cn
 * @license  https://github.com/firecms-ext/mailer/blob/master/LICENSE
 */
namespace FirecmsExt\Mailer;

use FirecmsExt\Mailer\Commands\GenMailerCommand;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
            ],
            'commands' => [
                GenMailerCommand::class
            ],
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The config for firecms-ext/mailer.',
                    'source' => __DIR__ . '/../publish/mailer.php',
                    'destination' => BASE_PATH . '/config/autoload/mailer.php',
                ],
            ],
        ];
    }
}
