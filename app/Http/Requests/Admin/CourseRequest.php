<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'title'           => 'required|string|max:255',
            'category_id'     => 'required|exists:categories,id',
            'price'           => 'required|numeric|min:0',
            'status'          => 'required|boolean',
            'live_class_link' => 'required_if:live_class,1|nullable|url',
            'image'           => $this->isMethod('POST') ? 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' : 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video'           => 'nullable|mimes:mp4,mov,ogg,qt|max:20000',
        ];

        // Include Study Material validation
        if ($this->include_material == 1) {
            $rules['materials'] = 'required|array|min:1';
            $rules['materials.*.title'] = 'required|string|max:255';
            $rules['materials.*.type'] = 'required|in:file,url';
            $rules['materials.*.material_type'] = 'required|in:video,pdf,doc,url,other';
            
            if ($this->isMethod('POST')) {
                $rules['materials.*.file'] = 'required_if:materials.*.type,file|nullable|file|max:102400';
            } else {
                $rules['materials.*.file'] = 'nullable|file|max:102400';
            }
            
            $rules['materials.*.url'] = 'required_if:materials.*.type,url|nullable|url';
        }

        // Include Quiz validation
        if ($this->include_quiz == 1) {
            $rules['quizzes'] = 'required|array|min:1';
            $rules['quizzes.*.title'] = 'required|string|max:255';
            $rules['quizzes.*.duration'] = 'required|integer|min:1';

            $rules['quizzes.*.questions'] = 'required|array|min:1';
            $rules['quizzes.*.questions.*.text'] = 'required|string';
            $rules['quizzes.*.questions.*.options'] = 'required|array|size:4';
            $rules['quizzes.*.questions.*.options.*'] = 'required|string';
            $rules['quizzes.*.questions.*.correct'] = 'required|in:1,2,3,4';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Course Title is required.',
            'category_id.required' => 'Please select a Category.',
            'category_id.exists' => 'The selected Category is invalid.',
            'price.required' => 'Course Price is required.',
            'price.numeric' => 'Price must be a number.',
            'status.required' => 'Please select the Course Status.',
            'live_class_link.required_if' => 'Live Class Link is required when Live Class is enabled.',
            'live_class_link.url' => 'Live Class Link must be a valid URL.',
            
            'materials.required' => 'Please add at least one Study Material.',
            'materials.*.title.required' => 'Study Material title is required.',
            'materials.*.type.required' => 'Study Material type is required.',
            'materials.*.material_type.required' => 'Please select a content type (Video/PDF/Doc).',
            'materials.*.file.required_if' => 'Study Material file is required.',
            'materials.*.url.required_if' => 'Study Material URL is required.',
            'materials.*.url.url' => 'Study Material URL must be a valid URL.',
            
            'quizzes.required' => 'Please add at least one Quiz.',
            'quizzes.*.title.required' => 'Quiz title is required.',
            'quizzes.*.duration.required' => 'Quiz duration is required.',
            'quizzes.*.questions.required' => 'Quiz must have at least one question.',
            'quizzes.*.questions.*.text.required' => 'Question text is required.',
            'quizzes.*.questions.*.options.required' => 'All four options are required for the question.',
            'quizzes.*.questions.*.options.*.required' => 'Option text cannot be empty.',
            'quizzes.*.questions.*.correct.required' => 'Correct answer is required for the question.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'Course Title',
            'category_id' => 'Category',
            'price' => 'Price',
            'status' => 'Status',
            'materials.*.title' => 'Material Title',
            'materials.*.type' => 'Material Type',
            'materials.*.file' => 'Material File',
            'materials.*.url'  => 'Material URL',
            'quizzes.*.title' => 'Quiz Title',
            'quizzes.*.duration' => 'Quiz Duration',
            'quizzes.*.questions.*.text' => 'Question',
            'quizzes.*.questions.*.options' => 'Question Options',
            'quizzes.*.questions.*.correct' => 'Correct Answer',
        ];
    }
}
