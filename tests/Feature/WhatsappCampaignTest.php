<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Crm\User\Models\User;
use Crm\WhatsApp\Jobs\SendWhatsappCampaignMessageJob;
use Crm\WhatsApp\Models\WhatsappCampaign;
use Crm\WhatsApp\Models\WhatsappCampaignRecipient;
use Crm\WhatsApp\Models\WhatsappDoNotContact;
use Crm\WhatsApp\Services\WhatsAppClientService;

beforeEach(function () {
    $this->admin = User::find(1) ?: User::factory()->create();
});

test('it shows the whatsapp broadcast index page to an authenticated admin', function () {
    $this->actingAs($this->admin, 'user')
        ->get(route('admin.whatsapp.index'))
        ->assertOk()
        ->assertSee('WhatsApp Broadcast')
        ->assertSee('New Broadcast');
});

test('it creates a campaign in draft status and parses uploaded contact numbers', function () {
    Storage::fake('public');

    $csvContent = "phone\n9876543210\n9876543211\ninvalid_text\n9876543210\n";
    $numbersFile = UploadedFile::fake()->createWithContent('contacts.csv', $csvContent);
    $brochureFile = UploadedFile::fake()->create('brochure.pdf', 100, 'application/pdf');

    $response = $this->actingAs($this->admin, 'user')
        ->post(route('admin.whatsapp.store'), [
            'name'             => 'Test Festive Launch',
            'numbers_file'     => $numbersFile,
            'brochure_file'    => $brochureFile,
            'caption'          => 'Check out our special brochure!',
            'throttle_seconds' => 15,
        ]);

    $campaign = WhatsappCampaign::where('name', 'Test Festive Launch')->latest('id')->first();

    expect($campaign)->not->toBeNull()
        ->and($campaign->status)->toBe('draft')
        ->and($campaign->total_recipients)->toBe(2)
        ->and($campaign->throttle_seconds)->toBe(15);

    $response->assertRedirect(route('admin.whatsapp.preview', $campaign->id));
});

test('it dispatches queued jobs with staggered delays on start campaign with consent', function () {
    Queue::fake();

    $campaign = WhatsappCampaign::create([
        'name'             => 'Dispatched Campaign',
        'brochure_path'    => 'test.pdf',
        'brochure_name'    => 'test.pdf',
        'status'           => 'draft',
        'throttle_seconds' => 20,
        'total_recipients' => 2,
    ]);

    $rec1 = WhatsappCampaignRecipient::create([
        'whatsapp_campaign_id' => $campaign->id,
        'phone_e164'           => '919876543210',
        'status'               => 'pending',
    ]);

    $rec2 = WhatsappCampaignRecipient::create([
        'whatsapp_campaign_id' => $campaign->id,
        'phone_e164'           => '919876543211',
        'status'               => 'pending',
    ]);

    $this->actingAs($this->admin, 'user')
        ->post(route('admin.whatsapp.start', $campaign->id), [
            'confirm_consent' => '1',
        ])
        ->assertRedirect(route('admin.whatsapp.show', $campaign->id));

    $campaign->refresh();
    expect($campaign->status)->toBe('running');

    Queue::assertPushed(SendWhatsappCampaignMessageJob::class, 2);
});

test('it returns live json status from status endpoint', function () {
    $campaign = WhatsappCampaign::create([
        'name'             => 'Live Status Campaign',
        'brochure_path'    => 'test.pdf',
        'status'           => 'running',
        'throttle_seconds' => 15,
        'total_recipients' => 10,
        'sent_count'       => 4,
        'failed_count'     => 1,
    ]);

    $this->actingAs($this->admin, 'user')
        ->get(route('admin.whatsapp.status', $campaign->id))
        ->assertOk()
        ->assertJson([
            'id'               => $campaign->id,
            'status'           => 'running',
            'total'            => 10,
            'sent'             => 4,
            'failed'           => 1,
            'pending'          => 5,
            'progress_percent' => 50,
        ]);
});

test('send job skips delivery if campaign is paused', function () {
    $campaign = WhatsappCampaign::create([
        'name'             => 'Paused Campaign',
        'brochure_path'    => 'test.pdf',
        'status'           => 'paused',
        'throttle_seconds' => 15,
        'total_recipients' => 1,
    ]);

    $recipient = WhatsappCampaignRecipient::create([
        'whatsapp_campaign_id' => $campaign->id,
        'phone_e164'           => '919876543210',
        'status'               => 'pending',
    ]);

    $clientMock = Mockery::mock(WhatsAppClientService::class);
    $clientMock->shouldNotReceive('sendMessage');

    $job = new SendWhatsappCampaignMessageJob($campaign->id, $recipient->id);
    $job->handle($clientMock);

    $recipient->refresh();
    expect($recipient->status)->toBe('pending');
});

test('it manages the do not contact (DNC) list', function () {
    $this->actingAs($this->admin, 'user')
        ->post(route('admin.whatsapp.dnc.store'), [
            'phone'  => '9876543210',
            'reason' => 'Requested opt out',
        ])
        ->assertRedirect(route('admin.whatsapp.dnc'));

    $dnc = WhatsappDoNotContact::where('phone_e164', '919876543210')->first();
    expect($dnc)->not->toBeNull();

    $this->actingAs($this->admin, 'user')
        ->delete(route('admin.whatsapp.dnc.delete', $dnc->id))
        ->assertRedirect(route('admin.whatsapp.dnc'));

    expect(WhatsappDoNotContact::find($dnc->id))->toBeNull();
});

test('it renders the whatsapp broadcast show and preview pages with breadcrumbs without error', function () {
    $campaign = WhatsappCampaign::create([
        'name'             => 'Test Render Campaign',
        'brochure_path'    => 'test.pdf',
        'status'           => 'running',
        'throttle_seconds' => 15,
        'total_recipients' => 5,
    ]);

    $this->actingAs($this->admin, 'user')
        ->get(route('admin.whatsapp.show', $campaign->id))
        ->assertOk()
        ->assertSee('Test Render Campaign');

    $draftCampaign = WhatsappCampaign::create([
        'name'             => 'Test Preview Draft',
        'brochure_path'    => 'test.pdf',
        'status'           => 'draft',
        'throttle_seconds' => 15,
        'total_recipients' => 5,
    ]);

    $this->actingAs($this->admin, 'user')
        ->get(route('admin.whatsapp.preview', $draftCampaign->id))
        ->assertOk()
        ->assertSee('Test Preview Draft');
});

