<?php

namespace Roukmoute\Timespan;

use OverflowException;

class TimeSpan {
    /* Represents the number of nanoseconds per tick. */
    public const int NANOSECONDS_PER_TICK = 100;

    /* Represents the number of ticks in 1 microsecond. */
    public const int TICKS_PER_MICROSECOND = 10;

    /* Represents the number of ticks in 1 millisecond. */
    public const int TICKS_PER_MILLISECOND = self::TICKS_PER_MICROSECOND * 1000;

    /* Represents the number of ticks in 1 second. */
    public const int TICKS_PER_SECOND = self::TICKS_PER_MILLISECOND * 1000;

    /* Represents the number of ticks in 1 minute. */
    public const int TICKS_PER_MINUTE = self::TICKS_PER_SECOND * 60;

    /* Represents the number of ticks in 1 hour. */
    public const int TICKS_PER_HOUR = self::TICKS_PER_MINUTE * 60;

    /* Represents the number of ticks in 1 day. */
    public const int TICKS_PER_DAY = self::TICKS_PER_HOUR * 24;

    /* Represents the number of microseconds in 1 millisecond. */
    public const int MICROSECONDS_PER_MILLISECOND = self::TICKS_PER_MILLISECOND / self::TICKS_PER_MICROSECOND;

    /* Represents the number of microseconds in 1 second. */
    public const int MICROSECONDS_PER_SECOND = self::TICKS_PER_SECOND / self::TICKS_PER_MICROSECOND;

    /* Represents the number of microseconds in 1 minute. */
    public const int MICROSECONDS_PER_MINUTE = self::TICKS_PER_MINUTE / self::TICKS_PER_MICROSECOND;

    /* Represents the number of microseconds in 1 hour. */
    public const int MICROSECONDS_PER_HOUR = self::TICKS_PER_HOUR / self::TICKS_PER_MICROSECOND;

    /* Represents the number of microseconds in 1 day. */
    public const int MICROSECONDS_PER_DAY = self::TICKS_PER_DAY / self::TICKS_PER_MICROSECOND;

    /* Represents the number of milliseconds in 1 second. */
    public const int MILLISECONDS_PER_SECOND = self::TICKS_PER_SECOND / self::TICKS_PER_MILLISECOND;

    /* Represents the number of seconds in 1 minute. */
    public const int SECONDS_PER_MINUTE = self::TICKS_PER_MINUTE / self::TICKS_PER_SECOND;

    /* Represents the number of minutes in 1 hour. */
    public const int MINUTES_PER_HOUR = self::TICKS_PER_HOUR / self::TICKS_PER_MINUTE;

    /* Represents the number of hours in 1 day. */
    public const int HOURS_PER_DAY = self::TICKS_PER_DAY / self::TICKS_PER_HOUR;

    public const int MIN_TICKS = PHP_INT_MIN;
    public const int MAX_TICKS = PHP_INT_MAX;

    public const float MIN_MICROSECONDS = self::MIN_TICKS / self::TICKS_PER_MICROSECOND;
    public const float MAX_MICROSECONDS = self::MAX_TICKS / self::TICKS_PER_MICROSECOND;

    // Un entier signé de 32-bits ou 64-bits
    // Représente la position du bit de signe
    // Ce bit indique si un nombre est positif (0) ou négatif (1).
    public const int SIGN_BIT_POSITION = PHP_INT_SIZE * 8 - 1;

    public function __construct(private readonly int $ticks = 0) {}

    public function totalDays()
    {
        return $this->ticks / self::TICKS_PER_DAY;
    }

    public function totalHours(): float
    {
        return $this->ticks / self::TICKS_PER_HOUR;
    }

    public function totalMinutes(): float
    {
        return $this->ticks / self::TICKS_PER_MINUTE;
    }

    public function totalSeconds(): float
    {
        return $this->ticks / self::TICKS_PER_SECOND;
    }

    public function totalMilliseconds(): float
    {
        return $this->ticks / self::TICKS_PER_MILLISECOND;
    }

    public function compareTo(?TimeSpan $timeSpan2): int
    {
        return $this->compare($this, $timeSpan2);
    }


    public static function zero(): TimeSpan
    {
        return new self(0);
    }

    public static function fromHours(
        float $hours,
        float $minutes = 0,
        float $seconds = 0,
        float $milliseconds = 0,
        float $microseconds = 0,
    ): TimeSpan {
        $totalMicroseconds = $hours * self::MICROSECONDS_PER_HOUR
            + ($minutes * self::MICROSECONDS_PER_MINUTE)
            + ($seconds * self::MICROSECONDS_PER_SECOND)
            + ($milliseconds * self::MICROSECONDS_PER_MILLISECOND)
            + $microseconds;;

        return self::fromMicroseconds($totalMicroseconds);
    }

    public static function fromMinutes(
        float $minutes,
        float $seconds = 0,
        float $milliseconds = 0,
        float $microseconds = 0,
    ) {
        $totalMicroseconds = ($minutes * self::MICROSECONDS_PER_MINUTE)
            + ($seconds * self::MICROSECONDS_PER_SECOND)
            + ($milliseconds * self::MICROSECONDS_PER_MILLISECOND)
            + $microseconds;

        return self::fromMicroseconds($totalMicroseconds);
    }

    public static function fromSeconds(float $seconds, float $milliseconds = 0, float $microseconds = 0): TimeSpan
    {
        $totalMicroseconds = ($seconds * self::MICROSECONDS_PER_SECOND)
            + ($milliseconds * self::MICROSECONDS_PER_MILLISECOND)
            + $microseconds;

        return self::fromMicroseconds($totalMicroseconds);
    }

