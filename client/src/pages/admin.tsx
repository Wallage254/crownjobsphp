import { useState } from "react";
import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import AdminDashboard from "@/components/admin-dashboard";
import { Crown } from "lucide-react";

export default function Admin() {
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const [credentials, setCredentials] = useState({ username: "", password: "" });
  const [isLoading, setIsLoading] = useState(false);

  const handleLogin = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);
    
    // Simple authentication - in a real app, this would be handled by the backend
    if (credentials.username === "admin" && credentials.password === "admin123") {
      setIsAuthenticated(true);
    } else {
      alert("Invalid credentials. Use admin/admin123 for demo.");
    }
    
    setIsLoading(false);
  };

  if (isAuthenticated) {
    return <AdminDashboard />;
  }

  return (
    <div className="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
      <Card className="w-full max-w-md">
        <CardContent className="p-8">
          <div className="text-center mb-8">
            <Crown className="w-12 h-12 text-primary mx-auto mb-4" />
            <h2 className="text-2xl font-bold text-gray-900">Admin Login</h2>
            <p className="text-gray-600 mt-2">Access the CrownOpportunities dashboard</p>
          </div>
          
          <form onSubmit={handleLogin} className="space-y-6">
            <div>
              <Label htmlFor="username">Username</Label>
              <Input
                id="username"
                type="text"
                value={credentials.username}
                onChange={(e) => setCredentials(prev => ({ ...prev, username: e.target.value }))}
                required
                className="mt-1"
                placeholder="Enter your username"
              />
            </div>
            
            <div>
              <Label htmlFor="password">Password</Label>
              <Input
                id="password"
                type="password"
                value={credentials.password}
                onChange={(e) => setCredentials(prev => ({ ...prev, password: e.target.value }))}
                required
                className="mt-1"
                placeholder="Enter your password"
              />
            </div>
            
            <Button 
              type="submit" 
              className="w-full"
              disabled={isLoading}
            >
              {isLoading ? "Signing in..." : "Sign In"}
            </Button>
          </form>
          
          <div className="mt-6 text-center text-sm text-gray-500">
            <p>Demo credentials: admin / admin123</p>
          </div>
        </CardContent>
      </Card>
    </div>
  );
}
