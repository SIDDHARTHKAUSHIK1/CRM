import React from 'react';
import { RealEstateMotionBackground } from './RealEstateMotionBackground';
import {
  ArrowRight,
  Shield,
  Zap,
  Lock,
} from 'lucide-react';

interface RealEstatePainVsReliefProps {
  onStartDemo?: () => void;
}

export const RealEstatePainVsRelief: React.FC<RealEstatePainVsReliefProps> = () => {
  const ROWS = [
    {
      id: 'leads',
      old: {
        title: 'Leads slip through the cracks',
        desc: 'Inquiries scattered across WhatsApp chats, sticky notes, and inboxes.',
        img: '/images/pain-relief/pain-leads-stack.png',
        alt: 'Scattered sticky notes and paperwork',
      },
      new: {
        title: 'Zero missed inquiries',
        desc: 'Every lead auto-captured, logged, and assigned to the right rep.',
        img: '/images/pain-relief/relief-laptop-lead.png',
        alt: 'Automated CRM lead notification on laptop',
      },
    },
    {
      id: 'visibility',
      old: {
        title: 'No visibility on deal status',
        desc: 'Managers constantly ask reps "What happened to this customer?"',
        img: '/images/pain-relief/pain-phone-chat.png',
        alt: 'Unread WhatsApp messages on phone',
      },
      new: {
        title: 'Visual drag-and-drop pipeline',
        desc: 'See exact deal stage, value in ₹ Cr, and next action at a glance.',
        img: '/images/pain-relief/relief-kanban-board.png',
        alt: 'Visual CRM Kanban deal pipeline',
      },
    },
    {
      id: 'fatigue',
      old: {
        title: 'App fatigue & scattered tools',
        desc: 'Jumping between 4 tools for emails, WhatsApp, quotes, and calendar.',
        img: '/images/pain-relief/pain-app-fatigue.png',
        alt: 'Scattered app icons on messy desk',
      },
      new: {
        title: 'One unified workspace',
        desc: 'WhatsApp, 2-way email, PDF cost sheets, and calendar in one screen.',
        img: '/images/pain-relief/relief-unified-tablet.png',
        alt: 'Unified CRM tablet interface',
      },
    },
    {
      id: 'security',
      old: {
        title: 'Security & data theft risks',
        desc: 'Ex-employees keeping buyer contacts on personal devices.',
        img: '/images/pain-relief/pain-data-lock.png',
        alt: 'Client data files with red padlock',
      },
      new: {
        title: 'Strict role access & data vault',
        desc: 'Masked numbers, complete audit logs, and 1-click offboarding.',
        img: '/images/pain-relief/relief-vault-shield.png',
        alt: 'Encrypted security shield with role access list',
      },
    },
  ];

  return (
    <section id="why-switch" className="isolate py-4 sm:py-6 lg:py-8 relative overflow-hidden">
      <RealEstateMotionBackground variant="workspace" />
      <div className="max-w-[1400px] 2xl:max-w-[1550px] mx-auto px-4 sm:px-6 lg:px-8 space-y-3 sm:space-y-4 relative z-10">
        
        {/* Compact Section Header */}
        <div className="text-center max-w-2xl mx-auto space-y-1">
          
          {/* Top Tag Pill */}
          <div className="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-[#FAF5EE] text-[#8C6036] border border-[#E8DCB8] text-[9.5px] font-outfit tracking-[0.14em] uppercase font-bold shadow-2xs">
            <span>🏛️ PAIN VS. RELIEF • WHY BUSINESSES SWITCH</span>
          </div>

          {/* Main Title */}
          <h2 className="text-lg sm:text-2xl font-black text-[#0B1A30] tracking-tight leading-tight">
            Is Your Real Estate Sales Team Struggling with{' '}
            <span className="italic font-serif font-normal text-[#1A4D3E] underline decoration-[#10B981]/30 underline-offset-3">
              These Daily Bottlenecks?
            </span>
          </h2>

          {/* Subtitle */}
          <p className="text-[10.5px] sm:text-[11.5px] text-[#64748B] font-medium leading-normal max-w-lg mx-auto">
            From missed leads to scattered data, modern real estate CRM turns chaos into clarity.
          </p>

          {/* Header Split Badge Pill */}
          <div className="pt-0.5 flex items-center justify-center">
            <div className="inline-flex items-center gap-1.5 p-0.5 px-1.5 rounded-full bg-white/90 border border-slate-200/90 shadow-2xs backdrop-blur-md">
              <div className="flex items-center gap-1 px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 text-[8.5px] font-bold tracking-wider uppercase border border-rose-200/60">
                <span className="w-3 h-3 rounded-full bg-rose-500 text-white flex items-center justify-center text-[7px] font-black">✕</span>
                <span>THE OLD, MESSY WAY</span>
              </div>
              <span className="text-[8px] font-extrabold text-slate-400 uppercase font-mono px-0.5">VS</span>
              <div className="flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-800 text-[8.5px] font-bold tracking-wider uppercase border border-emerald-200/60">
                <span className="w-3 h-3 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[7px] font-black">✓</span>
                <span>THE MODERN CRM WAY</span>
              </div>
            </div>
          </div>

        </div>

        {/* 4 Ultra-Compact Contrast Rows */}
        <div className="space-y-1.5 sm:space-y-2 max-w-4xl mx-auto">
          {ROWS.map((row) => (
            <div
              key={row.id}
              className="bg-white/95 backdrop-blur-md rounded-xl sm:rounded-2xl border border-slate-200/90 p-1.5 sm:p-2 px-2.5 sm:px-3.5 shadow-2xs hover:shadow-md hover:border-blue-200 transition-all duration-200 group"
            >
              <div className="grid grid-cols-1 lg:grid-cols-12 gap-1.5 sm:gap-2.5 items-center">
                
                {/* Left: The Old Way (Rose Container) */}
                <div className="lg:col-span-5 bg-[#FFF7F7] rounded-lg sm:rounded-xl border border-rose-200/70 p-1.5 sm:p-2 flex items-center gap-2 shadow-2xs">
                  {/* Left 3D Illustration */}
                  <div className="w-9 h-9 sm:w-11 sm:h-11 shrink-0 flex items-center justify-center drop-shadow-2xs group-hover:scale-105 transition-transform duration-200">
                    <img
                      src={row.old.img}
                      alt={row.old.alt}
                      className="w-full h-full object-contain"
                    />
                  </div>

                  <div className="space-y-0.5 min-w-0 flex-1">
                    <div className="flex items-center gap-1">
                      <span className="w-3 h-3 rounded-full bg-rose-600 text-white flex items-center justify-center text-[7px] font-bold shrink-0">
                        ✕
                      </span>
                      <h4 className="font-bold text-[10.5px] sm:text-[11.5px] text-rose-950 truncate leading-tight">
                        {row.old.title}
                      </h4>
                    </div>
                    <p className="text-[9.5px] sm:text-[10px] text-rose-900/80 leading-tight line-clamp-1">
                      {row.old.desc}
                    </p>
                  </div>
                </div>

                {/* Center Transition Arrow */}
                <div className="lg:col-span-2 flex items-center justify-center">
                  <div className="w-5 h-5 sm:w-5.5 sm:h-5.5 rounded-full bg-white border border-slate-200 shadow-2xs flex items-center justify-center text-[#1864E8] font-bold group-hover:scale-110 group-hover:bg-blue-50 transition-all">
                    <ArrowRight className="w-2.5 h-2.5 rotate-90 lg:rotate-0" />
                  </div>
                </div>

                {/* Right: The Modern CRM Way (Mint Container) */}
                <div className="lg:col-span-5 bg-[#F2FAF7] rounded-lg sm:rounded-xl border border-emerald-200/70 p-1.5 sm:p-2 flex items-center justify-between gap-2 shadow-2xs">
                  <div className="space-y-0.5 min-w-0 flex-1">
                    <div className="flex items-center gap-1">
                      <span className="w-3 h-3 rounded-full bg-emerald-600 text-white flex items-center justify-center text-[7px] font-bold shrink-0">
                        ✓
                      </span>
                      <h4 className="font-bold text-[10.5px] sm:text-[11.5px] text-emerald-950 truncate leading-tight">
                        {row.new.title}
                      </h4>
                    </div>
                    <p className="text-[9.5px] sm:text-[10px] text-emerald-900/80 leading-tight line-clamp-1">
                      {row.new.desc}
                    </p>
                  </div>

                  {/* Right 3D UI Preview Card */}
                  <div className="w-14 h-9 sm:w-18 sm:h-11 shrink-0 flex items-center justify-center drop-shadow-2xs group-hover:scale-105 transition-transform duration-200">
                    <img
                      src={row.new.img}
                      alt={row.new.alt}
                      className="w-full h-full object-contain"
                    />
                  </div>
                </div>

              </div>
            </div>
          ))}
        </div>

        {/* Bottom Trust Badge Capsule */}
        <div className="pt-0.5 flex justify-center">
          <div className="inline-flex flex-wrap items-center justify-center gap-3 sm:gap-6 px-3.5 py-1 rounded-full bg-white/90 backdrop-blur-md border border-slate-200/90 shadow-2xs text-[9.5px] sm:text-[10px] font-semibold text-slate-700">
            <div className="flex items-center gap-1">
              <Shield className="w-3 h-3 text-[#1864E8]" />
              <span>No credit card required</span>
            </div>
            <span className="hidden sm:inline text-slate-300">•</span>
            <div className="flex items-center gap-1">
              <Zap className="w-3 h-3 text-amber-500" />
              <span>Setup in under 5 minutes</span>
            </div>
            <span className="hidden sm:inline text-slate-300">•</span>
            <div className="flex items-center gap-1">
              <Lock className="w-3 h-3 text-emerald-600" />
              <span>100% Private &amp; Secure</span>
            </div>
          </div>
        </div>

      </div>
    </section>
  );
};
