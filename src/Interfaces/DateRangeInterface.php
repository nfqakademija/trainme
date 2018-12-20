<?php
/**
 * Created by PhpStorm.
 * User: Ignas
 * Date: 12/20/2018
 * Time: 4:16 PM
 */

namespace App\Interfaces;

interface DateRangeInterface
{
    /**
     * @return \DateTimeInterface|null
     */
    public function getStartsAt(): ?\DateTimeInterface;

    /**
     * @param \DateTimeInterface $startsAt
     */
    public function setStartsAt(\DateTimeInterface $startsAt);

    /**
     * @return \DateTimeInterface|null
     */
    public function getEndsAt(): ?\DateTimeInterface;


    /**
     * @param \DateTimeInterface $endsAt
     */
    public function setEndsAt(\DateTimeInterface $endsAt);
}