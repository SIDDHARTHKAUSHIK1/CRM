import React from 'react';
import { RealEstateMotionBackground } from './RealEstateMotionBackground';
import {
  Sparkles,
  Zap,
  Building2,
  Clock,
  ShieldCheck,
  TrendingUp,
  MessageSquare,
  CheckCircle2,
  FileSpreadsheet,
  Layers,
} from 'lucide-react';

export const RealEstateMarquee: React.FC = () => {
  const PORTAL_PARTNERS = [
    { name: '99acres Integration', badge: 'PORTAL API', highlight: 'Instant Webhook' },
    { name: 'Magicbricks Sync', badge: 'LEAD PORTAL', highlight: '2-Second Capture' },
    { name: 'Housing.com Leads', badge: 'DIRECT SYNC', highlight: 'Auto-Routing' },
    { name: 'Meta Lead Ads', badge: 'FB & IG', highlight: 'Zero Delay' },
    { name: 'WhatsApp Business Cloud', badge: 'OFFICIAL API', highlight: '60s Auto-Reply' },
    { name: 'Google Ads Search', badge: 'PPC SYNC', highlight: 'Campaign ROI' },
    { name: 'Godrej Properties CPs', badge: 'DEVELOPER', highlight: 'Inventory Linked' },
    { name: 'DLF Luxury Channels', badge: 'BROKERAGE', highlight: 'Exclusive Pipeline' },
    { name: 'Lodha Group Network', badge: 'BUILDER', highlight: 'Token Automation' },
    { name: 'Prestige Group Sales', badge: 'TOWNSHIP', highlight: 'Site Visit CRM' },
  ];

  const CRM_METRICS = [
    { label: '₹420+ Crore', desc: 'Real Estate Deals Closed', icon: TrendingUp, color: 'text-emerald-600' },
    { label: '60-Second SLA', desc: 'WhatsApp Brochure Delivery', icon: Clock, color: 'text-[#1864E8]' },
    { label: '100% Data Vault', desc: 'Masked Numbers & Role Security', icon: ShieldCheck, color: 'text-indigo-600' },
    { label: '0% Lead Leakage', desc: 'Automated Deduplication', icon: CheckCircle2, color: 'text-amber-600' },
    { label: '30-Second Quotes', desc: 'Instant PDF Milestone Cost Sheets', icon: FileSpreadsheet, color: 'text-blue-600' },
    { label: '14,000+ Enquiries', desc: 'Auto-Assigned Every Month', icon: Zap, color: 'text-purple-600' },
    { label: '98.4% Visit Show Rate', desc: 'WhatsApp Calendar Reminders', icon: Building2, color: 'text-teal-600' },
  ];

  return (
    <section className="isolate w-full py-5 sm:py-8 bg-slate-900 text-white relative overflow-hidden select-none">
      <RealEstateMotionBackground variant="pipeline" tone="dark" />
      {/* Background Ambient Glows */}
      <div className="absolute top-1/2 left-1/4 -translate-y-1/2 w-96 h-32 bg-blue-500/15 rounded-full blur-3xl pointer-events-none" />
      <div className="absolute top-1/2 right-1/4 -translate-y-1/2 w-96 h-32 bg-indigo-500/15 rounded-full blur-3xl pointer-events-none" />

      <div className="relative z-10 w-full space-y-3.5 sm:space-y-4">
        
        {/* Top Header Label */}
        <div className="max-w-[1400px] 2xl:max-w-[1600px] mx-auto px-4 sm:px-8 md:px-12 lg:px-16 flex items-center justify-between">
          <div className="flex items-center gap-2">
            <span className="relative flex h-2 w-2">
              <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
              <span className="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            <span className="text-[10px] sm:text-[11px] font-mono font-bold tracking-[0.2em] text-slate-300 uppercase">
              LIVE MULTI-CHANNEL INTEGRATIONS &amp; DEAL PERFORMANCE
            </span>
          </div>
        </div>

        {/* Row 1: Forward Marquee (Portals & Channels) */}
        <div className="w-full overflow-hidden mask-marquee-edges marquee-container py-0.5">
          <div className="animate-marquee gap-3 sm:gap-4 flex items-center">
            {/* Array duplicated twice for infinite seamless scroll */}
            {[...PORTAL_PARTNERS, ...PORTAL_PARTNERS].map((item, idx) => (
              <div
                key={idx}
                className="flex items-center gap-2.5 px-3.5 sm:px-4 py-1.5 sm:py-2 rounded-xl bg-slate-800/80 hover:bg-slate-800 border border-slate-700/80 hover:border-blue-500/80 backdrop-blur-md transition-all duration-300 group cursor-pointer hover:scale-[1.02] shadow-sm hover:shadow-blue-500/10 shrink-0"
              >
                <div className="flex flex-col">
                  <div className="flex items-center gap-2">
                    <span className="font-bold text-xs text-white group-hover:text-blue-300 transition-colors whitespace-nowrap">
                      {item.name}
                    </span>
                    <span className="text-[8.5px] font-mono font-extrabold px-1.5 py-0.5 rounded bg-blue-500/20 text-blue-300 border border-blue-400/30 uppercase">
                      {item.badge}
                    </span>
                  </div>
                  <span className="text-[9.5px] text-slate-400 font-medium">
                    {item.highlight}
                  </span>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Row 2: Reverse Marquee (Performance Metrics & Capabilities) */}
        <div className="w-full overflow-hidden mask-marquee-edges marquee-container py-0.5">
          <div className="animate-marquee-reverse gap-3 sm:gap-4 flex items-center">
            {/* Array duplicated twice for infinite seamless scroll */}
            {[...CRM_METRICS, ...CRM_METRICS].map((metric, idx) => {
              const Icon = metric.icon;
              return (
                <div
                  key={idx}
                  className="flex items-center gap-2.5 px-3.5 sm:px-4 py-1.5 sm:py-2 rounded-xl bg-slate-800/60 hover:bg-slate-800 border border-slate-700/70 hover:border-emerald-500/80 backdrop-blur-md transition-all duration-300 group cursor-pointer hover:scale-[1.02] shadow-sm hover:shadow-emerald-500/10 shrink-0"
                >
                  <div className="w-7 h-7 rounded-lg bg-slate-700/80 flex items-center justify-center text-white group-hover:scale-110 transition-transform border border-slate-600/60 shrink-0">
                    <Icon className={`w-3.5 h-3.5 ${metric.color}`} />
                  </div>
                  <div className="flex flex-col">
                    <span className="font-black text-xs text-white group-hover:text-emerald-300 transition-colors whitespace-nowrap">
                      {metric.label}
                    </span>
                    <span className="text-[9.5px] text-slate-400 font-medium whitespace-nowrap">
                      {metric.desc}
                    </span>
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
