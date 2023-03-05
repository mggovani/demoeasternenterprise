<?php

namespace App\Http\Controllers\API;

use App\Services\UserDataImportService;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use File;
use Validator;

class UserDataImportController extends ApiResponseController
{
    /**
    * Import JSON file stored in public, named challenge.json.
    *
    * @param  UserDataImportService  $userDataImportService
    * @return JsonResponse
    */
    public function importData(UserDataImportService $userDataImportService): JsonResponse
    {
        $file  = File::get(public_path('challenge.json'));
        $data  = json_decode($file, true);
        $users = (array) $data;
        $rules = [
            'users.*.id' => 'nullable',
            'users.*.name' => 'required|max:255',
            'users.*.age' => 'required|integer',
            'users.*.companies.*.id' => 'nullable',
            'users.*.companies.*.name' => 'required|max:255',
            'users.*.companies.*.started_at' => 'required|date_format:Y-m-d',
        ];
        $validator = Validator::make($data, $rules);

        if (!$validator->passes()) {
            return $this->errorResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = $userDataImportService->storeData($users);

        if($data) {
            return $this->successResponse($users, trans('messages.import_successful'));
        } else {
            return $this->errorResponse(Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
