<?php

namespace Tests\Feature;

use App\Enums\DocumentVerificationStatus;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestDocument;
use App\Models\User;
use Tests\TestCase;

class ServiceRequestResourceTest extends TestCase
{
    public function test_service_request_view_page_and_documents_relation_manager(): void
    {
        $user = User::first();
        if (! $user || ! ServiceRequest::first()) {
            $this->seed();
            $user = User::first();
        }
        $this->assertNotNull($user);

        $serviceRequest = ServiceRequest::first();
        $this->assertNotNull($serviceRequest);

        // Ensure at least one document exists
        ServiceRequestDocument::firstOrCreate(
            ['service_request_id' => $serviceRequest->id],
            [
                'original_name' => 'KTP_Pemohon.pdf',
                'file_path' => 'service-documents/dummy.pdf',
                'verification_status' => DocumentVerificationStatus::VALID,
                'notes' => 'Dokumen valid',
            ]
        );

        $response = $this->actingAs($user)->get("/admin/service-requests/{$serviceRequest->id}");
        $response->assertSuccessful();
    }
}
