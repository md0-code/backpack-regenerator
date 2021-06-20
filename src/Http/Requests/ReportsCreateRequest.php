<?php

namespace MD0\BackpackReGenerator\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class ReportsCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'sometimes|unique:reports,name|alpha_dash|min:3|max:255',
            'title' => 'nullable|min:3|max:255',
            'report_type' => 'required',
            'tag' => 'nullable|alpha_dash|min:3|max:255',
            'db_name' => 'nullable|alpha_dash|min:3|max:255',
            'sql_query' => 'required',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => __('Name'),
            'title' => __('Title'),
            'report_type' => __('Report type'),
            'tag' => __('Tag / Group'),
            'db_name' => __('Database name'),
            'sql_query' => __('SQL Query')
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
