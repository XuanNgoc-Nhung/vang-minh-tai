<?php

namespace App\Helpers;

class TimeHelper
{
    /**
     * Chuyển đổi thời gian từ tháng sang đơn vị phù hợp
     * 
     * @param int $months Số tháng
     * @return array Mảng chứa ['value' => số, 'unit' => đơn vị, 'display' => chuỗi hiển thị]
     */
    public static function convertHoursToAppropriateUnit($months)
    {
        if ($months < 12) {
            return [
                'value' => $months,
                'unit' => 'tháng',
                'display' => $months . ' tháng'
            ];
        }
        
        // Chuyển sang năm khi >= 12 tháng
        $years = floor($months / 12);
        $remainingMonths = $months % 12;
        
        if ($remainingMonths == 0) {
            return [
                'value' => $years,
                'unit' => 'năm',
                'display' => $years . ' năm'
            ];
        } else {
            return [
                'value' => $years,
                'unit' => 'năm',
                'display' => $years . ' năm ' . $remainingMonths . ' tháng'
            ];
        }
    }
    
    /**
     * Chuyển đổi thời gian từ tháng sang đơn vị phù hợp cho hiển thị
     * 
     * @param int $months Số tháng
     * @return string Chuỗi hiển thị thời gian
     */
    public static function formatTimeFromHours($months)
    {
        $converted = self::convertHoursToAppropriateUnit($months);
        return $converted['display'];
    }
    
    /**
     * Tính tổng thời gian cho nhiều chu kỳ
     * 
     * @param int $monthsPerCycle Số tháng mỗi chu kỳ
     * @param int $cycles Số chu kỳ
     * @return string Chuỗi hiển thị tổng thời gian
     */
    public static function formatTotalTimeForCycles($monthsPerCycle, $cycles)
    {
        $totalMonths = $monthsPerCycle * $cycles;
        return self::formatTimeFromHours($totalMonths);
    }
    
    /**
     * Format thời gian từ tháng
     * 
     * @param int $months Số tháng
     * @return string Chuỗi hiển thị thời gian
     */
    public static function formatTimeFromMonths($months)
    {
        if ($months < 1) {
            return '0 tháng';
        }
        
        if ($months < 12) {
            return $months . ' tháng';
        }
        
        $years = floor($months / 12);
        $remainingMonths = $months % 12;
        
        if ($remainingMonths == 0) {
            return $years . ' năm';
        } else {
            return $years . ' năm ' . $remainingMonths . ' tháng';
        }
    }
}