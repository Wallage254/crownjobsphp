import { useParams } from "wouter";
import { useQuery } from "@tanstack/react-query";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import ApplicationForm from "@/components/application-form";
import { useState } from "react";
import { ArrowLeft, MapPin, Clock, DollarSign, Building, Shield, Users } from "lucide-react";
import { Link } from "wouter";
import type { Job } from "@shared/schema";

export default function JobDetail() {
  const { id } = useParams();
  const [showApplicationForm, setShowApplicationForm] = useState(false);

  const { data: job, isLoading } = useQuery<Job>({
    queryKey: ['/api/jobs', id],
    queryFn: async () => {
      const response = await fetch(`/api/jobs/${id}`);
      if (!response.ok) throw new Error('Failed to fetch job');
      return response.json();
    }
  });

  if (isLoading) {
    return (
      <div className="min-h-screen bg-gray-50 py-16">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="animate-pulse">
            <div className="h-8 bg-gray-200 rounded w-32 mb-6"></div>
            <div className="h-12 bg-gray-200 rounded w-96 mb-4"></div>
            <div className="h-6 bg-gray-200 rounded w-64 mb-8"></div>
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
              <div className="space-y-6">
                <div className="h-64 bg-gray-200 rounded"></div>
                <div className="h-48 bg-gray-200 rounded"></div>
              </div>
              <div className="space-y-6">
                <div className="h-48 bg-gray-200 rounded"></div>
                <div className="h-32 bg-gray-200 rounded"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    );
  }

  if (!job) {
    return (
      <div className="min-h-screen bg-gray-50 py-16">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <h1 className="text-2xl font-bold text-gray-900 mb-4">Job Not Found</h1>
          <p className="text-gray-600 mb-8">The job you're looking for doesn't exist or has been removed.</p>
          <Link href="/jobs">
            <Button>
              <ArrowLeft className="w-4 h-4 mr-2" />
              Back to Jobs
            </Button>
          </Link>
        </div>
      </div>
    );
  }

  const salaryRange = job.salaryMin && job.salaryMax 
    ? `£${job.salaryMin.toLocaleString()} - £${job.salaryMax.toLocaleString()}`
    : "Competitive salary";

  return (
    <div className="min-h-screen bg-gray-50 py-16">
      <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Back button */}
        <Link href="/jobs">
          <Button variant="ghost" className="mb-6">
            <ArrowLeft className="w-4 h-4 mr-2" />
            Back to Jobs
          </Button>
        </Link>

        {/* Job header */}
        <div className="bg-white rounded-xl p-6 shadow-sm mb-8">
          <div className="flex items-start justify-between">
            <div className="flex items-start space-x-4">
              {job.companyLogo && (
                <img 
                  src={job.companyLogo} 
                  alt={`${job.company} logo`}
                  className="w-16 h-16 object-cover rounded-lg"
                />
              )}
              <div>
                <h1 className="text-3xl font-bold text-gray-900 mb-2">{job.title}</h1>
                <p className="text-xl text-primary font-medium mb-3">{job.company}</p>
                <div className="flex items-center space-x-4 text-sm text-gray-600 mb-4">
                  <span className="flex items-center">
                    <MapPin className="w-4 h-4 mr-1" />
                    {job.location}
                  </span>
                  <span className="flex items-center">
                    <Clock className="w-4 h-4 mr-1" />
                    {job.jobType}
                  </span>
                  <span className="flex items-center">
                    <DollarSign className="w-4 h-4 mr-1" />
                    {salaryRange}
                  </span>
                </div>
                <div className="flex items-center space-x-2">
                  <Badge variant="secondary">{job.category}</Badge>
                  {job.visaSponsored && (
                    <Badge variant="outline" className="bg-green-50 text-green-700 border-green-200">
                      Visa Sponsored
                    </Badge>
                  )}
                  {job.isUrgent && (
                    <Badge variant="destructive">
                      Urgent
                    </Badge>
                  )}
                </div>
              </div>
            </div>
            <div className="text-right">
              <p className="text-sm text-gray-500 mb-4">
                Posted {new Date(job.createdAt).toLocaleDateString()}
              </p>
              <Button 
                size="lg" 
                onClick={() => setShowApplicationForm(true)}
                className="bg-primary hover:bg-primary/90"
              >
                Apply Now
              </Button>
            </div>
          </div>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <div>
            {/* Job Description */}
            <Card className="mb-6">
              <CardContent className="p-6">
                <h3 className="text-lg font-semibold text-gray-900 mb-3">Job Description</h3>
                <div className="text-gray-700 leading-relaxed whitespace-pre-wrap">
                  {job.description}
                </div>
              </CardContent>
            </Card>

            {/* Requirements */}
            <Card className="mb-6">
              <CardContent className="p-6">
                <h3 className="text-lg font-semibold text-gray-900 mb-3">Requirements</h3>
                <div className="text-gray-700 leading-relaxed whitespace-pre-wrap">
                  {job.requirements}
                </div>
              </CardContent>
            </Card>

            {/* Benefits */}
            <Card>
              <CardContent className="p-6">
                <h3 className="text-lg font-semibold text-gray-900 mb-3">Benefits</h3>
                <ul className="text-gray-700 space-y-2">
                  <li>• Competitive salary package</li>
                  <li>• Visa sponsorship provided</li>
                  <li>• Accommodation assistance</li>
                  <li>• Health insurance coverage</li>
                  <li>• Professional development opportunities</li>
                  <li>• Relocation support</li>
                </ul>
              </CardContent>
            </Card>
          </div>

          <div>
            {/* Workplace Images */}
            {job.workplaceImages && job.workplaceImages.length > 0 && (
              <Card className="mb-6">
                <CardContent className="p-6">
                  <h3 className="text-lg font-semibold text-gray-900 mb-3">Workplace Environment</h3>
                  <div className="grid grid-cols-2 gap-4">
                    {job.workplaceImages.slice(0, 4).map((image, index) => (
                      <img
                        key={index}
                        src={image}
                        alt={`Workplace ${index + 1}`}
                        className="rounded-lg object-cover w-full h-32"
                      />
                    ))}
                  </div>
                </CardContent>
              </Card>
            )}

            {/* Quick Apply Card */}
            <Card className="bg-gray-50">
              <CardContent className="p-6">
                <h3 className="text-lg font-semibold text-gray-900 mb-4">Quick Apply</h3>
                <Button 
                  className="w-full mb-4 bg-primary hover:bg-primary/90"
                  size="lg"
                  onClick={() => setShowApplicationForm(true)}
                >
                  Apply for This Job
                </Button>
                <div className="text-sm text-gray-600 space-y-2">
                  <p className="flex items-center">
                    <Clock className="w-4 h-4 mr-2" />
                    Response within 24 hours
                  </p>
                  <p className="flex items-center">
                    <Shield className="w-4 h-4 mr-2" />
                    Secure application process
                  </p>
                  <p className="flex items-center">
                    <Users className="w-4 h-4 mr-2" />
                    Direct employer contact
                  </p>
                </div>
              </CardContent>
            </Card>
          </div>
        </div>
      </div>

      {/* Application Form Modal */}
      {showApplicationForm && (
        <ApplicationForm 
          job={job} 
          onClose={() => setShowApplicationForm(false)} 
        />
      )}
    </div>
  );
}
