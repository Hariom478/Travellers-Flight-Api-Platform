<?php

/**
 * This file is part of CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace CodeIgniter\Validation;
use InvalidArgumentException;
class CustomValidationRule
{
   /**
     * Checks for a check past   date and matches a given date 
     *  @param mixed $str
     */
    public function check_past_date(?string $str=null): bool
    {

        if ($str === null) {
            return false;
        }
date_default_timezone_set("UTC");
        $todayDate =  strtotime(date('Y-m-d'));
        $Date =   strtotime($str);
       return $Date>=$todayDate;

    }
   /**
     * Checks for a check past   Time and matches a given Time 
     *  @param mixed $str
     */
    public function check_past_time(?string $str=null): bool
    {
        if ($str === null) {
            return false;
        }
        date_default_timezone_set("UTC");

        $todayDate =  strtotime(date('Y-m-d'));
        $Date =   strtotime($str);
       return $Date>=$todayDate;

    }
}
?>