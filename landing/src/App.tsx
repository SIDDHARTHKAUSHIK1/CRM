import React, { useState } from 'react';
import { VertixNavbar } from './components/VertixNavbar';
import { VertixHero } from './components/VertixHero';
import { VertixAudience } from './components/VertixAudience';
import { VertixInteractivePipeline } from './components/VertixInteractivePipeline';
import { VertixFeatures } from './components/VertixFeatures';
import { VertixResults } from './components/VertixResults';
import { VertixTestimonials } from './components/VertixTestimonials';
import { VertixFaq } from './components/VertixFaq';
import { VertixContact } from './components/VertixContact';
import { VertixFooter } from './components/VertixFooter';
import { InquiryModal } from './components/InquiryModal';
import { CinematicReelModal } from './components/CinematicReelModal';

export default function App() {
  const [isInquiryOpen, setIsInquiryOpen] = useState(false);
  const [isCinematicReelOpen, setIsCinematicReelOpen] = useState(false);

  const handleScrollToSection = (sectionId: string) => {
    const el = document.getElementById(sectionId);
    if (el) el.scrollIntoView({ behavior: 'smooth' });
  };

  return (
    <div className="min-h-screen bg-[#F8F7F4] text-[#1A1A1A] flex flex-col font-sans selection:bg-[#1A1A1A] selection:text-white">
      {/* 1. Top Navigation Bar */}
      <VertixNavbar onStartProject={() => setIsInquiryOpen(true)} />

      {/* Main Page Flow */}
      <main className="flex-1">
        {/* 2. Hero Section with Video Background, 3D Spatial Chip & Clear Value Proposition */}
        <VertixHero
          onViewWork={() => handleScrollToSection('workflow')}
          onStartProject={() => setIsInquiryOpen(true)}
          onOpenCinematicReel={() => setIsCinematicReelOpen(true)}
        />

        {/* 3. Who Can Use It (Builders, Brokerages, CP Networks, Land & Commercial) */}
        <VertixAudience />

        {/* 4. Visual Workflow / Lead to Deal Journey (Interactive 5-Step Pipeline Simulator) */}
        <VertixInteractivePipeline onStartDemo={() => setIsInquiryOpen(true)} />

        {/* 5. Core Features & Capabilities */}
        <VertixFeatures />

        {/* 6. Tangible Business Benefits / ROI & Interactive Revenue Recovery Calculator */}
        <VertixResults onStartDemo={() => setIsInquiryOpen(true)} />

        {/* 7. Verified Customer Testimonials & Case Studies */}
        <VertixTestimonials />

        {/* 8. Frequently Asked Questions (Non-Technical Clarity) */}
        <VertixFaq onStartDemo={() => setIsInquiryOpen(true)} />

        {/* 9. Final CTA & Request Demo Section */}
        <VertixContact />
      </main>

      {/* 10. Minimalist Dark Editorial Footer */}
      <VertixFooter />

      {/* Interactive 15-Minute Demo Booking Modal */}
      <InquiryModal
        isOpen={isInquiryOpen}
        onClose={() => setIsInquiryOpen(false)}
      />

      {/* 4K Cinematic Video Reel Modal */}
      <CinematicReelModal
        isOpen={isCinematicReelOpen}
        onClose={() => setIsCinematicReelOpen(false)}
        onRequestDemo={() => setIsInquiryOpen(true)}
      />
    </div>
  );
}
