<?php

namespace Crm\Admin\Helpers\Reporting;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Crm\Contact\Repositories\PersonRepository;

class Person extends AbstractReporting
{
    /**
     * Create a helper instance.
     *
     * @return void
     */
    public function __construct(protected PersonRepository $personRepository)
    {
        parent::__construct();
    }

    /**
     * Retrieves total persons and their progress.
     */
    public function getTotalPersonsProgress(): array
    {
        return [
            'previous' => $previous = $this->getTotalPersons($this->lastStartDate, $this->lastEndDate),
            'current' => $current = $this->getTotalPersons($this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves total persons by date
     *
     * @param  Carbon  $startDate
     * @param  Carbon  $endDate
     */
    public function getTotalPersons($startDate, $endDate): int
    {
        $query = $this->personRepository
            ->resetModel()
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($userIds = bouncer()->getAuthorizedUserIds()) {
            $query->whereIn('persons.user_id', $userIds);
        }

        return $query->count();
    }

    /**
     * Gets top customers by revenue.
     *
     * @param  int  $limit
     */
    public function getTopCustomersByRevenue($limit = null): Collection
    {
        $tablePrefix = DB::getTablePrefix();

        $query = $this->personRepository
            ->resetModel()
            ->leftJoin('leads', 'persons.id', '=', 'leads.person_id')
            ->select('*', 'persons.id as id')
            ->addSelect(DB::raw('SUM('.$tablePrefix.'leads.lead_value) as revenue'))
            ->whereBetween('leads.closed_at', [$this->startDate, $this->endDate]);

        if ($userIds = bouncer()->getAuthorizedUserIds()) {
            $query->whereIn('persons.user_id', $userIds);
        }

        $items = $query->having(DB::raw('SUM('.$tablePrefix.'leads.lead_value)'), '>', 0)
            ->groupBy('person_id')
            ->orderBy('revenue', 'DESC')
            ->limit($limit)
            ->get();

        $items = $items->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'emails' => $item->emails,
                'contact_numbers' => $item->contact_numbers,
                'revenue' => $item->revenue,
                'formatted_revenue' => core()->formatBasePrice($item->revenue),
            ];
        });

        return $items;
    }
}