    public static function fromMilliseconds(float $milliseconds, float|int $microseconds = 0)
    {
        $totalMicroseconds = ($milliseconds * self::MICROSECONDS_PER_MILLISECOND) + $microseconds;

        return self::fromMicroseconds($totalMicroseconds);
    }


    public static function maxValue(): TimeSpan
    {
        return new self(PHP_INT_MAX);
    }

    public static function minValue(): TimeSpan
    {
        return new self(PHP_INT_MIN);
    }

    public static function fromTime(
        float $days,
        float $hours,
        float $minutes,
        float $seconds = 0,
        float $milliseconds = 0,
        float $microseconds = 0,
    ): self {
        $totalMicroseconds = ($days * self::MICROSECONDS_PER_DAY)
            + ($hours * self::MICROSECONDS_PER_HOUR)
            + ($minutes * self::MICROSECONDS_PER_MINUTE)
            + ($seconds * self::MICROSECONDS_PER_SECOND);

        if ($milliseconds !== 0)
        {
            $totalMicroseconds += $milliseconds * self::MICROSECONDS_PER_MILLISECOND;
        }

        if ($microseconds !== 0)
        {
            $totalMicroseconds += $microseconds;
        }

        if ($totalMicroseconds > self::MAX_MICROSECONDS || $totalMicroseconds < self::MIN_MICROSECONDS)
        {
            throw new \OutOfRangeException('TimeSpan overflowed because the duration is too long.');
        }

        $ticks = $totalMicroseconds * self::TICKS_PER_MICROSECOND;

        return new self((int)$ticks);
    }

    public function duration(): self
    {
        if ($this->ticks === self::MIN_TICKS)
        {
            throw new \OverflowException('TimeSpan overflowed because the duration is too long.');
        }

        return new self($this->ticks < 0 ? -$this->ticks : $this->ticks);
    }

    public function addTo(self $timeSpan): self
    {
        return self::add($this, $timeSpan);
    }

    // Méthode pour l'opérateur binaire `+`
    public static function add(self $t1, self $t2): self
    {
        $result = $t1->ticks + $t2->ticks;

        if (is_int($t1->ticks) && is_int($t2->ticks) && is_double($result))
        {
            throw new OverflowException("TimeSpan addition resulted in an overflow.");
        }

        $t1Sign = $t1->ticks >> self::SIGN_BIT_POSITION; // Obtenir le bit de signe
        $t2Sign = $t2->ticks >> self::SIGN_BIT_POSITION;
        $resultSign = $result >> self::SIGN_BIT_POSITION;

        // Vérifier les dépassements d'entiers
        if (($t1Sign === $t2Sign) && ($t1Sign !== $resultSign))
        {
            throw new OverflowException("TimeSpan addition resulted in an overflow.");
        }

        return new self($result);
    }

    public function equals(?self $t2)
    {
        return $this == $t2;
    }

    // Méthodes pour les comparaisons
    public static function equalsTo(self $t1, self $t2): bool
    {
        return $t1 == $t2;
    }

    public function ticks(): int
    {
        return $this->ticks;
    }

    public function days(): int
    {
        return intdiv($this->ticks, self::TICKS_PER_DAY);
    }

    public function hours(): int
    {
        return intdiv($this->ticks, self::TICKS_PER_HOUR) % self::HOURS_PER_DAY;
    }

    public function minutes(): int
    {
        return intdiv($this->ticks, self::TICKS_PER_MINUTE) % self::MINUTES_PER_HOUR;
    }

    public function seconds(): int
    {
        return intdiv($this->ticks, self::TICKS_PER_SECOND) % self::SECONDS_PER_MINUTE;
    }

    public function milliseconds(): int
    {
        return intdiv($this->ticks, self::TICKS_PER_MILLISECOND) % self::MILLISECONDS_PER_SECOND;
    }

    public function microseconds(): int
    {
        return intdiv($this->ticks, self::TICKS_PER_MICROSECOND) % self::MICROSECONDS_PER_MILLISECOND;
    }

    public function nanoseconds(): int
    {
        return $this->ticks % self::TICKS_PER_MICROSECOND * self::NANOSECONDS_PER_TICK;
    }

    public static function fromDays(
        float $days,
        float $hours = 0,
        float $minutes = 0,
        float $seconds = 0,
        float $milliseconds = 0,
        float $microseconds = 0,
    ) {
        $totalMicroseconds = ($days * self::MICROSECONDS_PER_DAY)
            + ($hours * self::MICROSECONDS_PER_HOUR)
            + ($minutes * self::MICROSECONDS_PER_MINUTE)
            + ($seconds * self::MICROSECONDS_PER_SECOND)
            + ($milliseconds * self::MICROSECONDS_PER_MILLISECOND)
            + $microseconds;

        return self::fromMicroseconds($totalMicroseconds);
    }

    public static function compare(TimeSpan $timeSpan1, ?TimeSpan $timeSpan2): int
    {
        if ($timeSpan2 === null)
        {
            return 1;
        }

        return $timeSpan1->ticks <=> $timeSpan2->ticks;
    }

    public static function fromMicroseconds(int|float $microseconds)
    {
        if ($microseconds > self::MAX_MICROSECONDS || $microseconds < self::MIN_MICROSECONDS)
        {
            throw new \OutOfRangeException('TimeSpan overflowed because the duration is too long.');
        }

        $ticks = $microseconds * self::TICKS_PER_MICROSECOND;

        return new self((int)$ticks);
    }

}
