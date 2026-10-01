<!-- About Page: Hero -->
<div class="tab-pane" id="aboutHero" role="tabpanel">
    <div class="alert alert-light border mb-4 fs-13"><i class="ri-pages-line me-1"></i> Shown at the top of the dedicated <a href="{{ route('about.us') }}" target="_blank">About Us page</a>.</div>
    <div class="row">
        <div class="col-lg-8">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label for="about_badge" class="form-label fw-bold">{{ admin_label('home_section_form', 'about_badge', 'Badge Text') }}</label>
                    <input type="text" class="form-control" id="about_badge" name="about_badge" maxlength="150"
                        value="{{ $homeSection->about_badge }}" placeholder="e.g. Licensed Automobile Dealer in Japan">
                    <label id="about_badge-error" class="text-danger error" style="display:none"></label>
                </div>
                <div class="col-md-6 mb-4">
                    <label for="about_button_text" class="form-label fw-bold">{{ admin_label('home_section_form', 'about_button_text', 'Button Text') }}</label>
                    <input type="text" class="form-control" id="about_button_text" name="about_button_text" maxlength="50"
                        value="{{ $homeSection->about_button_text }}" placeholder="e.g. Read More">
                    <small class="text-muted">Scrolls to the page content. Leave empty to hide the button.</small>
                    <label id="about_button_text-error" class="text-danger error" style="display:none"></label>
                </div>
            </div>
            <div class="mb-4">
                <label for="about_title" class="form-label fw-bold">{{ admin_label('home_section_form', 'about_title', 'Page Title') }}</label>
                <input type="text" class="form-control form-control-lg bg-light border-light" id="about_title" name="about_title"
                    maxlength="255" value="{{ $homeSection->about_title }}" placeholder="e.g. About Direct Imported Japan">
                <label id="about_title-error" class="text-danger error" style="display:none"></label>
            </div>
            <div class="mb-4">
                <label for="about_intro_editor" class="form-label fw-bold">{{ admin_label('home_section_form', 'about_intro', 'Intro Content') }}</label>
                <textarea class="form-control" id="about_intro_editor" rows="8">{{ $homeSection->about_intro }}</textarea>
                <input type="hidden" id="about_intro" name="about_intro" value="{{ $homeSection->about_intro }}">
                <label id="about_intro-error" class="text-danger error" style="display:none"></label>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="mb-4">
                @include('admin.home_section.partials.image-field', [
                    'name' => 'about_hero_image',
                    'label' => admin_label('home_section_form', 'about_hero_image', 'Hero Image'),
                    'url' => \App\Models\HomeSection::imageUrl($homeSection->about_hero_image),
                    'removeName' => 'remove_about_hero_image',
                ])
                <label id="about_hero_image-error" class="text-danger error" style="display:none"></label>
            </div>
        </div>
    </div>
</div>

