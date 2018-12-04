<?php

namespace App\ValueObjects;

class Interval implements \JsonSerializable
{
    public function __construct(\DateTimeInterface $startsAt, \DateTimeInterface $endsAt)
    {
        $this->startsAt = $startsAt;
        $this->endsAt = $endsAt;
    }

    /**
     * @var \DateTimeInterface
     */
    private $startsAt;

    /**
     * @var \DateTimeInterface
     */
    private $endsAt;

    /**
     * @return \DateTimeInterface
     */
    public function getEndsAt(): \DateTimeInterface
    {
        return $this->endsAt;
    }

    /**
     * @param \DateTimeInterface $endsAt
     */
    public function setEndsAt(\DateTimeInterface $endsAt): void
    {
        $this->endsAt = $endsAt;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getStartsAt(): \DateTimeInterface
    {
        return $this->startsAt;
    }

    /**
     * @param \DateTimeInterface $startsAt
     */
    public function setStartsAt(\DateTimeInterface $startsAt): void
    {
        $this->startsAt = $startsAt;
    }

    public function jsonSerialize()
    {
        return [
            'starts_at' => $this->startsAt->format('Y-m-d H:i:s'),
            'ends_at' => $this->endsAt->format('Y-m-d H:i:s')
        ];
    }
}
