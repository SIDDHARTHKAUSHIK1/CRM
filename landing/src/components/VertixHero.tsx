import React, { useState, useEffect, useRef } from 'react';
import {
  ArrowRight,
  ShieldCheck,
  CheckCircle2,
  Zap,
  Play,
  TrendingUp,
  Calendar,
  MessageSquare,
  Building2,
  Sparkles,
} from 'lucide-react';

interface VertixHeroProps {
  onViewWork: () => void;
  onStartProject: () => void;
  onOpenCinematicReel?: () => void;
}

export const VertixHero: React.FC<VertixHeroProps> = ({
  onViewWork,
  onStartProject,
  onOpenCinematicReel,
}) => {
  const [isVideoPlaying, setIsVideoPlaying] = useState(true);
  const videoRef = useRef<HTMLVideoElement>(null);

  useEffect(() => {
    const video = videoRef.current;
    if (!video) return;

    video.defaultMuted = true;
    video.muted = true;
    video.playsInline = true;

    const playVideo = async () => {
      try {
        await video.play();
        setIsVideoPlaying(true);
      } catch (err) {
        console.warn('Hero video autoplay waiting for user interaction:', err);
      }
    };

    if (video.readyState >= 2) {
      playVideo();
    } else {
      video.addEventListener('loadeddata', playVideo, { once: true });
    }

    const unlock = () => {
      if (videoRef.current && videoRef.current.paused) {
        videoRef.current.play().catch(() => {});
      }
    };

    window.addEventListener('click', unlock, { once: true });
    window.addEventListener('touchstart', unlock, { once: true });

    return () => {
      window.removeEventListener('click', unlock);
      window.removeEventListener('touchstart', unlock);
    };
  }, []);

  return (
    <section
      id="home"
      className="relative min-h-[620px] sm:min-h-[680px] lg:min-h-[720px] w-full flex items-center overflow-hidden bg-[#0A0A0A] border-b-2 border-[#2A2A2A]"
    >
      {/* LAYER 0: Instant Crisp Villa Poster Fallback */}
      <img
        src="/hero-villa-poster.jpg"
        alt="Luxury Modern Architecture"
        className="absolute inset-0 w-full h-full object-cover object-center pointer-events-none z-0"
        loading="eager"
      />

      {/* LAYER 1: Background Video */}
      <div className="absolute inset-0 z-0 overflow-hidden">
        <video
          ref={videoRef}
          src="/videos/hero-bg.mp4"
          poster="/hero-villa-poster.jpg"
          autoPlay
          muted
          loop
          playsInline
          className="absolute inset-0 w-full h-full object-cover object-center pointer-events-none opacity-90 transition-opacity duration-700"
        >
          <source src="/videos/hero-bg.mp4" type="video/mp4" />
        </video>
      </div>

      {/* LAYER 2: High-Contrast Gradient Scrim */}
      <div className="absolute inset-0 bg-gradient-to-r from-black/95 via-black/80 to-black/50 pointer-events-none z-10"></div>
      <div className="absolute inset-0 bg-gradient-to-t from-black/95 via-transparent to-black/60 pointer-events-none z-10"></div>

      {/* LAYER 3: Main Visual Hero Layout */}
      <div className="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full py-12 lg:py-16">
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
          
          {/* Left Hero Content: Simple, Punchy, High-Impact */}
          <div className="lg:col-span-7 space-y-4 sm:space-y-5 text-white">
            {/* Tag Badge */}
            <div className="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-emerald-950/80 border border-emerald-500/40 text-emerald-300 text-[10px] font-mono tracking-widest uppercase font-bold shadow-md">
              <span className="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
              BUILT FOR INDIAN REAL ESTATE
            </div>

            {/* Headline */}
            <h1 className="font-serif text-3xl sm:text-5xl lg:text-6xl font-medium text-white tracking-tight leading-[1.1] text-balance drop-shadow-md">
              The Visual CRM That <span className="italic font-normal text-emerald-300">Closes Property Deals.</span>
            </h1>

            {/* Subheading */}
            <p className="text-sm sm:text-base lg:text-lg text-neutral-200 font-normal leading-relaxed max-w-xl">
              From portal &amp; WhatsApp lead capture to site visits, branded PDF cost sheets, and token bookings in ₹ Crores. Zero IT training needed.
            </p>

            {/* Action Buttons */}
            <div className="pt-2 flex flex-wrap items-center gap-3.5 sm:gap-4">
              <button
                onClick={onStartProject}
                className="inline-flex items-center gap-2.5 px-6 sm:px-7 py-3.5 sm:py-4 rounded-full bg-white text-black font-extrabold text-xs uppercase tracking-wider hover:bg-neutral-200 transition-all cursor-pointer shadow-2xl hover:scale-105"
              >
                <span>Request a 15-Min Demo</span>
                <ArrowRight className="w-4 h-4" />
              </button>

              <button
                onClick={onViewWork}
                className="group inline-flex items-center gap-2.5 px-5 py-3.5 rounded-full bg-white/10 hover:bg-white/20 text-white text-xs font-bold uppercase tracking-wider border border-white/20 backdrop-blur-xs transition-all cursor-pointer"
              >
                <span>See 6-Step Workflow</span>
                <ArrowRight className="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" />
              </button>

              {onOpenCinematicReel && (
                <button
                  onClick={onOpenCinematicReel}
                  className="inline-flex items-center gap-2 text-xs font-mono font-bold text-emerald-300 hover:text-emerald-100 transition-colors cursor-pointer py-2"
                >
                  <Play className="w-3.5 h-3.5 fill-current" />
                  <span>Watch 60s Reel</span>
                </button>
              )}
            </div>

            {/* Trust Integration Strip */}
            <div className="pt-4 border-t border-white/15 flex flex-wrap items-center gap-4 sm:gap-6 text-[11px] font-mono text-neutral-300">
              <span className="flex items-center gap-1.5 font-bold">
                <CheckCircle2 className="w-3.5 h-3.5 text-emerald-400" />
                99acres &amp; MagicBricks Sync
              </span>
              <span className="flex items-center gap-1.5 font-bold">
                <CheckCircle2 className="w-3.5 h-3.5 text-emerald-400" />
                WhatsApp 2-Way Chat
              </span>
              <span className="flex items-center gap-1.5 font-bold">
                <ShieldCheck className="w-3.5 h-3.5 text-emerald-400" />
                Protected Buyer Vault
              </span>
            </div>
          </div>

          {/* Right Floating Live CRM Preview Card */}
          <div className="lg:col-span-5">
            <div className="bg-[#121316]/95 rounded-2xl border-2 border-white/20 p-4 sm:p-5 shadow-2xl backdrop-blur-md space-y-3.5 text-white animate-fade-in-scale">
              {/* Header Bar */}
              <div className="flex items-center justify-between pb-3 border-b border-white/10">
                <div className="flex items-center gap-2">
                  <div className="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></div>
                  <span className="font-serif text-sm font-bold text-white">Live Pipeline Overview</span>
                </div>
                <span className="text-[10px] font-mono px-2 py-0.5 rounded bg-white/10 text-emerald-300 font-bold">
                  ₹42.85 Cr Active
                </span>
              </div>

              {/* 3 Quick Visual Status Tiles */}
              <div className="grid grid-cols-3 gap-2">
                <div className="bg-white/5 p-2 rounded-lg border border-white/10 text-center space-y-0.5">
                  <div className="text-[9px] font-mono text-neutral-400 uppercase">New Leads</div>
                  <div className="font-serif text-base font-bold text-white">148</div>
                </div>
                <div className="bg-white/5 p-2 rounded-lg border border-white/10 text-center space-y-0.5">
                  <div className="text-[9px] font-mono text-amber-400 uppercase">Site Visits</div>
                  <div className="font-serif text-base font-bold text-amber-300">28</div>
                </div>
                <div className="bg-white/5 p-2 rounded-lg border border-white/10 text-center space-y-0.5">
                  <div className="text-[9px] font-mono text-emerald-400 uppercase">Token Won</div>
                  <div className="font-serif text-base font-bold text-emerald-300">14</div>
                </div>
              </div>

              {/* Live Inbound Lead Item */}
              <div className="bg-emerald-950/40 p-3 rounded-xl border border-emerald-500/30 space-y-2">
                <div className="flex items-center justify-between text-[10px] font-mono">
                  <span className="text-emerald-400 font-bold flex items-center gap-1">
                    <MessageSquare className="w-3 h-3" /> WhatsApp Auto-Captured
                  </span>
                  <span className="text-neutral-400">Just Now</span>
                </div>
                <div className="flex justify-between items-center text-xs">
                  <div>
                    <div className="font-serif text-sm font-bold text-white">Vikramaditya Singhania</div>
                    <div className="text-[11px] text-neutral-300">3 BHK Villa · Sector 150</div>
                  </div>
                  <span className="font-mono text-xs font-bold text-emerald-400 bg-black/40 px-2 py-1 rounded">
                    ₹1.85 Cr
                  </span>
                </div>
              </div>

              {/* Live Site Walkthrough Reminder */}
              <div className="bg-white/5 p-2.5 rounded-xl border border-white/10 flex items-center justify-between text-xs">
                <div className="flex items-center gap-2">
                  <Calendar className="w-4 h-4 text-amber-400" />
                  <div>
                    <span className="font-bold text-white block text-[11px]">Today's Site Walkthrough</span>
                    <span className="text-[10px] text-neutral-400">11:30 AM · Sunil Narang</span>
                  </div>
                </div>
                <span className="text-[9px] font-mono text-amber-300 bg-amber-500/20 px-2 py-0.5 rounded font-bold">
                  Location Pin Sent
                </span>
              </div>

              {/* Quick Bottom Action */}
              <button
                onClick={onStartProject}
                className="w-full py-2.5 rounded-xl bg-white text-black font-extrabold text-xs uppercase tracking-wider hover:bg-neutral-200 transition-colors flex items-center justify-center gap-2"
              >
                <span>Explore Full Platform</span>
                <ArrowRight className="w-3.5 h-3.5" />
              </button>
            </div>
          </div>

        </div>
      </div>
    </section>
  );
};