<!-- About Page: Our Story -->
<div class="tab-pane" id="aboutStory" role="tabpanel">
    <div class="alert alert-light border mb-4 fs-13"><i class="ri-pages-line me-1"></i> The "Why We Were Founded" block on the About Us page, with up to two images beside the text.</div>
    <div class="row">
        <div class="col-lg-8">
            <div class="mb-4">
                <label for="founded_title" class="form-label fw-bold">{{ admin_label('home_section_form', 'founded_title', 'Heading') }}</label>
                <input type="text" class="form-control" id="founded_title" name="founded_title" maxlength="255"
                    value="{{ $homeSection->founded_title }}" placeholder="e.g. Why We Were Founded">
                <label id="founded_title-error" class="text-danger error" style="display:none"></label>
            </div>
            <div class="mb-4">
                <label for="founded_content_editor" class="form-label fw-bold">{{ admin_label('home_section_form', 'founded_content', 'Content') }}</label>
                <textarea class="form-control" id="founded_content_editor" rows="8">{{ $homeSection->founded_content }}</textarea>
                <input type="hidden" id="founded_content" name="founded_content" value="{{ $homeSection->founded_content }}">
                <label id="founded_content-error" class="text-danger error" style="display:none"></label>
            </div>
            <div class="mb-4">
                <label for="advantage_title" class="form-label fw-bold">{{ admin_label('home_section_form', 'advantage_title', 'Sub Heading') }}</label>
                <input type="text" class="form-control" id="advantage_title" name="advantage_title" maxlength="255"
                    value="{{ $homeSection->advantage_title }}" placeholder="e.g. Our Automotive Inspection Advantage">
                <label id="advantage_title-error" class="text-danger error" style="display:none"></label>
            </div>
            <div class="mb-4">
                <label for="advantage_content_editor" class="form-label fw-bold">{{ admin_label('home_section_form', 'advantage_content', 'Sub Content') }}</label>
                <textarea class="form-control" id="advantage_content_editor" rows="8">{{ $homeSection->advantage_content }}</textarea>
                <input type="hidden" id="advantage_content" name="advantage_content" value="{{ $homeSection->advantage_content }}">
                <label id="advantage_content-error" class="text-danger error" style="display:none"></label>
            </div>
        </div>
        <div class="col-lg-4">
            @php($storyImages = $homeSection->story_images ?? [])
            @foreach([0, 1] as $slot)
                <div class="mb-4">
                    @include('admin.home_section.partials.image-field', [
                        'name' => "story_slots[$slot][image]",
                        'label' => 'Image ' . ($slot + 1),
                        'url' => \App\Models\HomeSection::imageUrl($storyImages[$slot] ?? null),
                        'removeName' => "story_slots[$slot][remove]",
                        'existingName' => "story_slots[$slot][existing]",
                        'existingValue' => $storyImages[$slot] ?? '',
                    ])
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- About Page: Core Operations -->
<div class="tab-pane" id="aboutOperations" role="tabpanel">
    <div class="alert alert-light border mb-4 fs-13"><i class="ri-pages-line me-1"></i> Icon cards on the About Us page. Click <strong>Choose icon</strong> on a card to pick its icon. The section is hidden when no cards are added.</div>
    <div class="row">
        <div class="col-md-6 mb-4">
            <label for="operations_title" class="form-label fw-bold">{{ admin_label('home_section_form', 'operations_title', 'Section Title') }}</label>
            <input type="text" class="form-control" id="operations_title" name="operations_title" maxlength="255"
                value="{{ $homeSection->operations_title }}" placeholder="e.g. Core Operations">
            <label id="operations_title-error" class="text-danger error" style="display:none"></label>
        </div>
        <div class="col-md-6 mb-4">
            <label for="operations_subtitle" class="form-label fw-bold">{{ admin_label('home_section_form', 'operations_subtitle', 'Section Subtitle') }}</label>
            <input type="text" class="form-control" id="operations_subtitle" name="operations_subtitle" maxlength="255"
                value="{{ $homeSection->operations_subtitle }}">
            <label id="operations_subtitle-error" class="text-danger error" style="display:none"></label>
        </div>
    </div>
    <div class="d-flex align-items-center mb-3">
        <h6 class="fw-bold mb-0 flex-grow-1">Cards</h6>
        <button type="button" class="btn btn-success btn-sm add-row" data-template="#operationRowTemplate" data-container="#operationsContainer">
            <i class="ri-add-line align-bottom me-1"></i> Add Card
        </button>
    </div>
    <div id="operationsContainer">
        @foreach(($homeSection->operations ?? []) as $index => $item)
            @include('admin.home_section.partials.operation-row', ['index' => $index, 'item' => $item])
        @endforeach
    </div>
    <label id="operations-error" class="text-danger error" style="display:none"></label>
</div>

