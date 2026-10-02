import React, { useState } from 'react';
import {
  TrendingUp,
  Clock,
  ShieldCheck,
  Zap,
  Calculator,
  ArrowRight,
  Sparkles,
} from 'lucide-react';

interface VertixResultsProps {
  onStartDemo: () => void;
}

export const VertixResults: React.FC<VertixResultsProps> = ({ onStartDemo }) => {
  const [monthlyLeads, setMonthlyLeads] = useState<number>(80);
  const [dealSize, setDealSize] = useState<number>(8500000); // ₹85 Lakh average property booking value

  const estimatedLostLeads = Math.round(monthlyLeads * 0.35); // Estimated enquiries dropped due to slow follow-up
  const recoveredDeals = Math.max(1, Math.round(estimatedLostLeads * 0.15)); // Estimated recovered deals
  const recoveredMonthlyRevenue = recoveredDeals * dealSize;
  const annualRevenueRecovered = recoveredMonthlyRevenue * 12;

  const formatPrice = (val: number) => {
    if (val >= 10000000) {
      const cr = val / 10000000;
      return `₹${cr % 1 === 0 ? cr.toFixed(0) : cr.toFixed(2)} Cr`;
    }
    if (val >= 100000) {
      const lakh = val / 100000;
      return `₹${lakh % 1 === 0 ? lakh.toFixed(0) : lakh.toFixed(1)} Lakh`;
    }
    return `₹${val.toLocaleString('en-IN')}`;
  };

  return (
    <section id="results" className="py-8 lg:py-12 bg-[#0B0B0B] text-white relative overflow-hidden border-b border-[#262626]">
      {/* Background Subtle Architectural Photo */}
      <div className="absolute inset-0 z-0 opacity-15 pointer-events-none">
        <img
          src="https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=2000&q=80"
          alt="Clean data and business pipeline"
          referrerPolicy="no-referrer"
          className="w-full h-full object-cover grayscale"
        />
        <div className="absolute inset-0 bg-[#0B0B0B]/90"></div>
      </div>

      <div className="max-w-4xl mx-auto px-4 sm:px-6 relative z-10 space-y-4 sm:space-y-5">
        {/* Compact Header */}
        <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-2 pb-3 border-b border-white/10">
          <div className="space-y-1 max-w-xl">
            <h2 className="font-serif text-xl sm:text-2xl text-white font-bold leading-tight">
              Real Impact That <span className="italic font-normal text-emerald-300">Accelerates Property Deals</span>
            </h2>
          </div>

          <div className="font-mono text-[9px] text-neutral-400 uppercase tracking-wider font-bold bg-white/5 px-2.5 py-0.5 rounded-full border border-white/15 shrink-0 self-start sm:self-auto">
            <span>Sales Benchmarks</span>
          </div>
        </div>

        {/* 4 Ultra-Compact KPI Cards */}
        <div className="grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-2.5">
          {[
            {
              icon: TrendingUp,
              badge: 'Follow-Up Speed',
              value: 'Instant',
              description: 'Auto-capture across WhatsApp & ads.',
            },
            {
              icon: Clock,
              badge: 'Deal Progression',
              value: 'Faster',
              description: 'Lead to visit & token booking.',
            },
            {
              icon: ShieldCheck,
              badge: 'Buyer Database',
              value: 'Secure',
              description: 'Role-based access & contact vault.',
            },
            {
              icon: Zap,
              badge: 'Cost Sheet Speed',
              value: '30s',
              description: 'Branded milestone PDF to WhatsApp.',
            },
          ].map((stat, sIdx) => {
            const Icon = stat.icon;
            return (
              <div
                key={sIdx}
                className="p-2.5 sm:p-3 rounded-lg bg-black/60 border border-white/10 hover:border-emerald-500/40 transition-all duration-200 flex flex-col justify-between space-y-1 shadow-2xs"
              >
                <div className="flex items-center justify-between">
                  <div className="w-5 h-5 rounded bg-emerald-950/80 border border-emerald-500/30 flex items-center justify-center text-emerald-400">
                    <Icon className="w-2.5 h-2.5 stroke-[2.5]" />
                  </div>
                  <span className="font-mono text-[8.5px] uppercase tracking-wider text-neutral-400 font-bold">
                    {stat.badge}
                  </span>
                </div>

                <div className="font-serif text-lg sm:text-xl font-bold text-white tracking-tight">
                  {stat.value}
                </div>

                <p className="text-[10px] text-neutral-400 font-light truncate leading-tight">
                  {stat.description}
                </p>
              </div>
            );
          })}
        </div>

        {/* Slim, Compact Revenue Calculator Box */}
        <div className="p-3.5 sm:p-5 rounded-xl bg-[#0F0F0F] border border-white/15 shadow-lg">
          <div className="grid grid-cols-1 lg:grid-cols-12 gap-4 lg:gap-6 items-center">
            {/* Left Inputs */}
            <div className="lg:col-span-6 space-y-2.5">
              <div className="space-y-0.5">
                <div className="inline-flex items-center gap-1 text-[9px] font-mono font-bold text-emerald-400 uppercase bg-emerald-950/70 px-2 py-0.5 rounded-full border border-emerald-500/30">
                  <Calculator className="w-2.5 h-2.5" />
                  <span>Revenue Calculator</span>
                </div>
                <h3 className="font-serif text-lg sm:text-xl text-white font-bold leading-snug">
                  See How Much More You Could Sell
                </h3>
              </div>

              {/* Slider 1: Monthly Leads */}
              <div className="space-y-1 p-2 sm:p-2.5 rounded-lg bg-white/[0.03] border border-white/10">
                <div className="flex justify-between items-baseline">
                  <span className="text-xs text-white font-medium">Monthly Leads</span>
                  <div className="flex items-baseline gap-1">
                    <span className="text-emerald-400 font-serif font-bold text-base">{monthlyLeads}</span>
                    <span className="text-neutral-500 text-[9px] font-mono">leads/mo</span>
                  </div>
                </div>
                <input
                  type="range"
                  min="20"
                  max="300"
                  step="10"
                  value={monthlyLeads}
                  onChange={(e) => setMonthlyLeads(Number(e.target.value))}
                  className="w-full accent-emerald-400 cursor-pointer h-1 bg-neutral-800 rounded"
                />
                <div className="flex justify-between text-[8.5px] text-neutral-500 font-mono">
                  <span>20 leads</span>
                  <span>150 leads</span>
                  <span>300+ leads</span>
                </div>
              </div>

              {/* Slider 2: Average Property Value */}
              <div className="space-y-1 p-2 sm:p-2.5 rounded-lg bg-white/[0.03] border border-white/10">
                <div className="flex justify-between items-baseline">
                  <span className="text-xs text-white font-medium">Average Property Value</span>
                  <div className="flex items-baseline gap-1">
                    <span className="text-emerald-400 font-serif font-bold text-base">{formatPrice(dealSize)}</span>
                  </div>
                </div>
                <input
                  type="range"
                  min="2000000"
                  max="50000000"
                  step="500000"
                  value={dealSize}
                  onChange={(e) => setDealSize(Number(e.target.value))}
                  className="w-full accent-emerald-400 cursor-pointer h-1 bg-neutral-800 rounded"
                />
                <div className="flex justify-between text-[8.5px] text-neutral-500 font-mono">
                  <span>₹20L (Plots)</span>
                  <span>₹1.5Cr (Flats)</span>
                  <span>₹5Cr+ (Villas)</span>
                </div>
              </div>
            </div>

            {/* Right Output Box */}
            <div className="lg:col-span-6 bg-black/85 border border-emerald-500/35 rounded-xl p-3.5 sm:p-4 space-y-3 shadow-md relative overflow-hidden">
              <div className="space-y-1">
                <div className="text-[9.5px] font-mono text-emerald-400 uppercase tracking-wider font-bold flex items-center gap-1">
                  <Sparkles className="w-2.5 h-2.5 text-emerald-400" />
                  <span>Estimated Potential Opportunity</span>
                </div>
                <div className="flex items-baseline gap-1.5 flex-wrap">
                  <span className="font-serif text-2xl sm:text-3xl lg:text-4xl text-white font-bold tracking-tight">
                    {formatPrice(annualRevenueRecovered)}
                  </span>
                  <span className="text-[11px] font-mono text-emerald-300 font-medium">/ year</span>
                </div>
                <p className="text-[10px] text-neutral-400 font-light leading-tight">
                  Estimated extra value unlocked with prompt follow-ups and automated cost sheets.
                </p>
              </div>

              {/* 2 Simple Metric Cards */}
              <div className="grid grid-cols-2 gap-2 pt-2 border-t border-white/10 text-left">
                <div className="p-2 rounded-md bg-white/[0.04] border border-white/10 space-y-0.5">
                  <span className="text-neutral-400 block text-[9px] font-medium">Missed Follow-ups</span>
                  <span className="text-amber-300 font-serif font-bold text-sm block">~{estimatedLostLeads} leads/mo</span>
                </div>

                <div className="p-2 rounded-md bg-white/[0.04] border border-white/10 space-y-0.5">
                  <span className="text-neutral-400 block text-[9px] font-medium">Extra Bookings</span>
                  <span className="text-emerald-400 font-serif font-bold text-sm block">+{recoveredDeals} {recoveredDeals === 1 ? 'deal' : 'deals'}/mo</span>
                </div>
              </div>

              <button
                onClick={onStartDemo}
                className="w-full py-2 px-3 rounded-full bg-white text-black hover:bg-neutral-100 transition-all font-bold uppercase tracking-wider text-[10px] flex items-center justify-center gap-1.5 cursor-pointer shadow-sm hover:scale-[1.01]"
              >
                <span>Request a Product Demo</span>
                <ArrowRight className="w-2.5 h-2.5" />
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};
