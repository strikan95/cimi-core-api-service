<?php

namespace App\DataFixtures;

use App\PropertyListing\Entity\PropertyListing as PropertyListingEntity;
use App\Reservation\Entity\Reservation as ReservationEntity;
use DateInterval;
use DateTime;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ReservationFixtures extends BaseFixture implements DependentFixtureInterface
{
    const RESERVATIONS_COUNT = 2;

    public function getDependencies(): array
    {
        return [
            ListingFixtures::class
        ];
    }

    protected function loadData(ObjectManager $manager)
    {
        for ($i = 0; $i < $this->getReferencesCount(PropertyListingEntity::class); $i++)
        {
            $data = [];
            $data['listing'] = $this->getReferenceAtIndex(PropertyListingEntity::class, $i);
            $bla = $data['listing']->getTitle();
            $data['date_intervals'] = $this->getSequentialReservationIntervals(
                self::RESERVATIONS_COUNT,
                stayDuration:   [ 'min'=>30, 'max'=>'180' ],
                emptyDuration:  [ 'min'=>7, 'max'=>60 ],
                minStartDate:   '2023-01-01',
                maxStartDate:   '2023-06-01'
            );

            $this->createMany(ReservationEntity::class, self::RESERVATIONS_COUNT, function (ReservationEntity $reservation, $count, $data) {
                $reservation->setStartDate($data['date_intervals'][$count]['start']);
                $reservation->setEndDate($data['date_intervals'][$count]['end']);
                $reservation->setListing($data['listing']);
            }, $data);
        }

        $manager->flush();

    }

    private function getSequentialReservationIntervals(int $count, array $stayDuration, array $emptyDuration, string $minStartDate, string $maxStartDate)
    {
        $sequentialDatetimeIntervals = [];
        $rollingStartDate = $this->generateRandomDateTimeBetween($minStartDate, $maxStartDate);
        for ($i = 0; $i < $count; $i++)
        {
            $rollingStartDate->add(new DateInterval('P'.mt_rand($emptyDuration['min'], $emptyDuration['max']).'D'));
            $sequentialDatetimeIntervals[$i]['start'] = clone $rollingStartDate;

            $rollingStartDate->add(new DateInterval('P'.mt_rand($stayDuration['min'], $stayDuration['max']).'D'));
            $sequentialDatetimeIntervals[$i]['end'] = clone $rollingStartDate;
        }

        return $sequentialDatetimeIntervals;
    }

    private function generateRandomDateTimeBetween(string $minDate, string $maxDate): DateTime
    {
        return new DateTime(date('Y-m-d H:i:s',mt_rand(strtotime($minDate), strtotime($maxDate))));
    }
}
