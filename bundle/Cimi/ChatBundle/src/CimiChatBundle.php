<?php

namespace Cimi\ChatBundle;

use Cimi\ChatBundle\DependencyInjection\CimiChatExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class CimiChatBundle extends AbstractBundle
{
    public function getPath(): string
    {
        return __DIR__;
    }

    public function getContainerExtension(): ?ExtensionInterface
    {
        return new CimiChatExtension();
    }
}