<!-- About Page: Operational Facts -->
<div class="tab-pane" id="aboutFacts" role="tabpanel">
    <div class="alert alert-light border mb-4 fs-13"><i class="ri-pages-line me-1"></i> The Feature / Details table on the About Us page. The section is hidden when no rows are added.</div>
    <div class="mb-4">
        <label for="facts_title" class="form-label fw-bold">{{ admin_label('home_section_form', 'facts_title', 'Section Title') }}</label>
        <input type="text" class="form-control" id="facts_title" name="facts_title" maxlength="255"
            value="{{ $homeSection->facts_title }}" placeholder="e.g. Operational Facts">
        <label id="facts_title-error" class="text-danger error" style="display:none"></label>
    </div>
    <div class="d-flex align-items-center mb-3">
        <h6 class="fw-bold mb-0 flex-grow-1">Rows</h6>
        <button type="button" class="btn btn-success btn-sm add-row" data-template="#factRowTemplate" data-container="#factsContainer">
            <i class="ri-add-line align-bottom me-1"></i> Add Row
        </button>
    </div>
    <div id="factsContainer">
        @foreach(($homeSection->facts ?? []) as $index => $item)
            @include('admin.home_section.partials.fact-row', ['index' => $index, 'item' => $item])
        @endforeach
    </div>
    <label id="facts-error" class="text-danger error" style="display:none"></label>
</div>

<!-- About Page: Vehicles We Source -->
<div class="tab-pane" id="aboutPassion" role="tabpanel">
    <div class="alert alert-light border mb-4 fs-13"><i class="ri-pages-line me-1"></i> The "Vehicles We Source" section at the bottom of the About Us page. Cards alternate image left / right automatically.</div>
    <div class="row">
        <div class="col-md-8 mb-4">
            <label for="passion_title" class="form-label fw-bold">{{ admin_label('home_section_form', 'passion_title', 'Section Title') }}</label>
            <input type="text" class="form-control" id="passion_title" name="passion_title" maxlength="255"
                value="{{ $homeSection->passion_title }}" placeholder="e.g. Our Passion: The Vehicles We Source">
            <label id="passion_title-error" class="text-danger error" style="display:none"></label>
        </div>
        <div class="col-md-4 mb-4">
            <label for="passion_button_text" class="form-label fw-bold">{{ admin_label('home_section_form', 'passion_button_text', 'Button Text') }}</label>
            <input type="text" class="form-control" id="passion_button_text" name="passion_button_text" maxlength="50"
                value="{{ $homeSection->passion_button_text }}" placeholder="e.g. Search Live Auctions Now">
            <small class="text-muted">Links to the vehicle search. Leave empty to hide.</small>
            <label id="passion_button_text-error" class="text-danger error" style="display:none"></label>
        </div>
    </div>
    <div class="mb-4">
        <label for="passion_intro_editor" class="form-label fw-bold">{{ admin_label('home_section_form', 'passion_intro', 'Intro Content') }}</label>
        <textarea class="form-control" id="passion_intro_editor" rows="6">{{ $homeSection->passion_intro }}</textarea>
        <input type="hidden" id="passion_intro" name="passion_intro" value="{{ $homeSection->passion_intro }}">
        <label id="passion_intro-error" class="text-danger error" style="display:none"></label>
    </div>
    <div class="d-flex align-items-center mb-3">
        <h6 class="fw-bold mb-0 flex-grow-1">Cards</h6>
        <button type="button" class="btn btn-success btn-sm add-row" data-template="#passionCardTemplate" data-container="#passionCardsContainer">
            <i class="ri-add-line align-bottom me-1"></i> Add Card
        </button>
    </div>
    <div id="passionCardsContainer">
        @foreach(($homeSection->passion_cards ?? []) as $index => $item)
            @include('admin.home_section.partials.passion-card-row', ['index' => $index, 'item' => $item])
        @endforeach
    </div>
    <label id="passion_cards-error" class="text-danger error" style="display:none"></label>
</div>
