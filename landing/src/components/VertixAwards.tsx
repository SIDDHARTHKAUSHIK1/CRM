import React from 'react';
import { ArrowRight, Zap } from 'lucide-react';

interface VertixAwardsProps {
  onSeeAll?: () => void;
}

export const VertixAwards: React.FC<VertixAwardsProps> = ({ onSeeAll }) => {
  return (
    <section className="py-10 lg:py-14 bg-[#F4F3EE] border-b border-[#D8D5CA]">
      <div className="max-w-5xl mx-auto px-4 sm:px-6">
        {/* Header Bar */}
        <div className="flex items-center justify-between pb-4 border-b border-[#D8D5CA] mb-6 sm:mb-8">
          <span className="text-[11px] font-bold tracking-[0.25em] text-[#0A0A0A] uppercase font-mono">
            PLATFORM SECURITY &amp; TRUST POINTS
          </span>

          <button
            onClick={onSeeAll}
            className="group inline-flex items-center gap-1.5 text-[11px] font-bold tracking-wider text-[#0A0A0A] hover:text-emerald-800 uppercase transition-colors cursor-pointer"
          >
            <span>PLATFORM STANDARDS</span>
            <ArrowRight className="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" />
          </button>
        </div>

        {/* 5 Trust Badges matching exact visual layout */}
        <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5 sm:gap-4 items-center text-center">
          {/* 1. Strict Role Access */}
          <div className="space-y-1.5 p-3.5 rounded-xl bg-white border border-[#CDC9BC] shadow-xs hover:border-[#0A0A0A] hover-lift transition-all">
            <div className="font-serif text-lg font-bold tracking-tight text-[#0A0A0A]">
              ROLE ACCESS
            </div>
            <div className="text-[11px] text-[#262522] font-medium leading-tight">
              Executive &amp; Manager Permissions
            </div>
            <div className="font-mono text-[9px] tracking-widest text-emerald-800 uppercase font-extrabold pt-0.5">
              BUYER VAULT SECURED
            </div>
          </div>

          {/* 2. Bank-Grade Security */}
          <div className="space-y-1.5 p-3.5 rounded-xl bg-white border border-[#CDC9BC] shadow-xs hover:border-[#0A0A0A] hover-lift transition-all">
            <div className="font-brand text-lg font-bold tracking-tight text-[#0A0A0A]">
              AES-256
            </div>
            <div className="text-[11px] text-[#262522] font-medium leading-tight">
              Encrypted Buyer Data
            </div>
            <div className="font-mono text-[9px] tracking-widest text-emerald-800 uppercase font-extrabold pt-0.5">
              SECURE STORAGE
            </div>
          </div>

          {/* 3. Official WhatsApp Partner */}
          <div className="space-y-1.5 p-3.5 rounded-xl bg-white border border-[#CDC9BC] shadow-xs hover:border-[#0A0A0A] hover-lift transition-all">
            <div className="inline-block px-2.5 py-0.5 bg-[#0A0A0A] text-white text-xs font-black tracking-tight rounded font-mono">
              WHATSAPP
            </div>
            <div className="text-[11px] text-[#262522] font-medium leading-tight uppercase">
              Two-Way Chat &amp; Email Sync
            </div>
            <div className="font-mono text-[9px] tracking-widest text-emerald-800 uppercase font-extrabold pt-0.5">
              UNIFIED TIMELINE
            </div>
          </div>

          {/* 4. Ex-Employee Contact Protection */}
          <div className="space-y-1.5 p-3.5 rounded-xl bg-white border border-[#CDC9BC] shadow-xs hover:border-[#0A0A0A] hover-lift transition-all">
            <div className="font-serif italic text-lg font-bold text-[#0A0A0A]">
              access-guard
            </div>
            <div className="text-[11px] text-[#262522] font-medium leading-tight">
              1-Click Employee Offboarding
            </div>
            <div className="font-mono text-[9px] tracking-widest text-emerald-800 uppercase font-extrabold pt-0.5">
              PROTECTED DATABASE
            </div>
          </div>

          {/* 5. Zero-Code Setup */}
          <div className="space-y-1.5 p-3.5 rounded-xl bg-white border border-[#CDC9BC] shadow-xs hover:border-[#0A0A0A] transition-colors col-span-2 sm:col-span-1">
            <div className="flex items-center justify-center gap-1 text-[#0A0A0A]">
              <Zap className="w-4 h-4 text-amber-600 fill-amber-600" />
              <span className="font-brand text-sm tracking-wider font-extrabold">&lt; 5 MIN</span>
            </div>
            <div className="text-[11px] text-[#262522] font-medium leading-tight">
              Zero IT Training Needed
            </div>
            <div className="font-mono text-[9px] tracking-widest text-emerald-800 uppercase font-extrabold pt-0.5">
              NON-TECHNICAL TEAMS
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};
