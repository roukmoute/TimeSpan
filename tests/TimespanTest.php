<?php

namespace Roukmoute\Timespan\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Roukmoute\Timespan\TimeSpan;

class TimespanTest extends TestCase {

    private const int MAX_MINUTES = 15_372_286_728;
    private const int MAX_SECONDS = 922_337_203_685;
    private const int MAX_MILLISECONDS = 922_337_203_685_477;
    private const int MAX_MICROSECONDS = 922_337_203_685_477_632;

    public function verifyTimeSpan(
        TimeSpan $timeSpan,
        int $days,
        int $hours,
        int $minutes,
        int $seconds,
        int $milliseconds,
        ?int $microseconds = null,
        ?int $nanoseconds = null,
    ): void {
        $this->assertSame($timeSpan->days(), $days, "Days mismatch");
        $this->assertSame($timeSpan->hours(), $hours, "Hours mismatch");
        $this->assertSame($timeSpan->minutes(), $minutes, "Minutes mismatch");
        $this->assertSame($timeSpan->seconds(), $seconds, "Seconds mismatch");
        $this->assertSame($timeSpan->milliseconds(), $milliseconds, "Milliseconds mismatch");

        if ($microseconds !== null)
        {
            $this->assertSame($timeSpan->microseconds(), $microseconds, "Microseconds mismatch");
        }

        if ($nanoseconds !== null)
        {
            $this->assertSame($timeSpan->nanoseconds(), $nanoseconds, "Nanoseconds mismatch");
        }

        // Vérifie si +timeSpan retourne le même objet (unary plus simulation)
        $positiveTimeSpan = $timeSpan; // En PHP, + ne change pas un objet
        $this->assertSame($timeSpan, $positiveTimeSpan, "Unary + mismatch");
    }

    public function sign(int $value): int
    {
        return $value <=> 0;
    }

    public function testMaxValue(): void
    {
        $this->verifyTimeSpan(TimeSpan::maxValue(), 10675199, 2, 48, 5, 477);
    }

    public function testMinValue(): void
    {
        $this->verifyTimeSpan(TimeSpan::minValue(), -10675199, -2, -48, -5, -477);
    }

    public function testZero(): void
    {
        $this->verifyTimeSpan(TimeSpan::zero(), 0, 0, 0, 0, 0);
    }

    public function testContructorEmpty()
    {
        $this->verifyTimeSpan(new TimeSpan(), 0, 0, 0, 0, 0);
    }

    public function testConstructorLong()
    {
        $this->verifyTimeSpan(new TimeSpan(999999999999999999), 1157407, 9, 46, 39, 999);
    }

    public function testFromDaysHoursMinutes()
    {
        $timeSpan = TimeSpan::fromTime(10, 9, 8);

        $this->verifyTimeSpan($timeSpan, 10, 9, 8, 0, 0);
    }

    public function testFromTimeDaysThrowsExceptionForOverflow()
    {
        $this->expectException(\OutOfRangeException::class);
        $this->expectExceptionMessage("TimeSpan overflowed because the duration is too long.");
        $timespan = TimeSpan::maxValue();
        TimeSpan::fromTime(
            $timespan->totalDays() + 1,
            $timespan->hours(),
            $timespan->minutes(),
            $timespan->seconds(),
            $timespan->milliseconds(),
        );
    }

    public function testFromTimeDaysThrowsExceptionForUnderflow()
    {
        $this->expectException(\OutOfRangeException::class);
        $this->expectExceptionMessage("TimeSpan overflowed because the duration is too long.");
        $timespan = TimeSpan::minValue();
        TimeSpan::fromTime(
            $timespan->totalDays() - 1,
            $timespan->hours(),
            $timespan->minutes(),
            $timespan->seconds(),
            $timespan->milliseconds(),
        );
    }

    public function testFromTimeHoursThrowsExceptionForOverflow()
    {
        $this->expectException(\OutOfRangeException::class);
        $this->expectExceptionMessage("TimeSpan overflowed because the duration is too long.");
        $timespan = TimeSpan::maxValue();
        TimeSpan::fromTime(
            $timespan->totalDays(),
            $timespan->hours() + 1,
            $timespan->minutes(),
            $timespan->seconds(),
            $timespan->milliseconds(),
        );
    }

    public function testFromTimeHoursThrowsExceptionForUnderflow()
    {
        $this->expectException(\OutOfRangeException::class);
        $this->expectExceptionMessage("TimeSpan overflowed because the duration is too long.");
        $timespan = TimeSpan::minValue();
        TimeSpan::fromTime(
            $timespan->totalDays(),
            $timespan->hours() - 1,
            $timespan->minutes(),
            $timespan->seconds(),
            $timespan->milliseconds(),
        );
    }

    public function testFromTimeMinutesThrowsExceptionForOverflow()
    {
        $this->expectException(\OutOfRangeException::class);
        $this->expectExceptionMessage("TimeSpan overflowed because the duration is too long.");
        $timespan = TimeSpan::maxValue();
        TimeSpan::fromTime(
            $timespan->totalDays(),
            $timespan->hours(),
            $timespan->minutes() + 1,
            $timespan->seconds(),
            $timespan->milliseconds(),
        );
    }

    public function testFromTimeMinutesThrowsExceptionForUnderflow()
    {
        $this->expectException(\OutOfRangeException::class);
        $this->expectExceptionMessage("TimeSpan overflowed because the duration is too long.");
        $timespan = TimeSpan::minValue();
        TimeSpan::fromTime(
            $timespan->totalDays(),
            $timespan->hours(),
            $timespan->minutes() - 1,
            $timespan->seconds(),
            $timespan->milliseconds(),
        );
    }

