<?php

namespace Crm\Admin\Http\Controllers\Activity;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Crm\Activity\Repositories\ActivityRepository;
use Crm\Activity\Repositories\FileRepository;
use Crm\Admin\DataGrids\Activity\ActivityDataGrid;
use Crm\Admin\Http\Controllers\Controller;
use Crm\Admin\Http\Requests\MassDestroyRequest;
use Crm\Admin\Http\Requests\MassUpdateRequest;
use Crm\Admin\Http\Resources\ActivityResource;
use Crm\Attribute\Repositories\AttributeRepository;

class ActivityController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected ActivityRepository $activityRepository,
        protected FileRepository $fileRepository,
        protected AttributeRepository $attributeRepository,
    ) {}

    /**
     * Resolve the user ids that own or participate in the given activity (owner or participant).
     */
    private function activityOwnerIds($activity): array
    {
        return array_merge(
            [$activity->user_id],
            $activity->participants->pluck('user_id')->filter()->all()
        );
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $userIds = bouncer()->getAuthorizedUserIds();

        $activitiesQuery = \Crm\Activity\Models\Activity::query()
            ->leftJoin('lead_activities', 'activities.id', '=', 'lead_activities.activity_id')
            ->leftJoin('leads', 'lead_activities.lead_id', '=', 'leads.id')
            ->leftJoin('users', 'activities.user_id', '=', 'users.id')
            ->select(
                'activities.*',
                'leads.id as lead_id',
                'leads.title as lead_title',
                'users.name as user_name'
            )
            ->whereIn('activities.type', ['call', 'meeting', 'lunch', 'task', 'note']);

        if ($userIds) {
            $activitiesQuery->whereIn('activities.user_id', $userIds);
        }

        $activities = $activitiesQuery
            ->orderBy('activities.schedule_from', 'desc')
            ->orderBy('activities.id', 'desc')
            ->get();

        $leadsQuery = \Crm\Lead\Models\Lead::query()->where('status', 1);
        if ($userIds) {
            $leadsQuery->whereIn('leads.user_id', $userIds);
        }
        $leads = $leadsQuery->take(50)->get();

        $users = \Crm\User\Models\User::query()->where('status', 1)->get();

        $todayDate = now()->format('Y-m-d');
        $yesterdayDate = now()->subDay()->format('Y-m-d');

        $todayActivities = $activities->filter(function ($a) use ($todayDate) {
            return $a->schedule_from && str_starts_with($a->schedule_from, $todayDate);
        });

        $yesterdayActivities = $activities->filter(function ($a) use ($yesterdayDate) {
            return $a->schedule_from && str_starts_with($a->schedule_from, $yesterdayDate);
        });

        $upcomingActivities = $activities->filter(function ($a) use ($todayDate) {
            return $a->schedule_from && $a->schedule_from > ($todayDate . ' 23:59:59');
        });

        $todayCount = $todayActivities->count();
        $todayDoneCount = $todayActivities->where('is_done', 1)->count();
        $todayPendingCount = $todayActivities->where('is_done', 0)->count();
        $todayCallsCount = $todayActivities->where('type', 'call')->count();
        $todayFollowUpsCount = $todayActivities->whereIn('type', ['meeting', 'lunch', 'call'])->count();
        $toursCount = $activities->whereIn('type', ['meeting'])->count();
        $callsCount = $activities->where('type', 'call')->count();
        $totalCompletedCount = $activities->where('is_done', 1)->count();
        $totalActivitiesCount = $activities->count();

        $upcomingActivity = $activities->firstWhere('is_done', 0) ?? $activities->first();
        $upcomingTodayActivities = $todayActivities->sortBy('schedule_from')->take(3);

        $recentOrganizationsQuery = \Crm\Contact\Models\Organization::query()->latest();
        $recentPersonsQuery = \Crm\Contact\Models\Person::query()->latest();
        if ($userIds) {
            $recentOrganizationsQuery->whereIn('organizations.user_id', $userIds);
            $recentPersonsQuery->whereIn('persons.user_id', $userIds);
        }
        $recentOrganizations = $recentOrganizationsQuery->take(4)->get();
        $recentPersons = $recentPersonsQuery->take(4)->get();

        $calendarInitialMonth = now()->format('Y-m');
        $currentUserId = auth()->guard('user')->user()?->id;
        $calendarActivities = $activities->map(function ($a) {
            return [
                'id'            => $a->id,
                'title'         => $a->title,
                'type'          => $a->type,
                'comment'       => $a->comment,
                'location'      => $a->location,
                'is_done'       => (int) $a->is_done,
                'schedule_from' => $a->schedule_from,
                'schedule_to'   => $a->schedule_to,
                'lead_id'       => $a->lead_id,
                'lead_title'    => $a->lead_title,
                'user_id'       => $a->user_id,
                'user_name'     => $a->user_name,
            ];
        });

        return view('admin::activities.index', compact(
            'activities',
            'leads',
            'users',
            'currentUserId',
            'todayDate',
            'yesterdayDate',
            'todayActivities',
            'yesterdayActivities',
            'upcomingActivities',
            'upcomingTodayActivities',
            'recentOrganizations',
            'recentPersons',
            'todayCount',
            'todayDoneCount',
            'todayPendingCount',
            'todayCallsCount',
            'todayFollowUpsCount',
            'toursCount',
            'callsCount',
            'totalCompletedCount',
            'totalActivitiesCount',
            'upcomingActivity',
            'calendarInitialMonth',
            'calendarActivities'
        ));
    }

    /**
     * Fetch calendar activities for a given month with ±7 days buffer.
     */
    public function calendarEvents(): JsonResponse
    {
        $month = request()->get('month', now()->format('Y-m'));

        try {
            $carbonMonth = Carbon::createFromFormat('Y-m', $month);
        } catch (\Exception $e) {
            $carbonMonth = now();
        }

        $startDate = $carbonMonth->copy()->startOfMonth()->subDays(7)->format('Y-m-d 00:00:00');
        $endDate = $carbonMonth->copy()->endOfMonth()->addDays(14)->format('Y-m-d 23:59:59');

        $userIds = bouncer()->getAuthorizedUserIds();

        $activitiesQuery = \Crm\Activity\Models\Activity::query()
            ->leftJoin('lead_activities', 'activities.id', '=', 'lead_activities.activity_id')
            ->leftJoin('leads', 'lead_activities.lead_id', '=', 'leads.id')
            ->leftJoin('users', 'activities.user_id', '=', 'users.id')
            ->select(
                'activities.id',
                'activities.title',
                'activities.type',
                'activities.comment',
                'activities.location',
                'activities.is_done',
                'activities.schedule_from',
                'activities.schedule_to',
                'activities.user_id',
                'leads.id as lead_id',
                'leads.title as lead_title',
                'users.name as user_name'
            )
            ->whereIn('activities.type', ['call', 'meeting', 'lunch', 'task', 'note'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('activities.schedule_from', [$startDate, $endDate])
                      ->orWhereBetween('activities.created_at', [$startDate, $endDate]);
            });

        if ($userIds) {
            $activitiesQuery->whereIn('activities.user_id', $userIds);
        }

        $activities = $activitiesQuery
            ->orderBy('activities.schedule_from', 'asc')
            ->get();

        return response()->json([
            'status'     => true,
            'month'      => $carbonMonth->format('Y-m'),
            'month_name' => $carbonMonth->format('F Y'),
            'activities' => $activities,
        ]);
    }

    /**
     * Returns a listing of the resource.
     */
    public function get(): JsonResponse
    {
        if (request()->get('view_type') === 'calendar_events') {
            return $this->calendarEvents();
        }

        if (! request()->has('view_type')) {
            return datagrid(ActivityDataGrid::class)->process();
        }

        $startDate = request()->get('startDate')
            ? Carbon::createFromTimeString(request()->get('startDate').' 00:00:01')
            : Carbon::now()->startOfWeek()->format('Y-m-d H:i:s');

        $endDate = request()->get('endDate')
            ? Carbon::createFromTimeString(request()->get('endDate').' 23:59:59')
            : Carbon::now()->endOfWeek()->format('Y-m-d H:i:s');

        $activities = $this->activityRepository->getActivities([$startDate, $endDate])->toArray();

        return response()->json([
            'activities' => $activities,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): RedirectResponse|JsonResponse
    {
        $this->validate(request(), [
            'type' => 'required',
            'comment' => 'required_if:type,note',
            'schedule_from' => 'required_unless:type,note,file',
            'schedule_to' => 'required_unless:type,note,file',
            'file' => 'required_if:type,file',
        ]);

        if (request('type') === 'meeting') {
            /**
             * Check if meeting is overlapping with other meetings.
             */
            $isOverlapping = $this->activityRepository->isDurationOverlapping(
                request()->input('schedule_from'),
                request()->input('schedule_to'),
                request()->input('participants'),
                request()->input('id')
            );

            if ($isOverlapping) {
                if (request()->ajax()) {
                    return response()->json([
                        'message' => trans('admin::app.activities.overlapping-error'),
                    ], 400);
                }

                session()->flash('success', trans('admin::app.activities.overlapping-error'));

                return redirect()->back();
            }
        }

        Event::dispatch('activity.create.before');

        $activity = $this->activityRepository->create(array_merge(request()->all(), [
            'is_done' => request('type') == 'note' ? 1 : 0,
            'user_id' => auth()->guard('user')->user()->id,
        ]));

        $leadId = request()->input('lead_id');
        if (!empty($leadId)) {
            $activity->leads()->sync([$leadId]);
        }

        Event::dispatch('activity.create.after', $activity);

        $lead = !empty($leadId) ? \Crm\Lead\Models\Lead::find($leadId) : null;
        $user = auth()->guard('user')->user();

        $activityShape = [
            'id'            => $activity->id,
            'title'         => $activity->title,
            'type'          => $activity->type,
            'comment'       => $activity->comment,
            'location'      => $activity->location,
            'is_done'       => (int) $activity->is_done,
            'schedule_from' => $activity->schedule_from ? (string) $activity->schedule_from : null,
            'schedule_to'   => $activity->schedule_to ? (string) $activity->schedule_to : null,
            'lead_id'       => $lead?->id,
            'lead_title'    => $lead?->title,
            'user_id'       => $user?->id,
            'user_name'     => $user?->name,
        ];

        if (request()->ajax()) {
            return response()->json([
                'data'     => $activityShape,
                'activity' => $activityShape,
                'message'  => trans('admin::app.activities.create-success'),
            ]);
        }

        session()->flash('success', trans('admin::app.activities.create-success'));

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $activity = $this->activityRepository->findOrFail($id);

        $this->preventUnauthorizedAccess($this->activityOwnerIds($activity));

        $leadId = old('lead_id') ?? optional($activity->leads()->first())->id;

        $lookUpEntityData = $this->attributeRepository->getLookUpEntity('leads', $leadId);

        return view('admin::activities.edit', compact('activity', 'lookUpEntityData'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id): RedirectResponse|JsonResponse
    {
        $this->preventUnauthorizedAccess($this->activityOwnerIds($this->activityRepository->findOrFail($id)));

        Event::dispatch('activity.update.before', $id);

        $data = request()->all();

        $activity = $this->activityRepository->update($data, $id);

        /**
         * We will not use `empty` directly here because `lead_id` can be a blank string
         * from the activity form. However, on the activity view page, we are only updating the
         * `is_done` field, so `lead_id` will not be present in that case.
         */
        if (isset($data['lead_id'])) {
            $activity->leads()->sync(
                ! empty($data['lead_id'])
                    ? [$data['lead_id']]
                    : []
            );
        }

        Event::dispatch('activity.update.after', $activity);

        if (request()->ajax()) {
            return response()->json([
                'data' => new ActivityResource($activity),
                'message' => trans('admin::app.activities.update-success'),
            ]);
        }

        session()->flash('success', trans('admin::app.activities.update-success'));

        return redirect()->route('admin.activities.index');
    }

    /**
     * Mass Update the specified resources.
     */
    public function massUpdate(MassUpdateRequest $massUpdateRequest): JsonResponse
    {
        $activities = $this->filterAuthorizedRecords(
            $this->activityRepository->findWhereIn('id', $massUpdateRequest->input('indices')),
            fn ($activity) => $this->activityOwnerIds($activity)
        );

        foreach ($activities as $activity) {
            Event::dispatch('activity.update.before', $activity->id);

            $activity = $this->activityRepository->update([
                'is_done' => $massUpdateRequest->input('value'),
            ], $activity->id);

            Event::dispatch('activity.update.after', $activity);
        }

        return response()->json([
            'message' => trans('admin::app.activities.mass-update-success'),
        ]);
    }

    /**
     * Download file from storage.
     */
    public function download(int $id): StreamedResponse
    {
        $file = $this->fileRepository->findOrFail($id);

        $this->preventUnauthorizedAccess($this->activityOwnerIds($file->activity));

        try {
            return Storage::download($file->path);
        } catch (\Exception $exception) {
            abort(404);
        }
    }

    /*
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $activity = $this->activityRepository->findOrFail($id);

        $this->preventUnauthorizedAccess($this->activityOwnerIds($activity));

        try {
            Event::dispatch('activity.delete.before', $id);

            $activity?->delete($id);

            Event::dispatch('activity.delete.after', $id);

            return response()->json([
                'message' => trans('admin::app.activities.destroy-success'),
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => trans('admin::app.activities.destroy-failed'),
            ], 400);
        }
    }

    /**
     * Mass Delete the specified resources.
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $activities = $this->filterAuthorizedRecords(
            $this->activityRepository->findWhereIn('id', $massDestroyRequest->input('indices')),
            fn ($activity) => $this->activityOwnerIds($activity)
        );

        try {
            foreach ($activities as $activity) {
                Event::dispatch('activity.delete.before', $activity->id);

                $this->activityRepository->delete($activity->id);

                Event::dispatch('activity.delete.after', $activity->id);
            }

            return response()->json([
                'message' => trans('admin::app.activities.mass-destroy-success'),
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => trans('admin::app.activities.mass-delete-failed'),
            ], 400);
        }
    }
}
