<?php
/**
 * Created by PhpStorm.
 * User: abc
 * Date: 12/13/2019
 * Time: 10:56 PM
 */

namespace backend\modules\report\components;

class DateRangePickerHelper extends \yii\base\BaseObject
{
    /**
     * Convert Date Range string to list timestamp
     *
     * $start_date_report d/m/y format
     * $end_date_report d/m/y
     * return array()
     */
    public static function getListDateRange($start_date_report, $end_date_report)
    {
        if (!$start_date_report instanceof \DateTime) {
            $start_date_report = self::formatTimestampToDateTimeObject($start_date_report);
        }
        if (!$end_date_report instanceof \DateTime) {
            $end_date_report = self::formatTimestampToDateTimeObject($end_date_report);
        }
        //86400 = 1 day
        $date = [];
        for ($i = (int)$start_date_report->getTimestamp(); $i <= (int)$end_date_report->getTimestamp(); $i = $i + 86400) {
            $date[] = $i;
        }
        return $date;
    }

    public static function formatTimestampToDateTimeObject($date)
    {
        // 13/12/2019
        $date = explode('/', $date);
        $datetime1 = date_create($date[2] . '-' . $date[1] . '-' . $date[0]);

        return $datetime1;
    }
}
