import React, { useState } from 'react';
import {
  LayoutDashboard,
  Users,
  Building2,
  Kanban,
  ShieldCheck,
  TrendingUp,
  ArrowUpRight,
  Filter,
  Search,
  CheckCircle2,
  Phone,
  MessageSquare,
  FileText,
  Calendar,
  Lock,
  ChevronRight,
  Plus,
  Eye,
  Download,
  Clock,
  Sparkles,
  MapPin,
  Tag,
} from 'lucide-react';

interface VertixDashboardTourProps {
  onStartDemo?: () => void;
}

export const VertixDashboardTour: React.FC<VertixDashboardTourProps> = ({ onStartDemo }) => {
  const [activeTab, setActiveTab] = useState<'dashboard' | 'leads' | 'inventory' | 'pipeline' | 'security'>('dashboard');

  const TABS = [
    { id: 'dashboard', label: 'Executive Dashboard', icon: LayoutDashboard, tag: '₹42.8 Cr Pipeline' },
    { id: 'leads', label: 'Lead Management', icon: Users, tag: '148 Active Enquiries' },
    { id: 'inventory', label: 'Property & Inventory Matrix', icon: Building2, tag: '84 Units Live' },
    { id: 'pipeline', label: 'Visual Deal Board', icon: Kanban, tag: 'Drag & Drop' },
    { id: 'security', label: 'Role Security & Vault', icon: ShieldCheck, tag: '100% Protected' },
  ] as const;

  return (
    <section id="tour" className="py-12 lg:py-18 bg-[#F4F3EE] border-b-2 border-[#D8D5CA] relative">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 lg:space-y-8">
        
        {/* Section Header */}
        <div className="flex flex-col md:flex-row md:items-end justify-between gap-4 pb-5 border-b border-[#D8D5CA]">
          <div className="space-y-1.5 max-w-2xl">
            <span className="text-[10px] font-bold tracking-[0.25em] text-[#0A0A0A] uppercase font-mono block">
              LIVE APPLICATION TOUR &amp; UI
            </span>
            <h2 className="font-serif text-2xl sm:text-3xl lg:text-4xl text-[#0A0A0A] font-bold leading-tight">
              A Glimpse Inside the <span className="italic font-normal text-emerald-800">Actual Software Interface</span>
            </h2>
            <p className="text-xs sm:text-sm text-[#3D3A34] font-normal leading-relaxed">
              Designed for ease of use by sales executives, sourcing managers, and company directors without technical complexity.
            </p>
          </div>

          <div className="flex items-center gap-2">
            <button
              onClick={onStartDemo}
              className="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#0A0A0A] hover:bg-[#262626] text-white text-xs font-bold uppercase tracking-wider transition-all cursor-pointer shadow-md hover:scale-105"
            >
              <span>Request Interactive Walkthrough</span>
              <ArrowUpRight className="w-3.5 h-3.5" />
            </button>
          </div>
        </div>

        {/* Module Switcher Tabs */}
        <div className="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
          {TABS.map((tab) => {
            const Icon = tab.icon;
            const isActive = activeTab === tab.id;
            return (
              <button
                key={tab.id}
                onClick={() => setActiveTab(tab.id)}
                className={`px-4 py-2.5 rounded-xl text-xs font-bold flex items-center gap-2.5 whitespace-nowrap transition-all duration-200 cursor-pointer border shadow-2xs ${
                  isActive
                    ? 'bg-[#0A0A0A] text-white border-[#0A0A0A] shadow-md ring-2 ring-emerald-500/40'
                    : 'bg-white text-[#0A0A0A] border-[#CDC9BC] hover:border-[#0A0A0A] hover:bg-[#FAF9F5]'
                }`}
              >
                <Icon className={`w-4 h-4 ${isActive ? 'text-emerald-400' : 'text-[#0A0A0A]'}`} />
                <span>{tab.label}</span>
                <span
                  className={`text-[9px] font-mono px-2 py-0.5 rounded-full font-bold ${
                    isActive
                      ? 'bg-white/20 text-emerald-300'
                      : 'bg-[#EFECE4] text-[#4A473F]'
                  }`}
                >
                  {tab.tag}
                </span>
              </button>
            );
          })}
        </div>

        {/* Master Realistic Software UI Display Window */}
        <div className="bg-[#0D0E11] rounded-2xl border-2 border-[#CDC9BC] shadow-2xl overflow-hidden text-neutral-200 font-sans">
          
          {/* Mac / Browser Header Bar */}
          <div className="bg-[#16181D] px-4 py-3 border-b border-white/10 flex items-center justify-between">
            <div className="flex items-center gap-3">
              <div className="flex gap-1.5">
                <span className="w-3 h-3 rounded-full bg-[#FF5F56] inline-block"></span>
                <span className="w-3 h-3 rounded-full bg-[#FFBD2E] inline-block"></span>
                <span className="w-3 h-3 rounded-full bg-[#27C93F] inline-block"></span>
              </div>
              <span className="text-[11px] font-mono text-neutral-400 font-semibold pl-2 border-l border-white/10">
                https://app.realestatecrm.in/{activeTab}
              </span>
            </div>

            <div className="flex items-center gap-3 text-xs">
              <span className="hidden sm:inline-flex items-center gap-1.5 font-mono text-[10px] text-emerald-400 bg-emerald-950/60 px-2.5 py-1 rounded border border-emerald-500/30">
                <span className="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                Live Database: 4 Projects Active
              </span>
              <div className="w-6 h-6 rounded-full bg-emerald-700 text-white flex items-center justify-center font-bold text-[10px]">
                AD
              </div>
            </div>
          </div>

          {/* Dynamic Module Content View */}
          <div className="p-4 sm:p-6 lg:p-8 min-h-[460px]">
            
            {/* 1. EXECUTIVE DASHBOARD VIEW */}
            {activeTab === 'dashboard' && (
              <div className="space-y-6 animate-fade-in-scale">
                {/* 4 Key Metric Tiles */}
                <div className="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                  <div className="bg-[#16181D] p-4 rounded-xl border border-white/10 space-y-1">
                    <span className="text-[10px] font-mono uppercase text-neutral-400">Total Active Pipeline</span>
                    <div className="font-serif text-2xl sm:text-3xl font-bold text-white">₹42.85 Cr</div>
                    <div className="text-[10px] font-mono text-emerald-400 flex items-center gap-1">
                      <TrendingUp className="w-3 h-3" /> +18.4% this month
                    </div>
                  </div>

                  <div className="bg-[#16181D] p-4 rounded-xl border border-white/10 space-y-1">
                    <span className="text-[10px] font-mono uppercase text-neutral-400">Site Visits Scheduled</span>
                    <div className="font-serif text-2xl sm:text-3xl font-bold text-white">28 Visits</div>
                    <div className="text-[10px] font-mono text-amber-400">6 visits planned today</div>
                  </div>

                  <div className="bg-[#16181D] p-4 rounded-xl border border-white/10 space-y-1">
                    <span className="text-[10px] font-mono uppercase text-neutral-400">Token Bookings Won</span>
                    <div className="font-serif text-2xl sm:text-3xl font-bold text-emerald-400">14 Units</div>
                    <div className="text-[10px] font-mono text-neutral-300">₹1.4 Cr token receipts logged</div>
                  </div>

                  <div className="bg-[#16181D] p-4 rounded-xl border border-white/10 space-y-1">
                    <span className="text-[10px] font-mono uppercase text-neutral-400">Avg. Cost Sheet Speed</span>
                    <div className="font-serif text-2xl sm:text-3xl font-bold text-white">45 Sec</div>
                    <div className="text-[10px] font-mono text-emerald-400">1-click WhatsApp PDF export</div>
                  </div>
                </div>

                {/* Split Charts & Activity Feed */}
                <div className="grid grid-cols-1 lg:grid-cols-12 gap-4">
                  {/* Pipeline Stage Distribution */}
                  <div className="lg:col-span-8 bg-[#16181D] p-4 sm:p-5 rounded-xl border border-white/10 space-y-4">
                    <div className="flex items-center justify-between">
                      <span className="font-serif text-base font-bold text-white">Active Deal Pipeline by Project</span>
                      <span className="text-[10px] font-mono text-neutral-400">Q3 FY 2026</span>
                    </div>

                    <div className="space-y-3">
                      <div>
                        <div className="flex justify-between text-xs mb-1">
                          <span className="text-white font-medium">Godrej Palm Retreat (Villas &amp; Plots)</span>
                          <span className="font-mono text-emerald-400 font-bold">₹18.4 Cr · 16 Deals</span>
                        </div>
                        <div className="w-full h-2.5 bg-white/10 rounded-full overflow-hidden">
                          <div className="w-[72%] h-full bg-emerald-500 rounded-full"></div>
                        </div>
                      </div>

                      <div>
                        <div className="flex justify-between text-xs mb-1">
                          <span className="text-white font-medium">DLF The Arbour (4 BHK Luxury)</span>
                          <span className="font-mono text-blue-400 font-bold">₹14.2 Cr · 6 Deals</span>
                        </div>
                        <div className="w-full h-2.5 bg-white/10 rounded-full overflow-hidden">
                          <div className="w-[55%] h-full bg-blue-500 rounded-full"></div>
                        </div>
                      </div>

                      <div>
                        <div className="flex justify-between text-xs mb-1">
                          <span className="text-white font-medium">Prestige City Villas (Turnkey)</span>
                          <span className="font-mono text-amber-400 font-bold">₹10.25 Cr · 4 Deals</span>
                        </div>
                        <div className="w-full h-2.5 bg-white/10 rounded-full overflow-hidden">
                          <div className="w-[42%] h-full bg-amber-500 rounded-full"></div>
                        </div>
                      </div>
                    </div>
                  </div>

                  {/* Today's High-Priority Tasks */}
                  <div className="lg:col-span-4 bg-[#16181D] p-4 sm:p-5 rounded-xl border border-white/10 space-y-3">
                    <span className="font-serif text-base font-bold text-white">Today's Site Walkthroughs</span>
                    <div className="space-y-2 text-xs">
                      <div className="p-2 rounded bg-white/5 border border-white/10 space-y-1">
                        <div className="flex justify-between font-mono text-[10px] text-amber-400">
                          <span>11:30 AM</span>
                          <span>Unit #104</span>
                        </div>
                        <div className="font-bold text-white">Dr. Arvind Mehra (₹4.85 Cr)</div>
                        <div className="text-[10px] text-neutral-400">Exec: Rahul Sharma</div>
                      </div>

                      <div className="p-2 rounded bg-white/5 border border-white/10 space-y-1">
                        <div className="flex justify-between font-mono text-[10px] text-emerald-400">
                          <span>02:30 PM</span>
                          <span>Plot #G-12</span>
                        </div>
                        <div className="font-bold text-white">Vikram Singhania (₹1.85 Cr)</div>
                        <div className="text-[10px] text-neutral-400">Exec: Amit Verma</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            )}

            {/* 2. LEAD MANAGEMENT VIEW */}
            {activeTab === 'leads' && (
              <div className="space-y-4 animate-fade-in-scale">
                {/* Search & Action Bar */}
                <div className="flex flex-wrap items-center justify-between gap-3 bg-[#16181D] p-3 rounded-xl border border-white/10">
                  <div className="flex items-center gap-2 text-xs">
                    <Filter className="w-3.5 h-3.5 text-neutral-400" />
                    <span className="text-white font-bold">Filter By:</span>
                    <span className="px-2 py-0.5 rounded bg-white/10 text-[11px] font-mono">Channel: All</span>
                    <span className="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400 text-[11px] font-mono">Budget: ₹1 Cr - ₹5 Cr+</span>
                  </div>
                  <span className="text-[10px] font-mono text-neutral-400">Auto-Deduplication: Active</span>
                </div>

                {/* Realistic Lead Table */}
                <div className="overflow-x-auto">
                  <table className="w-full text-left text-xs">
                    <thead>
                      <tr className="border-b border-white/10 text-neutral-400 font-mono text-[10px] uppercase">
                        <th className="py-2.5 px-3">Buyer Name</th>
                        <th className="py-2.5 px-3">Channel Source</th>
                        <th className="py-2.5 px-3">Project &amp; Requirement</th>
                        <th className="py-2.5 px-3">Budget</th>
                        <th className="py-2.5 px-3">Assigned Rep</th>
                        <th className="py-2.5 px-3 text-right">Quick Action</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-white/5">
                      <tr className="hover:bg-white/5 transition-colors">
                        <td className="py-3 px-3 font-bold text-white">
                          Vikramaditya Singhania
                          <span className="block text-[10px] font-normal text-neutral-400">Phone: +91 98XXX XXX89</span>
                        </td>
                        <td className="py-3 px-3">
                          <span className="px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400 font-mono text-[10px] font-bold">
                            WhatsApp Inbound
                          </span>
                        </td>
                        <td className="py-3 px-3 text-neutral-300">
                          Godrej Palm Retreat · 3 BHK Luxury
                        </td>
                        <td className="py-3 px-3 font-mono font-bold text-emerald-400">₹1.85 Cr</td>
                        <td className="py-3 px-3 text-neutral-300">Amit Verma</td>
                        <td className="py-3 px-3 text-right">
                          <button className="px-2.5 py-1 rounded bg-white/10 hover:bg-white hover:text-black font-mono text-[10px] font-bold transition-all">
                            Send Brochure PDF
                          </button>
                        </td>
                      </tr>

                      <tr className="hover:bg-white/5 transition-colors">
                        <td className="py-3 px-3 font-bold text-white">
                          Pooja &amp; Rohan Sharma
                          <span className="block text-[10px] font-normal text-neutral-400">Phone: +91 97XXX XXX12</span>
                        </td>
                        <td className="py-3 px-3">
                          <span className="px-2 py-0.5 rounded bg-blue-500/20 text-blue-400 font-mono text-[10px] font-bold">
                            Meta Lead Ad
                          </span>
                        </td>
                        <td className="py-3 px-3 text-neutral-300">
                          DLF The Arbour · 4 BHK Penthouse
                        </td>
                        <td className="py-3 px-3 font-mono font-bold text-blue-400">₹2.40 Cr</td>
                        <td className="py-3 px-3 text-neutral-300">Pooja Iyer</td>
                        <td className="py-3 px-3 text-right">
                          <button className="px-2.5 py-1 rounded bg-white/10 hover:bg-white hover:text-black font-mono text-[10px] font-bold transition-all">
                            Schedule Site Visit
                          </button>
                        </td>
                      </tr>

                      <tr className="hover:bg-white/5 transition-colors">
                        <td className="py-3 px-3 font-bold text-white">
                          Dr. Arvind Mehra
                          <span className="block text-[10px] font-normal text-neutral-400">Phone: +91 99XXX XXX55</span>
                        </td>
                        <td className="py-3 px-3">
                          <span className="px-2 py-0.5 rounded bg-purple-500/20 text-purple-400 font-mono text-[10px] font-bold">
                            Channel Partner (CP)
                          </span>
                        </td>
                        <td className="py-3 px-3 text-neutral-300">
                          Prestige City · Villa #18 (3,400 Sq.Ft.)
                        </td>
                        <td className="py-3 px-3 font-mono font-bold text-purple-400">₹4.85 Cr</td>
                        <td className="py-3 px-3 text-neutral-300">Suresh Reddy</td>
                        <td className="py-3 px-3 text-right">
                          <button className="px-2.5 py-1 rounded bg-white/10 hover:bg-white hover:text-black font-mono text-[10px] font-bold transition-all">
                            Export Cost Sheet
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            )}

            {/* 3. INVENTORY MATRIX VIEW */}
            {activeTab === 'inventory' && (
              <div className="space-y-4 animate-fade-in-scale">
                <div className="flex items-center justify-between bg-[#16181D] p-3 rounded-xl border border-white/10 text-xs">
                  <div className="flex items-center gap-2">
                    <Building2 className="w-4 h-4 text-emerald-400" />
                    <span className="text-white font-bold">Godrej Palm Retreat — Tower A &amp; Villa Phase 1</span>
                  </div>
                  <div className="flex items-center gap-3 font-mono text-[10px]">
                    <span className="flex items-center gap-1 text-emerald-400">
                      <span className="w-2 h-2 rounded bg-emerald-500"></span> Available (42)
                    </span>
                    <span className="flex items-center gap-1 text-amber-400">
                      <span className="w-2 h-2 rounded bg-amber-500"></span> Blocked / Token (18)
                    </span>
                    <span className="flex items-center gap-1 text-red-400">
                      <span className="w-2 h-2 rounded bg-red-500"></span> Sold / Registered (24)
                    </span>
                  </div>
                </div>

                {/* Interactive Unit Matrix Grid */}
                <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2.5">
                  {[
                    { unit: 'Villa #101', type: '3 BHK Villa', size: '2,200 Sq.Ft.', price: '₹1.85 Cr', status: 'available' },
                    { unit: 'Villa #102', type: '3 BHK Villa', size: '2,200 Sq.Ft.', price: '₹1.85 Cr', status: 'blocked' },
                    { unit: 'Villa #103', type: '4 BHK Grand', size: '2,900 Sq.Ft.', price: '₹2.45 Cr', status: 'sold' },
                    { unit: 'Villa #104', type: '4 BHK Grand', size: '2,900 Sq.Ft.', price: '₹2.45 Cr', status: 'available' },
                    { unit: 'Plot #G-11', type: 'SCO Plot', size: '180 Sq.Yd.', price: '₹2.10 Cr', status: 'available' },
                    { unit: 'Plot #G-12', type: 'SCO Plot', size: '180 Sq.Yd.', price: '₹2.10 Cr', status: 'blocked' },
                  ].map((item, idx) => (
                    <div
                      key={idx}
                      className={`p-3 rounded-xl border text-xs space-y-1.5 transition-all ${
                        item.status === 'available'
                          ? 'bg-emerald-950/30 border-emerald-500/40 text-white'
                          : item.status === 'blocked'
                          ? 'bg-amber-950/30 border-amber-500/40 text-neutral-200'
                          : 'bg-red-950/20 border-red-500/30 text-neutral-400 opacity-60'
                      }`}
                    >
                      <div className="flex justify-between items-center">
                        <span className="font-bold text-white text-xs">{item.unit}</span>
                        <span
                          className={`text-[8px] font-mono uppercase px-1.5 py-0.5 rounded font-bold ${
                            item.status === 'available'
                              ? 'bg-emerald-500/20 text-emerald-300'
                              : item.status === 'blocked'
                              ? 'bg-amber-500/20 text-amber-300'
                              : 'bg-red-500/20 text-red-300'
                          }`}
                        >
                          {item.status}
                        </span>
                      </div>
                      <div className="text-[10px] text-neutral-300">{item.type} · {item.size}</div>
                      <div className="font-mono font-bold text-white text-xs">{item.price}</div>
                    </div>
                  ))}
                </div>
              </div>
            )}

            {/* 4. PIPELINE KANBAN VIEW */}
            {activeTab === 'pipeline' && (
              <div className="space-y-4 animate-fade-in-scale">
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                  {/* Column 1 */}
                  <div className="bg-[#16181D] p-3 rounded-xl border border-white/10 space-y-2">
                    <div className="flex justify-between items-center text-xs pb-2 border-b border-white/10">
                      <span className="font-bold text-white">1. Qualified Leads</span>
                      <span className="font-mono text-[10px] text-neutral-400 font-bold">18</span>
                    </div>
                    <div className="bg-[#20232A] p-2.5 rounded-lg space-y-1 text-xs">
                      <div className="font-bold text-white">Rajeev Kapoor</div>
                      <div className="text-[10px] text-neutral-400">Sector 150 · 3 BHK</div>
                      <div className="font-mono text-emerald-400 font-bold text-[10px]">₹1.65 Cr</div>
                    </div>
                  </div>

                  {/* Column 2 */}
                  <div className="bg-[#16181D] p-3 rounded-xl border border-white/10 space-y-2">
                    <div className="flex justify-between items-center text-xs pb-2 border-b border-white/10">
                      <span className="font-bold text-amber-400">2. Site Visit Done</span>
                      <span className="font-mono text-[10px] text-amber-400 font-bold">9</span>
                    </div>
                    <div className="bg-[#20232A] p-2.5 rounded-lg space-y-1 text-xs border-l-2 border-amber-400">
                      <div className="font-bold text-white">Dr. Arvind Mehra</div>
                      <div className="text-[10px] text-neutral-400">Prestige City · Villa #18</div>
                      <div className="font-mono text-amber-400 font-bold text-[10px]">₹4.85 Cr</div>
                    </div>
                  </div>

                  {/* Column 3 */}
                  <div className="bg-[#16181D] p-3 rounded-xl border border-white/10 space-y-2">
                    <div className="flex justify-between items-center text-xs pb-2 border-b border-white/10">
                      <span className="font-bold text-blue-400">3. Cost Sheet Sent</span>
                      <span className="font-mono text-[10px] text-blue-400 font-bold">6</span>
                    </div>
                    <div className="bg-[#20232A] p-2.5 rounded-lg space-y-1 text-xs border-l-2 border-blue-400">
                      <div className="font-bold text-white">Pooja Sharma</div>
                      <div className="text-[10px] text-neutral-400">DLF The Arbour · 4 BHK</div>
                      <div className="font-mono text-blue-400 font-bold text-[10px]">₹2.40 Cr</div>
                    </div>
                  </div>

                  {/* Column 4 */}
                  <div className="bg-emerald-950/30 p-3 rounded-xl border border-emerald-500/30 space-y-2">
                    <div className="flex justify-between items-center text-xs pb-2 border-b border-emerald-500/20">
                      <span className="font-bold text-emerald-300">4. Booking Won 🎉</span>
                      <span className="font-mono text-[10px] text-emerald-300 font-bold">14</span>
                    </div>
                    <div className="bg-[#16181D] p-2.5 rounded-lg space-y-1 text-xs border border-emerald-500/40">
                      <div className="font-bold text-white">Siddharth Varma</div>
                      <div className="text-[10px] text-emerald-300 font-mono">Token: ₹5,00,000 Recd</div>
                      <div className="font-mono text-emerald-400 font-bold text-[10px]">₹6.20 Cr</div>
                    </div>
                  </div>
                </div>
              </div>
            )}

            {/* 5. ROLE SECURITY & VAULT VIEW */}
            {activeTab === 'security' && (
              <div className="space-y-4 animate-fade-in-scale">
                <div className="grid grid-cols-1 md:grid-cols-3 gap-3">
                  <div className="bg-[#16181D] p-4 rounded-xl border border-white/10 space-y-2 text-xs">
                    <div className="flex items-center gap-2 text-emerald-400 font-bold">
                      <Lock className="w-4 h-4" />
                      <span>Phone Number Masking</span>
                    </div>
                    <p className="text-neutral-300 leading-relaxed text-[11px]">
                      Sales reps call through cloud click-to-call. Raw buyer numbers (+91 98XXX XXX89) are shielded from direct copy/export.
                    </p>
                    <span className="text-[9px] font-mono text-emerald-400 font-bold bg-emerald-500/20 px-2 py-0.5 rounded inline-block">
                      Active for 18 Sales Executives
                    </span>
                  </div>

                  <div className="bg-[#16181D] p-4 rounded-xl border border-white/10 space-y-2 text-xs">
                    <div className="flex items-center gap-2 text-blue-400 font-bold">
                      <ShieldCheck className="w-4 h-4" />
                      <span>1-Click Safe Offboarding</span>
                    </div>
                    <p className="text-neutral-300 leading-relaxed text-[11px]">
                      When a consultant resigns, revoke their login in 1 second. All leads, notes, and past site visit records stay safely inside the company.
                    </p>
                    <span className="text-[9px] font-mono text-blue-400 font-bold bg-blue-500/20 px-2 py-0.5 rounded inline-block">
                      Zero Client Data Theft
                    </span>
                  </div>

                  <div className="bg-[#16181D] p-4 rounded-xl border border-white/10 space-y-2 text-xs">
                    <div className="flex items-center gap-2 text-purple-400 font-bold">
                      <Users className="w-4 h-4" />
                      <span>Role-Based Permissions</span>
                    </div>
                    <p className="text-neutral-300 leading-relaxed text-[11px]">
                      Granular controls: Executives view assigned leads; Sourcing Managers oversee CP deals; Directors view company revenue.
                    </p>
                    <span className="text-[9px] font-mono text-purple-400 font-bold bg-purple-500/20 px-2 py-0.5 rounded inline-block">
                      Enterprise Tier Security
                    </span>
                  </div>
                </div>
              </div>
            )}

          </div>

          {/* Bottom Control Bar */}
          <div className="bg-[#16181D] px-4 sm:px-6 py-3 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs">
            <span className="text-neutral-400 font-mono text-[11px]">
              Module: <strong className="text-white">{TABS.find(t => t.id === activeTab)?.label}</strong>
            </span>
            <div className="flex items-center gap-3">
              <button
                onClick={onStartDemo}
                className="px-4 py-1.5 rounded-full bg-white text-black font-bold text-[11px] uppercase tracking-wider hover:bg-neutral-200 transition-colors"
              >
                Schedule 15-Min Live Demo
              </button>
            </div>
          </div>

        </div>

      </div>
    </section>
  );
};
