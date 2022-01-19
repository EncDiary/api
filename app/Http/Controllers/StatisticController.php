<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Note;

class StatisticController extends BaseController
{
  public function getStatsMonth(Request $request) {
    $year = $request->input('year');
    $month = $request->input('month');
    $startDate = $year . '-' . $month . '-01';
    $endDate = $year . '-' . ($month + 1) . '-01';

    $users = User::whereBetween('time_added', [$startDate, $endDate])->get();
    $notes = Note::whereBetween('time_added', [$startDate, $endDate])->get();

    return [
      'users' => self::getStat($users, $startDate, $endDate, 'd.m'),
      'notes' => self::getStat($notes, $startDate, $endDate, 'd.m')
    ];
  }


  public function getStatsYear(Request $request) {
    $year = $request->input('year');
    $startDate = $year . '-01-01';
    $endDate = ($year + 1) . '-01-01';

    $users = User::whereBetween('time_added', [$startDate, $endDate])->get();
    $notes = Note::whereBetween('time_added', [$startDate, $endDate])->get();

    return [
      'users' => self::getStat($users, $startDate, $endDate, 'm.Y'),
      'notes' => self::getStat($notes, $startDate, $endDate, 'm.Y')
    ];
  }


  private static function getStat($items, $startDate, $endDate, $dateFormat) {
    $stat = array();
    foreach ($items as $item) {
      if (isset($stat[date($dateFormat, strtotime($item->time_added))])) {
        $stat[date($dateFormat, strtotime($item->time_added))]++;
      } else {
        $stat[date($dateFormat, strtotime($item->time_added))] = 1;
      }
    }
    return $stat;
  }
}
