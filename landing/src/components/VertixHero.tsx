import React, { useState, useEffect, useRef } from 'react';
import {
  ChevronRight,
  ArrowRight,
  ShieldCheck,
  CheckCircle2,
  Zap,
} from 'lucide-react';

interface VertixHeroProps {
  onViewWork: () => void;
  onStartProject: () => void;
  onOpenCinematicReel?: () => void;
}

const HERO_SLIDES = [
  {
    id: 1,
    title: 'Stop Losing Leads. Close More Deals on WhatsApp & Email.',
    kicker: 'CENTRALIZED LEADS · INSTANT WHATSAPP · SITE VISITS',
    subhead:
      'Ditch messy spreadsheets and scattered chat apps. Centralize your property enquiries, track every deal on a visual board, schedule site visits, and automate customer follow-ups without technical headaches.',
    project: 'Centralized Property Enquiries · Real-Time WhatsApp & Email Sync',
  },
  {
    id: 2,
    title: 'Organize Site Visits & Sales Team Follow-ups',
    kicker: 'VISUAL PIPELINE · SITE VISIT SCHEDULING · MEETING NOTES',
    subhead:
      'Give sales executives clear daily follow-up priorities, schedule site visits with automated reminders, log buyer meeting notes, and manage token bookings on a visual board.',
    project: 'Site Visit Scheduling · Automated Follow-Up Reminders',
  },
  {
    id: 3,
    title: 'Secure Vault for All Customer & Buyer Data',
    kicker: 'ROLE ACCESS · DATA MASKING · EX-EMPLOYEE VAULT',
    subhead:
      'Protect your hard-earned client database. When sales executives leave, revoke access in 1 click while buyer records, notes, and past site visit history remain safely in your company vault.',
    project: 'Strict Role Permissions · Secure Buyer Data Vault',
  },
];

