import React, { useState } from 'react';
import { RealEstateMotionBackground } from './RealEstateMotionBackground';
import { ArrowRight, Heart, Bed, Bath, Square, ChevronRight, MapPin } from 'lucide-react';

interface RealEstateProjectsProps {
  onStartDemo: () => void;
}

export const RealEstateProjects: React.FC<RealEstateProjectsProps> = ({ onStartDemo }) => {
  const [favorites, setFavorites] = useState<{ [key: string]: boolean }>({});

  const toggleFavorite = (id: string, e: React.MouseEvent) => {
    e.stopPropagation();
    setFavorites(prev => ({ ...prev, [id]: !prev[id] }));
  };

  const PROJECTS = [
    {
      id: 'godrej-palm',
      badge: 'FOR SALE',
      badgeType: 'sale',
      categoryBadge: 'Luxury Villa Plots',
      price: '₹1.85 Cr',
      title: 'Godrej Palm Retreat',
      location: 'Sector 150, Noida',
      description: 'Auto-assigned 180+ portal enquiries and closed 14 token bookings in 30 days.',
      beds: '4 BHK',
      baths: '3 Baths',
      sqft: '2,450 Sq Ft',
      image: '/images/properties/property-1.jpg',
    },
    {
      id: 'dlf-arbour',
      badge: 'FOR SALE',
      badgeType: 'sale',
      categoryBadge: '4 BHK Luxury',
      price: '₹3.20 Cr',
      title: 'DLF The Arbour',
      location: 'Golf Course Ext, Gurugram',
      description: 'Reduced cost sheet turnaround time from 3 hours to 45 seconds on WhatsApp.',
      beds: '4 BHK',
      baths: '4 Baths',
      sqft: '3,200 Sq Ft',
      image: '/images/properties/property-2.jpg',
    },
    {
      id: 'lodha-parkside',
      badge: 'READY TO MOVE',
      badgeType: 'ready',
      categoryBadge: 'Sea-Facing Tower',
      price: '₹4.50 Cr',
      title: 'Lodha Parkside',
      location: 'Worli, Mumbai',
      description: 'Automated site walkthrough bookings with 0 missed buyer follow-ups.',
      beds: '3 BHK',
      baths: '3 Baths',
      sqft: '2,100 Sq Ft',
      image: '/images/properties/property-3.jpg',
    },
    {
      id: 'prestige-city',
      badge: 'FOR SALE',
      badgeType: 'sale',
      categoryBadge: 'Turnkey Villas',
      price: '₹2.80 Cr',
      title: 'Prestige City Villas',
      location: 'Sarjapur, Bengaluru',
      description: 'Integrated CP brokerage network with instant digital token receipt generation.',
      beds: '4 BHK',
      baths: '4 Baths',
      sqft: '3,100 Sq Ft',
      image: '/images/properties/property-4.jpg',
    },
  ];

  return (
    <section id="projects" className="isolate py-14 sm:py-20 bg-white/85 backdrop-blur-xs border-b border-slate-200/80 relative overflow-hidden">
      <RealEstateMotionBackground variant="properties" />
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 relative z-10">
        
        {/* Section Header */}
        <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
          <div className="space-y-1">
            <span className="text-[11px] font-extrabold tracking-[0.2em] text-[#1864E8] uppercase">
              FEATURED REAL ESTATE PROJECTS
            </span>
            <h2 className="text-2xl sm:text-3xl lg:text-4xl font-black text-[#0B1A30] tracking-tight">
              Real Projects. Real Results.
            </h2>
          </div>

          <button
            onClick={onStartDemo}
            className="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-[#1864E8] hover:text-[#0B1A30] transition-colors self-start sm:self-auto group cursor-pointer"
          >
            <span>View All Projects</span>
            <ArrowRight className="w-4 h-4 group-hover:translate-x-1 transition-transform" />
          </button>
        </div>

        {/* 4-Card Property Grid */}
        <div className="relative">
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {PROJECTS.map((prop) => (
              <div
                key={prop.id}
                onClick={onStartDemo}
                className="bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 group cursor-pointer flex flex-col justify-between"
              >
                <div>
                  {/* Property Image Container */}
                  <div className="relative aspect-[16/11] overflow-hidden bg-slate-900">
                    <img
                      src={prop.image}
                      alt={prop.title}
                      className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                      loading="lazy"
                    />
                    
                    {/* Badge Pill on Top Left */}
                    <div className="absolute top-3 left-3">
                      <span className={`text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-md shadow-xs ${
                        prop.badgeType === 'sale'
                          ? 'bg-[#1864E8] text-white'
                          : 'bg-emerald-600 text-white'
                      }`}>
                        {prop.badge}
                      </span>
                    </div>

                    {/* Favorite Heart on Top Right */}
                    <button
                      onClick={(e) => toggleFavorite(prop.id, e)}
                      className="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/80 backdrop-blur-md flex items-center justify-center text-slate-700 hover:text-rose-600 transition-colors shadow-xs"
                      aria-label="Save Property"
                    >
                      <Heart className={`w-4 h-4 ${favorites[prop.id] ? 'fill-rose-500 text-rose-500' : ''}`} />
                    </button>
                  </div>

                  {/* Card Content Details */}
                  <div className="p-4 sm:p-5 space-y-2.5">
                    {/* Price & Category Pill */}
                    <div className="flex items-center justify-between gap-2">
                      <div className="text-lg sm:text-xl font-black text-[#0B1A30]">
                        {prop.price}
                      </div>
                      <span className="text-[10px] font-mono font-bold px-2 py-0.5 rounded-md bg-blue-50 text-[#1864E8] border border-blue-100">
                        {prop.categoryBadge}
                      </span>
                    </div>

                    {/* Project Title & Location */}
                    <div>
                      <h3 className="font-bold text-sm sm:text-base text-[#0B1A30] truncate group-hover:text-[#1864E8] transition-colors">
                        {prop.title}
                      </h3>
                      <div className="flex items-center gap-1 text-[11px] text-[#64748B] font-medium mt-0.5">
                        <MapPin className="w-3 h-3 text-[#1864E8] shrink-0" />
                        <span className="truncate">{prop.location}</span>
                      </div>
                    </div>

                    {/* Description */}
                    <p className="text-xs text-[#475569] leading-relaxed pt-1 line-clamp-2">
                      {prop.description}
                    </p>
                  </div>
                </div>

                {/* Bottom Specifications Bar */}
                <div className="px-4 sm:px-5 py-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-[#64748B] font-semibold bg-slate-50/50">
                  <div className="flex items-center gap-1">
                    <Bed className="w-3.5 h-3.5 text-slate-400" />
                    <span>{prop.beds}</span>
                  </div>
                  <div className="flex items-center gap-1">
                    <Bath className="w-3.5 h-3.5 text-slate-400" />
                    <span>{prop.baths}</span>
                  </div>
                  <div className="flex items-center gap-1">
                    <Square className="w-3.5 h-3.5 text-slate-400" />
                    <span>{prop.sqft}</span>
                  </div>
                </div>
              </div>
            ))}
          </div>

          {/* Floating Right Arrow for Carousel visual accent */}
          <button
            onClick={onStartDemo}
            className="hidden lg:flex absolute -right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white border border-slate-200 shadow-xl items-center justify-center text-[#0B1A30] hover:text-[#1864E8] hover:scale-110 transition-all z-20 cursor-pointer"
            aria-label="Next properties"
          >
            <ChevronRight className="w-5 h-5" />
          </button>
        </div>

      </div>
    </section>
  );
};
