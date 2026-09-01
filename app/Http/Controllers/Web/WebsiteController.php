<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Institute;
use Illuminate\Support\Str;

class WebsiteController extends Controller
{
    /**
     * Show the active website template for the given institute.
     *
     * URL: /{institute_code}/{institute_name_slug}
     *
     * The institute_name_slug is derived from the institute_name field
     * using Str::slug(). We validate it to ensure the URL is canonical,
     * but we match solely on institute_code so the route still works even
     * if the institute name contains special/unicode characters.
     */
    public function show(string $instituteCode, string $nameSlug)
    {
        // Find the institute by its unique code (allow null template_id)
        $institute = Institute::where('institute_code', $instituteCode)->first();

        if (!$institute) {
            abort(404, 'Institute website not found.');
        }

        // If institute hasn't chosen a template yet, show setup page
        if (!$institute->template_id) {
            return view('website_templates.no_template_setup', compact('institute'));
        }

        // Validate that the name slug in the URL matches the institute name
        $expectedSlug = Str::slug($institute->institute_name);
        if ($nameSlug !== $expectedSlug) {
            // Redirect to the canonical URL so bookmarks / links stay consistent
            return redirect()->route('institute.website', [
                'instituteCode' => $instituteCode,
                'nameSlug'      => $expectedSlug,
            ], 301);
        }

        $templateId   = $institute->template_id;
        $settings     = $this->getPillarSettings($institute);
        $isEditable   = false;  // public view — never editable
        $heroSlides   = $this->getNormalizedHeroSlides($institute);
        $galleryItems = $this->getNormalizedGallery($institute);
        $events       = $this->getNormalizedEvents($institute);
        $content      = $institute->websiteContent;

        $visionItems  = $this->getNormalizedPillars($institute, 'vision');
        $missionItems = $this->getNormalizedPillars($institute, 'mission');
        $valuesItems  = $this->getNormalizedPillars($institute, 'values');

        $hasAchievements = $content && !empty($content->achievements['items']);
        $hasGallery      = $content && !empty($content->gallery);
        $hasEvents       = $content && !empty($content->events);
        $hasHeroSlides   = !empty($heroSlides);
        $hasMission      = $content && !empty($content->settings);

        return view("website_templates.template_{$templateId}", compact(
            'isEditable', 'settings', 'institute', 'heroSlides',
            'galleryItems', 'events', 'content', 'hasAchievements',
            'hasGallery', 'hasEvents', 'hasHeroSlides', 'hasMission',
            'visionItems', 'missionItems', 'valuesItems'
        ));
    }

    /**
     * Preview template with institute custom data.
     */
    public function preview($id)
    {
        if (!in_array($id, [1, 2, 3, 4, 5])) {
            abort(404);
        }
        
        $institute = auth('institute')->user();
        $isEditable = false;
        $settings = $this->getPillarSettings($institute);
        $heroSlides = $this->getNormalizedHeroSlides($institute);
        $galleryItems = $this->getNormalizedGallery($institute);
        $events       = $this->getNormalizedEvents($institute);
        $content      = $institute->websiteContent;

        $visionItems  = $this->getNormalizedPillars($institute, 'vision');
        $missionItems = $this->getNormalizedPillars($institute, 'mission');
        $valuesItems  = $this->getNormalizedPillars($institute, 'values');
        
        $hasAchievements = $content && !empty($content->achievements['items']);
        $hasGallery      = $content && !empty($content->gallery);
        $hasEvents       = $content && !empty($content->events);
        $hasHeroSlides   = !empty($heroSlides);
        $hasMission      = $content && !empty($content->settings);

        return view("website_templates.template_{$id}", compact(
            'isEditable', 'settings', 'institute', 'heroSlides',
            'galleryItems', 'events', 'content', 'hasAchievements',
            'hasGallery', 'hasEvents', 'hasHeroSlides', 'hasMission',
            'visionItems', 'missionItems', 'valuesItems'
        ));
    }

