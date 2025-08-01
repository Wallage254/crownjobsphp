import { useState } from "react";
import { useMutation, useQueryClient } from "@tanstack/react-query";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { insertApplicationSchema } from "@shared/schema";
import { z } from "zod";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Checkbox } from "@/components/ui/checkbox";
import { Card, CardContent } from "@/components/ui/card";
import { X, Upload, User, FileText, Briefcase } from "lucide-react";
import { useToast } from "@/hooks/use-toast";
import { apiRequest } from "@/lib/queryClient";
import type { Job } from "@shared/schema";

const applicationFormSchema = insertApplicationSchema.extend({
  profilePhoto: z.any().optional(),
  cv: z.any().optional(),
  consent: z.boolean().refine(val => val === true, "You must consent to data processing")
});

type ApplicationFormData = z.infer<typeof applicationFormSchema>;

interface ApplicationFormProps {
  job: Job;
  onClose: () => void;
}

export default function ApplicationForm({ job, onClose }: ApplicationFormProps) {
  const [photoPreview, setPhotoPreview] = useState<string | null>(null);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const { toast } = useToast();
  const queryClient = useQueryClient();

  const form = useForm<ApplicationFormData>({
    resolver: zodResolver(applicationFormSchema),
    defaultValues: {
      jobId: job.id,
      firstName: "",
      lastName: "",
      email: "",
      phone: "",
      currentLocation: "",
      coverLetter: "",
      experience: "",
      previousRole: "",
      consent: false
    }
  });

  const submitApplication = useMutation({
    mutationFn: async (data: FormData) => {
      return await apiRequest('POST', '/api/applications', data);
    },
    onSuccess: () => {
      toast({
        title: "Application Submitted",
        description: "Your application has been sent successfully!",
      });
      queryClient.invalidateQueries({ queryKey: ['/api/applications'] });
      onClose();
    },
    onError: (error) => {
      toast({
        title: "Submission Failed",
        description: error.message,
        variant: "destructive"
      });
    }
  });

  const onSubmit = async (data: ApplicationFormData) => {
    console.log("Form submission data:", data);
    console.log("Form errors:", form.formState.errors);
    
    // Check all required fields are present before submitting
    const requiredFields = ['jobId', 'firstName', 'lastName', 'email', 'phone', 'currentLocation'];
    const missingFields = requiredFields.filter(field => !data[field as keyof ApplicationFormData] || data[field as keyof ApplicationFormData] === '');
    
    if (missingFields.length > 0) {
      console.error("Missing required fields:", missingFields);
      toast({
        title: "Form Incomplete",
        description: `Please fill in all required fields: ${missingFields.join(', ')}`,
        variant: "destructive"
      });
      return;
    }
    
    setIsSubmitting(true);
    
    const formData = new FormData();
    
    // Add all form fields explicitly - ensure they're properly added to FormData
    formData.append('jobId', data.jobId || '');
    formData.append('firstName', data.firstName || '');
    formData.append('lastName', data.lastName || '');
    formData.append('email', data.email || '');
    formData.append('phone', data.phone || '');
    formData.append('currentLocation', data.currentLocation || '');
    
    // Add optional fields only if they have values
    if (data.coverLetter && data.coverLetter.trim()) {
      formData.append('coverLetter', data.coverLetter);
    }
    if (data.experience && data.experience.trim()) {
      formData.append('experience', data.experience);
    }
    if (data.previousRole && data.previousRole.trim()) {
      formData.append('previousRole', data.previousRole);
    }

    // Add files
    const photoInput = document.querySelector('input[name="profilePhoto"]') as HTMLInputElement;
    const cvInput = document.querySelector('input[name="cv"]') as HTMLInputElement;
    
    if (photoInput?.files?.[0]) {
      formData.append('profilePhoto', photoInput.files[0]);
    }
    
    if (cvInput?.files?.[0]) {
      formData.append('cv', cvInput.files[0]);
    }

    // Debug: log form data contents
    console.log("=== CLIENT FORM DEBUG ===");
    console.log("Original form data object:", data);
    console.log("FormData contents:");
    for (const pair of Array.from(formData.entries())) {
      console.log(pair[0] + ': ' + pair[1]);
    }
    console.log("=== END CLIENT DEBUG ===");

    submitApplication.mutate(formData);
    setIsSubmitting(false);
  };

  const handlePhotoChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (e) => setPhotoPreview(e.target?.result as string);
      reader.readAsDataURL(file);
    }
  };

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
      <Card className="w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div className="sticky top-0 bg-white border-b border-gray-200 p-6 flex justify-between items-center">
          <h2 className="text-2xl font-bold text-gray-900">Job Application</h2>
          <Button variant="ghost" size="sm" onClick={onClose}>
            <X className="w-5 h-5" />
          </Button>
        </div>
        
        <CardContent className="p-6">
          <div className="mb-6 p-4 bg-blue-50 rounded-lg">
            <h3 className="font-semibold text-gray-900">{job.title}</h3>
            <p className="text-gray-600">{job.company} • {job.location}</p>
          </div>

          <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-6">
            {/* Personal Information */}
            <div>
              <h3 className="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <User className="w-5 h-5 mr-2" />
                Personal Information
              </h3>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <Label htmlFor="firstName">First Name *</Label>
                  <Input
                    id="firstName"
                    {...form.register("firstName")}
                    className="mt-1"
                  />
                  {form.formState.errors.firstName && (
                    <p className="text-red-600 text-sm mt-1">{form.formState.errors.firstName.message}</p>
                  )}
                </div>
                <div>
                  <Label htmlFor="lastName">Last Name *</Label>
                  <Input
                    id="lastName"
                    {...form.register("lastName")}
                    className="mt-1"
                  />
                  {form.formState.errors.lastName && (
                    <p className="text-red-600 text-sm mt-1">{form.formState.errors.lastName.message}</p>
                  )}
                </div>
              </div>
              
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                  <Label htmlFor="email">Email *</Label>
                  <Input
                    id="email"
                    type="email"
                    {...form.register("email")}
                    className="mt-1"
                  />
                  {form.formState.errors.email && (
                    <p className="text-red-600 text-sm mt-1">{form.formState.errors.email.message}</p>
                  )}
                </div>
                <div>
                  <Label htmlFor="phone">Phone Number *</Label>
                  <Input
                    id="phone"
                    type="tel"
                    {...form.register("phone")}
                    className="mt-1"
                  />
                  {form.formState.errors.phone && (
                    <p className="text-red-600 text-sm mt-1">{form.formState.errors.phone.message}</p>
                  )}
                </div>
              </div>

              <div className="mt-4">
                <Label htmlFor="currentLocation">Current Location *</Label>
                <Select 
                  value={form.watch("currentLocation") || ""} 
                  onValueChange={(value) => form.setValue("currentLocation", value, { shouldValidate: true })}
                >
                  <SelectTrigger className="mt-1">
                    <SelectValue placeholder="Select your country" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="Nigeria">Nigeria</SelectItem>
                    <SelectItem value="Kenya">Kenya</SelectItem>
                    <SelectItem value="Ghana">Ghana</SelectItem>
                    <SelectItem value="South Africa">South Africa</SelectItem>
                    <SelectItem value="Uganda">Uganda</SelectItem>
                    <SelectItem value="Rwanda">Rwanda</SelectItem>
                    <SelectItem value="Tanzania">Tanzania</SelectItem>
                    <SelectItem value="Ethiopia">Ethiopia</SelectItem>
                    <SelectItem value="Other">Other</SelectItem>
                  </SelectContent>
                </Select>
                {form.formState.errors.currentLocation && (
                  <p className="text-red-600 text-sm mt-1">{form.formState.errors.currentLocation.message}</p>
                )}
              </div>
            </div>

            {/* Profile Photo */}
            <div>
              <h3 className="text-lg font-semibold text-gray-900 mb-4">Profile Photo</h3>
              <div className="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                {photoPreview ? (
                  <div className="space-y-4">
                    <img src={photoPreview} alt="Preview" className="w-24 h-24 rounded-full object-cover mx-auto" />
                    <p className="text-sm text-gray-600">Photo uploaded successfully</p>
                  </div>
                ) : (
                  <div className="space-y-2">
                    <Upload className="w-12 h-12 text-gray-400 mx-auto" />
                    <p className="text-gray-600">Upload your profile photo</p>
                  </div>
                )}
                <input
                  type="file"
                  name="profilePhoto"
                  accept="image/*"
                  onChange={handlePhotoChange}
                  className="mt-4 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary file:text-white hover:file:bg-primary/90"
                />
              </div>
            </div>

            {/* CV Upload */}
            <div>
              <h3 className="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <FileText className="w-5 h-5 mr-2" />
                CV/Resume *
              </h3>
              <div className="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                <Upload className="w-12 h-12 text-gray-400 mx-auto mb-2" />
                <p className="text-gray-600 mb-4">Upload your CV/Resume (PDF, DOC, DOCX)</p>
                <input
                  type="file"
                  name="cv"
                  accept=".pdf,.doc,.docx"
                  className="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-emerald-600 file:text-white hover:file:bg-emerald-700"
                />
              </div>
            </div>

            {/* Cover Letter */}
            <div>
              <Label htmlFor="coverLetter">Cover Letter</Label>
              <Textarea
                id="coverLetter"
                rows={6}
                placeholder="Tell us why you're the perfect candidate for this role..."
                {...form.register("coverLetter")}
                className="mt-1"
              />
            </div>

            {/* Experience */}
            <div>
              <h3 className="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <Briefcase className="w-5 h-5 mr-2" />
                Relevant Experience
              </h3>
              <div className="space-y-4">
                <div>
                  <Label htmlFor="experience">Years of Experience</Label>
                  <Select 
                    value={form.watch("experience") || ""} 
                    onValueChange={(value) => form.setValue("experience", value, { shouldValidate: true })}
                  >
                    <SelectTrigger className="mt-1">
                      <SelectValue placeholder="Select experience level" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="<1">Less than 1 year</SelectItem>
                      <SelectItem value="1-2">1-2 years</SelectItem>
                      <SelectItem value="3-5">3-5 years</SelectItem>
                      <SelectItem value="5-10">5-10 years</SelectItem>
                      <SelectItem value="10+">10+ years</SelectItem>
                    </SelectContent>
                  </Select>
                </div>
                <div>
                  <Label htmlFor="previousRole">Previous Role</Label>
                  <Input
                    id="previousRole"
                    placeholder="Most recent job title"
                    {...form.register("previousRole")}
                    className="mt-1"
                  />
                </div>
              </div>
            </div>

            {/* Consent */}
            <div className="bg-gray-50 rounded-lg p-4">
              <div className="flex items-start space-x-2">
                <Checkbox
                  id="consent"
                  checked={form.watch("consent")}
                  onCheckedChange={(checked) => form.setValue("consent", !!checked)}
                  className="mt-1"
                />
                <Label htmlFor="consent" className="text-sm text-gray-700 leading-relaxed">
                  I consent to my personal data being processed for recruitment purposes and agree to the 
                  Terms of Service and Privacy Policy.
                </Label>
              </div>
              {form.formState.errors.consent && (
                <p className="text-red-600 text-sm mt-2">{form.formState.errors.consent.message}</p>
              )}
            </div>

            {/* Submit Button */}
            <div className="pt-6">
              <Button 
                type="submit" 
                className="w-full h-12"
                disabled={isSubmitting || submitApplication.isPending}
              >
                {isSubmitting || submitApplication.isPending ? "Submitting..." : "Submit Application"}
              </Button>
              <p className="text-center text-sm text-gray-600 mt-3">
                Your application will be sent directly to the employer
              </p>
            </div>
          </form>
        </CardContent>
      </Card>
    </div>
  );
}
