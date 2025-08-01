import { useState } from "react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Search, MapPin, Briefcase } from "lucide-react";
import { useLocation } from "wouter";

export default function HeroSection() {
  const [, setLocation] = useLocation();
  const [searchData, setSearchData] = useState({
    keyword: "",
    location: "",
    category: ""
  });

  const handleSearch = () => {
    const params = new URLSearchParams();
    if (searchData.keyword) params.set('search', searchData.keyword);
    if (searchData.location) params.set('location', searchData.location);
    if (searchData.category) params.set('category', searchData.category);
    
    setLocation(`/jobs?${params.toString()}`);
  };

  return (
    <section className="relative bg-gradient-to-r from-blue-600 via-emerald-600 to-orange-600 text-white py-20 lg:py-32 overflow-hidden">
      {/* Animated background */}
      <div className="absolute inset-0 bg-gradient-to-r from-blue-600 via-emerald-600 to-orange-600 animate-gradient-x"></div>
      
      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div className="animate-slide-up">
          <h1 className="text-4xl md:text-6xl font-bold mb-6">
            From Africa to UK –<br />
            <span className="text-yellow-300">Your Next Job Awaits</span>
          </h1>
          <p className="text-xl md:text-2xl mb-12 max-w-3xl mx-auto opacity-90">
            Connect with premium UK job opportunities in construction, healthcare, hospitality, and skilled trades
          </p>
        </div>
        
        {/* Search Bar */}
        <div className="max-w-4xl mx-auto animate-slide-up-delay">
          <div className="bg-white rounded-2xl p-6 shadow-2xl">
            <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
              <div className="relative">
                <Search className="absolute left-3 top-3 h-5 w-5 text-gray-400" />
                <Input
                  type="text"
                  placeholder="Job title or keyword"
                  value={searchData.keyword}
                  onChange={(e) => setSearchData(prev => ({ ...prev, keyword: e.target.value }))}
                  className="pl-10 h-12 text-gray-900 border-gray-200"
                />
              </div>
              
              <div className="relative">
                <MapPin className="absolute left-3 top-3 h-5 w-5 text-gray-400 z-10" />
                <Select value={searchData.location} onValueChange={(value) => setSearchData(prev => ({ ...prev, location: value }))}>
                  <SelectTrigger className="pl-10 h-12 text-gray-900 border-gray-200">
                    <SelectValue placeholder="Your Location" />
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
                  </SelectContent>
                </Select>
              </div>
              
              <div className="relative">
                <Briefcase className="absolute left-3 top-3 h-5 w-5 text-gray-400 z-10" />
                <Select value={searchData.category} onValueChange={(value) => setSearchData(prev => ({ ...prev, category: value }))}>
                  <SelectTrigger className="pl-10 h-12 text-gray-900 border-gray-200">
                    <SelectValue placeholder="All Categories" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="Construction">Construction</SelectItem>
                    <SelectItem value="Healthcare">Healthcare</SelectItem>
                    <SelectItem value="Hospitality">Hospitality</SelectItem>
                    <SelectItem value="Skilled Trades">Skilled Trades</SelectItem>
                  </SelectContent>
                </Select>
              </div>
              
              <Button 
                onClick={handleSearch}
                className="h-12 bg-orange-600 hover:bg-orange-700 text-white font-semibold"
              >
                <Search className="w-5 h-5 mr-2" />
                Search Jobs
              </Button>
            </div>
          </div>
        </div>
      </div>

      <style>{`
        @keyframes gradient-x {
          0%, 100% {
            background-position: 0% 50%;
          }
          50% {
            background-position: 100% 50%;
          }
        }
        
        .animate-gradient-x {
          background-size: 400% 400%;
          animation: gradient-x 6s ease infinite;
        }
        
        .animate-slide-up {
          animation: slideUp 0.8s ease-out;
        }
        
        .animate-slide-up-delay {
          animation: slideUp 0.8s ease-out 0.3s both;
        }
        
        @keyframes slideUp {
          from {
            opacity: 0;
            transform: translateY(30px);
          }
          to {
            opacity: 1;
            transform: translateY(0);
          }
        }
      `}</style>
    </section>
  );
}
