<?php

namespace App\ValueObjects;

use App\Entity\Tag;
use Symfony\Component\HttpFoundation\Request;

class Filter
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var int
     */
    private $page;

    /**
     * @var int
     */
    private $itemsPerPage = 6;

    /**
     * @var Tag[]
     */
    private $tags;

    /**
     * @var string
     */
    private $date;

    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $to;

    /**
     * @var \DateTime|null
     */
    private $startsAt = null;

    /**
     * @var \DateTime|null
     */
    private $endsAt = null;

    public function __construct(Request $request)
    {
        $this->name = $request->query->get('name');
        $this->page = $request->query->get('page') ?? 1;
        $this->tags = $request->query->get('tags');

        $this->date = $request->query->get('date');
        $this->from = $request->query->get('from');
        $this->to = $request->query->get('to');

        try {
            if (!$this->date || !$this->from || !$this->to) {
                throw new \Exception('Range is not defined');
            }

            $date = new \DateTime(trim($this->date));
            $from = new \DateTime(trim($this->from));
            $to = new \DateTime(trim($this->to));

            $this->startsAt = new \DateTime($date->format('Y-m-d') . ' ' . $from->format('H:i:s'));
            $this->endsAt = new \DateTime($date->format('Y-m-d') . ' ' . $to->format('H:i:s'));

            if ($this->startsAt >= $this->endsAt || $this->startsAt < new \DateTime()) {
                throw new \Exception('Incorrect range');
            }
        } catch (\Exception $e) {
            $this->resetDatesFilter();
        }
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    /**
     * @return Tag[]
     */
    public function getTags(): ?array
    {
        return $this->tags;
    }

    /**
     * @param Tag[] $tags
     */
    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }

    /**
     * @return \DateTime|null
     */
    public function getStartsAt(): ?\DateTime
    {
        return $this->startsAt;
    }

    /**
     * @param \DateTime|null $startsAt
     */
    public function setStartsAt(?\DateTime $startsAt): void
    {
        $this->startsAt = $startsAt;
    }

    /**
     * @return \DateTime|null
     */
    public function getEndsAt(): ?\DateTime
    {
        return $this->endsAt;
    }

    /**
     * @param \DateTime|null $endsAt
     */
    public function setEndsAt(?\DateTime $endsAt): void
    {
        $this->endsAt = $endsAt;
    }

    /**
     * @return int
     */
    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    /**
     * @param int $itemsPerPage
     */
    public function setItemsPerPage(int $itemsPerPage): void
    {
        $this->itemsPerPage = $itemsPerPage;
    }

    /**
     * @return string
     */
    public function getDate(): ?string
    {
        return $this->date;
    }

    /**
     * @param string $date
     */
    public function setDate(?string $date): void
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getFrom(): ?string
    {
        return $this->from;
    }

    /**
     * @param string $from
     */
    public function setFrom(?string $from): void
    {
        $this->from = $from;
    }

    /**
     * @return string
     */
    public function getTo(): ?string
    {
        return $this->to;
    }

    /**
     * @param string $to
     */
    public function setTo(?string $to): void
    {
        $this->to = $to;
    }

    private function resetDatesFilter()
    {
        $this->setDate(null);
        $this->setFrom(null);
        $this->setTo(null);
        $this->setStartsAt(null);
        $this->setEndsAt(null);
    }
}
