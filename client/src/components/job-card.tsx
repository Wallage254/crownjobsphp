import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";
import { MapPin, Clock, DollarSign, Building } from "lucide-react";
import { Link } from "wouter";
import type { Job } from "@shared/schema";

interface JobCardProps {
  job: Job;
}

export default function JobCard({ job }: JobCardProps) {
  const timeAgo = job.createdAt ? new Date(job.createdAt).toLocaleDateString() : "Recently";
  const salaryRange = job.salaryMin && job.salaryMax 
    ? `£${job.salaryMin.toLocaleString()} - £${job.salaryMax.toLocaleString()}`
    : "Competitive salary";

  return (
    <Card className="hover:shadow-lg transition-all duration-300 hover:-translate-y-1 border border-gray-200">
      <CardContent className="p-6">
        <div className="flex items-start justify-between">
          <div className="flex items-start space-x-4 flex-1">
            {job.companyLogo && (
              <img 
                src={job.companyLogo} 
                alt={`${job.company} logo`}
                className="w-16 h-16 object-cover rounded-lg flex-shrink-0"
              />
            )}
            <div className="flex-1 min-w-0">
              <Link href={`/jobs/${job.id}`}>
                <h3 className="text-xl font-semibold text-gray-900 mb-1 hover:text-primary transition-colors cursor-pointer">
                  {job.title}
                </h3>
              </Link>
              <p className="text-primary font-medium mb-2 flex items-center">
                <Building className="w-4 h-4 mr-1" />
                {job.company}
              </p>
              <div className="flex items-center space-x-4 text-sm text-gray-600 mb-3">
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
              <p className="text-gray-700 mb-3 line-clamp-2">
                {job.description.slice(0, 150)}...
              </p>
              <div className="flex items-center space-x-2 flex-wrap gap-2">
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
          <div className="text-right flex-shrink-0 ml-4">
            <p className="text-sm text-gray-500 mb-4">
              Posted {timeAgo}
            </p>
            <Link href={`/jobs/${job.id}`}>
              <Button className="bg-primary hover:bg-primary/90">
                Apply Now
              </Button>
            </Link>
          </div>
        </div>
      </CardContent>
    </Card>
  );
}
