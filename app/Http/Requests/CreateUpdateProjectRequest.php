<?php

namespace App\Http\Requests;

use App\Models\Group;
use App\Models\ProjectType;
use App\Models\Supervisor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'registration_number' => [
                'required', 'numeric',
                Rule::unique('projects', 'registration_number')
            ],
            'student' => [
                'between:3,255', 'required', 'string',
                'regex:/^[a-zA-Zа-яА-ЯіІґҐїЇ ]+(?:\s[a-zA-Zа-яА-ЯіІґҐїЇ ]+)+$/u'
            ],
            'theme' => [
                'between:3,255', 'required', 'string'
            ],
            'supervisor' => [
                'required',
                Rule::in(Supervisor::all()->map(function ($supervisor) {
                    return $supervisor->name;
                })),
            ],
            'group' => [
                'required',
                Rule::in(Group::all()->map(function ($group) {
                    return $group->name;
                })),
            ],
            'project_type' => [
                'required',
                Rule::in(ProjectType::query()->get('name')->map(function ($projectType) {
                    return $projectType->name;
                })),
            ],
            'registered_at' => [
                'required', 'date',
            ],
            'defended_at' => [
                'required', 'date',
            ],
            'grade' => [
                'required', 'numeric', 'between:60,100',
            ],
        ];
    }
}
