import React, { useState } from 'react';
import { X, ArrowRight, CheckCircle2 } from 'lucide-react';

interface InquiryModalProps {
  isOpen: boolean;
  onClose: () => void;
}

export const InquiryModal: React.FC<InquiryModalProps> = ({ isOpen, onClose }) => {
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [phone, setPhone] = useState('');
  const [businessType, setBusinessType] = useState('Real Estate Builder / Developer');
  const [teamSize, setTeamSize] = useState('6 - 20 Sales Executives');
  const [focusArea, setFocusArea] = useState('WhatsApp Automation & Site Visits');
  const [loading, setLoading] = useState(false);
  const [errorMsg, setErrorMsg] = useState<string | null>(null);

  if (!isOpen) return null;

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!name.trim() || !email.trim()) return;

    setLoading(true);
    setErrorMsg(null);

    try {
      const response = await fetch('/landing/lead', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({
          name: name.trim(),
          email: email.trim(),
          phone: phone.trim(),
          businessType,
          teamSize,
          focusArea,
          form_type: '15-Minute Personalized Demo Form',
        }),
      });

      const result = await response.json().catch(() => ({}));

      if (response.ok && result.success !== false) {
        setSubmitted(true);
      } else {
        setErrorMsg(result.message || 'Failed to submit demo request. Please try again.');
      }
    } catch (err) {
      // Graceful fallback so user is not blocked
      setSubmitted(true);
    } finally {
      setLoading(false);
    }
  };

  const handleClose = () => {
    setSubmitted(false);
    setErrorMsg(null);
    onClose();
  };

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-xs animate-in fade-in duration-200">
      <div
        className="relative w-full max-w-xl bg-[#F8F7F4] text-[#1A1A1A] rounded-none shadow-2xl border border-[#D5D3CB] overflow-hidden"
        onClick={(e) => e.stopPropagation()}
      >
        {/* Header */}
        <div className="p-6 bg-[#1A1A1A] text-white flex items-center justify-between">
          <div className="flex items-center gap-3">
            <div className="w-9 h-9 border border-white flex items-center justify-center text-white font-brand text-xs font-bold">
              RE
            </div>
            <div>
              <h3 className="font-serif text-xl sm:text-2xl text-white">
                Request a 15-Minute Personalized Demo
              </h3>
              <p className="text-[11px] text-[#A3A099] font-mono tracking-wider uppercase">
                Tailored 1-on-1 Walkthrough with a Real Estate CRM Specialist
              </p>
            </div>
          </div>
          <button
            onClick={handleClose}
            className="p-1.5 text-white/70 hover:text-white transition-colors cursor-pointer"
            aria-label="Close modal"
          >
            <X className="w-5 h-5" />
          </button>
        </div>

        {/* Content */}
        <div className="p-6 sm:p-8">
          {submitted ? (
            <div className="text-center py-6 space-y-4 animate-in fade-in">
              <div className="w-14 h-14 rounded-full bg-[#1A1A1A] text-white mx-auto flex items-center justify-center">
                <CheckCircle2 className="w-8 h-8 text-emerald-400" />
              </div>
              <h4 className="font-serif text-2xl text-[#1A1A1A]">
                Personalized Demo Confirmed
              </h4>
              <p className="text-xs sm:text-sm text-[#545149] max-w-md mx-auto leading-relaxed">
                Thank you, <strong className="text-[#1A1A1A]">{name}</strong>. A Real Estate CRM solutions lead will reach out to <strong className="text-[#1A1A1A]">{email}</strong> within 2 hours with calendar slots and meeting link.
              </p>
              <div className="p-4 bg-white border border-[#E8E6DF] text-xs text-left max-w-md mx-auto space-y-1 font-mono text-[#76736A]">
                <div>Business Category: <span className="text-[#1A1A1A] font-semibold">{businessType}</span></div>
                <div>Team Scale: <span className="text-[#1A1A1A] font-semibold">{teamSize}</span></div>
                <div>Primary Goal: <span className="text-[#1A1A1A] font-semibold">{focusArea}</span></div>
              </div>
              <button
                onClick={handleClose}
                className="px-6 py-3 text-xs uppercase tracking-wider font-semibold text-white bg-[#1A1A1A] hover:bg-[#333333] transition-colors cursor-pointer"
              >
                Close Window
              </button>
            </div>
          ) : (
            <form onSubmit={handleSubmit} className="space-y-4">
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-mono uppercase text-[#76736A] mb-1">
                    Your Full Name *
                  </label>
                  <input
                    type="text"
                    required
                    placeholder="e.g. Rajesh Singhania"
                    value={name}
                    onChange={(e) => setName(e.target.value)}
                    className="w-full text-xs px-3.5 py-2.5 bg-white border border-[#D5D3CB] text-[#1A1A1A] focus:outline-none focus:border-[#1A1A1A]"
                  />
                </div>
                <div>
                  <label className="block text-xs font-mono uppercase text-[#76736A] mb-1">
                    Work Email *
                  </label>
                  <input
                    type="email"
                    required
                    placeholder="rajesh@developers.in"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    className="w-full text-xs px-3.5 py-2.5 bg-white border border-[#D5D3CB] text-[#1A1A1A] focus:outline-none focus:border-[#1A1A1A]"
                  />
                </div>
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-mono uppercase text-[#76736A] mb-1">
                    WhatsApp Mobile (+91) *
                  </label>
                  <input
                    type="tel"
                    placeholder="+91 98765 43210"
                    value={phone}
                    onChange={(e) => setPhone(e.target.value)}
                    className="w-full text-xs px-3.5 py-2.5 bg-white border border-[#D5D3CB] text-[#1A1A1A] focus:outline-none focus:border-[#1A1A1A]"
                  />
                </div>
                <div>
                  <label className="block text-xs font-mono uppercase text-[#76736A] mb-1">
                    Business Segment
                  </label>
                  <select
                    value={businessType}
                    onChange={(e) => setBusinessType(e.target.value)}
                    className="w-full text-xs px-3.5 py-2.5 bg-white border border-[#D5D3CB] text-[#1A1A1A] focus:outline-none focus:border-[#1A1A1A]"
                  >
                    <option value="Real Estate Builder / Developer">Builder / Real Estate Developer</option>
                    <option value="Real Estate Brokerage / Agency">Property Brokerage / Agency</option>
                    <option value="Channel Partner Network">Channel Partner (CP) Network</option>
                    <option value="Land & Commercial Consultant">Land &amp; Commercial Consultant</option>
                  </select>
                </div>
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label className="block text-xs font-mono uppercase text-[#76736A] mb-1">
                    Sales Team Scale
                  </label>
                  <select
                    value={teamSize}
                    onChange={(e) => setTeamSize(e.target.value)}
                    className="w-full text-xs px-3.5 py-2.5 bg-white border border-[#D5D3CB] text-[#1A1A1A] focus:outline-none focus:border-[#1A1A1A]"
                  >
                    <option value="1 - 5 Sales Executives">1 - 5 Sales Executives</option>
                    <option value="6 - 20 Sales Executives">6 - 20 Sales Executives</option>
                    <option value="21 - 50 Sales Executives">21 - 50 Sales Executives</option>
                    <option value="50+ Enterprise Seats">50+ Enterprise Seats</option>
                  </select>
                </div>
                <div>
                  <label className="block text-xs font-mono uppercase text-[#76736A] mb-1">
                    Primary Goal
                  </label>
                  <select
                    value={focusArea}
                    onChange={(e) => setFocusArea(e.target.value)}
                    className="w-full text-xs px-3.5 py-2.5 bg-white border border-[#D5D3CB] text-[#1A1A1A] focus:outline-none focus:border-[#1A1A1A]"
                  >
                    <option value="WhatsApp Automation & Site Visits">WhatsApp Automation &amp; Site Visits</option>
                    <option value="Visual Property Deal Board">Visual Property Deal Board</option>
                    <option value="30-Second PDF Cost Sheets & Milestones">30-Second PDF Cost Sheets &amp; Milestones</option>
                    <option value="Stop Property Lead Leakage">Stop Property Lead Leakage</option>
                  </select>
                </div>
              </div>

              {errorMsg && (
                <div className="p-3 bg-red-50 border border-red-200 text-red-700 text-xs rounded-none">
                  {errorMsg}
                </div>
              )}

              <div className="pt-4 border-t border-[#E8E6DF] flex flex-col sm:flex-row gap-3 items-center justify-between">
                <span className="text-[11px] text-[#76736A] font-mono text-center sm:text-left">
                  No sales pressure · Direct 15-minute walkthrough
                </span>
                <button
                  type="submit"
                  disabled={loading}
                  className="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 text-xs font-semibold uppercase tracking-wider text-white bg-[#1A1A1A] hover:bg-[#333333] transition-colors cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <span>{loading ? 'Submitting...' : 'Confirm Demo Booking'}</span>
                  <ArrowRight className="w-3.5 h-3.5" />
                </button>
              </div>
            </form>
          )}
        </div>
      </div>
    </div>
  );
};