    public function testFromTimeSecondsThrowsExceptionForOverflow()
    {
        $this->expectException(\OutOfRangeException::class);
        $this->expectExceptionMessage("TimeSpan overflowed because the duration is too long.");
        $timespan = TimeSpan::maxValue();
        TimeSpan::fromTime(
            $timespan->totalDays(),
            $timespan->hours(),
            $timespan->minutes(),
            $timespan->seconds() + 1,
            $timespan->milliseconds(),
        );
    }

    public function testFromTimeSecondsThrowsExceptionForUnderflow()
    {
        $this->expectException(\OutOfRangeException::class);
        $this->expectExceptionMessage("TimeSpan overflowed because the duration is too long.");
        $timespan = TimeSpan::minValue();
        TimeSpan::fromTime(
            $timespan->totalDays(),
            $timespan->hours(),
            $timespan->minutes(),
            $timespan->seconds() - 1,
            $timespan->milliseconds(),
        );
    }

    public function testFromTimeMillisecondsThrowsExceptionForOverflow()
    {
        $this->expectException(\OutOfRangeException::class);
        $this->expectExceptionMessage("TimeSpan overflowed because the duration is too long.");
        $timespan = TimeSpan::maxValue();
        TimeSpan::fromTime(
            $timespan->totalDays(),
            $timespan->hours(),
            $timespan->minutes(),
            $timespan->seconds(),
            $timespan->milliseconds() + 1,
        );
    }

    public function testFromTimeMillisecondsThrowsExceptionForUnderflow()
    {
        $this->expectException(\OutOfRangeException::class);
        $this->expectExceptionMessage("TimeSpan overflowed because the duration is too long.");
        $timespan = TimeSpan::minValue();
        TimeSpan::fromTime(
            $timespan->totalDays(),
            $timespan->hours(),
            $timespan->minutes(),
            $timespan->seconds(),
            $timespan->milliseconds() - 1,
        );
    }

    public function testFromDaysHoursMinutesSeconds()
    {
        $timeSpan = TimeSpan::fromTime(10, 9, 8, 7);

        $this->verifyTimeSpan($timeSpan, 10, 9, 8, 7, 0);
    }

    public function testFromDaysHoursMinutesSecondsMilliseconds()
    {
        $timeSpan = TimeSpan::fromTime(10, 9, 8, 7, 6);

        $this->verifyTimeSpan($timeSpan, 10, 9, 8, 7, 6);
    }

    public function testFromDaysHoursMinutesSecondsMicroseconds()
    {
        $timeSpan = TimeSpan::fromTime(10, 9, 8, 7, 6, 5);

        $this->verifyTimeSpan($timeSpan, 10, 9, 8, 7, 6, 5);
    }

    public static function fromTimeWithMicrosecondsProvider()
    {
        return [
            [100],
            [200],
            [300],
            [400],
            [500],
            [600],
            [700],
            [800],
            [900],
        ];
    }

    #[DataProvider("fromTimeWithMicrosecondsProvider")]
    public function testFromTimeWithNanoseconds(int $nanoseconds)
    {
        $timeSpan = TimeSpan::fromTime(10, 9, 8, 7, 6, 5);
        $timeSpan = new TimeSpan($timeSpan->ticks() + $nanoseconds / 100);

        $this->verifyTimeSpan($timeSpan, 10, 9, 8, 7, 6, 5, $nanoseconds);
    }

    public static function totalDaysHoursMinutesSecondsMillisecondsProvider()
    {
        return [
            [TimeSpan::fromTime(0, 0, 0), 0.0, 0.0, 0.0, 0.0, 0.0],
            [TimeSpan::fromTime(0, 0, 0, 0, 500), 0.5 / 60.0 / 60.0 / 24.0, 0.5 / 60.0 / 60.0, 0.5 / 60.0, 0.5, 500.0],
            [TimeSpan::fromTime(0, 1, 0), 1 / 24.0, 1, 60, 3600, 3600000],
            [TimeSpan::fromTime(1, 0, 0), 1, 24, 1440, 86400, 86400000],
            [TimeSpan::fromTime(1, 1, 0), 25.0 / 24.0, 25, 1500, 90000, 90000000],
        ];
    }

    #[DataProvider("totalDaysHoursMinutesSecondsMillisecondsProvider")]
    public function testTotalDaysHoursMinutesSecondsMilliseconds(
        TimeSpan $timeSpan,
        float $expectedDays,
        float $expectedHours,
        float $expectedMinutes,
        float $expectedSeconds,
        float $expectedMilliseconds,
    ): void {
        // Use ToString() to prevent any rounding errors when comparing
        $this->assertSame(
            sprintf("%.15f", $expectedDays),
            sprintf("%.15f", $timeSpan->totalDays()),
            "TotalDays mismatch",
        );
        $this->assertSame($expectedHours, $timeSpan->totalHours(), "TotalHours mismatch");
        $this->assertSame($expectedMinutes, $timeSpan->totalMinutes(), "TotalMinutes mismatch");
        $this->assertSame($expectedSeconds, $timeSpan->totalSeconds(), "TotalSeconds mismatch");
        $this->assertSame($expectedMilliseconds, $timeSpan->totalMilliseconds(), "TotalMilliseconds mismatch");
    }

