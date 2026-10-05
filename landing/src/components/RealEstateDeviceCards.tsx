import React from 'react';
import { RealEstateMotionBackground } from './RealEstateMotionBackground';
import {
  ArrowUpRight,
  CheckCircle2,
  Sparkles,
  Smartphone,
  Laptop,
  MessageSquare,
  Building2,
  Layers,
  Kanban,
  Users,
  Calendar,
  DollarSign,
  TrendingUp,
  MapPin,
  Clock,
  FileCheck2,
  ShieldCheck,
  Check,
  PhoneCall,
  Search,
} from 'lucide-react';

interface RealEstateDeviceCardsProps {
  onStartDemo: () => void;
}

export const RealEstateDeviceCards: React.FC<RealEstateDeviceCardsProps> = ({ onStartDemo }) => {
  return (
    <section id="platform-modules" className="isolate py-16 sm:py-24 bg-[#F8FAFC]/85 backdrop-blur-xs border-b border-[#E2E8F0]/80 relative overflow-hidden">
      <RealEstateMotionBackground variant="properties" />
      {/* Background Ambient Glows */}
      <div className="absolute top-1/4 -left-20 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
      <div className="absolute bottom-10 -right-20 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 sm:space-y-14 relative z-10">
        
        {/* Section Header */}
        <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-4 pb-2">
          <div className="space-y-2.5 max-w-2xl">
            <div className="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-blue-50 border border-blue-200/80 shadow-2xs">
              <span className="w-1.5 h-1.5 rounded-full bg-[#1864E8]" />
              <span className="text-[11px] font-extrabold tracking-[0.16em] text-[#1864E8] uppercase">
                Enterprise Real Estate Software
              </span>
            </div>
            
            <h2 className="text-3xl sm:text-4xl lg:text-[42px] font-extrabold text-[#0F172A] tracking-tight leading-tight">
              One Unified System for Web &amp; Mobile
            </h2>
            <p className="text-sm sm:text-base text-[#475569] leading-relaxed">
              Experience the 3 core pillars of Real Estate CRM: Capture the Lead, Manage the Visit, and Close the Deal.
            </p>
          </div>

          <button
            onClick={onStartDemo}
            className="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-blue-50 text-xs font-bold text-[#1864E8] border border-blue-200 transition-all cursor-pointer self-start sm:self-auto group shadow-xs hover:shadow-md"
          >
            <span>Explore All Capabilities</span>
            <ArrowUpRight className="w-4 h-4 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" />
          </button>
        </div>

        {/* 3 Specialized Device Showcase Cards with Dynamic Animations */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 items-stretch">
          
          {/* ========================================================================= */}
          {/* CARD 1 — LEAD MANAGEMENT: "Capture and manage every property enquiry" */}
          {/* ========================================================================= */}
          <div
            onClick={onStartDemo}
            className="bg-white rounded-3xl border border-[#E2E8F0] p-4 sm:p-5 shadow-sm hover:shadow-2xl hover:border-blue-300 transition-all duration-500 flex flex-col justify-between group cursor-pointer hover:-translate-y-2"
          >
            <div className="space-y-4">
              
              {/* Top Device Mockup Canvas */}
              <div className="relative w-full aspect-[16/11] rounded-2xl bg-gradient-to-br from-[#EBF2FE] via-[#F1F5F9] to-[#E2E8F0] p-3 sm:p-4 flex items-center justify-center overflow-hidden border border-[#CBD5E1]/60 group-hover:border-[#1864E8]/40 transition-colors">
                {/* Blueprint Background Grid */}
                <div className="absolute inset-0 opacity-15 pointer-events-none bg-[linear-gradient(to_right,#1864E8_1px,transparent_1px),linear-gradient(to_bottom,#1864E8_1px,transparent_1px)] bg-[size:16px_16px]"></div>

                {/* Animated Ambient Backlight Halo */}
                <div className="absolute w-44 h-44 bg-blue-500/20 rounded-full blur-2xl pointer-events-none group-hover:scale-125 transition-transform duration-700"></div>

                <div className="relative w-full h-full flex items-center justify-center">
                  
                  {/* Laptop Mockup: Lead Management Dashboard with Floating Animation */}
                  <div className="relative w-[85%] sm:w-[88%] aspect-[16/10] bg-[#1E293B] rounded-t-lg rounded-b-xs p-1 shadow-2xl border border-slate-700/80 -ml-6 animate-float-laptop group-hover:scale-[1.03] transition-transform duration-500">
                    <div className="w-full h-full rounded-sm overflow-hidden bg-white flex flex-col justify-between text-[6.5px] font-sans p-1.5 space-y-1 relative">
                      
                      {/* Scanning Light Ray */}
                      <div className="absolute inset-x-0 h-8 bg-gradient-to-b from-transparent via-blue-400/20 to-transparent pointer-events-none animate-scanline z-20"></div>

                      {/* Laptop Mini Header */}
                      <div className="flex justify-between items-center pb-0.5 border-b border-slate-200">
                        <div className="flex items-center gap-1">
                          <div className="w-2 h-2 rounded-xs bg-[#1864E8] text-white flex items-center justify-center font-bold text-[5px]">RE</div>
                          <span className="font-bold text-slate-800 text-[6.5px]">Leads &amp; Enquiries</span>
                        </div>
                        <div className="flex items-center gap-1 bg-blue-50 text-[#1864E8] font-bold px-1 py-0.2 rounded font-mono text-[5.5px]">
                          <span className="w-1 h-1 rounded-full bg-[#1864E8] animate-pulse"></span>
                          <span>148 Active</span>
                        </div>
                      </div>

                      {/* 3 Mini KPI Tiles */}
                      <div className="grid grid-cols-3 gap-1 text-[5.5px]">
                        <div className="bg-slate-50 p-0.5 rounded border border-slate-200">
                          <span className="text-slate-400 block text-[4.5px]">NEW LEADS</span>
                          <span className="font-bold text-slate-800 text-[7px]">28</span>
                        </div>
                        <div className="bg-emerald-50 p-0.5 rounded border border-emerald-200">
                          <span className="text-emerald-600 block text-[4.5px]">HOT LEADS</span>
                          <span className="font-bold text-emerald-700 text-[7px]">42</span>
                        </div>
                        <div className="bg-blue-50 p-0.5 rounded border border-blue-200">
                          <span className="text-blue-600 block text-[4.5px]">PORTAL SYNC</span>
                          <span className="font-bold text-blue-700 text-[7px]">100%</span>
                        </div>
                      </div>

                      {/* Recent Lead Items */}
                      <div className="space-y-0.5 flex-1">
                        <div className="flex justify-between items-center bg-slate-50 p-0.5 rounded border border-slate-200 text-[5.5px] hover:bg-blue-50/50 transition-colors">
                          <div>
                            <span className="font-bold text-slate-800">Rajesh Sharma</span>
                            <span className="text-slate-400 block text-[4.5px]">Godrej Palm · 3 BHK Luxury</span>
                          </div>
                          <span className="text-emerald-600 font-bold bg-emerald-50 px-1 rounded text-[5px]">Website</span>
                        </div>
                        <div className="flex justify-between items-center bg-slate-50 p-0.5 rounded border border-slate-200 text-[5.5px]">
                          <div>
                            <span className="font-bold text-slate-800">Ananya Deshmukh</span>
                            <span className="text-slate-400 block text-[4.5px]">DLF Arbour · 4 BHK</span>
                          </div>
                          <span className="text-[#1864E8] font-bold bg-blue-50 px-1 rounded text-[5px]">WhatsApp</span>
                        </div>
                      </div>

                    </div>

                    {/* Laptop Base */}
                    <div className="absolute -bottom-1.5 left-1/2 -translate-x-1/2 w-[115%] h-1.5 bg-slate-300 rounded-b-sm shadow-md border-t border-slate-400 flex items-center justify-center">
                      <div className="w-8 h-0.5 bg-slate-500 rounded-full"></div>
                    </div>
                  </div>

                  {/* Laser Sync Pulse Beam Connecting Laptop to Mobile */}
                  <div className="absolute right-[22%] top-1/2 -translate-y-1/2 w-8 h-[2px] bg-gradient-to-r from-blue-500 via-indigo-400 to-emerald-400 z-30 animate-sync-laser opacity-80 pointer-events-none"></div>

                  {/* Mobile Phone Mockup: Card 1 Lead Activity Screen with Floating Animation */}
                  <div className="absolute -right-1 sm:right-1 bottom-0 w-[30%] sm:w-[32%] aspect-[9/18] bg-[#0F172A] rounded-2xl p-0.5 shadow-2xl border-2 border-slate-800 animate-float-mobile group-hover:scale-110 group-hover:-translate-y-2 transition-all duration-500 z-20">
                    <div className="w-full h-full rounded-[14px] overflow-hidden bg-white flex flex-col justify-between relative">
                      {/* Dynamic Island */}
                      <div className="absolute top-1 left-1/2 -translate-x-1/2 w-4 h-1 bg-black rounded-full z-20"></div>

                      {/* Scanning Light Ray on Mobile */}
                      <div className="absolute inset-x-0 h-6 bg-gradient-to-b from-transparent via-blue-400/20 to-transparent pointer-events-none animate-scanline z-20"></div>

                      {/* Mobile Header */}
                      <div className="bg-[#1864E8] text-white p-1.5 pt-2.5 text-[6.5px]">
                        <div className="font-bold flex justify-between items-center">
                          <span>RE CRM</span>
                          <span className="font-mono text-emerald-300 flex items-center gap-0.5">
                            <span className="w-1 h-1 rounded-full bg-emerald-300 animate-ping"></span>
                            <span>Live</span>
                          </span>
                        </div>
                        <div className="text-[5.5px] text-blue-100 font-medium">New Enquiries</div>
                      </div>

                      {/* Mobile Notification Cards */}
                      <div className="p-1 space-y-1 bg-slate-50 flex-1 text-[5.5px]">
                        <div className="bg-white p-1 rounded shadow-2xs space-y-0.5 border-l-2 border-[#1864E8] border-y border-r border-slate-200 ring-1 ring-blue-400/30">
                          <div className="flex justify-between text-[5px]">
                            <span className="font-bold text-[#1864E8] uppercase flex items-center gap-0.5">
                              <span className="w-1 h-1 rounded-full bg-[#1864E8] animate-pulse"></span>
                              New Lead
                            </span>
                            <span className="text-slate-400">Just now</span>
                          </div>
                          <div className="font-bold text-slate-800 text-[6px]">Rajesh Sharma</div>
                          <div className="text-slate-500">Interested in 3 BHK</div>
                          <div className="flex justify-between text-[4.5px] text-slate-400 pt-0.5">
                            <span>Source: Website</span>
                            <span className="text-amber-600 font-bold">Follow-up Today</span>
                          </div>
                        </div>

                        <div className="bg-white p-1 rounded shadow-2xs space-y-0.5 border-l-2 border-emerald-500 border-y border-r border-slate-200">
                          <div className="flex justify-between text-[5px]">
                            <span className="font-bold text-emerald-600 uppercase">WhatsApp Lead</span>
                          </div>
                          <div className="font-bold text-slate-800 text-[6px]">Ananya Deshmukh</div>
                          <div className="text-slate-500">DLF Arbour • 4 BHK</div>
                        </div>
                      </div>

                      {/* Mobile Bottom Home Bar */}
                      <div className="h-1 bg-white flex items-center justify-center">
                        <div className="w-5 h-0.5 bg-slate-400 rounded-full"></div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>

              {/* Card Title & Text */}
              <div className="space-y-1.5 pt-1">
                <div className="flex items-start justify-between gap-2">
                  <h3 className="text-lg sm:text-xl font-bold text-[#0F172A] group-hover:text-[#1864E8] transition-colors leading-snug">
                    Lead Management &amp; Capture
                  </h3>
                  <ArrowUpRight className="w-5 h-5 text-[#1864E8] shrink-0 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" />
                </div>
                <p className="text-xs sm:text-sm text-[#64748B] leading-relaxed">
                  Capture and manage every property enquiry from 99acres, Magicbricks, Meta Ads &amp; WhatsApp with auto-routing...
                </p>
              </div>

            </div>
          </div>

          {/* ========================================================================= */}
          {/* CARD 2 — SITE VISITS & FOLLOW-UPS: "Never miss a follow-up or site visit" */}
          {/* ========================================================================= */}
          <div
            onClick={onStartDemo}
            className="bg-white rounded-3xl border border-[#E2E8F0] p-4 sm:p-5 shadow-sm hover:shadow-2xl hover:border-amber-300 transition-all duration-500 flex flex-col justify-between group cursor-pointer hover:-translate-y-2"
          >
            <div className="space-y-4">
              
              {/* Top Device Mockup Canvas */}
              <div className="relative w-full aspect-[16/11] rounded-2xl bg-gradient-to-br from-[#FEF3C7]/40 via-[#F1F5F9] to-[#E2E8F0] p-3 sm:p-4 flex items-center justify-center overflow-hidden border border-[#CBD5E1]/60 group-hover:border-amber-500/40 transition-colors">
                {/* Blueprint Grid */}
                <div className="absolute inset-0 opacity-15 pointer-events-none bg-[linear-gradient(to_right,#D97706_1px,transparent_1px),linear-gradient(to_bottom,#D97706_1px,transparent_1px)] bg-[size:16px_16px]"></div>

                {/* Animated Ambient Backlight Halo */}
                <div className="absolute w-44 h-44 bg-amber-500/20 rounded-full blur-2xl pointer-events-none group-hover:scale-125 transition-transform duration-700"></div>

                <div className="relative w-full h-full flex items-center justify-center">
                  
                  {/* Laptop Mockup: Site Visits Screen with Floating Animation */}
                  <div className="relative w-[85%] sm:w-[88%] aspect-[16/10] bg-[#1E293B] rounded-t-lg rounded-b-xs p-1 shadow-2xl border border-slate-700/80 -ml-6 animate-float-laptop group-hover:scale-[1.03] transition-transform duration-500">
                    <div className="w-full h-full rounded-sm overflow-hidden bg-white flex flex-col justify-between text-[6.5px] font-sans p-1.5 space-y-1 relative">
                      
                      {/* Scanning Light Ray */}
                      <div className="absolute inset-x-0 h-8 bg-gradient-to-b from-transparent via-amber-400/20 to-transparent pointer-events-none animate-scanline z-20"></div>

                      {/* Laptop Mini Header */}
                      <div className="flex justify-between items-center pb-0.5 border-b border-slate-200">
                        <div className="flex items-center gap-1">
                          <div className="w-2 h-2 rounded-xs bg-amber-600 text-white flex items-center justify-center font-bold text-[5px]">SV</div>
                          <span className="font-bold text-slate-800 text-[6.5px]">Activities &amp; Site Visits</span>
                        </div>
                        <div className="flex items-center gap-1 bg-amber-50 text-amber-700 font-bold px-1 py-0.2 rounded font-mono text-[5.5px]">
                          <span className="w-1 h-1 rounded-full bg-amber-600 animate-pulse"></span>
                          <span>18 Today</span>
                        </div>
                      </div>

                      {/* 3 Mini KPI Tiles */}
                      <div className="grid grid-cols-3 gap-1 text-[5.5px]">
                        <div className="bg-amber-50 p-0.5 rounded border border-amber-200">
                          <span className="text-amber-700 block text-[4.5px]">TODAY'S VISITS</span>
                          <span className="font-bold text-amber-800 text-[7px]">18</span>
                        </div>
                        <div className="bg-blue-50 p-0.5 rounded border border-blue-200">
                          <span className="text-blue-600 block text-[4.5px]">FOLLOW-UPS</span>
                          <span className="font-bold text-blue-700 text-[7px]">24</span>
                        </div>
                        <div className="bg-emerald-50 p-0.5 rounded border border-emerald-200">
                          <span className="text-emerald-600 block text-[4.5px]">TURNOUT RATE</span>
                          <span className="font-bold text-emerald-700 text-[7px]">85%</span>
                        </div>
                      </div>

                      {/* Scheduled Visits list */}
                      <div className="space-y-0.5 flex-1">
                        <div className="flex justify-between items-center bg-amber-50/60 p-0.5 rounded border border-amber-200 text-[5.5px] hover:bg-amber-100/50 transition-colors">
                          <div>
                            <span className="font-bold text-slate-800">11:30 AM · Priya Mehta</span>
                            <span className="text-slate-500 block text-[4.5px]">Sector 150 · 3 BHK Walkthrough</span>
                          </div>
                          <span className="text-amber-700 font-bold bg-amber-100 px-1 rounded text-[5px]">Map Pin Sent</span>
                        </div>
                        <div className="flex justify-between items-center bg-slate-50 p-0.5 rounded border border-slate-200 text-[5.5px]">
                          <div>
                            <span className="font-bold text-slate-800">02:30 PM · Sunil Narang</span>
                            <span className="text-slate-500 block text-[4.5px]">Prestige City · Villa #18</span>
                          </div>
                          <span className="text-emerald-700 font-bold bg-emerald-50 px-1 rounded text-[5px]">Confirmed</span>
                        </div>
                      </div>

                    </div>

                    {/* Laptop Base */}
                    <div className="absolute -bottom-1.5 left-1/2 -translate-x-1/2 w-[115%] h-1.5 bg-slate-300 rounded-b-sm shadow-md border-t border-slate-400 flex items-center justify-center">
                      <div className="w-8 h-0.5 bg-slate-500 rounded-full"></div>
                    </div>
                  </div>

                  {/* Laser Sync Pulse Beam Connecting Laptop to Mobile */}
                  <div className="absolute right-[22%] top-1/2 -translate-y-1/2 w-8 h-[2px] bg-gradient-to-r from-amber-500 via-orange-400 to-amber-300 z-30 animate-sync-laser opacity-80 pointer-events-none"></div>

                  {/* Mobile Phone Mockup: Card 2 Screen with Floating Animation */}
                  <div className="absolute -right-1 sm:right-1 bottom-0 w-[30%] sm:w-[32%] aspect-[9/18] bg-[#0F172A] rounded-2xl p-0.5 shadow-2xl border-2 border-slate-800 animate-float-mobile group-hover:scale-110 group-hover:-translate-y-2 transition-all duration-500 z-20">
                    <div className="w-full h-full rounded-[14px] overflow-hidden bg-white flex flex-col justify-between relative">
                      {/* Dynamic Island */}
                      <div className="absolute top-1 left-1/2 -translate-x-1/2 w-4 h-1 bg-black rounded-full z-20"></div>

                      {/* Scanning Light Ray on Mobile */}
                      <div className="absolute inset-x-0 h-6 bg-gradient-to-b from-transparent via-amber-400/20 to-transparent pointer-events-none animate-scanline z-20"></div>

                      {/* Mobile Header */}
                      <div className="bg-amber-600 text-white p-1.5 pt-2.5 text-[6.5px]">
                        <div className="font-bold flex justify-between items-center">
                          <span>RE CRM</span>
                          <span className="font-mono text-amber-200 flex items-center gap-0.5">
                            <span className="w-1 h-1 rounded-full bg-amber-200 animate-ping"></span>
                            <span>Visits</span>
                          </span>
                        </div>
                        <div className="text-[5.5px] text-amber-100 font-medium">Daily Schedule</div>
                      </div>

                      {/* Mobile Notification Cards */}
                      <div className="p-1 space-y-1 bg-slate-50 flex-1 text-[5.5px]">
                        <div className="bg-white p-1 rounded shadow-2xs space-y-0.5 border-l-2 border-amber-500 border-y border-r border-slate-200 ring-1 ring-amber-400/30">
                          <div className="flex justify-between text-[5px]">
                            <span className="font-bold text-amber-700 uppercase flex items-center gap-0.5">
                              <span className="w-1 h-1 rounded-full bg-amber-500 animate-pulse"></span>
                              Site Visit
                            </span>
                            <span className="text-amber-600 font-bold">11:30 AM</span>
                          </div>
                          <div className="font-bold text-slate-800 text-[6px]">Priya Mehta</div>
                          <div className="text-slate-500">3 BHK • Sector 150</div>
                          <div className="text-[4.5px] text-emerald-700 font-bold bg-emerald-50 px-1 py-0.5 rounded inline-block">
                            Today • 11:30 AM
                          </div>
                        </div>

                        <div className="bg-white p-1 rounded shadow-2xs space-y-0.5 border-l-2 border-blue-500 border-y border-r border-slate-200">
                          <div className="flex justify-between text-[5px]">
                            <span className="font-bold text-blue-700 uppercase">Follow-up Due</span>
                          </div>
                          <div className="font-bold text-slate-800 text-[6px]">Rahul Verma</div>
                          <div className="text-slate-500">Tomorrow • 10:00 AM</div>
                        </div>
                      </div>

                      {/* Mobile Bottom Home Bar */}
                      <div className="h-1 bg-white flex items-center justify-center">
                        <div className="w-5 h-0.5 bg-slate-400 rounded-full"></div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>

              {/* Card Title & Text */}
              <div className="space-y-1.5 pt-1">
                <div className="flex items-start justify-between gap-2">
                  <h3 className="text-lg sm:text-xl font-bold text-[#0F172A] group-hover:text-amber-700 transition-colors leading-snug">
                    Site Visits &amp; Follow-ups
                  </h3>
                  <ArrowUpRight className="w-5 h-5 text-amber-600 shrink-0 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" />
                </div>
                <p className="text-xs sm:text-sm text-[#64748B] leading-relaxed">
                  Never miss a follow-up or site visit with automated calendar scheduling and WhatsApp location pins...
                </p>
              </div>

            </div>
          </div>

          {/* ========================================================================= */}
          {/* CARD 3 — DEALS, BOOKINGS & PAYMENTS: "Track every deal from booking to payment" */}
          {/* ========================================================================= */}
          <div
            onClick={onStartDemo}
            className="bg-white rounded-3xl border border-[#E2E8F0] p-4 sm:p-5 shadow-sm hover:shadow-2xl hover:border-emerald-300 transition-all duration-500 flex flex-col justify-between group cursor-pointer hover:-translate-y-2"
          >
            <div className="space-y-4">
              
              {/* Top Device Mockup Canvas */}
              <div className="relative w-full aspect-[16/11] rounded-2xl bg-gradient-to-br from-[#ECFDF5] via-[#F1F5F9] to-[#E2E8F0] p-3 sm:p-4 flex items-center justify-center overflow-hidden border border-[#CBD5E1]/60 group-hover:border-emerald-600/40 transition-colors">
                {/* Blueprint Grid */}
                <div className="absolute inset-0 opacity-15 pointer-events-none bg-[linear-gradient(to_right,#059669_1px,transparent_1px),linear-gradient(to_bottom,#059669_1px,transparent_1px)] bg-[size:16px_16px]"></div>

                {/* Animated Ambient Backlight Halo */}
                <div className="absolute w-44 h-44 bg-emerald-500/20 rounded-full blur-2xl pointer-events-none group-hover:scale-125 transition-transform duration-700"></div>

                <div className="relative w-full h-full flex items-center justify-center">
                  
                  {/* Laptop Mockup: Deals & Revenue Screen with Floating Animation */}
                  <div className="relative w-[85%] sm:w-[88%] aspect-[16/10] bg-[#1E293B] rounded-t-lg rounded-b-xs p-1 shadow-2xl border border-slate-700/80 -ml-6 animate-float-laptop group-hover:scale-[1.03] transition-transform duration-500">
                    <div className="w-full h-full rounded-sm overflow-hidden bg-white flex flex-col justify-between text-[6.5px] font-sans p-1.5 space-y-1 relative">
                      
                      {/* Scanning Light Ray */}
                      <div className="absolute inset-x-0 h-8 bg-gradient-to-b from-transparent via-emerald-400/20 to-transparent pointer-events-none animate-scanline z-20"></div>

                      {/* Laptop Mini Header */}
                      <div className="flex justify-between items-center pb-0.5 border-b border-slate-200">
                        <div className="flex items-center gap-1">
                          <div className="w-2 h-2 rounded-xs bg-emerald-600 text-white flex items-center justify-center font-bold text-[5px]">₹</div>
                          <span className="font-bold text-slate-800 text-[6.5px]">Deals &amp; Revenue Vault</span>
                        </div>
                        <div className="flex items-center gap-1 bg-emerald-50 text-emerald-700 font-bold px-1 py-0.2 rounded font-mono text-[5.5px]">
                          <span className="w-1 h-1 rounded-full bg-emerald-600 animate-pulse"></span>
                          <span>₹10.70 Cr Won</span>
                        </div>
                      </div>

                      {/* 3 Mini KPI Tiles */}
                      <div className="grid grid-cols-3 gap-1 text-[5.5px]">
                        <div className="bg-emerald-50 p-0.5 rounded border border-emerald-200">
                          <span className="text-emerald-700 block text-[4.5px]">WON REVENUE</span>
                          <span className="font-bold text-emerald-800 text-[7px]">₹10.70 Cr</span>
                        </div>
                        <div className="bg-blue-50 p-0.5 rounded border border-blue-200">
                          <span className="text-blue-600 block text-[4.5px]">TOKENS RECD</span>
                          <span className="font-bold text-blue-700 text-[7px]">₹1.40 Cr</span>
                        </div>
                        <div className="bg-purple-50 p-0.5 rounded border border-purple-200">
                          <span className="text-purple-600 block text-[4.5px]">ACTIVE DEALS</span>
                          <span className="font-bold text-purple-700 text-[7px]">14 Deals</span>
                        </div>
                      </div>

                      {/* Recent Bookings & Payments list */}
                      <div className="space-y-0.5 flex-1">
                        <div className="flex justify-between items-center bg-emerald-50/60 p-0.5 rounded border border-emerald-200 text-[5.5px] hover:bg-emerald-100/50 transition-colors">
                          <div>
                            <span className="font-bold text-slate-800">Vikram Aditya · 3 BHK</span>
                            <span className="text-slate-500 block text-[4.5px]">Godrej Palm #104 · ₹1.85 Cr</span>
                          </div>
                          <span className="text-emerald-700 font-bold bg-emerald-100 px-1 rounded text-[5px]">Token ₹5L Recd</span>
                        </div>
                        <div className="flex justify-between items-center bg-slate-50 p-0.5 rounded border border-slate-200 text-[5.5px]">
                          <div>
                            <span className="font-bold text-slate-800">Dr. Arvind · Villa #18</span>
                            <span className="text-slate-500 block text-[4.5px]">Prestige City · ₹4.85 Cr</span>
                          </div>
                          <span className="text-blue-700 font-bold bg-blue-50 px-1 rounded text-[5px]">Cost Sheet Sent</span>
                        </div>
                      </div>

                    </div>

                    {/* Laptop Base */}
                    <div className="absolute -bottom-1.5 left-1/2 -translate-x-1/2 w-[115%] h-1.5 bg-slate-300 rounded-b-sm shadow-md border-t border-slate-400 flex items-center justify-center">
                      <div className="w-8 h-0.5 bg-slate-500 rounded-full"></div>
                    </div>
                  </div>

                  {/* Laser Sync Pulse Beam Connecting Laptop to Mobile */}
                  <div className="absolute right-[22%] top-1/2 -translate-y-1/2 w-8 h-[2px] bg-gradient-to-r from-emerald-500 via-teal-400 to-emerald-300 z-30 animate-sync-laser opacity-80 pointer-events-none"></div>

                  {/* Mobile Phone Mockup: Card 3 Deals & Payments Screen with Floating Animation */}
                  <div className="absolute -right-1 sm:right-1 bottom-0 w-[30%] sm:w-[32%] aspect-[9/18] bg-[#0F172A] rounded-2xl p-0.5 shadow-2xl border-2 border-slate-800 animate-float-mobile group-hover:scale-110 group-hover:-translate-y-2 transition-all duration-500 z-20">
                    <div className="w-full h-full rounded-[14px] overflow-hidden bg-white flex flex-col justify-between relative">
                      {/* Dynamic Island */}
                      <div className="absolute top-1 left-1/2 -translate-x-1/2 w-4 h-1 bg-black rounded-full z-20"></div>

                      {/* Scanning Light Ray on Mobile */}
                      <div className="absolute inset-x-0 h-6 bg-gradient-to-b from-transparent via-emerald-400/20 to-transparent pointer-events-none animate-scanline z-20"></div>

                      {/* Mobile Header */}
                      <div className="bg-emerald-600 text-white p-1.5 pt-2.5 text-[6.5px]">
                        <div className="font-bold flex justify-between items-center">
                          <span>RE CRM</span>
                          <span className="font-mono text-emerald-200 flex items-center gap-0.5">
                            <span className="w-1 h-1 rounded-full bg-emerald-200 animate-ping"></span>
                            <span>Deals</span>
                          </span>
                        </div>
                        <div className="text-[5.5px] text-emerald-100 font-medium">Bookings &amp; Tokens</div>
                      </div>

                      {/* Mobile Notification Cards */}
                      <div className="p-1 space-y-1 bg-slate-50 flex-1 text-[5.5px]">
                        <div className="bg-white p-1 rounded shadow-2xs space-y-0.5 border-l-2 border-emerald-500 border-y border-r border-slate-200 ring-1 ring-emerald-400/30">
                          <div className="flex justify-between text-[5px]">
                            <span className="font-bold text-emerald-700 uppercase flex items-center gap-0.5">
                              <span className="w-1 h-1 rounded-full bg-emerald-500 animate-pulse"></span>
                              Booking Confirmed
                            </span>
                            <span className="text-emerald-600">🎉 Won</span>
                          </div>
                          <div className="font-bold text-slate-800 text-[6px]">Vikram Aditya</div>
                          <div className="text-slate-500">₹1.85 Cr • 3 BHK</div>
                          <div className="text-[4.5px] text-emerald-700 font-bold bg-emerald-50 px-1 py-0.5 rounded inline-block">
                            Booking Received
                          </div>
                        </div>

                        <div className="bg-white p-1 rounded shadow-2xs space-y-0.5 border-l-2 border-amber-500 border-y border-r border-slate-200">
                          <div className="flex justify-between text-[5px]">
                            <span className="font-bold text-amber-700 uppercase">Payment Due</span>
                          </div>
                          <div className="font-bold text-slate-800 text-[6px]">DLF Arbour</div>
                          <div className="text-slate-500">₹12.50 Lakh (20% Plinth)</div>
                        </div>
                      </div>

                      {/* Mobile Bottom Home Bar */}
                      <div className="h-1 bg-white flex items-center justify-center">
                        <div className="w-5 h-0.5 bg-slate-400 rounded-full"></div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>

              {/* Card Title & Text */}
              <div className="space-y-1.5 pt-1">
                <div className="flex items-start justify-between gap-2">
                  <h3 className="text-lg sm:text-xl font-bold text-[#0F172A] group-hover:text-emerald-700 transition-colors leading-snug">
                    Deals, Bookings &amp; Payments
                  </h3>
                  <ArrowUpRight className="w-5 h-5 text-emerald-600 shrink-0 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" />
                </div>
                <p className="text-xs sm:text-sm text-[#64748B] leading-relaxed">
                  Track every deal from booking to payment with visual pipeline stages and milestone schedules...
                </p>
              </div>

            </div>
          </div>

        </div>

      </div>
    </section>
  );
};
