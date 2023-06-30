<?php

namespace App\Reservation\Dto;

use DateTime;
use Symfony\Component\Serializer\Annotation\Groups;

class ReservationInputDto
{
    #[Groups(['update'])]
    public ?int $id;

    #[Groups(['create', 'update'])]
    public ?DateTime $startDate;

    #[Groups(['create', 'update'])]
    public ?DateTime $endDate;
}