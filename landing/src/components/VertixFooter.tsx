import React, { useState } from 'react';
import { ArrowRight, Instagram, Linkedin, Youtube, Mail, Phone, MapPin, Check } from 'lucide-react';

export const VertixFooter: React.FC = () => {
  const [newsletterEmail, setNewsletterEmail] = useState('');
  const [newsletterSubscribed, setNewsletterSubscribed] = useState(false);

  const handleNewsletter = (e: React.FormEvent) => {
    e.preventDefault();
    if (!newsletterEmail.trim()) return;
    setNewsletterSubscribed(true);
    setTimeout(() => {
      setNewsletterSubscribed(false);
      setNewsletterEmail('');
    }, 4000);
  };

  return (
    <footer className="bg-[#0A0A0A] text-neutral-300 py-16 lg:py-20 border-t-2 border-[#262626] text-xs">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 pb-12 border-b-2 border-[#222222]">
          {/* Column 1: Brand & Monogram */}
          <div className="lg:col-span-4 space-y-4">
            <div className="flex items-center gap-3">
              <div className="w-10 h-10 border-2 border-white flex items-center justify-center text-white font-brand tracking-tighter text-sm font-extrabold shadow-sm">
                RE
              </div>
              <div className="flex flex-col">
                <span className="font-brand text-sm tracking-[0.25em] text-white font-bold uppercase leading-none">
                  REAL ESTATE CRM
                </span>
                <span className="text-[10px] tracking-[0.2em] text-emerald-400 uppercase font-sans font-bold mt-1">
                  PROPERTY SALES PLATFORM
                </span>
              </div>
            </div>
            <p className="text-sm text-neutral-300 max-w-sm leading-relaxed font-normal">
              The high-converting Real Estate CRM platform designed for builders, developers, brokers, and channel partners across India to manage property enquiries, schedule site visits, and close bookings on WhatsApp without technical headaches.
            </p>
          </div>

          {/* Column 2: Navigation */}
          <div className="lg:col-span-2 space-y-3">
            <h4 className="text-white font-mono text-xs tracking-[0.2em] uppercase font-bold">
              Navigation
            </h4>
            <ul className="space-y-2 text-sm text-neutral-300 font-medium">
              <li><a href="#home" className="hover:text-white transition-colors">Overview</a></li>
              <li><a href="#audience" className="hover:text-white transition-colors">Who It's For</a></li>
              <li><a href="#workflow" className="hover:text-white transition-colors">Lead Journey</a></li>
              <li><a href="#features" className="hover:text-white transition-colors">Capabilities</a></li>
              <li><a href="#results" className="hover:text-white transition-colors">Results &amp; ROI</a></li>
              <li><a href="#faq" className="hover:text-white transition-colors">FAQ</a></li>
              <li><a href="#contact" className="hover:text-white transition-colors">Request Demo</a></li>
            </ul>
          </div>

          {/* Column 3: Get in Touch */}
          <div className="lg:col-span-3 space-y-3">
            <h4 className="text-white font-mono text-xs tracking-[0.2em] uppercase font-bold">
              Get in Touch
            </h4>
            <div className="space-y-2.5 text-sm text-neutral-300 font-medium">
              <div className="flex items-center gap-2.5">
                <Mail className="w-4 h-4 text-emerald-400 shrink-0" />
                <a href="mailto:hello@realestatecrm.in" className="hover:text-white transition-colors text-white font-semibold">
                  hello@realestatecrm.in
                </a>
              </div>
              <div className="flex items-center gap-2.5">
                <Phone className="w-4 h-4 text-emerald-400 shrink-0" />
                <span>+91 98765 43210</span>
              </div>
              <div className="flex items-center gap-2.5 pt-1">
                <MapPin className="w-4 h-4 text-emerald-400 shrink-0" />
                <span className="text-white font-semibold">Gurugram HQ · Golf Course Road</span>
              </div>
              <div className="pl-6.5 text-xs text-neutral-400">Mumbai Office · Bandra Kurla Complex (BKC)</div>
              <div className="pl-6.5 text-xs text-neutral-400">Bengaluru Hub · Whitefield IT Corridor</div>
            </div>
          </div>

          {/* Column 4: Follow Us & Newsletter */}
          <div className="lg:col-span-3 space-y-5">
            <div className="space-y-2">
              <h4 className="text-white font-mono text-xs tracking-[0.2em] uppercase font-bold">
                Follow Us
              </h4>
              <div className="flex items-center gap-3 text-white">
                <a href="#" className="w-9 h-9 rounded-full border-2 border-white/30 flex items-center justify-center hover:border-white hover:bg-white hover:text-black transition-colors" aria-label="Instagram">
                  <Instagram className="w-4 h-4" />
                </a>
                <a href="#" className="w-9 h-9 rounded-full border-2 border-white/30 flex items-center justify-center hover:border-white hover:bg-white hover:text-black transition-colors" aria-label="LinkedIn">
                  <Linkedin className="w-4 h-4" />
                </a>
                <a href="#" className="w-9 h-9 rounded-full border-2 border-white/30 flex items-center justify-center hover:border-white hover:bg-white hover:text-black transition-colors" aria-label="YouTube">
                  <Youtube className="w-4 h-4" />
                </a>
              </div>
            </div>

            <div className="space-y-2">
              <h4 className="text-white font-mono text-xs tracking-[0.2em] uppercase font-bold">
                Real Estate Sales Insights
              </h4>
              {newsletterSubscribed ? (
                <div className="text-xs text-emerald-400 font-bold flex items-center gap-2 py-2">
                  <Check className="w-4 h-4" />
                  <span>Subscribed to our weekly real estate sales blueprint!</span>
                </div>
              ) : (
                <form onSubmit={handleNewsletter} className="flex items-center">
                  <input
                    type="email"
                    required
                    placeholder="Work email address"
                    value={newsletterEmail}
                    onChange={(e) => setNewsletterEmail(e.target.value)}
                    className="w-full text-xs font-medium px-3.5 py-2.5 bg-[#1C1C1C] border-2 border-[#333333] text-white placeholder-neutral-500 focus:outline-none focus:border-white rounded-l-md"
                  />
                  <button
                    type="submit"
                    className="p-2.5 bg-white text-black hover:bg-neutral-200 transition-colors cursor-pointer rounded-r-md font-bold"
                    aria-label="Subscribe"
                  >
                    <ArrowRight className="w-4 h-4" />
                  </button>
                </form>
              )}
            </div>
          </div>
        </div>

        {/* Editorial Sub-Footer */}
        <div className="pt-6 pb-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left border-b-2 border-[#222222]">
          <div className="font-mono text-xs tracking-[0.3em] uppercase text-neutral-300 font-bold">
            ENQUIRIES <span className="mx-2 text-emerald-400">·</span> SITE VISITS <span className="mx-2 text-emerald-400">·</span> TOKEN BOOKINGS
          </div>
        </div>

        {/* Bottom Legal Row */}
        <div className="pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-neutral-400 font-medium">
          <div>
            © 2026 Real Estate CRM Systems India Pvt. Ltd. All rights reserved.
          </div>
          <div className="flex items-center gap-4">
            <a href="#" className="hover:text-white transition-colors">Privacy Policy</a>
            <span>·</span>
            <a href="#" className="hover:text-white transition-colors">Terms of Service</a>
            <span>·</span>
            <span className="text-neutral-500">Confidential &amp; Proprietary</span>
          </div>
        </div>
      </div>
    </footer>
  );
};
