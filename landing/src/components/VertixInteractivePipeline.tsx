import React, { useState } from 'react';
import {
  ArrowRight,
  CheckCircle2,
  Clock,
  Sparkles,
  MessageSquare,
  FileText,
  DollarSign,
  Users,
  ChevronRight,
  ShieldCheck,
  Send,
  Building,
  Building2,
  Home,
  MapPin,
  Maximize2,
  Layers,
  FileCheck,
  Zap,
  Check,
  PhoneCall,
  Download,
  Lock,
  Kanban,
  UserCheck,
} from 'lucide-react';

interface StageData {
  id: string;
  stepNumber: string;
  badge: string;
  title: string;
  highlightPhrase: string;
  subtitle: string;
  description: string;
  propertyVisual: string;
  propertyName: string;
  propertyMeta: string;
  propertyLocation: string;
  propertyType: string;
  propertySize: string;
  propertyStatus: string;
  hudTag: string;
  leadScore: string;
  simulatedData: {
    clientName: string;
    clientRole: string;
    dealValue: string;
    channel: string;
    statusText: string;
    statusBadgeColor: string;
    actionLabel: string;
    chatSnippet?: string;
    repName?: string;
    quoteDetails?: {
      item: string;
      originalPrice: string;
      discount: string;
      finalPrice: string;
    };
    kanbanColumns?: { name: string; count: number; active?: boolean }[];
  };
  keyBenefits: string[];
}

