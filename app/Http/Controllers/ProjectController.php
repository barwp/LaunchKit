<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\AudienceResolver;
use App\Services\CopyFrameworkResolver;
use App\Services\DesignStyleResolver;
use App\Services\EditorStateService;
use App\Services\PreviewScaleService;
use App\Services\LandingPageGenerator;
use App\Services\NicheThemeResolver;
use App\Services\PaymentService;
use App\Services\ReferralService;
use App\Services\SectionComposer;
use App\Services\VisualPresetResolver;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function __construct(
        protected LandingPageGenerator $generator,
        protected NicheThemeResolver $themeResolver,
        protected VisualPresetResolver $visualPresetResolver,
        protected SectionComposer $sectionComposer,
        protected PreviewScaleService $previewScaleService,
        protected ReferralService $referralService,
        protected PaymentService $paymentService,
        protected DesignStyleResolver $designStyleResolver,
        protected CopyFrameworkResolver $copyFrameworkResolver,
        protected AudienceResolver $audienceResolver,
        protected EditorStateService $editorStateService,
    ) {
    }

    public function dashboard(Request $request): View
    {
        $user = $this->referralService->ensureReferralCode($request->user());
        $projects = $request->user()
            ->projects()
            ->latest()
            ->get();

        return view('dashboard', [
            'projects' => $projects,
            'nicheLabels' => collect($this->themeResolver->options())->pluck('label', 'value')->all(),
            'referralLink' => $this->referralService->buildReferralLink($user),
            'packages' => $this->paymentService->packages(),
            'latestOrder' => $user->orders()->latest()->first(),
            'referralCode' => $user->referral_code,
        ]);
    }

    public function create(): View
    {
        return view('projects.create', [
            'nicheOptions' => $this->themeResolver->options(),
            'businessTypes' => $this->themeResolver->businessTypes(),
            'visualOptions' => $this->themeResolver->visualOptions(),
            'toneOptions' => $this->themeResolver->toneOptions(),
            'nicheCatalog' => $this->themeResolver->catalog(),
            'visualPresets' => $this->designStyleResolver->options(),
            'designStyles' => $this->designStyleResolver->options(),
            'copyFrameworks' => $this->copyFrameworkResolver->options(),
            'audienceOptions' => $this->audienceResolver->options(),
            'trafficSources' => config('traffic_sources.options', []),
            'platformTargets' => config('platform_targets.options', []),
            'awarenessLevels' => config('awareness_levels.options', []),
            'languageTones' => config('language_tones.options', []),
            'goals' => config('goals.options', []),
            'brandColors' => config('visual_identity.brand_colors', []),
            'backgroundModes' => config('visual_identity.background_modes', []),
            'fontOptions' => config('visual_identity.fonts', []),
            'spacingPresets' => config('visual_identity.spacing_presets', []),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateProjectInput($request);
        $input = $this->withUploadedAssets($validated, $request);
        $generated = $this->generator->generate($input);

        $project = $request->user()->projects()->create([
            'name' => $input['nama_project'],
            'niche' => $input['niche'],
            'business_type' => $input['business_type'],
            'raw_input' => $input,
            'generated_data' => $generated,
            'edited_data' => $generated,
        ]);

        return redirect()
            ->route('projects.edit', $project)
            ->with('status', 'Project adaptive landing page berhasil dibuat.');
    }

    public function edit(Project $project, Request $request): View
    {
        $this->authorizeProject($project, $request);

        return view('editor', [
            'project' => $project,
            'pageData' => $project->resolvedData(),
            'nicheOptions' => $this->themeResolver->options(),
            'themePresets' => $this->editorStateService->fonts(),
            'visualPresets' => $this->themeResolver->presetsForNiche($project->niche),
            'allVisualPresets' => $this->designStyleResolver->options(),
            'sectionRegistry' => array_values(config('sections.registry', [])),
            'previewConfig' => $this->previewScaleService->config(),
            'uploadEndpoint' => route('projects.asset', $project),
            'fontOptions' => $this->editorStateService->fonts(),
            'spacingScale' => $this->editorStateService->spacingScale(),
        ]);
    }

    public function uploadAsset(Project $project, Request $request): JsonResponse
    {
        $this->authorizeProject($project, $request);

        $payload = $request->validate([
            'asset' => ['required', 'image', 'max:6144'],
        ]);

        $path = $payload['asset']->store('launchkit/projects/'.$project->id, 'public');

        return response()->json([
            'url' => asset('storage/'.$path),
            'path' => $path,
        ]);
    }

    public function update(Project $project, Request $request): RedirectResponse
    {
        $this->authorizeProject($project, $request);

        $payload = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'edited_data' => ['required', 'string'],
        ]);

        $decoded = json_decode($payload['edited_data'], true);

        if (! is_array($decoded)) {
            return back()->withErrors(['edited_data' => 'Data editor tidak valid.'])->withInput();
        }

        Arr::set($decoded, 'meta.project_name', $payload['name']);

        $project->update([
            'name' => $payload['name'],
            'edited_data' => $decoded,
        ]);

        return back()->with('status', 'Perubahan project berhasil disimpan.');
    }

    public function duplicate(Project $project, Request $request): RedirectResponse
    {
        $this->authorizeProject($project, $request);

        $copy = $project->replicate();
        $copy->name = $project->name.' Copy';
        $copy->created_at = now();
        $copy->updated_at = now();
        $copy->save();

        return redirect()
            ->route('projects.edit', $copy)
            ->with('status', 'Project berhasil diduplikasi.');
    }

    public function export(Project $project, Request $request)
    {
        $this->authorizeProject($project, $request);

        $pageData = $this->inlineImageAssets($project->resolvedData());
        $html = view('projects.export', [
            'project' => $project,
            'pageData' => $pageData,
        ])->render();

        return Response::make($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$this->slug($project->name).'.html"',
        ]);
    }

    protected function validateProjectInput(Request $request): array
    {
        return $request->validate([
            'nama_project' => ['required', 'string', 'max:255'],
            'nama_brand_bisnis' => ['required', 'string', 'max:255'],
            'niche' => ['required', 'string', 'max:100'],
            'business_type' => ['required', 'string', 'max:100'],
            'nama_produk_layanan' => ['required', 'string', 'max:255'],
            'deskripsi_singkat' => ['required', 'string'],
            'target_market' => ['required', 'string'],
            'harga' => ['required', 'string', 'max:255'],
            'cta_utama' => ['required', 'string', 'max:255'],
            'cta_link' => ['required', 'string', 'max:500'],
            'masalah_utama' => ['required', 'string'],
            'manfaat_utama' => ['required', 'string'],
            'fitur_utama' => ['required', 'string'],
            'keunggulan_kompetitor' => ['nullable', 'string'],
            'testimoni' => ['nullable', 'string'],
            'faq_dasar' => ['nullable', 'string'],
            'platform_target' => ['nullable', 'string', 'max:100'],
            'traffic_source' => ['nullable', 'string', 'max:100'],
            'goal' => ['nullable', 'string', 'max:100'],
            'awareness_level' => ['nullable', 'string', 'max:100'],
            'copywriting_framework' => ['nullable', 'string', 'max:100'],
            'language_tone' => ['nullable', 'string', 'max:100'],
            'target_audience' => ['nullable', 'array'],
            'target_audience.*' => ['string', 'max:100'],
            'pain_point_audience' => ['nullable', 'string'],
            'desire_goal_audience' => ['nullable', 'string'],
            'objection_audience' => ['nullable', 'string'],
            'visual_preference' => ['required', 'string', 'max:100'],
            'tone_copy' => ['required', 'string', 'max:100'],
            'visual_preset' => ['nullable', 'string', 'max:100'],
            'design_style' => ['nullable', 'string', 'max:100'],
            'brand_color_family' => ['nullable', 'string', 'max:100'],
            'background_mode' => ['nullable', 'string', 'max:100'],
            'font_preference' => ['nullable', 'string', 'max:100'],
            'spacing_preset' => ['nullable', 'string', 'max:100'],
            'logo' => ['nullable', 'image', 'max:4096'],
            'hero_image' => ['nullable', 'image', 'max:6144'],
        ]);
    }

    protected function withUploadedAssets(array $validated, Request $request): array
    {
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('launchkit/logos', 'public');
            $validated['logo_path'] = $path;
            $validated['logo_url'] = asset('storage/'.$path);
        }

        if ($request->hasFile('hero_image')) {
            $path = $request->file('hero_image')->store('launchkit/heroes', 'public');
            $validated['hero_image_path'] = $path;
            $validated['hero_image_url'] = asset('storage/'.$path);
        }

        return $validated;
    }

    protected function authorizeProject(Project $project, Request $request): void
    {
        abort_unless($project->user_id === $request->user()->id, 403);
    }

    protected function inlineImageAssets(array $pageData): array
    {
        array_walk_recursive($pageData, function (&$value) {
            if (! is_string($value) || $value === '') {
                return;
            }

            if (str_starts_with($value, asset('storage/'))) {
                $relativePath = str_replace(asset('storage/'), '', $value);
                $absolutePath = storage_path('app/public/'.$relativePath);

                if (is_file($absolutePath)) {
                    $mime = mime_content_type($absolutePath) ?: 'image/png';
                    $value = 'data:'.$mime.';base64,'.base64_encode((string) file_get_contents($absolutePath));
                }
            }
        });

        return $pageData;
    }

    protected function slug(string $name): string
    {
        $slug = str($name)->slug()->value();

        return $slug !== '' ? $slug : 'launchkit-adaptive';
    }
}
