import { 
  users, jobs, applications, testimonials, messages, categories,
  type User, type InsertUser, type Job, type InsertJob, 
  type Application, type InsertApplication, type Testimonial, 
  type InsertTestimonial, type Message, type InsertMessage,
  type Category, type InsertCategory
} from "@shared/schema";
import { db } from "./db";
import { eq, desc, and, or, ilike, gte, lte } from "drizzle-orm";

export interface IStorage {
  // User methods
  getUser(id: string): Promise<User | undefined>;
  getUserByUsername(username: string): Promise<User | undefined>;
  createUser(user: InsertUser): Promise<User>;

  // Job methods
  getAllJobs(filters?: {
    category?: string;
    location?: string;
    search?: string;
    salaryMin?: number;
    salaryMax?: number;
  }): Promise<Job[]>;
  getJobById(id: string): Promise<Job | undefined>;
  createJob(job: InsertJob): Promise<Job>;
  updateJob(id: string, job: Partial<InsertJob>): Promise<Job | undefined>;
  deleteJob(id: string): Promise<boolean>;

  // Application methods
  getApplicationsByJobId(jobId: string): Promise<Application[]>;
  getAllApplications(): Promise<Application[]>;
  createApplication(application: InsertApplication): Promise<Application>;
  updateApplicationStatus(id: string, status: string): Promise<Application | undefined>;

  // Testimonial methods
  getActiveTestimonials(): Promise<Testimonial[]>;
  getAllTestimonials(): Promise<Testimonial[]>;
  createTestimonial(testimonial: InsertTestimonial): Promise<Testimonial>;
  updateTestimonial(id: string, testimonial: Partial<InsertTestimonial>): Promise<Testimonial | undefined>;
  deleteTestimonial(id: string): Promise<boolean>;

  // Message methods
  getAllMessages(): Promise<Message[]>;
  createMessage(message: InsertMessage): Promise<Message>;
  markMessageAsRead(id: string): Promise<Message | undefined>;

  // Category methods
  getAllCategories(): Promise<Category[]>;
  createCategory(category: InsertCategory): Promise<Category>;
  updateCategory(id: string, category: Partial<InsertCategory>): Promise<Category | undefined>;
  deleteCategory(id: string): Promise<boolean>;
}

export class DatabaseStorage implements IStorage {
  // User methods
  async getUser(id: string): Promise<User | undefined> {
    const [user] = await db.select().from(users).where(eq(users.id, id));
    return user || undefined;
  }

  async getUserByUsername(username: string): Promise<User | undefined> {
    const [user] = await db.select().from(users).where(eq(users.username, username));
    return user || undefined;
  }

  async createUser(insertUser: InsertUser): Promise<User> {
    const [user] = await db
      .insert(users)
      .values(insertUser)
      .returning();
    return user;
  }

  // Job methods
  async getAllJobs(filters?: {
    category?: string;
    location?: string;
    search?: string;
    salaryMin?: number;
    salaryMax?: number;
  }): Promise<Job[]> {
    let query = db.select().from(jobs);
    
    if (filters) {
      const conditions = [];
      
      if (filters.category) {
        conditions.push(eq(jobs.category, filters.category));
      }
      
      if (filters.location) {
        conditions.push(ilike(jobs.location, `%${filters.location}%`));
      }
      
      if (filters.search) {
        conditions.push(
          or(
            ilike(jobs.title, `%${filters.search}%`),
            ilike(jobs.description, `%${filters.search}%`),
            ilike(jobs.company, `%${filters.search}%`)
          )
        );
      }
      
      if (filters.salaryMin) {
        conditions.push(gte(jobs.salaryMin, filters.salaryMin));
      }
      
      if (filters.salaryMax) {
        conditions.push(lte(jobs.salaryMax, filters.salaryMax));
      }
      
      if (conditions.length > 0) {
        query = query.where(and(...conditions)) as typeof query;
      }
    }
    
    return await query.orderBy(desc(jobs.createdAt));
  }

