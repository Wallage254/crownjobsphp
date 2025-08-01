import { db } from "../server/db";
import { jobs, testimonials } from "@shared/schema";

const sampleJobs = [
  {
    title: "Senior Construction Project Manager",
    company: "BuildTech UK Ltd",
    category: "Construction",
    location: "London",
    description: `Join our dynamic team as a Senior Construction Project Manager overseeing major infrastructure projects across London. You'll be responsible for managing multi-million pound construction projects from inception to completion.

Key Responsibilities:
• Lead and manage construction teams of 50+ workers
• Oversee project budgets ranging from £2M to £10M
• Ensure compliance with UK health and safety regulations
• Coordinate with architects, engineers, and stakeholders
• Manage project timelines and deliverables`,
    requirements: `Essential Requirements:
• Minimum 5 years construction management experience
• Bachelor's degree in Civil Engineering or Construction Management
• CSCS Card and SMSTS qualification
• Strong knowledge of UK building regulations
• Excellent leadership and communication skills
• Proficiency in project management software
• Valid UK work visa or eligibility for sponsorship`,
    salaryMin: 55000,
    salaryMax: 75000,
    jobType: "Full-time",
    isUrgent: true,
    visaSponsored: true
  },
  {
    title: "Registered Nurse - ICU",
    company: "Royal London Hospital",
    category: "Healthcare",
    location: "London",
    description: `We are seeking dedicated Registered Nurses to join our Intensive Care Unit. This is an excellent opportunity for experienced nurses to work in one of the UK's leading hospitals.

What We Offer:
• Comprehensive training and development programs
• Competitive salary with shift allowances
• Excellent pension scheme and benefits
• Supportive multicultural work environment
• Career progression opportunities`,
    requirements: `Essential Requirements:
• Registered Nurse qualification (Bachelor's degree in Nursing)
• NMC registration or eligibility to register
• Minimum 2 years ICU experience preferred
• Strong clinical assessment skills
• Excellent English communication skills
• Commitment to continuing professional development
• Right to work in the UK or visa sponsorship available`,
    salaryMin: 28000,
    salaryMax: 35000,
    jobType: "Full-time",
    isUrgent: false,
    visaSponsored: true
  },
  {
    title: "Hotel Manager",
    company: "Premium Hotels Group",
    category: "Hospitality",
    location: "Manchester",
    description: `Lead our 4-star hotel in the heart of Manchester as Hotel Manager. You'll oversee all hotel operations, ensuring exceptional guest experiences and operational excellence.

Your Role:
• Manage daily hotel operations and staff
• Ensure high standards of customer service
• Oversee budgets and financial performance
• Lead a team of 30+ hospitality professionals
• Implement strategies to increase revenue and guest satisfaction`,
    requirements: `What We're Looking For:
• Hotel Management degree or equivalent experience
• Minimum 3 years hotel management experience
• Strong financial and operational management skills
• Excellent customer service orientation
• Leadership experience in hospitality sector
• Fluent English with additional languages preferred
• Eligible for UK work visa sponsorship`,
    salaryMin: 35000,
    salaryMax: 45000,
    jobType: "Full-time",
    isUrgent: false,
    visaSponsored: true
  },
  {
    title: "Electrical Technician",
    company: "PowerGrid Solutions",
    category: "Skilled Trades",
    location: "Birmingham",
    description: `Join our growing electrical contracting company as an Electrical Technician. Work on diverse projects including commercial, industrial, and residential installations.

Projects Include:
• Commercial building electrical systems
• Industrial machinery installations
• Renewable energy projects
• Maintenance and repair services
• Emergency call-out services`,
    requirements: `Required Qualifications:
• City & Guilds Level 3 Electrical qualification or equivalent
• 18th Edition Wiring Regulations
• Minimum 3 years electrical experience
• Test and inspection certification (2391/2394/2395)
• Full UK driving license
• Excellent problem-solving skills
• Safety-conscious with attention to detail`,
    salaryMin: 32000,
    salaryMax: 42000,
    jobType: "Full-time",
    isUrgent: false,
    visaSponsored: true
  },
  {
    title: "Care Assistant",
    company: "Sunshine Care Homes",
    category: "Healthcare",
    location: "Leeds",
    description: `Make a difference in the lives of elderly residents as a Care Assistant. Provide compassionate care and support in our award-winning care facility.

Daily Responsibilities:
• Assist residents with personal care and daily activities
• Administer medications under supervision
• Support meal times and recreational activities
• Maintain detailed care records
• Work as part of a caring, professional team`,
    requirements: `Essential Criteria:
• Care Certificate or willingness to complete training
• Previous care experience preferred but not essential
• Compassionate and patient nature
• Good English communication skills
• Flexible approach to shift work
• Enhanced DBS check required
• Right to work in UK - visa sponsorship available`,
    salaryMin: 22000,
    salaryMax: 26000,
    jobType: "Full-time",
    isUrgent: true,
    visaSponsored: true
  },
  {
    title: "Chef de Partie",
    company: "The Gourmet Kitchen",
    category: "Hospitality",
    location: "Edinburgh",
    description: `Join our award-winning restaurant kitchen as Chef de Partie. Work alongside experienced chefs in creating exceptional dining experiences for our guests.

Kitchen Environment:
• High-volume restaurant serving 200+ covers daily
• Focus on modern British cuisine with international influences
• Fresh, locally sourced ingredients
• Fast-paced, professional kitchen environment
• Opportunities to develop your culinary skills`,
    requirements: `We're Looking For:
• Culinary qualification or equivalent experience
• Minimum 2 years experience in professional kitchen
• Knowledge of food safety and hygiene standards
• Ability to work under pressure
• Team player with positive attitude
• Passion for cooking and food presentation
• Flexible schedule including evenings and weekends`,
    salaryMin: 25000,
    salaryMax: 30000,
    jobType: "Full-time",
    isUrgent: false,
    visaSponsored: true
  },
  {
    title: "Plumber",
    company: "City Plumbing Services",
    category: "Skilled Trades",
    location: "Liverpool",
    description: `Experienced plumber required for established plumbing company. Work on diverse projects from residential repairs to commercial installations.

Work Includes:
• Domestic plumbing repairs and installations
• Commercial plumbing projects
• Bathroom and kitchen renovations
• Emergency call-out services
• Central heating installations and repairs`,
    requirements: `Essential Requirements:
• NVQ Level 2/3 in Plumbing or equivalent
• Gas Safe registration preferred
• Minimum 3 years plumbing experience
• Own tools and transport
• Customer service skills
• Problem-solving abilities
• Reliable and punctual
• Valid UK driving license`,
    salaryMin: 30000,
    salaryMax: 40000,
    jobType: "Full-time",
    isUrgent: false,
    visaSponsored: true
  },
  {
    title: "Site Supervisor",
    company: "Construction Elite",
    category: "Construction",
    location: "Bristol",
    description: `Lead construction teams as Site Supervisor on residential and commercial projects. Ensure quality, safety, and timely completion of construction work.

Key Duties:
• Supervise construction crews and subcontractors
• Ensure compliance with health and safety regulations
• Monitor quality standards and project progress
• Coordinate with project managers and clients
• Manage site logistics and resources`,
    requirements: `Required Qualifications:
• SMSTS (Site Management Safety Training Scheme)
• First Aid at Work certification
• Minimum 5 years construction experience
• Strong leadership and communication skills
• Knowledge of construction methods and materials
• Computer literacy for reporting and documentation
• CSCS Card
• Right to work in UK`,
    salaryMin: 40000,
    salaryMax: 50000,
    jobType: "Full-time",
    isUrgent: true,
    visaSponsored: true
  }
];

