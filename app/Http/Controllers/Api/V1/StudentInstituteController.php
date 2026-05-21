<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentInstituteController extends Controller
{
    /**
     * GET /api/v1/student/institute
     *
     * Returns the student's institute profile — name, logo, contact, address.
     * Used for the Institute screen showing institute info + chat + location.
     */
    public function show(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof Student)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $student   = $request->user();
        $institute = $student->institute;

        if (!$institute) {
            return response()->json(['status' => 'error', 'message' => 'Institute not found'], 404);
        }

        // Build full address string
        $addressParts = array_filter([
            $institute->address,
            $institute->address_line_2,
            $institute->city,
            $institute->state,
            $institute->pincode,
        ]);
        $fullAddress = implode(', ', $addressParts);

        // Initials for avatar fallback (e.g. "Saraswati Coaching Centre" → "SC")
        $words    = explode(' ', trim($institute->institute_name ?? $institute->name ?? ''));
        $initials = strtoupper(implode('', array_map(fn($w) => $w[0] ?? '', array_slice($words, 0, 2))));

        return response()->json([
            'status' => 'success',
            'data'   => [
                'id'             => $institute->id,
                'name'           => $institute->institute_name ?? $institute->name,
                'initials'       => $initials,
                'logo_url'       => $institute->logo_url,          // null if no logo → show initials
                'contact_person' => $institute->name,              // owner/admin name
                'phone'          => $institute->phone,
                'email'          => $institute->email,
                'website'        => $institute->website ?? null,
                'location'       => [
                    'address'     => $institute->address,
                    'address_2'   => $institute->address_line_2 ?? null,
                    'city'        => $institute->city,
                    'state'       => $institute->state,
                    'country'     => $institute->country ?? 'India',
                    'pincode'     => $institute->pincode,
                    'full_address' => $fullAddress,
                ],
                'social'         => [
                    'youtube'   => $institute->youtube ?? null,
                    'instagram' => $institute->instagram ?? null,
                ],
            ],
        ]);
    }
}
