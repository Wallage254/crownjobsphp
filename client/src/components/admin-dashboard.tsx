import { useState } from "react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Badge } from "@/components/ui/badge";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { 
  Briefcase, 
  Users, 
  MessageSquare, 
  Upload, 
  Edit, 
  Trash2, 
  Eye, 
  CheckCircle, 
  XCircle,
  Star,
  Plus,
  Building,
  MapPin,
  DollarSign
} from "lucide-react";
import { insertJobSchema, insertTestimonialSchema } from "@shared/schema";
import { z } from "zod";
import { useToast } from "@/hooks/use-toast";
import { apiRequest } from "@/lib/queryClient";
import type { Job, Application, Testimonial, Message, Category } from "@shared/schema";

const jobFormSchema = insertJobSchema.extend({
  companyLogo: z.any().optional(),
  workplaceImages: z.any().optional()
});

const testimonialFormSchema = insertTestimonialSchema.extend({
  photo: z.any().optional(),
  videoUrl: z.string().url().optional().or(z.literal(""))
});

const categoryFormSchema = z.object({
  name: z.string().min(1, "Category name is required"),
  description: z.string().optional(),
  gifUrl: z.string().url("Must be a valid URL").optional().or(z.literal("")),
  isActive: z.boolean().default(true)
});

type JobFormData = z.infer<typeof jobFormSchema>;
type TestimonialFormData = z.infer<typeof testimonialFormSchema>;
type CategoryFormData = z.infer<typeof categoryFormSchema>;

