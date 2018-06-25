<?php

namespace Cron;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use Exception;
use InvalidArgumentException;
use RuntimeException;

/**
 * Abstract CRON expression field
 */
abstract class AbstractField implements FieldInterface
{
    /**
     * Check to see if a field is satisfied by a value
     *
     * @param string $dateValue Date value to check
     * @param string $value     Value to test
     *
     * @return bool
     */
    public function isSatisfied($dateValue, $value)
    {
        if ($this->isIncrementsOfRanges($value)) {
            return $this->isInIncrementsOfRanges($dateValue, $value);
        } elseif ($this->isRange($value)) {
            return $this->isInRange($dateValue, $value);
        }

        return $value == '*' || $dateValue == $value;
    }

    /**
     * Check if a value is a range
     *
     * @param string $value Value to test
     *
     * @return bool
     */
    public function isRange($value)
    {
        return strpos($value, '-') !== false;
    }

    /**
     * Check if a value is an increments of ranges
     *
     * @param string $value Value to test
     *
     * @return bool
     */
    public function isIncrementsOfRanges($value)
    {
        return strpos($value, '/') !== false;
    }

    /**
     * Test if a value is within a range
     *
     * @param string $dateValue Set date value
     * @param string $value     Value to test
     *
     * @return bool
     */
    public function isInRange($dateValue, $value)
    {
        $parts = array_map('trim', explode('-', $value, 2));

        return $dateValue >= $parts[0] && $dateValue <= $parts[1];
    }

