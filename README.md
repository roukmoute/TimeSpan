## TimeSpan v0.1

Represents a time interval

### Key Features:
- **Precise time representation** using ticks (nanoseconds) as the base unit.
- Support for converting between different time units (days, hours, minutes, seconds, milliseconds, microseconds, nanoseconds).

### Available Methods:
#### Time Conversion:
- `totalDays()`: Returns the total duration in days.
- `totalHours()`: Returns the total duration in hours.
- `totalMinutes()`: Returns the total duration in minutes.
- `totalSeconds()`: Returns the total duration in seconds.
- `totalMilliseconds()`: Returns the total duration in milliseconds.

#### Instance Creation:
- `TimeSpan::zero()`: Creates an instance representing a duration of 0.
- `TimeSpan::fromDays(float $days, ...)`: Creates an instance from a duration in days.
- `TimeSpan::fromHours(float $hours, ...)`: Creates an instance from a duration in hours.
- `TimeSpan::fromMinutes(float $minutes, ...)`: Creates an instance from a duration in minutes.
- `TimeSpan::fromSeconds(float $seconds, ...)`: Creates an instance from a duration in seconds.
- `TimeSpan::fromMilliseconds(float $milliseconds, ...)`: Creates an instance from a duration in milliseconds.
- `TimeSpan::fromMicroseconds(float $microseconds)`: Creates an instance from a duration in microseconds.

#### Manipulation and Comparison:
- `compareTo(?TimeSpan $timeSpan2)`: Compares two `TimeSpan` instances.
- `addTo(TimeSpan $timeSpan)`: Adds a duration to the current instance.
- `duration()`: Returns the absolute value of the current duration.
- `equals(?TimeSpan $t2)`: Checks if two `TimeSpan` instances are equal.
- `compare(TimeSpan $timeSpan1, ?TimeSpan $timeSpan2)`: Compares two durations in ticks.
- `negate(): TimeSpan`: Returns a new instance representing the negation of the current duration.

#### Tick-Based Properties:
- `ticks()`: Returns the duration in ticks.
- `days()`: Returns the whole number of days.
- `hours()`: Returns the number of hours in the current day.
- `minutes()`: Returns the number of minutes in the current hour.
- `seconds()`: Returns the number of seconds in the current minute.
- `milliseconds()`: Returns the number of milliseconds in the current second.
- `microseconds()`: Returns the number of microseconds in the current millisecond.
- `nanoseconds()`: Returns the number of remaining nanoseconds.

#### Constants:
- Definitions of relationships between time units, such as:
    - `TICKS_PER_SECOND`, `TICKS_PER_MINUTE`, `MICROSECONDS_PER_SECOND`, etc.
    - Limits: `MIN_TICKS` and `MAX_TICKS`.
