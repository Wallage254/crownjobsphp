import { Link } from "wouter";
import { Crown } from "lucide-react";

export default function Footer() {
  return (
    <footer className="bg-gray-900 text-white py-12">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
          {/* Brand */}
          <div>
            <div className="flex items-center mb-4">
              <Crown className="text-primary text-2xl mr-2" />
              <span className="text-xl font-bold">CrownOpportunities</span>
            </div>
            <p className="text-gray-400">
              Connecting African talent with UK opportunities since 2024.
            </p>
          </div>

          {/* Job Seekers */}
          <div>
            <h4 className="font-semibold mb-4">Job Seekers</h4>
            <ul className="space-y-2 text-gray-400">
              <li>
                <Link href="/jobs">
                  <span className="hover:text-white transition-colors cursor-pointer">Browse Jobs</span>
                </Link>
              </li>
              <li>
                <Link href="/#testimonials">
                  <span className="hover:text-white transition-colors cursor-pointer">Success Stories</span>
                </Link>
              </li>
              <li>
                <span className="hover:text-white transition-colors cursor-pointer">Career Advice</span>
              </li>
              <li>
                <span className="hover:text-white transition-colors cursor-pointer">Visa Information</span>
              </li>
            </ul>
          </div>

          {/* Employers */}
          <div>
            <h4 className="font-semibold mb-4">Employers</h4>
            <ul className="space-y-2 text-gray-400">
              <li>
                <Link href="/admin">
                  <span className="hover:text-white transition-colors cursor-pointer">Post a Job</span>
                </Link>
              </li>
              <li>
                <span className="hover:text-white transition-colors cursor-pointer">Pricing</span>
              </li>
              <li>
                <span className="hover:text-white transition-colors cursor-pointer">Recruitment Solutions</span>
              </li>
              <li>
                <span className="hover:text-white transition-colors cursor-pointer">Employer Resources</span>
              </li>
            </ul>
          </div>

          {/* Support */}
          <div>
            <h4 className="font-semibold mb-4">Support</h4>
            <ul className="space-y-2 text-gray-400">
              <li>
                <span className="hover:text-white transition-colors cursor-pointer">Help Center</span>
              </li>
              <li>
                <Link href="/#contact">
                  <span className="hover:text-white transition-colors cursor-pointer">Contact Us</span>
                </Link>
              </li>
              <li>
                <span className="hover:text-white transition-colors cursor-pointer">Privacy Policy</span>
              </li>
              <li>
                <span className="hover:text-white transition-colors cursor-pointer">Terms of Service</span>
              </li>
            </ul>
          </div>
        </div>

        <div className="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
          <p>&copy; 2024 CrownOpportunities. All rights reserved.</p>
        </div>
      </div>
    </footer>
  );
}