    /**
     * Test if a value is within an increments of ranges (offset[-to]/step size)
     *
     * @param string $dateValue Set date value
     * @param string $value     Value to test
     *
     * @return bool
     */
    public function isInIncrementsOfRanges($dateValue, $value)
    {
        $parts = array_map('trim', explode('/', $value, 2));
        $stepSize = isset($parts[1]) ? (int) $parts[1] : 0;

        if ($stepSize === 0) {
            return false;
        }

        if (($parts[0] == '*' || $parts[0] === '0')) {
            return (int) $dateValue % $stepSize == 0;
        }

        $range = explode('-', $parts[0], 2);
        $offset = $range[0];
        $to = isset($range[1]) ? $range[1] : $dateValue;
        // Ensure that the date value is within the range
        if ($dateValue < $offset || $dateValue > $to) {
            return false;
        }

        if ($dateValue > $offset && 0 === $stepSize) {
          return false;
        }

        for ($i = $offset; $i <= $to; $i+= $stepSize) {
            if ($i == $dateValue) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns a range of values for the given cron expression
     *
     * @param string $expression The expression to evaluate
     * @param int $max           Maximum offset for range
     *
     * @return array
     */
    public function getRangeForExpression($expression, $max)
    {
        $values = array();

        if ($this->isRange($expression) || $this->isIncrementsOfRanges($expression)) {
            if (!$this->isIncrementsOfRanges($expression)) {
                list ($offset, $to) = explode('-', $expression);
                $stepSize = 1;
            }
            else {
                $range = array_map('trim', explode('/', $expression, 2));
                $stepSize = isset($range[1]) ? $range[1] : 0;
                $range = $range[0];
                $range = explode('-', $range, 2);
                $offset = $range[0];
                $to = isset($range[1]) ? $range[1] : $max;
            }
            $offset = $offset == '*' ? 0 : $offset;
            for ($i = $offset; $i <= $to; $i += $stepSize) {
                $values[] = $i;
            }
            sort($values);
        }
        else {
            $values = array($expression);
        }

        return $values;
    }

}


/**
 * CRON expression parser that can determine whether or not a CRON expression is
 * due to run, the next run date and previous run date of a CRON expression.
 * The determinations made by this class are accurate if checked run once per
 * minute (seconds are dropped from date time comparisons).
 *
 * Schedule parts must map to:
 * minute [0-59], hour [0-23], day of month, month [1-12|JAN-DEC], day of week
 * [1-7|MON-SUN], and an optional year.
 *
 * @link http://en.wikipedia.org/wiki/Cron
 */
class CronExpression
{
    const MINUTE = 0;
    const HOUR = 1;
    const DAY = 2;
    const MONTH = 3;
    const WEEKDAY = 4;
    const YEAR = 5;

    /**
     * @var array CRON expression parts
     */
    private $cronParts;

    /**
     * @var FieldFactory CRON field factory
     */
    private $fieldFactory;

    /**
     * @var int Max iteration count when searching for next run date
     */
    private $maxIterationCount = 1000;

    /**
     * @var array Order in which to test of cron parts
     */
    private static $order = array(self::YEAR, self::MONTH, self::DAY, self::WEEKDAY, self::HOUR, self::MINUTE);

    /**
     * Factory method to create a new CronExpression.
     *
     * @param string $expression The CRON expression to create.  There are
     *                           several special predefined values which can be used to substitute the
     *                           CRON expression:
     *
     *      `@yearly`, `@annually` - Run once a year, midnight, Jan. 1 - 0 0 1 1 *
     *      `@monthly` - Run once a month, midnight, first of month - 0 0 1 * *
     *      `@weekly` - Run once a week, midnight on Sun - 0 0 * * 0
     *      `@daily` - Run once a day, midnight - 0 0 * * *
     *      `@hourly` - Run once an hour, first minute - 0 * * * *
     * @param FieldFactory $fieldFactory Field factory to use
     *
     * @return CronExpression
     */
    public static function factory($expression, FieldFactory $fieldFactory = null)
    {
        $mappings = array(
            '@yearly' => '0 0 1 1 *',
            '@annually' => '0 0 1 1 *',
            '@monthly' => '0 0 1 * *',
            '@weekly' => '0 0 * * 0',
            '@daily' => '0 0 * * *',
            '@hourly' => '0 * * * *'
        );

        if (isset($mappings[$expression])) {
            $expression = $mappings[$expression];
        }
        return new static($expression, $fieldFactory ?: new FieldFactory());
    }

    /**
     * Validate a CronExpression.
     *
     * @param string $expression The CRON expression to validate.
     *
     * @return bool True if a valid CRON expression was passed. False if not.
     * @see \Cron\CronExpression::factory
     */
    public static function isValidExpression($expression)
    {
        try {
            self::factory($expression);
        } catch (InvalidArgumentException $e) {
            return false;
        }

        return true;
    }

    /**
     * Parse a CRON expression
     *
     * @param string       $expression   CRON expression (e.g. '8 * * * *')
     * @param FieldFactory $fieldFactory Factory to create cron fields
     */
    public function __construct($expression, FieldFactory $fieldFactory)
    {
        $this->fieldFactory = $fieldFactory;
        $this->setExpression($expression);
    }

    /**
     * Set or change the CRON expression
     *
     * @param string $value CRON expression (e.g. 8 * * * *)
     *
     * @return CronExpression
     * @throws \InvalidArgumentException if not a valid CRON expression
     */
    public function setExpression($value)
    {
        $this->cronParts = preg_split('/\s/', $value, -1, PREG_SPLIT_NO_EMPTY);
        
        if (count($this->cronParts) < 5) {
            throw new InvalidArgumentException(
                $value . ' is not a valid CRON expression'
            );
        }

        foreach ($this->cronParts as $position => $part) {
            $this->setPart($position, $part);
        }

        return $this;
    }

    /**
     * Set part of the CRON expression
     *
     * @param int    $position The position of the CRON expression to set
     * @param string $value    The value to set
     *
     * @return CronExpression
     * @throws \InvalidArgumentException if the value is not valid for the part
     */
    public function setPart($position, $value)
    {
        if (!$this->fieldFactory->getField($position)->validate($value)) {
            throw new InvalidArgumentException(
                'Invalid CRON field value ' . $value . ' at position ' . $position
            );
        }

        $this->cronParts[$position] = $value;

        return $this;
    }

    /**
     * Set max iteration count for searching next run dates
     *
     * @param int $maxIterationCount Max iteration count when searching for next run date
     *
     * @return CronExpression
     */
    public function setMaxIterationCount($maxIterationCount)
    {
        $this->maxIterationCount = $maxIterationCount;
        
        return $this;
    }

    /**
     * Get a next run date relative to the current date or a specific date
     *
     * @param string|\DateTime $currentTime      Relative calculation date
     * @param int              $nth              Number of matches to skip before returning a
     *                                           matching next run date.  0, the default, will return the current
     *                                           date and time if the next run date falls on the current date and
     *                                           time.  Setting this value to 1 will skip the first match and go to
     *                                           the second match.  Setting this value to 2 will skip the first 2
     *                                           matches and so on.
     * @param bool             $allowCurrentDate Set to TRUE to return the current date if
     *                                           it matches the cron expression.
     *
     * @return \DateTime
     * @throws \RuntimeException on too many iterations
     */
    public function getNextRunDate($currentTime = 'now', $nth = 0, $allowCurrentDate = false)
    {
        return $this->getRunDate($currentTime, $nth, false, $allowCurrentDate);
    }

    /**
     * Get a previous run date relative to the current date or a specific date
     *
     * @param string|\DateTime $currentTime      Relative calculation date
     * @param int              $nth              Number of matches to skip before returning
     * @param bool             $allowCurrentDate Set to TRUE to return the
     *                                           current date if it matches the cron expression
     *
     * @return \DateTime
     * @throws \RuntimeException on too many iterations
     * @see \Cron\CronExpression::getNextRunDate
     */
    public function getPreviousRunDate($currentTime = 'now', $nth = 0, $allowCurrentDate = false)
    {
        return $this->getRunDate($currentTime, $nth, true, $allowCurrentDate);
    }

    /**
     * Get multiple run dates starting at the current date or a specific date
     *
     * @param int              $total            Set the total number of dates to calculate
     * @param string|\DateTime $currentTime      Relative calculation date
     * @param bool             $invert           Set to TRUE to retrieve previous dates
     * @param bool             $allowCurrentDate Set to TRUE to return the
     *                                           current date if it matches the cron expression
     *
     * @return array Returns an array of run dates
     */
    public function getMultipleRunDates($total, $currentTime = 'now', $invert = false, $allowCurrentDate = false)
    {
        $matches = array();
        for ($i = 0; $i < max(0, $total); $i++) {
            try {
                $matches[] = $this->getRunDate($currentTime, $i, $invert, $allowCurrentDate);
            } catch (RuntimeException $e) {
                break;
            }
        }

        return $matches;
    }

    /**
     * Get all or part of the CRON expression
     *
     * @param string $part Specify the part to retrieve or NULL to get the full
     *                     cron schedule string.
     *
     * @return string|null Returns the CRON expression, a part of the
     *                     CRON expression, or NULL if the part was specified but not found
     */
    public function getExpression($part = null)
    {
        if (null === $part) {
            return implode(' ', $this->cronParts);
        } elseif (array_key_exists($part, $this->cronParts)) {
            return $this->cronParts[$part];
        }

        return null;
    }

    /**
     * Helper method to output the full expression.
     *
     * @return string Full CRON expression
     */
    public function __toString()
    {
        return $this->getExpression();
    }

    /**
     * Determine if the cron is due to run based on the current date or a
     * specific date.  This method assumes that the current number of
     * seconds are irrelevant, and should be called once per minute.
     *
     * @param string|\DateTime $currentTime Relative calculation date
     *
     * @return bool Returns TRUE if the cron is due to run or FALSE if not
     */
    public function isDue($currentTime = 'now')
    {
        if ('now' === $currentTime) {
            $currentDate = date('Y-m-d H:i');
            $currentTime = strtotime($currentDate);
        } elseif ($currentTime instanceof DateTime) {
            $currentDate = clone $currentTime;
            // Ensure time in 'current' timezone is used
            $currentDate->setTimezone(new DateTimeZone(date_default_timezone_get()));
            $currentDate = $currentDate->format('Y-m-d H:i');
            $currentTime = strtotime($currentDate);
        } elseif ($currentTime instanceof DateTimeImmutable) {
            $currentDate = DateTime::createFromFormat('U', $currentTime->format('U'));
            $currentDate->setTimezone(new DateTimeZone(date_default_timezone_get()));
            $currentDate = $currentDate->format('Y-m-d H:i');
            $currentTime = strtotime($currentDate);
        } else {
            $currentTime = new DateTime($currentTime);
            $currentTime->setTime($currentTime->format('H'), $currentTime->format('i'), 0);
            $currentDate = $currentTime->format('Y-m-d H:i');
            $currentTime = $currentTime->getTimeStamp();
        }

        try {
            return $this->getNextRunDate($currentDate, 0, true)->getTimestamp() == $currentTime;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get the next or previous run date of the expression relative to a date
     *
     * @param string|\DateTime $currentTime      Relative calculation date
     * @param int              $nth              Number of matches to skip before returning
     * @param bool             $invert           Set to TRUE to go backwards in time
     * @param bool             $allowCurrentDate Set to TRUE to return the
     *                                           current date if it matches the cron expression
     *
     * @return \DateTime
     * @throws \RuntimeException on too many iterations
     */
    protected function getRunDate($currentTime = null, $nth = 0, $invert = false, $allowCurrentDate = false)
    {
        if ($currentTime instanceof DateTime) {
            $currentDate = clone $currentTime;
        } elseif ($currentTime instanceof DateTimeImmutable) {
            $currentDate = DateTime::createFromFormat('U', $currentTime->format('U'));
            $currentDate->setTimezone($currentTime->getTimezone());
        } else {
            $currentDate = new DateTime($currentTime ?: 'now');
            $currentDate->setTimezone(new DateTimeZone(date_default_timezone_get()));
        }
        $currentDate->setTime($currentDate->format('H'), $currentDate->format('i'), 0);
        $nextRun = clone $currentDate;
        $nth = (int) $nth;

        // We don't have to satisfy * or null fields
        $parts = array();
        $fields = array();
        foreach (self::$order as $position) {
            $part = $this->getExpression($position);
            if (null === $part || '*' === $part) {
                continue;
            }
            $parts[$position] = $part;
            $fields[$position] = $this->fieldFactory->getField($position);
        }

        // Set a hard limit to bail on an impossible date
        for ($i = 0; $i < $this->maxIterationCount; $i++) {

            foreach ($parts as $position => $part) {
                $satisfied = false;
                // Get the field object used to validate this part
                $field = $fields[$position];
                // Check if this is singular or a list
                if (strpos($part, ',') === false) {
                    $satisfied = $field->isSatisfiedBy($nextRun, $part);
                } else {
                    foreach (array_map('trim', explode(',', $part)) as $listPart) {
                        if ($field->isSatisfiedBy($nextRun, $listPart)) {
                            $satisfied = true;
                            break;
                        }
                    }
                }
                // If the field is not satisfied, then start over
                if (!$satisfied) {
                    $field->increment($nextRun, $invert, $part);
                    continue 2;
                }
            }

            // Skip this match if needed
            if ((!$allowCurrentDate && $nextRun == $currentDate) || --$nth > -1) {
                $this->fieldFactory->getField(0)->increment($nextRun, $invert, isset($parts[0]) ? $parts[0] : null);
                continue;
            }

            return $nextRun;
        }
        // @codeCoverageIgnoreStart
        throw new RuntimeException('Impossible CRON expression');
        // @codeCoverageIgnoreEnd
    }
}

/**
 * Day of month field.  Allows: * , / - ? L W
 *
 * 'L' stands for "last" and specifies the last day of the month.
 *
 * The 'W' character is used to specify the weekday (Monday-Friday) nearest the
 * given day. As an example, if you were to specify "15W" as the value for the
 * day-of-month field, the meaning is: "the nearest weekday to the 15th of the
 * month". So if the 15th is a Saturday, the trigger will fire on Friday the
 * 14th. If the 15th is a Sunday, the trigger will fire on Monday the 16th. If
 * the 15th is a Tuesday, then it will fire on Tuesday the 15th. However if you
 * specify "1W" as the value for day-of-month, and the 1st is a Saturday, the
 * trigger will fire on Monday the 3rd, as it will not 'jump' over the boundary
 * of a month's days. The 'W' character can only be specified when the
 * day-of-month is a single day, not a range or list of days.
 *
 * @author Michael Dowling <mtdowling@gmail.com>
 */
class DayOfMonthField extends AbstractField
{
    /**
     * Get the nearest day of the week for a given day in a month
     *
     * @param int $currentYear  Current year
     * @param int $currentMonth Current month
     * @param int $targetDay    Target day of the month
     *
     * @return \DateTime Returns the nearest date
     */
    private static function getNearestWeekday($currentYear, $currentMonth, $targetDay)
    {
        $tday = str_pad($targetDay, 2, '0', STR_PAD_LEFT);
        $target = DateTime::createFromFormat('Y-m-d', "$currentYear-$currentMonth-$tday");
        $currentWeekday = (int) $target->format('N');

        if ($currentWeekday < 6) {
            return $target;
        }

        $lastDayOfMonth = $target->format('t');

        foreach (array(-1, 1, -2, 2) as $i) {
            $adjusted = $targetDay + $i;
            if ($adjusted > 0 && $adjusted <= $lastDayOfMonth) {
                $target->setDate($currentYear, $currentMonth, $adjusted);
                if ($target->format('N') < 6 && $target->format('m') == $currentMonth) {
                    return $target;
                }
            }
        }
    }

    public function isSatisfiedBy(DateTime $date, $value)
    {
        // ? states that the field value is to be skipped
        if ($value == '?') {
            return true;
        }

        $fieldValue = $date->format('d');

        // Check to see if this is the last day of the month
        if ($value == 'L') {
            return $fieldValue == $date->format('t');
        }

        // Check to see if this is the nearest weekday to a particular value
        if (strpos($value, 'W')) {
            // Parse the target day
            $targetDay = substr($value, 0, strpos($value, 'W'));
            // Find out if the current day is the nearest day of the week
            return $date->format('j') == self::getNearestWeekday(
                $date->format('Y'),
                $date->format('m'),
                $targetDay
            )->format('j');
        }

        return $this->isSatisfied($date->format('d'), $value);
    }

    public function increment(DateTime $date, $invert = false)
    {
        if ($invert) {
            $date->modify('previous day');
            $date->setTime(23, 59);
        } else {
            $date->modify('next day');
            $date->setTime(0, 0);
        }

        return $this;
    }

    /**
     * Validates that the value is valid for the Day of the Month field
     * Days of the month can contain values of 1-31, *, L, or ? by default. This can be augmented with lists via a ',',
     * ranges via a '-', or with a '[0-9]W' to specify the closest weekday.
     *
     * @param string $value
     * @return bool
     */
    public function validate($value)
    {
        // Allow wildcards and a single L
        if ($value === '?' || $value === '*' || $value === 'L') {
            return true;
        }

        // If you only contain numbers and are within 1-31
        if ((bool) preg_match('/^\d{1,2}$/', $value) && ($value >= 1 && $value <= 31)) {
            return true;
        }

        // If you have a -, we will deal with each of your chunks
        if ((bool) preg_match('/-/', $value)) {
            // We cannot have a range within a list or vice versa
            if ((bool) preg_match('/,/', $value)) {
                return false;
            }

            $chunks = explode('-', $value);
            foreach ($chunks as $chunk) {
                if (!$this->validate($chunk)) {
                    return false;
                }
            }

            return true;
        }

        // If you have a comma, we will deal with each value
        if ((bool) preg_match('/,/', $value)) {
            // We cannot have a range within a list or vice versa
            if ((bool) preg_match('/-/', $value)) {
                return false;
            }

            $chunks = explode(',', $value);
            foreach ($chunks as $chunk) {
                if (!$this->validate($chunk)) {
                    return false;
                }
            }

            return true;
        }

        // If you contain a /, we'll deal with it
        if ((bool) preg_match('/\//', $value)) {
            $chunks = explode('/', $value);
            foreach ($chunks as $chunk) {
                if (!$this->validate($chunk)) {
                    return false;
                }
            }
            return true;
        }

        // If you end in W, make sure that it has a numeric in front of it
        if ((bool) preg_match('/^\d{1,2}W$/', $value)) {
            return true;
        }

        return false;
    }
}


/**
 * Day of week field.  Allows: * / , - ? L #
 *
 * Days of the week can be represented as a number 0-7 (0|7 = Sunday)
 * or as a three letter string: SUN, MON, TUE, WED, THU, FRI, SAT.
 *
 * 'L' stands for "last". It allows you to specify constructs such as
 * "the last Friday" of a given month.
 *
 * '#' is allowed for the day-of-week field, and must be followed by a
 * number between one and five. It allows you to specify constructs such as
 * "the second Friday" of a given month.
 */
class DayOfWeekField extends AbstractField
{
    public function isSatisfiedBy(DateTime $date, $value)
    {
        if ($value == '?') {
            return true;
        }

        // Convert text day of the week values to integers
        $value = $this->convertLiterals($value);

        $currentYear = $date->format('Y');
        $currentMonth = $date->format('m');
        $lastDayOfMonth = $date->format('t');

        // Find out if this is the last specific weekday of the month
        if (strpos($value, 'L')) {
            $weekday = str_replace('7', '0', substr($value, 0, strpos($value, 'L')));
            $tdate = clone $date;
            $tdate->setDate($currentYear, $currentMonth, $lastDayOfMonth);
            while ($tdate->format('w') != $weekday) {
                $tdateClone = new DateTime();
                $tdate = $tdateClone
                    ->setTimezone($tdate->getTimezone())
                    ->setDate($currentYear, $currentMonth, --$lastDayOfMonth);
            }

            return $date->format('j') == $lastDayOfMonth;
        }

        // Handle # hash tokens
        if (strpos($value, '#')) {
            list($weekday, $nth) = explode('#', $value);

            // 0 and 7 are both Sunday, however 7 matches date('N') format ISO-8601
            if ($weekday === '0') {
                $weekday = 7;
            }

            // Validate the hash fields
            if ($weekday < 0 || $weekday > 7) {
                throw new InvalidArgumentException("Weekday must be a value between 0 and 7. {$weekday} given");
            }
            if ($nth > 5) {
                throw new InvalidArgumentException('There are never more than 5 of a given weekday in a month');
            }
            // The current weekday must match the targeted weekday to proceed
            if ($date->format('N') != $weekday) {
                return false;
            }

            $tdate = clone $date;
            $tdate->setDate($currentYear, $currentMonth, 1);
            $dayCount = 0;
            $currentDay = 1;
            while ($currentDay < $lastDayOfMonth + 1) {
                if ($tdate->format('N') == $weekday) {
                    if (++$dayCount >= $nth) {
                        break;
                    }
                }
                $tdate->setDate($currentYear, $currentMonth, ++$currentDay);
            }

            return $date->format('j') == $currentDay;
        }

        // Handle day of the week values
        if (strpos($value, '-')) {
            $parts = explode('-', $value);
            if ($parts[0] == '7') {
                $parts[0] = '0';
            } elseif ($parts[1] == '0') {
                $parts[1] = '7';
            }
            $value = implode('-', $parts);
        }

        // Test to see which Sunday to use -- 0 == 7 == Sunday
        $format = in_array(7, str_split($value)) ? 'N' : 'w';
        $fieldValue = $date->format($format);

        return $this->isSatisfied($fieldValue, $value);
    }

    public function increment(DateTime $date, $invert = false)
    {
        if ($invert) {
            $date->modify('-1 day');
            $date->setTime(23, 59, 0);
        } else {
            $date->modify('+1 day');
            $date->setTime(0, 0, 0);
        }

        return $this;
    }

    public function validate($value)
    {
        $value = $this->convertLiterals($value);

        foreach (explode(',', $value) as $expr) {
            if (!preg_match('/^(\*|[0-7](L?|#[1-5]))([\/\,\-][0-7]+)*$/', $expr)) {
                return false;
            }
        }

        return true;
    }

    private function convertLiterals($string)
    {
        return str_ireplace(
            array('SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'),
            range(0, 6),
            $string
        );
    }
}

/**
 * CRON field factory implementing a flyweight factory
 * @link http://en.wikipedia.org/wiki/Cron
 */
class FieldFactory
{
    /**
     * @var array Cache of instantiated fields
     */
    private $fields = array();

    /**
     * Get an instance of a field object for a cron expression position
     *
     * @param int $position CRON expression position value to retrieve
     *
     * @return FieldInterface
     * @throws InvalidArgumentException if a position is not valid
     */
    public function getField($position)
    {
        if (!isset($this->fields[$position])) {
            switch ($position) {
                case 0:
                    $this->fields[$position] = new MinutesField();
                    break;
                case 1:
                    $this->fields[$position] = new HoursField();
                    break;
                case 2:
                    $this->fields[$position] = new DayOfMonthField();
                    break;
                case 3:
                    $this->fields[$position] = new MonthField();
                    break;
                case 4:
                    $this->fields[$position] = new DayOfWeekField();
                    break;
                case 5:
                    $this->fields[$position] = new YearField();
                    break;
                default:
                    throw new InvalidArgumentException(
                        $position . ' is not a valid position'
                    );
            }
        }

        return $this->fields[$position];
    }
}

/**
 * CRON field interface
 */
interface FieldInterface
{
    /**
     * Check if the respective value of a DateTime field satisfies a CRON exp
     *
     * @param DateTime $date  DateTime object to check
     * @param string   $value CRON expression to test against
     *
     * @return bool Returns TRUE if satisfied, FALSE otherwise
     */
    public function isSatisfiedBy(DateTime $date, $value);

    /**
     * When a CRON expression is not satisfied, this method is used to increment
     * or decrement a DateTime object by the unit of the cron field
     *
     * @param DateTime $date   DateTime object to change
     * @param bool     $invert (optional) Set to TRUE to decrement
     *
     * @return FieldInterface
     */
    public function increment(DateTime $date, $invert = false);

    /**
     * Validates a CRON expression for a given field
     *
     * @param string $value CRON expression value to validate
     *
     * @return bool Returns TRUE if valid, FALSE otherwise
     */
    public function validate($value);
}

/**
 * Hours field.  Allows: * , / -
 */
class HoursField extends AbstractField
{
    public function isSatisfiedBy(DateTime $date, $value)
    {
        return $this->isSatisfied($date->format('H'), $value);
    }

    public function increment(DateTime $date, $invert = false, $parts = null)
    {
        // Change timezone to UTC temporarily. This will
        // allow us to go back or forwards and hour even
        // if DST will be changed between the hours.
        if (is_null($parts) || $parts == '*') {
            $timezone = $date->getTimezone();
            $date->setTimezone(new DateTimeZone('UTC'));
            if ($invert) {
                $date->modify('-1 hour');
            } else {
                $date->modify('+1 hour');
            }
            $date->setTimezone($timezone);

            $date->setTime($date->format('H'), $invert ? 59 : 0);
            return $this;
        }

        $parts = strpos($parts, ',') !== false ? explode(',', $parts) : array($parts);
        $hours = array();
        foreach ($parts as $part) {
            $hours = array_merge($hours, $this->getRangeForExpression($part, 23));
        }

        $current_hour = $date->format('H');
        $position = $invert ? count($hours) - 1 : 0;
        if (count($hours) > 1) {
            for ($i = 0; $i < count($hours) - 1; $i++) {
                if ((!$invert && $current_hour >= $hours[$i] && $current_hour < $hours[$i + 1]) ||
                    ($invert && $current_hour > $hours[$i] && $current_hour <= $hours[$i + 1])) {
                    $position = $invert ? $i : $i + 1;
                    break;
                }
            }
        }

        $hour = $hours[$position];
        if ((!$invert && $date->format('H') >= $hour) || ($invert && $date->format('H') <= $hour)) {
            $date->modify(($invert ? '-' : '+') . '1 day');
            $date->setTime($invert ? 23 : 0, $invert ? 59 : 0);
        }
        else {
            $date->setTime($hour, $invert ? 59 : 0);
        }

        return $this;
    }

    public function validate($value)
    {
        return (bool) preg_match('/^[\*,\/\-0-9]+$/', $value);
    }
}

/**
 * Minutes field.  Allows: * , / -
 */
class MinutesField extends AbstractField
{
    public function isSatisfiedBy(DateTime $date, $value)
    {
        return $this->isSatisfied($date->format('i'), $value);
    }

    public function increment(DateTime $date, $invert = false, $parts = null)
    {
        if (is_null($parts)) {
            if ($invert) {
                $date->modify('-1 minute');
            } else {
                $date->modify('+1 minute');
            }
            return $this;
        }

        $parts = strpos($parts, ',') !== false ? explode(',', $parts) : array($parts);
        $minutes = array();
        foreach ($parts as $part) {
            $minutes = array_merge($minutes, $this->getRangeForExpression($part, 59));
        }

        $current_minute = $date->format('i');
        $position = $invert ? count($minutes) - 1 : 0;
        if (count($minutes) > 1) {
            for ($i = 0; $i < count($minutes) - 1; $i++) {
                if ((!$invert && $current_minute >= $minutes[$i] && $current_minute < $minutes[$i + 1]) ||
                    ($invert && $current_minute > $minutes[$i] && $current_minute <= $minutes[$i + 1])) {
                    $position = $invert ? $i : $i + 1;
                    break;
                }
            }
        }

        if ((!$invert && $current_minute >= $minutes[$position]) || ($invert && $current_minute <= $minutes[$position])) {
            $date->modify(($invert ? '-' : '+') . '1 hour');
            $date->setTime($date->format('H'), $invert ? 59 : 0);
        }
        else {
            $date->setTime($date->format('H'), $minutes[$position]);
        }

        return $this;
    }

    public function validate($value)
    {
        return (bool) preg_match('/^[\*,\/\-0-9]+$/', $value);
    }
}


/**
 * Month field.  Allows: * , / -
 */
class MonthField extends AbstractField
{
    public function isSatisfiedBy(DateTime $date, $value)
    {
        // Convert text month values to integers
        $value = str_ireplace(
            array(
                'JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN',
                'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'
            ),
            range(1, 12),
            $value
        );

        return $this->isSatisfied($date->format('m'), $value);
    }

    public function increment(DateTime $date, $invert = false)
    {
        if ($invert) {
            $date->modify('last day of previous month');
            $date->setTime(23, 59);
        } else {
            $date->modify('first day of next month');
            $date->setTime(0, 0);
        }

        return $this;
    }

    public function validate($value)
    {
        return (bool) preg_match('/^[\*,\/\-0-9A-Z]+$/', $value);
    }
}

/**
 * Year field.  Allows: * , / -
 */
class YearField extends AbstractField
{
    public function isSatisfiedBy(DateTime $date, $value)
    {
        return $this->isSatisfied($date->format('Y'), $value);
    }

    public function increment(DateTime $date, $invert = false)
    {
        if ($invert) {
            $date->modify('-1 year');
            $date->setDate($date->format('Y'), 12, 31);
            $date->setTime(23, 59, 0);
        } else {
            $date->modify('+1 year');
            $date->setDate($date->format('Y'), 1, 1);
            $date->setTime(0, 0, 0);
        }

        return $this;
    }

    public function validate($value)
    {
        return (bool) preg_match('/^[\*,\/\-0-9]+$/', $value);
    }
}
