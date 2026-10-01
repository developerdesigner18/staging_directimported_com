<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\HomeSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HomeSectionController extends Controller
{
    use ResponseTrait;

    private const ICON_RULE = ['nullable', 'string', 'max:100', 'regex:/^[a-z0-9\- ]+$/i'];
    private const IMAGE_RULE = 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120';
    private const EXISTING_IMAGE_RULE = ['nullable', 'string', 'max:255', 'regex:/^[A-Za-z0-9_\-][A-Za-z0-9_.\-]*$/'];

    public function edit()
    {
        $homeSection = HomeSection::with('points')->first();
        if (!$homeSection) {
            $homeSection = HomeSection::create([
                'title' => 'About Us',
                'short_description' => 'Welcome to our website. We are dedicated to providing the best service possible.'
            ]);
        }
        return view('admin.home_section.edit', compact('homeSection'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'short_description' => 'required|string',
            'points' => 'nullable|array',
            'points.*' => 'required',

            // About Us page
            'about_badge' => 'nullable|string|max:150',
            'about_title' => 'nullable|string|max:255',
            'about_intro' => 'nullable|string',
            'about_button_text' => 'nullable|string|max:50',
            'about_hero_image' => self::IMAGE_RULE,
            'remove_about_hero_image' => 'nullable|boolean',
            'story_slots' => 'nullable|array|max:2',
            'story_slots.*.image' => self::IMAGE_RULE,
            'story_slots.*.existing' => self::EXISTING_IMAGE_RULE,
            'story_slots.*.remove' => 'nullable|boolean',
            'founded_title' => 'nullable|string|max:255',
            'founded_content' => 'nullable|string',
            'advantage_title' => 'nullable|string|max:255',
            'advantage_content' => 'nullable|string',
            'operations_title' => 'nullable|string|max:255',
            'operations_subtitle' => 'nullable|string|max:255',
            'operations' => 'nullable|array|max:12',
            'operations.*.icon' => self::ICON_RULE,
            'operations.*.title' => 'required|string|max:150',
            'operations.*.description' => 'nullable|string|max:500',
            'facts_title' => 'nullable|string|max:255',
            'facts' => 'nullable|array|max:20',
            'facts.*.icon' => self::ICON_RULE,
            'facts.*.feature' => 'required|string|max:150',
            'facts.*.details' => 'nullable|string|max:1000',
            'passion_title' => 'nullable|string|max:255',
            'passion_intro' => 'nullable|string',
            'passion_cards' => 'nullable|array|max:10',
            'passion_cards.*.image' => self::IMAGE_RULE,
            'passion_cards.*.existing_image' => self::EXISTING_IMAGE_RULE,
            'passion_cards.*.remove_image' => 'nullable|boolean',
            'passion_cards.*.badge' => 'nullable|string|max:100',
            'passion_cards.*.title' => 'required|string|max:150',
            'passion_cards.*.description' => 'nullable|string|max:3000',
            'passion_button_text' => 'nullable|string|max:50',
        ], [
            'title.required' => 'The title field is required.',
            'short_description.required' => 'The description field is required.',
            'points.*.required' => 'The point text is required.',
            'operations.*.title.required' => 'The card title is required.',
            'facts.*.feature.required' => 'The feature name is required.',
            'passion_cards.*.title.required' => 'The card title is required.',
            'operations.*.icon.regex' => 'Please choose a valid icon.',
            'facts.*.icon.regex' => 'Please choose a valid icon.',
        ] + $this->imageMessages());

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();

            $homeSection = HomeSection::first();
            if (!$homeSection) {
                $homeSection = new HomeSection();
            }
            $homeSection->title = $request->title;
            $homeSection->short_description = $request->short_description;

            $this->fillAboutPage($homeSection, $request);

            $homeSection->save();

            // Sync points
            $homeSection->points()->delete();
            if ($request->has('points')) {
                foreach ($request->points as $pointText) {
                    if (!empty($pointText)) {
                        $homeSection->points()->create(['point_text' => $pointText]);
                    }
                }
            }

            DB::commit();
            return $this->sendSuccess('About Us section updated successfully!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }

    /**
     * Fill the fields used by the dedicated About Us page.
     */
    private function fillAboutPage(HomeSection $homeSection, Request $request): void
    {
        $homeSection->fill($request->only([
            'about_badge',
            'about_title',
            'about_intro',
            'about_button_text',
            'founded_title',
            'founded_content',
            'advantage_title',
            'advantage_content',
            'operations_title',
            'operations_subtitle',
            'facts_title',
            'passion_title',
            'passion_intro',
            'passion_button_text',
        ]));

        if ($request->hasFile('about_hero_image')) {
            $homeSection->about_hero_image = uploadFile($request->file('about_hero_image'), ABOUT_US_PATH, 'about_');
        } elseif ($request->boolean('remove_about_hero_image')) {
            $homeSection->about_hero_image = null;
        }

        $homeSection->story_images = collect($request->input('story_slots', []))
            ->map(fn($slot, $index) => $this->resolveImage(
                $request->file("story_slots.$index.image"),
                $slot['existing'] ?? null,
                !empty($slot['remove'])
            ))
            ->filter()
            ->values()
            ->all();

        $homeSection->operations = collect($request->input('operations', []))
            ->map(fn($item) => [
                'icon' => trim($item['icon'] ?? ''),
                'title' => trim($item['title'] ?? ''),
                'description' => trim($item['description'] ?? ''),
            ])
            ->values()
            ->all();

        $homeSection->facts = collect($request->input('facts', []))
            ->map(fn($item) => [
                'icon' => trim($item['icon'] ?? ''),
                'feature' => trim($item['feature'] ?? ''),
                'details' => trim($item['details'] ?? ''),
            ])
            ->values()
            ->all();

        $homeSection->passion_cards = collect($request->input('passion_cards', []))
            ->map(fn($item, $index) => [
                'image' => $this->resolveImage(
                    $request->file("passion_cards.$index.image"),
                    $item['existing_image'] ?? null,
                    !empty($item['remove_image'])
                ),
                'badge' => trim($item['badge'] ?? ''),
                'title' => trim($item['title'] ?? ''),
                'description' => trim($item['description'] ?? ''),
            ])
            ->values()
            ->all();
    }

    private function imageMessages(): array
    {
        $messages = [];
        foreach (['about_hero_image', 'story_slots.*.image', 'passion_cards.*.image'] as $field) {
            $messages["$field.image"] = 'The file must be a JPG, PNG or WEBP image.';
            $messages["$field.mimes"] = 'The file must be a JPG, PNG or WEBP image.';
            $messages["$field.max"] = 'The image may not be greater than 5 MB.';
        }

        return $messages;
    }

    /**
     * New upload wins, otherwise keep the existing image unless it was removed.
     */
    private function resolveImage($file, ?string $existing, bool $remove): ?string
    {
        if ($file) {
            return uploadFile($file, ABOUT_US_PATH, 'about_');
        }

        return $remove || blank($existing) ? null : $existing;
    }
}
