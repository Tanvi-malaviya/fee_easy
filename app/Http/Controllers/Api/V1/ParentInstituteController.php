<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\StudentParent;
use Illuminate\Http\Request;

class ParentInstituteController extends Controller
{
    /**
     * GET /api/v1/parent/institute
     *
     * Returns the institute info for parent's child — including UPI payment
     * details so parent can scan QR / copy UPI ID to pay fee directly.
     */
    public function show(Request $request)
    {
        if (!$request->user() || !($request->user() instanceof StudentParent)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $parent = $request->user();

        // Get institute via first child (all children belong to same institute)
        $student = $parent->students()->first();

        if (!$student || !$student->institute) {
            return response()->json(['status' => 'error', 'message' => 'Institute not found'], 404);
        }

        $institute = $student->institute;

        // Build full address string
        $addressParts = array_filter([
            $institute->address,
            $institute->address_line_2,
            $institute->city,
            $institute->state,
            $institute->pincode,
        ]);
        $fullAddress = implode(', ', $addressParts);

        // Initials for avatar fallback
        $words    = explode(' ', trim($institute->institute_name ?? $institute->name ?? ''));
        $initials = strtoupper(implode('', array_map(fn($w) => $w[0] ?? '', array_slice($words, 0, 2))));

        return response()->json([
            'status' => 'success',
            'data'   => [
                'id'             => $institute->id,
                'name'           => $institute->institute_name ?? $institute->name,
                'initials'       => $initials,
                'logo_url'       => $institute->logo_url,
                'contact_person' => $institute->name,
                'phone'          => $institute->phone,
                'email'          => $institute->email,
                'website'        => $institute->website ?? null,
                'location'       => [
                    'address'      => $institute->address,
                    'address_2'    => $institute->address_line_2 ?? null,
                    'city'         => $institute->city,
                    'state'        => $institute->state,
                    'country'      => $institute->country ?? 'India',
                    'pincode'      => $institute->pincode,
                    'full_address' => $fullAddress,
                ],
                'social'         => [
                    'youtube'   => $institute->youtube ?? null,
                    'instagram' => $institute->instagram ?? null,
                ],
                'payment'        => [
                    'upi_id'           => $institute->upi_id ?? null,
                    'upi_qr_code_url'  => $institute->upi_qr_code_url ?? null,
                    // Deep link: open GPay/PhonePe/BHIM directly with pre-filled UPI ID
                    'upi_payment_link' => $institute->upi_id
                        ? 'upi://pay?pa=' . urlencode($institute->upi_id) . '&pn=' . urlencode($institute->institute_name ?? $institute->name) . '&cu=INR'
                        : null,
                ],
            ],
        ]);
    }
}
