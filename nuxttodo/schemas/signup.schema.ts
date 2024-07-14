import { z } from "zod";

export const signupSchema = z
  .object({
    name: z.string().min(1, "Name is required"),
    email: z.string().email().min(1, "Email is required"),
    password: z.string().min(6).max(15),
    password_confirmation: z.string().min(6).max(15),
    remember: z.boolean().optional(),
    captcha: z.string().min(6).max(6).optional(),
  })
  .refine((data) => data.password === data.password_confirmation, {
    message: "Passwords do not match",
    path: ["password_confirmation"],
  });

export type SignupSchema = z.input<typeof signupSchema>;
