<?php

namespace App\Services;

use App\Entity\Trainer;
use App\Repository\AvailabilitySlotRepository;
use App\ValueObjects\Interval;

class AvailableTimesCalculationService
{

    private $availabilitySlotsRepository;

    public function __construct(AvailabilitySlotRepository $availabilitySlotRepository)
    {
        $this->availabilitySlotsRepository = $availabilitySlotRepository;
    }

    /**
     * @param Trainer $trainer
     * @return Interval[]
     */
    public function getAvailableTimes(Trainer $trainer)
    {

        $scheduleData = $this->availabilitySlotsRepository->getTrainerSlotsWithScheduledWorkoutsArray($trainer);
        $availabilityPeriods = [];
        $mappedTimes = [];


        foreach ($scheduleData as $value) {  // Parses the data
            $slotId = $value['a_id'];
            if (!isset($availabilityPeriods[$slotId])) {
                $availabilityPeriods[$value['a_id']] = [
                    'starts_at' => $value['a_startsAt'],
                    'ends_at' => $value['a_endsAt']
                ];
            }

            $mappedTimes[$slotId][] = [
                'starts_at' => $value['s_startsAt'],
                'ends_at' => $value['s_endsAt']
            ];
        }

        return $this->calculateIntervals($mappedTimes, $availabilityPeriods);
    }

    /**
     * @param $mappedTimes
     * @param $availabilityPeriods
     * @return Interval[]
     */
    private function calculateIntervals($mappedTimes, $availabilityPeriods)
    {
        $availableTimes = [];

        foreach ($mappedTimes as $key => $value) {
            $rangeFrom = $availabilityPeriods[$key]['starts_at'];
            $rangeTo = $availabilityPeriods[$key]['ends_at'];

            $first = $value[0];
            $last = $value[count($value) - 1];

            if ($first['starts_at'] > $rangeFrom) {
                $availableTimes[] = new  Interval($rangeFrom, $first['starts_at']);
            }
            $insert = [
                'starts_at' => $first['ends_at']
            ];

            foreach ($value as $reservedTimes) {
                if (!array_key_exists('ends_at', $insert)) {
                    $insert['ends_at'] = $reservedTimes['starts_at'];
                    if ($insert['starts_at'] < $insert['ends_at']) {
                        $availableTimes[] = new Interval($insert['starts_at'], $insert['ends_at']);
                    }

                    $insert = [
                        'starts_at' => $reservedTimes['ends_at']
                    ];
                }
            }

            if ($last['ends_at'] < $rangeTo) {
                $availableTimes[] = new Interval($last['ends_at'], $rangeTo);
            }
        }
        return $availableTimes;
    }
}
