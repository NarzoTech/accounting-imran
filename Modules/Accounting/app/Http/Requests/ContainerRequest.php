<?php

namespace Modules\Accounting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContainerRequest extends FormRequest
{
    public function rules(): array
    {
        // Get current container id from route if exists (for update)
        $containerId = $this->route('container')?->id;

        return [
            'container_number' => [
                'required',
                Rule::unique('containers', 'container_number')->ignore($containerId),
            ],
            'container_type' => 'required|string',
            'shipping_line' => 'nullable|string',
            'port_of_loading' => 'nullable|string',
            'port_of_discharge' => 'nullable|string',
            'estimated_departure' => 'nullable|date',
            'estimated_arrival' => 'nullable|date',
            'actual_arrival' => 'nullable|date',
            'remarks' => 'nullable|string',
            'status' => ['required', Rule::in(['Pending', 'In Transit', 'Arrived', 'Cleared', 'Delivered'])],
            'products' => ['required', 'array', 'min:1'],
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'container_number.required' => __('Container number is required.'),
            'container_number.unique' => __('Container number must be unique.'),
            'status.required' => __('Status is required.'),
            'products.*.product_id.required' => __('Product is required.'),
            'products.*.quantity.required' => __('Quantity is required.'),
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
