import React, { useState } from 'react';
import { RealEstateNavbar } from './components/RealEstateNavbar';
import { RealEstateHero } from './components/RealEstateHero';
import { RealEstateMarquee } from './components/RealEstateMarquee';
import { RealEstateSolutions } from './components/RealEstateSolutions';
import { RealEstateSpeedBanner } from './components/RealEstateSpeedBanner';
import { RealEstatePainVsRelief } from './components/RealEstatePainVsRelief';
import { RealEstateWorkflow } from './components/RealEstateWorkflow';
import { RealEstateDeviceCards } from './components/RealEstateDeviceCards';
import { RealEstateSoftwarePreview } from './components/RealEstateSoftwarePreview';
import { RealEstateCtaFaq } from './components/RealEstateCtaFaq';
import { RealEstateFooter } from './components/RealEstateFooter';
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
    <div className="min-h-screen bg-white text-[#0B1A30] flex flex-col font-sans selection:bg-[#1864E8] selection:text-white">
      {/* 1. Header & Top Announcement Bar matching reference layout */}
      <RealEstateNavbar onStartDemo={() => setIsInquiryOpen(true)} />

      {/* Main Page Sections */}
      <main className="flex-1">
        {/* 2. Hero Section: Headline + Floating Search/Filter Bar + 4-Icon Trust Row */}
        <RealEstateHero
          onExploreSolutions={() => handleScrollToSection('solutions')}
          onStartDemo={() => setIsInquiryOpen(true)}
        />

        {/* Section-scoped property and CRM scenes */}
        <div className="relative">
          {/* 3. Real Estate Solutions: "Built for Every Need" */}
          <RealEstateSolutions
            onSelectSolution={() => setIsInquiryOpen(true)}
          />

          {/* 4. Split Promo Valuation Banner: "Better Follow-ups. Better Outcomes." */}
          <RealEstateSpeedBanner onStartValuation={() => setIsInquiryOpen(true)} />

          {/* 5. Pain vs. Relief ("Is Your Sales Team Struggling with These Daily Bottlenecks?") */}
          <RealEstatePainVsRelief onStartDemo={() => setIsInquiryOpen(true)} />

          {/* 6. Workflow Journey: "A Smarter Way to Close" */}
          <RealEstateWorkflow />

          {/* 7. Inside the Software: Live Interactive CRM Showcase */}
          <RealEstateSoftwarePreview
            onStartDemo={() => setIsInquiryOpen(true)}
          />

          {/* 8. Device Mockup Cards: Mobile & Desktop Workspaces + Security & Roles */}
          <RealEstateDeviceCards
            onStartDemo={() => setIsInquiryOpen(true)}
          />

          {/* 9. Real Estate Multi-Channel Marquee & Performance Metrics */}
          <RealEstateMarquee />

          {/* 10. FAQ Accordion (Overcoming Objections) & Closing High-Converting CTA */}
          <RealEstateCtaFaq onStartDemo={() => setIsInquiryOpen(true)} />
        </div>
      </main>

      {/* 12. Deep Navy 5-Column Footer */}
      <RealEstateFooter />

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
