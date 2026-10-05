import React from 'react';

/** Decorative architectural model and sales workspace, kept behind footer content. */
export const RealEstateFooterScene = () => (
  <div className="estate-footer-scene" aria-hidden="true">
    <div className="estate-footer-glow" />
    <div className="estate-footer-model">
      <svg viewBox="0 0 920 420" fill="none">
        <defs>
          <pattern id="footer-floor" width="48" height="28" patternUnits="userSpaceOnUse">
            <path d="M0 14 24 0 48 14 24 28Z" stroke="#60a5fa" strokeOpacity=".4" />
          </pattern>
          <linearGradient id="footer-building" x2="1" y2="1">
            <stop stopColor="#2563eb" stopOpacity=".65" />
            <stop offset="1" stopColor="#38bdf8" stopOpacity=".2" />
          </linearGradient>
        </defs>
        <path d="M30 260 450 30 900 270 480 410Z" fill="url(#footer-floor)" />
        <g stroke="#7dd3fc" strokeWidth="2" strokeLinejoin="round">
          {[
            { x: 240, y: 220, h: 110 },
            { x: 380, y: 260, h: 190 },
            { x: 530, y: 215, h: 135 },
            { x: 665, y: 280, h: 90 },
          ].map(({ x, y, h }) => (
            <g key={x}>
              <path d={`M${x} ${y-h} l55 -30 55 30 -55 30Z`} fill="#2563eb" fillOpacity=".55" />
              <path d={`M${x} ${y-h} l55 30 v${h} l-55 -30Z`} fill="url(#footer-building)" />
              <path d={`M${x+55} ${y-h+30} l55 -30 v${h} l-55 30Z`} fill="#38bdf8" fillOpacity=".25" />
              {[...Array(Math.floor(h / 25))].map((_, i) => (
                <path key={i} d={`M${x+12} ${y-h+25+i*23} l30 16 m25 -2 30 -16`} strokeOpacity=".85" strokeDasharray="6 5" />
              ))}
            </g>
          ))}
        </g>
        <path className="estate-footer-route" d="M140 280 260 345 430 315 555 380 790 250" stroke="#67e8f9" strokeWidth="3" strokeDasharray="5 9" />
        {[{ x: 140, y: 280 }, { x: 430, y: 315 }, { x: 790, y: 250 }].map(({ x, y }) => (
          <g key={x} transform={`translate(${x} ${y})`}>
            <ellipse rx="16" ry="8" fill="#38bdf8" fillOpacity=".4" />
            <path d="M0 -33C-17 -33 -17 -14 0 -4C17 -14 17 -33 0 -33Z" fill="#0b1a30" stroke="#67e8f9" strokeWidth="2" />
            <circle cy="-22" r="4" fill="#60a5fa" />
          </g>
        ))}
        <g className="estate-footer-dashboard" transform="translate(70 55) rotate(-8)">
          <rect width="190" height="116" rx="12" fill="#102544" stroke="#7dd3fc" strokeWidth="2" />
          <circle cx="16" cy="16" r="3" fill="#38bdf8" />
          <path d="M29 16H105" stroke="#93c5fd" strokeWidth="4" strokeLinecap="round" />
          {[0, 1, 2].map((i) => (
            <g key={i} transform={`translate(${14+i*57} 34)`}>
              <rect width="48" height="66" rx="4" fill="#2563eb" fillOpacity=".12" />
              <path d="M8 12H36 M8 29H30 M8 47H34" stroke="#60a5fa" strokeWidth="5" strokeLinecap="round" strokeOpacity={.7-i*.15} />
            </g>
          ))}
        </g>
      </svg>
    </div>
    <div className="estate-footer-shade" />
  </div>
);