    public function testTotalMilliseconds()
    {
        $maxMilliseconds = PHP_INT_MAX / 10000;
        $minMilliseconds = PHP_INT_MIN / 10000;
        $this->assertSame($maxMilliseconds, TimeSpan::maxValue()->totalMilliseconds());
        $this->assertSame($minMilliseconds, TimeSpan::minValue()->totalMilliseconds());
    }

    public static function addTestData()
    {
        return [
            [TimeSpan::fromTime(0, 0, 0), TimeSpan::fromTime(1, 2, 3), TimeSpan::fromTime(1, 2, 3)],
            [TimeSpan::fromTime(1, 2, 3), TimeSpan::fromTime(4, 5, 6), TimeSpan::fromTime(5, 7, 9)],
            [TimeSpan::fromTime(1, 2, 3), TimeSpan::fromTime(-4, -5, -6), TimeSpan::fromTime(-3, -3, -3)],
            [TimeSpan::fromTime(1, 2, 3, 4, 5), TimeSpan::fromTime(0, 1, 2, 3), TimeSpan::fromTime(1, 3, 5, 7, 5)],
            [
                TimeSpan::fromTime(1, 2, 3, 4, 5),
                TimeSpan::fromTime(10, 12, 13, 14, 15),
                TimeSpan::fromTime(11, 14, 16, 18, 20),
            ],
            [new TimeSpan(10000), new TimeSpan(200000), new TimeSpan(210000)],
        ];
    }

    #[DataProvider("addTestData")]
    public function testAdd(TimeSpan $timeSpan1, TimeSpan $timeSpan2, TimeSpan $expected)
    {
        $this->assertEquals($expected, $timeSpan1->addTo($timeSpan2));
    }

    public function testAddMaxValueThrowsException()
    {
        $this->expectException(\OverflowException::class);
        $this->expectExceptionMessage("TimeSpan addition resulted in an overflow.");
        TimeSpan::maxValue()->addTo(new TimeSpan(1));
    }

    public function testAddMinValueThrowsException()
    {
        $this->expectException(\OverflowException::class);
        $this->expectExceptionMessage("TimeSpan addition resulted in an overflow.");
        TimeSpan::minValue()->addTo(new TimeSpan(-1));
    }

    public static function CompareToTestData()
    {
        return [
            [new TimeSpan(10000), new TimeSpan(10000), 0],
            [new TimeSpan(20000), new TimeSpan(10000), 1],
            [new TimeSpan(10000), new TimeSpan(20000), -1],

            [TimeSpan::fromTime(1, 2, 3), TimeSpan::fromTime(1, 2, 3), 0],
            [TimeSpan::fromTime(1, 2, 3), TimeSpan::fromTime(1, 2, 4), -1],
            [TimeSpan::fromTime(1, 2, 3), TimeSpan::fromTime(1, 2, 2), 1],
            [TimeSpan::fromTime(1, 2, 3), TimeSpan::fromTime(1, 3, 3), -1],
            [TimeSpan::fromTime(1, 2, 3), TimeSpan::fromTime(1, 1, 3), 1],
            [TimeSpan::fromTime(1, 2, 3), TimeSpan::fromTime(2, 2, 3), -1],
            [TimeSpan::fromTime(1, 2, 3), TimeSpan::fromTime(0, 2, 3), 1],

            [TimeSpan::fromTime(1, 2, 3, 4), TimeSpan::fromTime(1, 2, 3, 4), 0],
            [TimeSpan::fromTime(1, 2, 3, 4), TimeSpan::fromTime(1, 2, 3, 5), -1],
            [TimeSpan::fromTime(1, 2, 3, 4), TimeSpan::fromTime(1, 2, 3, 3), 1],
            [TimeSpan::fromTime(1, 2, 3, 4), TimeSpan::fromTime(1, 2, 4, 4), -1],
            [TimeSpan::fromTime(1, 2, 3, 4), TimeSpan::fromTime(1, 2, 2, 4), 1],
            [TimeSpan::fromTime(1, 2, 3, 4), TimeSpan::fromTime(1, 3, 3, 4), -1],
            [TimeSpan::fromTime(1, 2, 3, 4), TimeSpan::fromTime(1, 1, 3, 4), 1],
            [TimeSpan::fromTime(1, 2, 3, 4), TimeSpan::fromTime(2, 2, 3, 4), -1],
            [TimeSpan::fromTime(1, 2, 3, 4), TimeSpan::fromTime(0, 2, 3, 4), 1],

            [TimeSpan::fromTime(1, 2, 3, 4, 5), TimeSpan::fromTime(1, 2, 3, 4, 5), 0],
            [TimeSpan::fromTime(1, 2, 3, 4, 5), TimeSpan::fromTime(1, 2, 3, 4, 6), -1],
            [TimeSpan::fromTime(1, 2, 3, 4, 5), TimeSpan::fromTime(1, 2, 3, 4, 4), 1],
            [TimeSpan::fromTime(1, 2, 3, 4, 5), TimeSpan::fromTime(1, 2, 3, 5, 5), -1],
            [TimeSpan::fromTime(1, 2, 3, 4, 5), TimeSpan::fromTime(1, 2, 3, 3, 5), 1],
            [TimeSpan::fromTime(1, 2, 3, 4, 5), TimeSpan::fromTime(1, 2, 4, 4, 5), -1],
            [TimeSpan::fromTime(1, 2, 3, 4, 5), TimeSpan::fromTime(1, 2, 2, 4, 5), 1],
            [TimeSpan::fromTime(1, 2, 3, 4, 5), TimeSpan::fromTime(1, 3, 3, 4, 5), -1],
            [TimeSpan::fromTime(1, 2, 3, 4, 5), TimeSpan::fromTime(1, 1, 3, 4, 5), 1],
            [TimeSpan::fromTime(1, 2, 3, 4, 5), TimeSpan::fromTime(2, 2, 3, 4, 5), -1],
            [TimeSpan::fromTime(1, 2, 3, 4, 5), TimeSpan::fromTime(0, 2, 3, 4, 5), 1],
        ];
    }

