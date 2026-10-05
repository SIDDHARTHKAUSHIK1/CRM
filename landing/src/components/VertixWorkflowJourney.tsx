import React, { useState } from 'react';
import {
  ArrowRight,
  Check,
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
  Calendar,
  Layers,
  FileCheck,
  Zap,
  PhoneCall,
  Download,
  Lock,
  Kanban,
  UserCheck,
  TrendingUp,
  Inbox,
  Filter,
} from 'lucide-react';

interface WorkflowStep {
  id: string;
  stepNumber: string;
  badge: string;
  title: string;
  subtitle: string;
  tagline: string;
  client: string;
  dealValue: string;
  project: string;
  unit: string;
  location: string;
  summary: string;
  keyPoints: string[];
}

const WORKFLOW_STEPS: WorkflowStep[] = [
  {
    id: 'lead-capture',
    stepNumber: '01',
    badge: 'STEP 1: LEAD CAPTURE',
    title: 'Lead Capture & Sync',
    subtitle: 'Meta Ads · Portals · WhatsApp · Website',
    tagline: 'Zero lead leakage from any channel',
    client: 'Vikramaditya Singhania',
    dealValue: '₹1.85 Cr',
    project: 'Godrej Palm Retreat',
    unit: '3 BHK Luxury · 1,950 Sq.Ft.',
    location: 'Sector 150, Noida',
    summary: 'Instantly captures inbound buyer enquiries from 99acres, Magicbricks, Meta Lead Ads, website forms, and WhatsApp into one unified inbox.',
    keyPoints: [
      'Instant real-time sync from 99acres, Magicbricks & Housing.com',
      'Meta Facebook & Instagram ad leads auto-imported in 2 seconds',
      'Automatic deduplication prevents multiple brokers calling the same lead',
    ],
  },
  {
    id: 'follow-up',
    stepNumber: '02',
    badge: 'STEP 2: SMART FOLLOW-UP',
    title: 'Automated Follow-Up',
    subtitle: 'Instant WhatsApp Brochure · 2-Way Chat',
    tagline: 'Connect in under 60 seconds with floor plans',
    client: 'Pooja & Rohan Sharma',
    dealValue: '₹2.40 Cr',
    project: 'DLF The Arbour',
    unit: '4 BHK Grand · 2,800 Sq.Ft.',
    location: 'Golf Course Ext, Gurugram',
    summary: 'Auto-assigns leads to sales executives via round-robin and immediately dispatches verified WhatsApp brochures, floor plans, and video walkthroughs.',
    keyPoints: [
      'Pre-approved WhatsApp PDF brochures & price sheets sent instantly',
      'Two-way WhatsApp & email conversation history logged in CRM',
      'Round-robin distribution ensures fair lead allocation across sales reps',
    ],
  },
  {
    id: 'site-visit',
    stepNumber: '03',
    badge: 'STEP 3: SITE VISIT',
    title: 'Site Visit Scheduling',
    subtitle: 'Calendar · Location Pin · Auto-Reminders',
    tagline: '42% higher client visit turnout',
    client: 'Sunil Narang & Family',
    dealValue: '₹3.10 Cr',
    project: 'Prestige City Villas',
    unit: 'Villa #18 · 3,400 Sq.Ft.',
    location: 'Sarjapur, Bengaluru',
    summary: 'Coordinates site walkthroughs with Google Map location pins, assigned sales managers, and automated WhatsApp reminders to buyers.',
    keyPoints: [
      'One-click calendar scheduling with sales manager availability',
      'Automated WhatsApp reminder with Google Maps pin sent 2 hours before',
      'Mobile check-in & visit notes logged directly by sales executives on site',
    ],
  },
  {
    id: 'deal-booking',
    stepNumber: '04',
    badge: 'STEP 4: DEAL & PIPELINE',
    title: 'Visual Deal Board',
    subtitle: 'Kanban Stages · Deal Value in ₹ Cr',
    tagline: 'Track every deal from visit to closing',
    client: 'Dr. Arvind & Neha Mehra',
    dealValue: '₹4.85 Cr',
    project: 'Oberoi Sky City',
    unit: 'Penthouse #42 · 4,100 Sq.Ft.',
    location: 'Borivali East, Mumbai',
    summary: 'Visual drag-and-drop pipeline showing exact deal stages: New Enquiry, Qualified, Site Visit Done, Negotiation, and Token Booking Won.',
    keyPoints: [
      'Visual drag-and-drop stages tailored to Indian property sales cycles',
      'Real-time total pipeline forecast displayed in ₹ Lakhs & Crores',
      'Manager overview of stalled deals, overdue follow-ups, and sales rep pipeline',
    ],
  },
  {
    id: 'payment-costsheet',
    stepNumber: '05',
    badge: 'STEP 5: PAYMENT & COST SHEET',
    title: 'Cost Sheets & Payment',
    subtitle: 'Construction Milestones · GST · Token Receipt',
    tagline: '1-click branded PDF cost sheets & token receipts',
    client: 'Anand Mahindra Group (CP Deal)',
    dealValue: '₹1.65 Cr',
    project: 'Brigade Horizon Plots',
    unit: 'Plot #G-14 · 250 Sq.Yd.',
    location: 'Mysore Road, Bengaluru',
    summary: 'Generates branded PDF cost sheets with milestone payment schedules (10% Token, 20% Plinth, 70% Possession) and records token receipts.',
    keyPoints: [
      'Automated calculation of base price, PLC, club charges & GST',
      '1-Click PDF export dispatched directly to buyer\'s WhatsApp',
      'Real-time logging of booking token amount (₹2 Lakh / ₹5 Lakh) & receipts',
    ],
  },
  {
    id: 'customer-vault',
    stepNumber: '06',
    badge: 'STEP 6: CUSTOMER VAULT',
    title: 'Customer Vault & Retention',
    subtitle: 'Role Access · Data Masking · Handover',
    tagline: '100% protected client database & zero data theft',
    client: 'Siddharth Varma (High-Net-Worth)',
    dealValue: '₹6.20 Cr',
    project: 'Sobha Windsor Luxury',
    unit: 'Duplex Villa #04',
    location: 'Whitefield, Bengaluru',
    summary: 'Secure buyer repository with allotment letters, payment ledgers, masked phone numbers, and 1-click access revocation when sales reps leave.',
    keyPoints: [
      'Role-based permissions: Executives see only their assigned leads',
      'Masked buyer phone numbers prevent unauthorized client exports',
      'Safe offboarding: Revoke employee access in 1 click while all records stay',
    ],
  },
];

