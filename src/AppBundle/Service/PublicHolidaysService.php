<?php

namespace AppBundle\Service;

class PublicHolidaysService
{
    public function getPublicHolidaysOfThisYear($year = null)
    {
        if ($year === null)
        {
            $year = intval(date('Y'));
        }

        $easterDate  = easter_date($year);
        $easterDay   = date('d', $easterDate);
        $easterMonth = date('m', $easterDate);
        $easterYear   = date('Y', $easterDate);

        $publicHolidays = array(
            // Dates fixes
            date('Y-m-d', mktime(0, 0, 0, 1,  1,  $year)), // nouvel an
            date('Y-m-d', mktime(0, 0, 0, 5,  1,  $year)), // fête du travail
            date('Y-m-d', mktime(0, 0, 0, 5,  8,  $year)), // 8 mai
            date('Y-m-d', mktime(0, 0, 0, 7,  14, $year)), // 14 juillet
            date('Y-m-d', mktime(0, 0, 0, 8,  15, $year)), // 15 août
            date('Y-m-d', mktime(0, 0, 0, 11, 1,  $year)), // 1e nov
            date('Y-m-d', mktime(0, 0, 0, 11, 11, $year)), // 11 nov
            date('Y-m-d', mktime(0, 0, 0, 12, 25, $year)), // noel

            // Dates variables
            date('Y-m-d',mktime(0, 0, 0, $easterMonth, $easterDay + 1,  $easterYear)),
            date('Y-m-d',mktime(0, 0, 0, $easterMonth, $easterDay + 39, $easterYear)),
            date('Y-m-d',mktime(0, 0, 0, $easterMonth, $easterDay + 50, $easterYear)),
        );

        sort($publicHolidays);

        return $publicHolidays;
    }


    public function getPublicHolidaysofNextYear($year = null)
    {
        if ($year === null)
        {
            $year = intval(date('Y')+1);
        }

        $easterDate  = easter_date($year);
        $easterDay   = date('d', $easterDate);
        $easterMonth = date('m', $easterDate);
        $easterYear   = date('Y', $easterDate);

        $publicHolidays = array(
            // Dates fixes
            date('Y-m-d', mktime(0, 0, 0, 1,  1,  $year)), // nouvel an
            date('Y-m-d', mktime(0, 0, 0, 5,  1,  $year)), // fête du travail
            date('Y-m-d', mktime(0, 0, 0, 5,  8,  $year)), // 8 mai
            date('Y-m-d', mktime(0, 0, 0, 7,  14, $year)), // 14 juillet
            date('Y-m-d', mktime(0, 0, 0, 8,  15, $year)), // 15 août
            date('Y-m-d', mktime(0, 0, 0, 11, 1,  $year)), // 1e nov
            date('Y-m-d', mktime(0, 0, 0, 11, 11, $year)), // 11 nov
            date('Y-m-d', mktime(0, 0, 0, 12, 25, $year)), // noel

            // Dates variables
            date('Y-m-d',mktime(0, 0, 0, $easterMonth, $easterDay + 1,  $easterYear)),
            date('Y-m-d',mktime(0, 0, 0, $easterMonth, $easterDay + 39, $easterYear)),
            date('Y-m-d',mktime(0, 0, 0, $easterMonth, $easterDay + 50, $easterYear)),
        );

        sort($publicHolidays);

        return $publicHolidays;
    }


    public function getPublicHolidaysOnTheseTwoYears()
    {

        $publicHolidayOfCurrentYear = $this->getPublicHolidaysOfThisYear($year = null);
        $publicHolidayOfNextYear = $this->getPublicHolidaysofNextYear($year = null);

        $result = array_merge($publicHolidayOfCurrentYear, $publicHolidayOfNextYear);

        return $result;

    }

    public function checkIsHoliday(\DateTime $day)
    {
        $publicHolidays = $this->getPublicHolidaysOfThisYear($year = null);

        if (in_array($day->format('Y-m-d'), $publicHolidays)) {
            return true;
        } else {
            return false;
        }
    }
}