    #[DataProvider("CompareToTestData")]
    public function testCompareTo(TimeSpan $timeSpan1, TimeSpan $timeSpan2, int $expected)
    {
        $this->assertSame($expected, $this->sign($timeSpan1->compareTo($timeSpan2)));
        $this->assertSame($expected, $this->sign(TimeSpan::compare($timeSpan1, $timeSpan2)));

        if ($expected >= 0)
        {
            $this->assertTrue($timeSpan1 >= $timeSpan2);
            $this->assertFalse($timeSpan1 < $timeSpan2);
        }
        if ($expected > 0)
        {
            $this->assertTrue($timeSpan1 > $timeSpan2);
            $this->assertFalse($timeSpan1 <= $timeSpan2);
        }
        if ($expected <= 0)
        {
            $this->assertTrue($timeSpan1 <= $timeSpan2);
            $this->assertFalse($timeSpan1 > $timeSpan2);
        }
        if ($expected < 0)
        {
            $this->assertTrue($timeSpan1 < $timeSpan2);
            $this->assertFalse($timeSpan1 >= $timeSpan2);
        }
    }

    public function testTimeSpanNullable()
    {
        $timeSpan1 = new TimeSpan(10000);
        $timeSpan2 = null;
        $expected = 1;
        $this->assertSame($expected, $this->sign($timeSpan1->compareTo($timeSpan2)));
        $this->assertSame($expected, $this->sign(TimeSpan::compare($timeSpan1, $timeSpan2)));
        $this->assertTrue($timeSpan1 > $timeSpan2);
        $this->assertFalse($timeSpan1 <= $timeSpan2);
    }

    public static function DurationTestData()
    {
        return [
            [TimeSpan::fromTime(0, 0, 0), TimeSpan::fromTime(0, 0, 0)],
            [TimeSpan::fromTime(1, 2, 3), TimeSpan::fromTime(1, 2, 3)],
            [TimeSpan::fromTime(-1, -2, -3), TimeSpan::fromTime(1, 2, 3)],
            [new TimeSpan(12345), new TimeSpan(12345)],
            [new TimeSpan(-12345), new TimeSpan(12345)],
        ];
    }

    #[DataProvider("DurationTestData")]
    public function testDuration(TimeSpan $timeSpan, TimeSpan $expected)
    {
        $this->assertEquals($expected, $timeSpan->duration());
    }

    public function testDurationInvalid()
    {
        $this->expectException(\OverflowException::class);
        $this->expectExceptionMessage('TimeSpan overflowed because the duration is too long.');
        TimeSpan::minValue()->duration();
    }

    public function testDurationTicksInvalid()
    {
        $this->expectException(\OverflowException::class);
        $this->expectExceptionMessage('TimeSpan overflowed because the duration is too long.');
        new TimeSpan(TimeSpan::minValue()->ticks())->duration();
    }

    public static function EqualsTestData()
    {
        return [
            [TimeSpan::fromTime(0, 0, 0), TimeSpan::fromTime(0, 0, 0), true],
            [TimeSpan::fromTime(1, 2, 3), TimeSpan::fromTime(1, 2, 3), true],
            [TimeSpan::fromTime(1, 2, 3), TimeSpan::fromTime(1, 2, 4), false],
            [TimeSpan::fromTime(1, 2, 3), TimeSpan::fromTime(1, 3, 3), false],
            [TimeSpan::fromTime(1, 2, 3), TimeSpan::fromTime(2, 2, 3), false],

            [TimeSpan::fromTime(1, 2, 3, 4), TimeSpan::fromTime(1, 2, 3, 4), true],
            [TimeSpan::fromTime(1, 2, 3, 4), TimeSpan::fromTime(1, 2, 3, 5), false],
            [TimeSpan::fromTime(1, 2, 3, 4), TimeSpan::fromTime(1, 2, 4, 4), false],
            [TimeSpan::fromTime(1, 2, 3, 4), TimeSpan::fromTime(1, 3, 3, 4), false],
            [TimeSpan::fromTime(1, 2, 3, 4), TimeSpan::fromTime(2, 2, 3, 4), false],
            [TimeSpan::fromTime(1, 2, 3, 4, 5), TimeSpan::fromTime(2, 3, 4), false],

            [TimeSpan::fromTime(1, 2, 3, 4, 5), TimeSpan::fromTime(1, 2, 3, 4, 5), true],
            [TimeSpan::fromTime(1, 2, 3, 4, 5), TimeSpan::fromTime(1, 2, 3, 4, 6), false],
            [TimeSpan::fromTime(1, 2, 3, 4, 5), TimeSpan::fromTime(1, 2, 3, 5, 5), false],
            [TimeSpan::fromTime(1, 2, 3, 4, 5), TimeSpan::fromTime(1, 2, 4, 4, 5), false],
            [TimeSpan::fromTime(1, 2, 3, 4, 5), TimeSpan::fromTime(1, 3, 3, 4, 5), false],
            [TimeSpan::fromTime(1, 2, 3, 4, 5), TimeSpan::fromTime(2, 2, 3, 4, 5), false],

            [TimeSpan::fromTime(1, 2, 3, 4, 5), TimeSpan::fromTime(1, 2, 3, 4), false],
            [TimeSpan::fromTime(1, 2, 3, 4, 5), TimeSpan::fromTime(2, 2, 3), false],

            [new TimeSpan(10000), new TimeSpan(10000), true],
            [new TimeSpan(10000), new TimeSpan(20000), false],
        ];
    }