    /**
     * Get normalized slides array matching all templates schemas.
     */
    private function getNormalizedHeroSlides($institute)
    {
        if (!$institute) {
            return [];
        }
        
        $content = $institute->websiteContent;
        $heroSlides = [];
        
        if ($content && !empty($content->hero_slides)) {
            foreach ($content->hero_slides as $slide) {
                $title = $slide['title'] ?? '';
                $words = explode(' ', $title);
                $count = count($words);
                
                $title1 = $title;
                $accent = '';
                $title2 = '';
                $title3 = '';
                $highlight = '';
                
                if ($count >= 3) {
                    $title1 = implode(' ', array_slice($words, 0, 2));
                    $accent = $words[2];
                    $highlight = $words[2];
                    $title2 = implode(' ', array_slice($words, 3));
                    $title3 = implode(' ', array_slice($words, 3));
                }
                
                $heroSlides[] = [
                    'img' => $slide['image'] ?? '',
                    'badge' => $slide['badge'] ?? '',
                    'badgeText' => '',
                    'tag' => $slide['badge'] ?? '',
                    'title' => $title,
                    'title1' => $title1,
                    'title2' => $title2,
                    'title3' => $title3,
                    'accent' => $accent,
                    'highlight' => $highlight,
                    'desc' => $slide['desc'] ?? '',
                ];
            }
        }
        
        return $heroSlides;
    }

    /**
     * Get settings array representing Vision, Mission, and Values for the templates.
     */
    private function getPillarSettings($institute)
    {
        $settings = [];
        if (!$institute) {
            return $settings;
        }

        // Set dynamic contact details from the institute profile
        $settings['footer_email'] = $institute->alternate_email ?: $institute->email;
        $settings['footer_phone'] = $institute->phone ?: null;
        
        $addressParts = array_filter([
            $institute->address,
            $institute->address_line_2,
            $institute->city,
            $institute->state,
            $institute->country,
            $institute->pincode
        ]);
        $settings['footer_address'] = !empty($addressParts) ? implode(', ', $addressParts) : null;

        $content = $institute->websiteContent;
        if ($content) {
            $visionItems  = $this->getNormalizedPillars($institute, 'vision');
            $missionItems = $this->getNormalizedPillars($institute, 'mission');
            $valuesItems  = $this->getNormalizedPillars($institute, 'values');

            $firstVision  = $visionItems[0] ?? ['title' => '', 'desc' => ''];
            $firstMission = $missionItems[0] ?? ['title' => '', 'desc' => ''];
            $firstValues  = $valuesItems[0] ?? ['title' => '', 'desc' => ''];

            // Vision
            $settings['vision_title'] = $firstVision['title'];
            $settings['vision_desc'] = $firstVision['desc'];
            $settings['vision_focus'] = '';
            $settings['vision_image'] = '';
            
            // For template 5
            $settings['pillar1_title'] = $firstVision['title'];
            $settings['pillar1_desc'] = $firstVision['desc'];
            $settings['pillar1_detail'] = $firstVision['desc'];
            $settings['pillar1_focus'] = '';
            
            // For template 4
            $settings['vision_focus_1'] = '';
            $settings['vision_focus_2'] = '';
            $settings['vision_focus_3'] = '';

            // Mission
            $settings['mission_title'] = $firstMission['title'];
            $settings['mission_desc'] = $firstMission['desc'];
            $settings['mission_focus'] = '';
            $settings['mission_image'] = '';
            
            // For template 5
            $settings['pillar2_title'] = $firstMission['title'];
            $settings['pillar2_desc'] = $firstMission['desc'];
            $settings['pillar2_detail'] = $firstMission['desc'];
            $settings['pillar2_focus'] = '';
            
            // For template 4
            $settings['mission_focus_1'] = '';
            $settings['mission_focus_2'] = '';
            $settings['mission_focus_3'] = '';

            // Values
            $settings['values_title'] = $firstValues['title'];
            $settings['values_desc'] = $firstValues['desc'];
            $settings['values_focus'] = '';
            $settings['values_image'] = '';
            
            // For template 5
            $settings['pillar3_title'] = $firstValues['title'];
            $settings['pillar3_desc'] = $firstValues['desc'];
            $settings['pillar3_detail'] = $firstValues['desc'];
            $settings['pillar3_focus'] = '';
            
            // For template 4
            $settings['values_focus_1'] = '';
            $settings['values_focus_2'] = '';
            $settings['values_focus_3'] = '';

            // Achievements
            $achievements = $content->achievements;
            $items = $achievements['items'] ?? [];
            if (empty($items)) {
                $settings['achieve_badge'] = 'Our Milestones';
                $settings['achieve_title'] = 'Recent Achievements';
                $settings['achieve_desc']  = 'Proud moments demonstrating our dedication to academic and athletic excellence.';

                $settings["ach1_tag"]   = 'Award';
                $settings["ach1_title"] = 'Best School Award 2025';
                $settings["ach1_desc"]  = 'Named "State\'s Most Innovational Education Center" for integrating interactive smart panels in 100% of classrooms.';

                $settings["ach2_tag"]   = 'Academics';
                $settings["ach2_title"] = '100% Board Exam Success';
                $settings["ach2_desc"]  = 'For 8 consecutive years, our senior batch students have achieved a 100% pass rate with over 45% scoring distinctions.';

                $settings["ach3_tag"]   = 'Sports';
                $settings["ach3_title"] = 'National Sports Champions';
                $settings["ach3_desc"]  = 'Our athletic team brought home 4 Gold and 2 Silver medals from the All-India Inter-School Sports Championship.';
            } else {
                $settings['achieve_badge'] = !empty($achievements['badge']) ? $achievements['badge'] : null;
                $settings['achieve_title'] = !empty($achievements['title']) ? $achievements['title'] : null;
                $settings['achieve_desc']  = !empty($achievements['desc']) ? $achievements['desc'] : null;

                foreach ([1, 2, 3] as $i) {
                    $item = $items[$i - 1] ?? [];
                    $settings["ach{$i}_tag"]   = !empty($item['tag']) ? $item['tag'] : null;
                    $settings["ach{$i}_title"] = !empty($item['title']) ? $item['title'] : null;
                    $settings["ach{$i}_desc"]  = !empty($item['desc']) ? $item['desc'] : null;
                }

                // Map ach keys to stat keys for template 5
                for ($i = 1; $i <= 3; $i++) {
                    $settings["stat{$i}_tag"]      = $settings["ach{$i}_tag"] ?? null;
                    $settings["stat{$i}_title"]    = $settings["ach{$i}_title"] ?? null;
                    $settings["stat{$i}_subtitle"] = $settings["ach{$i}_desc"] ?? null;
                    $settings["stat{$i}_detail"]   = $settings["ach{$i}_desc"] ?? null;
                }
            }
        }

        return $settings;
    }

