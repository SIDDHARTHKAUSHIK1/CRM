<?php

namespace Crm\Admin\Http\Controllers\Products;

use Illuminate\Http\Response;
use Crm\Activity\Repositories\ActivityRepository;
use Crm\Admin\Http\Controllers\Controller;
use Crm\Admin\Http\Resources\ActivityResource;
use Crm\Email\Repositories\EmailRepository;

class ActivityController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected ActivityRepository $activityRepository,
        protected EmailRepository $emailRepository
    ) {}

    /**
     * Display a listing of the resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function index($id)
    {
        $activities = $this->activityRepository
            ->leftJoin('product_activities', 'activities.id', '=', 'product_activities.activity_id')
            ->where('product_activities.product_id', $id)
            ->get();

        return ActivityResource::collection($this->concatEmail($activities));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function concatEmail($activities)
    {
        return $activities->sortByDesc('id')->sortByDesc('created_at');
    }
}
