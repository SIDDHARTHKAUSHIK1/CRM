import React, { useState, useEffect } from 'react';
import { RealEstateMotionBackground } from './RealEstateMotionBackground';
import {
  FileSpreadsheet,
  MessageSquare,
  Calendar,
  FileCheck2,
  KeyRound,
  Check,
  Sparkles,
} from 'lucide-react';

export const RealEstateWorkflow: React.FC = () => {
  const [activeStep, setActiveStep] = useState<number>(0);

  const STEPS = [
    {
      number: '1',
      title: 'Capture',
      icon: FileSpreadsheet,
      badge: 'STEP 1: INBOUND CAPTURE',
      description: 'Inbound leads from 99acres, Magicbricks, Meta Ads & WhatsApp auto-synced.',
    },
    {
      number: '2',
      title: 'Engage',
      icon: MessageSquare,
      badge: 'STEP 2: INSTANT ENGAGEMENT',
      description: 'Pre-approved WhatsApp PDF brochures & floor plans dispatched in 60s.',
    },
    {
      number: '3',
      title: 'Schedule',
      icon: Calendar,
      badge: 'STEP 3: SITE WALKTHROUGH',
      description: 'Site visits confirmed with Google Maps pin & automated WhatsApp reminders.',
    },
    {
      number: '4',
      title: 'Propose',
      icon: FileCheck2,
      badge: 'STEP 4: COST SHEET DISPATCH',
      description: '1-Click branded PDF cost sheets with milestone payment schedules & GST.',
    },
    {
      number: '5',
      title: 'Close',
      icon: KeyRound,
      badge: 'STEP 5: BOOKING WON & VAULT',
      description: 'Token booking confirmed (₹ Lakhs) & customer data secured in company vault.',
    },
  ];

  // Auto-advancing continuous loop: full 5-step cycle completes in exactly 4 seconds (800ms per step)
  useEffect(() => {
    const timer = setInterval(() => {
      setActiveStep((prev) => (prev === STEPS.length - 1 ? 0 : prev + 1));
    }, 800);

    return () => clearInterval(timer);
  }, [STEPS.length]);

  const progressPercentage = (activeStep / (STEPS.length - 1)) * 100;

  return (
    <section id="workflow" className="isolate py-16 sm:py-24 bg-[#F8FAFC]/85 backdrop-blur-xs border-b border-[#E2E8F0]/80 relative overflow-hidden">
      <RealEstateMotionBackground variant="pipeline" />
      
      {/* Background Ambience */}
      <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[45rem] h-[25rem] bg-blue-400/8 rounded-full blur-3xl pointer-events-none"></div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 sm:space-y-14 relative z-10">
        
        {/* Section Header */}
        <div className="text-center space-y-3 max-w-2xl mx-auto">
          <div className="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-gradient-to-r from-blue-50/90 via-indigo-50/70 to-blue-50/90 text-[#1864E8] text-[11px] font-outfit tracking-[0.2em] uppercase font-extrabold border border-blue-200/90 shadow-[0_2px_10px_rgba(24,100,232,0.08)] backdrop-blur-md">
            <span className="relative flex h-2 w-2">
              <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
              <span className="relative inline-flex rounded-full h-2 w-2 bg-[#1864E8]"></span>
            </span>
            <span>HOW IT WORKS · 5-STEP DEAL FLOW</span>
          </div>
          <h2 className="text-3xl sm:text-4xl lg:text-[42px] font-extrabold text-[#0F172A] tracking-tight leading-tight">
            A Smarter Way to Close
          </h2>
          <p className="text-xs sm:text-sm text-[#64748B] font-normal leading-relaxed max-w-lg mx-auto">
            Watch how a property lead automatically journeys from first enquiry to site visit, proposal, and token booking.
          </p>
        </div>

        {/* 5-Step Connected Interactive Circles with Animated Progress Beam */}
        <div className="relative pt-4">
          
          {/* Base Dotted Connecting Line (Desktop) */}
          <div className="hidden lg:block absolute top-12 left-[10%] right-[10%] h-1 border-t-2 border-dashed border-[#CBD5E1] -z-0"></div>

          {/* Active Animated Gradient Progress Beam */}
          <div
            className="hidden lg:block absolute top-12 left-[10%] h-1 bg-gradient-to-r from-[#1864E8] via-[#38BDF8] to-[#1864E8] transition-all duration-700 ease-out shadow-[0_0_12px_rgba(24,100,232,0.6)] z-0"
            style={{ width: `${progressPercentage * 0.8}%` }}
          >
            {/* Glowing Flowing Pulse Bead */}
            <div className="absolute right-0 top-1/2 -translate-y-1/2 w-3.5 h-3.5 rounded-full bg-white border-2 border-[#1864E8] shadow-[0_0_10px_#1864E8] animate-ping"></div>
          </div>

          {/* 5 Steps Grid */}
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8 lg:gap-6 relative z-10">
            {STEPS.map((step, idx) => {
              const Icon = step.icon;
              const isActive = idx === activeStep;
              const isPast = idx < activeStep;

              return (
                <div
                  key={step.number}
                  onClick={() => setActiveStep(idx)}
                  className={`flex flex-col items-center text-center space-y-3 cursor-pointer transition-all duration-300 group ${
                    isActive ? 'scale-105' : 'hover:scale-102 opacity-80 hover:opacity-100'
                  }`}
                >
                  {/* Circle Icon Container */}
                  <div className="relative">
                    <div
                      className={`w-16 h-16 rounded-full flex items-center justify-center transition-all duration-500 shadow-md ${
                        isActive
                          ? 'bg-[#1864E8] text-white ring-4 ring-[#1864E8]/30 shadow-2xl shadow-blue-500/40 scale-110'
                          : isPast
                          ? 'bg-[#0F172A] text-white shadow-md'
                          : 'bg-white text-[#64748B] border-2 border-[#CBD5E1] hover:border-[#1864E8]'
                      }`}
                    >
                      <Icon className={`w-7 h-7 stroke-[2.2] ${isActive ? 'animate-bounce' : ''}`} style={{ animationDuration: '2s' }} />
                    </div>

                    {/* Step Number Badge */}
                    <div
                      className={`absolute -bottom-1 -left-1 w-6 h-6 rounded-full text-[11px] font-mono font-bold flex items-center justify-center border-2 border-white shadow-md transition-colors ${
                        isActive
                          ? 'bg-[#1864E8] text-white'
                          : isPast
                          ? 'bg-emerald-600 text-white'
                          : 'bg-[#0F172A] text-white'
                      }`}
                    >
                      {isPast ? <Check className="w-3.5 h-3.5 stroke-[3]" /> : step.number}
                    </div>

                    {/* Active Ping Indicator */}
                    {isActive && (
                      <span className="absolute -top-1 -right-1 flex h-3.5 w-3.5">
                        <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#1864E8] opacity-75"></span>
                        <span className="relative inline-flex rounded-full h-3.5 w-3.5 bg-emerald-500 border border-white"></span>
                      </span>
                    )}
                  </div>

                  {/* Step Title */}
                  <div className="space-y-0.5">
                    <h3
                      className={`text-base font-bold transition-colors ${
                        isActive ? 'text-[#1864E8] scale-105' : 'text-[#0F172A]'
                      }`}
                    >
                      {step.title}
                    </h3>
                    <span
                      className={`text-[9px] font-mono uppercase font-bold px-2 py-0.5 rounded-full inline-block ${
                        isActive
                          ? 'bg-blue-100 text-[#1864E8]'
                          : 'bg-slate-100 text-slate-500'
                      }`}
                    >
                      Phase 0{step.number}
                    </span>
                  </div>

                  {/* Step Description */}
                  <p className="text-xs text-[#64748B] leading-relaxed max-w-[200px]">
                    {step.description}
                  </p>
                </div>
              );
            })}
          </div>
        </div>

      </div>
    </section>
  );
};
