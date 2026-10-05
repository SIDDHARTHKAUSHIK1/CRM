import React from 'react';
import {
  Inbox,
  Kanban,
  MessageCircle,
  FileCheck2,
  Calendar,
  Lock,
  Check,
  Zap,
  ArrowRight,
  ShieldCheck,
  FileText,
  MapPin,
  Sparkles,
} from 'lucide-react';

interface VertixFeaturesProps {
  onStartDemo?: () => void;
}

export const VertixFeatures: React.FC<VertixFeaturesProps> = ({ onStartDemo }) => {
  const FEATURES = [
    {
      id: 'centralized-leads',
      icon: Inbox,
      badge: 'LEAD CAPTURE',
      title: 'Unified Lead Capture',
      tagline: '99acres · Magicbricks · Meta Ads · WhatsApp',
      bullets: [
        'Auto-imports buyer leads from all portals in real-time',
        'Built-in deduplication prevents duplicate calls',
      ],
      previewSnippet: (
        <div className="bg-[#121316] p-2.5 rounded-lg border border-white/10 text-white space-y-1.5 font-mono text-[10px]">
          <div className="flex justify-between items-center text-neutral-400">
            <span>Inbound Lead #482</span>
            <span className="text-emerald-400 font-bold">Auto-Captured</span>
          </div>
          <div className="font-sans text-xs font-bold text-white">Vikram Singhania · ₹1.85 Cr</div>
          <div className="flex gap-1.5">
            <span className="px-1.5 py-0.5 rounded bg-white/10 text-neutral-300">WhatsApp</span>
            <span className="px-1.5 py-0.5 rounded bg-white/10 text-neutral-300">Sector 150</span>
          </div>
        </div>
      ),
    },
    {
      id: 'kanban-pipeline',
      icon: Kanban,
      badge: 'SALES PIPELINE',
      title: 'Visual Deal Board',
      tagline: 'Drag-and-drop property deal stages',
      bullets: [
        'Custom stages: Qualified → Site Visit → Cost Sheet → Won',
        'Live pipeline value calculated in ₹ Lakhs & Crores',
      ],
      previewSnippet: (
        <div className="bg-[#121316] p-2.5 rounded-lg border border-white/10 text-white space-y-1.5 font-mono text-[10px]">
          <div className="grid grid-cols-3 gap-1 text-center">
            <div className="bg-white/5 p-1 rounded">
              <span className="text-neutral-400 block text-[9px]">Qualified</span>
              <span className="text-white font-bold">14</span>
            </div>
            <div className="bg-amber-500/20 text-amber-300 p-1 rounded border border-amber-500/30">
              <span className="block text-[9px]">Site Visit</span>
              <span className="font-bold">8</span>
            </div>
            <div className="bg-emerald-500/20 text-emerald-300 p-1 rounded border border-emerald-500/30">
              <span className="block text-[9px]">Won 🎉</span>
              <span className="font-bold">12</span>
            </div>
          </div>
        </div>
      ),
    },
    {
      id: 'whatsapp-sync',
      icon: MessageCircle,
      badge: 'WHATSAPP SUITE',
      title: 'WhatsApp & Email Chat',
      tagline: 'Instant PDF brochures & 2-way logged chat',
      bullets: [
        'Official WhatsApp Business API integration',
        'Pre-approved quick-reply floor plans & payment plans',
      ],
      previewSnippet: (
        <div className="bg-[#121316] p-2.5 rounded-lg border border-white/10 text-white space-y-1.5 font-mono text-[10px]">
          <div className="flex items-center justify-between text-neutral-400">
            <span>WhatsApp · Pooja Sharma</span>
            <span className="text-emerald-400">Delivered</span>
          </div>
          <div className="bg-white/5 p-1.5 rounded text-neutral-200 font-sans text-[11px] flex items-center justify-between">
            <span className="truncate">DLF_4BHK_Brochure.pdf</span>
            <span className="text-emerald-400 font-bold ml-1">Viewed</span>
          </div>
        </div>
      ),
    },
    {
      id: 'pdf-costsheets',
      icon: FileCheck2,
      badge: 'COST SHEETS',
      title: '1-Click PDF Proposals',
      tagline: 'Itemized milestone pricing & GST in seconds',
      bullets: [
        'Pre-loaded unit inventory with base price, PLC & parking',
        'Exports branded PDF with construction-linked schedule',
      ],
      previewSnippet: (
        <div className="bg-[#121316] p-2.5 rounded-lg border border-white/10 text-white space-y-1 font-mono text-[10px]">
          <div className="flex justify-between text-neutral-400">
            <span>Cost Sheet #108</span>
            <span className="text-white font-bold">Godrej Palm Retreat</span>
          </div>
          <div className="flex justify-between font-sans text-xs font-bold text-emerald-400 pt-0.5">
            <span>Total All-Inclusive</span>
            <span>₹1,84,50,000</span>
          </div>
        </div>
      ),
    },
    {
      id: 'site-visits',
      icon: Calendar,
      badge: 'SITE VISITS',
      title: 'Site Visit Planner',
      tagline: 'Google Maps pin & automated reminders',
      bullets: [
        'Team calendar with real-time manager availability',
        'WhatsApp location pin sent 2 hours before walkthrough',
      ],
      previewSnippet: (
        <div className="bg-[#121316] p-2.5 rounded-lg border border-white/10 text-white space-y-1 font-mono text-[10px]">
          <div className="flex items-center justify-between">
            <span className="text-amber-400 font-bold flex items-center gap-1">
              <MapPin className="w-3 h-3" /> Saturday 11:30 AM
            </span>
            <span className="text-emerald-400">Confirmed</span>
          </div>
          <div className="font-sans text-xs text-white">Sunil Narang · Prestige City Villa #18</div>
        </div>
      ),
    },
    {
      id: 'data-vault',
      icon: Lock,
      badge: 'SECURITY VAULT',
      title: 'Buyer Data Protection',
      tagline: 'Masked phone numbers & role permissions',
      bullets: [
        'Cloud click-to-call shields raw buyer phone numbers',
        '1-Click employee offboarding protects company database',
      ],
      previewSnippet: (
        <div className="bg-[#121316] p-2.5 rounded-lg border border-white/10 text-white space-y-1 font-mono text-[10px]">
          <div className="flex justify-between text-neutral-400">
            <span>Buyer Phone Masking</span>
            <span className="text-emerald-400 font-bold">Active</span>
          </div>
          <div className="font-sans text-xs font-bold text-white flex items-center justify-between">
            <span>+91 98XXX XXX89</span>
            <span className="text-[9px] font-mono text-neutral-400">No Export Allowed</span>
          </div>
        </div>
      ),
    },
  ];

  return (
    <section id="features" className="py-12 lg:py-16 bg-[#F8F7F4] border-b-2 border-[#D8D5CA] relative">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 lg:space-y-8">
        
        {/* Section Header */}
        <div className="flex flex-col md:flex-row md:items-end justify-between gap-3 pb-5 border-b border-[#D8D5CA]">
          <div className="space-y-1.5 max-w-2xl">
            <div className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#0A0A0A] text-white text-[10px] font-mono tracking-widest uppercase font-bold shadow-xs">
              <Sparkles className="w-3 h-3 text-emerald-400" />
              <span>CORE CAPABILITIES</span>
            </div>
            <h2 className="font-serif text-2xl sm:text-3xl lg:text-4xl text-[#0A0A0A] font-bold leading-tight">
              Visual Tools Built to <span className="italic font-normal text-emerald-800">Accelerate Property Deals</span>
            </h2>
            <p className="text-xs sm:text-sm text-[#3D3A34] font-normal leading-relaxed">
              Every tool engineered specifically for the practical day-to-day workflow of Indian property consultants, developers, and brokers.
            </p>
          </div>

          <div className="font-mono text-[10px] text-[#3D3A34] uppercase tracking-wider font-extrabold bg-white px-3 py-1.5 rounded-full border border-[#CDC9BC] shadow-2xs shrink-0">
            <span>6 Integrated Modules</span>
          </div>
        </div>

        {/* 3-Column Visual Feature Cards Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5 items-stretch">
          {FEATURES.map((feat) => {
            const Icon = feat.icon;
            return (
              <div
                key={feat.id}
                className="p-4 sm:p-5 rounded-2xl bg-white border border-[#CDC9BC] hover:border-[#0A0A0A] hover-lift flex flex-col justify-between space-y-4 shadow-sm transition-all group"
              >
                <div className="space-y-3">
                  {/* Top Header */}
                  <div className="flex items-center justify-between">
                    <div className="w-8 h-8 rounded-xl bg-[#0A0A0A] flex items-center justify-center text-white shadow-xs group-hover:bg-emerald-800 transition-colors">
                      <Icon className="w-4 h-4 stroke-[2]" />
                    </div>
                    <span className="text-[9px] font-mono tracking-wider uppercase px-2.5 py-0.5 rounded-full bg-[#EFECE4] text-[#111111] font-bold">
                      {feat.badge}
                    </span>
                  </div>

                  {/* Title & Tagline */}
                  <div>
                    <h3 className="font-serif text-lg sm:text-xl text-[#0A0A0A] font-bold leading-snug">
                      {feat.title}
                    </h3>
                    <p className="text-xs font-mono text-emerald-800 font-medium mt-0.5">
                      {feat.tagline}
                    </p>
                  </div>

                  {/* Visual UI Preview Snippet */}
                  {feat.previewSnippet}

                  {/* Bullets */}
                  <div className="space-y-1.5 pt-1">
                    {feat.bullets.map((b, bIdx) => (
                      <div key={bIdx} className="flex items-start gap-2 text-xs text-[#262522] font-medium leading-snug">
                        <div className="w-3.5 h-3.5 rounded-full bg-emerald-600 flex items-center justify-center text-white shrink-0 mt-0.5 shadow-2xs">
                          <Check className="w-2 h-2 stroke-[3.5]" />
                        </div>
                        <span>{b}</span>
                      </div>
                    ))}
                  </div>
                </div>
              </div>
            );
          })}
        </div>

      </div>
    </section>
  );
};
