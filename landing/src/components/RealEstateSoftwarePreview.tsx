import React from 'react';
import { RealEstateMotionBackground } from './RealEstateMotionBackground';
import {
  LayoutDashboard,
  Users,
  MessageSquare,
  FileText,
  TrendingUp,
  Sparkles,
  Search,
  Plus,
  Calendar,
  Mail,
  TrendingDown,
  Lock,
  ChevronLeft,
} from 'lucide-react';

interface RealEstateSoftwarePreviewProps {
  onStartDemo: () => void;
}

export const RealEstateSoftwarePreview: React.FC<RealEstateSoftwarePreviewProps> = () => {
  return (
    <section id="software-preview" className="isolate py-12 sm:py-16 bg-white/85 backdrop-blur-xs border-b border-[#E2E8F0]/80 relative overflow-hidden">
      <RealEstateMotionBackground variant="workspace" />
      <div className="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 sm:space-y-10">
        
        {/* Centered Section Header without CTA Button */}
        <div className="text-center space-y-2.5 max-w-2xl mx-auto">
          <div className="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-gradient-to-r from-blue-50/90 via-indigo-50/70 to-blue-50/90 text-[#1864E8] text-[11px] font-outfit tracking-[0.22em] uppercase font-extrabold border border-blue-200/90 shadow-[0_2px_10px_rgba(24,100,232,0.08)] backdrop-blur-md">
            <span className="relative flex h-2 w-2">
              <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
              <span className="relative inline-flex rounded-full h-2 w-2 bg-[#1864E8]"></span>
            </span>
            <span>INSIDE REAL ESTATE CRM</span>
          </div>
          
          <h2 className="text-2xl sm:text-3xl lg:text-[34px] font-extrabold text-[#0F172A] tracking-tight leading-tight">
            A Look Inside the Actual Software
          </h2>
          <p className="text-xs sm:text-sm text-[#475569] leading-relaxed max-w-xl mx-auto">
            Designed for simplicity and speed. Track revenue in ₹ Crores, monitor deal negotiations, and broadcast to WhatsApp in one unified interface.
          </p>
        </div>

        {/* Compact & Ultra-HD Native Vector Software UI Window (Pixel-Perfect at Any Resolution) */}
        <div className="max-w-5xl mx-auto bg-white rounded-2xl border border-slate-200/90 shadow-xl overflow-hidden group relative">
          
          {/* Actual Native High-Fidelity Ultra-HD CRM Dashboard Interface */}
          <div className="bg-[#F8FAFC] p-3 sm:p-5 text-slate-800 font-sans select-none antialiased">
            <div className="grid grid-cols-12 gap-3 sm:gap-4">
              
              {/* Left Sidebar */}
              <div className="col-span-3 sm:col-span-3 lg:col-span-2 bg-white rounded-xl border border-slate-200/80 p-2.5 sm:p-3 space-y-3 hidden sm:flex flex-col justify-between shadow-2xs">
                <div className="space-y-3">
                  {/* Brand Header */}
                  <div className="flex items-center justify-between pb-2 border-b border-slate-100">
                    <div className="flex items-center gap-1.5">
                      <div className="w-6 h-6 rounded-md bg-[#6366F1] text-white flex items-center justify-center font-bold text-[10px]">
                        RE
                      </div>
                      <div>
                        <div className="font-extrabold text-[11px] text-slate-900 leading-none">CRM</div>
                        <div className="text-[7.5px] text-slate-400 font-medium">Grow · Manage</div>
                      </div>
                    </div>
                    <ChevronLeft className="w-3.5 h-3.5 text-slate-400" />
                  </div>

                  {/* Nav Links */}
                  <div className="space-y-1 text-[11px] font-medium">
                    <div className="flex items-center gap-2 px-2 py-1.5 rounded-lg bg-[#6366F1]/10 text-[#6366F1] font-bold">
                      <LayoutDashboard className="w-3.5 h-3.5" />
                      <span>Dashboard</span>
                    </div>
                    <div className="flex items-center gap-2 px-2 py-1.5 rounded-lg text-slate-600 hover:bg-slate-50">
                      <Users className="w-3.5 h-3.5 text-emerald-500" />
                      <span>Leads</span>
                    </div>
                    <div className="flex items-center gap-2 px-2 py-1.5 rounded-lg text-slate-600 hover:bg-slate-50">
                      <FileText className="w-3.5 h-3.5 text-indigo-500" />
                      <span>Quotes</span>
                    </div>
                    <div className="flex items-center gap-2 px-2 py-1.5 rounded-lg text-slate-600 hover:bg-slate-50">
                      <MessageSquare className="w-3.5 h-3.5 text-emerald-600" />
                      <span className="truncate">WhatsApp</span>
                    </div>
                    <div className="flex items-center gap-2 px-2 py-1.5 rounded-lg text-slate-600 hover:bg-slate-50">
                      <Mail className="w-3.5 h-3.5 text-purple-500" />
                      <span>Mail</span>
                    </div>
                    <div className="flex items-center gap-2 px-2 py-1.5 rounded-lg text-slate-600 hover:bg-slate-50">
                      <Calendar className="w-3.5 h-3.5 text-blue-500" />
                      <span>Activities</span>
                    </div>
                  </div>
                </div>

                <div className="pt-2 border-t border-slate-100 flex items-center gap-2 text-[10px] text-slate-500 font-medium">
                  <div className="w-5 h-5 rounded-full bg-[#6366F1] text-white flex items-center justify-center font-bold text-[9px]">E</div>
                  <div className="truncate font-semibold text-slate-700">Enterprise</div>
                </div>
              </div>

              {/* Main Dashboard Area */}
              <div className="col-span-12 sm:col-span-9 lg:col-span-10 space-y-3 sm:space-y-3.5">
                
                {/* Top Search & Actions Bar */}
                <div className="flex flex-wrap items-center justify-between gap-2 bg-white p-2 sm:p-2.5 rounded-xl border border-slate-200/80 shadow-2xs">
                  <div className="flex items-center gap-2 flex-1 max-w-xs bg-slate-50 px-2.5 py-1.5 rounded-lg border border-slate-200/70 text-slate-400 text-xs">
                    <Search className="w-3.5 h-3.5 text-slate-400" />
                    <span className="text-[11px]">Search anything...</span>
                  </div>

                  <div className="flex items-center gap-2">
                    <button className="inline-flex items-center gap-1 bg-[#6366F1] hover:bg-[#4F46E5] text-white text-[11px] font-bold px-2.5 py-1.5 rounded-lg shadow-2xs">
                      <Plus className="w-3.5 h-3.5" />
                      <span>Add</span>
                    </button>
                    <span className="bg-rose-500 text-white font-bold text-[10px] px-2 py-1 rounded-md">ADMIN</span>
                    <div className="hidden md:flex items-center gap-1.5 bg-slate-50 border border-slate-200 px-2 py-1 rounded-lg text-[10.5px] font-mono text-slate-600">
                      <Calendar className="w-3 h-3 text-slate-400" />
                      <span>2026-09-03 to 2026-10-03</span>
                    </div>
                  </div>
                </div>

                {/* Dashboard Title */}
                <div className="space-y-0.5">
                  <h3 className="text-sm sm:text-base font-black text-slate-900">Dashboard</h3>
                  <p className="text-[10.5px] sm:text-xs text-slate-500">Here's what's happening in your real estate pipeline today.</p>
                </div>

                {/* 4 Ultra-HD Crisp KPI Cards */}
                <div className="grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-2.5">
                  
                  {/* WON REVENUE */}
                  <div className="bg-white p-2.5 sm:p-3 rounded-xl border border-slate-200/90 shadow-2xs space-y-1.5">
                    <div className="flex items-center justify-between">
                      <span className="text-[9.5px] font-bold font-mono text-slate-400 uppercase tracking-wider">WON REVENUE</span>
                      <div className="w-5 h-5 rounded-md bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <Lock className="w-2.5 h-2.5" />
                      </div>
                    </div>
                    <div className="font-black text-sm sm:text-base text-slate-900 tracking-tight">
                      ₹107,000,000...
                    </div>
                    <div className="inline-flex items-center gap-1 text-[9px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">
                      <TrendingUp className="w-2.5 h-2.5" />
                      <span>+100% vs 30d</span>
                    </div>
                  </div>

                  {/* LOST REVENUE */}
                  <div className="bg-white p-2.5 sm:p-3 rounded-xl border border-slate-200/90 shadow-2xs space-y-1.5">
                    <div className="flex items-center justify-between">
                      <span className="text-[9.5px] font-bold font-mono text-slate-400 uppercase tracking-wider">LOST REVENUE</span>
                      <div className="w-5 h-5 rounded-md bg-rose-50 text-rose-500 flex items-center justify-center">
                        <TrendingDown className="w-2.5 h-2.5" />
                      </div>
                    </div>
                    <div className="font-black text-sm sm:text-base text-slate-900 tracking-tight">
                      ₹35,000,000...
                    </div>
                    <div className="inline-flex items-center gap-1 text-[9px] font-bold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded">
                      <TrendingDown className="w-2.5 h-2.5" />
                      <span>-100% vs 30d</span>
                    </div>
                  </div>

                  {/* TOTAL LEADS */}
                  <div className="bg-white p-2.5 sm:p-3 rounded-xl border border-slate-200/90 shadow-2xs space-y-1.5">
                    <div className="flex items-center justify-between">
                      <span className="text-[9.5px] font-bold font-mono text-slate-400 uppercase tracking-wider">TOTAL LEADS</span>
                      <div className="w-5 h-5 rounded-md bg-blue-50 text-blue-600 flex items-center justify-center">
                        <Users className="w-2.5 h-2.5" />
                      </div>
                    </div>
                    <div className="font-black text-sm sm:text-base text-slate-900 tracking-tight">
                      10
                    </div>
                    <div className="inline-flex items-center gap-1 text-[9px] font-bold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded">
                      <TrendingUp className="w-2.5 h-2.5" />
                      <span>+100% vs 30d</span>
                    </div>
                  </div>

                  {/* AVERAGE LEAD VALUE */}
                  <div className="bg-white p-2.5 sm:p-3 rounded-xl border border-slate-200/90 shadow-2xs space-y-1.5">
                    <div className="flex items-center justify-between">
                      <span className="text-[9.5px] font-bold font-mono text-slate-400 uppercase tracking-wider truncate">AVG LEAD VALUE</span>
                      <div className="w-5 h-5 rounded-md bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-[9.5px]">
                        ₹
                      </div>
                    </div>
                    <div className="font-black text-sm sm:text-base text-slate-900 tracking-tight truncate">
                      ₹51,167,444...
                    </div>
                    <div className="inline-flex items-center gap-1 text-[9px] font-bold text-amber-700 bg-amber-50 px-1.5 py-0.5 rounded">
                      <TrendingUp className="w-2.5 h-2.5" />
                      <span>+100% vs 30d</span>
                    </div>
                  </div>

                </div>

                {/* Bottom 6 Status Mini Metric Cards */}
                <div className="grid grid-cols-3 sm:grid-cols-6 gap-1.5 sm:gap-2">
                  <div className="bg-white p-2 rounded-lg border border-slate-200 text-center space-y-0.5 shadow-2xs">
                    <span className="text-[8.5px] text-slate-400 block font-medium">New Leads</span>
                    <span className="text-xs sm:text-sm font-black text-blue-600">👤 3</span>
                  </div>
                  <div className="bg-white p-2 rounded-lg border border-slate-200 text-center space-y-0.5 shadow-2xs">
                    <span className="text-[8.5px] text-slate-400 block font-medium">In Negotiation</span>
                    <span className="text-xs sm:text-sm font-black text-amber-600">💬 2</span>
                  </div>
                  <div className="bg-white p-2 rounded-lg border border-slate-200 text-center space-y-0.5 shadow-2xs">
                    <span className="text-[8.5px] text-slate-400 block font-medium">In Prospect</span>
                    <span className="text-xs sm:text-sm font-black text-emerald-600">🎯 1</span>
                  </div>
                  <div className="bg-white p-2 rounded-lg border border-slate-200 text-center space-y-0.5 shadow-2xs">
                    <span className="text-[8.5px] text-slate-400 block font-medium">Total Quotes</span>
                    <span className="text-xs sm:text-sm font-black text-purple-600">📄 2</span>
                  </div>
                  <div className="bg-white p-2 rounded-lg border border-slate-200 text-center space-y-0.5 shadow-2xs">
                    <span className="text-[8.5px] text-slate-400 block font-medium">Total Persons</span>
                    <span className="text-xs sm:text-sm font-black text-cyan-600">👥 8</span>
                  </div>
                  <div className="bg-white p-2 rounded-lg border border-slate-200 text-center space-y-0.5 shadow-2xs">
                    <span className="text-[8.5px] text-slate-400 block font-medium">Total Orgs</span>
                    <span className="text-xs sm:text-sm font-black text-indigo-600">🏢 8</span>
                  </div>
                </div>

              </div>

            </div>
          </div>

        </div>

      </div>
    </section>
  );
};