    /**
     * Get normalized dynamic pillars array.
     */
    public function getNormalizedPillars($institute, $type)
    {
        $defaults = [
            'vision' => [],
            'mission' => [],
            'values' => []
        ];

        if (!$institute) {
            return $defaults[$type] ?? [];
        }

        $content = $institute->websiteContent;
        if (!$content) {
            return $defaults[$type] ?? [];
        }

        $field = 'about_' . $type;
        $data = $content->$field;

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
     * Get normalized gallery items array.
     */
    private function getNormalizedGallery($institute)
    {
        $defaultGallery = [
            [
                'img' => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=800&q=80',
                'tag' => 'Laboratory',
                'title' => 'Chemistry Research Lab',
                'cat' => 'academics',
                'category' => 'academics'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1521587760476-6c12a4b040da?auto=format&fit=crop&w=800&q=80',
                'tag' => 'Academics',
                'title' => 'Resource Center & Library',
                'cat' => 'academics',
                'category' => 'academics'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1581092918056-0c4c3acd37bd?auto=format&fit=crop&w=800&q=80',
                'tag' => 'Technology',
                'title' => 'Digital IT Hub & Coding Lab',
                'cat' => 'infrastructure',
                'category' => 'infrastructure'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=800&q=80',
                'tag' => 'Campus',
                'title' => 'Central University Courtyard',
                'cat' => 'infrastructure',
                'category' => 'infrastructure'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1460661419201-fd4cecdf8a8b?auto=format&fit=crop&w=800&q=80',
                'tag' => 'Sports',
                'title' => 'Athletic Running Track',
                'cat' => 'sports',
                'category' => 'sports'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1513364776144-60967b0f800f?auto=format&fit=crop&w=800&q=80',
                'tag' => 'Arts',
                'title' => 'Creative Arts & Pottery',
                'cat' => 'co-curricular',
                'category' => 'co-curricular'
            ]
        ];

        if (!$institute) {
            return $defaultGallery;
        }

        $content = $institute->websiteContent;
        if ($content && !empty($content->gallery)) {
            $galleryItems = [];
            foreach ($content->gallery as $index => $item) {
                $galleryItems[] = [
                    'id' => $index + 1,
                    'img' => $item['image'] ?? '',
                    'image' => $item['image'] ?? '',
                    'tag' => $item['tag'] ?? '',
                    'title' => $item['title'] ?? '',
                    'cat' => strtolower($item['tag'] ?? 'gallery'),
                    'category' => strtolower($item['tag'] ?? 'gallery')
                ];
            }
            return $galleryItems;
        }

        // Add IDs to default gallery
        foreach ($defaultGallery as $index => &$item) {
            $item['id'] = $index + 1;
            $item['image'] = $item['img'];
        }

        return $defaultGallery;
    }

    /**
     * Get normalized events array.
     */
    private function getNormalizedEvents($institute)
    {
        $defaultEvents = [
            [
                'day' => '25',
                'month' => 'JUN',
                'year' => '2026',
                'tag' => 'Networking',
                'category' => 'SUMMIT',
                'location' => 'Main Auditorium',
                'title' => 'Global Alumni Summit 2026',
                'time' => '10:00 AM',
                'desc' => 'Connecting graduating seniors with active technical leaders in worldwide tech divisions. Discover post-grad placement paths, industry integration challenges, and direct job offerings.',
                'speaker' => 'Dr. Sarah Jenkins & Alumni Panel',
                'speaker_role' => 'Director of Tech Innovation / Senior Alumni Members',
                'occupancy' => '88% Capacity Booked',
                'occupancy_pct' => 88
            ],
            [
                'day' => '10',
                'month' => 'JUL',
                'year' => '2026',
                'tag' => 'Ecology',
                'category' => 'ECOLOGY',
                'location' => 'Science Block Yard',
                'title' => 'Sustainability Campaign Drive',
                'time' => '02:30 PM',
                'desc' => 'Exploring green layouts, eco energy cells, and active recycling standards on campus. Participate in live solar panel workshops and campus ecosystem sustainability challenges.',
                'speaker' => 'Prof. Marcus Vance',
                'speaker_role' => 'Dean of Environmental Science Department',
                'occupancy' => '65% Filled',
                'occupancy_pct' => 65
            ],
            [
                'day' => '05',
                'month' => 'AUG',
                'year' => '2026',
                'tag' => 'Exhibition',
                'category' => 'EXHIBITION',
                'location' => 'Creative Arts Center',
                'title' => 'Art & Film Showcase (Aura 2026)',
                'time' => '02:00 PM',
                'desc' => 'Exhibition of student-produced documentaries, canvas installations, and classical acoustic music.',
                'speaker' => 'Creative Arts Department',
                'speaker_role' => 'Exhibition Coordinators & Students',
                'occupancy' => '92% Filled',
                'occupancy_pct' => 92
            ]
        ];

        if (!$institute) {
            return $defaultEvents;
        }

        $content = $institute->websiteContent;
        if ($content && !empty($content->events)) {
            $events = [];
            foreach ($content->events as $index => $item) {
                $occupancy = $item['occupancy'] ?? '';
                $occupancy_pct = 100;
                if (preg_match('/(\d+)/', $occupancy, $matches)) {
                    $occupancy_pct = intval($matches[1]);
                }
                
                $tag = $item['tag'] ?? 'Event';
                
                $events[] = [
                    'id' => $index + 1,
                    'day' => $item['day'] ?? '01',
                    'month' => strtoupper($item['month'] ?? 'JAN'),
                    'year' => $item['year'] ?? date('Y'),
                    'tag' => $tag,
                    'category' => strtoupper($item['category'] ?? $tag),
                    'location' => $item['location'] ?? 'Campus',
                    'title' => $item['title'] ?? '',
                    'time' => $item['time'] ?? '10:00 AM',
                    'desc' => $item['desc'] ?? '',
                    'speaker' => $item['speaker'] ?? '',
                    'speaker_role' => $item['speaker_role'] ?? '',
                    'occupancy' => $occupancy,
                    'occupancy_pct' => $occupancy_pct
                ];
            }
            return $events;
        }

        return $defaultEvents;
    }
}
