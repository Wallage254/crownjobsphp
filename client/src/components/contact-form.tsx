import { useState } from "react";
import { useMutation } from "@tanstack/react-query";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { insertMessageSchema } from "@shared/schema";
import { z } from "zod";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Card, CardContent } from "@/components/ui/card";
import { Mail, Phone, MapPin } from "lucide-react";
import { useToast } from "@/hooks/use-toast";
import { apiRequest } from "@/lib/queryClient";

type ContactFormData = z.infer<typeof insertMessageSchema>;

export default function ContactForm() {
  const { toast } = useToast();

  const form = useForm<ContactFormData>({
    resolver: zodResolver(insertMessageSchema),
    defaultValues: {
      firstName: "",
      lastName: "",
      email: "",
      subject: "",
      message: ""
    }
  });

  const sendMessage = useMutation({
    mutationFn: async (data: ContactFormData) => {
      return await apiRequest('POST', '/api/contact', data);
    },
    onSuccess: () => {
      toast({
        title: "Message Sent",
        description: "Thank you for your message. We'll get back to you soon!",
      });
      form.reset();
    },
    onError: (error) => {
      toast({
        title: "Failed to Send",
        description: error.message,
        variant: "destructive"
      });
    }
  });

  const onSubmit = (data: ContactFormData) => {
    sendMessage.mutate(data);
  };

  return (
    <section id="contact" className="py-16 bg-gray-50">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-12">
          <h2 className="text-3xl font-bold text-gray-900 mb-4">Get In Touch</h2>
          <p className="text-xl text-gray-600">Have questions? We're here to help you start your UK career journey</p>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
          {/* Contact Form */}
          <Card>
            <CardContent className="p-8">
              <h3 className="text-xl font-semibold text-gray-900 mb-6">Send us a message</h3>
              <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-6">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <Label htmlFor="firstName">First Name</Label>
                    <Input
                      id="firstName"
                      {...form.register("firstName")}
                      placeholder="John"
                      className="mt-1"
                    />
                    {form.formState.errors.firstName && (
                      <p className="text-red-600 text-sm mt-1">{form.formState.errors.firstName.message}</p>
                    )}
                  </div>
                  <div>
                    <Label htmlFor="lastName">Last Name</Label>
                    <Input
                      id="lastName"
                      {...form.register("lastName")}
                      placeholder="Doe"
                      className="mt-1"
                    />
                    {form.formState.errors.lastName && (
                      <p className="text-red-600 text-sm mt-1">{form.formState.errors.lastName.message}</p>
                    )}
                  </div>
                </div>
                
                <div>
                  <Label htmlFor="email">Email</Label>
                  <Input
                    id="email"
                    type="email"
                    {...form.register("email")}
                    placeholder="john@example.com"
                    className="mt-1"
                  />
                  {form.formState.errors.email && (
                    <p className="text-red-600 text-sm mt-1">{form.formState.errors.email.message}</p>
                  )}
                </div>
                
                <div>
                  <Label htmlFor="subject">Subject</Label>
                  <Select onValueChange={(value) => form.setValue("subject", value)}>
                    <SelectTrigger className="mt-1">
                      <SelectValue placeholder="Select a subject" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="General Inquiry">General Inquiry</SelectItem>
                      <SelectItem value="Job Application Help">Job Application Help</SelectItem>
                      <SelectItem value="Visa Information">Visa Information</SelectItem>
                      <SelectItem value="Technical Support">Technical Support</SelectItem>
                      <SelectItem value="Partnership Inquiry">Partnership Inquiry</SelectItem>
                    </SelectContent>
                  </Select>
                  {form.formState.errors.subject && (
                    <p className="text-red-600 text-sm mt-1">{form.formState.errors.subject.message}</p>
                  )}
                </div>
                
                <div>
                  <Label htmlFor="message">Message</Label>
                  <Textarea
                    id="message"
                    rows={5}
                    {...form.register("message")}
                    placeholder="Tell us how we can help you..."
                    className="mt-1"
                  />
                  {form.formState.errors.message && (
                    <p className="text-red-600 text-sm mt-1">{form.formState.errors.message.message}</p>
                  )}
                </div>
                
                <Button 
                  type="submit" 
                  className="w-full"
                  disabled={sendMessage.isPending}
                >
                  {sendMessage.isPending ? "Sending..." : "Send Message"}
                </Button>
              </form>
            </CardContent>
          </Card>

          {/* Contact Information */}
          <div className="space-y-8">
            <Card>
              <CardContent className="p-6">
                <div className="flex items-center space-x-4">
                  <div className="bg-primary/10 rounded-lg p-3">
                    <Mail className="w-6 h-6 text-primary" />
                  </div>
                  <div>
                    <h4 className="font-semibold text-gray-900">Email Us</h4>
                    <p className="text-gray-600">support@crownopportunities.com</p>
                  </div>
                </div>
              </CardContent>
            </Card>

            <Card>
              <CardContent className="p-6">
                <div className="flex items-center space-x-4">
                  <div className="bg-emerald-500/10 rounded-lg p-3">
                    <Phone className="w-6 h-6 text-emerald-600" />
                  </div>
                  <div>
                    <h4 className="font-semibold text-gray-900">Call Us</h4>
                    <p className="text-gray-600">+44 20 1234 5678</p>
                  </div>
                </div>
              </CardContent>
            </Card>

            <Card>
              <CardContent className="p-6">
                <div className="flex items-center space-x-4">
                  <div className="bg-orange-500/10 rounded-lg p-3">
                    <MapPin className="w-6 h-6 text-orange-600" />
                  </div>
                  <div>
                    <h4 className="font-semibold text-gray-900">Office Address</h4>
                    <p className="text-gray-600">123 Business Street<br />London, EC1A 1BB, UK</p>
                  </div>
                </div>
              </CardContent>
            </Card>

            <Card>
              <CardContent className="p-6">
                <h4 className="font-semibold text-gray-900 mb-4">Follow Us</h4>
                <div className="flex space-x-4">
                  <Button variant="outline" size="icon" className="text-blue-600 border-blue-600 hover:bg-blue-600 hover:text-white">
                    <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                    </svg>
                  </Button>
                  <Button variant="outline" size="icon" className="text-blue-700 border-blue-700 hover:bg-blue-700 hover:text-white">
                    <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                    </svg>
                  </Button>
                  <Button variant="outline" size="icon" className="text-green-600 border-green-600 hover:bg-green-600 hover:text-white">
                    <svg className="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                    </svg>
                  </Button>
                </div>
              </CardContent>
            </Card>
          </div>
        </div>
      </div>
    </section>
  );
}
