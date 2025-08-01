import HeroSection from "@/components/hero-section";
import TestimonialsCarousel from "@/components/testimonials-carousel";
import ContactForm from "@/components/contact-form";
import { Card, CardContent } from "@/components/ui/card";
import { Link } from "wouter";
import { Construction, Heart, UtensilsCrossed, Wrench } from "lucide-react";

const categories = [
  {
    title: "Construction",
    description: "Join major UK construction projects with competitive wages",
    jobCount: "2,340+ jobs",
    icon: Construction,
    color: "from-blue-50 to-blue-100",
    textColor: "text-blue-600"
  },
  {
    title: "Healthcare",
    description: "Make a difference in UK's healthcare system",
    jobCount: "1,890+ jobs",
    icon: Heart,
    color: "from-emerald-50 to-emerald-100",
    textColor: "text-emerald-600"
  },
  {
    title: "Hospitality",
    description: "Excel in UK's thriving hospitality industry",
    jobCount: "1,560+ jobs",
    icon: UtensilsCrossed,
    color: "from-orange-50 to-orange-100",
    textColor: "text-orange-600"
  },
  {
    title: "Skilled Trades",
    description: "Apply your expertise in specialized UK trades",
    jobCount: "980+ jobs",
    icon: Wrench,
    color: "from-purple-50 to-purple-100",
    textColor: "text-purple-600"
  }
];

export default function Home() {
  return (
    <div className="min-h-screen">
      <HeroSection />
      
      {/* Job Categories */}
      <section className="py-16 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 className="text-3xl font-bold text-center text-gray-900 mb-12">Popular Job Categories</h2>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            {categories.map((category) => (
              <Link key={category.title} href="/jobs">
                <Card className={`bg-gradient-to-br ${category.color} border-0 hover:shadow-lg transition-all cursor-pointer group`}>
                  <CardContent className="p-6">
                    <category.icon className={`w-16 h-16 ${category.textColor} mb-4 group-hover:scale-105 transition-transform`} />
                    <h3 className="text-xl font-semibold text-gray-900 mb-2">{category.title}</h3>
                    <p className="text-gray-600 mb-4">{category.description}</p>
                    <span className={`${category.textColor} font-semibold`}>{category.jobCount}</span>
                  </CardContent>
                </Card>
              </Link>
            ))}
          </div>
        </div>
      </section>

      <TestimonialsCarousel />
      <ContactForm />
    </div>
  );
}