  async getJobById(id: string): Promise<Job | undefined> {
    const [job] = await db.select().from(jobs).where(eq(jobs.id, id));
    return job || undefined;
  }

  async createJob(insertJob: InsertJob): Promise<Job> {
    const [job] = await db
      .insert(jobs)
      .values(insertJob)
      .returning();
    return job;
  }

  async updateJob(id: string, updateJob: Partial<InsertJob>): Promise<Job | undefined> {
    const [job] = await db
      .update(jobs)
      .set({ ...updateJob, updatedAt: new Date() })
      .where(eq(jobs.id, id))
      .returning();
    return job || undefined;
  }

  async deleteJob(id: string): Promise<boolean> {
    const result = await db.delete(jobs).where(eq(jobs.id, id));
    return (result.rowCount ?? 0) > 0;
  }

  // Application methods
  async getApplicationsByJobId(jobId: string): Promise<Application[]> {
    return await db.select().from(applications).where(eq(applications.jobId, jobId)).orderBy(desc(applications.createdAt));
  }

  async getAllApplications(): Promise<Application[]> {
    return await db.select().from(applications).orderBy(desc(applications.createdAt));
  }

  async createApplication(insertApplication: InsertApplication): Promise<Application> {
    const [application] = await db
      .insert(applications)
      .values(insertApplication)
      .returning();
    return application;
  }

  async updateApplicationStatus(id: string, status: string): Promise<Application | undefined> {
    const [application] = await db
      .update(applications)
      .set({ status })
      .where(eq(applications.id, id))
      .returning();
    return application || undefined;
  }

  // Testimonial methods
  async getActiveTestimonials(): Promise<Testimonial[]> {
    return await db.select().from(testimonials).where(eq(testimonials.isActive, true)).orderBy(desc(testimonials.createdAt));
  }

  async getAllTestimonials(): Promise<Testimonial[]> {
    return await db.select().from(testimonials).orderBy(desc(testimonials.createdAt));
  }

  async createTestimonial(insertTestimonial: InsertTestimonial): Promise<Testimonial> {
    const [testimonial] = await db
      .insert(testimonials)
      .values(insertTestimonial)
      .returning();
    return testimonial;
  }

  async updateTestimonial(id: string, updateTestimonial: Partial<InsertTestimonial>): Promise<Testimonial | undefined> {
    const [testimonial] = await db
      .update(testimonials)
      .set(updateTestimonial)
      .where(eq(testimonials.id, id))
      .returning();
    return testimonial || undefined;
  }

  async deleteTestimonial(id: string): Promise<boolean> {
    const result = await db.delete(testimonials).where(eq(testimonials.id, id));
    return (result.rowCount ?? 0) > 0;
  }

  // Message methods
  async getAllMessages(): Promise<Message[]> {
    return await db.select().from(messages).orderBy(desc(messages.createdAt));
  }

  async createMessage(insertMessage: InsertMessage): Promise<Message> {
    const [message] = await db
      .insert(messages)
      .values(insertMessage)
      .returning();
    return message;
  }

  async markMessageAsRead(id: string): Promise<Message | undefined> {
    const [message] = await db
      .update(messages)
      .set({ isRead: true })
      .where(eq(messages.id, id))
      .returning();
    return message || undefined;
  }

  // Category methods
  async getAllCategories(): Promise<Category[]> {
    return await db.select().from(categories).where(eq(categories.isActive, true)).orderBy(categories.name);
  }

  async createCategory(insertCategory: InsertCategory): Promise<Category> {
    const [category] = await db
      .insert(categories)
      .values(insertCategory)
      .returning();
    return category;
  }

  async updateCategory(id: string, updateCategory: Partial<InsertCategory>): Promise<Category | undefined> {
    const [category] = await db
      .update(categories)
      .set(updateCategory)
      .where(eq(categories.id, id))
      .returning();
    return category || undefined;
  }

  async deleteCategory(id: string): Promise<boolean> {
    const result = await db.delete(categories).where(eq(categories.id, id));
    return (result.rowCount ?? 0) > 0;
  }
}

export const storage = new DatabaseStorage();
