import { useState, useEffect } from "react";
import { Card, CardContent } from "@/components/ui/card";
import { Label } from "@/components/ui/label";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Checkbox } from "@/components/ui/checkbox";
import { RadioGroup, RadioGroupItem } from "@/components/ui/radio-group";
import { Button } from "@/components/ui/button";

interface JobFiltersProps {
  onFilterChange: (filters: any) => void;
}

export default function JobFilters({ onFilterChange }: JobFiltersProps) {
  const [filters, setFilters] = useState({
    salaryRange: "",
    jobTypes: [] as string[],
    ukLocation: "all",
    experience: "any"
  });

  useEffect(() => {
    const searchFilters: any = {};
    
    if (filters.salaryRange) {
      const [min, max] = filters.salaryRange.split('-').map(v => parseInt(v));
      if (min) searchFilters.salaryMin = min;
      if (max) searchFilters.salaryMax = max;
    }
    
    if (filters.ukLocation && filters.ukLocation !== 'all') {
      searchFilters.location = filters.ukLocation;
    }
    
    onFilterChange(searchFilters);
  }, [filters, onFilterChange]);

  const handleJobTypeChange = (jobType: string, checked: boolean) => {
    setFilters(prev => ({
      ...prev,
      jobTypes: checked 
        ? [...prev.jobTypes, jobType]
        : prev.jobTypes.filter(type => type !== jobType)
    }));
  };

  const clearFilters = () => {
    setFilters({
      salaryRange: "",
      jobTypes: [],
      ukLocation: "",
      experience: ""
    });
  };

  return (
    <Card className="sticky top-4">
      <CardContent className="p-6">
        <div className="flex justify-between items-center mb-4">
          <h3 className="text-lg font-semibold text-gray-900">Refine Search</h3>
          <Button variant="ghost" size="sm" onClick={clearFilters}>
            Clear All
          </Button>
        </div>
        
        {/* Salary Range */}
        <div className="mb-6">
          <Label className="text-sm font-medium text-gray-700 mb-2 block">
            Salary Range (£)
          </Label>
          <Select value={filters.salaryRange} onValueChange={(value) => setFilters(prev => ({ ...prev, salaryRange: value }))}>
            <SelectTrigger>
              <SelectValue placeholder="Any" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="">Any</SelectItem>
              <SelectItem value="20000-30000">£20,000 - £30,000</SelectItem>
              <SelectItem value="30000-40000">£30,000 - £40,000</SelectItem>
              <SelectItem value="40000-50000">£40,000 - £50,000</SelectItem>
              <SelectItem value="50000-">£50,000+</SelectItem>
            </SelectContent>
          </Select>
        </div>

        {/* Job Type */}
        <div className="mb-6">
          <Label className="text-sm font-medium text-gray-700 mb-2 block">
            Job Type
          </Label>
          <div className="space-y-2">
            {["Full-time", "Part-time", "Contract", "Temporary"].map((type) => (
              <div key={type} className="flex items-center space-x-2">
                <Checkbox
                  id={type}
                  checked={filters.jobTypes.includes(type)}
                  onCheckedChange={(checked) => handleJobTypeChange(type, checked as boolean)}
                />
                <Label htmlFor={type} className="text-sm text-gray-600">
                  {type}
                </Label>
              </div>
            ))}
          </div>
        </div>

        {/* UK Location */}
        <div className="mb-6">
          <Label className="text-sm font-medium text-gray-700 mb-2 block">
            UK Location
          </Label>
          <Select value={filters.ukLocation} onValueChange={(value) => setFilters(prev => ({ ...prev, ukLocation: value }))}>
            <SelectTrigger>
              <SelectValue placeholder="All Locations" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="all">All Locations</SelectItem>
              <SelectItem value="London">London</SelectItem>
              <SelectItem value="Manchester">Manchester</SelectItem>
              <SelectItem value="Birmingham">Birmingham</SelectItem>
              <SelectItem value="Liverpool">Liverpool</SelectItem>
              <SelectItem value="Glasgow">Glasgow</SelectItem>
              <SelectItem value="Edinburgh">Edinburgh</SelectItem>
              <SelectItem value="Leeds">Leeds</SelectItem>
              <SelectItem value="Bristol">Bristol</SelectItem>
            </SelectContent>
          </Select>
        </div>

        {/* Experience Level */}
        <div className="mb-6">
          <Label className="text-sm font-medium text-gray-700 mb-2 block">
            Experience Level
          </Label>
          <RadioGroup 
            value={filters.experience} 
            onValueChange={(value) => setFilters(prev => ({ ...prev, experience: value }))}
          >
            <div className="flex items-center space-x-2">
              <RadioGroupItem value="any" id="any-exp" />
              <Label htmlFor="any-exp" className="text-sm text-gray-600">Any</Label>
            </div>
            <div className="flex items-center space-x-2">
              <RadioGroupItem value="entry" id="entry" />
              <Label htmlFor="entry" className="text-sm text-gray-600">Entry Level</Label>
            </div>
            <div className="flex items-center space-x-2">
              <RadioGroupItem value="mid" id="mid" />
              <Label htmlFor="mid" className="text-sm text-gray-600">Mid Level</Label>
            </div>
            <div className="flex items-center space-x-2">
              <RadioGroupItem value="senior" id="senior" />
              <Label htmlFor="senior" className="text-sm text-gray-600">Senior Level</Label>
            </div>
          </RadioGroup>
        </div>
      </CardContent>
    </Card>
  );
}
