<?php

namespace Cimi\ChatBundle\Entity;
interface ChatUserInterface
{
    public function getId(): ?int;
    public function getName(): ?string;
}
