import React from 'react';
import {
  Inbox,
  Kanban,
  MessageCircle,
  FileCheck2,
  BellRing,
  Lock,
  Check,
} from 'lucide-react';

export const VertixFeatures: React.FC = () => {
  const FEATURES = [
    {
      id: 'centralized-inbox',
      icon: Inbox,
      badge: 'LEAD CAPTURE',
      title: 'Centralized Lead Management',
      description:
        'Capture enquiries from website forms, WhatsApp, portals, and CSV files in one unified inbox.',
      bullets: [
        'Website & WhatsApp lead auto-capture',
        'CSV & Excel instant contact import',
        'Automatic deduplication & lead routing',
      ],
      previewStats: 'Centralized Lead Control',
    },
    {
      id: 'kanban-pipeline',
      icon: Kanban,
      badge: 'VISUAL PIPELINE',
      title: 'Visual Sales Pipeline',
      description:
        'Track every deal from New Enquiry to Site Visit and Token Booking on a visual board.',
      bullets: [
        'Custom stages for plots, flats, villas & SCO',
        'Live pipeline value in ₹ Lakhs & Crores',
        'Executive filtering & project-wise tracking',
      ],
      previewStats: 'Real-Time Deal Stages',
    },
    {
      id: 'whatsapp-suite',
      icon: MessageCircle,
      badge: 'COMMUNICATION',
      title: 'WhatsApp & 2-Way Email Sync',
      description:
        'Send brochures, layouts, and cost sheets with all client conversations permanently logged.',
      bullets: [
        'Official WhatsApp 2-way team chat',
        'Two-way email sync linked to lead files',
        'Pre-approved quick-reply message templates',
      ],
      previewStats: 'Multi-Channel Inbox',
    },
    {
      id: 'instant-proposals',
      icon: FileCheck2,
      badge: 'QUOTATIONS',
      title: 'Branded PDF Cost Sheets',
      description:
        'Generate itemized payment plans and project quotations in seconds directly to WhatsApp.',
      bullets: [
        'Pre-loaded inventory & unit pricing catalog',
        'Construction-linked milestone breakdown',
        '1-Click branded PDF export & delivery',
      ],
      previewStats: 'Instant PDF Proposals',
    },
    {
      id: 'automated-reminders',
      icon: BellRing,
      badge: 'CALENDAR & TASKS',
      title: 'Calendar & Smart Reminders',
      description:
        'Schedule site visits, log meeting notes, and receive automated alerts for follow-up calls.',
      bullets: [
        'Team calendar for site visit schedules',
        'Call logs and meeting notes on every lead',
        'Automated alerts for pending client follow-ups',
      ],
      previewStats: 'Zero Missed Follow-ups',
    },
    {
      id: 'role-security',
      icon: Lock,
      badge: 'DATA VAULT',
      title: 'Role Access & Data Protection',
      description:
        'Protect high-net-worth buyer contacts with masked phone numbers and role permissions.',
      bullets: [
        'Executive, Manager & Admin permission levels',
        'Masked buyer phone numbers & export control',
        '1-Click employee offboarding with safe data retention',
      ],
      previewStats: 'Protected Buyer Vault',
    },
  ];

  return (
    <section id="features" className="py-10 lg:py-14 bg-[#F4F3EE] border-b border-[#D8D5CA] relative">
      <div className="max-w-5xl mx-auto px-4 sm:px-6">
        {/* Compact Section Header */}
        <div className="flex flex-col md:flex-row md:items-end justify-between gap-3 pb-5 border-b border-[#D8D5CA] mb-6 sm:mb-8">
          <div className="space-y-1.5 max-w-2xl">
            <span className="text-[10px] font-bold tracking-[0.25em] text-[#0A0A0A] uppercase font-mono block">
              REAL ESTATE CRM CAPABILITIES
            </span>
            <h2 className="font-serif text-2xl sm:text-3xl text-[#0A0A0A] font-bold leading-tight">
              Everything You Need to <span className="italic font-normal text-emerald-800">Close Property Deals</span>
            </h2>
            <p className="text-xs text-[#3D3A34] font-normal leading-relaxed">
              Engineered for builders, developers, brokers, and sales teams who want high conversion without complex software.
            </p>
          </div>

          <div className="font-mono text-[10px] text-[#3D3A34] uppercase tracking-wider font-extrabold bg-white px-3 py-1 rounded-full border border-[#CDC9BC] shadow-xs shrink-0 self-start md:self-auto">
            <span>6 Modular Systems</span>
          </div>
        </div>

        {/* Compact 3-Column Features Grid */}
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 sm:gap-4 items-stretch">
          {FEATURES.map((feature) => {
            const Icon = feature.icon;
            return (
              <div
                key={feature.id}
                className="p-3.5 sm:p-4 rounded-xl bg-white border border-[#CDC9BC] hover:border-[#0A0A0A] hover-lift flex flex-col justify-between group space-y-3 cursor-default shadow-xs transition-all duration-200"
              >
                <div className="space-y-2.5">
                  {/* Top Icon & Badge */}
                  <div className="flex items-center justify-between">
                    <div className="w-7 h-7 rounded-lg bg-[#0A0A0A] flex items-center justify-center text-white shadow-xs group-hover:scale-105 group-hover:bg-emerald-700 transition-all duration-300">
                      <Icon className="w-3.5 h-3.5 stroke-[2]" />
                    </div>
                    <span className="text-[9px] font-mono tracking-wider uppercase px-2 py-0.5 rounded-full bg-[#EFECE4] text-[#111111] font-bold group-hover:bg-emerald-50 group-hover:text-emerald-800 transition-colors duration-300">
                      {feature.badge}
                    </span>
                  </div>

                  {/* Title & Short Description */}
                  <div className="space-y-1">
                    <h3 className="font-serif text-base sm:text-lg text-[#0A0A0A] font-bold leading-snug">
                      {feature.title}
                    </h3>
                    <p className="text-[11px] sm:text-xs text-[#4A473F] font-normal leading-relaxed">
                      {feature.description}
                    </p>
                  </div>

                  {/* Concise Bullets List (2-3 items) */}
                  <div className="space-y-1.5 pt-2 border-t border-[#EAE7DD]">
                    {feature.bullets.map((bullet, bIdx) => (
                      <div key={bIdx} className="flex items-start gap-1.5 text-[11px] text-[#1A1A1A] font-medium leading-tight">
                        <div className="w-3 h-3 rounded-full bg-emerald-600 flex items-center justify-center text-white shrink-0 mt-0.5 shadow-2xs">
                          <Check className="w-2 h-2 stroke-[3.5]" />
                        </div>
                        <span>{bullet}</span>
                      </div>
                    ))}
                  </div>
                </div>

                {/* Bottom Compact Stat Pill */}
                <div className="py-1.5 px-2.5 rounded-lg bg-[#FAF9F5] border border-[#EAE7DD] text-[10px] font-mono text-[#0A0A0A] flex items-center justify-between font-bold shadow-2xs">
                  <span className="text-[#66635B] font-semibold uppercase text-[8.5px]">Outcome:</span>
                  <span className="font-bold text-emerald-800">{feature.previewStats}</span>
                </div>
              </div>
            );
          })}
        </div>
      </div>
    </section>
  );
};
