// Centralized Configuration for Indian Real Estate CRM
// Built for Indian Builders, Developers, Brokers, Channel Partners & Property Consultants

export const REAL_ESTATE_CONFIG = {
  currency: {
    symbol: '₹',
    code: 'INR',
    locale: 'en-IN',
  },
  contact: {
    phoneDisplay: '+91 98765 43210',
    phoneRaw: '+919876543210',
    whatsappDisplay: '+91 98765 43210',
    email: 'contact@realestatecrm.in',
    salesEmail: 'sales@realestatecrm.in',
    address: 'Golf Course Road, Gurugram · BKC, Mumbai · Whitefield, Bengaluru',
    officeHours: 'Mon - Sat: 9:30 AM to 6:30 PM IST',
  },
  propertyTypes: [
    'Residential Plots',
    'Apartments & Flats (2/3/4 BHK)',
    'Luxury Villas & Row Houses',
    'Commercial Shops & Office Spaces',
    'Independent Houses & Floors',
  ],
  pipelineStages: [
    'New Enquiry',
    'Contacted',
    'Requirement Qualified',
    'Site Visit Scheduled',
    'Site Visit Completed',
    'Negotiation & Token Amount',
    'Booking Confirmed',
  ],
  leadSources: [
    'Website Enquiry Form',
    'WhatsApp Inbound',
    'Meta Lead Ads (Facebook & Instagram)',
    'Google Search Ads',
    'Property Portals (99acres, Magicbricks, Housing)',
    'Channel Partner & Broker Referral',
    'Walk-in / Direct Call',
  ],
  paymentModes: ['UPI', 'Bank Transfer / NEFT / RTGS', 'Cheque', 'Debit / Credit Card', 'Demand Draft'],
  pricingPlans: [
    {
      id: 'starter',
      name: 'Broker & Agency Starter',
      tagline: 'Ideal for independent brokers, property consultants & small agencies',
      priceMonthly: 2499,
      priceAnnualPerMonth: 1999,
      priceFormatted: '₹1,999',
      billingCycle: 'per user / month billed annually',
      usersIncluded: 'Up to 3 Sales Executives',
      features: [
        'Centralized WhatsApp & Portal Lead Inbox',
        'Plot, Flat & Villa Inventory Manager (Up to 5 Projects)',
        'Site Visit Scheduler with WhatsApp Reminders',
        '30-Second PDF Property Brochure & Cost Sheet Generator',
        'Sales Executive Lead Routing',
        'Customer Requirement Tracking',
      ],
      popular: false,
    },
    {
      id: 'growth',
      name: 'Builder & Developer Enterprise',
      tagline: 'For real-estate developers, multi-project builders & large channel partner networks',
      priceMonthly: 4999,
      priceAnnualPerMonth: 3999,
      priceFormatted: '₹3,999',
      billingCycle: 'per user / month billed annually',
      usersIncluded: 'Up to 15 Sales Executives + Team Lead',
      features: [
        'Everything in Starter +',
        'Unlimited Real Estate Projects & Unit Inventory',
        'Channel Partner & Broker Commission Tracking',
        'Customer Data Vault with Phone Number Masking',
        'Booking & Token Amount Milestone Management',
        'Sales Executive Activity & Site Visit Attendance',
        'Dedicated Onboarding & Indian Support Manager',
      ],
      popular: true,
    },
  ],
};

/**
 * Format numbers into Indian Rupee currency format (e.g., ₹25,000 or ₹1,25,000)
 */
export function formatINR(amount: number): string {
  return '₹' + amount.toLocaleString('en-IN');
}

/**
 * Format numbers into Lakhs and Crores (e.g. ₹85 Lakh, ₹1.25 Crore)
 */
export function formatIndianCurrencyWords(amount: number): string {
  if (amount >= 10000000) {
    const cr = amount / 10000000;
    return `₹${cr.toFixed(cr % 1 === 0 ? 0 : 2)} Crore`;
  }
  if (amount >= 100000) {
    const lakh = amount / 100000;
    return `₹${lakh.toFixed(lakh % 1 === 0 ? 0 : 2)} Lakh`;
  }
  if (amount >= 1000) {
    return `₹${(amount / 1000).toFixed(0)}k`;
  }
  return `₹${amount.toLocaleString('en-IN')}`;
}

