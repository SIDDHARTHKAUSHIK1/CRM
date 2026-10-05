import React, { useState } from 'react';
import { RealEstateMotionBackground } from './RealEstateMotionBackground';
import {
  ArrowRight,
  ChevronDown,
  CheckCircle2,
  ShieldCheck,
  Zap,
} from 'lucide-react';

interface RealEstateCtaFaqProps {
  onStartDemo: () => void;
}

export const RealEstateCtaFaq: React.FC<RealEstateCtaFaqProps> = ({ onStartDemo }) => {
  const [openIndex, setOpenIndex] = useState<number | null>(0);

  const FAQS = [
    {
      question: 'Do I need technical skills or coding knowledge?',
      answer:
        'Not at all. If you know how to use email, WhatsApp, and social media, you and your sales team can master this CRM in less than 10 minutes with zero IT complexity.',
    },
    {
      question: 'Can I import my existing contacts from Excel or Google Sheets?',
      answer:
        'Yes! You can upload all your existing leads, customer contacts, property inventory, and product lists in bulk with a single CSV / Excel file upload in under 60 seconds.',
    },
    {
      question: 'Can employees only access leads assigned to them?',
      answer:
        'Absolutely. Role-based permissions allow admins to restrict sales executives so they only see their assigned deals with masked buyer phone numbers, while managers and founders have complete visibility and audit logs.',
    },
    {
      question: 'How does Real Estate CRM capture leads from 99acres and Magicbricks?',
      answer:
        'The CRM connects directly via instant webhooks and API integrations to 99acres, Magicbricks, Housing.com, Meta Lead Ads, and your landing page. Inbound enquiries are auto-captured, deduplicated, and routed to sales reps in under 2 seconds.',
    },
    {
      question: 'Can sales executives send WhatsApp brochures and cost sheets directly?',
      answer:
        'Yes. Through official WhatsApp Business integration, sales reps can dispatch pre-approved PDF floor plans, project brochures, and branded cost sheets in 1 click. All buyer messages and two-way conversations are permanently logged in the CRM.',
    },
    {
      question: 'How quickly can our sales team get started?',
      answer:
        'Setup takes less than 24 hours (or under 5 minutes for direct cloud self-setup). Our onboarding team pre-loads your property inventory, connects your lead sources, and trains your sales executives with zero hassle.',
    },
  ];

  const toggleFaq = (idx: number) => {
    setOpenIndex(openIndex === idx ? null : idx);
  };

  return (
    <section id="faq" className="isolate py-8 sm:py-12 lg:py-14 bg-[#F8FAFC]/85 backdrop-blur-xs border-b border-[#E2E8F0]/80 relative overflow-hidden">
      <RealEstateMotionBackground variant="properties" />
      <div className="max-w-[1400px] 2xl:max-w-[1550px] mx-auto px-4 sm:px-6 lg:px-8 space-y-6 sm:space-y-8 relative z-10">
        
        {/* Streamlined Closing CTA Banner */}
        <div className="max-w-4xl mx-auto bg-gradient-to-br from-[#0B1A30] via-[#0D2345] to-[#1864E8] rounded-2xl sm:rounded-3xl p-5 sm:p-8 text-white shadow-xl relative overflow-hidden">
          {/* Ambient Glows */}
          <div className="absolute top-0 right-0 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl pointer-events-none" />
          <div className="absolute bottom-0 left-0 w-60 h-60 bg-indigo-500/15 rounded-full blur-3xl pointer-events-none" />

          <div className="relative z-10 space-y-4">
            <div className="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-300 text-[10px] font-mono font-bold tracking-widest uppercase">
              <Zap className="w-3 h-3 text-amber-400" />
              <span>TRANSFORM YOUR SALES PIPELINE</span>
            </div>

            <h2 className="text-xl sm:text-2xl lg:text-3xl font-extrabold text-white tracking-tight leading-tight">
              Start Closing More Deals with Less Effort Today.
            </h2>

            <p className="text-xs sm:text-sm text-slate-200 leading-relaxed max-w-xl font-normal">
              Join forward-thinking real estate businesses, builders, and sales teams using our Modern CRM to eliminate busywork, follow up in 60s, and scale revenue.
            </p>

            {/* Dual CTA Buttons */}
            <div className="flex flex-wrap items-center gap-3 pt-1">
              <button
                onClick={onStartDemo}
                className="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-lg bg-[#1864E8] hover:bg-blue-600 text-white font-bold text-xs uppercase tracking-wider transition-all cursor-pointer shadow-md shadow-blue-500/30 hover:scale-[1.02] active:scale-98"
              >
                <span>Schedule 15-Minute Demo</span>
                <ArrowRight className="w-3.5 h-3.5" />
              </button>

              <button
                onClick={onStartDemo}
                className="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg bg-white/10 hover:bg-white/20 text-white font-bold text-xs uppercase tracking-wider border border-white/20 transition-all cursor-pointer backdrop-blur-md"
              >
                <span>Try Free for 14 Days</span>
              </button>
            </div>

            {/* Trust Badges */}
            <div className="pt-3 flex flex-wrap items-center gap-4 text-[11px] text-blue-200/90 font-medium border-t border-white/10">
              <div className="flex items-center gap-1">
                <CheckCircle2 className="w-3.5 h-3.5 text-emerald-400" />
                <span>No credit card required</span>
              </div>
              <div className="flex items-center gap-1">
                <CheckCircle2 className="w-3.5 h-3.5 text-emerald-400" />
                <span>Setup in under 5 minutes</span>
              </div>
              <div className="flex items-center gap-1">
                <ShieldCheck className="w-3.5 h-3.5 text-emerald-400" />
                <span>100% Private &amp; Secure</span>
              </div>
            </div>
          </div>
        </div>

        {/* Compact FAQ Accordion Section */}
        <div className="max-w-3xl mx-auto space-y-4">
          <div className="text-center space-y-1">
            <span className="text-[10px] font-mono font-extrabold uppercase tracking-[0.2em] text-[#1864E8]">
              OVERCOMING OBJECTIONS · FAQ
            </span>
            <h3 className="text-xl sm:text-2xl font-extrabold text-[#0B1A30] tracking-tight">
              Frequently Asked Questions
            </h3>
            <p className="text-[11px] sm:text-xs text-[#64748B]">
              Everything you need to know about getting started and migrating your data.
            </p>
          </div>

          <div className="space-y-1.5 sm:space-y-2">
            {FAQS.map((faq, idx) => {
              const isOpen = openIndex === idx;
              return (
                <div
                  key={idx}
                  className="bg-white rounded-xl border border-[#E2E8F0] overflow-hidden transition-all shadow-2xs hover:border-blue-200"
                >
                  <button
                    onClick={() => toggleFaq(idx)}
                    className="w-full p-3 sm:p-3.5 text-left flex items-center justify-between gap-3 cursor-pointer hover:bg-[#F8FAFC] transition-colors"
                  >
                    <span className="text-xs sm:text-[13px] font-bold text-[#0B1A30] leading-snug">
                      {faq.question}
                    </span>
                    <ChevronDown
                      className={`w-3.5 h-3.5 text-[#64748B] shrink-0 transition-transform duration-200 ${
                        isOpen ? 'rotate-180 text-[#1864E8]' : ''
                      }`}
                    />
                  </button>

                  {isOpen && (
                    <div className="px-3 pb-3 sm:px-3.5 sm:pb-3.5 text-[11.5px] sm:text-xs text-[#475569] leading-relaxed border-t border-[#F1F5F9] pt-2 animate-in fade-in">
                      {faq.answer}
                    </div>
                  )}
                </div>
              );
            })}
          </div>
        </div>

      </div>
    </section>
  );
};