export const VertixHero: React.FC<VertixHeroProps> = ({
  onViewWork,
  onStartProject,
}) => {
  const [currentSlideIndex, setCurrentSlideIndex] = useState(0);
  const [slideDirection, setSlideDirection] = useState<'fwd' | 'bwd'>('fwd');
  const slide = HERO_SLIDES[currentSlideIndex];

  const [mousePosition, setMousePosition] = useState({ x: 0.5, y: 0.5 });
  const [isVideoPlaying, setIsVideoPlaying] = useState(true);
  const [isLoopTransitioning, setIsLoopTransitioning] = useState(false);
  const [isAutoSlidePaused, setIsAutoSlidePaused] = useState(false);

  const heroRef = useRef<HTMLDivElement>(null);
  const videoRef = useRef<HTMLVideoElement>(null);

  // Auto-change hero slides every 4 seconds in an infinite loop (pauses when user taps/clicks hero)
  useEffect(() => {
    if (isAutoSlidePaused) return;

    const timer = setInterval(() => {
      setSlideDirection('fwd');
      setCurrentSlideIndex((prev) => (prev === HERO_SLIDES.length - 1 ? 0 : prev + 1));
    }, 4000);

    return () => clearInterval(timer);
  }, [isAutoSlidePaused]);

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
      video.addEventListener('canplay', playVideo, { once: true });
    }

    // Single fallback unlock listener for strict browser autoplay policies
    const handleInitialAutoplayUnlock = () => {
      if (videoRef.current && videoRef.current.paused) {
        videoRef.current.muted = true;
        videoRef.current
          .play()
          .then(() => setIsVideoPlaying(true))
          .catch(() => {});
      }
    };

    window.addEventListener('click', handleInitialAutoplayUnlock, { once: true });
    window.addEventListener('touchstart', handleInitialAutoplayUnlock, { once: true });

    // Smooth loop listener
    const handleTimeUpdate = () => {
      if (!video || !video.duration) return;
      // Trigger soft seamless loop transition right before video ends
      if (video.duration > 1 && video.currentTime >= video.duration - 0.35) {
        setIsLoopTransitioning(true);
      } else if (video.currentTime < 0.35) {
        setIsLoopTransitioning(false);
      }
    };

    const handleEnded = () => {
      setIsLoopTransitioning(true);
      if (video) {
        video.currentTime = 0;
        video
          .play()
          .then(() => {
            setIsVideoPlaying(true);
            setTimeout(() => setIsLoopTransitioning(false), 300);
          })
          .catch(() => {});
      }
    };

    video.addEventListener('timeupdate', handleTimeUpdate);
    video.addEventListener('ended', handleEnded);

    return () => {
      video.removeEventListener('loadeddata', playVideo);
      video.removeEventListener('canplay', playVideo);
      video.removeEventListener('timeupdate', handleTimeUpdate);
      video.removeEventListener('ended', handleEnded);
      window.removeEventListener('click', handleInitialAutoplayUnlock);
      window.removeEventListener('touchstart', handleInitialAutoplayUnlock);
    };
  }, []);

  const handleMouseMove = (e: React.MouseEvent<HTMLDivElement>) => {
    if (!heroRef.current) return;
    const rect = heroRef.current.getBoundingClientRect();
    const x = (e.clientX - rect.left) / rect.width;
    const y = (e.clientY - rect.top) / rect.height;
    setMousePosition({ x, y });
  };

  const handleMouseLeave = () => {
    setMousePosition({ x: 0.5, y: 0.5 });
  };

  const handleToggleAutoSlide = () => {
    setIsAutoSlidePaused((prev) => !prev);
  };

  const handlePrev = (e?: React.MouseEvent) => {
    if (e) e.stopPropagation();
    setSlideDirection('bwd');
    setCurrentSlideIndex((prev) => (prev === 0 ? HERO_SLIDES.length - 1 : prev - 1));
  };

  const handleNext = (e?: React.MouseEvent) => {
    if (e) e.stopPropagation();
    setSlideDirection('fwd');
    setCurrentSlideIndex((prev) => (prev === HERO_SLIDES.length - 1 ? 0 : prev + 1));
  };

  return (
    <section
      id="home"
      ref={heroRef}
      onClick={handleToggleAutoSlide}
      onMouseMove={handleMouseMove}
      onMouseLeave={handleMouseLeave}
      className="relative min-h-[560px] sm:min-h-[600px] lg:min-h-[680px] w-full flex items-center overflow-hidden bg-[#0A0A0A] perspective-1200 border-b border-[#2A2A2A] cursor-pointer"
    >
      {/* LAYER 0: Instant Crisp Villa Poster */}
      <img
        src="/hero-villa-poster.jpg"
        alt="Luxury Modern Villa Architecture"
        className="absolute inset-0 w-full h-full object-cover object-center pointer-events-none z-0"
        loading="eager"
      />

      {/* LAYER 1: Remastered Web-Optimized Hero Background Video with Smooth Looping & Animated States */}
      <div className="absolute inset-0 z-0 overflow-hidden">
        <video
          ref={videoRef}
          src="/videos/hero-bg.mp4"
          poster="/hero-villa-poster.jpg"
          autoPlay
          muted
          loop
          playsInline
          preload="auto"
          onPlay={() => setIsVideoPlaying(true)}
          onPause={() => setIsVideoPlaying(false)}
          onError={() => {
            if (videoRef.current) {
              videoRef.current.style.display = 'none';
            }
          }}
          className={`absolute inset-0 w-full h-full object-cover object-center pointer-events-none transition-all duration-700 ${
            isLoopTransitioning ? 'opacity-90 scale-[1.01]' : 'opacity-100 scale-100'
          } ${isVideoPlaying ? 'filter saturate-105 brightness-100' : 'filter saturate-75 brightness-90'}`}
        >
          <source src="/videos/hero-bg.mp4" type="video/mp4" />
          Your browser does not support the video tag.
        </video>

        {/* Ambient Loop Shimmer Crossfade Animation */}
        <div
          className={`absolute inset-0 bg-black/40 pointer-events-none transition-opacity duration-500 ${
            isLoopTransitioning ? 'opacity-40' : 'opacity-0'
          }`}
        ></div>
      </div>

      {/* LAYER 2: High-Contrast Dark Gradient Scrim */}
      <div className="absolute inset-0 bg-gradient-to-r from-black/95 via-black/75 to-black/40 pointer-events-none z-10"></div>
      <div className="absolute inset-0 bg-gradient-to-t from-black/95 via-transparent to-black/50 pointer-events-none z-10"></div>

      {/* Subtle dynamic sheen */}
      <div
        className="absolute inset-0 pointer-events-none opacity-25 mix-blend-overlay transition-opacity duration-300 z-10"
        style={{
          background: `radial-gradient(circle at ${mousePosition.x * 100}% ${
            mousePosition.y * 100
          }%, rgba(255,255,255,0.3) 0%, transparent 60%)`,
        }}
      ></div>

      {/* LAYER 3: Main Content Area */}
      <div className="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full py-10 sm:py-12 lg:py-14 flex flex-col justify-between min-h-[520px] sm:min-h-[560px] lg:min-h-[620px]">
        {/* Top/Middle Area */}
        <div className="max-w-3xl space-y-4 sm:space-y-5 lg:space-y-6 text-white">
          {/* Tag Kicker */}
          <div key={`kicker-${slide.id}`} className="animate-cinematic-kicker">
            <span className="text-[11px] sm:text-xs font-bold tracking-[0.2em] sm:tracking-[0.25em] uppercase text-emerald-300 font-mono bg-black/70 px-3.5 py-1.5 rounded-full border border-emerald-400/40 inline-block shadow-md">
              {slide.kicker}
            </span>
          </div>

          {/* Display Headline with Directional Depth Animation */}
          <h1
            key={`title-${slide.id}`}
            className={`font-serif text-3xl sm:text-5xl lg:text-6xl xl:text-7xl font-medium text-white tracking-tight leading-[1.1] text-balance drop-shadow-md will-change-transform ${
              slideDirection === 'fwd' ? 'animate-cinematic-title-fwd' : 'animate-cinematic-title-bwd'
            }`}
          >
            {slide.title}
          </h1>

          {/* Subheading with Directional Parallax Animation */}
          <p
            key={`subhead-${slide.id}`}
            className={`text-sm sm:text-base lg:text-lg text-neutral-100 font-normal leading-relaxed max-w-2xl drop-shadow-sm will-change-transform ${
              slideDirection === 'fwd' ? 'animate-cinematic-subhead-fwd' : 'animate-cinematic-subhead-bwd'
            }`}
          >
            {slide.subhead}
          </p>

          {/* Action Buttons: Request Demo + View Interactive Workflow */}
          <div
            key={`buttons-${slide.id}`}
            className="pt-2 sm:pt-3 flex flex-wrap items-center gap-3.5 sm:gap-4 animate-cinematic-buttons will-change-transform"
          >
            {/* Primary High-Contrast Demo CTA */}
            <button
              onClick={(e) => {
                e.stopPropagation();
                onStartProject();
              }}
              className="inline-flex items-center gap-2.5 px-6 sm:px-7 py-3.5 sm:py-4 rounded-full bg-white text-black font-extrabold text-xs uppercase tracking-wider hover:bg-neutral-200 transition-all cursor-pointer shadow-2xl hover:scale-105"
            >
              <span>Request a 15-Min Demo</span>
              <ArrowRight className="w-4 h-4" />
            </button>

            {/* View Interactive Workflow */}
            <button
              onClick={(e) => {
                e.stopPropagation();
                onViewWork();
              }}
              className="group inline-flex items-center gap-2.5 sm:gap-3 text-xs sm:text-sm tracking-wider uppercase font-bold text-white hover:text-emerald-300 transition-all cursor-pointer focus:outline-none"
            >
              <div className="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-white/20 backdrop-blur-xs border-2 border-white/50 flex items-center justify-center text-white group-hover:bg-white group-hover:text-black transition-all shadow-md">
                <ChevronRight className="w-4 h-4 sm:w-5 sm:h-5 ml-0.5" />
              </div>
              <span>See How It Works</span>
            </button>
          </div>

          {/* Trust Badges Strip */}
          <div
            key={`badges-${slide.id}`}
            className="pt-3 sm:pt-4 flex flex-wrap items-center gap-4 sm:gap-6 text-[11px] sm:text-xs font-mono text-white font-bold border-t border-white/20 animate-cinematic-badges will-change-transform"
          >
            <span className="flex items-center gap-1.5 sm:gap-2">
              <CheckCircle2 className="w-3.5 h-3.5 sm:w-4 sm:h-4 text-emerald-400" />
              Official WhatsApp &amp; Email Sync
            </span>
            <span className="flex items-center gap-1.5 sm:gap-2">
              <ShieldCheck className="w-3.5 h-3.5 sm:w-4 sm:h-4 text-emerald-400" />
              Property &amp; Project Inventory
            </span>
            <span className="flex items-center gap-1.5 sm:gap-2">
              <Zap className="w-3.5 h-3.5 sm:w-4 sm:h-4 text-emerald-400" />
              Zero IT Skills Needed
            </span>
          </div>
        </div>

        {/* Bottom Bar */}
        <div className="pt-6 sm:pt-8 flex items-center justify-between text-white border-t border-white/20">
          <div
            key={`focus-${slide.id}`}
            className="flex items-center gap-2 text-xs text-neutral-200 font-mono tracking-wider animate-cinematic-focus will-change-transform"
          >
            <span className="hidden sm:inline text-neutral-400">Active Focus:</span>
            <span className="text-white font-bold">{slide.project}</span>
          </div>
        </div>
      </div>
    </section>
  );
};
