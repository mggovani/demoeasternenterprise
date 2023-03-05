<?php

namespace App\Services;

use App\Models\User;
use App\Models\Company;
use DB;

class UserDataImportService
{
    /**
    * Store imported json data to database.
    *
    * @param array $users
    */
    public function storeData(array $users = []): bool
    {
        try {
            if (count($users)) {
                DB::beginTransaction();

                foreach ($users['users'] as $user) {
                    $insertUser = new User();
                    $insertUser->import_id = $user['id'];
                    $insertUser->name = $user['name'];
                    $insertUser->age = $user['age'];
                    $insertUser->save();

                    $companyIds = [];
                    foreach ($user['companies'] as $company) {
                        $insertCompanyId = Company::insertGetId([
                            'import_id' => $company['id'],
                            'name' => $company['name'],
                            'started_at' => date('Y-m-d', strtotime($company['started_at'])),
                        ]);
                        $companyIds[] = $insertCompanyId;
                    }
                    $insertUser->companies()->attach($companyIds);
                }

                DB::commit();
            }

            return true;
        } catch (\Exception $e) {
            DB::rollback();
            report($e);

            return false;
        }
    }
}