const WORKFLOW_STAGES: StageData[] = [
  {
    id: 'capture',
    stepNumber: '01',
    badge: 'CAPTURE PROPERTY ENQUIRIES',
    title: 'Capture Property Enquiries from Every Channel',
    highlightPhrase: 'Every Channel',
    subtitle: 'WEBSITE FORMS · META ADS · PROPERTY PORTALS · WHATSAPP · CSV IMPORT',
    description:
      'Whether a buyer reaches out via your website form, WhatsApp, Meta Lead Ads (Facebook & Instagram), property portals, CSV/Excel import, or channel partner referrals, their contact details, budget range, and property preference are captured instantly into one organized dashboard.',
    propertyVisual:
      'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1400&q=85',
    propertyName: 'Green Valley Villas — Plot #14',
    propertyMeta: 'Sector 150, Noida · 300 Sq. Yd. Luxury Plot',
    propertyLocation: 'Sector 150, Noida',
    propertyType: 'Luxury Villa Plot',
    propertySize: '300 Sq. Yd. (2,700 Sq. Ft.)',
    propertyStatus: 'Plot Available · Immediate Allotment',
    hudTag: 'Meta Ad: Sector 150 Luxury Plot Campaign',
    leadScore: 'Hot Buyer Enquiry · Budget ₹85L',
    simulatedData: {
      clientName: 'Vikramaditya Singhania',
      clientRole: 'Investor & Home Buyer, New Delhi',
      dealValue: '₹85 Lakh',
      channel: 'WhatsApp Inbound',
      statusText: 'Auto-Captured into CRM',
      statusBadgeColor: 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
      actionLabel: 'Assigned to Project Sales Executive',
      chatSnippet:
        '“Hi! I saw your Green Valley Villas brochure on Instagram. I would like to check plot availability and the construction-linked payment plan for Sector 150.”',
    },
    keyBenefits: [
      'Website lead capture & official WhatsApp integration',
      'Meta Facebook & Instagram ad leads sync in real time',
      'CSV & Excel contact import for existing databases',
      'Automatic deduplication prevents duplicate calling',
    ],
  },
  {
    id: 'assignment',
    stepNumber: '02',
    badge: 'ASSIGN & QUALIFY REQUIREMENTS',
    title: 'Smart Lead Assignment & Multi-Channel Engagement',
    highlightPhrase: 'Lead Assignment',
    subtitle: 'FAST LEAD ROUTING & INSTANT PROSPECT ENGAGEMENT',
    description:
      'Real Estate CRM routes enquiries instantly to active sales executives based on project, budget, or territory. Sales executives can immediately respond with property brochures via WhatsApp or two-way synced email, keeping all communication logged.',
    propertyVisual:
      'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=1400&q=85',
    propertyName: 'Emerald Heights — 3 BHK Luxury Apartment',
    propertyMeta: 'Whitefield, Bengaluru · 1,850 Sq. Ft. · ₹1.15 Crore',
    propertyLocation: 'Whitefield, Bengaluru',
    propertyType: '3 BHK Premium High-Rise',
    propertySize: '1,850 Sq. Ft. (Super Built-Up)',
    propertyStatus: 'Sample Flat Ready · Site Visits Open',
    hudTag: 'Round-Robin Lead Assignment: Bengaluru East',
    leadScore: 'Priority Enquiry · Quick Reply Sent',
    simulatedData: {
      clientName: 'Pooja & Rohan Sharma',
      clientRole: 'Tech Consultant, Whitefield',
      dealValue: '₹1.15 Crore',
      channel: 'WhatsApp & Email Sync',
      statusText: 'Brochure Delivered & Viewed',
      statusBadgeColor: 'bg-blue-500/20 text-blue-400 border-blue-500/30',
      actionLabel: 'Assigned to Amit Verma (Sales Executive)',
      repName: 'Amit Verma (Property Consultant)',
      chatSnippet:
        '“Hello Pooja! Amit here from Emerald Heights. Attached is the 3 BHK floor plan and amenities brochure. Would tomorrow at 11:30 AM work for a site visit at our experience center?”',
    },
    keyBenefits: [
      'Automated round-robin lead assignment to sales executives',
      'Two-way WhatsApp & email communication logged in CRM',
      'Pre-approved quick-reply templates & PDF brochures',
      'Complete communication history accessible to managers',
    ],
  },
  {
    id: 'pipeline',
    stepNumber: '03',
    badge: 'SITE VISIT & PIPELINE',
    title: 'Visual Drag-and-Drop Property Deal Board',
    highlightPhrase: 'Visual Deal Board',
    subtitle: 'CLEAR STAGE TRACKING · SITE VISITS & FOLLOW-UP REMINDERS',
    description:
      'Eliminate spreadsheet confusion. Move buyers visually through customizable stages: New Enquiry, Qualified, Site Visit Scheduled, Negotiation, and Booking Won. Schedule calendar meetings, log visit notes, and set follow-up reminders.',
    propertyVisual:
      'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1400&q=85',
    propertyName: 'Horizon Commercial SCO Plots',
    propertyMeta: 'Golf Course Extension Road, Gurugram · ₹2.4 Crore',
    propertyLocation: 'Golf Course Ext. Road, Gurugram',
    propertyType: 'Commercial SCO Plots (G+4 Approved)',
    propertySize: '180 Sq. Yd. Dual-Frontage',
    propertyStatus: 'Site Visit Confirmed (Sat 11 AM)',
    hudTag: 'Live Kanban Column: Site Visit Confirmed',
    leadScore: 'Site Visit Scheduled · Saturday 11 AM',
    simulatedData: {
      clientName: 'Sunil Narang',
      clientRole: 'Managing Partner, Narang Retail',
      dealValue: '₹2.4 Crore',
      channel: 'Kanban Stage 4 / 6',
      statusText: 'Site Visit Confirmed for Saturday',
      statusBadgeColor: 'bg-purple-500/20 text-purple-400 border-purple-500/30',
      actionLabel: 'Next: Generate Branded PDF Cost Sheet',
      kanbanColumns: [
        { name: 'New Enquiry', count: 24 },
        { name: 'Qualified', count: 16 },
        { name: 'Site Visit', count: 9, active: true },
        { name: 'Negotiation', count: 5 },
        { name: 'Booking Won', count: 12 },
      ],
      chatSnippet:
        '“Site walkthrough confirmed for Saturday 11:00 AM with Sunil. Buyer requested construction milestone breakdown for Unit #G-12.”',
    },
    keyBenefits: [
      'Visual drag-and-drop property sales pipeline',
      'Calendar scheduling, site visit logs, and follow-up reminders',
      'Meeting notes and call activity tracked on each lead',
      'Manager visibility across team performance and pipelines',
    ],
  },
  {
    id: 'quotation',
    stepNumber: '04',
    badge: 'QUOTATIONS & COST SHEETS',
    title: 'Generate & Send Branded PDF Cost Sheets',
    highlightPhrase: 'Branded PDF Cost Sheets',
    subtitle: 'INSTANT PROPOSALS & PAYMENT PLANS VIA WHATSAPP & EMAIL',
    description:
      'Stop waiting days to build property quotations. Select available units, plots, or villas from your pre-loaded project inventory, apply payment plans or approved discounts, and dispatch a clean branded PDF cost sheet straight to the buyer.',
    propertyVisual:
      'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=1400&q=85',
    propertyName: 'The Palms Luxury Villa #08',
    propertyMeta: 'Turnkey 4-BHK Villa · 3,400 Sq. Ft. · ₹1.85 Crore',
    propertyLocation: 'ECR Scenic Corridor, Chennai',
    propertyType: 'Turnkey 4-BHK Luxury Villa',
    propertySize: '3,400 Sq. Ft. Built-Up with Private Deck',
    propertyStatus: 'Cost Sheet Generated · Festive Offer',
    hudTag: 'Cost Sheet #CS-2026-108 Generated',
    leadScore: 'Cost Sheet Sent via WhatsApp',
    simulatedData: {
      clientName: 'Dr. Arvind Mehra',
      clientRole: 'Senior Surgeon, Apollo Hospitals',
      dealValue: '₹1.66 Crore',
      channel: 'Branded PDF Cost Sheet',
      statusText: 'Delivered to WhatsApp & Email',
      statusBadgeColor: 'bg-amber-500/20 text-amber-400 border-amber-500/30',
      actionLabel: 'Approved Festive Payment Plan Applied',
      quoteDetails: {
        item: 'The Palms Villa #08 (3,400 Sq. Ft. Turnkey 4-BHK)',
        originalPrice: '₹1.85 Crore',
        discount: '-₹18.5 Lakh (Approved Incentive)',
        finalPrice: '₹1.66 Crore (All Inclusive)',
      },
      chatSnippet:
        '“Official Cost Sheet #108 delivered to WhatsApp. Buyer opened and reviewed payment schedule. Milestone: 10% Booking Token / 90% Construction Linked.”',
    },
    keyBenefits: [
      'Pre-loaded property inventory with unit specs and pricing',
      'One-click branded PDF quotation & cost sheet export',
      'Itemized payment plans and milestone breakdown schedules',
      'Direct sharing over WhatsApp and synced two-way email',
    ],
  },
  {
    id: 'closed-won',
    stepNumber: '05',
    badge: 'BOOKING WON & BUYER VAULT',
    title: 'Token Booking Confirmed & Secure Customer Vault',
    highlightPhrase: 'Secure Customer Vault',
    subtitle: 'TOKEN AMOUNT TRACKING · ROLE-BASED ACCESS & DATA PROTECTION',
    description:
      'Record token amounts, log payment receipts, and convert deals to Won. With strict role-based access control and manager-level visibility, buyer phone numbers and customer communication history remain protected inside your company CRM.',
    propertyVisual:
      'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=1400&q=85',
    propertyName: 'Grand View Residences — Penthouse Suite',
    propertyMeta: 'Bandra West, Mumbai · 4,200 Sq. Ft. · ₹4.85 Crore',
    propertyLocation: 'Bandra West, Mumbai',
    propertyType: 'Duplex Penthouse Suite (Sea View)',
    propertySize: '4,200 Sq. Ft. Carpet Area',
    propertyStatus: 'Token Received · Allotment Issued',
    hudTag: 'Booking Won & Token Received 🎉',
    leadScore: 'Role-Based Access Protected',
    simulatedData: {
      clientName: 'Ananya Sen',
      clientRole: 'Founder & MD, Sen Enterprises',
      dealValue: '₹4.85 Crore',
      channel: 'Booking Confirmed Deal',
      statusText: 'Token Amount Received & Allotment Issued',
      statusBadgeColor: 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
      actionLabel: 'Deal Closed · Moved to Post-Sales',
      chatSnippet:
        '“Token amount of ₹15 Lakh received via RTGS. Allotment letter issued. Buyer moved to post-sales documentation team. Customer history securely logged.”',
    },
    keyBenefits: [
      'Booking token tracking and deal closing workflow',
      'Role-based access permissions protecting customer phone numbers',
      'Full manager visibility over sales executive activity and deals',
      'Centralized customer database that stays with your company',
    ],
  },
];

