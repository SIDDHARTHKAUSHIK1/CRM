import React from 'react';
import { RealEstateMotionBackground } from './RealEstateMotionBackground';
import { ArrowRight } from 'lucide-react';

interface RealEstateSpeedBannerProps {
  onStartValuation?: () => void;
}

export const RealEstateSpeedBanner: React.FC<RealEstateSpeedBannerProps> = ({ onStartValuation }) => {
  return (
    <section id="efficiency" className="isolate py-8 sm:py-12 bg-white relative overflow-hidden">
      <RealEstateMotionBackground variant="pipeline" />
      <div className="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Compact Split Promo Video Banner Container */}
        <div className="bg-[#0B1A30] rounded-2xl sm:rounded-3xl overflow-hidden grid grid-cols-1 lg:grid-cols-12 shadow-xl items-stretch">
          
          {/* Left Dark Navy Column - Sleek & Compact */}
          <div className="lg:col-span-6 p-6 sm:p-8 lg:p-10 flex flex-col justify-center space-y-3 sm:space-y-4">
            <div className="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full bg-blue-900/60 border border-blue-500/30 text-blue-300 text-[10px] font-mono font-bold tracking-widest uppercase self-start">
              <span>SPEED &amp; EFFICIENCY</span>
            </div>

            <h2 className="text-xl sm:text-3xl lg:text-[32px] font-black text-white tracking-tight leading-tight">
              Better Follow-ups. <br />
              <span className="text-[#38BDF8]">Better Outcomes.</span>
            </h2>

            <p className="text-xs sm:text-sm text-slate-300 leading-relaxed max-w-md">
              Automated 60-second WhatsApp lead response, live calendar site-visit bookings, and instant digital token receipts engineered for Indian sales teams.
            </p>

            <div className="pt-1">
              <button
                onClick={onStartValuation}
                className="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-white hover:bg-slate-100 text-[#0B1A30] font-bold text-xs sm:text-sm transition-all cursor-pointer shadow-md hover:shadow-lg hover:scale-[1.02]"
              >
                <span>Get a Free Demo</span>
                <ArrowRight className="w-3.5 h-3.5" />
              </button>
            </div>
          </div>

          {/* Right Column: Looping Cinematic Video from Videos Folder */}
          <div className="lg:col-span-6 relative min-h-[220px] sm:min-h-[260px] lg:min-h-[300px] bg-slate-900 overflow-hidden">
            <video
              autoPlay
              loop
              muted
              playsInline
              poster="/images/properties/property-3.jpg"
              className="w-full h-full object-cover"
            >
              <source src="/videos/hero04.mp4" type="video/mp4" />
              <source src="/videos/use-case-bg.mp4" type="video/mp4" />
            </video>

            {/* Subtle Gradient Over Video */}
            <div className="absolute inset-0 bg-gradient-to-r from-[#0B1A30]/60 via-transparent to-transparent lg:hidden pointer-events-none"></div>
          </div>

        </div>

      </div>
    </section>
  );
};
