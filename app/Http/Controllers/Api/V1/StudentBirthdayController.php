<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentBirthdayController extends Controller
{
    /**
     * Upcoming birthdays (next 30 days) among the authenticated student's
     * batchmates. Deliberately returns a curated field set rather than raw
     * Student models — unlike the institute-facing equivalent, this is
     * visible to a fellow student and must not leak contact info, fees, or
     * the fcm_token/otp columns.
     */
    public function index(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Student)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $student = $request->user();

        if (!$student->batch_id) {
            return response()->json(['status' => 'success', 'data' => []]);
        }

        $today = Carbon::today();

        $batchmates = Student::where('batch_id', $student->batch_id)
            ->whereNotNull('dob')
            ->get()
            ->filter(function ($s) use ($today) {
                $birthdayThisYear = $this->nextOccurrence($s->dob, $today);
                return $birthdayThisYear !== null && $birthdayThisYear->diffInDays($today) <= 30;
            })
            ->sortBy(fn ($s) => $this->nextOccurrence($s->dob, $today)->timestamp)
            ->values()
            ->map(fn ($s) => [
                'id' => $s->id,
                'name' => $s->name,
                'profile_image_url' => $s->profile_image_url,
                'dob' => $s->dob,
                'is_birthday_today' => $s->is_birthday_today,
                'is_me' => $s->id === $student->id,
            ]);

        return response()->json([
            'status' => 'success',
            'data' => $batchmates,
        ]);
    }

    /**
     * This year's occurrence of $dob's month/day, rolled to next year if
     * it already passed. Null if $dob can't be parsed.
     */
    private function nextOccurrence(?string $dob, Carbon $today): ?Carbon
    {
        try {
            $occurrence = Carbon::parse($dob)->year($today->year);
        } catch (\Exception $e) {
            return null;
        }

        if ($occurrence->isPast() && !$occurrence->isToday()) {
            $occurrence->addYear();
        }

        return $occurrence;
    }
}
