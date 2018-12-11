<?php
/**
 * Created by PhpStorm.
 * User: Ignas
 * Date: 12/11/2018
 * Time: 5:04 PM
 */

namespace App\Tests;


use App\Entity\Trainer;
use App\Repository\AvailabilitySlotRepository;
use App\Services\AvailableTimesCalculationService;
use App\ValueObjects\Interval;
use PHPUnit\Framework\TestCase;

class AvailableTimesCalculationServiceTest extends TestCase
{



    public function testGetAvailableTimes()
    {
        $trainer = new Trainer();

        $stub = $this->createMock(AvailabilitySlotRepository::class);

        $stub->method('getTrainerSlotsWithScheduledWorkoutsArray')
            ->with($trainer)
            ->willReturn([
                [
                    "a_id" => 1,
                    "a_startsAt" => new \DateTime("2018-12-12 10:00:00"),
                    "a_endsAt" => new \DateTime("2018-12-12 12:00:00"),
                    "s_id" => 1,
                    "s_startsAt" => new \DateTime("2018-12-12 10:30:00"),
                    "s_endsAt" => new \DateTime("2018-12-12 11:00:00")
                ]
            ]);

        $availableTimesCalculationService = new AvailableTimesCalculationService($stub);

        $intervals = $availableTimesCalculationService->getAvailableTimes($trainer);

        $desiredArray = [
            new Interval(new \DateTime("2018-12-12 10:00:00"), new \DateTime("2018-12-12 10:30:00")),
            new Interval(new \DateTime("2018-12-12 11:00:00"), new \DateTime("2018-12-12 12:00:00"))
        ];

        $this->assertEquals($intervals, $desiredArray);
    }
}
