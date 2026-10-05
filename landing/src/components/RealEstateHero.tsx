import React from 'react';
import {
  ArrowRight,
  Play,
} from 'lucide-react';

interface RealEstateHeroProps {
  onExploreSolutions: () => void;
  onStartDemo: () => void;
}

export const RealEstateHero: React.FC<RealEstateHeroProps> = ({
  onExploreSolutions,
  onStartDemo,
}) => {

  return (
    <section id="home" className="relative bg-white min-h-[calc(100vh-4.5rem)] sm:min-h-[calc(100vh-5rem)] flex items-center overflow-hidden">
      
      {/* ========================================================================= */}
      {/* FULL-HEIGHT ULTRA HD LUXURY VILLA SHOWCASE BACKGROUND                     */}
      {/* ========================================================================= */}
      <div className="absolute inset-y-0 right-0 w-full md:w-[62%] lg:w-[58%] xl:w-[56%] 2xl:w-[55%] h-full pointer-events-none z-0 overflow-hidden">
        <picture className="block w-full h-full">
          <source
            type="image/webp"
            srcSet="/images/hero-villa-premium-4k-lossless.webp"
          />
          <img
            src="/images/hero-villa-premium-4k-hq.jpg"
            width={3840}
            height={2160}
            alt="Contemporary luxury villa with warm interiors, landscaped gardens, and a turquoise swimming pool"
            className="block w-full h-full object-cover object-center lg:object-right"
            loading="eager"
            fetchPriority="high"
            decoding="async"
          />
        </picture>
        {/* Sleek, subtle edge blend leaving the villa crystal clear and fully visible */}
        <div className="absolute inset-y-0 left-0 w-20 sm:w-28 lg:w-40 bg-gradient-to-r from-white via-white/35 to-transparent pointer-events-none"></div>
        <div className="absolute inset-x-0 bottom-0 h-16 sm:h-24 bg-gradient-to-t from-white/90 via-white/30 to-transparent pointer-events-none"></div>
        <div className="absolute inset-x-0 top-0 h-12 sm:h-16 bg-gradient-to-b from-white/80 via-white/20 to-transparent pointer-events-none"></div>
      </div>

      <div className="max-w-[1400px] 2xl:max-w-[1600px] w-full mx-auto px-4 sm:px-6 lg:px-10 xl:px-12 2xl:px-16 relative z-10 py-12 sm:py-16 lg:py-20">
        
        {/* Hero Top Content: Left Column */}
        <div className="max-w-xl lg:max-w-2xl 2xl:max-w-3xl space-y-5 sm:space-y-7">
          
          {/* Main Headline */}
          <div className="space-y-1">
            <h1 className="text-4xl sm:text-6xl lg:text-[66px] 2xl:text-[78px] font-black text-[#0B1A30] tracking-tight leading-[1.04]">
              Close Deals. <br />
              <span className="text-[#1864E8]">Sell Faster.</span>
            </h1>
          </div>

          {/* Subtitle Description */}
          <p className="text-sm sm:text-base 2xl:text-lg text-[#475569] font-normal leading-relaxed max-w-lg 2xl:max-w-xl antialiased">
            Premium real estate sales CRM engineered for Indian builders, developers, brokerages, and CP networks to automate WhatsApp enquiries, site visits, and token bookings.
          </p>

          {/* Dual Action Buttons */}
          <div className="flex flex-wrap items-center gap-4 pt-2">
            <button
              onClick={onExploreSolutions}
              className="inline-flex items-center gap-2.5 px-7 py-3.5 2xl:px-8 2xl:py-4 rounded-xl bg-[#0B1A30] hover:bg-[#1864E8] text-white font-bold text-sm 2xl:text-base transition-all cursor-pointer shadow-md hover:shadow-xl hover:scale-[1.02]"
            >
              <span>Explore Solutions</span>
              <ArrowRight className="w-4 h-4 2xl:w-5 2xl:h-5" />
            </button>

            <button
              onClick={onStartDemo}
              className="inline-flex items-center gap-2 px-6 py-3.5 2xl:px-7 2xl:py-4 rounded-xl bg-white hover:bg-slate-50 text-[#0B1A30] hover:text-[#1864E8] font-bold text-sm 2xl:text-base border border-slate-300 transition-all cursor-pointer shadow-xs hover:shadow-sm"
            >
              <span>Get a Free Demo</span>
              <Play className="w-3.5 h-3.5 fill-current ml-1" />
            </button>
          </div>

        </div>

      </div>
    </section>
  );
};
