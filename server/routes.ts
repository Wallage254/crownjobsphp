import express, { type Express } from "express";
import { createServer, type Server } from "http";
import { storage } from "./storage";
import { insertJobSchema, insertApplicationSchema, insertTestimonialSchema, insertMessageSchema, insertCategorySchema } from "@shared/schema";
import multer from "multer";
import path from "path";
import fs from "fs";

// Configure multer for file uploads
const uploadDir = path.join(process.cwd(), 'uploads');
if (!fs.existsSync(uploadDir)) {
  fs.mkdirSync(uploadDir, { recursive: true });
}

const upload = multer({
  storage: multer.diskStorage({
    destination: uploadDir,
    filename: (req, file, cb) => {
      const uniqueSuffix = Date.now() + '-' + Math.round(Math.random() * 1E9);
      cb(null, file.fieldname + '-' + uniqueSuffix + path.extname(file.originalname));
    }
  }),
  limits: {
    fileSize: 10 * 1024 * 1024, // 10MB limit
  },
  fileFilter: (req, file, cb) => {
    if (file.fieldname === 'cv') {
      // Accept PDF, DOC, DOCX for CV
      const allowedTypes = ['.pdf', '.doc', '.docx'];
      const ext = path.extname(file.originalname).toLowerCase();
      cb(null, allowedTypes.includes(ext));
    } else {
      // Accept images for photos
      const allowedTypes = ['.jpg', '.jpeg', '.png', '.gif', '.webp'];
      const ext = path.extname(file.originalname).toLowerCase();
      cb(null, allowedTypes.includes(ext));
    }
  }
});