interface VertixWorkflowJourneyProps {
  onStartDemo?: () => void;
}

export const VertixWorkflowJourney: React.FC<VertixWorkflowJourneyProps> = ({ onStartDemo }) => {
  const [activeStepIndex, setActiveStepIndex] = useState(0);
  const activeStep = WORKFLOW_STEPS[activeStepIndex];

  return (
    <section id="workflow" className="py-10 lg:py-16 bg-[#F8F7F4] border-b-2 border-[#D8D5CA] relative overflow-hidden">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 lg:space-y-8">
        
        {/* Section Header */}
        <div className="flex flex-col md:flex-row md:items-end justify-between gap-3 pb-5 border-b border-[#D8D5CA]">
          <div className="space-y-1.5 max-w-2xl">
            <div className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#0A0A0A] text-white text-[10px] font-mono tracking-widest uppercase font-bold shadow-xs">
              <Zap className="w-3 h-3 text-emerald-400" />
              <span>THE COMPLETE 6-STEP WORKFLOW</span>
            </div>
            <h2 className="font-serif text-2xl sm:text-3xl lg:text-4xl text-[#0A0A0A] font-bold leading-tight">
              From First Lead to <span className="italic font-normal text-emerald-800">Site Visit, Booking &amp; Payment</span>
            </h2>
            <p className="text-xs sm:text-sm text-[#3D3A34] font-normal leading-relaxed">
              Understand how your real estate sales team operates inside one seamless visual system. Click each step to see the live workflow.
            </p>
          </div>

          <div className="flex items-center gap-2 self-start md:self-end">
            <span className="text-[11px] font-mono font-bold text-[#0A0A0A] bg-white px-3 py-1.5 rounded-full border border-[#CDC9BC] shadow-2xs">
              Step {activeStep.stepNumber} of 06
            </span>
          </div>
        </div>

        {/* 6-Step Visual Timeline Selector */}
        <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2 sm:gap-2.5">
          {WORKFLOW_STEPS.map((step, idx) => {
            const isActive = idx === activeStepIndex;
            return (
              <button
                key={step.id}
                onClick={() => setActiveStepIndex(idx)}
                className={`p-3 rounded-xl text-left transition-all duration-200 cursor-pointer border flex flex-col justify-between space-y-2 hover-lift ${
                  isActive
                    ? 'bg-[#0A0A0A] text-white border-[#0A0A0A] shadow-lg ring-2 ring-emerald-500/50 scale-[1.02]'
                    : 'bg-white text-[#0A0A0A] border-[#CDC9BC] hover:border-[#0A0A0A] hover:bg-[#FAF9F5] shadow-xs'
                }`}
              >
                <div className="flex items-center justify-between w-full">
                  <span
                    className={`text-[10px] font-mono font-extrabold px-2 py-0.5 rounded-full ${
                      isActive
                        ? 'bg-emerald-500/30 text-emerald-300 border border-emerald-400/40'
                        : 'bg-[#EFECE4] text-[#111111]'
                    }`}
                  >
                    STEP {step.stepNumber}
                  </span>
                  {isActive && <span className="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>}
                </div>

                <div>
                  <div className="font-serif text-xs sm:text-sm font-bold leading-tight line-clamp-1">
                    {step.title}
                  </div>
                  <div
                    className={`text-[10px] font-mono line-clamp-1 mt-0.5 ${
                      isActive ? 'text-neutral-300' : 'text-[#55524A]'
                    }`}
                  >
                    {step.subtitle.split('·')[0]}
                  </div>
                </div>
              </button>
            );
          })}
        </div>

        {/* Main Interactive Stage Canvas - Realistic Visual SaaS Mockup */}
        <div className="bg-white rounded-2xl border-2 border-[#CDC9BC] overflow-hidden shadow-xl grid grid-cols-1 lg:grid-cols-12 items-stretch">
          
          {/* Left Column: Realistic UI Mockup for the Active Stage */}
          <div className="lg:col-span-7 bg-[#0B0B0C] p-4 sm:p-6 lg:p-7 flex flex-col justify-between border-b lg:border-b-0 lg:border-r border-[#262522] text-white relative">
            {/* Top Mockup Header Bar */}
            <div className="flex items-center justify-between pb-3 border-b border-white/10 mb-4">
              <div className="flex items-center gap-2">
                <div className="flex gap-1.5">
                  <span className="w-2.5 h-2.5 rounded-full bg-red-500/80"></span>
                  <span className="w-2.5 h-2.5 rounded-full bg-amber-500/80"></span>
                  <span className="w-2.5 h-2.5 rounded-full bg-emerald-500/80"></span>
                </div>
                <span className="text-[10px] font-mono text-neutral-400 font-bold ml-2">
                  Real Estate CRM Pro • {activeStep.badge}
                </span>
              </div>
              <span className="text-[9px] font-mono px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 font-bold">
                Live Simulation
              </span>
            </div>

            {/* Dynamic Interactive Stage Simulator UI */}
            <div className="space-y-3.5 my-auto py-2">
              {/* STEP 1: LEAD CAPTURE SIMULATION */}
              {activeStep.id === 'lead-capture' && (
                <div className="space-y-3 animate-fade-in-scale">
                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    {/* Inbound Lead Card 1 */}
                    <div className="bg-[#141416] p-3 rounded-xl border border-white/15 space-y-2">
                      <div className="flex items-center justify-between">
                        <span className="text-[9px] font-mono px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400 font-bold">
                          WhatsApp Inbound
                        </span>
                        <span className="text-[10px] font-mono text-neutral-400">Just Now</span>
                      </div>
                      <div>
                        <div className="font-serif text-sm font-bold text-white">Vikramaditya Singhania</div>
                        <div className="text-[11px] text-neutral-300">Sector 150, Noida · 3 BHK Luxury</div>
                      </div>
                      <div className="text-[10px] font-mono text-emerald-400 font-bold bg-white/5 px-2 py-1 rounded">
                        Budget: ₹1.85 Cr · High Intent
                      </div>
                    </div>

                    {/* Inbound Lead Card 2 */}
                    <div className="bg-[#141416] p-3 rounded-xl border border-white/15 space-y-2">
                      <div className="flex items-center justify-between">
                        <span className="text-[9px] font-mono px-2 py-0.5 rounded bg-blue-500/20 text-blue-400 font-bold">
                          MagicBricks Portal
                        </span>
                        <span className="text-[10px] font-mono text-neutral-400">4m ago</span>
                      </div>
                      <div>
                        <div className="font-serif text-sm font-bold text-white">Ananya Deshmukh</div>
                        <div className="text-[11px] text-neutral-300">Golf Course Road · 4 BHK Penthouse</div>
                      </div>
                      <div className="text-[10px] font-mono text-blue-400 font-bold bg-white/5 px-2 py-1 rounded">
                        Budget: ₹3.50 Cr · Verified Phone
                      </div>
                    </div>
                  </div>

                  {/* Auto-Deduplication & Sync Alert */}
                  <div className="p-2.5 rounded-lg bg-emerald-950/40 border border-emerald-500/30 flex items-center justify-between text-xs text-emerald-300">
                    <span className="flex items-center gap-1.5 font-mono text-[10px]">
                      <CheckCircle2 className="w-3.5 h-3.5 text-emerald-400" />
                      Auto-Deduplicated: No duplicate calls triggered
                    </span>
                    <span className="font-bold text-[10px] font-mono">Synced</span>
                  </div>
                </div>
              )}

              {/* STEP 2: SMART FOLLOW-UP SIMULATION */}
              {activeStep.id === 'follow-up' && (
                <div className="space-y-3 animate-fade-in-scale">
                  {/* WhatsApp Chat Simulation */}
                  <div className="bg-[#141416] rounded-xl border border-white/15 p-3 space-y-2.5">
                    <div className="flex items-center justify-between pb-2 border-b border-white/10 text-xs">
                      <div className="flex items-center gap-2">
                        <div className="w-6 h-6 rounded-full bg-emerald-600 flex items-center justify-center font-bold text-white text-[10px]">
                          WA
                        </div>
                        <div>
                          <span className="font-bold text-white text-xs">Pooja Sharma</span>
                          <span className="text-[9px] text-emerald-400 ml-2 font-mono">● Online</span>
                        </div>
                      </div>
                      <span className="text-[10px] font-mono text-neutral-400">Executive: Amit Verma</span>
                    </div>

                    <div className="space-y-2 text-xs">
                      <div className="bg-[#1E1E22] p-2.5 rounded-lg max-w-[85%] text-neutral-200">
                        "Hi Pooja! Amit here from DLF The Arbour. Here is the verified 4 BHK floor plan PDF and amenities brochure you requested."
                      </div>
                      <div className="bg-emerald-950/60 border border-emerald-500/30 p-2.5 rounded-lg max-w-[90%] text-emerald-200 flex items-center justify-between">
                        <div className="flex items-center gap-2">
                          <FileText className="w-4 h-4 text-emerald-400" />
                          <span className="font-mono text-[11px]">DLF_Arbour_4BHK_Brochure.pdf</span>
                        </div>
                        <span className="text-[9px] font-bold text-emerald-400 bg-emerald-500/20 px-1.5 py-0.5 rounded">
                          Viewed
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              )}

              {/* STEP 3: SITE VISIT SIMULATION */}
              {activeStep.id === 'site-visit' && (
                <div className="space-y-3 animate-fade-in-scale">
                  <div className="bg-[#141416] rounded-xl border border-white/15 p-3.5 space-y-3">
                    <div className="flex items-center justify-between">
                      <div className="flex items-center gap-2 text-xs">
                        <Calendar className="w-4 h-4 text-amber-400" />
                        <span className="font-serif text-sm font-bold text-white">Confirmed Site Walkthrough</span>
                      </div>
                      <span className="text-[10px] font-mono px-2 py-0.5 rounded bg-amber-500/20 text-amber-300 font-bold">
                        Saturday · 11:30 AM
                      </span>
                    </div>

                    <div className="grid grid-cols-2 gap-2 text-xs">
                      <div className="bg-[#1E1E22] p-2 rounded-lg space-y-0.5">
                        <span className="text-[9px] font-mono text-neutral-400">Buyer</span>
                        <div className="font-bold text-white">Sunil Narang &amp; Family</div>
                      </div>
                      <div className="bg-[#1E1E22] p-2 rounded-lg space-y-0.5">
                        <span className="text-[9px] font-mono text-neutral-400">Project / Unit</span>
                        <div className="font-bold text-emerald-400">Prestige City · Villa #18</div>
                      </div>
                    </div>

                    <div className="p-2 bg-white/5 rounded-lg flex items-center justify-between text-xs">
                      <span className="flex items-center gap-1.5 text-neutral-300 text-[10px] font-mono">
                        <MapPin className="w-3.5 h-3.5 text-emerald-400" />
                        Google Map Pin &amp; Gate Pass Sent to WhatsApp
                      </span>
                      <span className="text-emerald-400 text-[9px] font-mono font-bold">Delivered</span>
                    </div>
                  </div>
                </div>
              )}

              {/* STEP 4: DEAL & PIPELINE SIMULATION */}
              {activeStep.id === 'deal-booking' && (
                <div className="space-y-3 animate-fade-in-scale">
                  <div className="grid grid-cols-3 gap-2">
                    {/* Column 1 */}
                    <div className="bg-[#141416] p-2 rounded-lg border border-white/10 space-y-1.5">
                      <div className="flex justify-between text-[10px] font-mono font-bold text-neutral-400">
                        <span>Qualified</span>
                        <span>14</span>
                      </div>
                      <div className="bg-[#1E1E22] p-1.5 rounded text-[10px] text-white">
                        <div className="font-bold">Rahul Khanna</div>
                        <div className="text-emerald-400 font-mono text-[9px]">₹1.40 Cr</div>
                      </div>
                    </div>

                    {/* Column 2 */}
                    <div className="bg-[#141416] p-2 rounded-lg border border-white/10 space-y-1.5">
                      <div className="flex justify-between text-[10px] font-mono font-bold text-amber-400">
                        <span>Site Visit Done</span>
                        <span>8</span>
                      </div>
                      <div className="bg-[#1E1E22] p-1.5 rounded text-[10px] text-white">
                        <div className="font-bold">Dr. Arvind Mehra</div>
                        <div className="text-emerald-400 font-mono text-[9px]">₹4.85 Cr</div>
                      </div>
                    </div>

                    {/* Column 3 */}
                    <div className="bg-emerald-950/40 p-2 rounded-lg border border-emerald-500/30 space-y-1.5">
                      <div className="flex justify-between text-[10px] font-mono font-bold text-emerald-400">
                        <span>Booking Won 🎉</span>
                        <span>12</span>
                      </div>
                      <div className="bg-[#141416] p-1.5 rounded text-[10px] text-white border border-emerald-500/40">
                        <div className="font-bold">Siddharth Varma</div>
                        <div className="text-emerald-300 font-mono text-[9px]">₹6.20 Cr</div>
                      </div>
                    </div>
                  </div>
                </div>
              )}

              {/* STEP 5: PAYMENT & COST SHEET SIMULATION */}
              {activeStep.id === 'payment-costsheet' && (
                <div className="space-y-3 animate-fade-in-scale">
                  <div className="bg-[#141416] rounded-xl border border-white/15 p-3.5 space-y-2.5">
                    <div className="flex items-center justify-between pb-2 border-b border-white/10">
                      <span className="font-serif text-sm font-bold text-white">Official Cost Sheet #CS-2026-89</span>
                      <span className="text-[10px] font-mono px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 font-bold">
                        10% Token Received
                      </span>
                    </div>

                    <div className="space-y-1.5 text-xs font-mono">
                      <div className="flex justify-between text-neutral-300">
                        <span>Base Price (250 Sq.Yd.)</span>
                        <span className="text-white font-bold">₹1,45,00,000</span>
                      </div>
                      <div className="flex justify-between text-neutral-300">
                        <span>Clubhouse &amp; PLC</span>
                        <span className="text-white font-bold">₹12,00,000</span>
                      </div>
                      <div className="flex justify-between text-neutral-300">
                        <span>GST &amp; Statutory (5%)</span>
                        <span className="text-white font-bold">₹7,85,000</span>
                      </div>
                      <div className="pt-1.5 border-t border-white/10 flex justify-between text-sm font-bold text-emerald-400">
                        <span>Total All-Inclusive</span>
                        <span>₹1,64,85,000</span>
                      </div>
                    </div>
                  </div>
                </div>
              )}

              {/* STEP 6: CUSTOMER VAULT SIMULATION */}
              {activeStep.id === 'customer-vault' && (
                <div className="space-y-3 animate-fade-in-scale">
                  <div className="bg-[#141416] rounded-xl border border-white/15 p-3.5 space-y-3">
                    <div className="flex items-center justify-between">
                      <div className="flex items-center gap-2">
                        <Lock className="w-4 h-4 text-emerald-400" />
                        <span className="font-serif text-sm font-bold text-white">Protected Customer Record</span>
                      </div>
                      <span className="text-[9px] font-mono px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 font-bold">
                        Role-Based Access
                      </span>
                    </div>

                    <div className="grid grid-cols-2 gap-2 text-xs">
                      <div className="bg-[#1E1E22] p-2 rounded-lg">
                        <div className="text-[9px] font-mono text-neutral-400">Phone Number Masking</div>
                        <div className="font-mono text-white font-bold">+91 98XXX XXX10</div>
                      </div>
                      <div className="bg-[#1E1E22] p-2 rounded-lg">
                        <div className="text-[9px] font-mono text-neutral-400">Access Permission</div>
                        <div className="font-mono text-emerald-400 font-bold">Admin &amp; Sourcing Mgr Only</div>
                      </div>
                    </div>

                    <div className="text-[10px] font-mono text-neutral-300 bg-white/5 p-2 rounded flex items-center justify-between">
                      <span>Ex-Employee Protection</span>
                      <span className="text-emerald-400 font-bold">1-Click Access Revocation</span>
                    </div>
                  </div>
                </div>
              )}
            </div>

            {/* Bottom Live Metric Strip */}
            <div className="pt-3 border-t border-white/10 flex items-center justify-between text-xs">
              <div className="flex items-center gap-2">
                <span className="w-2 h-2 rounded-full bg-emerald-400"></span>
                <span className="text-neutral-300 text-[11px] font-mono">
                  {activeStep.project} · {activeStep.unit}
                </span>
              </div>
              <span className="font-serif text-base font-bold text-white">
                {activeStep.dealValue}
              </span>
            </div>
          </div>

          {/* Right Column: Clean Editorial Explanation & Highlights */}
          <div className="lg:col-span-5 bg-white p-5 sm:p-7 lg:p-8 flex flex-col justify-between space-y-5">
            <div className="space-y-3">
              <div className="space-y-1">
                <div className="text-[10px] font-mono text-emerald-800 uppercase tracking-widest font-extrabold bg-emerald-50 px-2.5 py-0.5 rounded-full inline-block border border-emerald-300">
                  {activeStep.badge}
                </div>
                <h3 className="font-serif text-2xl sm:text-3xl text-[#0A0A0A] font-bold leading-tight">
                  {activeStep.title}
                </h3>
                <p className="text-xs font-mono text-emerald-700 font-bold">
                  {activeStep.tagline}
                </p>
              </div>

              <p className="text-xs sm:text-sm text-[#262522] font-normal leading-relaxed">
                {activeStep.summary}
              </p>

              {/* Key Bullet Highlights */}
              <div className="space-y-2 pt-3 border-t border-[#EAE7DD]">
                <div className="text-[10px] font-mono uppercase tracking-wider text-[#0A0A0A] font-extrabold">
                  System Advantages:
                </div>
                <div className="space-y-2">
                  {activeStep.keyPoints.map((point, pIdx) => (
                    <div key={pIdx} className="flex items-start gap-2.5 text-xs text-[#0A0A0A] font-semibold leading-snug">
                      <div className="w-4 h-4 rounded-full bg-emerald-600 flex items-center justify-center text-white shrink-0 mt-0.5 shadow-2xs">
                        <Check className="w-2.5 h-2.5 stroke-[3.5]" />
                      </div>
                      <span>{point}</span>
                    </div>
                  ))}
                </div>
              </div>
            </div>

            {/* Quick Demo CTA */}
            <div className="pt-3 border-t border-[#EAE7DD] flex items-center justify-between">
              <button
                onClick={onStartDemo}
                className="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#0A0A0A] hover:bg-[#262626] text-white text-xs font-bold uppercase tracking-wider transition-all cursor-pointer shadow-md hover:scale-105"
              >
                <span>Try This Workflow</span>
                <ArrowRight className="w-3.5 h-3.5" />
              </button>

              <button
                onClick={() => setActiveStepIndex((prev) => (prev === WORKFLOW_STEPS.length - 1 ? 0 : prev + 1))}
                className="inline-flex items-center gap-1.5 text-xs font-mono font-bold text-emerald-800 hover:text-emerald-950 transition-colors cursor-pointer"
              >
                <span>Next Step</span>
                <ChevronRight className="w-4 h-4" />
              </button>
            </div>
          </div>
        </div>

      </div>
    </section>
  );
};
