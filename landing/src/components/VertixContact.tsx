import React, { useState } from 'react';
import { ArrowRight, CheckCircle2 } from 'lucide-react';

export const VertixContact: React.FC = () => {
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [phone, setPhone] = useState('');
  const [businessType, setBusinessType] = useState('Real Estate Builder / Developer');
  const [location, setLocation] = useState('');
  const [teamSize, setTeamSize] = useState('6 - 20 Sales Executives');
  const [message, setMessage] = useState('');
  const [submitted, setSubmitted] = useState(false);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!name.trim() || !email.trim()) return;
    setSubmitted(true);
  };

  return (
    <section id="contact" className="py-12 lg:py-18 bg-[#F4F3EE] border-b border-[#D8D5CA]">
      <div className="max-w-5xl mx-auto px-4 sm:px-6">
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
          {/* Left Title and Subhead */}
          <div className="lg:col-span-4 space-y-3">
            <h2 className="font-serif text-2xl sm:text-3xl lg:text-4xl text-[#0A0A0A] font-bold leading-[1.15] uppercase tracking-tight">
              LET'S ACCELERATE YOUR PROPERTY SALES
            </h2>
            <p className="text-xs sm:text-sm text-[#262522] font-normal leading-relaxed">
              Whether you want to centralize property leads, organize site visits, or generate instant branded PDF cost sheets, we're ready to show you how.
            </p>

            <div className="pt-3 font-mono text-[11px] text-[#262522] space-y-1.5 border-t border-[#D8D5CA] font-bold">
              <div className="truncate">Product Demo: <a href="mailto:demo@realestatecrm.in" className="text-black underline">demo@realestatecrm.in</a></div>
              <div>Sales Advisory: +91 98765 43210</div>
              <div>Response Time: <span className="text-emerald-800">Prompt reply via WhatsApp &amp; Email</span></div>
            </div>
          </div>

          {/* Center Form */}
          <div className="lg:col-span-5">
            {submitted ? (
              <div className="p-5 sm:p-6 rounded-xl bg-white border border-[#CDC9BC] space-y-3 shadow-lg animate-in fade-in">
                <div className="w-10 h-10 rounded-full bg-[#0A0A0A] text-white flex items-center justify-center">
                  <CheckCircle2 className="w-6 h-6 text-emerald-400" />
                </div>
                <h3 className="font-serif text-xl sm:text-2xl text-[#0A0A0A] font-bold">
                  Demo Request Confirmed
                </h3>
                <p className="text-xs text-[#262522] leading-relaxed">
                  Thank you, <strong className="text-black">{name}</strong>. A Real Estate CRM solutions specialist has prepared a 15-minute tailored walkthrough for your {businessType} team and sent an invitation to <strong className="text-black">{email}</strong>.
                </p>
                <button
                  onClick={() => {
                    setSubmitted(false);
                    setName('');
                    setEmail('');
                    setPhone('');
                    setLocation('');
                    setMessage('');
                  }}
                  className="text-xs font-bold text-black underline underline-offset-4 cursor-pointer"
                >
                  Schedule another session
                </button>
              </div>
            ) : (
              <form onSubmit={handleSubmit} className="space-y-3 bg-white p-4 sm:p-5 rounded-xl border border-[#CDC9BC] shadow-lg">
                {/* Row 1: Name and Email */}
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-3">
                  <div>
                    <input
                      type="text"
                      required
                      placeholder="Your Name *"
                      value={name}
                      onChange={(e) => setName(e.target.value)}
                      className="w-full text-xs sm:text-sm font-medium px-3.5 py-2.5 rounded-lg border border-[#CDC9BC] bg-[#FAF9F5] text-black placeholder-[#5C5950] focus:outline-none focus:border-black transition-colors"
                    />
                  </div>
                  <div>
                    <input
                      type="email"
                      required
                      placeholder="Work Email Address *"
                      value={email}
                      onChange={(e) => setEmail(e.target.value)}
                      className="w-full text-xs sm:text-sm font-medium px-3.5 py-2.5 rounded-lg border border-[#CDC9BC] bg-[#FAF9F5] text-black placeholder-[#5C5950] focus:outline-none focus:border-black transition-colors"
                    />
                  </div>
                </div>

                {/* Row 2: WhatsApp Number and Location */}
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-3">
                  <div>
                    <input
                      type="tel"
                      placeholder="WhatsApp Mobile (+91) *"
                      value={phone}
                      onChange={(e) => setPhone(e.target.value)}
                      className="w-full text-xs sm:text-sm font-medium px-3.5 py-2.5 rounded-lg border border-[#CDC9BC] bg-[#FAF9F5] text-black placeholder-[#5C5950] focus:outline-none focus:border-black transition-colors"
                    />
                  </div>
                  <div>
                    <input
                      type="text"
                      placeholder="City / Region (e.g. Gurugram, Mumbai)"
                      value={location}
                      onChange={(e) => setLocation(e.target.value)}
                      className="w-full text-xs sm:text-sm font-medium px-3.5 py-2.5 rounded-lg border border-[#CDC9BC] bg-[#FAF9F5] text-black placeholder-[#5C5950] focus:outline-none focus:border-black transition-colors"
                    />
                  </div>
                </div>

                {/* Row 3: Business Type and Team Size */}
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-3">
                  <div>
                    <select
                      value={businessType}
                      onChange={(e) => setBusinessType(e.target.value)}
                      className="w-full text-xs sm:text-sm font-medium px-3.5 py-2.5 rounded-lg border border-[#CDC9BC] bg-[#FAF9F5] text-black focus:outline-none focus:border-black transition-colors"
                    >
                      <option value="Real Estate Builder / Developer">Builder / Real Estate Developer</option>
                      <option value="Real Estate Brokerage / Agency">Property Brokerage / Agency</option>
                      <option value="Channel Partner Network">Channel Partner (CP) Firm</option>
                      <option value="Land & Commercial Consultant">Land &amp; Commercial Consultant</option>
                      <option value="Independent Property Consultant">Independent Consultant</option>
                    </select>
                  </div>
                  <div>
                    <select
                      value={teamSize}
                      onChange={(e) => setTeamSize(e.target.value)}
                      className="w-full text-xs sm:text-sm font-medium px-3.5 py-2.5 rounded-lg border border-[#CDC9BC] bg-[#FAF9F5] text-black focus:outline-none focus:border-black transition-colors"
                    >
                      <option value="1 - 5 Sales Executives">Team: 1 - 5 Executives</option>
                      <option value="6 - 20 Sales Executives">Team: 6 - 20 Executives</option>
                      <option value="21 - 50 Sales Executives">Team: 21 - 50 Executives</option>
                      <option value="50+ Enterprise Seats">Team: 50+ Seats</option>
                    </select>
                  </div>
                </div>

                {/* Row 4: Message */}
                <div>
                  <textarea
                    rows={2}
                    placeholder="Tell us about your current sales challenges (e.g. lost leads, delayed site visits, unorganized cost sheets)..."
                    value={message}
                    onChange={(e) => setMessage(e.target.value)}
                    className="w-full text-xs sm:text-sm font-medium px-3.5 py-2 rounded-lg border border-[#CDC9BC] bg-[#FAF9F5] text-black placeholder-[#5C5950] focus:outline-none focus:border-black transition-colors resize-none"
                  ></textarea>
                </div>

                {/* Submit button */}
                <div className="flex justify-stretch sm:justify-end pt-0.5">
                  <button
                    type="submit"
                    className="w-full sm:w-auto group btn-shimmer inline-flex items-center justify-center gap-2 px-6 py-2.5 text-xs font-bold uppercase tracking-wider text-white bg-[#0A0A0A] hover:bg-[#262626] transition-all cursor-pointer rounded-full shadow-md hover:scale-[1.02]"
                  >
                    <span>Request Live Demo</span>
                    <ArrowRight className="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" />
                  </button>
                </div>
              </form>
            )}
          </div>

          {/* Right Image & Vertical Typography */}
          <div className="lg:col-span-3 flex flex-col items-center sm:items-start lg:items-end w-full">
            <div className="w-full aspect-[16/9] sm:aspect-[3/4] rounded-xl overflow-hidden bg-[#0A0A0A] border border-[#CDC9BC] relative group shadow-lg">
              <img
                src="https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=800&q=80"
                alt="Modern organized business environment"
                referrerPolicy="no-referrer"
                className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-90"
              />
              <div className="absolute inset-0 bg-black/35"></div>
              {/* Stacked Manifesto */}
              <div className="absolute inset-0 p-4 sm:p-5 flex flex-row sm:flex-col justify-between sm:justify-end items-end sm:items-start text-white font-mono text-[9px] sm:text-[10px] tracking-[0.25em] sm:tracking-[0.3em] uppercase leading-relaxed font-extrabold drop-shadow-md">
                <div>CLOSE</div>
                <div>DEALS</div>
                <div>BUILD</div>
                <div>BETTER</div>
                <div>PIPELINES</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};
