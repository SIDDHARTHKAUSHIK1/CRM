import React, { useState } from 'react';
import { ChevronLeft, ChevronRight } from 'lucide-react';

const USE_CASE_SETS = [
  {
    left: {
      quote:
        'We cut our buyer cost sheet turnaround from hours to under a minute. Enquiries from portals and WhatsApp get assigned automatically, and our sales team never misses a site visit follow-up.',
      author: 'Rajesh Singhania',
      role: 'MD, Singhania Infra · Gurugram',
    },
    clientPhoto:
      'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80',
    right: {
      quote:
        'A great combination of visual simplicity and strict data security. Our buyer database stays protected with role-based access, and our management team has clear visibility over site visits.',
      author: 'Ananya Deshmukh',
      role: 'Principal Partner, Apex Prime Realty · Mumbai',
    },
  },
  {
    left: {
      quote:
        'Having all customer communication history in one place across WhatsApp and email makes handoffs seamless. Our sales executives know buyer preferences before every site walkthrough.',
      author: 'Vikramaditya Reddy',
      role: 'Director of Sales, Sovereign Plots · Bengaluru',
    },
    clientPhoto:
      'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=400&q=80',
    right: {
      quote:
        'Our 18 property consultants mastered the visual pipeline on day one with zero IT training. No bloated menus—just clear daily site visits, instant PDF cost sheets, and faster deal tracking.',
      author: 'Rohit Agarwal',
      role: 'Founder, Metro Commercial · Noida',
    },
  },
];

export const VertixTestimonials: React.FC = () => {
  const [index, setIndex] = useState(0);
  const current = USE_CASE_SETS[index];

  const handlePrev = () => {
    setIndex((prev) => (prev === 0 ? USE_CASE_SETS.length - 1 : prev - 1));
  };

  const handleNext = () => {
    setIndex((prev) => (prev === USE_CASE_SETS.length - 1 ? 0 : prev + 1));
  };

  return (
    <section id="testimonials" className="py-8 lg:py-12 bg-[#F4F3EE] border-b border-[#D8D5CA]">
      <div className="max-w-4xl mx-auto px-4 sm:px-6">
        {/* Top Header Bar with Carousel Controls */}
        <div className="flex items-center justify-between pb-3.5 border-b border-[#D8D5CA] mb-5">
          <span className="text-[10px] font-bold tracking-[0.2em] text-[#0A0A0A] uppercase font-mono">
            CLIENT EXPERIENCES &amp; REVIEWS
          </span>

          <div className="flex items-center gap-1.5">
            <button
              onClick={handlePrev}
              className="w-7 h-7 rounded-full border border-[#CDC9BC] flex items-center justify-center hover:bg-[#0A0A0A] hover:text-white transition-colors cursor-pointer text-[#0A0A0A] bg-white shadow-xs"
              aria-label="Previous testimonial"
            >
              <ChevronLeft className="w-3.5 h-3.5 stroke-[2.5]" />
            </button>
            <button
              onClick={handleNext}
              className="w-7 h-7 rounded-full border border-[#CDC9BC] flex items-center justify-center hover:bg-[#0A0A0A] hover:text-white transition-colors cursor-pointer text-[#0A0A0A] bg-white shadow-xs"
              aria-label="Next testimonial"
            >
              <ChevronRight className="w-3.5 h-3.5 stroke-[2.5]" />
            </button>
          </div>
        </div>

        {/* 3-Column Layout with High Contrast Text - Compact & Lightweight */}
        <div key={index} className="grid grid-cols-1 lg:grid-cols-12 gap-3.5 lg:gap-5 items-center bg-white p-4 sm:p-5 rounded-xl border border-[#CDC9BC] shadow-md animate-fade-in-scale">
          {/* Left Testimonial */}
          <div className="lg:col-span-5 space-y-1.5">
            <span className="font-serif text-xl sm:text-2xl text-emerald-800 font-extrabold block leading-none">
              “
            </span>
            <blockquote className="font-serif italic text-xs sm:text-sm text-[#0A0A0A] font-medium leading-relaxed">
              "{current.left.quote}"
            </blockquote>
            <div className="pt-0.5 text-[10px] sm:text-[11px] font-mono text-[#262522] font-bold">
              — {current.left.author} · <span className="text-emerald-800">{current.left.role}</span>
            </div>
          </div>

          {/* Center Round Portrait */}
          <div className="lg:col-span-2 flex flex-col items-center justify-center text-center py-2 lg:py-0 border-y lg:border-y-0 lg:border-x border-[#EAE7DD] px-2">
            <div className="w-12 h-12 sm:w-14 sm:h-14 rounded-full overflow-hidden border-2 border-[#0A0A0A] shadow-sm relative bg-[#0A0A0A] mb-1.5 ring-1 ring-emerald-400/40">
              <img
                src={current.clientPhoto}
                alt={current.left.author}
                referrerPolicy="no-referrer"
                className="w-full h-full object-cover"
              />
            </div>
            <div className="font-serif text-xs font-bold text-[#0A0A0A] leading-tight">
              {current.left.author}
            </div>
            <div className="text-[9px] font-mono text-[#4A473F] uppercase tracking-wider font-semibold leading-tight mt-0.5">
              {current.left.role}
            </div>
          </div>

          {/* Right Testimonial */}
          <div className="lg:col-span-5 space-y-1.5">
            <span className="font-serif text-xl sm:text-2xl text-emerald-800 font-extrabold block leading-none">
              “
            </span>
            <blockquote className="font-serif italic text-xs sm:text-sm text-[#0A0A0A] font-medium leading-relaxed">
              "{current.right.quote}"
            </blockquote>
            <div className="pt-0.5 text-[10px] sm:text-[11px] font-mono text-[#262522] font-bold">
              — {current.right.author} · <span className="text-emerald-800">{current.right.role}</span>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};
