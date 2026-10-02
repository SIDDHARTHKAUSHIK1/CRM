import React, { useState } from 'react';
import {
  AlertCircle,
  CheckCircle2,
  XCircle,
  ArrowRight,
  TrendingDown,
  TrendingUp,
  MessageSquare,
  FileSpreadsheet,
  Clock,
  ShieldCheck,
  Zap,
  Sparkles,
  Lock,
  FileText,
  Check,
  MapPin,
  Building2,
  Users,
  CalendarCheck,
} from 'lucide-react';

interface PainPoint {
  id: string;
  icon: React.ComponentType<{ className?: string }>;
  title: string;
  subtitle: string;
  riskBadge: string;
  riskStat: string;
  impactStat: string;
  painTitle: string;
  pain: string;
  solutionTitle: string;
  solution: string;
  featureTag: string;
}

const PAIN_POINTS: PainPoint[] = [
  {
    id: '01',
    icon: MessageSquare,
    title: 'Property Enquiries Trapped in Personal WhatsApps',
    subtitle: 'Buyer chats and site visit requests on personal numbers lead to lost customer history.',
    riskBadge: 'Old Way: Lead Leakage',
    riskStat: 'Lost Customer History',
    impactStat: 'Centralized Chat Thread',
    painTitle: 'The Old, Messy Way',
    pain: 'Enquiries from property portals (99acres, MagicBricks), website forms, and Meta ads sit inside sales executives’ personal WhatsApp chats. When an executive leaves or takes off, buyer notes, budget preferences, and conversation histories disappear.',
    solutionTitle: 'Centralized WhatsApp & Email Inbox',
    solution: 'Every property enquiry flows into a single unified company dashboard. Sales executives reply under your verified company number while management maintains complete visibility and permanent record of all client communication.',
    featureTag: 'WhatsApp & Email Sync',
  },
  {
    id: '02',
    icon: FileSpreadsheet,
    title: 'Spreadsheet Chaos & No Visibility on Deal Status',
    subtitle: 'Disconnected Excel sheets leave founders and managers guessing daily site visits and bookings.',
    riskBadge: 'Old Way: Forecast Blindspots',
    riskStat: 'Deal Status Blindspots',
    impactStat: 'Visual Drag & Drop Board',
    painTitle: 'The Old, Messy Way',
    pain: 'Property leads, site visit schedules, and token bookings are scattered across multiple Google Sheets. Builders and agency owners have to constantly interrogate sales reps just to understand which customer visited which project and which deals are alive.',
    solutionTitle: 'Visual Drag-and-Drop Real Estate Pipeline',
    solution: 'Instantly visualize every deal stage from "New Enquiry" to "Site Visit Scheduled" to "Cost Sheet Sent" to "Booking Confirmed". Easily track deal values, assigned executives, and next follow-up actions at a glance.',
    featureTag: 'Visual Sales Pipeline',
  },
  {
    id: '03',
    icon: Clock,
    title: 'Delayed Buyer Follow-ups & Cold Enquiries',
    subtitle: 'Slow response times cause high-intent property buyers to contact competitor projects.',
    riskBadge: 'Old Way: Missed Follow-ups',
    riskStat: 'Missed Follow-up Calls',
    impactStat: 'Smart Team Reminders',
    painTitle: 'The Old, Messy Way',
    pain: 'A prospective buyer submits an enquiry for an apartment or plot and waits hours or days for a callback. By the time a sales executive responds, the buyer has already scheduled a site visit with another developer.',
    solutionTitle: 'Automated Follow-ups & Calendar Reminders',
    solution: 'Send immediate automated WhatsApp greetings with project brochures, automatically assign incoming leads to active sales reps, and schedule follow-up reminders so your team never forgets a customer again.',
    featureTag: 'Automated Lead Routing',
  },
  {
    id: '04',
    icon: FileText,
    title: 'Slow Cost Sheet & Payment Plan Delivery',
    subtitle: 'Manual calculation of unit prices, GST, and milestone payment schedules delays buyer decisions.',
    riskBadge: 'Old Way: Slow Proposals',
    riskStat: 'Manual Cost Calculations',
    impactStat: 'Instant PDF Cost Sheets',
    painTitle: 'The Old, Messy Way',
    pain: 'Sales executives juggle calculators, Excel price lists, and outdated templates to calculate unit pricing and payment plans. The back-and-forth delay cools buyer excitement and introduces calculation mistakes.',
    solutionTitle: '30-Second Branded PDF Cost Sheets',
    solution: 'Select available apartments, plots, or villas from your pre-loaded project inventory, apply authorized discounts or milestone payment plans, and send a clean, branded PDF cost sheet directly to the buyer’s WhatsApp in seconds.',
    featureTag: 'Instant PDF Cost Sheets',
  },
  {
    id: '05',
    icon: Lock,
    title: 'Sales Executive Turnover & Buyer Data Theft',
    subtitle: 'Unrestricted customer directories expose your business to contact list poaching.',
    riskBadge: 'Old Way: Security Vulnerability',
    riskStat: 'Ex-Employee Contact Risk',
    impactStat: 'Role-Based Access Vault',
    painTitle: 'The Old, Messy Way',
    pain: 'When a sales executive leaves, they can easily export your entire directory of high-net-worth property buyers and investors, taking valuable customer relationships directly to competing brokers or developers.',
    solutionTitle: 'Role-Based Customer Vault & Phone Masking',
    solution: 'Sales executives only see their assigned leads with masked phone numbers. If an employee leaves, revoke access in 1 click while all buyer records, notes, and past site visit logs remain safely in your company vault.',
    featureTag: 'Strict Role Permissions',
  },
];