const sampleTestimonials = [
  {
    name: "James Okafor",
    country: "Nigeria",
    rating: 5,
    comment: "CrownOpportunities changed my life! I found my dream job as a Construction Manager in London within 3 months. The visa sponsorship process was smooth and the support team was incredible.",
    isActive: true
  },
  {
    name: "Sarah Mwangi",
    country: "Kenya",
    rating: 5,
    comment: "As a registered nurse, I was nervous about moving to the UK. But CrownOpportunities connected me with the perfect hospital role in Manchester. The application process was straightforward and professional.",
    isActive: true
  },
  {
    name: "David Asante",
    country: "Ghana",
    rating: 4,
    comment: "I landed my first UK job in hospitality through this platform. The employers were genuine and the visa support was excellent. Now I'm working in a beautiful hotel in Edinburgh!",
    isActive: true
  },
  {
    name: "Grace Nalwanga",
    country: "Uganda",
    rating: 5,
    comment: "After years of trying to find work in the UK, CrownOpportunities made it happen. I'm now a healthcare assistant in Leeds and couldn't be happier with my new life here.",
    isActive: true
  },
  {
    name: "Mohammed Hassan",
    country: "Ethiopia",
    rating: 5,
    comment: "The platform is fantastic! I found work as an electrical technician and the whole family moved to Birmingham. The support didn't stop after I got the job - they helped with settlement too.",
    isActive: true
  },
  {
    name: "Amara Diallo",
    country: "Ghana",
    rating: 4,
    comment: "Professional service from start to finish. Found my role as a chef in a top London restaurant. The visa process was handled expertly and I felt supported throughout.",
    isActive: true
  }
];

async function seedData() {
  try {
    console.log("Seeding sample jobs...");
    
    // Insert sample jobs
    for (const job of sampleJobs) {
      await db.insert(jobs).values(job);
    }
    
    console.log("Seeding sample testimonials...");
    
    // Insert sample testimonials
    for (const testimonial of sampleTestimonials) {
      await db.insert(testimonials).values(testimonial);
    }
    
    console.log("✅ Sample data seeded successfully!");
  } catch (error) {
    console.error("❌ Error seeding data:", error);
  }
}

seedData();