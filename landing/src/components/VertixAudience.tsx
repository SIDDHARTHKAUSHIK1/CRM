import React, { useState } from 'react';
import {
  Building2,
  Briefcase,
  Layers,
  ShoppingBag,
  Check,
} from 'lucide-react';

export const VertixAudience: React.FC = () => {
  const [activeAudienceIndex, setActiveAudienceIndex] = useState(0);

  const AUDIENCES = [
    {
      id: 'developers',
      icon: Building2,
      video: '/videos/use-case-bg.mp4',
      badge: 'BUILDERS & DEVELOPERS',
      name: 'Real Estate Builders & Developers',
      tagline: 'Villas, Gated Townships, High-Rise Apartments & Plotted Projects',
      image:
        'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
      headline: 'Stop losing high-intent property buyers to delayed site visit scheduling.',
      description:
        'Manage incoming buyer enquiries from property portals, website forms, Meta Lead Ads, and WhatsApp in one centralized pipeline. Dispatch floor plan brochures quickly and track site visits seamlessly across all towers or plot phases.',
      challengesSolved: [
        'Organize villa, flat, and plot enquiries by project, phase & budget tier',
        'Send instant WhatsApp brochures with PDF floor plans & location pins',
        'Assign enquiries to sales executives via round-robin distribution',
        'Prevent sales staff from unauthorized buyer contact list exports',
      ],
      roiOutcome: 'Faster site visit scheduling & streamlined plot booking workflows',
    },
    {
      id: 'brokerages',
      icon: Briefcase,
      video: '/videos/brokerage-bg.mp4',
      badge: 'PROPERTY BROKERAGES',
      name: 'Real Estate Brokerages & Agencies',
      tagline: 'Luxury Residential, Resale Homes, 2/3/4 BHK Flats & Commercial Leasing',
      image:
        'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=1200&q=80',
      headline: 'Cut cost sheet turnaround time and keep buyer interest active.',
      description:
        'Track buyer preferences across location, BHK configuration, and budget. Share instant itemized cost sheets with construction-linked payment milestones and coordinate site visits without spreadsheet confusion.',
      challengesSolved: [
        'Centralize buyer leads from website forms, portals, WhatsApp & referrals',
        'One-click payment breakdowns with milestone schedules & GST',
        'Two-way email & WhatsApp communication history logged in CRM',
        'Visual forecasting of monthly brokerage deal pipeline',
      ],
      roiOutcome: 'Instant branded cost sheets & organized client visit management',
    },
    {
      id: 'channel-partners',
      icon: Layers,
      video: '/videos/channel-partner.mp4',
      badge: 'CP NETWORKS',
      name: 'Channel Partner (CP) Networks',
      tagline: 'CP Lead Registration, Sourcing Managers & Closing Teams',
      image:
        'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80',
      headline: 'Eliminate channel partner lead disputes with transparent lead tagging.',
      description:
        'Provide channel partners and sourcing managers with clear lead registration and protection windows. Automatically log client ownership, trigger site visit confirmations, and track token booking status transparently.',
      challengesSolved: [
        'Automatic CP lead tagging & duplicate lead prevention logic',
        'Broadcast new inventory releases & payment plans to CP networks',
        'Shared site visit status updates between CP and sales closing team',
        'Clear client ownership records during booking negotiations',
      ],
      roiOutcome: 'Zero lead duplication & transparent CP commission deal tracking',
    },
    {
      id: 'commercial-land',
      icon: ShoppingBag,
      video: '/videos/plot.mp4',
      badge: 'LAND & COMMERCIAL',
      name: 'Commercial Land & Plot Consultants',
      tagline: 'SCO Plots, Industrial Land, Retail Showrooms & Institutional Deals',
      image:
        'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80',
      headline: 'Strict investor confidentiality and dependable high-ticket deal forecasts.',
      description:
        'Manage high-value commercial and plotted land negotiations with institutional investors. Maintain role-based data security and generate accurate quarterly booking forecasts in ₹ Crores.',
      challengesSolved: [
        'Strict role-based isolation between sales executives & investor records',
        'Custom milestone schedules for commercial installment plans',
        'Complete audit trails of every quote revision, token receipt & status change',
        'Protected customer database and phone number masking controls',
      ],
      roiOutcome: 'Secure buyer data protection & structured milestone payment plans',
    },
  ];

  const activeAudience = AUDIENCES[activeAudienceIndex];

  return (
    <section id="audience" className="py-8 lg:py-12 bg-[#F4F3EE] border-b border-[#D8D5CA] relative">
      <div className="max-w-4xl mx-auto px-4 sm:px-6">
        {/* Section Header */}
        <div className="flex flex-col md:flex-row md:items-end justify-between gap-3 pb-4 border-b border-[#D8D5CA] mb-5">
          <div className="space-y-1.5 max-w-xl">
            <span className="text-[10px] font-bold tracking-[0.2em] text-[#0A0A0A] uppercase font-mono block">
              REAL ESTATE BUSINESS USE CASES
            </span>
            <h2 className="font-serif text-xl sm:text-2xl lg:text-3xl text-[#0A0A0A] font-bold leading-tight">
              Tailored for <span className="italic font-normal text-emerald-800">Builders, Brokers &amp; Sales Teams</span>
            </h2>
            <p className="text-xs sm:text-sm text-[#262522] font-normal leading-relaxed">
              Discover how Real Estate CRM is configured to eliminate follow-up friction for your specific real estate business model.
            </p>
          </div>

          <div className="font-mono text-[10px] text-[#3D3A34] uppercase tracking-wider font-extrabold bg-white px-2.5 py-1 rounded-full border border-[#CDC9BC] shadow-xs shrink-0 self-start md:self-end">
            <span>Select Segment</span>
          </div>
        </div>

        {/* Industry Switcher Buttons */}
        <div className="grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-2.5 mb-4">
          {AUDIENCES.map((aud, idx) => {
            const Icon = aud.icon;
            const isActive = idx === activeAudienceIndex;
            return (
              <button
                key={aud.id}
                onClick={() => setActiveAudienceIndex(idx)}
                className={`p-2.5 sm:p-3 rounded-xl text-left transition-all duration-200 cursor-pointer border flex flex-col justify-between space-y-1.5 hover-lift ${
                  isActive
                    ? 'bg-[#0A0A0A] text-white border-[#0A0A0A] shadow-md ring-2 ring-emerald-500/40'
                    : 'bg-white text-[#0A0A0A] border-[#CDC9BC] hover:border-[#0A0A0A] hover:bg-[#FAF9F5] shadow-xs'
                }`}
              >
                <div className="flex items-center justify-between">
                  <div
                    className={`w-6 h-6 rounded-md flex items-center justify-center ${
                      isActive
                        ? 'bg-white/20 text-white'
                        : 'bg-[#0A0A0A] text-white'
                    }`}
                  >
                    <Icon className="w-3.5 h-3.5" />
                  </div>
                  <span
                    className={`text-[8px] sm:text-[9px] font-mono uppercase px-1.5 py-0.5 rounded-full font-bold ${
                      isActive
                        ? 'bg-emerald-500/30 text-emerald-300 border border-emerald-400/40'
                        : 'bg-[#EFECE4] text-[#111111]'
                    }`}
                  >
                    {aud.badge}
                  </span>
                </div>
                <div>
                  <div className="font-serif text-xs sm:text-sm font-bold leading-snug">
                    {aud.name}
                  </div>
                  <div
                    className={`text-[10px] font-mono line-clamp-1 mt-0.5 font-medium ${
                      isActive ? 'text-neutral-300' : 'text-[#4A473F]'
                    }`}
                  >
                    {aud.tagline}
                  </div>
                </div>
              </button>
            );
          })}
        </div>

        {/* Active Industry Showcase Card - Compact & Balanced */}
        <div className="bg-white rounded-xl border border-[#CDC9BC] overflow-hidden shadow-md grid grid-cols-1 lg:grid-cols-12 items-stretch max-w-4xl mx-auto">
          {/* Left Column: Video when available vs Photo as fallback */}
          <div className="lg:col-span-5 h-[200px] sm:h-[240px] lg:h-auto min-h-[200px] lg:min-h-[260px] relative group overflow-hidden bg-[#0A0A0A]">
            {activeAudience.video ? (
              <video
                key={activeAudience.id}
                autoPlay
                loop
                muted
                playsInline
                className="w-full h-full object-cover scale-105 transition-transform duration-700"
                onError={(e) => {
                  const target = e.currentTarget as HTMLElement;
                  target.style.display = 'none';
                  const fallbackImg = target.parentElement?.querySelector('.fallback-img') as HTMLElement;
                  if (fallbackImg) fallbackImg.classList.remove('hidden');
                }}
              >
                <source src={activeAudience.video} type="video/mp4" />
              </video>
            ) : null}
            <img
              src={activeAudience.image}
              alt={activeAudience.name}
              referrerPolicy="no-referrer"
              className={`w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-90 ${
                activeAudience.video ? 'fallback-img hidden' : ''
              }`}
            />
            <div className="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent pointer-events-none"></div>

            <div className="absolute bottom-3 inset-x-3 text-white space-y-1 z-10">
              <div className="text-[9px] font-mono text-emerald-400 font-extrabold uppercase tracking-widest flex items-center gap-1">
                <span className="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                Key Advantage
              </div>
              <div className="font-serif text-sm sm:text-base font-bold text-white leading-snug">
                {activeAudience.roiOutcome}
              </div>
            </div>
          </div>

          {/* Right Column: Clean White Showcase */}
          <div className="lg:col-span-7 bg-white p-4 sm:p-5 lg:p-6 space-y-3 flex flex-col justify-center">
            <div className="space-y-1">
              <div className="text-[9px] font-mono text-emerald-800 uppercase tracking-widest font-extrabold bg-emerald-50 px-2 py-0.5 rounded-full inline-block border border-emerald-300">
                USE CASE &amp; SPECIALIZATION
              </div>
              <h3 className="font-serif text-base sm:text-lg lg:text-xl text-[#0A0A0A] font-bold leading-snug">
                {activeAudience.headline}
              </h3>
            </div>

            <p className="text-xs text-[#262522] font-normal leading-relaxed">
              {activeAudience.description}
            </p>

            {/* Challenges Solved */}
            <div className="space-y-1.5 pt-2.5 border-t border-[#EAE7DD]">
              <div className="text-[10px] font-mono uppercase tracking-wider text-[#0A0A0A] font-extrabold flex items-center gap-1.5">
                <span>Key Solutions Provided:</span>
              </div>
              <div className="space-y-1.5">
                {activeAudience.challengesSolved.map((chal, cIdx) => (
                  <div key={cIdx} className="flex items-start gap-2 text-xs text-[#0A0A0A] font-medium">
                    <div className="w-3.5 h-3.5 rounded-full bg-emerald-600 flex items-center justify-center text-white shrink-0 mt-0.5 shadow-xs">
                      <Check className="w-2 h-2 stroke-[3.5]" />
                    </div>
                    <span className="leading-tight">{chal}</span>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};
