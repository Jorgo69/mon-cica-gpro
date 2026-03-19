<?php

namespace App\Actions\SubActivities;

use App\Models\Activity;
use App\DTOs\SubActivityDTO;
use Illuminate\Support\Facades\DB;

class UpdateSubActivityStatusAction
{
    public function execute(SubActivityDTO $dto): Activity
    {
        return DB::transaction(function () use ($dto) {
            $subActivity = Activity::findOrFail($dto->id);
            
            $subActivity->update([
                'status' => $dto->status,
            ]);

            return $subActivity;
        });
    }
}

