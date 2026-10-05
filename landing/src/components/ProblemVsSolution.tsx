import React, { useState } from 'react';
import {
  MessageSquare,
  FileSpreadsheet,
  Clock,
  ShieldCheck,
  Zap,
  Sparkles,
  Lock,
  FileText,
  Check,
  ArrowRight,
  TrendingUp,
  TrendingDown,
  Building2,
  Calendar,
  Send,
  Eye,
  CheckCircle2,
  XCircle,
  AlertTriangle,
  Users,
  Smartphone,
  MapPin,
  Kanban,
  FileCheck,
} from 'lucide-react';

export const ProblemVsSolution: React.FC = () => {
  const [viewMode, setViewMode] = useState<'solution' | 'problem'>('solution');
  const [activeKanbanTab, setActiveKanbanTab] = useState<'all' | 'plots' | 'flats' | 'villas'>('all');
  const [chatSent, setChatSent] = useState(false);
  const [costSheetSent, setCostSheetSent] = useState(false);

  return (
    <section
      id="problem-solution"
      className="py-12 lg:py-20 bg-[#FAF9F5] border-b border-[#E2DFD4] relative overflow-hidden"
    >
      {/* Subtle luxury architectural grid texture */}
      <div className="absolute inset-0 opacity-[0.03] pointer-events-none bg-[radial-gradient(#0A0A0A_1px,transparent_1px)] [background-size:28px_28px]"></div>

      <div className="max-w-6xl mx-auto px-4 sm:px-6 relative z-10 space-y-8 sm:space-y-10">
        {/* Section Header */}
        <div className="text-center max-w-3xl mx-auto space-y-3">
          <div className="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-[#1A1A1A] text-white text-[11px] font-mono tracking-widest uppercase font-bold shadow-sm">
            <Sparkles className="w-3.5 h-3.5 text-emerald-400" />
            <span>TRANSFORM YOUR SALES OPERATIONS</span>
          </div>

          <h2 className="font-serif text-2xl sm:text-4xl lg:text-[42px] text-[#0A0A0A] font-bold tracking-tight leading-[1.15]">
            Replace Spreadsheets with a{' '}
            <span className="italic font-normal text-emerald-800 underline decoration-emerald-500/30 underline-offset-6">
              Visual Sales Engine
            </span>
          </h2>

          <p className="text-xs sm:text-sm text-[#55524A] font-normal leading-relaxed max-w-2xl mx-auto">
            Eliminate lost WhatsApp chats, disorganized Excel sheets, and delayed cost sheets with purpose-built real estate workflows.
          </p>

          {/* Interactive Mode Switcher Pill Toggle */}
          <div className="pt-3 flex items-center justify-center">
            <div className="p-1 rounded-full bg-[#EAE7DD] border border-[#D8D5CA] inline-flex items-center gap-1 shadow-inner">
              <button
                onClick={() => setViewMode('solution')}
                className={`px-4 sm:px-5 py-2 rounded-full text-xs font-mono font-bold uppercase tracking-wider transition-all cursor-pointer flex items-center gap-2 ${
                  viewMode === 'solution'
                    ? 'bg-[#0A0A0A] text-white shadow-md'
                    : 'text-[#55524A] hover:text-black'
                }`}
              >
                <Sparkles className={`w-3.5 h-3.5 ${viewMode === 'solution' ? 'text-emerald-400' : ''}`} />
                <span>The Modern CRM Solution</span>
              </button>

              <button
                onClick={() => setViewMode('problem')}
                className={`px-4 sm:px-5 py-2 rounded-full text-xs font-mono font-bold uppercase tracking-wider transition-all cursor-pointer flex items-center gap-2 ${
                  viewMode === 'problem'
                    ? 'bg-red-900 text-white shadow-md'
                    : 'text-[#55524A] hover:text-red-700'
                }`}
              >
                <AlertTriangle className={`w-3.5 h-3.5 ${viewMode === 'problem' ? 'text-amber-400' : ''}`} />
                <span>The Old Friction (5 Bottlenecks)</span>
              </button>
            </div>
          </div>
        </div>

        {/* VIEW 1: GORGEOUS INTERACTIVE BENTO GRID (THE MODERN CRM SOLUTION) */}
        {viewMode === 'solution' ? (
          <div className="space-y-4 animate-fade-in-scale">
            {/* Top Row: 2 Major Feature Bento Cards */}
            <div className="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-5 items-stretch">
              
              {/* BENTO 1: Centralized WhatsApp & Multi-Channel Lead Hub (7 cols) */}
              <div className="lg:col-span-7 bg-white rounded-2xl border-2 border-[#D8D5CA] p-5 sm:p-6 space-y-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                <div className="space-y-2">
                  <div className="flex items-center justify-between">
                    <div className="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-800 text-[10px] font-mono font-bold border border-emerald-200">
                      <MessageSquare className="w-3.5 h-3.5 text-emerald-600" />
                      <span>OFFICIAL WHATSAPP &amp; LEAD INBOX</span>
                    </div>
                    <span className="text-[10px] font-mono font-bold text-neutral-400 uppercase">
                      2-Way Sync
                    </span>
                  </div>

                  <h3 className="font-serif text-xl sm:text-2xl text-[#0A0A0A] font-bold">
                    Capture &amp; Reply on WhatsApp in One Unified Dashboard
                  </h3>
                  <p className="text-xs text-[#55524A] leading-relaxed">
                    Leads from website forms, Meta Ads, and portals flow directly into your CRM. Reps reply from your verified business number with complete chat history saved forever.
                  </p>
                </div>

                {/* Interactive Simulated WhatsApp Chat Widget */}
                <div className="p-3.5 sm:p-4 rounded-xl bg-[#F4F3EE] border border-[#E0DCD0] space-y-3 font-sans">
                  {/* Lead Header Bar */}
                  <div className="flex items-center justify-between text-xs pb-2 border-b border-[#D8D5CA]">
                    <div className="flex items-center gap-2">
                      <div className="w-7 h-7 rounded-full bg-emerald-700 text-white font-bold flex items-center justify-center text-xs">
                        VS
                      </div>
                      <div>
                        <div className="font-bold text-[#0A0A0A] text-xs">Vikramaditya Singhania</div>
                        <div className="text-[10px] font-mono text-[#66635B]">Meta Ad: Sector 150 Villa Campaign</div>
                      </div>
                    </div>
                    <span className="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[10px] font-mono font-bold border border-emerald-300">
                      Hot Buyer
                    </span>
                  </div>

                  {/* Chat Bubbles */}
                  <div className="space-y-2 text-xs">
                    {/* Buyer message */}
                    <div className="max-w-[85%] bg-white p-2.5 rounded-lg rounded-tl-none border border-[#DDD9CD] text-[#222222] space-y-1 shadow-2xs">
                      <p className="text-[11px] leading-relaxed">
                        “Hi! I saw your Green Valley project on Instagram. Can you send the 300 Sq. Yd. plot layout and payment plan?”
                      </p>
                      <span className="text-[9px] text-neutral-400 font-mono block text-right">10:42 AM</span>
                    </div>

                    {/* CRM Executive reply */}
                    <div className="max-w-[85%] ml-auto bg-[#0A0A0A] text-white p-2.5 rounded-lg rounded-tr-none space-y-1.5 shadow-xs">
                      <p className="text-[11px] leading-relaxed text-neutral-100">
                        “Hello Vikramaditya! Attached is the master layout and 3-year milestone schedule. Would Saturday 11 AM work for a site visit?”
                      </p>
                      <div className="flex items-center justify-between text-[9px] text-emerald-400 font-mono pt-1 border-t border-white/15">
                        <span className="flex items-center gap-1">
                          <FileText className="w-3 h-3 text-emerald-400" />
                          Brochure_Sector150.pdf
                        </span>
                        <span>Delivered ✓✓</span>
                      </div>
                    </div>
                  </div>

                  {/* Interactive Action Bar */}
                  <div className="flex items-center justify-between pt-1 text-[11px] font-mono">
                    <span className="text-[#66635B]">Assigned to: <strong className="text-black">Amit Verma (Sales Exec)</strong></span>
                    <button
                      onClick={() => setChatSent(!chatSent)}
                      className="px-2.5 py-1 rounded bg-white hover:bg-neutral-100 text-black border border-[#D5D1C4] text-[10px] font-bold cursor-pointer transition-colors shadow-2xs"
                    >
                      {chatSent ? '✓ Response Logged in CRM' : 'Simulate Quick Reply'}
                    </button>
                  </div>
                </div>

                <div className="grid grid-cols-3 gap-2 pt-1 text-center font-mono text-[10px] text-[#44413A]">
                  <div className="p-2 rounded-lg bg-[#FAF9F5] border border-[#E8E5DC]">
                    <div className="font-bold text-emerald-800 text-xs">Zero Loss</div>
                    <div>Permanent History</div>
                  </div>
                  <div className="p-2 rounded-lg bg-[#FAF9F5] border border-[#E8E5DC]">
                    <div className="font-bold text-[#0A0A0A] text-xs">Round-Robin</div>
                    <div>Auto Assignment</div>
                  </div>
                  <div className="p-2 rounded-lg bg-[#FAF9F5] border border-[#E8E5DC]">
                    <div className="font-bold text-emerald-800 text-xs">Masked</div>
                    <div>Phone Protection</div>
                  </div>
                </div>
              </div>

              {/* BENTO 2: Visual Real Estate Kanban Pipeline (5 cols) */}
              <div className="lg:col-span-5 bg-white rounded-2xl border-2 border-[#D8D5CA] p-5 sm:p-6 space-y-4 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                <div className="space-y-2">
                  <div className="flex items-center justify-between">
                    <div className="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-purple-50 text-purple-800 text-[10px] font-mono font-bold border border-purple-200">
                      <Kanban className="w-3.5 h-3.5 text-purple-600" />
                      <span>VISUAL PIPELINE</span>
                    </div>
                    <span className="text-[10px] font-mono font-bold text-emerald-800">
                      Live Deal Flow
                    </span>
                  </div>

                  <h3 className="font-serif text-xl sm:text-2xl text-[#0A0A0A] font-bold">
                    Visual Property Deal Board
                  </h3>
                  <p className="text-xs text-[#55524A] leading-relaxed">
                    Track every prospect across stages: New Enquiry, Site Visit Scheduled, Negotiation, and Token Won.
                  </p>
                </div>

                {/* Mini Kanban Columns Visual */}
                <div className="p-3.5 rounded-xl bg-[#FAF9F5] border border-[#E0DCD0] space-y-2.5">
                  <div className="flex items-center justify-between pb-1.5 border-b border-[#E2DFD4] text-[10px] font-mono">
                    <span className="font-bold text-[#0A0A0A]">PIPELINE: ₹18.4 CR ACTIVE</span>
                    <span className="text-emerald-800 font-bold">38 Deals</span>
                  </div>

                  {/* 3 Mini Columns */}
                  <div className="grid grid-cols-3 gap-2">
                    {/* Col 1 */}
                    <div className="space-y-1.5">
                      <div className="text-[9px] font-mono font-bold uppercase text-neutral-500 flex justify-between">
                        <span>Enquiry</span>
                        <span className="text-neutral-400">14</span>
                      </div>
                      <div className="p-2 rounded-md bg-white border border-[#DDD9CD] text-[10px] space-y-1 shadow-2xs">
                        <div className="font-bold text-[#0A0A0A] truncate">V. Singhania</div>
                        <div className="text-emerald-700 font-serif font-bold text-[11px]">₹85 Lakh</div>
                        <span className="inline-block text-[8px] font-mono px-1 py-0.2 rounded bg-amber-50 text-amber-800">Plot #14</span>
                      </div>
                    </div>

                    {/* Col 2 */}
                    <div className="space-y-1.5">
                      <div className="text-[9px] font-mono font-bold uppercase text-purple-700 flex justify-between">
                        <span>Site Visit</span>
                        <span className="text-purple-600">6</span>
                      </div>
                      <div className="p-2 rounded-md bg-purple-50/60 border border-purple-200 text-[10px] space-y-1 shadow-2xs">
                        <div className="font-bold text-[#0A0A0A] truncate">Pooja Sharma</div>
                        <div className="text-emerald-700 font-serif font-bold text-[11px]">₹1.15 Cr</div>
                        <span className="inline-block text-[8px] font-mono px-1 py-0.2 rounded bg-purple-100 text-purple-800">Sat 11 AM</span>
                      </div>
                    </div>

                    {/* Col 3 */}
                    <div className="space-y-1.5">
                      <div className="text-[9px] font-mono font-bold uppercase text-emerald-700 flex justify-between">
                        <span>Won 🎉</span>
                        <span className="text-emerald-600">9</span>
                      </div>
                      <div className="p-2 rounded-md bg-emerald-50/60 border border-emerald-200 text-[10px] space-y-1 shadow-2xs">
                        <div className="font-bold text-[#0A0A0A] truncate">Dr. Mehra</div>
                        <div className="text-emerald-800 font-serif font-bold text-[11px]">₹1.66 Cr</div>
                        <span className="inline-block text-[8px] font-mono px-1 py-0.2 rounded bg-emerald-100 text-emerald-800">Token Paid</span>
                      </div>
                    </div>
                  </div>
                </div>

                <div className="p-2.5 rounded-lg bg-[#0A0A0A] text-white flex items-center justify-between text-xs font-mono">
                  <span className="text-neutral-300 text-[11px]">Next: Auto Site Visit Alert</span>
                  <span className="text-emerald-400 font-bold">1-Click Drag</span>
                </div>
              </div>

            </div>

            {/* Bottom Row: 3 Modular Bento Cards */}
            <div className="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
              
              {/* BENTO 3: 30-Second PDF Cost Sheet Generator */}
              <div className="bg-white rounded-2xl border-2 border-[#D8D5CA] p-5 space-y-3.5 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                <div className="space-y-2">
                  <div className="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-amber-50 text-amber-800 text-[10px] font-mono font-bold border border-amber-200">
                    <FileText className="w-3.5 h-3.5 text-amber-600" />
                    <span>30-SEC COST SHEETS</span>
                  </div>
                  <h4 className="font-serif text-lg font-bold text-[#0A0A0A]">
                    Branded PDF Quotations
                  </h4>
                  <p className="text-xs text-[#55524A] leading-relaxed">
                    Select plots or flats from inventory and dispatch itemized payment schedules directly to WhatsApp.
                  </p>
                </div>

                {/* Mini Cost Sheet Preview */}
                <div className="p-3 rounded-lg bg-[#FAF9F5] border border-[#E0DCD0] text-[11px] font-mono space-y-1.5">
                  <div className="flex justify-between font-bold text-[#0A0A0A]">
                    <span>The Palms Villa #08</span>
                    <span className="text-emerald-700">₹1.66 Cr</span>
                  </div>
                  <div className="text-[10px] text-[#66635B] space-y-0.5 pt-1 border-t border-[#EAE7DD]">
                    <div className="flex justify-between">
                      <span>• 10% Booking Token:</span>
                      <span className="font-semibold text-black">₹16.6 Lakh</span>
                    </div>
                    <div className="flex justify-between">
                      <span>• 80% Construction Linked:</span>
                      <span className="font-semibold text-black">₹1.32 Cr</span>
                    </div>
                    <div className="flex justify-between">
                      <span>• 10% Possession:</span>
                      <span className="font-semibold text-black">₹16.6 Lakh</span>
                    </div>
                  </div>
                </div>

                <button
                  onClick={() => setCostSheetSent(!costSheetSent)}
                  className="w-full py-2 px-3 rounded-lg bg-[#0A0A0A] hover:bg-[#222222] text-white text-[11px] font-mono font-bold uppercase tracking-wider flex items-center justify-center gap-1.5 cursor-pointer transition-colors shadow-xs"
                >
                  <Send className="w-3 h-3 text-emerald-400" />
                  <span>{costSheetSent ? '✓ Sent to WhatsApp' : 'Generate & Send PDF'}</span>
                </button>
              </div>

              {/* BENTO 4: Team Calendar & Follow-up Reminders */}
              <div className="bg-white rounded-2xl border-2 border-[#D8D5CA] p-5 space-y-3.5 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                <div className="space-y-2">
                  <div className="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-blue-50 text-blue-800 text-[10px] font-mono font-bold border border-blue-200">
                    <Calendar className="w-3.5 h-3.5 text-blue-600" />
                    <span>CALENDAR &amp; REMINDERS</span>
                  </div>
                  <h4 className="font-serif text-lg font-bold text-[#0A0A0A]">
                    Automated Site Visit Alerts
                  </h4>
                  <p className="text-xs text-[#55524A] leading-relaxed">
                    Never forget a site visit or call. Automatic alerts notify sales reps on mobile with Google Maps pins.
                  </p>
                </div>

                {/* Calendar Reminder Widget */}
                <div className="p-3 rounded-lg bg-[#FAF9F5] border border-[#E0DCD0] space-y-2">
                  <div className="flex items-center justify-between text-[11px] font-mono font-bold text-[#0A0A0A]">
                    <span className="flex items-center gap-1.5 text-blue-700">
                      <Clock className="w-3.5 h-3.5" />
                      Saturday 11:00 AM
                    </span>
                    <span className="px-1.5 py-0.2 rounded bg-blue-100 text-blue-800 text-[9px]">
                      Confirmed
                    </span>
                  </div>
                  <div className="text-xs text-[#333333] font-medium">
                    Sector 150 Experience Center Walkthrough with Sunil Narang
                  </div>
                  <div className="flex items-center gap-1 text-[10px] text-emerald-700 font-mono">
                    <MapPin className="w-3 h-3" />
                    <span>Location Pin Sent on WhatsApp</span>
                  </div>
                </div>

                <div className="p-2 rounded-lg bg-[#FAF9F5] border border-[#E8E5DC] text-[10px] font-mono text-center text-[#44413A] font-bold">
                  ✓ 0 Missed Follow-ups
                </div>
              </div>

              {/* BENTO 5: Role-Based Buyer Vault & Data Protection */}
              <div className="bg-white rounded-2xl border-2 border-[#D8D5CA] p-5 space-y-3.5 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between">
                <div className="space-y-2">
                  <div className="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-800 text-[10px] font-mono font-bold border border-emerald-200">
                    <Lock className="w-3.5 h-3.5 text-emerald-600" />
                    <span>BUYER DATA VAULT</span>
                  </div>
                  <h4 className="font-serif text-lg font-bold text-[#0A0A0A]">
                    Role-Based Data Security
                  </h4>
                  <p className="text-xs text-[#55524A] leading-relaxed">
                    Protect high-net-worth investor directories. When reps leave, revoke access in 1-click while data stays safe.
                  </p>
                </div>

                {/* Masked Phone & Security Card */}
                <div className="p-3 rounded-lg bg-[#0A0A0A] text-white space-y-2">
                  <div className="flex items-center justify-between text-[10px] font-mono">
                    <span className="text-neutral-400">Buyer Contact:</span>
                    <span className="text-emerald-400 font-bold">Masked</span>
                  </div>
                  <div className="font-mono text-sm font-bold text-white tracking-widest">
                    +91 987•• ••890
                  </div>
                  <div className="flex items-center justify-between text-[9px] font-mono text-neutral-400 pt-1 border-t border-white/10">
                    <span>Export Permission:</span>
                    <span className="text-red-400 font-bold">Blocked ✕</span>
                  </div>
                </div>

                <div className="p-2 rounded-lg bg-emerald-50 border border-emerald-200 text-[10px] font-mono text-center text-emerald-800 font-bold">
                  ✓ 1-Click Employee Offboarding
                </div>
              </div>

            </div>
          </div>
        ) : (
          /* VIEW 2: THE 5 REAL ESTATE BOTTLENECKS (FRICTION VIEW) */
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 animate-fade-in-scale">
            {[
              {
                num: '01',
                icon: MessageSquare,
                title: 'Trapped on Personal WhatsApps',
                desc: 'Buyer enquiries sit on reps’ personal phones. When an executive resigns, client notes, budget tiers, and deal history disappear forever.',
                impact: 'Lost Customer History',
              },
              {
                num: '02',
                icon: FileSpreadsheet,
                title: 'Spreadsheet Chaos & Blindspots',
                desc: 'Scattered Excel sheets force builders and managers to constantly interrogate sales reps just to understand which site visits happened.',
                impact: 'Deal Status Blindspots',
              },
              {
                num: '03',
                icon: Clock,
                title: 'Delayed Follow-ups & Cold Leads',
                desc: 'Enquiries wait hours or days for a callback. By the time a rep responds, high-intent buyers have already booked with another builder.',
                impact: 'Missed Buyer Closures',
              },
              {
                num: '04',
                icon: FileText,
                title: 'Slow Calculator Cost Sheets',
                desc: 'Manual calculation of unit prices, GST, and payment schedules causes errors, breaks negotiation momentum, and slows deal closing.',
                impact: 'Delayed Decision Making',
              },
              {
                num: '05',
                icon: Lock,
                title: 'Sales Staff Turnover & Data Theft',
                desc: 'Unrestricted customer directories expose your business to contact list poaching when sales reps move to competing firms.',
                impact: 'High-Net-Worth List Poaching',
              },
              {
                num: '06',
                icon: CheckCircle2,
                title: 'The Real Estate CRM Fix',
                desc: 'Replace all 5 bottlenecks with an automated lead inbox, visual drag-and-drop pipeline, instant PDF cost sheets, and a secure buyer vault.',
                impact: 'Complete Operational Control',
                isCta: true,
              },
            ].map((card, cIdx) => {
              const Icon = card.icon;
              return (
                <div
                  key={cIdx}
                  className={`p-5 rounded-2xl border-2 transition-all flex flex-col justify-between space-y-4 ${
                    card.isCta
                      ? 'bg-[#0A0A0A] text-white border-[#0A0A0A] shadow-md'
                      : 'bg-white text-[#0A0A0A] border-red-200/80 hover:border-red-300 shadow-xs'
                  }`}
                >
                  <div className="space-y-2.5">
                    <div className="flex items-center justify-between">
                      <div
                        className={`w-8 h-8 rounded-lg flex items-center justify-center ${
                          card.isCta ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-50 text-red-600'
                        }`}
                      >
                        <Icon className="w-4 h-4" />
                      </div>
                      <span className={`text-[10px] font-mono font-bold ${card.isCta ? 'text-emerald-400' : 'text-red-700'}`}>
                        {card.num}
                      </span>
                    </div>

                    <h4 className="font-serif text-lg font-bold leading-snug">
                      {card.title}
                    </h4>
                    <p className={`text-xs leading-relaxed ${card.isCta ? 'text-neutral-300' : 'text-[#55524A]'}`}>
                      {card.desc}
                    </p>
                  </div>

                  <div className="pt-2 border-t border-neutral-200/50">
                    {card.isCta ? (
                      <button
                        onClick={() => setViewMode('solution')}
                        className="w-full py-2 px-3 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-mono text-[10px] font-bold uppercase tracking-wider flex items-center justify-center gap-1.5 cursor-pointer transition-colors"
                      >
                        <span>See The CRM Solution</span>
                        <ArrowRight className="w-3 h-3" />
                      </button>
                    ) : (
                      <div className="flex items-center justify-between text-[10px] font-mono text-red-700 font-bold">
                        <span>Friction:</span>
                        <span>{card.impact}</span>
                      </div>
                    )}
                  </div>
                </div>
              );
            })}
          </div>
        )}

      </div>
    </section>
  );
};
