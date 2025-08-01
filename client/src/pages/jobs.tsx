import { useState } from "react";
import { useQuery } from "@tanstack/react-query";
import JobCard from "@/components/job-card";
import JobFilters from "@/components/job-filters";
import { Button } from "@/components/ui/button";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { ChevronLeft, ChevronRight } from "lucide-react";
import type { Job } from "@shared/schema";

interface JobFilters {
  category?: string;
  location?: string;
  search?: string;
  salaryMin?: number;
  salaryMax?: number;
}

export default function Jobs() {
  const [filters, setFilters] = useState<JobFilters>({});
  const [sortBy, setSortBy] = useState("recent");
  
  const { data: jobs, isLoading } = useQuery<Job[]>({
    queryKey: ['/api/jobs', filters],
    queryFn: async () => {
      const searchParams = new URLSearchParams();
      Object.entries(filters).forEach(([key, value]) => {
        if (value !== undefined && value !== '') {
          searchParams.set(key, value.toString());
        }
      });
      
      const response = await fetch(`/api/jobs?${searchParams}`);
      if (!response.ok) throw new Error('Failed to fetch jobs');
      return response.json();
    }
  });

  const handleFilterChange = (newFilters: JobFilters) => {
    setFilters(newFilters);
  };

  if (isLoading) {
    return (
      <div className="min-h-screen bg-gray-50 py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="animate-pulse">
            <div className="h-8 bg-gray-200 rounded w-64 mb-8"></div>
            <div className="grid grid-cols-1 lg:grid-cols-4 gap-8">
              <div className="space-y-4">
                {[1, 2, 3, 4].map((i) => (
                  <div key={i} className="h-32 bg-gray-200 rounded"></div>
                ))}
              </div>
              <div className="lg:col-span-3 space-y-6">
                {[1, 2, 3].map((i) => (
                  <div key={i} className="h-48 bg-gray-200 rounded"></div>
                ))}
              </div>
            </div>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-50 py-16">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex flex-col lg:flex-row gap-8">
          {/* Filters Sidebar */}
          <div className="lg:w-1/4">
            <JobFilters onFilterChange={handleFilterChange} />
          </div>

          {/* Job Listings */}
          <div className="lg:w-3/4">
            <div className="flex justify-between items-center mb-6">
              <h2 className="text-2xl font-bold text-gray-900">
                Available Opportunities ({jobs?.length || 0})
              </h2>
              <Select value={sortBy} onValueChange={setSortBy}>
                <SelectTrigger className="w-48">
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="recent">Most Recent</SelectItem>
                  <SelectItem value="salary-high">Highest Salary</SelectItem>
                  <SelectItem value="salary-low">Lowest Salary</SelectItem>
                  <SelectItem value="relevant">Most Relevant</SelectItem>
                </SelectContent>
              </Select>
            </div>

            {jobs && jobs.length > 0 ? (
              <div className="space-y-6">
                {jobs.map((job) => (
                  <JobCard key={job.id} job={job} />
                ))}
              </div>
            ) : (
              <div className="text-center py-12">
                <h3 className="text-lg font-semibold text-gray-900 mb-2">No jobs found</h3>
                <p className="text-gray-600">Try adjusting your filters to see more results.</p>
              </div>
            )}

            {/* Pagination */}
            {jobs && jobs.length > 0 && (
              <div className="flex justify-center mt-8">
                <nav className="flex items-center space-x-2">
                  <Button variant="ghost" size="sm" disabled>
                    <ChevronLeft className="w-4 h-4" />
                  </Button>
                  <Button variant="default" size="sm">1</Button>
                  <Button variant="ghost" size="sm">2</Button>
                  <Button variant="ghost" size="sm">3</Button>
                  <Button variant="ghost" size="sm">
                    <ChevronRight className="w-4 h-4" />
                  </Button>
                </nav>
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}