    #[DataProvider("EqualsTestData")]
    public function testEquals(TimeSpan $timeSpan1, TimeSpan $timeSpan2, bool $expected)
    {
        $this->assertEquals($expected, TimeSpan::equalsTo($timeSpan1, $timeSpan2));
        $this->assertEquals($expected, $timeSpan1->equals($timeSpan2));
        $this->assertEquals($expected, $timeSpan1 == $timeSpan2);
        $this->assertEquals(!$expected, $timeSpan1 != $timeSpan2);
    }

    public function testEqualsNullable()
    {
        $this->assertEquals(false, (new TimeSpan(10000))->equals(null));
    }

    public function testFromDaysPositive()
    {
        $this->assertEquals(TimeSpan::fromTime(1, 2, 3, 4, 5, 6), TimeSpan::fromDays(1, 2, 3, 4, 5, 6));
    }

    public function testFromDaysNegative()
    {
        $this->assertEquals(TimeSpan::fromTime(-1, -2, -3, -4, -5, -6), TimeSpan::fromDays(-1, -2, -3, -4, -5, -6));
    }

    public function testFromDaysZero()
    {
        $this->assertEquals(TimeSpan::fromTime(0, 0, 0), TimeSpan::fromDays(0));
    }

    public function testFromSecondsShouldGiveResultWithPrecision()
    {
        $this->assertEquals(TimeSpan::fromTime(0, 0, 0, 101, 832), TimeSpan::fromSeconds(101, 832));
    }

    public static function fromMinutesSingleProvider()
    {
        return [
            [0],
            [1],
            [-1],
            [self::MAX_MINUTES],
            [-self::MAX_MINUTES],
        ];
    }

    #[DataProvider("fromMinutesSingleProvider")]
    public function testFromMinutesSingleShouldCreate(float $minutes)
    {
        $this->assertEquals(TimeSpan::fromDays(0, 0, $minutes), TimeSpan::fromMinutes($minutes));
    }

    public static function fromOverflowMinutesSingleProvider()
    {
        return [
            [self::MAX_MINUTES + 1],
            [-self::MAX_MINUTES - 1],
            [PHP_INT_MAX],
            [PHP_INT_MIN],
        ];
    }

    #[DataProvider("fromOverflowMinutesSingleProvider")]
    public function testFromMinutesSingleShouldOverflow(float $minutes)
    {
        $this->expectException(\OutOfRangeException::class);
        $this->expectExceptionMessage("TimeSpan overflowed because the duration is too long.");
        TimeSpan::fromMinutes($minutes);
    }

    public static function fromMinutesProvider()
    {
        return [
            [0, 0, 0, 0],
            [1, 1, 1, 1],
            [-1, -1, -1, -1],
            [self::MAX_MINUTES, 0, 0, 0],
            [-self::MAX_MINUTES, 0, 0, 0],
            [0, self::MAX_SECONDS, 0, 0],
            [0, -self::MAX_SECONDS, 0, 0],
            [0, 0, self::MAX_MILLISECONDS, 0],
            [0, 0, -self::MAX_MILLISECONDS, 0],
            [0, 0, 0, self::MAX_MICROSECONDS],
            [0, 0, 0, -self::MAX_MICROSECONDS],
        ];
    }

    #[DataProvider("fromMinutesProvider")]
    public function testFromMinutesShouldCreate(
        float $minutes,
        float $seconds = 0,
        float $milliseconds = 0,
        float $microseconds = 0,
    ) {
        $ticksFromMinutes = $minutes * TimeSpan::TICKS_PER_MINUTE;
        $ticksFromSeconds = $seconds * TimeSpan::TICKS_PER_SECOND;
        $ticksFromMilliseconds = $milliseconds * TimeSpan::TICKS_PER_MILLISECOND;
        $ticksFromMicroseconds = $microseconds * TimeSpan::TICKS_PER_MICROSECOND;
        $expected = new TimeSpan(
            (int)($ticksFromMinutes + $ticksFromSeconds + $ticksFromMilliseconds + $ticksFromMicroseconds),
        );

        $this->assertEquals($expected, TimeSpan::FromMinutes($minutes, $seconds, $milliseconds, $microseconds));
    }

    public static function fromOverflowMinutesProvider()
    {
        return [
            [self::MAX_MINUTES + 1, 0, 0, 0],
            [-(self::MAX_MINUTES + 1), 0, 0, 0],
            [0, self::MAX_SECONDS + 1, 0, 0],
            [0, -(self::MAX_SECONDS + 1), 0, 0],
            [0, 0, self::MAX_MILLISECONDS + 1, 0],
            [0, 0, -(self::MAX_MILLISECONDS + 1), 0],
            // These numbers, converted to float, return imprecision and cannot be tested.
            // [0, 0, 0, self::MAX_MICROSECONDS + 1],
            // [0, 0, 0, -(self::MAX_MICROSECONDS + 1)],
        ];
    }

