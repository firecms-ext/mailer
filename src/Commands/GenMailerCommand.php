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
namespace FirecmsExt\Mailer\Commands;

use Hyperf\Devtool\Generator\GeneratorCommand;

class GenMailerCommand extends GeneratorCommand
{
    public function __construct()
    {
        parent::__construct('gen:mail');

        $this->setDescription('Create a new email class');
    }

    /**
     * 获取生成器的存根文件。
     */
    protected function getStub(): string
    {
        return __DIR__ . '/stubs/mail.stub';
    }

    /**
     * 获取类的默认名称空间。
     */
    protected function getDefaultNamespace(): string
    {
        return $this->getConfig()['namespace'] ?? 'App\\Mail';
    }
}
