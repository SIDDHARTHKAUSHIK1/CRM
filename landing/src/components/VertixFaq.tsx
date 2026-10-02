import React, { useState } from 'react';
import { ChevronDown, HelpCircle, ArrowRight, MessageSquare } from 'lucide-react';

interface VertixFaqProps {
  onStartDemo: () => void;
}

export const VertixFaq: React.FC<VertixFaqProps> = ({ onStartDemo }) => {
  const [openIndex, setOpenIndex] = useState<number | null>(0);

  const FAQS = [
    {
      question: 'How do we import existing leads from Excel, CSV, or portals?',
      answer:
        'Upload your Excel/CSV in minutes. The smart column mapper automatically organizes buyer contacts, phone numbers, property types (plots, flats, villas), and budget tiers into your pipeline with zero data loss.',
    },
    {
      question: 'Do our property sales executives need technical training?',
      answer:
        'No technical background needed. The visual interface feels like everyday messaging and drag-and-drop boards. Sales executives and site managers start managing daily follow-ups on day one.',
    },
    {
      question: 'How does WhatsApp and two-way email sync work?',
      answer:
        'Send brochures, layout plans, and cost sheets directly from the CRM. All customer replies sync automatically to the buyer’s deal timeline, giving managers complete communication visibility.',
    },
    {
      question: 'What happens to buyer data when a sales executive leaves?',
      answer:
        'Your buyer database stays protected in your company vault. With masked phone numbers and role permissions, revoke access in 1-click while all deal history and site notes remain safely with your firm.',
    },
    {
      question: 'Can sales executives use the CRM on mobile during site visits?',
      answer:
        'Yes. The CRM is fully mobile-responsive on iOS and Android. Sales reps can log site visit notes, send WhatsApp cost sheets, and update deal stages directly from the property site.',
    },
    {
      question: 'How are branded PDF cost sheets generated in seconds?',
      answer:
        'Select units from your pre-loaded inventory catalog, apply construction-linked or down-payment milestones, and generate a clean, branded PDF cost sheet dispatched straight to WhatsApp.',
    },
  ];

  const toggleFaq = (idx: number) => {
    setOpenIndex(openIndex === idx ? null : idx);
  };

  return (
    <section id="faq" className="py-8 lg:py-12 bg-[#F4F3EE] border-b border-[#D8D5CA] relative">
      <div className="max-w-5xl mx-auto px-4 sm:px-6 space-y-4 sm:space-y-5">
        {/* Compact Header */}
        <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-2 pb-3 border-b border-[#D8D5CA]">
          <div className="space-y-1 max-w-xl">
            <div className="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#0A0A0A] text-white text-[9px] font-mono tracking-widest uppercase font-bold shadow-2xs">
              <HelpCircle className="w-3 h-3 text-emerald-400" />
              <span>FREQUENTLY ASKED QUESTIONS</span>
            </div>
            <h2 className="font-serif text-xl sm:text-2xl text-[#0A0A0A] font-bold leading-tight">
              Everything Real Estate Leaders <span className="italic font-normal text-emerald-800">Need to Know</span>
            </h2>
          </div>
        </div>

        {/* 2-Column Compact FAQ Grid — Fits within a Single Screen Frame */}
        <div className="grid grid-cols-1 md:grid-cols-2 gap-2.5 sm:gap-3 items-start">
          {/* Left Column (Items 0, 1, 2) */}
          <div className="space-y-2.5">
            {FAQS.slice(0, 3).map((faq, idx) => {
              const actualIdx = idx;
              const isOpen = openIndex === actualIdx;
              return (
                <div
                  key={actualIdx}
                  className="rounded-lg bg-white border border-[#CDC9BC] overflow-hidden transition-all shadow-2xs"
                >
                  <button
                    onClick={() => toggleFaq(actualIdx)}
                    className="w-full p-2.5 sm:p-3 text-left flex items-center justify-between gap-2 cursor-pointer hover:bg-[#FAF9F5] transition-colors"
                  >
                    <span className="font-serif text-xs sm:text-sm text-[#0A0A0A] font-bold leading-snug">
                      {faq.question}
                    </span>
                    <div
                      className={`w-5 h-5 rounded-full flex items-center justify-center text-white shrink-0 transition-transform duration-200 ${
                        isOpen ? 'rotate-180 bg-emerald-700' : 'bg-[#0A0A0A]'
                      }`}
                    >
                      <ChevronDown className="w-3 h-3 stroke-[2.5]" />
                    </div>
                  </button>

                  {isOpen && (
                    <div className="px-3 pb-3 pt-0.5 text-[11px] text-[#3D3A34] font-normal leading-relaxed border-t border-[#EAE7DD] bg-[#FAF9F5]/60 animate-in fade-in duration-150">
                      <p>{faq.answer}</p>
                    </div>
                  )}
                </div>
              );
            })}
          </div>

          {/* Right Column (Items 3, 4, 5) */}
          <div className="space-y-2.5">
            {FAQS.slice(3, 6).map((faq, idx) => {
              const actualIdx = idx + 3;
              const isOpen = openIndex === actualIdx;
              return (
                <div
                  key={actualIdx}
                  className="rounded-lg bg-white border border-[#CDC9BC] overflow-hidden transition-all shadow-2xs"
                >
                  <button
                    onClick={() => toggleFaq(actualIdx)}
                    className="w-full p-2.5 sm:p-3 text-left flex items-center justify-between gap-2 cursor-pointer hover:bg-[#FAF9F5] transition-colors"
                  >
                    <span className="font-serif text-xs sm:text-sm text-[#0A0A0A] font-bold leading-snug">
                      {faq.question}
                    </span>
                    <div
                      className={`w-5 h-5 rounded-full flex items-center justify-center text-white shrink-0 transition-transform duration-200 ${
                        isOpen ? 'rotate-180 bg-emerald-700' : 'bg-[#0A0A0A]'
                      }`}
                    >
                      <ChevronDown className="w-3 h-3 stroke-[2.5]" />
                    </div>
                  </button>

                  {isOpen && (
                    <div className="px-3 pb-3 pt-0.5 text-[11px] text-[#3D3A34] font-normal leading-relaxed border-t border-[#EAE7DD] bg-[#FAF9F5]/60 animate-in fade-in duration-150">
                      <p>{faq.answer}</p>
                    </div>
                  )}
                </div>
              );
            })}
          </div>
        </div>

        {/* Slim Single-Line Help Banner */}
        <div className="p-2.5 sm:p-3 rounded-lg bg-white border border-[#CDC9BC] flex flex-col sm:flex-row items-center justify-between gap-2 shadow-2xs text-center sm:text-left">
          <div className="text-xs text-[#262522] font-medium">
            Have a question about your specific property sales setup?
          </div>

          <button
            onClick={onStartDemo}
            className="inline-flex items-center gap-1.5 px-3.5 py-1 text-[10px] font-bold uppercase tracking-wider text-white bg-[#0A0A0A] hover:bg-[#262626] transition-all shrink-0 cursor-pointer rounded-full shadow-2xs hover:scale-[1.02]"
          >
            <span>Ask in a 15-Min Demo</span>
            <ArrowRight className="w-3 h-3" />
          </button>
        </div>
      </div>
    </section>
  );
};