    #[DataProvider("fromOverflowMinutesProvider")]
    public function testFromMinutesShouldOverflow(
        float $minutes,
        float $seconds = 0,
        float $milliseconds = 0,
        float $microseconds = 0,
    ) {
        $this->expectException(\OutOfRangeException::class);
        $this->expectExceptionMessage("TimeSpan overflowed because the duration is too long.");
        TimeSpan::fromMinutes($minutes, $seconds, $milliseconds, $microseconds);
    }

    public static function fromSecondsSingleProvider()
    {
        return [
            [0],
            [1],
            [-1],
            [self::MAX_SECONDS],
            [-self::MAX_SECONDS],
        ];
    }

    #[DataProvider("fromSecondsSingleProvider")]
    public function testFromSecondsSingleShouldCreate(float $seconds)
    {
        $this->assertEquals(TimeSpan::fromDays(0, 0, 0, $seconds), TimeSpan::fromSeconds($seconds));
    }

    public static function fromOverflowSecondsSingleProvider()
    {
        return [
            [self::MAX_SECONDS + 1],
            [-self::MAX_SECONDS - 1],
            [PHP_INT_MAX],
            [PHP_INT_MIN],
        ];
    }

    #[DataProvider("fromOverflowSecondsSingleProvider")]
    public function testFromSecondsSingleShouldOverflow(float $minutes)
    {
        $this->expectException(\OutOfRangeException::class);
        $this->expectExceptionMessage("TimeSpan overflowed because the duration is too long.");
        TimeSpan::fromSeconds($minutes);
    }

    public static function fromSecondsProvider()
    {
        return [
            [0, 0, 0],
            [1, 1, 1],
            [-1, -1, -1],
            [self::MAX_SECONDS, 0, 0],
            [-self::MAX_SECONDS, 0, 0],
            [0, self::MAX_MILLISECONDS, 0],
            [0, -self::MAX_MILLISECONDS, 0],
            [0, 0, self::MAX_MICROSECONDS],
            [0, 0, -self::MAX_MICROSECONDS],
        ];
    }

    #[DataProvider("fromSecondsProvider")]
    public function testFromSecondsShouldCreate(
        float $seconds,
        float $milliseconds = 0,
        float $microseconds = 0,
    ) {
        $ticksFromSeconds = $seconds * TimeSpan::TICKS_PER_SECOND;
        $ticksFromMilliseconds = $milliseconds * TimeSpan::TICKS_PER_MILLISECOND;
        $ticksFromMicroseconds = $microseconds * TimeSpan::TICKS_PER_MICROSECOND;
        $expected = new TimeSpan(
            (int)($ticksFromSeconds + $ticksFromMilliseconds + $ticksFromMicroseconds),
        );

        $this->assertEquals($expected, TimeSpan::FromSeconds($seconds, $milliseconds, $microseconds));
    }

    public static function fromOverflowSecondsProvider()
    {
        return [
            // These numbers, converted to float, return imprecision and cannot be tested.
            [0, self::MAX_SECONDS + 1, 0, 0],
            [0, -(self::MAX_SECONDS + 1), 0, 0],
            [0, 0, self::MAX_MILLISECONDS + 1, 0],
            [0, 0, -(self::MAX_MILLISECONDS + 1), 0],
            [0, 0, 0, self::MAX_MICROSECONDS + 1],
            [0, 0, 0, -(self::MAX_MICROSECONDS + 1)],
        ];
    }

    #[DataProvider("fromOverflowSecondsProvider")]
    public function testFromSecondsShouldOverflow(
        float $seconds = 0,
        float $milliseconds = 0,
        float $microseconds = 0,
    ) {
        $this->markTestSkipped('This test is not reliable because of float precision.');
        $this->expectException(\OutOfRangeException::class);
        $this->expectExceptionMessage("TimeSpan overflowed because the duration is too long.");
        TimeSpan::fromSeconds($seconds, $milliseconds, $microseconds);
    }

    public static function fromMillisecondsProvider()
    {
        return [
            [0, 0],
            [1, 0],
            [0, 1],
            [-1, 0],
            [0, -1],
            [self::MAX_MILLISECONDS, 0],
            [-self::MAX_MILLISECONDS, 0],
            [0, self::MAX_MICROSECONDS],
            [0, -self::MAX_MICROSECONDS],
        ];
    }

    #[DataProvider("fromMillisecondsProvider")]
    public function testFromMillisecondsShouldCreate(
        float $milliseconds,
        float $microseconds = 0,
    ) {
        $ticksFromMilliseconds = $milliseconds * TimeSpan::TICKS_PER_MILLISECOND;
        $ticksFromMicroseconds = $microseconds * TimeSpan::TICKS_PER_MICROSECOND;
        $expected = new TimeSpan(
            (int)($ticksFromMilliseconds + $ticksFromMicroseconds),
        );

        $this->assertEquals($expected, TimeSpan::fromMilliseconds($milliseconds, $microseconds));
    }

    public static function fromOverflowMillisecondsProvider()
    {
        return [
            [self::MAX_MILLISECONDS + 1, 0],
            [-(self::MAX_MILLISECONDS + 1), 0],
            [0, self::MAX_MICROSECONDS + 1],
            [0, -(self::MAX_MICROSECONDS + 1)],
        ];
    }

    #[DataProvider("fromOverflowMillisecondsProvider")]
    public function testFromMillisecondsShouldOverflow(
        float $milliseconds,
        float $microseconds = 0,
    ) {
        $this->markTestSkipped('This test is not reliable because of float precision.');

        $this->expectException(\OutOfRangeException::class);
        $this->expectExceptionMessage("TimeSpan overflowed because the duration is too long.");
        TimeSpan::fromMilliseconds($milliseconds, $microseconds);
    }

