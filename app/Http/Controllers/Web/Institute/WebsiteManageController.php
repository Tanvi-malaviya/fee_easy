<?php

namespace App\Http\Controllers\Web\Institute;

use App\Http\Controllers\Controller;
use App\Models\InstituteWebsiteContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebsiteManageController extends Controller
{
    /**
     * Show the website management panel.
     */
    public function index()
    {
        $institute = Auth::guard('institute')->user();
        
        // Find or create website content record for this institute
        $content = $institute->websiteContent;
        if (!$content) {
            $content = InstituteWebsiteContent::create([
                'institute_id' => $institute->id,
                'hero_slides' => [],
                'achievements' => [],
                'gallery' => [],
                'events' => [],
            ]);
        }

        $vision = $this->getNormalizedPillars($content, 'vision');
        $mission = $this->getNormalizedPillars($content, 'mission');
        $values = $this->getNormalizedPillars($content, 'values');

        return view('institute.website.manage', compact('institute', 'content', 'vision', 'mission', 'values'));
    }

    /**
     * Get normalized pillars array helper.
     */
    private function getNormalizedPillars($content, $type)
    {
        $defaults = [
            'vision' => [],
            'mission' => [],
            'values' => []
        ];

        $field = 'about_' . $type;
        $data = $content ? $content->$field : null;

        if (empty($data)) {
            return $defaults[$type] ?? [];
        }

        // Backward compatibility: check if it's a single object
        if (is_array($data) && !isset($data[0])) {
            return [
                [
                    'title' => $data['title'] ?? '',
                    'desc' => $data['desc'] ?? '',
                ]
            ];
        }

        if (is_array($data)) {
            $normalized = [];
            foreach ($data as $item) {
                if (!empty($item['title'])) {
                    $normalized[] = [
                        'title' => $item['title'],
                        'desc' => $item['desc'] ?? '',
                    ];
                }
            }
            if (!empty($normalized)) {
                return $normalized;
            }
        }

        return $defaults[$type] ?? [];
    }

    /**
     * Update the active website template.
     */
    public function updateTemplate(Request $request)
    {
        $institute = Auth::guard('institute')->user();

        $validated = $request->validate([
            'template_id' => ['required', 'integer', 'between:1,5'],
        ]);

        $institute->update([
            'template_id' => $validated['template_id']
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Website template updated successfully.'
        ]);
    }

    /**
     * Save the website hero sliders.
     */
    public function saveHeroSlides(Request $request)
    {
        $institute = Auth::guard('institute')->user();
        $content = $institute->websiteContent ?: new InstituteWebsiteContent(['institute_id' => $institute->id]);

        $validated = $request->validate([
            'hero_slides' => ['required', 'array'],
            'hero_slides.*.image' => ['required', 'string'],
            'hero_slides.*.badge' => ['nullable', 'string', 'max:255'],
            'hero_slides.*.title' => ['required', 'string', 'max:255'],
            'hero_slides.*.desc' => ['nullable', 'string'],
        ]);

        $content->hero_slides = $validated['hero_slides'];
        $content->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Website hero slides saved successfully.'
        ]);
    }

    /**
     * Save the website pillars (Vision, Mission, Values).
     */
    public function savePillars(Request $request)
    {
        $institute = Auth::guard('institute')->user();
        $content = $institute->websiteContent ?: new InstituteWebsiteContent(['institute_id' => $institute->id]);

        $validated = $request->validate([
            'vision' => ['nullable', 'array'],
            'vision.*.title' => ['required', 'string', 'max:255'],
            'vision.*.desc' => ['required', 'string'],
            
            'mission' => ['nullable', 'array'],
            'mission.*.title' => ['required', 'string', 'max:255'],
            'mission.*.desc' => ['required', 'string'],
            
            'values' => ['nullable', 'array'],
            'values.*.title' => ['required', 'string', 'max:255'],
            'values.*.desc' => ['required', 'string'],
        ]);

        $content->about_vision = $validated['vision'] ?? [];
        $content->about_mission = $validated['mission'] ?? [];
        $content->about_values = $validated['values'] ?? [];
        $content->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Website Vision, Mission, and Values saved successfully.'
        ]);
    }

    /**
     * Save the achievements section content.
     */
    public function saveAchievements(Request $request)
    {
        $institute = Auth::guard('institute')->user();
        $content = $institute->websiteContent ?: new InstituteWebsiteContent(['institute_id' => $institute->id]);

        $validated = $request->validate([
            'badge'          => ['nullable', 'string', 'max:255'],
            'title'          => ['nullable', 'string', 'max:255'],
            'desc'           => ['nullable', 'string'],
            'items'          => ['nullable', 'array'],
            'items.*.tag'    => ['nullable', 'string', 'max:100'],
            'items.*.title'  => ['required', 'string', 'max:255'],
            'items.*.desc'   => ['nullable', 'string'],
        ]);

        $content->achievements = [
            'badge' => $validated['badge'] ?? '',
            'title' => $validated['title'] ?? '',
            'desc'  => $validated['desc']  ?? '',
            'items' => $validated['items'] ?? [],
        ];
        $content->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Achievements section saved successfully.'
        ]);
    }

    /**
     * Save the website gallery items.
     */
    public function saveGallery(Request $request)
    {
        $institute = Auth::guard('institute')->user();
        $content = $institute->websiteContent ?: new InstituteWebsiteContent(['institute_id' => $institute->id]);

        $validated = $request->validate([
            'gallery' => ['nullable', 'array', 'max:20'],
            'gallery.*.image' => ['required', 'string'],
            'gallery.*.tag' => ['nullable', 'string', 'max:255'],
            'gallery.*.title' => ['required', 'string', 'max:255'],
        ]);

        $content->gallery = $validated['gallery'] ?? [];
        $content->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Website gallery saved successfully.'
        ]);
    }

    /**
     * Upload an image for the website sliders.
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'], // max 5MB
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('website/hero', 'public');
            $url = asset('storage/' . $path);
            return response()->json([
                'status' => 'success',
                'url' => $url
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No file uploaded.'
        ], 400);
    }

    /**
     * Save the events calendar.
     */
    public function saveEvents(Request $request)
    {
        $institute = Auth::guard('institute')->user();
        $content = $institute->websiteContent ?: new InstituteWebsiteContent(['institute_id' => $institute->id]);

        $validated = $request->validate([
            'events' => ['nullable', 'array', 'max:20'],
            'events.*.day' => ['required', 'string', 'max:10'],
            'events.*.month' => ['required', 'string', 'max:20'],
            'events.*.year' => ['required', 'string', 'max:10'],
            'events.*.tag' => ['nullable', 'string', 'max:255'],
            'events.*.location' => ['nullable', 'string', 'max:255'],
            'events.*.title' => ['required', 'string', 'max:255'],
            'events.*.time' => ['nullable', 'string', 'max:255'],
            'events.*.desc' => ['nullable', 'string'],
            'events.*.speaker' => ['nullable', 'string', 'max:255'],
            'events.*.speaker_role' => ['nullable', 'string', 'max:255'],
            'events.*.occupancy' => ['nullable', 'string', 'max:255'],
        ]);

        $content->events = $validated['events'] ?? [];
        $content->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Events calendar saved successfully.'
        ]);
    }

    /**
     * Save footer social media links.
     */
    public function saveSocialLinks(Request $request)
    {
        $institute = Auth::guard('institute')->user();
        $content = $institute->websiteContent ?: new InstituteWebsiteContent(['institute_id' => $institute->id]);

        $validated = $request->validate([
            'facebook' => ['nullable', 'url', 'max:255'],
            'twitter' => ['nullable', 'url', 'max:255'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'instagram' => ['nullable', 'url', 'max:255'],
            'youtube' => ['nullable', 'url', 'max:255'],
        ]);

        $content->fill($validated);
        $content->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Social media links saved successfully.'
        ]);
    }
}
