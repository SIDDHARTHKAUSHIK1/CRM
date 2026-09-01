<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Crm\Activity\Repositories\ActivityRepository;
use Crm\Contact\Repositories\OrganizationRepository;
use Crm\Contact\Repositories\PersonRepository;
use Crm\Lead\Repositories\LeadRepository;
use Crm\Product\Repositories\ProductRepository;
use Crm\Quote\Repositories\QuoteRepository;
use Crm\Tag\Repositories\TagRepository;
use Crm\User\Repositories\UserRepository;
use Crm\Warehouse\Repositories\WarehouseRepository;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the demo data seeds.
     */
    public function run()
    {
        $userRepo = app(UserRepository::class);
        $adminUser = $userRepo->first();
        if (! $adminUser) {
            return;
        }

        $request = Request::create('/');
        app()->instance('request', $request);
        Auth::guard('user')->setUser($adminUser);

        $orgRepo = app(OrganizationRepository::class);
        $personRepo = app(PersonRepository::class);
        $productRepo = app(ProductRepository::class);
        $leadRepo = app(LeadRepository::class);
        $quoteRepo = app(QuoteRepository::class);
        $activityRepo = app(ActivityRepository::class);
        $tagRepo = app(TagRepository::class);
        $warehouseRepo = app(WarehouseRepository::class);

        $adminId = $adminUser->id;

        // 1. Real Estate Tags
        $tagNames = [
            ['name' => 'Luxury Property', 'color' => '#F97316'],
            ['name' => 'Commercial Space', 'color' => '#3B82F6'],
            ['name' => 'HNI Investor', 'color' => '#10B981'],
            ['name' => 'Site Visit Booked', 'color' => '#8B5CF6'],
            ['name' => 'NRI Buyer', 'color' => '#EC4899'],
            ['name' => 'Pre-Launch Token', 'color' => '#EAB308'],
            ['name' => 'Ready to Move', 'color' => '#06B6D4'],
            ['name' => 'High Priority', 'color' => '#EF4444'],
        ];

        foreach ($tagNames as $t) {
            if (! $tagRepo->findOneByField('name', $t['name'])) {
                $tagRepo->create([
                    'name' => $t['name'],
                    'color' => $t['color'],
                    'user_id' => $adminId,
                ]);
            }
        }

        // 2. Real Estate Experience Centers & Sales Galleries (Warehouses)
        $warehousesData = [
            [
                'name' => 'Luxury Sales Lounge & Experience Gallery',
                'description' => 'Flagship customer lounge and sample flat experience center for luxury residences and penthouses.',
                'contact_name' => 'Siddharth Kaushik',
                'contact_emails' => [['value' => 'gallery@dlf-luxury.demo', 'label' => 'work']],
                'contact_numbers' => [['value' => '9811002233', 'label' => 'work']],
                'contact_address' => [
                    'address' => 'Golf Course Road, DLF Phase 5',
                    'country' => 'IN',
                    'state' => 'HR',
                    'city' => 'Gurugram',
                    'postcode' => '122002',
                ],
            ],
            [
                'name' => 'Corporate Real Estate & Commercial Sales Center',
                'description' => 'Commercial leasing and corporate property acquisition sales office.',
                'contact_name' => 'Rajiv Kapur',
                'contact_emails' => [['value' => 'commercial@godrej-properties.demo', 'label' => 'work']],
                'contact_numbers' => [['value' => '9822003344', 'label' => 'work']],
                'contact_address' => [
                    'address' => 'Bandra-Kurla Complex (BKC), G Block',
                    'country' => 'IN',
                    'state' => 'MH',
                    'city' => 'Mumbai',
                    'postcode' => '400051',
                ],
            ],
        ];

        foreach ($warehousesData as $w) {
            if (! $warehouseRepo->findOneByField('name', $w['name'])) {
                $warehouseRepo->create($w);
            }
        }

        // 3. Real Estate Developers, Builders & Corporate Clients (Organizations)
        $orgsData = [
            [
                'name' => 'DLF Luxury Developers',
                'address' => [
                    'address' => 'DLF Cyber City, Building 10, DLF Phase 2',
                    'country' => 'IN',
                    'state' => 'HR',
                    'city' => 'Gurugram',
                    'postcode' => '122002',
                ],
            ],
            [
                'name' => 'Godrej Properties Ltd',
                'address' => [
                    'address' => 'Godrej One, 5th Floor, Pirojshanagar, Vikhroli East',
                    'country' => 'IN',
                    'state' => 'MH',
                    'city' => 'Mumbai',
                    'postcode' => '400079',
                ],
            ],
            [
                'name' => 'Prestige Estates Projects',
                'address' => [
                    'address' => 'Prestige Falcon Tower, Brunton Road',
                    'country' => 'IN',
                    'state' => 'KA',
                    'city' => 'Bengaluru',
                    'postcode' => '560025',
                ],
            ],
            [
                'name' => 'Oberoi Realty Limited',
                'address' => [
                    'address' => 'Commerz II, International Business Park, Oberoi Garden City',
                    'country' => 'IN',
                    'state' => 'MH',
                    'city' => 'Mumbai',
                    'postcode' => '400063',
                ],
            ],
            [
                'name' => 'Sobha Premium Estates',
                'address' => [
                    'address' => 'Sobha Corporate Office, Sarjapur-Marathahalli Outer Ring Road',
                    'country' => 'IN',
                    'state' => 'KA',
                    'city' => 'Bengaluru',
                    'postcode' => '560103',
                ],
            ],
            [
                'name' => 'Lodha Luxury Living (Macrotech)',
                'address' => [
                    'address' => 'Lodha Excelus, NM Joshi Marg, Mahalaxmi',
                    'country' => 'IN',
                    'state' => 'MH',
                    'city' => 'Mumbai',
                    'postcode' => '400011',
                ],
            ],
            [
                'name' => 'Hiranandani Urban Communities',
                'address' => [
                    'address' => 'Olympia, Central Avenue, Hiranandani Gardens, Powai',
                    'country' => 'IN',
                    'state' => 'MH',
                    'city' => 'Mumbai',
                    'postcode' => '400076',
                ],
            ],
            [
                'name' => 'Brigade Real Estate Group',
                'address' => [
                    'address' => 'Brigade Gateway, 26/1 Dr. Rajkumar Road, Malleswaram',
                    'country' => 'IN',
                    'state' => 'KA',
                    'city' => 'Bengaluru',
                    'postcode' => '560055',
                ],
            ],
        ];

        $createdOrgs = [];
        foreach ($orgsData as $o) {
            $existing = $orgRepo->findOneByField('name', $o['name']);
            if (! $existing) {
                $createdOrgs[$o['name']] = $orgRepo->create([
                    'name' => $o['name'],
                    'address' => $o['address'],
                    'user_id' => $adminId,
                    'entity_type' => 'organizations',
                ]);
            } else {
                $createdOrgs[$o['name']] = $existing;
            }
        }

        // 4. Real Estate Contacts: Buyers, HNI Investors & Channel Partners (Persons)
        $personsData = [
            [
                'name' => 'Vikramaditya Singhania',
                'emails' => [['value' => 'vikram.singhania@apexcapital-demo.com', 'label' => 'work']],
                'contact_numbers' => [['value' => '9811200111', 'label' => 'work']],
                'job_title' => 'Managing Director & HNI Property Investor',
                'organization_id' => $createdOrgs['DLF Luxury Developers']->id,
            ],
            [
                'name' => 'Ananya Deshmukh',
                'emails' => [['value' => 'ananya.deshmukh@godrej-corp.demo', 'label' => 'work']],
                'contact_numbers' => [['value' => '9822300222', 'label' => 'work']],
                'job_title' => 'VP of Corporate Real Estate & Leasing',
                'organization_id' => $createdOrgs['Godrej Properties Ltd']->id,
            ],
            [
                'name' => 'Raghavan Sundaram',
                'emails' => [['value' => 'r.sundaram@singapore-invest.demo', 'label' => 'work']],
                'contact_numbers' => [['value' => '9833400333', 'label' => 'work']],
                'job_title' => 'NRI Real Estate Portfolio Director',
                'organization_id' => $createdOrgs['Prestige Estates Projects']->id,
            ],
            [
                'name' => 'Meera Nair',
                'emails' => [['value' => 'meera.nair@sobhainvest-demo.com', 'label' => 'work']],
                'contact_numbers' => [['value' => '9844500444', 'label' => 'work']],
                'job_title' => 'Chief Investment Officer (CIO) / Commercial Buyer',
                'organization_id' => $createdOrgs['Sobha Premium Estates']->id,
            ],
            [
                'name' => 'Karan Mehra',
                'emails' => [['value' => 'karan.mehra@foundersclub-demo.com', 'label' => 'work']],
                'contact_numbers' => [['value' => '9855600555', 'label' => 'work']],
                'job_title' => 'Tech Entrepreneur / Luxury Penthouse Buyer',
                'organization_id' => $createdOrgs['Oberoi Realty Limited']->id,
            ],
            [
                'name' => 'Dr. Sunita Rao',
                'emails' => [['value' => 'dr.sunita.rao@medicare-demo.com', 'label' => 'work']],
                'contact_numbers' => [['value' => '9888900666', 'label' => 'work']],
                'job_title' => 'Senior Surgeon & Private Property Investor',
                'organization_id' => $createdOrgs['Lodha Luxury Living (Macrotech)']->id,
            ],
            [
                'name' => 'Rajiv Kapur',
                'emails' => [['value' => 'rajiv.kapur@primeadvisors-demo.com', 'label' => 'work']],
                'contact_numbers' => [['value' => '9877800777', 'label' => 'work']],
                'job_title' => 'Principal Real Estate Consultant & Master Broker',
                'organization_id' => $createdOrgs['Hiranandani Urban Communities']->id,
            ],
            [
                'name' => 'Pooja Hegde',
                'emails' => [['value' => 'pooja.hegde@brigade-corp.demo', 'label' => 'work']],
                'contact_numbers' => [['value' => '9866700888', 'label' => 'work']],
                'job_title' => 'Head of Corporate Relocation & Facilities',
                'organization_id' => $createdOrgs['Brigade Real Estate Group']->id,
            ],
        ];

        $createdPersons = [];
        foreach ($personsData as $p) {
            $existing = $personRepo->findOneByField('name', $p['name']);
            if (! $existing) {
                $createdPersons[$p['name']] = $personRepo->create([
                    'name' => $p['name'],
                    'emails' => $p['emails'],
                    'contact_numbers' => $p['contact_numbers'],
                    'job_title' => $p['job_title'],
                    'organization_id' => $p['organization_id'],
                    'user_id' => $adminId,
                    'entity_type' => 'persons',
                ]);
            } else {
                $createdPersons[$p['name']] = $existing;
            }
        }

        // 5. Real Estate Properties & Units (Products)
        $productsData = [
            [
                'name' => '4BHK Sea-Facing Sky Penthouse (4,800 sq.ft)',
                'sku' => 'PROP-SKY-001',
                'description' => 'Ultra-luxury duplex penthouse with private rooftop plunge pool, panoramic 270-degree ocean view, Italian marble finishes, and 3 reserved basement car parkings.',
                'quantity' => 4,
                'price' => 65000000.00,
                'entity_type' => 'products',
            ],
            [
                'name' => '3BHK Golf Course Luxury Residence (2,350 sq.ft)',
                'sku' => 'PROP-APT-002',
                'description' => 'Golf-facing premium high-rise apartment with smart home automation, expansive glass balconies, German modular kitchen fittings, and 2 dedicated parking bays.',
                'quantity' => 12,
                'price' => 28500000.00,
                'entity_type' => 'products',
            ],
            [
                'name' => 'Grade-A Commercial Office Floor Plate (10,000 sq.ft)',
                'sku' => 'PROP-COM-003',
                'description' => 'LEED Gold certified commercial floor plate with central HVAC, 100% DG power backup, high-speed passenger elevators, and 12 reserved parking spots.',
                'quantity' => 6,
                'price' => 140000000.00,
                'entity_type' => 'products',
            ],
            [
                'name' => '5BHK Private Independent Gated Estate Villa (7,200 sq.ft)',
                'sku' => 'PROP-VIL-004',
                'description' => 'Exclusive luxury villa on half-acre private plot with landscaped lawn, temperature-controlled swimming pool, home theater, and attached servant quarters.',
                'quantity' => 3,
                'price' => 115000000.00,
                'entity_type' => 'products',
            ],
            [
                'name' => 'Smart City 2BHK High-Rise Executive Suite (1,150 sq.ft)',
                'sku' => 'PROP-SMT-005',
                'description' => 'Modern tech-integrated apartment adjacent to major IT tech parks with infinity clubhouse access, EV charging points, and 24/7 biometric security.',
                'quantity' => 25,
                'price' => 12500000.00,
                'entity_type' => 'products',
            ],
            [
                'name' => 'High-Street Retail Boulevard Showroom (2,800 sq.ft)',
                'sku' => 'PROP-RET-006',
                'description' => 'Double-height ground floor retail commercial frontage with prominent road visibility, high consumer footfall, dedicated loading bay, and customer valet parking.',
                'quantity' => 8,
                'price' => 42000000.00,
                'entity_type' => 'products',
            ],
        ];

        $createdProducts = [];
        foreach ($productsData as $prod) {
            $existing = $productRepo->findOneByField('sku', $prod['sku']);
            if (! $existing) {
                $createdProducts[$prod['sku']] = $productRepo->create($prod);
            } else {
                $createdProducts[$prod['sku']] = $existing;
            }
        }

        // 6. Real Estate Leads across all Sales Pipeline Stages
        $leadsData = [
            [
                'title' => 'Inquiry: 4BHK Sea-Facing Sky Penthouse - Bandra West',
                'description' => 'High-net-worth client seeking ready-to-move ultra-luxury ocean view residence. Requested detailed floor plans and private evening viewing.',
                'lead_value' => 65000000.00,
                'lead_source_id' => 2,
                'lead_type_id' => 1,
                'lead_pipeline_id' => 1,
                'lead_pipeline_stage_id' => 1, // New
                'expected_close_date' => Carbon::now()->addDays(45)->format('Y-m-d'),
                'person_id' => $createdPersons['Karan Mehra']->id,
                'entity_type' => 'leads',
                'products' => [
                    ['product_id' => $createdProducts['PROP-SKY-001']->id, 'name' => $createdProducts['PROP-SKY-001']->name, 'quantity' => 1, 'price' => 65000000.00, 'amount' => 65000000.00],
                ],
            ],
            [
                'title' => 'Site Visit Scheduled: 3BHK Golf Course Residence - Gurugram',
                'description' => 'Client booked weekend private site visit with family for sample flat inspection. Interested in 18th+ floor with sunset and green golf course view.',
                'lead_value' => 28500000.00,
                'lead_source_id' => 1,
                'lead_type_id' => 1,
                'lead_pipeline_id' => 1,
                'lead_pipeline_stage_id' => 2, // Follow-up / Site Visit
                'expected_close_date' => Carbon::now()->addDays(30)->format('Y-m-d'),
                'person_id' => $createdPersons['Dr. Sunita Rao']->id,
                'entity_type' => 'leads',
                'products' => [
                    ['product_id' => $createdProducts['PROP-APT-002']->id, 'name' => $createdProducts['PROP-APT-002']->name, 'quantity' => 1, 'price' => 28500000.00, 'amount' => 28500000.00],
                ],
            ],
            [
                'title' => 'Commercial Office Floor Purchase (10,000 sq.ft) - Cyber City',
                'description' => 'Corporate expansion acquisition. Client reviewing technical specifications, parking ratio, and negotiating fit-out rent-free period.',
                'lead_value' => 140000000.00,
                'lead_source_id' => 4,
                'lead_type_id' => 2,
                'lead_pipeline_id' => 1,
                'lead_pipeline_stage_id' => 3, // Prospect
                'expected_close_date' => Carbon::now()->addDays(20)->format('Y-m-d'),
                'person_id' => $createdPersons['Ananya Deshmukh']->id,
                'entity_type' => 'leads',
                'products' => [
                    ['product_id' => $createdProducts['PROP-COM-003']->id, 'name' => $createdProducts['PROP-COM-003']->name, 'quantity' => 1, 'price' => 140000000.00, 'amount' => 140000000.00],
                ],
            ],
            [
                'title' => 'NRI Investment: 2x Smart City High-Rise Suites - Whitefield',
                'description' => 'NRI buyer purchasing two smart 2BHK apartments for rental yield. Title deed legal verification and NRI repatriation advisory in progress.',
                'lead_value' => 25000000.00,
                'lead_source_id' => 5,
                'lead_type_id' => 1,
                'lead_pipeline_id' => 1,
                'lead_pipeline_stage_id' => 3, // Prospect
                'expected_close_date' => Carbon::now()->addDays(25)->format('Y-m-d'),
                'person_id' => $createdPersons['Raghavan Sundaram']->id,
                'entity_type' => 'leads',
                'products' => [
                    ['product_id' => $createdProducts['PROP-SMT-005']->id, 'name' => $createdProducts['PROP-SMT-005']->name, 'quantity' => 2, 'price' => 12500000.00, 'amount' => 25000000.00],
                ],
            ],
            [
                'title' => 'Commercial Negotiation: 5BHK Independent Estate Villa',
                'description' => 'Drafting Sale Agreement terms, milestone construction schedule, and customization of private home theater and infinity pool.',
                'lead_value' => 115000000.00,
                'lead_source_id' => 1,
                'lead_type_id' => 1,
                'lead_pipeline_id' => 1,
                'lead_pipeline_stage_id' => 4, // Negotiation
                'expected_close_date' => Carbon::now()->addDays(10)->format('Y-m-d'),
                'person_id' => $createdPersons['Vikramaditya Singhania']->id,
                'entity_type' => 'leads',
                'products' => [
                    ['product_id' => $createdProducts['PROP-VIL-004']->id, 'name' => $createdProducts['PROP-VIL-004']->name, 'quantity' => 1, 'price' => 115000000.00, 'amount' => 115000000.00],
                ],
            ],
            [
                'title' => 'Sale Deed Registered: 4BHK Luxury Sky Villa & Penthouse',
                'description' => 'Full payment milestone completed, stamp duty and registration executed at sub-registrar office. Keys and possession package handed over.',
                'lead_value' => 65000000.00,
                'status' => 1,
                'closed_at' => Carbon::now()->subDays(3)->format('Y-m-d H:i:s'),
                'lead_source_id' => 5,
                'lead_type_id' => 1,
                'lead_pipeline_id' => 1,
                'lead_pipeline_stage_id' => 5, // Won
                'expected_close_date' => Carbon::now()->subDays(3)->format('Y-m-d'),
                'person_id' => $createdPersons['Vikramaditya Singhania']->id,
                'entity_type' => 'leads',
                'products' => [
                    ['product_id' => $createdProducts['PROP-SKY-001']->id, 'name' => $createdProducts['PROP-SKY-001']->name, 'quantity' => 1, 'price' => 65000000.00, 'amount' => 65000000.00],
                ],
            ],
            [
                'title' => 'Retail Showroom Registry Completed - High-Street Boulevard',
                'description' => 'Sale conveyance registered with anchor retail tenant already in place generating 8.2% annual net rental yield.',
                'lead_value' => 42000000.00,
                'status' => 1,
                'closed_at' => Carbon::now()->subDays(7)->format('Y-m-d H:i:s'),
                'lead_source_id' => 3,
                'lead_type_id' => 1,
                'lead_pipeline_id' => 1,
                'lead_pipeline_stage_id' => 5, // Won
                'expected_close_date' => Carbon::now()->subDays(7)->format('Y-m-d'),
                'person_id' => $createdPersons['Meera Nair']->id,
                'entity_type' => 'leads',
                'products' => [
                    ['product_id' => $createdProducts['PROP-RET-006']->id, 'name' => $createdProducts['PROP-RET-006']->name, 'quantity' => 1, 'price' => 42000000.00, 'amount' => 42000000.00],
                ],
            ],
            [
                'title' => 'Pre-Launch Villa Booking - Sector 150 Expressway',
                'description' => 'Buyer opted for alternative property closer to international airport terminal due to frequent overseas flight requirements.',
                'lead_value' => 35000000.00,
                'status' => 0,
                'lost_reason' => 'Buyer chose alternate luxury project closer to international airport.',
                'closed_at' => Carbon::now()->subDays(12)->format('Y-m-d H:i:s'),
                'lead_source_id' => 4,
                'lead_type_id' => 1,
                'lead_pipeline_id' => 1,
                'lead_pipeline_stage_id' => 6, // Lost
                'expected_close_date' => Carbon::now()->subDays(12)->format('Y-m-d'),
                'person_id' => $createdPersons['Pooja Hegde']->id,
                'entity_type' => 'leads',
                'products' => [
                    ['product_id' => $createdProducts['PROP-APT-002']->id, 'name' => $createdProducts['PROP-APT-002']->name, 'quantity' => 1, 'price' => 28500000.00, 'amount' => 28500000.00],
                ],
            ],
        ];

        foreach ($leadsData as $ld) {
            if (! $leadRepo->findOneByField('title', $ld['title'])) {
                $ld['user_id'] = $adminId;
                $leadRepo->create($ld);
            }
        }

        // 7. Real Estate Cost Sheets & Booking Proposals (Quotes)
        $quotesData = [
            [
                'subject' => 'Cost Sheet & Payment Schedule: 4BHK Sea-Facing Penthouse (Tower A, Unit 2402)',
                'description' => 'Formal property cost sheet including base apartment cost, 3 covered car park bays, clubhouse development charges, and construction-linked payment milestones.',
                'user_id' => $adminId,
                'person_id' => $createdPersons['Vikramaditya Singhania']->id,
                'entity_type' => 'quotes',
                'expired_at' => Carbon::now()->addDays(30)->format('Y-m-d'),
                'billing_address' => [
                    'address' => 'DLF Cyber City, Building 10, DLF Phase 2',
                    'country' => 'IN',
                    'state' => 'HR',
                    'city' => 'Gurugram',
                    'postcode' => '122002',
                ],
                'shipping_address' => [
                    'address' => 'DLF Cyber City, Building 10, DLF Phase 2',
                    'country' => 'IN',
                    'state' => 'HR',
                    'city' => 'Gurugram',
                    'postcode' => '122002',
                ],
                'discount_percent' => '2',
                'discount_amount' => 1300000.00,
                'tax_amount' => 3185000.00, // 5% GST on luxury real estate
                'adjustment_amount' => 0.00,
                'sub_total' => 65000000.00,
                'grand_total' => 66885000.00,
                'items' => [
                    [
                        'product_id' => $createdProducts['PROP-SKY-001']->id,
                        'name' => $createdProducts['PROP-SKY-001']->name,
                        'sku' => $createdProducts['PROP-SKY-001']->sku,
                        'quantity' => 1,
                        'price' => 65000000.00,
                        'total' => 65000000.00,
                    ],
                ],
            ],
            [
                'subject' => 'Commercial Purchase Proposal: Grade-A Office Floor Plate (Tower B, 7th Floor)',
                'description' => 'Comprehensive commercial acquisition quotation with 10,000 sq.ft floor plate, 12 reserved parking allocations, and 100% DG power backup setup.',
                'user_id' => $adminId,
                'person_id' => $createdPersons['Ananya Deshmukh']->id,
                'entity_type' => 'quotes',
                'expired_at' => Carbon::now()->addDays(45)->format('Y-m-d'),
                'billing_address' => [
                    'address' => 'Godrej One, 5th Floor, Pirojshanagar, Vikhroli East',
                    'country' => 'IN',
                    'state' => 'MH',
                    'city' => 'Mumbai',
                    'postcode' => '400079',
                ],
                'shipping_address' => [
                    'address' => 'Godrej One, 5th Floor, Pirojshanagar, Vikhroli East',
                    'country' => 'IN',
                    'state' => 'MH',
                    'city' => 'Mumbai',
                    'postcode' => '400079',
                ],
                'discount_percent' => '0',
                'discount_amount' => 0.00,
                'tax_amount' => 16800000.00, // 12% GST on commercial real estate
                'adjustment_amount' => 0.00,
                'sub_total' => 140000000.00,
                'grand_total' => 156800000.00,
                'items' => [
                    [
                        'product_id' => $createdProducts['PROP-COM-003']->id,
                        'name' => $createdProducts['PROP-COM-003']->name,
                        'sku' => $createdProducts['PROP-COM-003']->sku,
                        'quantity' => 1,
                        'price' => 140000000.00,
                        'total' => 140000000.00,
                    ],
                ],
            ],
        ];

        foreach ($quotesData as $qd) {
            if (! $quoteRepo->findOneByField('subject', $qd['subject'])) {
                $quoteRepo->create($qd);
            }
        }

        // 8. Real Estate Activities: Calls, Site Visits, Lunches & Legal Tasks
        $activitiesData = [
            [
                'title' => 'Discovery Call with Karan Mehra (Penthouse Requirements & Budget)',
                'type' => 'call',
                'comment' => 'Reviewed luxury sea-facing penthouse requirements. Client requested high floor, minimum 4 bedrooms, and private terrace pool.',
                'schedule_from' => Carbon::now()->subDays(2)->setTime(11, 0)->format('Y-m-d H:i:s'),
                'schedule_to' => Carbon::now()->subDays(2)->setTime(11, 45)->format('Y-m-d H:i:s'),
                'is_done' => 1,
                'user_id' => $adminId,
                'participants' => ['persons' => [$createdPersons['Karan Mehra']->id]],
            ],
            [
                'title' => 'Exclusive Sample Flat Site Tour with Dr. Sunita Rao (Golf Course Residence)',
                'type' => 'meeting',
                'location' => 'Luxury Sales Lounge & Experience Gallery, DLF Phase 5',
                'comment' => 'Escorted client through 2,350 sq.ft golf-facing show apartment, showcased clubhouse amenities, spa, and Olympic-length pool.',
                'schedule_from' => Carbon::now()->subDay()->setTime(15, 0)->format('Y-m-d H:i:s'),
                'schedule_to' => Carbon::now()->subDay()->setTime(16, 30)->format('Y-m-d H:i:s'),
                'is_done' => 1,
                'user_id' => $adminId,
                'participants' => ['persons' => [$createdPersons['Dr. Sunita Rao']->id]],
            ],
            [
                'title' => 'Commercial Lease & Purchase Terms Meeting with Ananya Deshmukh (Godrej)',
                'type' => 'meeting',
                'location' => 'BKC Corporate Boardroom / Google Meet',
                'comment' => 'Negotiating 10,000 sq.ft Grade-A commercial floor purchase, 12 parking allocations, and fit-out timeline milestones.',
                'schedule_from' => Carbon::now()->addDay()->setTime(14, 0)->format('Y-m-d H:i:s'),
                'schedule_to' => Carbon::now()->addDay()->setTime(15, 0)->format('Y-m-d H:i:s'),
                'is_done' => 0,
                'user_id' => $adminId,
                'participants' => ['persons' => [$createdPersons['Ananya Deshmukh']->id]],
            ],
            [
                'title' => 'NRI Repatriation & Mortgage Verification Call with Raghavan Sundaram',
                'type' => 'call',
                'comment' => 'Discussed NRE/NRO account wire transfer procedures, FEMA compliance, and RBI property acquisition guidelines for NRIs.',
                'schedule_from' => Carbon::now()->addDays(2)->setTime(16, 30)->format('Y-m-d H:i:s'),
                'schedule_to' => Carbon::now()->addDays(2)->setTime(17, 15)->format('Y-m-d H:i:s'),
                'is_done' => 0,
                'user_id' => $adminId,
                'participants' => ['persons' => [$createdPersons['Raghavan Sundaram']->id]],
            ],
            [
                'title' => 'Send RERA Approval Certificate & Sanctioned Floor Plans to Vikramaditya Singhania',
                'type' => 'task',
                'comment' => 'Forward sanctioned architectural floor plans, RERA project registration certificate, and draft Sale Agreement copy.',
                'schedule_from' => Carbon::now()->setTime(10, 0)->format('Y-m-d H:i:s'),
                'schedule_to' => Carbon::now()->setTime(10, 30)->format('Y-m-d H:i:s'),
                'is_done' => 1,
                'user_id' => $adminId,
                'participants' => ['persons' => [$createdPersons['Vikramaditya Singhania']->id]],
            ],
            [
                'title' => 'VIP Channel Partner Luncheon with Master Broker Rajiv Kapur',
                'type' => 'lunch',
                'location' => 'The Leela Palace, Bengaluru',
                'comment' => 'Executive lunch meeting to discuss exclusive pre-launch booking brokerage incentives and NRI roadshow schedule in Dubai.',
                'schedule_from' => Carbon::now()->addDays(3)->setTime(13, 0)->format('Y-m-d H:i:s'),
                'schedule_to' => Carbon::now()->addDays(3)->setTime(14, 30)->format('Y-m-d H:i:s'),
                'is_done' => 0,
                'user_id' => $adminId,
                'participants' => ['persons' => [$createdPersons['Rajiv Kapur']->id]],
            ],
        ];

        foreach ($activitiesData as $act) {
            if (! $activityRepo->findOneByField('title', $act['title'])) {
                $activityRepo->create($act);
            }
        }
    }
}