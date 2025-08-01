# Overview

CrownOpportunities is a modern UK job board application specifically designed to connect African professionals with job opportunities in the United Kingdom. The platform focuses on sectors like construction, healthcare, hospitality, and skilled trades, with particular emphasis on visa sponsorship opportunities. Built as a full-stack web application, it features a React frontend with shadcn/ui components, Express.js backend, and PostgreSQL database managed through Drizzle ORM.

# User Preferences

Preferred communication style: Simple, everyday language.

# System Architecture

## Frontend Architecture
- **Framework**: React 18 with TypeScript using Vite as the build tool
- **UI Framework**: shadcn/ui component library built on Radix UI primitives
- **Styling**: Tailwind CSS with custom CSS variables for brand theming
- **Routing**: Wouter for lightweight client-side routing
- **State Management**: TanStack Query (React Query) for server state management
- **Form Handling**: React Hook Form with Zod validation
- **File Structure**: Component-based architecture with separate pages, components, and utility folders

## Backend Architecture
- **Runtime**: Node.js with Express.js framework
- **Language**: TypeScript with ES modules
- **API Design**: RESTful API endpoints with proper HTTP methods and status codes
- **File Uploads**: Multer middleware for handling CV uploads and image files
- **Error Handling**: Centralized error handling middleware with proper status codes
- **Static Files**: Express static middleware for serving uploaded files

## Database Design
- **Database**: PostgreSQL with Neon serverless connection
- **ORM**: Drizzle ORM for type-safe database operations
- **Schema Management**: Drizzle Kit for migrations and schema synchronization
- **Core Tables**:
  - `jobs` - Job postings with company details, requirements, and metadata
  - `applications` - User applications linked to jobs with personal information and file uploads
  - `testimonials` - Success stories with photos and ratings
  - `messages` - Contact form submissions
  - `users` - Admin authentication system

## Authentication & Authorization
- **Admin System**: Simple username/password authentication for administrative functions
- **Session Management**: Token-based authentication for admin routes
- **Access Control**: Protected admin endpoints for job management, application review, and testimonial curation

## File Management
- **Storage**: Local file system with organized upload directory structure
- **File Types**: Support for PDF/DOC/DOCX for CVs and various image formats for photos
- **File Serving**: Static file serving with proper CORS headers
- **Upload Limits**: 10MB file size limit with file type validation

## Development Environment
- **Build System**: Vite for frontend bundling with esbuild for backend compilation
- **Development Server**: Hot module replacement for frontend, nodemon-like functionality for backend
- **Type Safety**: Full TypeScript coverage across frontend, backend, and shared schemas
- **Code Quality**: Consistent import/export patterns and modular component structure

# External Dependencies

## Core Framework Dependencies
- **@neondatabase/serverless** - PostgreSQL serverless database connection
- **drizzle-orm** & **drizzle-kit** - Type-safe ORM and migration tools
- **@tanstack/react-query** - Server state management and caching
- **react-hook-form** & **@hookform/resolvers** - Form handling with validation
- **zod** & **drizzle-zod** - Schema validation and type inference

## UI Component Libraries
- **@radix-ui/react-*** - Comprehensive set of accessible UI primitives
- **lucide-react** - Icon library for consistent iconography
- **tailwindcss** - Utility-first CSS framework
- **class-variance-authority** & **clsx** - Conditional styling utilities

## Backend Dependencies
- **express** - Web application framework
- **multer** - File upload handling middleware
- **ws** - WebSocket library for Neon database connection
- **connect-pg-simple** - PostgreSQL session store (configured but not actively used)

## Development Tools
- **vite** & **@vitejs/plugin-react** - Frontend build tooling
- **esbuild** - Fast backend compilation
- **tsx** - TypeScript execution for development
- **@replit/vite-plugin-runtime-error-modal** - Development error overlay
- **@replit/vite-plugin-cartographer** - Replit-specific development features

## Utility Libraries
- **date-fns** - Date manipulation and formatting
- **wouter** - Lightweight routing for React applications
- **nanoid** - Unique ID generation for various use cases