export async function registerRoutes(app: Express): Promise<Server> {
  
  // Serve uploaded files
  app.use('/uploads', (req, res, next) => {
    res.header('Access-Control-Allow-Origin', '*');
    next();
  });
  app.use('/uploads', express.static(uploadDir));

  // Job routes
  app.get("/api/jobs", async (req, res) => {
    try {
      const { category, location, search, salaryMin, salaryMax } = req.query;
      const filters = {
        category: category as string,
        location: location as string,
        search: search as string,
        salaryMin: salaryMin ? parseInt(salaryMin as string) : undefined,
        salaryMax: salaryMax ? parseInt(salaryMax as string) : undefined,
      };
      
      // Remove undefined values
      Object.keys(filters).forEach(key => {
        if (filters[key as keyof typeof filters] === undefined || filters[key as keyof typeof filters] === '') {
          delete filters[key as keyof typeof filters];
        }
      });
      
      const jobs = await storage.getAllJobs(Object.keys(filters).length > 0 ? filters : undefined);
      res.json(jobs);
    } catch (error) {
      res.status(500).json({ message: "Failed to fetch jobs", error: (error as Error).message });
    }
  });

  app.get("/api/jobs/:id", async (req, res) => {
    try {
      const job = await storage.getJobById(req.params.id);
      if (!job) {
        return res.status(404).json({ message: "Job not found" });
      }
      res.json(job);
    } catch (error) {
      res.status(500).json({ message: "Failed to fetch job", error: (error as Error).message });
    }
  });

  app.post("/api/jobs", upload.fields([
    { name: 'companyLogo', maxCount: 1 },
    { name: 'workplaceImages', maxCount: 5 }
  ]), async (req, res) => {
    try {
      const jobData = insertJobSchema.parse(req.body);
      
      // Handle file uploads
      if (req.files) {
        const files = req.files as { [fieldname: string]: Express.Multer.File[] };
        
        if (files.companyLogo && files.companyLogo[0]) {
          jobData.companyLogo = `/uploads/${files.companyLogo[0].filename}`;
        }
        
        if (files.workplaceImages) {
          jobData.workplaceImages = files.workplaceImages.map(file => `/uploads/${file.filename}`);
        }
      }
      
      const job = await storage.createJob(jobData);
      res.status(201).json(job);
    } catch (error) {
      res.status(400).json({ message: "Failed to create job", error: (error as Error).message });
    }
  });

  app.put("/api/jobs/:id", upload.fields([
    { name: 'companyLogo', maxCount: 1 },
    { name: 'workplaceImages', maxCount: 5 }
  ]), async (req, res) => {
    try {
      const jobData = { ...req.body };
      
      // Handle file uploads
      if (req.files) {
        const files = req.files as { [fieldname: string]: Express.Multer.File[] };
        
        if (files.companyLogo && files.companyLogo[0]) {
          jobData.companyLogo = `/uploads/${files.companyLogo[0].filename}`;
        }
        
        if (files.workplaceImages) {
          jobData.workplaceImages = files.workplaceImages.map(file => `/uploads/${file.filename}`);
        }
      }
      
      const job = await storage.updateJob(req.params.id, jobData);
      if (!job) {
        return res.status(404).json({ message: "Job not found" });
      }
      res.json(job);
    } catch (error) {
      res.status(400).json({ message: "Failed to update job", error: (error as Error).message });
    }
  });

  app.delete("/api/jobs/:id", async (req, res) => {
    try {
      const success = await storage.deleteJob(req.params.id);
      if (!success) {
        return res.status(404).json({ message: "Job not found" });
      }
      res.json({ message: "Job deleted successfully" });
    } catch (error) {
      res.status(500).json({ message: "Failed to delete job", error: (error as Error).message });
    }
  });

  // Application routes
  app.get("/api/applications", async (req, res) => {
    try {
      const { jobId } = req.query;
      let applications;
      
      if (jobId) {
        applications = await storage.getApplicationsByJobId(jobId as string);
      } else {
        applications = await storage.getAllApplications();
      }
      
      res.json(applications);
    } catch (error) {
      res.status(500).json({ message: "Failed to fetch applications", error: (error as Error).message });
    }
  });

  app.post("/api/applications", upload.fields([
    { name: 'profilePhoto', maxCount: 1 },
    { name: 'cv', maxCount: 1 }
  ]), async (req, res) => {
    try {
      console.log("Received application data:", req.body);
      console.log("Received files:", req.files);
      
      const applicationData = insertApplicationSchema.parse(req.body);
      
      // Handle file uploads
      if (req.files) {
        const files = req.files as { [fieldname: string]: Express.Multer.File[] };
        
        if (files.profilePhoto && files.profilePhoto[0]) {
          applicationData.profilePhoto = `/uploads/${files.profilePhoto[0].filename}`;
        }
        
        if (files.cv && files.cv[0]) {
          applicationData.cvFile = `/uploads/${files.cv[0].filename}`;
        }
      }
      
      const application = await storage.createApplication(applicationData);
      res.status(201).json(application);
    } catch (error) {
      console.error("Application creation error:", error);
      res.status(400).json({ message: "Failed to create application", error: (error as Error).message });
    }
  });

  app.patch("/api/applications/:id/status", async (req, res) => {
    try {
      const { status } = req.body;
      const application = await storage.updateApplicationStatus(req.params.id, status);
      if (!application) {
        return res.status(404).json({ message: "Application not found" });
      }
      res.json(application);
    } catch (error) {
      res.status(400).json({ message: "Failed to update application status", error: (error as Error).message });
    }
  });

  // Testimonial routes
  app.get("/api/testimonials", async (req, res) => {
    try {
      const { all } = req.query;
      let testimonials;
      
      if (all === 'true') {
        testimonials = await storage.getAllTestimonials();
      } else {
        testimonials = await storage.getActiveTestimonials();
      }
      
      res.json(testimonials);
    } catch (error) {
      res.status(500).json({ message: "Failed to fetch testimonials", error: error.message });
    }
  });

  app.post("/api/testimonials", upload.single('photo'), async (req, res) => {
    try {
      const testimonialData = insertTestimonialSchema.parse(req.body);
      
      // Handle photo upload
      if (req.file) {
        testimonialData.photo = `/uploads/${req.file.filename}`;
      }
      
      const testimonial = await storage.createTestimonial(testimonialData);
      res.status(201).json(testimonial);
    } catch (error) {
      res.status(400).json({ message: "Failed to create testimonial", error: error.message });
    }
  });

  app.put("/api/testimonials/:id", upload.single('photo'), async (req, res) => {
    try {
      const testimonialData = { ...req.body };
      
      // Handle photo upload
      if (req.file) {
        testimonialData.photo = `/uploads/${req.file.filename}`;
      }
      
      const testimonial = await storage.updateTestimonial(req.params.id, testimonialData);
      if (!testimonial) {
        return res.status(404).json({ message: "Testimonial not found" });
      }
      res.json(testimonial);
    } catch (error) {
      res.status(400).json({ message: "Failed to update testimonial", error: error.message });
    }
  });

  app.delete("/api/testimonials/:id", async (req, res) => {
    try {
      const success = await storage.deleteTestimonial(req.params.id);
      if (!success) {
        return res.status(404).json({ message: "Testimonial not found" });
      }
      res.json({ message: "Testimonial deleted successfully" });
    } catch (error) {
      res.status(500).json({ message: "Failed to delete testimonial", error: error.message });
    }
  });

  // Contact/Message routes
  app.get("/api/messages", async (req, res) => {
    try {
      const messages = await storage.getAllMessages();
      res.json(messages);
    } catch (error) {
      res.status(500).json({ message: "Failed to fetch messages", error: error.message });
    }
  });

  app.post("/api/contact", async (req, res) => {
    try {
      const messageData = insertMessageSchema.parse(req.body);
      const message = await storage.createMessage(messageData);
      res.status(201).json(message);
    } catch (error) {
      res.status(400).json({ message: "Failed to send message", error: error.message });
    }
  });

  app.patch("/api/messages/:id/read", async (req, res) => {
    try {
      const message = await storage.markMessageAsRead(req.params.id);
      if (!message) {
        return res.status(404).json({ message: "Message not found" });
      }
      res.json(message);
    } catch (error) {
      res.status(400).json({ message: "Failed to mark message as read", error: error.message });
    }
  });

  // Category routes
  app.get("/api/categories", async (req, res) => {
    try {
      const categories = await storage.getAllCategories();
      res.json(categories);
    } catch (error) {
      res.status(500).json({ message: "Failed to fetch categories", error: error.message });
    }
  });

  app.post("/api/categories", async (req, res) => {
    try {
      const categoryData = insertCategorySchema.parse(req.body);
      const category = await storage.createCategory(categoryData);
      res.status(201).json(category);
    } catch (error) {
      res.status(400).json({ message: "Failed to create category", error: error.message });
    }
  });

  app.put("/api/categories/:id", async (req, res) => {
    try {
      const categoryData = insertCategorySchema.parse(req.body);
      const category = await storage.updateCategory(req.params.id, categoryData);
      if (!category) {
        return res.status(404).json({ message: "Category not found" });
      }
      res.json(category);
    } catch (error) {
      res.status(400).json({ message: "Failed to update category", error: error.message });
    }
  });

  app.delete("/api/categories/:id", async (req, res) => {
    try {
      const success = await storage.deleteCategory(req.params.id);
      if (!success) {
        return res.status(404).json({ message: "Category not found" });
      }
      res.json({ message: "Category deleted successfully" });
    } catch (error) {
      res.status(500).json({ message: "Failed to delete category", error: error.message });
    }
  });

  const httpServer = createServer(app);
  return httpServer;
}
