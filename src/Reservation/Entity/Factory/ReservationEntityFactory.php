<?php

namespace App\Reservation\Entity\Factory;

use App\ApiTools\EntityFactory\AbstractEntityFactory;
use App\Reservation\Entity\Reservation;

class ReservationEntityFactory extends AbstractEntityFactory
{

    function getEntityClassName(): string
    {
        return Reservation::class;
    }

    function onCreatePreLoad($source, mixed $target, ?array $settings): void
    {
        return;
    }
}