import React, { useState, useEffect } from 'react';
import { ArrowRight, Menu, X, Phone, Sparkles, CheckCircle2, ChevronRight } from 'lucide-react';

interface RealEstateNavbarProps {
  onStartDemo: () => void;
}

export const RealEstateNavbar: React.FC<RealEstateNavbarProps> = ({ onStartDemo }) => {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const [isScrolled, setIsScrolled] = useState(false);
  const [scrollProgress, setScrollProgress] = useState(0);
  const [activeSection, setActiveSection] = useState<string>('home');

  const NAV_LINKS = [
    { href: '#solutions', id: 'solutions', label: 'Solutions' },
    { href: '#workflow', id: 'workflow', label: 'How It Works' },
    { href: '#software-preview', id: 'software-preview', label: 'Software' },
    { href: '#why-us', id: 'why-us', label: 'Why Choose Us' },
    { href: '#faq', id: 'faq', label: 'FAQ' },
  ];

  // Dynamic Scroll & Progress Spy
  useEffect(() => {
    const handleScroll = () => {
      const scrollY = window.scrollY;
      setIsScrolled(scrollY > 20);

      // Calculate scroll progress percentage
      const winHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
      if (winHeight > 0) {
        setScrollProgress(Math.min(100, Math.max(0, (scrollY / winHeight) * 100)));
      }

      // Active Section Spy
      const sectionIds = ['home', 'solutions', 'why-us', 'efficiency', 'why-switch', 'workflow', 'software-preview', 'faq'];
      const scrollPosition = scrollY + 120;

      for (let i = sectionIds.length - 1; i >= 0; i--) {
        const id = sectionIds[i];
        const el = document.getElementById(id);
        if (el) {
          const top = el.offsetTop;
          if (scrollPosition >= top) {
            setActiveSection(id);
            break;
          }
        }
      }
    };

    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  const handleSmoothScroll = (e: React.MouseEvent<HTMLAnchorElement>, href: string) => {
    e.preventDefault();
    setMobileMenuOpen(false);

    if (href === '#home') {
      window.scrollTo({ top: 0, behavior: 'smooth' });
      return;
    }

    const targetId = href.replace('#', '');
    const element = document.getElementById(targetId);
    if (element) {
      const navOffset = 80;
      const elementPosition = element.getBoundingClientRect().top + window.pageYOffset;
      const offsetPosition = elementPosition - navOffset;

      window.scrollTo({
        top: offsetPosition,
        behavior: 'smooth',
      });
    }
  };

  return (
    <header
      className={`sticky top-0 z-50 w-full transition-all duration-300 ${
        isScrolled
          ? 'bg-white/95 backdrop-blur-xl shadow-md shadow-slate-900/5 border-b border-slate-200/90'
          : 'bg-white/90 backdrop-blur-md border-b border-slate-100'
      }`}
    >
      {/* Dynamic Scroll Progress Bar */}
      <div
        className="absolute top-0 left-0 h-[2.5px] bg-gradient-to-r from-[#1864E8] via-[#3B82F6] to-[#60A5FA] transition-all duration-150 ease-out z-50 pointer-events-none"
        style={{ width: `${scrollProgress}%` }}
      />

      <div className="max-w-[1400px] 2xl:max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-10 xl:px-12 2xl:px-16">
        <div
          className={`flex items-center justify-between transition-all duration-300 ${
            isScrolled ? 'h-16 sm:h-18' : 'h-18 sm:h-20'
          }`}
        >
          {/* Brand Logo & Wordmark - Anchored Left */}
          <a
            href="#home"
            onClick={(e) => handleSmoothScroll(e, '#home')}
            className="flex items-center gap-3 group focus:outline-none shrink-0"
          >
            {/* Geometric Skyline Icon */}
            <div className="w-10 h-10 rounded-xl bg-gradient-to-br from-[#1864E8] to-[#0B40A8] flex items-center justify-center text-white shadow-md shadow-blue-500/20 group-hover:scale-105 group-hover:shadow-blue-500/35 transition-all duration-300">
              <svg className="w-5.5 h-5.5 fill-current" viewBox="0 0 24 24">
                <path d="M3 21h18v-2H3v2zm3-4h3V5L6 7.5V17zm5 0h3V3l-3 2v12zm5 0h3v-8l-3 2v6z" />
              </svg>
            </div>
            <div className="flex flex-col">
              <div className="flex items-center gap-2">
                <span className="font-sans text-base font-extrabold tracking-wider text-[#0F172A] uppercase leading-tight group-hover:text-[#1864E8] transition-colors">
                  REAL ESTATE CRM
                </span>
              </div>
              <span className="text-[10px] tracking-[0.16em] text-[#64748B] uppercase font-mono font-bold">
                SALES &amp; PLATFORM
              </span>
            </div>
          </a>

          {/* Dynamic Center Navigation Links with Active Spy Pill */}
          <nav className="hidden lg:flex items-center gap-1 bg-slate-50/80 p-1.5 rounded-full border border-slate-200/70 shadow-2xs backdrop-blur-md">
            {NAV_LINKS.map((link) => {
              const isActive = activeSection === link.id;
              return (
                <a
                  key={link.href}
                  href={link.href}
                  onClick={(e) => handleSmoothScroll(e, link.href)}
                  className={`px-3.5 py-1.5 rounded-full text-xs font-semibold tracking-normal transition-all duration-200 relative ${
                    isActive
                      ? 'bg-white text-[#1864E8] shadow-xs font-bold border border-slate-200/60'
                      : 'text-[#475569] hover:text-[#0F172A] hover:bg-white/50'
                  }`}
                >
                  {link.label}
                  {isActive && (
                    <span className="absolute -bottom-0.5 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-[#1864E8]" />
                  )}
                </a>
              );
            })}
          </nav>

          {/* Right Area: Phone + CRM Login + Primary CTA Button */}
          <div className="hidden sm:flex items-center gap-3 lg:gap-4 shrink-0">
            <a
              href="tel:+919876543210"
              className="flex items-center gap-1.5 text-xs font-bold text-[#0F172A] hover:text-[#1864E8] transition-colors py-1.5 px-2 rounded-lg hover:bg-slate-50"
            >
              <Phone className="w-3.5 h-3.5 text-[#1864E8]" />
              <span>+91 98765 43210</span>
            </a>

            <a
              href="/admin/login"
              className="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold text-[#0F172A] hover:text-[#1864E8] bg-slate-50 hover:bg-slate-100 border border-slate-200/80 rounded-xl transition-all duration-200"
            >
              <span>CRM Login</span>
            </a>

            <button
              onClick={onStartDemo}
              className="group relative inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold text-white bg-[#1864E8] hover:bg-[#1351C0] active:bg-[#0E3D96] rounded-xl transition-all duration-200 cursor-pointer shadow-md shadow-blue-500/25 hover:shadow-lg hover:shadow-blue-500/35 hover:scale-[1.02] overflow-hidden"
            >
              <span className="relative z-10 flex items-center gap-1.5">
                <span>Get a Demo</span>
                <ArrowRight className="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" />
              </span>
              <div className="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 -translate-x-full group-hover:translate-x-full transition-transform duration-700 ease-in-out pointer-events-none" />
            </button>
          </div>

          {/* Mobile Hamburger Toggle & Quick Demo button */}
          <div className="flex lg:hidden items-center gap-2">
            <a
              href="/admin/login"
              className="px-2.5 py-1.5 text-[11px] font-bold text-[#0F172A] bg-slate-100 rounded-lg sm:hidden border border-slate-200"
            >
              Login
            </a>
            <button
              onClick={onStartDemo}
              className="px-3 py-1.5 text-xs font-bold text-white bg-[#1864E8] rounded-lg sm:hidden shadow-xs active:scale-95 transition-transform"
            >
              Demo
            </button>
            <button
              onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
              className="p-2 text-[#0F172A] hover:bg-slate-100 rounded-xl transition-colors cursor-pointer"
              aria-label="Toggle Navigation"
            >
              {mobileMenuOpen ? <X className="w-6 h-6 text-[#1864E8]" /> : <Menu className="w-6 h-6" />}
            </button>
          </div>
        </div>
      </div>

      {/* Dynamic Mobile Menu Dropdown with Backdrop Blur */}
      {mobileMenuOpen && (
        <div className="lg:hidden border-b border-slate-200 bg-white/98 backdrop-blur-2xl px-5 py-5 space-y-4 shadow-2xl animate-in slide-in-from-top-2 duration-200">
          <div className="flex items-center justify-between pb-2 border-b border-slate-100">
            <span className="text-[11px] font-bold uppercase tracking-wider text-[#64748B]">Navigation</span>
            <div className="flex items-center gap-1.5 text-[11px] font-semibold text-emerald-600">
              <span className="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
              <span>CRM Live Online</span>
            </div>
          </div>

          <nav className="flex flex-col space-y-1 text-sm font-semibold">
            {NAV_LINKS.map((link) => {
              const isActive = activeSection === link.id;
              return (
                <a
                  key={link.href}
                  href={link.href}
                  onClick={(e) => handleSmoothScroll(e, link.href)}
                  className={`flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-colors ${
                    isActive
                      ? 'bg-blue-50/80 text-[#1864E8] font-bold'
                      : 'text-[#334155] hover:bg-slate-50 hover:text-[#1864E8]'
                  }`}
                >
                  <span>{link.label}</span>
                  <ChevronRight className={`w-4 h-4 ${isActive ? 'text-[#1864E8]' : 'text-slate-300'}`} />
                </a>
              );
            })}
          </nav>

          <div className="pt-2 space-y-2.5 border-t border-slate-100">
            <a
              href="/admin/login"
              className="w-full py-2.5 text-xs font-bold text-[#0F172A] bg-slate-100 hover:bg-slate-200 rounded-xl flex items-center justify-center gap-2 border border-slate-200 transition-colors"
            >
              <span>CRM Login (Admin / Sales Portal)</span>
            </a>
            <a
              href="tel:+919876543210"
              className="flex items-center justify-center gap-2 text-xs font-bold text-[#0F172A] py-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors"
            >
              <Phone className="w-4 h-4 text-[#1864E8]" />
              <span>+91 98765 43210 (Direct Call)</span>
            </a>
            <button
              onClick={() => {
                setMobileMenuOpen(false);
                onStartDemo();
              }}
              className="w-full py-3 text-xs font-bold uppercase tracking-wider text-white bg-[#1864E8] hover:bg-[#1351C0] rounded-xl flex items-center justify-center gap-2 shadow-lg shadow-blue-500/25 active:scale-98 transition-all"
            >
              <span>Request a 15-Min Demo</span>
              <ArrowRight className="w-4 h-4" />
            </button>
          </div>
        </div>
      )}
    </header>
  );
};
