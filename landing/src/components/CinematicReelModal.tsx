import React, { useState, useEffect, useRef } from 'react';
import {
  X,
  Play,
  Pause,
  Volume2,
  VolumeX,
  RotateCcw,
  Sparkles,
  Maximize2,
  Minimize2,
  ChevronRight,
  CheckCircle2,
} from 'lucide-react';

interface CinematicReelModalProps {
  isOpen: boolean;
  onClose: () => void;
  onRequestDemo: () => void;
}

const CINEMATIC_SCENES = [
  {
    time: 0,
    endTime: 8,
    chapter: '01 / 04',
    title: 'Act I: The Property Inbound Spark',
    location: 'Golf Course Extension Villa · 18:42 Twilight',
    narrative: 'A high-net-worth buyer inquires via WhatsApp while reviewing your project brochure. Instant intake logs the enquiry in under 60 seconds.',
    hud: {
      type: 'lead',
      title: 'New WhatsApp Property Lead',
      value: 'Vikram Malhotra · Budget ₹2.85 Crore',
      badge: 'Auto-Logged to New Enquiries',
    },
    bgImage:
      'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=2400&q=90',
    cameraMotion: 'scale-110 translate-x-2 -translate-y-2',
  },
  {
    time: 8,
    endTime: 16,
    chapter: '02 / 04',
    title: 'Act II: Instant WhatsApp Brochure & Site Visit',
    location: 'Sector 150 Project Pavilion · Cloud Sync',
    narrative: 'No switching apps. The sales executive replies directly inside Real Estate CRM. The buyer receives floor plans and a calendar invite for a weekend site visit.',
    hud: {
      type: 'chat',
      title: 'Site Visit Confirmed',
      value: 'Sunday 11:00 AM · Green Valley Plots',
      badge: 'WhatsApp Calendar Invite Sent',
    },
    bgImage:
      'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=2400&q=90',
    cameraMotion: 'scale-115 -translate-x-3 translate-y-1',
  },
  {
    time: 16,
    endTime: 24,
    chapter: '03 / 04',
    title: 'Act III: The 30-Second PDF Cost Sheet',
    location: 'Sales Gallery Terrace · Instant Quotation',
    narrative: 'Select unit, apply approved festival discount, and generate an itemized PDF cost sheet with milestone payment schedule directly to WhatsApp.',
    hud: {
      type: 'quote',
      title: 'Cost Sheet #1084 Delivered',
      value: '₹85 Lakh (Construction Linked Plan)',
      badge: 'Delivered via WhatsApp PDF',
    },
    bgImage:
      'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=2400&q=90',
    cameraMotion: 'scale-110 translate-y-3 -translate-x-1',
  },
  {
    time: 24,
    endTime: 32,
    chapter: '04 / 04',
    title: 'Act IV: Token Booking & Pipeline Retained',
    location: 'Central Developer Board · Deal Closed',
    narrative: 'Token advance received. The deal transitions to Won, forecasts update in real time, and buyer contacts remain protected in your company vault.',
    hud: {
      type: 'won',
      title: 'Token Booking Confirmed',
      value: 'Total Pipeline: ₹18.4 Crore Active',
      badge: 'Zero Lost Leads · Strict Role Vault',
    },
    bgImage:
      'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=2400&q=90',
    cameraMotion: 'scale-105 translate-x-1 -translate-y-1',
  },
];

const TOTAL_DURATION = 32;

