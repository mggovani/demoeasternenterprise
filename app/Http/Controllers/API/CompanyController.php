<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Validator;

class CompanyController extends ApiResponseController
{
	/**
	* Display a listing of the resource.
	*
    * @param  Request  $request
	* @return JsonResponse
	*/
    public function index(Request $request): JsonResponse
    {
        try {
            $rules = [
                'max_age' => 'sometimes|integer',
                'min_age' => 'sometimes|integer',
                'year' => 'sometimes|digits:4|integer|min:1900|max:'.date('Y'),
                'per_page' => 'sometimes|integer',
            ];
            $validator = Validator::make($request->all(), $rules);

            if (!$validator->passes()) {
                return $this->errorResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $records = $request->has('per_page') ? $request->get('per_page') : 20;

            $companies = Company::query();

            if ($request->has('year')) {
            	$companies = $companies->whereYear('started_at', '=', $request->get('year'));
            }

            $companies = $companies->whereHas('users', function ($query) use ($request) {

                if ($request->has('min_age') && $request->has('max_age')) {
                    $query->whereBetween('age', [$request->get('min_age'), $request->get('max_age')]);
                } elseif ($request->has('min_age')) {
                    $query->where('age', '>=', $request->get('min_age'));
                } elseif ($request->has('max_age')) {
                    $query->where('age', '<=', $request->get('max_age'));
                }

            })->paginate($records);

            $data = CompanyResource::collection($companies);

            return $this->successResponse($data->response()->getData());
        } catch (\Exception $e) {
            report($e);

            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }
}
