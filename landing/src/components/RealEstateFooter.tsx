import React from 'react';
import { RealEstateFooterScene } from './RealEstateFooterScene';
import { Phone, Mail, MapPin, Building2, Facebook, Instagram, Twitter, Linkedin } from 'lucide-react';

export const RealEstateFooter: React.FC = () => {
  return (
    <footer className="relative isolate overflow-hidden bg-[#0B1A30] text-white pt-16 pb-10 border-t border-slate-800">
      <RealEstateFooterScene />
      <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        
        {/* Main 5-Column Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 lg:gap-8 pb-20 sm:pb-24">
          
          {/* Column 1: Brand & Social */}
          <div className="space-y-4">
            <div className="flex items-center gap-2.5">
              <div className="w-8 h-8 rounded-lg bg-[#1864E8] flex items-center justify-center text-white shadow-xs">
                <Building2 className="w-5 h-5 stroke-[2]" />
              </div>
              <div className="flex flex-col">
                <span className="text-base font-black tracking-tight text-white uppercase leading-none">
                  REAL ESTATE CRM
                </span>
                <span className="text-[9px] tracking-[0.2em] text-slate-400 uppercase font-bold mt-0.5">
                  SALES &amp; PLATFORM
                </span>
              </div>
            </div>

            <p className="text-xs text-slate-400 leading-relaxed max-w-xs">
              Helping you find the perfect place to call home. Your trusted real estate and sales CRM partner.
            </p>

            {/* Social Icons */}
            <div className="flex items-center gap-3 pt-1 text-slate-400">
              <a href="#facebook" className="w-8 h-8 rounded-full bg-white/5 hover:bg-[#1864E8] hover:text-white flex items-center justify-center transition-colors"><Facebook className="w-4 h-4" /></a>
              <a href="#instagram" className="w-8 h-8 rounded-full bg-white/5 hover:bg-[#1864E8] hover:text-white flex items-center justify-center transition-colors"><Instagram className="w-4 h-4" /></a>
              <a href="#twitter" className="w-8 h-8 rounded-full bg-white/5 hover:bg-[#1864E8] hover:text-white flex items-center justify-center transition-colors"><Twitter className="w-4 h-4" /></a>
              <a href="#linkedin" className="w-8 h-8 rounded-full bg-white/5 hover:bg-[#1864E8] hover:text-white flex items-center justify-center transition-colors"><Linkedin className="w-4 h-4" /></a>
            </div>
          </div>

          {/* Column 2: Quick Links */}
          <div className="space-y-3">
            <h4 className="text-xs font-bold uppercase tracking-wider text-white">
              Quick Links
            </h4>
            <ul className="space-y-2 text-xs text-slate-400 font-medium">
              <li><a href="#home" className="hover:text-white transition-colors">Home</a></li>
              <li><a href="#solutions" className="hover:text-white transition-colors">Solutions</a></li>
              <li><a href="#workflow" className="hover:text-white transition-colors">How It Works</a></li>
              <li><a href="#faq" className="hover:text-white transition-colors">FAQ</a></li>
              <li><a href="/admin/login" className="text-[#60A5FA] font-bold hover:text-white transition-colors">CRM Portal Login</a></li>
            </ul>
          </div>

          {/* Column 3: Company */}
          <div className="space-y-3">
            <h4 className="text-xs font-bold uppercase tracking-wider text-white">
              Company
            </h4>
            <ul className="space-y-2 text-xs text-slate-400 font-medium">
              <li><a href="#why-us" className="hover:text-white transition-colors">About Us</a></li>
              <li><a href="#why-us" className="hover:text-white transition-colors">Our Agents</a></li>
              <li><a href="#why-us" className="hover:text-white transition-colors">Careers</a></li>
              <li><a href="#why-us" className="hover:text-white transition-colors">Blog</a></li>
              <li><a href="#contact" className="hover:text-white transition-colors">Contact Us</a></li>
            </ul>
          </div>

          {/* Column 4: Resources */}
          <div className="space-y-3">
            <h4 className="text-xs font-bold uppercase tracking-wider text-white">
              Resources
            </h4>
            <ul className="space-y-2 text-xs text-slate-400 font-medium">
              <li><a href="#efficiency" className="hover:text-white transition-colors">Home Valuation</a></li>
              <li><a href="#solutions" className="hover:text-white transition-colors">Buyers Guide</a></li>
              <li><a href="#solutions" className="hover:text-white transition-colors">Sellers Guide</a></li>
              <li><a href="#faq" className="hover:text-white transition-colors">FAQ</a></li>
              <li><a href="#software-preview" className="hover:text-white transition-colors">Mortgage Calculator</a></li>
            </ul>
          </div>

          {/* Column 5: Contact Us */}
          <div className="space-y-3">
            <h4 className="text-xs font-bold uppercase tracking-wider text-white">
              Contact Us
            </h4>
            <ul className="space-y-2.5 text-xs text-slate-400 font-medium">
              <li className="flex items-center gap-2">
                <Phone className="w-3.5 h-3.5 text-[#1864E8] shrink-0" />
                <span>(800) 123-4567</span>
              </li>
              <li className="flex items-center gap-2">
                <Mail className="w-3.5 h-3.5 text-[#1864E8] shrink-0" />
                <span>info@realestatecrm.com</span>
              </li>
              <li className="flex items-start gap-2">
                <MapPin className="w-3.5 h-3.5 text-[#1864E8] shrink-0 mt-0.5" />
                <span>123 Real Estate Blvd, Suite 100, Los Angeles, CA 90001</span>
              </li>
            </ul>
          </div>

        </div>

        {/* Bottom Copyright Bar */}
        <div className="pt-6 border-t border-slate-700/80 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-300 font-medium">
          <div>
            © 2026 Real Estate CRM. All rights reserved.
          </div>
          <div className="flex items-center gap-6">
            <a href="#privacy" className="hover:text-white transition-colors">Privacy Policy</a>
            <a href="#terms" className="hover:text-white transition-colors">Terms of Service</a>
          </div>
        </div>

      </div>
    </footer>
  );
};