    public static function fromMicrosecondsProvider()
    {
        return [
            [0],
            [1],
            [-1],
            [self::MAX_MICROSECONDS],
            [-self::MAX_MICROSECONDS],
        ];
    }

    #[DataProvider("fromMicrosecondsProvider")]
    public function testFromMicrosecondsShouldCreate(
        float $microseconds,
    ) {
        $ticksFromMicroseconds = $microseconds * TimeSpan::TICKS_PER_MICROSECOND;
        $expected = new TimeSpan(
            (int)($ticksFromMicroseconds),
        );

        $this->assertEquals($expected, TimeSpan::FromMicroseconds($microseconds));
    }

    public static function fromOverflowMicrosecondsProvider()
    {
        return [
            [self::MAX_MICROSECONDS + 1],
            [-(self::MAX_MICROSECONDS + 1)],
        ];
    }

    #[DataProvider("fromOverflowMicrosecondsProvider")]
    public function testFromMicrosecondsShouldOverflow(
        float $microseconds = 0,
    ) {
        $this->markTestSkipped('This test is not reliable because of float precision.');

        $this->expectException(\OutOfRangeException::class);
        $this->expectExceptionMessage("TimeSpan overflowed because the duration is too long.");
        TimeSpan::fromMicroseconds($milliseconds, $microseconds);
    }

    public static function fromDaysProvider()
    {
        return [
            [100.5, TimeSpan::fromTime(100, 12, 0, 0)],
            [2.5, TimeSpan::fromTime(2, 12, 0, 0)],
            [1.0, TimeSpan::fromTime(1, 0, 0, 0)],
            [0.0, TimeSpan::fromTime(0, 0, 0, 0)],
            [-1.0, TimeSpan::fromTime(-1, 0, 0, 0)],
            [-2.5, TimeSpan::fromTime(-2, -12, 0, 0)],
            [-100.5, TimeSpan::fromTime(-100, -12, 0, 0)],
        ];
    }

    #[DataProvider("fromDaysProvider")]
    public function testFromDays(
        float $days,
        TimeSpan $expected,
    ) {
        $this->assertEquals($expected, TimeSpan::fromDays($days));
    }

    public static function fromDaysInvalidProvider()
    {
        $maxDays = PHP_INT_MAX / (TimeSpan::TICKS_PER_MILLISECOND / 1000 / 60 / 60 / 24);

        return [
            [$maxDays],
            [-$maxDays],
            [INF],
            [-INF],
        ];
    }

    #[DataProvider("fromDaysInvalidProvider")]
    public function testFromDaysInvalid(float $value)
    {
        $this->expectException(\OutOfRangeException::class);
        $this->expectExceptionMessage("TimeSpan overflowed because the duration is too long.");
        TimeSpan::fromDays($value);
    }

    public static function fromHoursProvider()
    {
        return [
            [100.5, TimeSpan::fromTime(4, 4, 30)],
            [2.5, TimeSpan::fromTime(0, 2, 30)],
            [1.0, TimeSpan::fromTime(0, 1, 0)],
            [0.0, TimeSpan::fromTime(0, 0, 0)],
            [-1.0, TimeSpan::fromTime(0, -1, 0)],
            [-2.5, TimeSpan::fromTime(0, -2, -30)],
            [-100.5, TimeSpan::fromTime(-4, -4, -30)],
        ];
    }

    #[DataProvider("fromHoursProvider")]
    public function testFromHours(float $hours, TimeSpan $expected)
    {
        $this->assertEquals($expected, TimeSpan::fromHours($hours));
    }

    public static function fromHoursInvalidProvider()
    {
        $maxHours = PHP_INT_MAX / (TimeSpan::TICKS_PER_MILLISECOND / 1000 / 60 / 60);

        return [
            [$maxHours],
            [-$maxHours],
            [INF],
            [-INF],
        ];
    }

    #[DataProvider("fromHoursInvalidProvider")]
    public function testFromHoursInvalid(float $value)
    {
        $this->expectException(\OutOfRangeException::class);
        $this->expectExceptionMessage("TimeSpan overflowed because the duration is too long.");
        TimeSpan::fromHours($value);
    }

    public static function fromMinutesTestData()
    {
        return [
            [100.5, TimeSpan::fromTime(0, 1, 40, 30)],
            [2.5, TimeSpan::fromTime(0, 0, 2, 30)],
            [1.0, TimeSpan::fromTime(0, 0, 1, 0)],
            [0.0, TimeSpan::fromTime(0, 0, 0, 0)],
            [-1.0, TimeSpan::fromTime(0, 0, -1, 0)],
            [-2.5, TimeSpan::fromTime(0, 0, -2, -30)],
            [-100.5, TimeSpan::fromTime(0, -1, -40, -30)],
        ];
    }

    #[DataProvider("fromMinutesTestData")]
    public function testFromMinutes(float $minutes, TimeSpan $expected)
    {
        $this->assertEquals($expected, TimeSpan::fromMinutes($minutes));
    }

    public static function fromMinutesInvalidProvider()
    {
        $maxMinutes = PHP_INT_MAX / (TimeSpan::TICKS_PER_MILLISECOND / 1000 / 60);

        return [
            [$maxMinutes],
            [-$maxMinutes],
            [INF],
            [-INF],
        ];
    }

