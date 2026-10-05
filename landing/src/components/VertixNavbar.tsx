import React, { useState } from 'react';
import { ArrowRight, Menu, X } from 'lucide-react';

interface VertixNavbarProps {
  onStartProject: () => void;
}

export const VertixNavbar: React.FC<VertixNavbarProps> = ({ onStartProject }) => {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

  const NAV_LINKS = [
    { href: '#home', label: 'Overview' },
    { href: '#audience', label: 'Who It\'s For' },
    { href: '#workflow', label: '6-Step Workflow' },
    { href: '#tour', label: 'Product Tour' },
    { href: '#features', label: 'Capabilities' },
    { href: '#results', label: 'Results & ROI' },
    { href: '#faq', label: 'FAQ' },
  ];

  return (
    <header className="sticky top-0 z-50 w-full bg-[#F4F3EE]/95 backdrop-blur-md border-b-2 border-[#D8D5CA] transition-all">
      <div className="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-16 sm:h-20">
          {/* Brand Wordmark & RE Monogram - Anchored Left */}
          <a href="#home" className="flex items-center gap-3.5 group focus:outline-none shrink-0">
            {/* RE Monogram Glyph */}
            <div className="w-10 h-10 border-2 border-[#0A0A0A] flex items-center justify-center text-[#0A0A0A] font-brand tracking-tighter text-sm font-extrabold transition-colors group-hover:bg-[#0A0A0A] group-hover:text-white shadow-xs">
              RE
            </div>
            <div className="flex flex-col">
              <span className="font-brand text-sm tracking-[0.22em] text-[#0A0A0A] font-extrabold uppercase leading-none">
                REAL ESTATE CRM
              </span>
              <span className="text-[10px] tracking-[0.18em] text-[#3D3A34] uppercase font-sans font-bold mt-1">
                PROPERTY SALES PLATFORM
              </span>
            </div>
          </a>

          {/* Navigation Links - Center */}
          <nav className="hidden lg:flex items-center gap-4 xl:gap-6 text-xs font-bold tracking-wider uppercase text-[#262522]">
            {NAV_LINKS.map((link) => (
              <a
                key={link.href}
                href={link.href}
                className="hover:text-black transition-colors hover:border-b-2 hover:border-[#0A0A0A] pb-0.5 whitespace-nowrap"
              >
                {link.label}
              </a>
            ))}
          </nav>

          {/* Primary Action Button - Right */}
          <div className="hidden sm:flex items-center gap-3 shrink-0">
            <button
              onClick={onStartProject}
              className="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-extrabold tracking-wider text-white bg-[#0A0A0A] hover:bg-[#262626] active:bg-black rounded-full transition-all cursor-pointer shadow-md hover:shadow-lg hover:scale-105"
            >
              <span>Request a Demo</span>
              <ArrowRight className="w-3.5 h-3.5" />
            </button>
          </div>

          {/* Mobile hamburger */}
          <div className="flex lg:hidden items-center gap-2">
            <button
              onClick={onStartProject}
              className="px-3.5 py-1.5 text-xs font-extrabold tracking-wider text-white bg-[#0A0A0A] rounded-full sm:hidden"
            >
              Demo
            </button>
            <button
              onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
              className="p-2 text-[#0A0A0A] hover:bg-[#EAE7DD] rounded-lg transition-colors"
              aria-label="Toggle Menu"
            >
              {mobileMenuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
            </button>
          </div>
        </div>
      </div>

      {/* Mobile Menu Dropdown */}
      {mobileMenuOpen && (
        <div className="lg:hidden border-b-2 border-[#D8D5CA] bg-[#F4F3EE] px-6 py-6 space-y-4 shadow-2xl animate-in fade-in">
          <nav className="flex flex-col space-y-3 text-xs uppercase tracking-widest font-bold text-[#262522]">
            {NAV_LINKS.map((link) => (
              <a
                key={link.href}
                href={link.href}
                onClick={() => setMobileMenuOpen(false)}
                className="hover:text-black py-1.5 border-b border-[#D8D5CA]"
              >
                {link.label}
              </a>
            ))}
          </nav>
          <div className="pt-2">
            <button
              onClick={() => {
                setMobileMenuOpen(false);
                onStartProject();
              }}
              className="w-full py-3.5 text-xs tracking-widest uppercase font-extrabold text-white bg-[#0A0A0A] hover:bg-[#262626] rounded-full flex items-center justify-center gap-2 shadow-lg"
            >
              <span>Schedule 15-Min Demo</span>
              <ArrowRight className="w-4 h-4" />
            </button>
          </div>
        </div>
      )}
    </header>
  );
};