export const CinematicReelModal: React.FC<CinematicReelModalProps> = ({
  isOpen,
  onClose,
  onRequestDemo,
}) => {
  const [isPlaying, setIsPlaying] = useState(true);
  const [currentTime, setCurrentTime] = useState(0);
  const [isMuted, setIsMuted] = useState(false);
  const [isFullscreen, setIsFullscreen] = useState(false);
  const containerRef = useRef<HTMLDivElement>(null);

  // Playback timer
  useEffect(() => {
    let timer: NodeJS.Timeout;
    if (isOpen && isPlaying) {
      timer = setInterval(() => {
        setCurrentTime((prev) => {
          if (prev >= TOTAL_DURATION - 0.2) {
            return 0; // loop reel
          }
          return Number((prev + 0.2).toFixed(1));
        });
      }, 200);
    }
    return () => clearInterval(timer);
  }, [isOpen, isPlaying]);

  if (!isOpen) return null;

  const currentScene =
    CINEMATIC_SCENES.find(
      (s) => currentTime >= s.time && currentTime < s.endTime
    ) || CINEMATIC_SCENES[0];

  const formatSeconds = (sec: number) => {
    const m = Math.floor(sec / 60);
    const s = Math.floor(sec % 60);
    return `0${m}:${s < 10 ? '0' : ''}${s}`;
  };

  const progressPercent = (currentTime / TOTAL_DURATION) * 100;

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4 md:p-6 bg-black/95 backdrop-blur-md animate-in fade-in duration-300">
      <div
        ref={containerRef}
        className={`relative w-full ${
          isFullscreen ? 'h-full max-w-none' : 'max-w-5xl aspect-[16/10] sm:aspect-[16/9]'
        } bg-[#0A0A0A] text-white overflow-hidden shadow-2xl border border-white/15 flex flex-col justify-between select-none`}
        onClick={(e) => e.stopPropagation()}
      >
        {/* Cinematic 2.39:1 Anamorphic Letterbox Bars (Top & Bottom subtle overlays) */}
        <div className="absolute top-0 inset-x-0 h-6 sm:h-10 bg-gradient-to-b from-black via-black/80 to-transparent z-20 pointer-events-none"></div>
        <div className="absolute bottom-0 inset-x-0 h-16 sm:h-24 bg-gradient-to-t from-black via-black/90 to-transparent z-20 pointer-events-none"></div>

        {/* Ambient Film Grain & Vignette */}
        <div className="absolute inset-0 bg-radial from-transparent via-black/30 to-black/80 z-10 pointer-events-none"></div>

        {/* Top Control Bar */}
        <div className="relative z-30 p-4 sm:p-6 flex items-center justify-between text-xs">
          <div className="flex items-center gap-3">
            <div className="flex items-center gap-2 px-2.5 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20">
              <span className="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
              <span className="font-mono text-[10px] tracking-widest uppercase text-white font-bold">
                4K HDR · CINEMATIC WALKTHROUGH
              </span>
            </div>
            <span className="hidden sm:inline font-mono text-white/50 text-[11px]">
              {currentScene.chapter}
            </span>
          </div>

          <div className="flex items-center gap-3">
            <button
              onClick={() => setIsMuted(!isMuted)}
              className="p-2 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 text-white transition-colors cursor-pointer"
              title={isMuted ? 'Unmute' : 'Mute'}
            >
              {isMuted ? <VolumeX className="w-4 h-4" /> : <Volume2 className="w-4 h-4 text-emerald-400" />}
            </button>
            <button
              onClick={() => setIsFullscreen(!isFullscreen)}
              className="hidden sm:block p-2 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 text-white transition-colors cursor-pointer"
              title="Fullscreen"
            >
              {isFullscreen ? <Minimize2 className="w-4 h-4" /> : <Maximize2 className="w-4 h-4" />}
            </button>
            <button
              onClick={onClose}
              className="p-2 rounded-full bg-white/20 hover:bg-white/30 backdrop-blur-md border border-white/30 text-white transition-colors cursor-pointer"
              aria-label="Close video"
            >
              <X className="w-4 h-4" />
            </button>
          </div>
        </div>

        {/* 3D Motion Video Stage (Simulated high-res cinematic pan with Ken Burns drift) */}
        <div className="absolute inset-0 z-0 overflow-hidden">
          {CINEMATIC_SCENES.map((scene, idx) => {
            const isActive = currentScene.time === scene.time;
            return (
              <div
                key={idx}
                className={`absolute inset-0 transition-opacity duration-1000 ease-in-out ${
                  isActive ? 'opacity-100 z-1' : 'opacity-0 z-0 pointer-events-none'
                }`}
              >
                <img
                  src={scene.bgImage}
                  alt={scene.title}
                  referrerPolicy="no-referrer"
                  className={`w-full h-full object-cover transform duration-[8000ms] ease-out ${
                    isActive ? scene.cameraMotion : 'scale-100'
                  }`}
                />
                {/* Architectural Dark Gradient Scrim */}
                <div className="absolute inset-0 bg-gradient-to-t from-black/85 via-black/40 to-black/30"></div>
                <div className="absolute inset-0 bg-gradient-to-r from-black/80 via-transparent to-black/40"></div>
              </div>
            );
          })}
        </div>

        {/* Dynamic 3D Spatial Holographic HUD Overlay */}
        <div className="relative z-30 px-6 sm:px-10 my-auto grid grid-cols-1 lg:grid-cols-12 gap-8 items-end">
          {/* Left Narrative Text */}
          <div className="lg:col-span-8 space-y-3">
            <div className="inline-flex items-center gap-2 font-mono text-[10px] tracking-[0.25em] uppercase text-emerald-400 font-bold">
              <span className="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
              {currentScene.location}
            </div>

            <h2 className="font-serif text-3xl sm:text-5xl text-white font-light tracking-tight leading-none text-balance">
              {currentScene.title}
            </h2>

            <p className="text-xs sm:text-sm text-white/80 max-w-xl font-light leading-relaxed">
              {currentScene.narrative}
            </p>
          </div>

          {/* Right Floating 3D Spatial CRM Card */}
          <div className="lg:col-span-4 flex justify-start lg:justify-end">
            <div className="p-4 rounded-xl bg-black/60 backdrop-blur-xl border border-white/20 shadow-2xl space-y-2 transform hover:scale-105 transition-all max-w-xs w-full">
              <div className="flex items-center justify-between text-[11px] text-white/60 font-mono">
                <span className="flex items-center gap-1.5 text-emerald-400 font-semibold">
                  <Sparkles className="w-3 h-3" />
                  Live System State
                </span>
                <span>Active</span>
              </div>
              <div className="text-xs font-bold text-white leading-tight">
                {currentScene.hud.title}
              </div>
              <div className="text-sm font-serif text-emerald-300 font-semibold">
                {currentScene.hud.value}
              </div>
              <div className="pt-2 border-t border-white/10 text-[10px] font-mono text-white/70 flex items-center gap-1.5">
                <CheckCircle2 className="w-3 h-3 text-emerald-400 shrink-0" />
                <span>{currentScene.hud.badge}</span>
              </div>
            </div>
          </div>
        </div>

        {/* Bottom Playback HUD Bar */}
        <div className="relative z-30 p-4 sm:p-6 space-y-3">
          {/* Chapter Quick Jump Markers */}
          <div className="flex items-center justify-between gap-2 overflow-x-auto pb-1 text-[11px] font-mono">
            {CINEMATIC_SCENES.map((scene, idx) => (
              <button
                key={idx}
                onClick={() => setCurrentTime(scene.time)}
                className={`flex-1 py-1.5 px-2 rounded text-left transition-all whitespace-nowrap cursor-pointer ${
                  currentScene.time === scene.time
                    ? 'bg-white/20 text-white font-semibold border-b-2 border-emerald-400'
                    : 'text-white/40 hover:text-white/80 hover:bg-white/5'
                }`}
              >
                <span className="block text-[9px] opacity-75">{scene.chapter}</span>
                <span className="truncate block">{scene.title.split(':')[1] || scene.title}</span>
              </button>
            ))}
          </div>

          {/* Progress Scrubber */}
          <div
            className="w-full h-1.5 bg-white/20 rounded-full cursor-pointer relative overflow-hidden"
            onClick={(e) => {
              const rect = e.currentTarget.getBoundingClientRect();
              const pos = (e.clientX - rect.left) / rect.width;
              setCurrentTime(Number((pos * TOTAL_DURATION).toFixed(1)));
            }}
          >
            <div
              className="h-full bg-emerald-400 rounded-full transition-all duration-150"
              style={{ width: `${progressPercent}%` }}
            ></div>
          </div>

          {/* Controls & Actions */}
          <div className="flex items-center justify-between text-xs">
            <div className="flex items-center gap-3">
              <button
                onClick={() => setIsPlaying(!isPlaying)}
                className="w-8 h-8 rounded-full bg-white text-black flex items-center justify-center hover:bg-white/90 transition-colors cursor-pointer"
              >
                {isPlaying ? <Pause className="w-4 h-4 fill-black" /> : <Play className="w-4 h-4 fill-black ml-0.5" />}
              </button>
              <button
                onClick={() => setCurrentTime(0)}
                className="p-1.5 text-white/70 hover:text-white transition-colors cursor-pointer"
                title="Replay from start"
              >
                <RotateCcw className="w-3.5 h-3.5" />
              </button>
              <span className="font-mono text-white/60 text-[11px]">
                {formatSeconds(currentTime)} <span className="text-white/30">/</span> {formatSeconds(TOTAL_DURATION)}
              </span>
            </div>

            <div className="flex items-center gap-3">
              <button
                onClick={() => {
                  onClose();
                  onRequestDemo();
                }}
                className="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold uppercase tracking-wider text-black bg-white hover:bg-neutral-200 rounded-full transition-all cursor-pointer shadow-lg"
              >
                <span>Request a Demo</span>
                <ChevronRight className="w-3.5 h-3.5" />
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