    #[DataProvider("fromMinutesInvalidProvider")]
    public function testFromMinutesInvalid(float $value)
    {
        $this->expectException(\OutOfRangeException::class);
        $this->expectExceptionMessage("TimeSpan overflowed because the duration is too long.");
        TimeSpan::fromMinutes($value);
    }

    public static function fromSecondsTestData()
    {
        return [
            [100.5, TimeSpan::fromTime(0, 0, 1, 40, 500)],
            [2.5, TimeSpan::fromTime(0, 0, 0, 2, 500)],
            [1.0, TimeSpan::fromTime(0, 0, 0, 1, 0)],
            [0.0, TimeSpan::fromTime(0, 0, 0, 0, 0)],
            [-1.0, TimeSpan::fromTime(0, 0, 0, -1, 0)],
            [-2.5, TimeSpan::fromTime(0, 0, 0, -2, -500)],
            [-100.5, TimeSpan::fromTime(0, 0, -1, -40, -500)],
        ];
    }

    #[DataProvider("fromSecondsTestData")]
    public function testFromSeconds(float $seconds, TimeSpan $expected)
    {
        $this->assertEquals($expected, TimeSpan::fromSeconds($seconds));
    }

    public static function fromSecondsInvalidProvider()
    {
        $maxSeconds = PHP_INT_MAX / (TimeSpan::TICKS_PER_MILLISECOND / 1000);

        return [
            [$maxSeconds],
            [-$maxSeconds],
            [INF],
            [-INF],
        ];
    }

    #[DataProvider("fromSecondsInvalidProvider")]
    public function testFromSecondsInvalid(float $value)
    {
        $this->expectException(\OutOfRangeException::class);
        $this->expectExceptionMessage("TimeSpan overflowed because the duration is too long.");
        TimeSpan::fromSeconds($value);
    }

    public static function fromMillisecondsTestData()
    {
        return [
            [1500.5, new TimeSpan(15005000)],
            [2.5, new TimeSpan(25000)],
            [1.0, new TimeSpan(10000)],
            [0.0, new TimeSpan(0)],
            [-1.0, new TimeSpan(-10000)],
            [-2.5, new TimeSpan(-25000)],
            [-1500.5, new TimeSpan(-15005000)],
        ];
    }

    #[DataProvider("fromMillisecondsTestData")]
    public function testFromMilliseconds(float $milliseconds, TimeSpan $expected)
    {
        $this->assertEquals($expected, TimeSpan::fromMilliseconds($milliseconds));
    }

    public static function fromMillisecondsInvalidProvider()
    {
        $maxSeconds = TimeSpan::maxValue()->ticks() / (TimeSpan::TICKS_PER_MILLISECOND / 1000);

        return [
            [$maxSeconds],
            [-$maxSeconds],
            [INF],
            [-INF],
        ];
    }

    #[DataProvider("fromMillisecondsInvalidProvider")]
    public function testFromMillisecondsInvalid(float $value)
    {
        $this->expectException(\OutOfRangeException::class);
        $this->expectExceptionMessage("TimeSpan overflowed because the duration is too long.");
        TimeSpan::fromMilliseconds($value);
    }

    public static function fromTicksTestData()
    {
        return [
            [TimeSpan::TICKS_PER_MILLISECOND, TimeSpan::fromTime(0, 0, 0, 0, 1)],
            [TimeSpan::TICKS_PER_SECOND, TimeSpan::fromTime(0, 0, 0, 1)],
            [TimeSpan::TICKS_PER_MINUTE, TimeSpan::fromTime(0, 0, 1)],
            [TimeSpan::TICKS_PER_HOUR, TimeSpan::fromTime(0, 1, 0)],
            [TimeSpan::TICKS_PER_DAY, TimeSpan::fromTime(1, 0, 0)],
            [1.0, new TimeSpan(1)],
            [0.0, TimeSpan::fromTime(0, 0, 0)],
            [-1.0, new TimeSpan(-1)],
            [-TimeSpan::TICKS_PER_MILLISECOND, TimeSpan::fromTime(0, 0, 0, 0, -1)],
            [-TimeSpan::TICKS_PER_SECOND, TimeSpan::fromTime(0, 0, 0, -1)],
            [-TimeSpan::TICKS_PER_MINUTE, TimeSpan::fromTime(0, 0, -1)],
            [-TimeSpan::TICKS_PER_HOUR, TimeSpan::fromTime(0, -1, 0)],
            [-TimeSpan::TICKS_PER_DAY, TimeSpan::fromTime(-1, 0, 0)],
        ];
    }

    #[DataProvider("fromTicksTestData")]
    public function testFromTicks(float $ticks, TimeSpan $expected)
    {
        $this->assertEquals($expected, new TimeSpan($ticks));
    }

    public static function TotalSecondsExactRepresentationTestData()
    {
        return [
            [TimeSpan::fromTime(0, 0, 0)],
            [TimeSpan::fromTime(0, 0, 0, 1, 0)],
            [TimeSpan::fromTime(0, 0, 0, 1, 100)],
            [TimeSpan::fromTime(0, 0, 0, 0, -100)],
            [TimeSpan::fromTime(0, 0, 0, 0, 34967800)],
        ];
    }

    #[DataProvider("TotalSecondsExactRepresentationTestData")]
    public function testTotalSecondsExactRepresentation(TimeSpan $value)
    {
        $this->assertEquals($value, TimeSpan::fromSeconds($value->totalSeconds()));
    }
}
