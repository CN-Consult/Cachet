<?php

/*
 * This file is part of Cachet.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CachetHQ\Cachet\Composers;

use CachetHQ\Cachet\Integrations\Core\System;
use CachetHQ\Cachet\Models\Component;
use CachetHQ\Cachet\Models\ComponentGroup;
use CachetHQ\Cachet\Models\Downtime;
use CachetHQ\Cachet\Models\Incident;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;

/**
 * This is the status page composer.
 *
 * @author James Brooks <james@alt-three.com>
 */
class StatusPageComposer
{
    /**
     * The system instance.
     *
     * @var \CachetHQ\Cachet\Integrations\Contracts\System
     */
    protected $system;

    /**
     * Create a new status page composer instance.
     *
     * @param \CachetHQ\Cachet\Integrations\Contracts\System $system
     *
     * @return void
     */
    public function __construct(System $system)
    {
        $this->system = $system;
    }

    /**
     * Index page view composer.
     *
     * @param \Illuminate\Contracts\View\View $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        $status = $this->system->getStatus();

        // Scheduled maintenance code.
        $scheduledMaintenance = Incident::scheduled()->orderBy('scheduled_at')->get();

        // Component & Component Group lists.
        $usedComponentGroups = Component::enabled()->where('group_id', '>', 0)->groupBy('group_id')->pluck('group_id');
        $componentGroups = ComponentGroup::whereIn('id', $usedComponentGroups)->orderBy('order')->get();
        $ungroupedComponents = Component::enabled()->where('group_id', 0)->orderBy('order')->orderBy('created_at')->get();

        // Downtime stats for uptime table
        $availability = array();


        $now = Carbon::now();
        $startOfDay=clone $now;
        $startOfDay->setTime(0,0,0);
        $endOfDay=clone $now;
        $endOfDay->setTime(23,59,59);
        $rangeToday = array();
        array_push($rangeToday, $startOfDay);
        array_push($rangeToday, $endOfDay);

        //Today
        $downtimeCollectionToday = Downtime::whereBetween('created_at', [$startOfDay, $endOfDay])
                                            ->orWhereBetween('resolved_at', [$startOfDay, $endOfDay])
                                            ->get();
        array_push($availability, $this->calculateAvailability($downtimeCollectionToday, $rangeToday));


        // Last week
        $last7Days = array();
        $last7Days[0] = date("Y-m-d", strtotime("7 days ago"));
        $last7Days[1] = $endOfDay;
        $downtimeCollectionLastWeek = Downtime::whereBetween('created_at', [$last7Days[0], $last7Days[1]])
                                                ->orWhereBetween('resolved_at', [$last7Days[0], $last7Days[1]])
                                                ->get();
        array_push($availability, $this->calculateAvailability($downtimeCollectionLastWeek, $last7Days));

        // Last month
        $last30Days = array();
        $last30Days[0] = date("Y-m-d", strtotime("30 days ago"));
        $last30Days[1] = $endOfDay;
        $downtimeCollectionLastMonth = Downtime::whereBetween('created_at', [$last30Days[0], $last30Days[1]])
                                                ->orWhereIn('resolved_at', [$last30Days[0], $last30Days[1]])
                                                ->get();

        array_push($availability, $this->calculateAvailability($downtimeCollectionLastMonth, $last30Days));

        // Current year
        $currentYear = array();
        $currentYear[0] = date("Y-m-d", strtotime("first day of january this year"));
        $currentYear[1] = $endOfDay;
        $downtimeCollectionCurrentYear = Downtime::where('created_at', "like", date("Y")."-%")
                                                ->orWhere('resolved_at', "like", date("Y")."-%")
                                                ->get();
        array_push($availability, $this->calculateAvailability($downtimeCollectionCurrentYear, $currentYear));

        // Last year
        $lastYear = array();
        $lastYear[0] = date("Y-m-d", strtotime("first day of january last year"));
        $lastYear[1] = date("Y-m-d", strtotime("last day of december last year"));
        $downtimeCollectionLastYear = Downtime::where('created_at', "like", date("Y", strtotime("-1 year"))."-%")
                                                ->orWhere('resolved_at', "like", date("Y", strtotime("-1 year"))."-%")
                                                ->get();
        array_push($availability, $this->calculateAvailability($downtimeCollectionLastYear, $lastYear));


        $view->with($status)
            ->withComponentGroups($componentGroups)
            ->withUngroupedComponents($ungroupedComponents)
            ->withScheduledMaintenance($scheduledMaintenance)
            ->withUptimeStats($availability);
    }

    private function calculateAvailability($_downtimeCollection, $_range)
    {
        $availability = 100;

        $start = Carbon::parse($_range[0]);
        $end = Carbon::parse($_range[1]);

        $daysInRange = $start->diffInDays($end);


        foreach ($_downtimeCollection as $downtime)
        {
            // The begin of the downtime is older than today
            if ($downtime->created_at < $start) $downtime->created_at = $start;

            // The downtime isn't resolved yet so we use the downtime till now
            if ($downtime->resolved_at === null) $downtime->resolved_at = Carbon::now();
            // The end of the downtime reaches further than today (This shouldn't be possible but i keep it if someone makes a mistake)
            else if ($downtime->resolved_at > $end) $downtime->resolved_at = $end;

            if (!$downtime->resolved_at instanceof Carbon) $downtime->resolved_at = Carbon::createFromFormat("Y-m-d H:i:s", $downtime->resolved_at);
            $diffInSec = $downtime->resolved_at->timestamp - $downtime->created_at->timestamp;

            if ($daysInRange === 0) $downtimePercentage = $diffInSec*100/(24*60*60); // $downtimePercentage for today
            else $downtimePercentage = $diffInSec*100/(24*60*60*$daysInRange); // $downtimePercentage for longer time spans

            $availability = round($availability-$downtimePercentage, 1);
        }

        return $availability."%";
    }
}
