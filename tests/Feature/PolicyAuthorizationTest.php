<?php

namespace Tests\Feature;

use App\Filament\Pages\Reports\DtsenReportPage;
use App\Filament\Resources\ServiceRequests\ServiceRequestResource;
use App\Models\RehabilitationCase;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestDocument;
use App\Models\ServiceRequirement;
use App\Models\ServiceType;
use App\Models\User;
use App\Models\Village;
use App\Policies\ClientPolicy;
use App\Policies\DistrictPolicy;
use App\Policies\RehabilitationCasePolicy;
use App\Policies\ServiceRequestPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class PolicyAuthorizationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! User::first()) {
            $this->seed();
        }
    }

    public function test_admin_has_full_authorization_across_policies(): void
    {
        $admin = User::where('email', 'admin@dinsos.blitarkab.go.id')->firstOrFail();

        $userPolicy = new UserPolicy;
        $this->assertTrue($userPolicy->viewAny($admin));
        $this->assertTrue($userPolicy->create($admin));

        $districtPolicy = new DistrictPolicy;
        $this->assertTrue($districtPolicy->viewAny($admin));
        $this->assertTrue($districtPolicy->create($admin));

        $requestPolicy = new ServiceRequestPolicy;
        $this->assertTrue($requestPolicy->viewAny($admin));
        $this->assertTrue($requestPolicy->create($admin));

        $rehabPolicy = new RehabilitationCasePolicy;
        $this->assertTrue($rehabPolicy->viewAny($admin));

        $clientPolicy = new ClientPolicy;
        $this->assertTrue($clientPolicy->viewAny($admin));
    }

    public function test_pure_pimpinan_has_read_only_access_and_sees_only_dashboard_and_reports(): void
    {
        // Create or get pure pimpinan user (has role pimpinan only)
        $pimpinan = User::firstOrCreate(
            ['email' => 'bupati@blitarkab.go.id'],
            [
                'name' => 'Bupati Blitar',
                'password' => bcrypt('password'),
                'is_active' => true,
            ]
        );
        $pimpinan->syncRoles(['pimpinan']);

        $userPolicy = new UserPolicy;
        $this->assertFalse($userPolicy->viewAny($pimpinan));
        $this->assertFalse($userPolicy->create($pimpinan));

        $districtPolicy = new DistrictPolicy;
        $this->assertFalse($districtPolicy->viewAny($pimpinan));
        $this->assertFalse($districtPolicy->create($pimpinan));

        $requestPolicy = new ServiceRequestPolicy;
        $this->assertTrue($requestPolicy->viewAny($pimpinan));
        $this->assertFalse($requestPolicy->create($pimpinan));

        $request = ServiceRequest::first();
        if ($request) {
            $this->assertTrue($requestPolicy->view($pimpinan, $request));
            $this->assertFalse($requestPolicy->update($pimpinan, $request));
            $this->assertFalse($requestPolicy->delete($pimpinan, $request));
        }

        // Pimpinan navigation check for resource
        Auth::login($pimpinan);
        $this->assertFalse(ServiceRequestResource::shouldRegisterNavigation());
        $this->assertTrue(DtsenReportPage::canAccess());
        Auth::logout();
    }

    public function test_operator_cannot_access_data_master_or_reports_or_rehab(): void
    {
        $operator = User::where('email', 'operator.satreyan@blitarkab.go.id')->firstOrFail();

        $userPolicy = new UserPolicy;
        $this->assertFalse($userPolicy->viewAny($operator));

        $districtPolicy = new DistrictPolicy;
        $this->assertFalse($districtPolicy->viewAny($operator));

        $rehabPolicy = new RehabilitationCasePolicy;
        $this->assertFalse($rehabPolicy->viewAny($operator));

        $clientPolicy = new ClientPolicy;
        $this->assertFalse($clientPolicy->viewAny($operator));

        Auth::login($operator);
        $this->assertFalse(DtsenReportPage::canAccess());
        Auth::logout();
    }

    public function test_territory_scope_restricts_queries_for_operator(): void
    {
        $operatorDesa = User::where('email', 'operator.satreyan@blitarkab.go.id')->firstOrFail();
        $this->assertNotNull($operatorDesa->village_id);

        Auth::login($operatorDesa);

        // Under TerritoryScope, all queries on ServiceRequest must match operatorDesa->village_id
        $requests = ServiceRequest::all();
        foreach ($requests as $req) {
            $this->assertEquals($operatorDesa->village_id, $req->village_id);
        }

        Auth::logout();

        $operatorKecamatan = User::where('email', 'operator.kanigoro@blitarkab.go.id')->firstOrFail();
        $this->assertNotNull($operatorKecamatan->district_id);
        $this->assertNull($operatorKecamatan->village_id);

        Auth::login($operatorKecamatan);

        $kecamatanVillageIds = Village::where('district_id', $operatorKecamatan->district_id)->pluck('id')->all();
        $requestsKec = ServiceRequest::all();
        foreach ($requestsKec as $req) {
            $this->assertContains($req->village_id, $kecamatanVillageIds);
        }

        Auth::logout();
    }

    public function test_sensitive_rehabilitation_case_is_protected(): void
    {
        $rehabCase = RehabilitationCase::first();
        if (! $rehabCase) {
            $this->markTestSkipped('No rehabilitation case available in seed.');
        }

        $operator = User::where('email', 'operator.satreyan@blitarkab.go.id')->firstOrFail();
        $rehabPolicy = new RehabilitationCasePolicy;

        // Operator should not be able to view rehab case
        $this->assertFalse($rehabPolicy->view($operator, $rehabCase));
        $this->assertFalse($rehabPolicy->create($operator));
        $this->assertFalse($rehabPolicy->update($operator, $rehabCase));
    }

    public function test_activity_log_records_changes_on_transaction_models(): void
    {
        $admin = User::where('email', 'admin@dinsos.blitarkab.go.id')->firstOrFail();
        Auth::login($admin);

        $serviceType = ServiceType::firstOrFail();
        $village = Village::firstOrFail();

        $initialCount = Activity::count();

        $newRequest = ServiceRequest::create([
            'request_number' => 'AUDIT-TEST-'.time(),
            'service_type_id' => $serviceType->id,
            'applicant_name' => 'Warga Audit Test',
            'applicant_nik' => '3505081122330001',
            'family_card_number' => '3505081122330002',
            'address' => 'Jl. Audit Test No. 12',
            'village_id' => $village->id,
            'phone' => '081234567899',
            'status' => 'submitted',
        ]);

        $this->assertGreaterThan($initialCount, Activity::count());

        $lastActivity = Activity::where('subject_type', ServiceRequest::class)
            ->where('subject_id', $newRequest->id)
            ->latest('id')
            ->first();

        $this->assertNotNull($lastActivity);
        $this->assertEquals('created', $lastActivity->event);
        $this->assertEquals($admin->id, $lastActivity->causer_id);

        Auth::logout();
    }

    public function test_secure_document_download_requires_signature_or_authorization(): void
    {
        $fakePath = 'documents/test_ktp.pdf';
        Storage::disk('local')->put($fakePath, 'dummy document content');

        $serviceRequest = ServiceRequest::firstOrFail();
        $requirement = ServiceRequirement::first() ?? ServiceRequirement::create([
            'service_type_id' => $serviceRequest->service_type_id,
            'name' => 'KTP Pemohon',
            'is_mandatory' => true,
        ]);

        $document = ServiceRequestDocument::create([
            'service_request_id' => $serviceRequest->id,
            'service_requirement_id' => $requirement->id,
            'file_path' => $fakePath,
            'original_name' => 'ktp_asli.pdf',
        ]);

        // 1. Unauthenticated and unsigned request -> 403
        $unsignedUrl = route('documents.service-request.download', ['document' => $document->id]);
        $response = $this->get($unsignedUrl);
        $response->assertStatus(403);

        // 2. Signed URL -> 200 Download
        $signedUrl = $document->getSignedUrl(30);
        $responseSigned = $this->get($signedUrl);
        $responseSigned->assertSuccessful();
        $responseSigned->assertHeader('content-disposition');

        // 3. Authenticated Admin -> 200 Download even without signature
        $admin = User::where('email', 'admin@dinsos.blitarkab.go.id')->firstOrFail();
        $responseAuth = $this->actingAs($admin)->get($unsignedUrl);
        $responseAuth->assertSuccessful();

        Storage::disk('local')->delete($fakePath);
    }
}