interface VertixInteractivePipelineProps {
  onStartDemo: () => void;
}

export const VertixInteractivePipeline: React.FC<VertixInteractivePipelineProps> = ({
  onStartDemo,
}) => {
  const [activeStageId, setActiveStageId] = useState<string>(WORKFLOW_STAGES[0].id);

  const activeStage =
    WORKFLOW_STAGES.find((s) => s.id === activeStageId) || WORKFLOW_STAGES[0];

  return (
    <section id="workflow" className="py-12 lg:py-18 bg-[#F8F7F4] border-b border-[#E8E6DF] relative">
      <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Section Header */}
        <div className="flex flex-col md:flex-row md:items-end justify-between gap-4 pb-6 border-b border-[#E8E6DF] mb-8">
          <div className="space-y-2 max-w-2xl">
            <span className="text-[11px] font-semibold tracking-[0.25em] text-[#1A1A1A] uppercase font-mono block">
              THE LEAD-TO-DEAL JOURNEY
            </span>
            <h2 className="font-serif text-2xl sm:text-4xl text-[#1A1A1A] font-normal leading-[1.15]">
              How a Stranger’s Inquiry Becomes{' '}
              <span className="italic font-light text-[#76736A]">Predictable Revenue</span>
            </h2>
            <p className="text-xs sm:text-sm text-[#545149] font-light leading-relaxed">
              Explore the 5 interactive stages below to see how Real Estate CRM guides every prospect smoothly across your sales pipeline.
            </p>
          </div>

          <button
            onClick={onStartDemo}
            className="inline-flex items-center gap-2 px-5 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-white bg-[#1A1A1A] hover:bg-[#333333] transition-all self-start md:self-auto cursor-pointer rounded-full shadow-md hover:shadow-lg shrink-0"
          >
            <span>Test Real Leads</span>
            <ArrowRight className="w-3.5 h-3.5" />
          </button>
        </div>

        {/* Step Selector Horizontal Bar with Luxury Glassmorphic Pills - Swipeable on phone */}
        <div className="flex overflow-x-auto no-scrollbar sm:grid sm:grid-cols-3 lg:grid-cols-5 gap-2 sm:gap-2.5 mb-6 sm:mb-8 pb-2 sm:pb-0 -mx-4 px-4 sm:mx-0 sm:px-0">
          {WORKFLOW_STAGES.map((stage) => {
            const isActive = stage.id === activeStageId;
            return (
              <button
                key={stage.id}
                onClick={() => setActiveStageId(stage.id)}
                className={`p-3 rounded-xl text-left transition-all duration-300 cursor-pointer border relative overflow-hidden flex flex-col justify-between min-w-[150px] sm:min-w-0 shrink-0 sm:shrink flex-1 hover-lift ${
                  isActive
                    ? 'bg-[#1A1A1A] text-white border-[#1A1A1A] shadow-lg ring-2 ring-emerald-500/50'
                    : 'bg-white text-[#1A1A1A] border-[#E8E6DF] hover:border-[#C5C3BC] hover:bg-[#FAF9F6] shadow-xs'
                }`}
              >
                {/* Active Top Highlight Bar */}
                {isActive && (
                  <div className="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-emerald-400 via-teal-300 to-emerald-500"></div>
                )}

                <div className="flex items-center justify-between mb-1.5 gap-1">
                  <span
                    className={`font-mono text-[11px] font-bold ${
                      isActive ? 'text-emerald-400' : 'text-[#76736A]'
                    }`}
                  >
                    STEP {stage.stepNumber}
                  </span>
                  <span
                    className={`text-[8px] sm:text-[8.5px] font-mono uppercase px-1.5 py-0.5 rounded-full font-semibold truncate ${
                      isActive
                        ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30'
                        : 'bg-[#1A1A1A]/5 text-[#76736A]'
                    }`}
                  >
                    {stage.badge.split(' ')[0]}
                  </span>
                </div>

                <div>
                  <div className="font-serif text-xs sm:text-sm font-semibold leading-snug">
                    {stage.title.split(' ')[0]} {stage.title.split(' ')[1]} {stage.title.split(' ')[2] || ''}
                  </div>
                  <div
                    className={`text-[9px] font-mono uppercase tracking-wider mt-0.5 truncate ${
                      isActive ? 'text-white/60' : 'text-[#8F8C83]'
                    }`}
                  >
                    {stage.highlightPhrase}
                  </div>
                </div>
              </button>
            );
          })}
        </div>

        {/* Main Interactive Stage Showcase Card - Compact */}
        <div className="bg-white rounded-2xl border border-[#E8E6DF] overflow-hidden shadow-lg grid grid-cols-1 lg:grid-cols-12 items-stretch max-w-5xl mx-auto">
          {/* LEFT COLUMN: Stage Narrative, Value Proposition & Benefits */}
          <div key={`narrative-${activeStage.id}`} className="lg:col-span-6 p-5 sm:p-6 lg:p-7 flex flex-col justify-between space-y-4 sm:space-y-6 bg-gradient-to-b from-white to-[#FAF9F6] animate-fade-in-up">
            <div className="space-y-3 sm:space-y-4">
              {/* Step Pill & Badge */}
              <div className="flex flex-wrap items-center gap-2">
                <div className="inline-flex items-center gap-1.5 text-[11px] font-mono font-bold text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">
                  <span className="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                  <span>STEP {activeStage.stepNumber} · {activeStage.badge}</span>
                </div>
                <span className="text-[9px] font-mono text-[#8F8C83] uppercase tracking-widest font-semibold">
                  {activeStage.leadScore}
                </span>
              </div>

              {/* Title & Subheading */}
              <div className="space-y-1">
                <h3 className="font-serif text-xl sm:text-2xl lg:text-[26px] text-[#1A1A1A] font-normal leading-snug">
                  {activeStage.title}
                </h3>
                <div className="font-mono text-[11px] text-[#76736A] uppercase tracking-[0.15em] font-semibold flex items-center gap-1.5 pt-0.5">
                  <Zap className="w-3 h-3 text-amber-600 shrink-0" />
                  <span className="truncate">{activeStage.subtitle}</span>
                </div>
              </div>

              {/* Description */}
              <p className="text-xs sm:text-sm text-[#545149] font-light leading-relaxed">
                {activeStage.description}
              </p>

              {/* Key Benefits Grid (Sleek Micro-Chips) */}
              <div className="space-y-2 pt-2.5 border-t border-[#E8E6DF]">
                <div className="text-[11px] font-mono uppercase tracking-wider text-[#1A1A1A] font-bold">
                  Why Sales Teams Love This Step:
                </div>
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-2">
                  {activeStage.keyBenefits.map((benefit, idx) => (
                    <div
                      key={idx}
                      className="flex items-start gap-2 p-2 rounded-lg bg-white border border-[#E8E6DF]/80 hover:border-[#D5D3CB] hover-lift transition-all text-xs text-[#333333]"
                    >
                      <div className="w-3.5 h-3.5 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-800 shrink-0 mt-0.5">
                        <Check className="w-2 h-2 stroke-[3]" />
                      </div>
                      <span className="leading-tight text-[11px]">{benefit}</span>
                    </div>
                  ))}
                </div>
              </div>
            </div>

            {/* Bottom High-Impact CTA Button */}
            <div className="pt-3 border-t border-[#E8E6DF] flex items-center justify-between gap-3">
              <button
                onClick={onStartDemo}
                className="w-full sm:w-auto group btn-shimmer inline-flex items-center justify-center gap-2.5 px-5 py-2.5 rounded-full bg-[#1A1A1A] text-white font-semibold text-[11px] uppercase tracking-wider hover:bg-[#333333] transition-all cursor-pointer shadow-sm hover:shadow-md"
              >
                <span>Schedule 15-Min Walkthrough</span>
                <ArrowRight className="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" />
              </button>
            </div>
          </div>

          {/* RIGHT COLUMN: Ultra-Premium Real Estate Showcase Card */}
          <div key={`visual-${activeStage.id}`} className="lg:col-span-6 bg-[#0A0A0A] rounded-2xl overflow-hidden border border-[#222222] shadow-xl text-white flex flex-col justify-between relative group animate-fade-in-scale">
            {/* Top Full-Width High-Res Property Image */}
            <div className="relative aspect-[16/10] sm:aspect-[16/9] w-full overflow-hidden bg-neutral-900">
              <img
                src={activeStage.propertyVisual}
                alt={activeStage.propertyName}
                referrerPolicy="no-referrer"
                className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-90"
              />
              <div className="absolute inset-0 bg-gradient-to-t from-[#0A0A0A] via-[#0A0A0A]/30 to-transparent"></div>

              {/* Pill Badges on Image */}
              <div className="absolute top-3 left-3 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-black/75 backdrop-blur-md border border-white/20 text-[11px] font-mono text-emerald-400 font-bold shadow-md">
                <span className="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Real Estate Transformation</span>
              </div>

              <div className="absolute top-3 right-3 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-black/75 backdrop-blur-md border border-white/20 text-[10px] font-mono text-emerald-300 font-bold shadow-md">
                <Home className="w-3 h-3 text-emerald-400" />
                <span>{activeStage.propertyName.split('—')[0]}</span>
              </div>
            </div>

            {/* Bottom Content Area */}
            <div className="p-4 sm:p-6 space-y-4 bg-[#0A0A0A] flex-1 flex flex-col justify-between">
              <div className="space-y-2">
                <div className="flex items-center justify-between">
                  <span className="text-[10px] font-mono tracking-widest text-emerald-400 uppercase font-bold">
                    EXECUTIVE OVERVIEW · STAGE {activeStage.stepNumber}
                  </span>
                  <span className="text-[9px] font-mono text-white/50 uppercase tracking-wider font-semibold">
                    {activeStage.propertyStatus}
                  </span>
                </div>

                <h3 className="font-serif text-lg sm:text-xl text-white font-normal leading-snug">
                  {activeStage.propertyName}
                </h3>

                <p className="text-xs text-neutral-300 font-light leading-relaxed">
                  {activeStage.propertyMeta}. {activeStage.simulatedData.chatSnippet?.replace(/[“”"]/g, '') || activeStage.description}
                </p>
              </div>

              {/* Two Statistics / SLA Stat Cards side-by-side */}
              <div className="pt-3 border-t border-white/10 grid grid-cols-2 gap-2.5">
                <div className="p-2.5 rounded-xl bg-white/5 border border-white/10 hover:border-emerald-500/30 transition-colors">
                  <div className="text-lg sm:text-xl font-serif text-emerald-400 font-bold">
                    {activeStage.simulatedData.dealValue}
                  </div>
                  <div className="text-[10px] font-mono text-neutral-400 uppercase tracking-wide mt-0.5">
                    Deal Value
                  </div>
                </div>

                <div className="p-2.5 rounded-xl bg-white/5 border border-white/10 hover:border-emerald-500/30 transition-colors">
                  <div className="text-lg sm:text-xl font-serif text-white font-bold">
                    &lt; 90s
                  </div>
                  <div className="text-[10px] font-mono text-neutral-400 uppercase tracking-wide mt-0.5">
                    WhatsApp SLA
                  </div>
                </div>
              </div>

              {/* Full-Width Button */}
              <button
                onClick={onStartDemo}
                className="w-full py-3 px-4 rounded-xl bg-white text-black font-extrabold text-[11px] uppercase tracking-wider flex items-center justify-center gap-2 hover:bg-neutral-200 transition-all cursor-pointer shadow-lg hover:scale-[1.01]"
              >
                <span>EXPLORE WORKFLOW</span>
                <ArrowRight className="w-3.5 h-3.5" />
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};