export default function AdminDashboard() {
  const [activeTab, setActiveTab] = useState("jobs");
  const { toast } = useToast();
  const queryClient = useQueryClient();

  // Fetch data
  const { data: jobs, isLoading: jobsLoading } = useQuery<Job[]>({
    queryKey: ['/api/jobs'],
  });

  const { data: applications, isLoading: applicationsLoading } = useQuery<Application[]>({
    queryKey: ['/api/applications'],
  });

  const { data: testimonials, isLoading: testimonialsLoading } = useQuery<Testimonial[]>({
    queryKey: ['/api/testimonials', 'all'],
    queryFn: async () => {
      const response = await fetch('/api/testimonials?all=true');
      if (!response.ok) throw new Error('Failed to fetch testimonials');
      return response.json();
    }
  });

  const { data: messages, isLoading: messagesLoading } = useQuery<Message[]>({
    queryKey: ['/api/messages'],
  });

  const { data: categories, isLoading: categoriesLoading } = useQuery<Category[]>({
    queryKey: ['/api/categories'],
  });

  // Job form
  const jobForm = useForm<JobFormData>({
    resolver: zodResolver(jobFormSchema),
    defaultValues: {
      title: "",
      company: "",
      category: "",
      location: "",
      description: "",
      requirements: "",
      salaryMin: 0,
      salaryMax: 0,
      jobType: "Full-time",
      isUrgent: false,
      visaSponsored: true
    }
  });

  // Testimonial form
  const testimonialForm = useForm<TestimonialFormData>({
    resolver: zodResolver(testimonialFormSchema),
    defaultValues: {
      name: "",
      country: "",
      rating: 5,
      comment: "",
      videoUrl: "",
      isActive: true
    }
  });

  // Category form
  const categoryForm = useForm<CategoryFormData>({
    resolver: zodResolver(categoryFormSchema),
    defaultValues: {
      name: "",
      description: "",
      gifUrl: "",
      isActive: true
    }
  });

  // Mutations
  const createJobMutation = useMutation({
    mutationFn: async (data: FormData) => {
      return await apiRequest('POST', '/api/jobs', data);
    },
    onSuccess: () => {
      toast({ title: "Job Created", description: "Job posted successfully!" });
      queryClient.invalidateQueries({ queryKey: ['/api/jobs'] });
      jobForm.reset();
    },
    onError: (error) => {
      toast({ title: "Error", description: error.message, variant: "destructive" });
    }
  });

  const deleteJobMutation = useMutation({
    mutationFn: async (id: string) => {
      return await apiRequest('DELETE', `/api/jobs/${id}`);
    },
    onSuccess: () => {
      toast({ title: "Job Deleted", description: "Job removed successfully!" });
      queryClient.invalidateQueries({ queryKey: ['/api/jobs'] });
    },
    onError: (error) => {
      toast({ title: "Error", description: error.message, variant: "destructive" });
    }
  });

  const updateApplicationStatusMutation = useMutation({
    mutationFn: async ({ id, status }: { id: string; status: string }) => {
      return await apiRequest('PATCH', `/api/applications/${id}/status`, { status });
    },
    onSuccess: () => {
      toast({ title: "Status Updated", description: "Application status updated successfully!" });
      queryClient.invalidateQueries({ queryKey: ['/api/applications'] });
    },
    onError: (error) => {
      toast({ title: "Error", description: error.message, variant: "destructive" });
    }
  });

  const createTestimonialMutation = useMutation({
    mutationFn: async (data: FormData) => {
      return await apiRequest('POST', '/api/testimonials', data);
    },
    onSuccess: () => {
      toast({ title: "Testimonial Added", description: "Testimonial created successfully!" });
      queryClient.invalidateQueries({ queryKey: ['/api/testimonials'] });
      testimonialForm.reset();
    },
    onError: (error) => {
      toast({ title: "Error", description: error.message, variant: "destructive" });
    }
  });

  const deleteTestimonialMutation = useMutation({
    mutationFn: async (id: string) => {
      return await apiRequest('DELETE', `/api/testimonials/${id}`);
    },
    onSuccess: () => {
      toast({ title: "Testimonial Deleted", description: "Testimonial removed successfully!" });
      queryClient.invalidateQueries({ queryKey: ['/api/testimonials'] });
    },
    onError: (error) => {
      toast({ title: "Error", description: error.message, variant: "destructive" });
    }
  });

  // Category mutations
  const createCategoryMutation = useMutation({
    mutationFn: async (data: CategoryFormData) => {
      return await apiRequest('POST', '/api/categories', data);
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['/api/categories'] });
      categoryForm.reset();
      toast({ title: "Success", description: "Category created successfully" });
    },
    onError: () => {
      toast({ title: "Error", description: "Failed to create category", variant: "destructive" });
    },
  });

  const deleteCategoryMutation = useMutation({
    mutationFn: async (id: string) => {
      return await apiRequest('DELETE', `/api/categories/${id}`);
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['/api/categories'] });
      toast({ title: "Success", description: "Category deleted successfully" });
    },
    onError: () => {
      toast({ title: "Error", description: "Failed to delete category", variant: "destructive" });
    },
  });

  // Form handlers
  const onSubmitJob = async (data: JobFormData) => {
    const formData = new FormData();
    
    Object.keys(data).forEach(key => {
      if (key !== 'companyLogo' && key !== 'workplaceImages') {
        formData.append(key, data[key as keyof JobFormData] as string);
      }
    });

    const logoInput = document.querySelector('input[name="companyLogo"]') as HTMLInputElement;
    const imagesInput = document.querySelector('input[name="workplaceImages"]') as HTMLInputElement;
    
    if (logoInput?.files?.[0]) {
      formData.append('companyLogo', logoInput.files[0]);
    }
    
    if (imagesInput?.files) {
      Array.from(imagesInput.files).forEach(file => {
        formData.append('workplaceImages', file);
      });
    }

    createJobMutation.mutate(formData);
  };

  const onSubmitTestimonial = async (data: TestimonialFormData) => {
    const formData = new FormData();
    
    Object.keys(data).forEach(key => {
      if (key !== 'photo') {
        formData.append(key, data[key as keyof TestimonialFormData] as string);
      }
    });

    const photoInput = document.querySelector('input[name="testimonialPhoto"]') as HTMLInputElement;
    if (photoInput?.files?.[0]) {
      formData.append('photo', photoInput.files[0]);
    }

    createTestimonialMutation.mutate(formData);
  };

  const onSubmitCategory = async (data: CategoryFormData) => {
    createCategoryMutation.mutate(data);
  };

  const getStatusBadge = (status: string) => {
    switch (status) {
      case 'pending':
        return <Badge variant="secondary">Under Review</Badge>;
      case 'accepted':
        return <Badge className="bg-green-100 text-green-800">Accepted</Badge>;
      case 'rejected':
        return <Badge variant="destructive">Rejected</Badge>;
      default:
        return <Badge variant="outline">{status}</Badge>;
    }
  };

  const renderStars = (rating: number) => {
    return (
      <div className="flex">
        {[1, 2, 3, 4, 5].map((star) => (
          <Star 
            key={star} 
            className={`w-4 h-4 ${star <= rating ? 'text-yellow-400 fill-current' : 'text-gray-300'}`}
          />
        ))}
      </div>
    );
  };

  return (
    <div className="min-h-screen bg-gray-50 py-8">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="mb-8">
          <h1 className="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
          <p className="text-gray-600 mt-2">Manage jobs, applications, and testimonials</p>
        </div>

        <Tabs value={activeTab} onValueChange={setActiveTab} className="space-y-6">
          <TabsList className="grid w-full grid-cols-5">
            <TabsTrigger value="jobs" className="flex items-center space-x-2">
              <Briefcase className="w-4 h-4" />
              <span>Jobs</span>
            </TabsTrigger>
            <TabsTrigger value="applications" className="flex items-center space-x-2">
              <Users className="w-4 h-4" />
              <span>Applications</span>
            </TabsTrigger>
            <TabsTrigger value="testimonials" className="flex items-center space-x-2">
              <Star className="w-4 h-4" />
              <span>Testimonials</span>
            </TabsTrigger>
            <TabsTrigger value="categories" className="flex items-center space-x-2">
              <Building className="w-4 h-4" />
              <span>Categories</span>
            </TabsTrigger>
            <TabsTrigger value="messages" className="flex items-center space-x-2">
              <MessageSquare className="w-4 h-4" />
              <span>Messages</span>
            </TabsTrigger>
          </TabsList>

          {/* Jobs Tab */}
          <TabsContent value="jobs" className="space-y-6">
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
              {/* Add New Job Form */}
              <Card>
                <CardContent className="p-6">
                  <h3 className="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <Plus className="w-5 h-5 mr-2" />
                    Add New Job
                  </h3>
                  <form onSubmit={jobForm.handleSubmit(onSubmitJob)} className="space-y-4">
                    <div>
                      <Label htmlFor="title">Job Title</Label>
                      <Input id="title" {...jobForm.register("title")} className="mt-1" />
                    </div>
                    
                    <div className="grid grid-cols-2 gap-4">
                      <div>
                        <Label htmlFor="company">Company</Label>
                        <Input id="company" {...jobForm.register("company")} className="mt-1" />
                      </div>
                      <div>
                        <Label htmlFor="category">Category</Label>
                        <Select onValueChange={(value) => jobForm.setValue("category", value)}>
                          <SelectTrigger className="mt-1">
                            <SelectValue placeholder="Select category" />
                          </SelectTrigger>
                          <SelectContent>
                            {categories?.map((category) => (
                              <SelectItem key={category.id} value={category.name}>
                                {category.name}
                              </SelectItem>
                            ))}
                          </SelectContent>
                        </Select>
                      </div>
                    </div>

                    <div>
                      <Label htmlFor="location">UK Location</Label>
                      <Input id="location" {...jobForm.register("location")} className="mt-1" />
                    </div>

                    <div className="grid grid-cols-2 gap-4">
                      <div>
                        <Label htmlFor="salaryMin">Min Salary (£)</Label>
                        <Input 
                          id="salaryMin" 
                          type="number" 
                          {...jobForm.register("salaryMin", { valueAsNumber: true })} 
                          className="mt-1" 
                        />
                      </div>
                      <div>
                        <Label htmlFor="salaryMax">Max Salary (£)</Label>
                        <Input 
                          id="salaryMax" 
                          type="number" 
                          {...jobForm.register("salaryMax", { valueAsNumber: true })} 
                          className="mt-1" 
                        />
                      </div>
                    </div>

                    <div>
                      <Label htmlFor="description">Job Description</Label>
                      <Textarea 
                        id="description" 
                        rows={4} 
                        {...jobForm.register("description")} 
                        className="mt-1" 
                      />
                    </div>

                    <div>
                      <Label htmlFor="requirements">Requirements</Label>
                      <Textarea 
                        id="requirements" 
                        rows={4} 
                        {...jobForm.register("requirements")} 
                        className="mt-1" 
                      />
                    </div>

                    <div>
                      <Label htmlFor="companyLogo">Company Logo</Label>
                      <Input 
                        id="companyLogo" 
                        name="companyLogo"
                        type="file" 
                        accept="image/*" 
                        className="mt-1" 
                      />
                    </div>

                    <div>
                      <Label htmlFor="workplaceImages">Workplace Images</Label>
                      <Input 
                        id="workplaceImages" 
                        name="workplaceImages"
                        type="file" 
                        accept="image/*" 
                        multiple 
                        className="mt-1" 
                      />
                    </div>

                    <Button 
                      type="submit" 
                      className="w-full"
                      disabled={createJobMutation.isPending}
                    >
                      {createJobMutation.isPending ? "Publishing..." : "Publish Job"}
                    </Button>
                  </form>
                </CardContent>
              </Card>

              {/* Recent Jobs List */}
              <Card>
                <CardContent className="p-6">
                  <h3 className="text-lg font-semibold text-gray-900 mb-4">Recent Jobs</h3>
                  {jobsLoading ? (
                    <div className="space-y-4">
                      {[1, 2, 3].map((i) => (
                        <div key={i} className="animate-pulse">
                          <div className="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
                          <div className="h-3 bg-gray-200 rounded w-1/2"></div>
                        </div>
                      ))}
                    </div>
                  ) : (
                    <div className="space-y-4">
                      {jobs?.slice(0, 5).map((job) => (
                        <div key={job.id} className="border border-gray-200 rounded-lg p-4">
                          <div className="flex justify-between items-start">
                            <div>
                              <h4 className="font-semibold text-gray-900">{job.title}</h4>
                              <p className="text-sm text-gray-600 flex items-center">
                                <Building className="w-4 h-4 mr-1" />
                                {job.company}
                              </p>
                              <p className="text-sm text-gray-500 flex items-center mt-1">
                                <MapPin className="w-4 h-4 mr-1" />
                                {job.location}
                              </p>
                              <p className="text-xs text-gray-500 mt-1">
                                Posted {new Date(job.createdAt).toLocaleDateString()}
                              </p>
                            </div>
                            <div className="flex space-x-2">
                              <Button variant="ghost" size="sm">
                                <Edit className="w-4 h-4" />
                              </Button>
                              <Button 
                                variant="ghost" 
                                size="sm" 
                                onClick={() => deleteJobMutation.mutate(job.id)}
                                disabled={deleteJobMutation.isPending}
                              >
                                <Trash2 className="w-4 h-4" />
                              </Button>
                            </div>
                          </div>
                        </div>
                      ))}
                    </div>
                  )}
                </CardContent>
              </Card>
            </div>
          </TabsContent>

          {/* Applications Tab */}
          <TabsContent value="applications">
            <Card>
              <CardContent className="p-6">
                <h3 className="text-lg font-semibold text-gray-900 mb-4">Job Applications</h3>
                {applicationsLoading ? (
                  <div className="space-y-4">
                    {[1, 2, 3].map((i) => (
                      <div key={i} className="animate-pulse">
                        <div className="h-16 bg-gray-200 rounded"></div>
                      </div>
                    ))}
                  </div>
                ) : (
                  <div className="overflow-x-auto">
                    <Table>
                      <TableHeader>
                        <TableRow>
                          <TableHead>Applicant</TableHead>
                          <TableHead>Job</TableHead>
                          <TableHead>Applied</TableHead>
                          <TableHead>Status</TableHead>
                          <TableHead>Actions</TableHead>
                        </TableRow>
                      </TableHeader>
                      <TableBody>
                        {applications?.map((application) => (
                          <TableRow key={application.id}>
                            <TableCell>
                              <div className="flex items-center space-x-3">
                                {application.profilePhoto && (
                                  <img 
                                    src={application.profilePhoto} 
                                    alt={`${application.firstName} ${application.lastName}`}
                                    className="w-10 h-10 rounded-full object-cover"
                                  />
                                )}
                                <div>
                                  <div className="font-medium text-gray-900">
                                    {application.firstName} {application.lastName}
                                  </div>
                                  <div className="text-sm text-gray-500">{application.email}</div>
                                </div>
                              </div>
                            </TableCell>
                            <TableCell>
                              <div className="text-sm text-gray-900">Job #{application.jobId.slice(-8)}</div>
                            </TableCell>
                            <TableCell>
                              <div className="text-sm text-gray-500">
                                {new Date(application.createdAt).toLocaleDateString()}
                              </div>
                            </TableCell>
                            <TableCell>
                              {getStatusBadge(application.status)}
                            </TableCell>
                            <TableCell>
                              <div className="flex space-x-2">
                                {application.cvFile && (
                                  <Button variant="ghost" size="sm">
                                    <Eye className="w-4 h-4 mr-1" />
                                    CV
                                  </Button>
                                )}
                                {application.status === 'pending' && (
                                  <>
                                    <Button 
                                      variant="ghost" 
                                      size="sm" 
                                      onClick={() => updateApplicationStatusMutation.mutate({
                                        id: application.id, 
                                        status: 'accepted'
                                      })}
                                      className="text-green-600 hover:text-green-700"
                                    >
                                      <CheckCircle className="w-4 h-4" />
                                    </Button>
                                    <Button 
                                      variant="ghost" 
                                      size="sm" 
                                      onClick={() => updateApplicationStatusMutation.mutate({
                                        id: application.id, 
                                        status: 'rejected'
                                      })}
                                      className="text-red-600 hover:text-red-700"
                                    >
                                      <XCircle className="w-4 h-4" />
                                    </Button>
                                  </>
                                )}
                              </div>
                            </TableCell>
                          </TableRow>
                        ))}
                      </TableBody>
                    </Table>
                  </div>
                )}
              </CardContent>
            </Card>
          </TabsContent>

          {/* Testimonials Tab */}
          <TabsContent value="testimonials" className="space-y-6">
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
              {/* Add Testimonial Form */}
              <Card>
                <CardContent className="p-6">
                  <h3 className="text-lg font-semibold text-gray-900 mb-4">Add Testimonial</h3>
                  <form onSubmit={testimonialForm.handleSubmit(onSubmitTestimonial)} className="space-y-4">
                    <div>
                      <Label htmlFor="name">Name</Label>
                      <Input id="name" {...testimonialForm.register("name")} className="mt-1" />
                    </div>
                    
                    <div>
                      <Label htmlFor="country">Country</Label>
                      <Input id="country" {...testimonialForm.register("country")} className="mt-1" />
                    </div>
                    
                    <div>
                      <Label htmlFor="rating">Rating</Label>
                      <Select onValueChange={(value) => testimonialForm.setValue("rating", parseInt(value))}>
                        <SelectTrigger className="mt-1">
                          <SelectValue placeholder="Select rating" />
                        </SelectTrigger>
                        <SelectContent>
                          <SelectItem value="5">5 Stars</SelectItem>
                          <SelectItem value="4">4 Stars</SelectItem>
                          <SelectItem value="3">3 Stars</SelectItem>
                          <SelectItem value="2">2 Stars</SelectItem>
                          <SelectItem value="1">1 Star</SelectItem>
                        </SelectContent>
                      </Select>
                    </div>
                    
                    <div>
                      <Label htmlFor="comment">Testimonial</Label>
                      <Textarea 
                        id="comment" 
                        rows={4} 
                        {...testimonialForm.register("comment")} 
                        className="mt-1" 
                      />
                    </div>
                    
                    <div>
                      <Label htmlFor="videoUrl">Video URL (optional)</Label>
                      <Input 
                        id="videoUrl" 
                        placeholder="https://youtube.com/..." 
                        {...testimonialForm.register("videoUrl")} 
                        className="mt-1" 
                      />
                    </div>
                    
                    <div>
                      <Label htmlFor="testimonialPhoto">Photo</Label>
                      <Input 
                        id="testimonialPhoto" 
                        name="testimonialPhoto"
                        type="file" 
                        accept="image/*" 
                        className="mt-1" 
                      />
                    </div>
                    
                    <Button 
                      type="submit" 
                      className="w-full"
                      disabled={createTestimonialMutation.isPending}
                    >
                      {createTestimonialMutation.isPending ? "Adding..." : "Add Testimonial"}
                    </Button>
                  </form>
                </CardContent>
              </Card>

              {/* Current Testimonials */}
              <Card>
                <CardContent className="p-6">
                  <h3 className="text-lg font-semibold text-gray-900 mb-4">Current Testimonials</h3>
                  {testimonialsLoading ? (
                    <div className="space-y-4">
                      {[1, 2, 3].map((i) => (
                        <div key={i} className="animate-pulse">
                          <div className="h-20 bg-gray-200 rounded"></div>
                        </div>
                      ))}
                    </div>
                  ) : (
                    <div className="space-y-4">
                      {testimonials?.map((testimonial) => (
                        <div key={testimonial.id} className="border border-gray-200 rounded-lg p-4">
                          <div className="flex justify-between items-start">
                            <div className="flex items-start space-x-3">
                              {testimonial.photo && (
                                <img 
                                  src={testimonial.photo} 
                                  alt={testimonial.name}
                                  className="w-12 h-12 rounded-full object-cover"
                                />
                              )}
                              <div>
                                <h4 className="font-semibold text-gray-900">{testimonial.name}</h4>
                                <p className="text-sm text-gray-600">{testimonial.country}</p>
                                {renderStars(testimonial.rating)}
                                <p className="text-sm text-gray-700 mt-2">{testimonial.comment}</p>
                                {testimonial.videoUrl && (
                                  <p className="text-xs text-blue-600 mt-1">
                                    Video: <a href={testimonial.videoUrl} target="_blank" rel="noopener noreferrer" className="underline">Watch</a>
                                  </p>
                                )}
                              </div>
                            </div>
                            <div className="flex space-x-2">
                              <Button variant="ghost" size="sm">
                                <Edit className="w-4 h-4" />
                              </Button>
                              <Button 
                                variant="ghost" 
                                size="sm" 
                                onClick={() => deleteTestimonialMutation.mutate(testimonial.id)}
                                disabled={deleteTestimonialMutation.isPending}
                              >
                                <Trash2 className="w-4 h-4" />
                              </Button>
                            </div>
                          </div>
                        </div>
                      ))}
                    </div>
                  )}
                </CardContent>
              </Card>
            </div>
          </TabsContent>

          {/* Categories Tab */}
          <TabsContent value="categories" className="space-y-6">
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
              {/* Add New Category Form */}
              <Card>
                <CardContent className="p-6">
                  <h3 className="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <Plus className="w-5 h-5 mr-2" />
                    Add New Category
                  </h3>
                  <form onSubmit={categoryForm.handleSubmit(onSubmitCategory)} className="space-y-4">
                    <div>
                      <Label htmlFor="categoryName">Category Name</Label>
                      <Input id="categoryName" {...categoryForm.register("name")} className="mt-1" />
                    </div>
                    
                    <div>
                      <Label htmlFor="categoryDescription">Description</Label>
                      <Textarea 
                        id="categoryDescription" 
                        rows={3} 
                        {...categoryForm.register("description")} 
                        className="mt-1" 
                      />
                    </div>
                    
                    <div>
                      <Label htmlFor="categoryGifUrl">GIF URL</Label>
                      <Input 
                        id="categoryGifUrl" 
                        placeholder="https://media.giphy.com/..." 
                        {...categoryForm.register("gifUrl")} 
                        className="mt-1" 
                      />
                    </div>
                    
                    <Button 
                      type="submit" 
                      className="w-full"
                      disabled={createCategoryMutation.isPending}
                    >
                      {createCategoryMutation.isPending ? "Adding..." : "Add Category"}
                    </Button>
                  </form>
                </CardContent>
              </Card>

              {/* Current Categories */}
              <Card>
                <CardContent className="p-6">
                  <h3 className="text-lg font-semibold text-gray-900 mb-4">Current Categories</h3>
                  {categoriesLoading ? (
                    <div className="space-y-4">
                      {[1, 2, 3].map((i) => (
                        <div key={i} className="animate-pulse">
                          <div className="h-20 bg-gray-200 rounded"></div>
                        </div>
                      ))}
                    </div>
                  ) : (
                    <div className="space-y-4">
                      {categories?.map((category) => (
                        <div key={category.id} className="border border-gray-200 rounded-lg p-4">
                          <div className="flex justify-between items-start">
                            <div className="flex items-start space-x-3">
                              {category.gifUrl && (
                                <img 
                                  src={category.gifUrl} 
                                  alt={category.name}
                                  className="w-16 h-16 rounded object-cover"
                                />
                              )}
                              <div>
                                <h4 className="font-semibold text-gray-900">{category.name}</h4>
                                <p className="text-sm text-gray-600">{category.description}</p>
                                <Badge variant={category.isActive ? "default" : "secondary"} className="mt-2">
                                  {category.isActive ? "Active" : "Inactive"}
                                </Badge>
                              </div>
                            </div>
                            <div className="flex space-x-2">
                              <Button variant="ghost" size="sm">
                                <Edit className="w-4 h-4" />
                              </Button>
                              <Button 
                                variant="ghost" 
                                size="sm" 
                                onClick={() => deleteCategoryMutation.mutate(category.id)}
                                disabled={deleteCategoryMutation.isPending}
                              >
                                <Trash2 className="w-4 h-4" />
                              </Button>
                            </div>
                          </div>
                        </div>
                      ))}
                    </div>
                  )}
                </CardContent>
              </Card>
            </div>
          </TabsContent>

          {/* Messages Tab */}
          <TabsContent value="messages">
            <Card>
              <CardContent className="p-6">
                <h3 className="text-lg font-semibold text-gray-900 mb-4">Contact Messages</h3>
                {messagesLoading ? (
                  <div className="space-y-4">
                    {[1, 2, 3].map((i) => (
                      <div key={i} className="animate-pulse">
                        <div className="h-16 bg-gray-200 rounded"></div>
                      </div>
                    ))}
                  </div>
                ) : (
                  <div className="space-y-4">
                    {messages?.map((message) => (
                      <div key={message.id} className="border border-gray-200 rounded-lg p-4">
                        <div className="flex justify-between items-start">
                          <div>
                            <h4 className="font-semibold text-gray-900">
                              {message.firstName} {message.lastName}
                            </h4>
                            <p className="text-sm text-gray-600">{message.email}</p>
                            <p className="text-sm font-medium text-gray-900 mt-1">{message.subject}</p>
                            <p className="text-sm text-gray-700 mt-2">{message.message}</p>
                            <p className="text-xs text-gray-500 mt-2">
                              {new Date(message.createdAt).toLocaleDateString()}
                            </p>
                          </div>
                          <div>
                            {!message.isRead && (
                              <Badge variant="default">New</Badge>
                            )}
                          </div>
                        </div>
                      </div>
                    ))}
                  </div>
                )}
              </CardContent>
            </Card>
          </TabsContent>
        </Tabs>
      </div>
    </div>
  );
}
