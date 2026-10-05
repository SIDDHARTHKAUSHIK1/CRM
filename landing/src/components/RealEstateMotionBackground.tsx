import React, { useEffect, useId, useRef } from 'react';
import './RealEstateMotionBackground.css';

interface RealEstateMotionBackgroundProps {
  variant?: 'properties' | 'pipeline' | 'workspace';
  tone?: 'light' | 'dark';
}

function Buildings() {
  return (
    <g stroke="currentColor" strokeWidth="1.4" strokeLinejoin="round">
      <path d="M24 223 170 142 337 230 187 315Z" className="estate-motion-face" />
      <path d="m45 234 139 74m-99-97 139 74m-98-97 138 74m-41-96-141 79m180-58-141 80" opacity=".2" />
      {[{ x: 74, y: 212, h: 93 }, { x: 163, y: 242, h: 166 }, { x: 248, y: 217, h: 107 }].map(({ x, y, h }) => (
        <g key={x}>
          <path d={`M${x} ${y-h} l32 -18 32 18 -32 18Z`} className="estate-motion-roof" />
          <path d={`M${x} ${y-h} l32 18 v${h} l-32 -18Z`} className="estate-motion-face" />
          <path d={`M${x+32} ${y-h+18} l32 -18 v${h} l-32 18Z`} className="estate-motion-side" />
          {Array.from({ length: Math.floor(h / 22) }, (_, i) => (
            <path key={i} d={`M${x+8} ${y-h+20+i*21} l17 9m14 0 17 -9`} strokeDasharray="4 4" opacity=".7" />
          ))}
        </g>
      ))}
      <path d="m26 263 81 44 53-29 48 26 103-58" className="estate-motion-flow" fill="none" />
      <g transform="translate(285 96)" className="estate-motion-pin">
        <path d="M0-24C-22-24-22 0 0 18C22 0 22-24 0-24Z" className="estate-motion-roof" />
        <circle cy="-7" r="6" fill="var(--scene-paper)" />
      </g>
    </g>
  );
}

function Workspace() {
  return (
    <g stroke="currentColor" strokeWidth="1.3" strokeLinejoin="round">
      <path d="M25 119 212 55 339 152 149 222Z" className="estate-motion-face" />
      <path d="m25 119 124 103v12L25 132Zm124 103 190-70v12l-190 70Z" className="estate-motion-side" />
      <path d="m57 122 55-19 39 31-56 21Zm69-24 74-25 39 30-73 26Zm-20 65 56-19 40 31-56 21Zm69-24 73-26 48 37-74 27Z" fill="var(--scene-paper)" />
      <path d="m116 104 10-4m35 37 12-4m39-25 12-4m-55 56 11-4" strokeWidth="4" />
      <g transform="translate(218 178)" className="estate-motion-pin">
        <path d="M0-32C-25-32-25-3 0 20C25-3 25-32 0-32Z" className="estate-motion-roof" />
        <path d="m-8-12 8-6 8 6v10H-8Z" fill="var(--scene-paper)" />
      </g>
      <g transform="translate(32 249)">
        <rect width="173" height="44" rx="12" className="estate-motion-paper" />
        <circle cx="22" cy="22" r="8" className="estate-motion-roof" />
        <path d="m18 22 3 3 5-6" stroke="var(--scene-paper)" fill="none" />
        <text x="40" y="26" stroke="none" fill="currentColor" fontSize="12" fontWeight="600">Site visit scheduled</text>
      </g>
      <path d="M248 211v39h-31" className="estate-motion-flow" fill="none" />
    </g>
  );
}

function Pipeline() {
  return (
    <g stroke="currentColor" strokeWidth="1.2">
      <rect x="24" y="70" width="307" height="196" rx="17" className="estate-motion-paper" />
      <path d="M25 105h305" opacity=".25" />
      <circle cx="43" cy="87" r="3" fill="currentColor" stroke="none" />
      <circle cx="55" cy="87" r="3" fill="currentColor" stroke="none" opacity=".35" />
      <text x="72" y="91" fontSize="11" fontWeight="600" fill="currentColor" stroke="none">PROPERTY PIPELINE</text>
      {['Enquiry', 'Site visit', 'Booking'].map((label, i) => (
        <g key={label} transform={`translate(${37+i*96} 117)`}>
          <rect width="86" height="132" rx="8" className="estate-motion-face" stroke="none" />
          <text x="9" y="18" fontSize="10" fill="currentColor" stroke="none">{label}</text>
          {[0, 1].map((row) => (
            <g key={row} transform={`translate(8 ${29+row*47})`} className={row === 0 ? 'estate-motion-deal' : undefined} style={{ animationDelay: `${i * -2}s` }}>
              <rect width="70" height="37" rx="6" className="estate-motion-paper" />
              <path d="M9 12h42m-42 10h26" opacity=".5" strokeWidth="3" strokeLinecap="round" />
              <circle cx="59" cy="24" r="3" className="estate-motion-roof" />
            </g>
          ))}
        </g>
      ))}
      <path d="M66 291h222" className="estate-motion-flow" fill="none" />
      {[66, 177, 288].map((x) => <circle key={x} cx={x} cy="291" r="6" className="estate-motion-roof estate-motion-node" style={{ animationDelay: `${-x/60}s` }} />)}
    </g>
  );
}

/** Decorative section-local scenes; never capture input or announce mock data. */
export const RealEstateMotionBackground: React.FC<RealEstateMotionBackgroundProps> = ({ variant = 'properties', tone = 'light' }) => {
  const root = useRef<HTMLDivElement>(null);
  const id = useId().replace(/:/g, '');

  useEffect(() => {
    const element = root.current;
    if (!element) return;
    let inView = false;
    const update = () => { element.dataset.active = String(inView && !document.hidden); };
    const observer = new IntersectionObserver(([entry]) => {
      inView = entry.isIntersecting;
      update();
    }, { rootMargin: '100px' });
    observer.observe(element);
    document.addEventListener('visibilitychange', update);
    return () => {
      observer.disconnect();
      document.removeEventListener('visibilitychange', update);
    };
  }, []);

  const Primary = variant === 'properties' ? Buildings : variant === 'pipeline' ? Pipeline : Workspace;
  const Secondary = variant === 'properties' ? Workspace : variant === 'pipeline' ? Buildings : Pipeline;

  return (
    <div ref={root} className={`estate-motion estate-motion--${tone} estate-motion--${variant}`} data-active="false" aria-hidden="true">
      <div className="estate-motion-grid" />
      <div className="estate-motion-glow estate-motion-glow--left" />
      <div className="estate-motion-glow estate-motion-glow--right" />
      <svg className="estate-motion-scene estate-motion-scene--left" viewBox="0 0 360 340" fill="none" focusable="false"><Primary /></svg>
      <svg className="estate-motion-scene estate-motion-scene--right" viewBox="0 0 360 340" fill="none" focusable="false"><Secondary /></svg>
      <svg className="estate-motion-network" width="100%" height="100%" focusable="false">
        <defs><pattern id={`${id}-network`} width="700" height="390" patternUnits="userSpaceOnUse">
          <path d="M0 310h85q25 0 25-25V90q0-25 25-25h65M700 120h-85q-25 0-25 25v160q0 25-25 25h-65" className="estate-motion-flow" fill="none" />
          <circle cx="200" cy="65" r="4" fill="currentColor" /><circle cx="500" cy="330" r="4" fill="currentColor" />
        </pattern></defs>
        <rect width="100%" height="100%" fill={`url(#${id}-network)`} />
      </svg>
    </div>
  );
};
