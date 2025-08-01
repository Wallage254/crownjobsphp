import HeroSection from "@/components/hero-section";
import TestimonialsCarousel from "@/components/testimonials-carousel";
import ContactForm from "@/components/contact-form";
import { Card, CardContent } from "@/components/ui/card";
import { Link } from "wouter";
import { useQuery } from "@tanstack/react-query";
import { Category } from "@shared/schema";

export default function Home() {
  const { data: categories, isLoading: categoriesLoading } = useQuery<Category[]>({
    queryKey: ['/api/categories'],
  });
  return (
    <div className="min-h-screen">
      <HeroSection />
      
      {/* Job Categories */}
      <section className="py-16 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 className="text-3xl font-bold text-center text-gray-900 mb-12">Popular Job Categories</h2>
          {categoriesLoading ? (
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
              {[1, 2, 3, 4].map((i) => (
                <div key={i} className="animate-pulse">
                  <Card className="bg-gray-100 border-0">
                    <CardContent className="p-6">
                      <div className="w-16 h-16 bg-gray-300 rounded mb-4"></div>
                      <div className="h-6 bg-gray-300 rounded mb-2"></div>
                      <div className="h-4 bg-gray-300 rounded mb-4"></div>
                      <div className="h-4 bg-gray-300 rounded w-1/2"></div>
                    </CardContent>
                  </Card>
                </div>
              ))}
            </div>
          ) : (
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
              {categories?.filter(cat => cat.isActive).map((category) => (
                <Link key={category.id} href="/jobs">
                  <Card className="bg-white border hover:shadow-lg transition-all cursor-pointer group">
                    <CardContent className="p-6 text-center">
                      {category.gifUrl && (
                        <img 
                          src={category.gifUrl} 
                          alt={category.name}
                          className="w-16 h-16 mx-auto mb-4 rounded-lg object-cover group-hover:scale-105 transition-transform"
                        />
                      )}
                      <h3 className="text-xl font-semibold text-gray-900 mb-2">{category.name}</h3>
                      <p className="text-gray-600 mb-4">{category.description}</p>
                      <span className="text-blue-600 font-semibold">View Jobs →</span>
                    </CardContent>
                  </Card>
                </Link>
              ))}
            </div>
          )}
        </div>
      </section>

      <TestimonialsCarousel />
      <ContactForm />
    </div>
  );
}
