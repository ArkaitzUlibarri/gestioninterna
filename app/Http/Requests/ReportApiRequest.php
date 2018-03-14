<?php

namespace App\Http\Requests;

use App\WorkingReport;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ReportApiRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id'          => 'required|numeric',
            'created_at'       => 'required|date|before:' . Carbon::tomorrow()->toDateString() . '|date_format:Y-m-d',
            'activity'         => 'required|' . Rule::in(WorkingReport::ACTIVITIES),
            'project_id'       => 'required_if:activity,project|numeric|nullable',
            'group_id'         => 'required_if:activity,project|numeric|nullable',
            'category_id'      => 'required_if:activity,project|numeric|nullable',
            'training_type'    => 'required_if:activity,training|nullable|' . Rule::in(config('options.training')),
            'course_group_id'  => 'nullable|numeric',
            'absence_id'       => 'required_if:activity,absence|numeric|nullable',
            'time_slots'       => 'required|numeric',
            'job_type'         => 'nullable|' . Rule::in(config('options.typeOfJob')),
            'comments'         => 'nullable|string',
            'pm_validation'    => 'required|boolean',
            'admin_validation' => 'required|boolean'
        ];
    }
}
