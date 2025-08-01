import { useState, useEffect } from "react";
import { useQuery } from "@tanstack/react-query";
import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { ChevronLeft, ChevronRight, Star } from "lucide-react";
import type { Testimonial } from "@shared/schema";

export default function TestimonialsCarousel() {
  const [currentSlide, setCurrentSlide] = useState(0);
  
  const { data: testimonials, isLoading } = useQuery<Testimonial[]>({
    queryKey: ['/api/testimonials'],
    queryFn: async () => {
      const response = await fetch('/api/testimonials');
      if (!response.ok) throw new Error('Failed to fetch testimonials');
      return response.json();
    }
  });

  useEffect(() => {
    if (testimonials && testimonials.length > 0) {
      const interval = setInterval(() => {
        setCurrentSlide(prev => (prev + 1) % Math.ceil(testimonials.length / 3));
      }, 5000);
      return () => clearInterval(interval);
    }
  }, [testimonials]);

  if (isLoading) {
    return (
      <section className="py-16 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-12">
            <div className="h-8 bg-gray-200 rounded w-64 mx-auto mb-4 animate-pulse"></div>
            <div className="h-6 bg-gray-200 rounded w-96 mx-auto animate-pulse"></div>
          </div>
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {[1, 2, 3].map((i) => (
              <div key={i} className="bg-gray-50 rounded-xl p-6 animate-pulse">
                <div className="w-20 h-20 bg-gray-200 rounded-full mx-auto mb-4"></div>
                <div className="h-4 bg-gray-200 rounded w-32 mx-auto mb-3"></div>
                <div className="h-16 bg-gray-200 rounded mb-4"></div>
                <div className="h-4 bg-gray-200 rounded w-24 mx-auto mb-2"></div>
                <div className="h-3 bg-gray-200 rounded w-16 mx-auto"></div>
              </div>
            ))}
          </div>
        </div>
      </section>
    );
  }

  if (!testimonials || testimonials.length === 0) {
    return null;
  }

  const slides = [];
  for (let i = 0; i < testimonials.length; i += 3) {
    slides.push(testimonials.slice(i, i + 3));
  }

  const nextSlide = () => {
    setCurrentSlide(prev => (prev + 1) % slides.length);
  };

  const prevSlide = () => {
    setCurrentSlide(prev => (prev - 1 + slides.length) % slides.length);
  };

  const renderStars = (rating: number) => {
    return (
      <div className="flex justify-center mb-3">
        {[1, 2, 3, 4, 5].map((star) => (
          <Star 
            key={star} 
            className={`w-5 h-5 ${star <= rating ? 'text-yellow-400 fill-current' : 'text-gray-300'}`}
          />
        ))}
      </div>
    );
  };

  return (
    <section className="py-16 bg-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-12">
          <h2 className="text-3xl font-bold text-gray-900 mb-4">Success Stories</h2>
          <p className="text-xl text-gray-600">Hear from professionals who found their dream jobs in the UK</p>
        </div>

        <div className="relative overflow-hidden">
          <div 
            className="flex transition-transform duration-500 ease-in-out"
            style={{ transform: `translateX(-${currentSlide * 100}%)` }}
          >
            {slides.map((slide, slideIndex) => (
              <div key={slideIndex} className="w-full flex-shrink-0 px-4">
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                  {slide.map((testimonial) => (
                    <Card key={testimonial.id} className="bg-gray-50 border-0">
                      <CardContent className="p-6 text-center">
                        {testimonial.photo && (
                          <img 
                            src={testimonial.photo} 
                            alt={testimonial.name}
                            className="w-20 h-20 rounded-full mx-auto mb-4 object-cover"
                          />
                        )}
                        {renderStars(testimonial.rating)}
                        <p className="text-gray-700 mb-4 leading-relaxed">"{testimonial.comment}"</p>
                        <h4 className="font-semibold text-gray-900">{testimonial.name}</h4>
                        <p className="text-sm text-gray-600">{testimonial.country}</p>
                      </CardContent>
                    </Card>
                  ))}
                </div>
              </div>
            ))}
          </div>

          {/* Navigation Buttons */}
          {slides.length > 1 && (
            <>
              <Button
                variant="outline"
                size="icon"
                onClick={prevSlide}
                className="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white shadow-lg hover:bg-gray-50"
              >
                <ChevronLeft className="w-4 h-4" />
              </Button>
              <Button
                variant="outline"
                size="icon"
                onClick={nextSlide}
                className="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white shadow-lg hover:bg-gray-50"
              >
                <ChevronRight className="w-4 h-4" />
              </Button>
            </>
          )}

          {/* Indicators */}
          {slides.length > 1 && (
            <div className="flex justify-center mt-8 space-x-2">
              {slides.map((_, index) => (
                <Button
                  key={index}
                  variant="ghost"
                  size="sm"
                  onClick={() => setCurrentSlide(index)}
                  className={`w-3 h-3 rounded-full p-0 ${
                    index === currentSlide ? 'bg-primary' : 'bg-gray-300'
                  }`}
                />
              ))}
            </div>
          )}
        </div>
      </div>
    </section>
  );
}
