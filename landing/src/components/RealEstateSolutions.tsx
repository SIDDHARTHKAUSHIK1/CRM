import React, { useState } from 'react';
import { RealEstateMotionBackground } from './RealEstateMotionBackground';
import {
  Home,
  Building,
  Briefcase,
  Layers,
  ArrowRight,
  CheckCircle2,
  Sparkles,
  Users,
  MessageCircle,
  LayoutDashboard,
  ShieldCheck,
  Zap,
} from 'lucide-react';

interface RealEstateSolutionsProps {
  onSelectSolution: (solutionId: string) => void;
}

export const RealEstateSolutions: React.FC<RealEstateSolutionsProps> = ({
  onSelectSolution,
}) => {
  const [hoveredCard, setHoveredCard] = useState<string | null>(null);
  const SOLUTIONS = [
    {
      id: 'developers',
      icon: Home,
      title: 'Builders & Developers',
      description: 'Villas, gated townships, high-rises, and plotted projects with automated site visit scheduling.',
      image: '/images/properties/property-1.jpg',
      keyAdvantage: 'Instant Site Visit Scheduling',
      highlights: [
        'Organize villa, flat & plot inventory by phase',
        'Round-robin lead routing to sales reps',
      ],
    },
    {
      id: 'brokerages',
      icon: Building,
      title: 'Property Brokerages',
      description: 'Luxury residential, 2/3/4 BHK flats, resale homes, and visual client deal management.',
      image: '/images/properties/property-2.jpg',
      keyAdvantage: '1-Click WhatsApp Cost Sheets',
      highlights: [
        'Itemized payment milestone breakdowns',
        'Logged WhatsApp & 2-way email history',
      ],
    },
    {
      id: 'commercial',
      icon: Briefcase,
      title: 'Commercial & SCO Plots',
      description: 'SCO plots, industrial land, retail showrooms, and institutional negotiations in ₹ Crores.',
      image: '/images/properties/property-3.jpg',
      keyAdvantage: 'Protected High-Value Vault',
      highlights: [
        'Strict investor confidentiality & masking',
        'Quarterly revenue forecasting in ₹ Crores',
      ],
    },
    {
      id: 'channel-partners',
      icon: Layers,
      title: 'Channel Partner (CP) Networks',
      description: 'CP lead registration, duplicate lead tagging, and transparent token commission tracking.',
      image: '/images/properties/property-4.jpg',
      keyAdvantage: 'Zero Lead Duplication',
      highlights: [
        'Automated CP lead protection windows',
        'Shared site visit status with closing teams',
      ],
    },
  ];

  const WHY_CHOOSE_US = [
    {
      icon: Users,
      title: 'Every lead, in one place',
      description: 'Keep new inquiries and customer details together, so your team knows who to contact next.',
      detail: 'Less searching. More conversations.',
    },
    {
      icon: MessageCircle,
      title: 'Follow up with confidence',
      description: 'Manage WhatsApp, email, and reminders from one workspace. Keep every conversation moving.',
      detail: 'Stay connected at every step.',
    },
    {
      icon: LayoutDashboard,
      title: 'See every deal clearly',
      description: 'Track buyers from first inquiry to site visit to booking on a simple visual sales board.',
      detail: 'Know exactly what happens next.',
    },
    {
      icon: ShieldCheck,
      title: 'Keep your team in control',
      description: 'Assign leads to the right people and use access permissions to protect customer information.',
      detail: 'A shared workspace. Clear ownership.',
    },
  ];

  return (
    <section id="solutions" className="isolate py-14 sm:py-20 bg-[#F8FAFC]/85 backdrop-blur-xs border-b border-slate-200/80 relative overflow-hidden space-y-16 sm:space-y-24">
      <RealEstateMotionBackground variant="workspace" />
      
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 sm:space-y-14 relative z-10">
        
        {/* Section Header */}
        <div className="text-center space-y-2 max-w-2xl mx-auto">
          <span className="text-[11px] font-extrabold tracking-[0.2em] text-[#1864E8] uppercase">
            REAL ESTATE SOLUTIONS
          </span>
          
          <h2 className="text-3xl sm:text-4xl font-black text-[#0B1A30] tracking-tight">
            Built for Every Need
          </h2>
          
          <p className="text-xs sm:text-sm text-[#64748B] font-medium leading-relaxed max-w-lg mx-auto">
            Tailored workflows engineered specifically for your exact real estate business model.
          </p>
        </div>

        {/* 4-Column Card Grid */}
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch">
          {SOLUTIONS.map((sol) => {
            const Icon = sol.icon;
            const isHovered = hoveredCard === sol.id;

            return (
              <div
                key={sol.id}
                onMouseEnter={() => setHoveredCard(sol.id)}
                onMouseLeave={() => setHoveredCard(null)}
                className={`bg-white rounded-2xl border transition-all duration-300 flex flex-col justify-between group cursor-pointer relative overflow-hidden ${
                  isHovered
                    ? 'border-[#1864E8] shadow-2xl shadow-blue-500/10 -translate-y-1.5'
                    : 'border-slate-200 shadow-xs hover:shadow-lg'
                }`}
              >
                <div>
                  {/* Top Image Container - Clean & Crisp Display (No Text/Logos On Image) */}
                  <div className="relative aspect-[16/11] overflow-hidden bg-slate-900 rounded-t-2xl">
                    <img
                      src={sol.image}
                      alt={sol.title}
                      className="w-full h-full object-cover group-hover:scale-106 transition-transform duration-500 ease-out filter contrast-[1.03] brightness-[1.02]"
                      loading="lazy"
                    />
                  </div>

                  {/* Card Content */}
                  <div className="p-5 space-y-3">
                    <div className="space-y-1.5">
                      <div className="flex items-center gap-2.5">
                        <div className="w-7 h-7 rounded-lg bg-blue-50 text-[#1864E8] flex items-center justify-center shrink-0 border border-blue-100 shadow-2xs">
                          <Icon className="w-3.5 h-3.5 stroke-[2.2]" />
                        </div>
                        <h3 className="text-base sm:text-lg font-bold text-[#0B1A30] group-hover:text-[#1864E8] transition-colors leading-snug">
                          {sol.title}
                        </h3>
                      </div>
                      
                      <div className="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-blue-50 border border-blue-100 text-[#1864E8] text-[10px] font-mono font-bold">
                        <Zap className="w-2.5 h-2.5 fill-current" />
                        <span>{sol.keyAdvantage}</span>
                      </div>
                    </div>

                    <p className="text-xs text-[#64748B] leading-relaxed">
                      {sol.description}
                    </p>

                    <div className="space-y-1.5 pt-2 border-t border-slate-100">
                      {sol.highlights.map((h, hIdx) => (
                        <div key={hIdx} className="flex items-start gap-1.5 text-[11px] text-[#334155] font-medium leading-tight">
                          <CheckCircle2 className="w-3.5 h-3.5 text-[#1864E8] shrink-0 mt-0.5" />
                          <span>{h}</span>
                        </div>
                      ))}
                    </div>
                  </div>
                </div>

                {/* Bottom Action Button */}
                <div className="p-5 pt-0">
                  <button
                    onClick={() => onSelectSolution(sol.id)}
                    className="w-full py-2 px-3 rounded-lg bg-slate-50 group-hover:bg-[#0B1A30] group-hover:text-white text-[#0B1A30] text-xs font-bold transition-all duration-300 flex items-center justify-between shadow-2xs group/btn cursor-pointer"
                  >
                    <span>Learn More</span>
                    <ArrowRight className="w-3.5 h-3.5 group-hover/btn:translate-x-1 transition-transform" />
                  </button>
                </div>
              </div>
            );
          })}
        </div>

      </div>

      <div id="why-us" className="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" aria-labelledby="why-us-heading">
        <div className="flex flex-col justify-between gap-6 md:flex-row md:items-end">
          <div className="max-w-2xl">
            <span className="mb-5 inline-flex items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-3.5 py-2 text-[11px] font-bold uppercase tracking-[0.16em] text-[#1864E8]">
              <Sparkles className="h-3.5 w-3.5" aria-hidden="true" /> Why choose us
            </span>
            <h2 id="why-us-heading" className="text-3xl font-extrabold leading-[1.15] tracking-tight text-[#0B1A30] sm:text-4xl lg:text-5xl">
              Real estate sales.<br /><span className="text-[#1864E8]">A whole lot simpler.</span>
            </h2>
          </div>
          <p className="max-w-sm text-base leading-relaxed text-slate-600">
            Spend less time juggling tools and more time helping buyers. One CRM to keep your leads, conversations, and team on track.
          </p>
        </div>

        <div className="mt-9 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:mt-12 lg:grid-cols-4">
          {WHY_CHOOSE_US.map((item, idx) => {
            const Icon = item.icon;
            return (
              <article key={item.title} className="group flex flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-colors hover:border-blue-300 sm:p-7">
                <div className="mb-7 flex items-center justify-between">
                  <div className="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-[#1864E8]">
                    <Icon className="h-6 w-6" strokeWidth={1.8} aria-hidden="true" />
                  </div>
                  <span className="text-xs font-semibold tracking-wider text-slate-400" aria-hidden="true">0{idx + 1}</span>
                </div>
                <h3 className="mb-3 text-lg font-bold leading-snug tracking-tight text-[#0B1A30]">{item.title}</h3>
                <p className="mb-6 text-sm leading-7 text-slate-600">{item.description}</p>
                <div className="mt-auto flex items-start gap-2 border-t border-slate-100 pt-4 text-xs font-semibold leading-relaxed text-[#1864E8]">
                  <CheckCircle2 className="mt-0.5 h-4 w-4 shrink-0" aria-hidden="true" />
                  <span>{item.detail}</span>
                </div>
              </article>
            );
          })}
        </div>

        <div className="relative mt-6 overflow-hidden rounded-3xl bg-[#0B1A30] p-6 sm:p-9 lg:mt-8 lg:p-10">
          <div className="pointer-events-none absolute -right-16 -top-28 h-80 w-80 rounded-full bg-blue-500/15 blur-3xl" aria-hidden="true" />
          <div className="relative flex flex-col justify-between gap-7 lg:flex-row lg:items-center">
            <div className="max-w-xl">
              <span className="mb-3 block text-xs font-bold uppercase tracking-[0.16em] text-blue-300">Less busywork. More possibilities.</span>
              <h3 className="text-2xl font-bold leading-tight tracking-tight text-white sm:text-3xl">Your next deal starts with a clearer day.</h3>
              <p className="mt-3 text-sm leading-7 text-slate-300">See how your team can manage leads, plan follow-ups, and move deals forward in one simple workspace.</p>
            </div>
            <div className="flex shrink-0 flex-col gap-3 lg:items-center">
              <button type="button" onClick={() => onSelectSolution('demo')} className="inline-flex min-h-12 w-full items-center justify-center gap-3 rounded-xl bg-[#1864E8] px-6 py-3.5 text-sm font-bold text-white transition-colors hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-blue-300 sm:w-auto">
                Book my free demo <ArrowRight className="h-4 w-4" aria-hidden="true" />
              </button>
              <span className="flex items-center justify-center gap-2 text-xs text-slate-300"><CheckCircle2 className="h-3.5 w-3.5 text-blue-300" aria-hidden="true" />15 minutes ? Personalized to your team</span>
            </div>
          </div>
        </div>
      </div>

    </section>
  );
};