export const ProblemVsSolution: React.FC = () => {
  const [activeCard, setActiveCard] = useState<number>(0);

  return (
    <section
      id="problem-solution"
      className="py-12 lg:py-18 bg-[#FAF9F5] border-b border-[#E2DFD4] relative overflow-hidden"
    >
      {/* Subtle luxury architectural grid texture */}
      <div className="absolute inset-0 opacity-[0.03] pointer-events-none bg-[radial-gradient(#0A0A0A_1px,transparent_1px)] [background-size:28px_28px]"></div>

      <div className="max-w-5xl mx-auto px-4 sm:px-6 relative z-10">
        {/* Section Header */}
        <div className="text-center max-w-3xl mx-auto mb-10 sm:mb-12 space-y-3">
          <div className="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-[#1A1A1A] text-white text-[11px] font-mono tracking-widest uppercase font-bold shadow-md">
            <AlertCircle className="w-3.5 h-3.5 text-amber-400" />
            <span>SALES CHALLENGES IN REAL ESTATE</span>
          </div>

          <h2 className="font-serif text-2xl sm:text-4xl lg:text-[42px] text-[#0A0A0A] font-normal tracking-tight leading-[1.15]">
            Is Your Sales Team Struggling with{' '}
            <span className="italic font-normal text-emerald-800 underline decoration-emerald-500/30 underline-offset-6">
              Daily Bottlenecks?
            </span>
          </h2>

          <p className="text-xs sm:text-sm text-[#55524A] font-normal leading-relaxed max-w-2xl mx-auto">
            When property enquiries, customer follow-ups, and site visits are scattered across personal WhatsApps and spreadsheets, high-value deals slip through the cracks. Here is how our CRM gives your team complete operational control.
          </p>

          {/* Quick Metrics Bar */}
          <div className="pt-1 flex flex-wrap items-center justify-center gap-2.5 sm:gap-4 text-xs font-mono text-[#333333] font-semibold">
            <span className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-100/80 text-red-800 border border-red-200 text-[11px]">
              <TrendingDown className="w-3.5 h-3.5 text-red-600" />
              Scattered Inboxes &amp; Chats
            </span>
            <span className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-100/80 text-emerald-800 border border-emerald-200 text-[11px]">
              <TrendingUp className="w-3.5 h-3.5 text-emerald-600" />
              Unified WhatsApp &amp; Email
            </span>
            <span className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#1A1A1A]/5 text-[#1A1A1A] border border-[#DDD9CD] text-[11px]">
              <ShieldCheck className="w-3.5 h-3.5 text-emerald-700" />
              Protected Buyer Vault
            </span>
          </div>
        </div>

        {/* Cleaner, Balanced 2-Column Comparison Architecture */}
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
          
          {/* LEFT COLUMN: Sticky Overview, Live Audit & Featured Plot Inventory */}
          <div className="lg:col-span-4 space-y-4 lg:sticky lg:top-20">
            
            {/* Main Overview Showcase Card */}
            <div className="rounded-xl overflow-hidden border border-[#D5D1C4] shadow-lg bg-[#0A0A0A] text-white relative group">
              <div className="relative aspect-[16/10] overflow-hidden bg-neutral-900">
                <img
                  src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80"
                  alt="Modern architectural workspace"
                  referrerPolicy="no-referrer"
                  className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-85"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-[#0A0A0A] via-[#0A0A0A]/30 to-transparent"></div>

                <div className="absolute top-3.5 left-3.5 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-black/75 backdrop-blur-md border border-white/20 text-xs font-mono text-emerald-400 font-bold shadow-md">
                  <span className="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                  Real Estate Transformation
                </div>
              </div>

              <div className="p-4 sm:p-5 space-y-3">
                <div className="space-y-0.5">
                  <span className="text-[10px] font-mono tracking-widest text-emerald-400 uppercase font-bold">
                    EXECUTIVE OVERVIEW
                  </span>
                  <h3 className="font-serif text-lg sm:text-xl text-white font-normal leading-snug">
                    From Scattered Leads to Closed Bookings
                  </h3>
                  <p className="text-[11px] text-neutral-300 font-light leading-relaxed">
                    Stop letting high-value property buyers slip away. Real Estate CRM standardizes every lead touchpoint into an organized, high-converting pipeline.
                  </p>
                </div>

                {/* Stat Chips */}
                <div className="pt-2 border-t border-white/10 grid grid-cols-2 gap-2">
                  <div className="p-2.5 rounded-lg bg-white/5 border border-white/10">
                    <div className="text-lg font-serif text-emerald-400 font-bold">
                      +42.8%
                    </div>
                    <div className="text-[9px] font-mono text-neutral-400 uppercase tracking-wide">
                      Booking Rate Boost
                    </div>
                  </div>
                  <div className="p-2.5 rounded-lg bg-white/5 border border-white/10">
                    <div className="text-lg font-serif text-white font-bold">
                      &lt; 90s
                    </div>
                    <div className="text-[9px] font-mono text-neutral-400 uppercase tracking-wide">
                      Response SLA
                    </div>
                  </div>
                </div>

                {/* Deal Leakage Audit Mini Module */}
                <div className="p-2.5 rounded-lg bg-[#141414] border border-white/10 space-y-1">
                  <div className="flex items-center justify-between text-[11px] font-mono text-neutral-400">
                    <span>Est. Monthly Lost Deals:</span>
                    <span className="text-red-400 font-bold">- ₹35 Lakh / mo</span>
                  </div>
                  <div className="flex items-center justify-between text-[11px] font-mono text-emerald-400 font-bold pt-1 border-t border-white/10">
                    <span>Recovered With CRM:</span>
                    <span className="text-emerald-300 font-serif text-xs font-extrabold">+ ₹28 Lakh / mo</span>
                  </div>
                </div>

                <a
                  href="#workflow"
                  className="w-full py-2.5 px-3.5 rounded-lg bg-white hover:bg-neutral-100 text-[#0A0A0A] text-[11px] font-bold uppercase tracking-wider flex items-center justify-center gap-1.5 transition-all shadow-sm group/btn"
                >
                  <span>Explore Real Estate Workflow</span>
                  <ArrowRight className="w-3 h-3 group-hover/btn:translate-x-1 transition-transform" />
                </a>
              </div>
            </div>

            {/* Live Plot & Project Inventory Showcase Card */}
            <div className="rounded-xl overflow-hidden border border-[#D5D1C4] shadow-lg bg-[#0A0A0A] text-white relative group">
              <div className="relative aspect-[16/9] overflow-hidden bg-neutral-900">
                <img
                  src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1200&q=80"
                  alt="Green Valley Meadows Residential Plots"
                  referrerPolicy="no-referrer"
                  className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-85"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-[#0A0A0A] via-[#0A0A0A]/30 to-transparent"></div>

                <div className="absolute top-2.5 left-2.5 inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-black/75 backdrop-blur-md border border-white/20 text-[10px] font-mono text-emerald-400 font-bold shadow-sm">
                  <span className="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                  Residential Plot Inventory
                </div>

                <div className="absolute top-2.5 right-2.5 inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-950/80 backdrop-blur-md border border-emerald-500/40 text-[9px] font-mono text-emerald-300 font-bold shadow-sm">
                  82% Booked
                </div>

                <div className="absolute bottom-2.5 left-3 right-3 text-white">
                  <div className="font-serif text-base font-bold leading-tight">
                    Green Valley Meadows — Plots
                  </div>
                  <div className="flex items-center gap-1 text-[11px] text-neutral-300 font-mono mt-0.5">
                    <MapPin className="w-3 h-3 text-emerald-400 shrink-0" />
                    <span>Sector 150, Noida Express Corridor</span>
                  </div>
                </div>
              </div>

              <div className="p-3.5 space-y-2.5 bg-[#0A0A0A]">
                <div className="space-y-1 text-[11px] font-mono text-neutral-400">
                  <div className="flex items-center justify-between">
                    <span>Plot Sizes:</span>
                    <span className="text-white font-bold">200 to 500 Sq. Yd.</span>
                  </div>
                  <div className="flex items-center justify-between">
                    <span>Starting Price:</span>
                    <span className="text-emerald-400 font-bold font-serif text-xs">₹85 Lakh - ₹1.75 Cr</span>
                  </div>
                  <div className="flex items-center justify-between">
                    <span>Approvals:</span>
                    <span className="text-white font-semibold">RERA Approved</span>
                  </div>
                </div>

                {/* Master Plan Progress Meter */}
                <div className="p-2 rounded-lg bg-white/5 border border-white/10 space-y-1">
                  <div className="flex justify-between items-center text-[10px] font-mono">
                    <span className="text-neutral-400">Inventory:</span>
                    <span className="text-emerald-400 font-bold">98 Sold / 22 Avail</span>
                  </div>
                  <div className="w-full h-1.5 rounded-full bg-white/10 overflow-hidden">
                    <div className="h-full bg-gradient-to-r from-emerald-500 to-teal-400 rounded-full w-[82%]"></div>
                  </div>
                </div>
              </div>
            </div>

            {/* Platform Safeguards Card */}
            <div className="p-4 rounded-xl bg-white border border-[#D5D1C4] shadow-sm space-y-2">
              <div className="text-[11px] font-mono font-bold uppercase tracking-wider text-[#0A0A0A] flex items-center gap-1.5">
                <ShieldCheck className="w-3.5 h-3.5 text-emerald-600" />
                <span>Real Estate Safeguards</span>
              </div>
              <div className="space-y-1.5 text-[11px] text-[#44413A]">
                <div className="flex items-start gap-1.5">
                  <Check className="w-3 h-3 text-emerald-600 shrink-0 mt-0.5" />
                  <span><strong>WhatsApp Cloud Sync</strong> — instant floor plans.</span>
                </div>
                <div className="flex items-start gap-1.5">
                  <Check className="w-3 h-3 text-emerald-600 shrink-0 mt-0.5" />
                  <span><strong>Auto-Routing</strong> — instant executive alerts.</span>
                </div>
                <div className="flex items-start gap-1.5">
                  <Check className="w-3 h-3 text-emerald-600 shrink-0 mt-0.5" />
                  <span><strong>Number Masking</strong> — zero contact poaching.</span>
                </div>
              </div>
            </div>
          </div>

          {/* RIGHT COLUMN: The 5 High-Impact Challenge vs Solution Comparison Cards */}
          <div className="lg:col-span-8 space-y-3.5">
            {PAIN_POINTS.map((item, idx) => {
              const Icon = item.icon;
              const isSelected = activeCard === idx;

              return (
                <div
                  key={idx}
                  onClick={() => setActiveCard(idx)}
                  className={`rounded-xl border transition-all duration-300 overflow-hidden cursor-pointer ${
                    isSelected
                      ? 'bg-white border-[#0A0A0A] shadow-lg ring-1 ring-[#0A0A0A]/10'
                      : 'bg-[#FDFCFA] border-[#DDD9CD] hover:border-[#B8B3A2] hover:shadow-sm'
                  }`}
                >
                  <div className="p-4 sm:p-5 space-y-3">
                    {/* Header Row: Number + Icon + Title + Impact Badges */}
                    <div className="flex flex-wrap items-start justify-between gap-3 pb-3 border-b border-[#ECE9DF]">
                      <div className="flex items-center gap-3">
                        <div
                          className={`w-9 h-9 rounded-lg flex items-center justify-center transition-colors shadow-xs ${
                            isSelected
                              ? 'bg-[#0A0A0A] text-white'
                              : 'bg-[#EAE7DD] text-[#333333]'
                          }`}
                        >
                          <Icon className="w-4 h-4" />
                        </div>
                        <div>
                          <div className="flex items-center gap-2">
                            <span className="font-mono text-[11px] font-bold text-neutral-400">
                              {item.id}
                            </span>
                            <h3 className="font-serif text-lg sm:text-xl text-[#0A0A0A] font-bold tracking-tight">
                              {item.title}
                            </h3>
                          </div>
                          <p className="text-[11px] text-[#66635B] mt-0.5 font-normal">
                            {item.subtitle}
                          </p>
                        </div>
                      </div>

                      {/* Stat Indicators */}
                      <div className="flex items-center gap-1.5 shrink-0">
                        <span className="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-red-100 text-red-800 text-[10px] font-mono font-bold border border-red-200">
                          <TrendingDown className="w-3 h-3 text-red-600" />
                          {item.riskStat}
                        </span>
                        <span className="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[10px] font-mono font-bold border border-emerald-200">
                          <TrendingUp className="w-3 h-3 text-emerald-600" />
                          {item.impactStat}
                        </span>
                      </div>
                    </div>

                    {/* Side-by-Side Dual Comparison Modules */}
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-3 items-stretch">
                      
                      {/* Left: The Challenge / Pain Point */}
                      <div className="p-3.5 sm:p-4 rounded-lg bg-[#F6F4EE] border border-[#E0DCD0] space-y-2 flex flex-col justify-between">
                        <div className="space-y-1.5">
                          <div className="flex items-center justify-between">
                            <div className="flex items-center gap-1.5 text-[11px] font-mono font-bold uppercase tracking-wider text-red-700">
                              <XCircle className="w-3.5 h-3.5 text-red-500 shrink-0" />
                              <span>{item.painTitle}</span>
                            </div>
                            <span className="text-[9px] font-mono text-neutral-400 uppercase font-semibold">
                              Unorganized
                            </span>
                          </div>
                          <p className="text-xs text-[#3D3A34] leading-relaxed font-normal">
                            {item.pain}
                          </p>
                        </div>
                      </div>

                      {/* Right: How Real Estate CRM Solves It */}
                      <div className="p-3.5 sm:p-4 rounded-lg bg-[#0A0A0A] text-white border border-neutral-800 space-y-2 flex flex-col justify-between shadow-xs">
                        <div className="space-y-1.5">
                          <div className="flex items-center justify-between">
                            <div className="flex items-center gap-1.5 text-[11px] font-mono font-bold uppercase tracking-wider text-emerald-400">
                              <CheckCircle2 className="w-3.5 h-3.5 text-emerald-400 shrink-0" />
                              <span>{item.solutionTitle}</span>
                            </div>
                            <span className="text-[9px] font-mono px-1.5 py-0.5 rounded-full bg-emerald-400/20 text-emerald-300 font-bold border border-emerald-500/30">
                              Solved
                            </span>
                          </div>
                          <p className="text-xs text-neutral-200 leading-relaxed font-light">
                            {item.solution}
                          </p>
                        </div>

                        {/* Feature Tag & Automation Status */}
                        <div className="pt-2 border-t border-white/10 flex items-center justify-between text-[10px] font-mono text-neutral-400">
                          <span className="flex items-center gap-1 text-emerald-300 font-medium">
                            <Sparkles className="w-3 h-3 text-emerald-400" />
                            {item.featureTag}
                          </span>
                          <span className="text-white/60 text-[9px]">Automated</span>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              );
            })}
          </div>

        </div>
      </div>
    </section>
  );
};


