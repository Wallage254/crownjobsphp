// Common types and interfaces for the application

export interface SearchFilters {
  category?: string;
  location?: string;
  search?: string;
  salaryMin?: number;
  salaryMax?: number;
}

export interface JobFilters {
  salaryRange?: string;
  jobTypes?: string[];
  ukLocation?: string;
  experience?: string;
}

export interface ApiError {
  message: string;
  status?: number;
}

export interface FileUpload {
  file: File;
  preview?: string;
}

export interface ApplicationFormFiles {
  profilePhoto?: File;
  cv?: File;
}

export interface JobFormFiles {
  companyLogo?: File;
  workplaceImages?: File[];
}

export interface TestimonialFormFiles {
  photo?: File;
}

// Navigation and routing types
export interface NavItem {
  href: string;
  label: string;
  external?: boolean;
}

// Component prop types
export interface WithChildren {
  children: React.ReactNode;
}

export interface WithClassName {
  className?: string;
}

// Form validation types
export interface FormErrors {
  [key: string]: string | undefined;
}

// API response types
export interface ApiResponse<T = any> {
  data?: T;
  message?: string;
  error?: string;
}

// Pagination types
export interface PaginationParams {
  page: number;
  limit: number;
  total?: number;
}

// Sort options
export type SortOption = 'recent' | 'salary-high' | 'salary-low' | 'relevant';

// Status types
export type ApplicationStatus = 'pending' | 'accepted' | 'rejected';
export type JobType = 'Full-time' | 'Part-time' | 'Contract' | 'Temporary';
export type ExperienceLevel = 'entry' | 'mid' | 'senior';

// Admin dashboard types
export interface DashboardStats {
  totalJobs: number;
  totalApplications: number;
  totalTestimonials: number;
  totalMessages: number;
  pendingApplications: number;
  activeJobs: number;
}

export interface AdminTabItem {
  id: string;
  label: string;
  icon: React.ComponentType<any>;
  count?: number;
}

// File upload constraints
export const FILE_CONSTRAINTS = {
  maxSize: 10 * 1024 * 1024, // 10MB
  allowedImageTypes: ['.jpg', '.jpeg', '.png', '.gif', '.webp'],
  allowedDocumentTypes: ['.pdf', '.doc', '.docx'],
} as const;

// Application constants
export const CATEGORIES = [
  'Construction',
  'Healthcare', 
  'Hospitality',
  'Skilled Trades'
] as const;

export const UK_LOCATIONS = [
  'London',
  'Manchester',
  'Birmingham',
  'Liverpool',
  'Glasgow',
  'Edinburgh',
  'Leeds',
  'Bristol'
] as const;

export const AFRICAN_COUNTRIES = [
  'Nigeria',
  'Kenya',
  'Ghana',
  'South Africa',
  'Uganda',
  'Rwanda',
  'Tanzania',
  'Ethiopia',
  'Morocco',
  'Egypt',
  'Cameroon',
  'Zambia'
] as const;

export type Category = typeof CATEGORIES[number];
export type UKLocation = typeof UK_LOCATIONS[number];
export type AfricanCountry = typeof AFRICAN_COUNTRIES[number];
