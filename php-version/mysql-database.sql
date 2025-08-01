-- MySQL Version of CrownOpportunities Database
-- Use this if your DirectAdmin hosting doesn't support PostgreSQL

CREATE DATABASE IF NOT EXISTS crownopportunities CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE crownopportunities;

-- Users table for admin authentication
CREATE TABLE users (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    username VARCHAR(255) NOT NULL UNIQUE,
    password TEXT NOT NULL,
    is_admin BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE categories (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    name VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    gif_url TEXT,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Jobs table
CREATE TABLE jobs (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    title TEXT NOT NULL,
    company VARCHAR(255) NOT NULL,
    category VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    requirements TEXT NOT NULL,
    salary_min INTEGER,
    salary_max INTEGER,
    job_type VARCHAR(50) NOT NULL DEFAULT 'Full-time',
    is_urgent BOOLEAN DEFAULT false,
    visa_sponsored BOOLEAN DEFAULT true,
    company_logo TEXT,
    workplace_images JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Applications table
CREATE TABLE applications (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    job_id VARCHAR(36) NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    current_location VARCHAR(255) NOT NULL,
    profile_photo TEXT,
    cv_file TEXT,
    cover_letter TEXT,
    experience VARCHAR(50),
    previous_role VARCHAR(255),
    status VARCHAR(50) NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
);

-- Testimonials table
CREATE TABLE testimonials (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    name VARCHAR(255) NOT NULL,
    country VARCHAR(255) NOT NULL,
    rating INTEGER NOT NULL,
    comment TEXT NOT NULL,
    photo TEXT,
    video_url TEXT,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Messages table (contact form)
CREATE TABLE messages (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(500) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, password, is_admin) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', true);

-- Insert sample categories
INSERT INTO categories (name, description) VALUES 
('Healthcare', 'Medical and healthcare positions including nursing, pharmacy, and clinical roles'),
('Construction', 'Building and construction jobs including site management, trades, and engineering'),
('Hospitality', 'Hotels, restaurants, and service industry positions'),
('Skilled Trades', 'Technical and specialized trades including plumbing, electrical, and carpentry'),
('Education', 'Teaching and educational roles at all levels'),
('Technology', 'IT and technology positions including software development and system administration'),
('Finance', 'Banking, accounting, and financial services roles'),
('Manufacturing', 'Production, quality control, and industrial manufacturing positions');

-- Insert sample jobs
INSERT INTO jobs (title, company, category, location, description, requirements, salary_min, salary_max, job_type, is_urgent, visa_sponsored) VALUES 

('Senior Staff Nurse', 'NHS Foundation Trust', 'Healthcare', 'London', 
'We are seeking experienced nurses to join our dynamic healthcare team. This role offers excellent career progression opportunities in one of London''s leading hospitals. You will be responsible for providing high-quality patient care, mentoring junior staff, and contributing to clinical excellence initiatives.

Key Responsibilities:
- Provide comprehensive nursing care to patients
- Lead and supervise nursing teams
- Maintain accurate patient records
- Collaborate with multidisciplinary teams
- Ensure compliance with healthcare standards

Benefits:
- Competitive NHS salary scale
- Comprehensive pension scheme
- 27 days annual leave plus bank holidays
- Professional development opportunities
- Visa sponsorship available', 
'- Registered Nurse with NMC pin
- Minimum 3 years post-qualification experience
- Experience in acute care settings preferred
- Strong communication and leadership skills
- IELTS 7.0 or equivalent English proficiency
- Willingness to relocate to UK', 
32000, 45000, 'Full-time', true, true),

('Construction Project Manager', 'BuildTech Solutions Ltd', 'Construction', 'Manchester', 
'Leading construction company seeks experienced Project Manager to oversee major infrastructure projects. This is an excellent opportunity for a construction professional looking to advance their career in the UK market.

Project Types:
- Commercial developments
- Residential complexes  
- Infrastructure projects
- Renovation and refurbishment

What We Offer:
- Competitive salary package
- Company vehicle and fuel allowance
- Private healthcare
- Performance bonuses
- Clear career progression path', 
'- Degree in Civil Engineering or Construction Management
- Minimum 5 years project management experience
- CSCS card or equivalent
- Strong knowledge of UK building regulations
- PMP or PRINCE2 certification preferred
- Valid driving license
- Excellent problem-solving skills', 
48000, 65000, 'Full-time', false, true),

('Head Chef', 'The Royal Oak Hotel', 'Hospitality', 'Edinburgh', 
'Award-winning hotel restaurant seeks talented Head Chef to lead our culinary team. We pride ourselves on serving exceptional cuisine using locally sourced ingredients.

Restaurant Details:
- 80-cover restaurant
- AA Rosette rated
- Focus on modern British cuisine
- Strong emphasis on local suppliers
- Busy conference and events catering

Career Benefits:
- Opportunity to shape the menu
- Lead a team of 12 kitchen staff
- Work with premium ingredients
- Potential for TV and media appearances', 
'- Culinary degree or equivalent experience
- Minimum 7 years in senior kitchen roles
- Experience managing large teams
- Strong knowledge of food safety standards
- Creative flair and menu development skills
- Ability to work under pressure
- Passion for quality and innovation', 
42000, 55000, 'Full-time', false, true),

('Senior Software Developer', 'TechForward Ltd', 'Technology', 'Birmingham', 
'Growing fintech company looking for talented developers to join our expanding team. Work on cutting-edge financial applications used by thousands of businesses across the UK.

Technology Stack:
- React, Node.js, TypeScript
- PostgreSQL, Redis
- AWS cloud infrastructure
- Microservices architecture
- Agile development methodology

Company Culture:
- Flexible working arrangements
- Regular team building events
- Continuous learning budget
- Modern office in city center
- Startup environment with stability', 
'- Computer Science degree or equivalent
- 5+ years full-stack development experience
- Strong JavaScript/TypeScript skills
- Experience with React and Node.js
- Knowledge of database design
- Familiarity with cloud platforms (AWS preferred)
- Good understanding of software architecture
- Excellent English communication skills', 
45000, 70000, 'Full-time', false, true),

('Qualified Electrician', 'PowerTech Services', 'Skilled Trades', 'Bristol', 
'Established electrical contractor seeking qualified electricians for commercial and residential projects. Excellent opportunity for skilled tradespeople to build their career in the UK.

Project Types:
- New build installations
- Maintenance and repairs
- Industrial electrical work
- Solar panel installations
- Emergency call-out services

Benefits Package:
- Company van and tools provided
- Overtime opportunities
- Training and certification support
- Pension scheme
- 28 days holiday', 
'- City & Guilds 2365 or equivalent
- 18th Edition Wiring Regulations
- Minimum 3 years post-apprenticeship experience
- Full UK driving license
- Ability to work independently
- Strong problem-solving skills
- Customer service oriented
- Physical fitness for manual work', 
35000, 48000, 'Full-time', true, true),

('Primary School Teacher', 'Greenfield Primary Academy', 'Education', 'Liverpool', 
'Outstanding primary school seeks passionate teachers to join our dedicated team. We provide education to children aged 4-11 in a supportive, nurturing environment.

School Information:
- Rated ''Outstanding'' by Ofsted
- 420 pupils across 14 classes
- Strong community links
- Excellent resources and facilities
- Supportive leadership team

Professional Development:
- NQT mentoring program
- Regular training opportunities
- Career progression pathways
- Collaborative planning time
- Access to education conferences', 
'- Qualified Teacher Status (QTS)
- Primary education degree preferred
- Experience teaching Key Stage 1 or 2
- Strong classroom management skills
- Commitment to inclusive education
- Excellent communication with parents
- Creative approach to learning
- Enhanced DBS clearance required', 
28000, 42000, 'Full-time', false, true);

-- Insert sample testimonials
INSERT INTO testimonials (name, country, rating, comment, is_active) VALUES 

('Amara Okafor', 'Nigeria', 5, 
'CrownOpportunities completely changed my life! I was working as a nurse in Lagos and dreaming of opportunities in the UK. Within 6 months of applying through their platform, I landed a senior nurse position at a prestigious London hospital. The visa sponsorship process was smooth, and the support team guided me every step of the way. I now have a fulfilling career, excellent salary, and my family has joined me. I cannot recommend them enough!', 
true),

('David Mensah', 'Ghana', 5, 
'As a construction engineer, I was struggling to find opportunities that offered visa sponsorship. CrownOpportunities connected me with BuildTech Solutions, and I''m now managing major infrastructure projects in Manchester. The salary is three times what I earned back home, and the company has been incredibly supportive with my relocation. My wife and children are now with me, and we''re building a wonderful life in the UK.', 
true),

('Fatima Al-Hassan', 'Egypt', 4, 
'I found my dream job as a software developer through CrownOpportunities. The application process was straightforward, and I appreciated how they matched my skills with the right opportunities. Moving from Cairo to Birmingham was a big step, but the company provided excellent support. I''m now working on fintech solutions and earning more than I ever imagined. The quality of life here is amazing!', 
true),

('Joseph Nakamura', 'Kenya', 5, 
'After years of working in hospitality in Nairobi, I wanted to take my career to the next level. CrownOpportunities helped me secure a Head Chef position at a prestigious Edinburgh hotel. The interview process was professional, and they helped with all the visa documentation. I''m now leading a team of 12 and have creative control over our award-winning menu. This opportunity has exceeded all my expectations.', 
true),

('Sarah Boateng', 'Ghana', 5, 
'Teaching has always been my passion, and CrownOpportunities made my UK teaching dream a reality. I now teach at an outstanding primary school in Liverpool, working with amazing colleagues and inspiring young minds. The school supported my visa application and helped me settle in. The work-life balance here is excellent, and I''m pursuing my Masters degree in the evenings. Thank you CrownOpportunities!', 
true),

('Ahmed Kone', 'Mali', 4, 
'I was an electrician in Bamako looking for better opportunities. Through CrownOpportunities, I found PowerTech Services in Bristol. They sponsored my visa and provided additional training to meet UK standards. I now earn five times my previous salary and have bought my first home. My family is planning to join me next year. The platform truly delivers on its promises.', 
true);

-- Get job IDs for applications (MySQL compatible)
SET @job1 = (SELECT id FROM jobs WHERE title = 'Senior Staff Nurse' LIMIT 1);
SET @job2 = (SELECT id FROM jobs WHERE title = 'Construction Project Manager' LIMIT 1);
SET @job3 = (SELECT id FROM jobs WHERE title = 'Head Chef' LIMIT 1);
SET @job4 = (SELECT id FROM jobs WHERE title = 'Senior Software Developer' LIMIT 1);
SET @job5 = (SELECT id FROM jobs WHERE title = 'Qualified Electrician' LIMIT 1);

-- Insert sample applications
INSERT INTO applications (job_id, first_name, last_name, email, phone, current_location, cover_letter, experience, previous_role, status) VALUES 

(@job1, 'Grace', 'Adebayo', 'grace.adebayo@email.com', '+234 801 234 5678', 'Lagos, Nigeria',
'I am writing to express my strong interest in the Senior Staff Nurse position. With 5 years of experience in critical care nursing and a passion for patient advocacy, I am excited about the opportunity to contribute to your healthcare team while advancing my career in the UK.',
'5+ years', 'Critical Care Nurse', 'pending'),

(@job2, 'Michael', 'Osei', 'michael.osei@email.com', '+233 24 123 4567', 'Accra, Ghana',
'As a construction professional with 8 years of project management experience, I am thrilled to apply for this position. I have successfully managed projects worth over $2M and am eager to bring my skills to the UK construction industry.',
'8 years', 'Site Manager', 'reviewed'),

(@job3, 'Yusuf', 'Hassan', 'yusuf.hassan@email.com', '+20 10 1234 5678', 'Cairo, Egypt',
'Culinary arts is my passion, and I have spent 10 years perfecting my craft in high-end restaurants. I am excited about the opportunity to lead your kitchen team and create exceptional dining experiences for your guests.',
'10+ years', 'Sous Chef', 'shortlisted'),

(@job4, 'Priya', 'Sharma', 'priya.sharma@email.com', '+91 98765 43210', 'Mumbai, India',
'I am a full-stack developer with 6 years of experience in fintech applications. My expertise in React, Node.js, and cloud technologies aligns perfectly with your requirements. I am excited about contributing to innovative financial solutions.',
'6 years', 'Full Stack Developer', 'interview'),

(@job5, 'Emmanuel', 'Kiprop', 'emmanuel.kiprop@email.com', '+254 712 345 678', 'Nairobi, Kenya',
'With my City & Guilds certification and 4 years of electrical experience, I am ready to contribute to your team. I have worked on various projects from residential to industrial installations and am committed to maintaining the highest safety standards.',
'4 years', 'Electrical Technician', 'pending');

-- Insert sample contact messages
INSERT INTO messages (first_name, last_name, email, subject, message, is_read) VALUES 

('James', 'Mwangi', 'james.mwangi@email.com', 'Inquiry about Healthcare Opportunities', 
'Hello, I am a qualified pharmacist from Kenya with 7 years of experience. I would like to know more about pharmacy opportunities in the UK and the visa sponsorship process. Could you please provide more information about requirements and timeline?', 
false),

('Aisha', 'Camara', 'aisha.camara@email.com', 'Construction Jobs - Civil Engineer', 
'Good day, I am a civil engineer from Senegal with expertise in infrastructure projects. I am interested in opportunities in the UK construction sector. Please let me know what positions might be available for someone with my background.', 
false),

('Robert', 'Asante', 'robert.asante@email.com', 'Teaching Opportunities', 
'I am a secondary school mathematics teacher from Ghana with 12 years of experience. I hold a Bachelor''s degree in Mathematics Education and would love to teach in the UK. What are the requirements for international teachers?', 
true),

('Fatou', 'Diallo', 'fatou.diallo@email.com', 'Hospitality Management Position', 
'I have been working in hotel management for 8 years in Ivory Coast. I am fluent in English and French and would like to explore opportunities in the UK hospitality industry. Could you guide me on the application process?', 
false),

('Peter', 'Owusu', 'peter.owusu@email.com', 'IT Support Specialist', 
'Hello, I am an IT professional with experience in network administration and technical support. I am currently based in Ghana and looking for opportunities to work in the UK. Please advise on available positions and visa requirements.', 
false);