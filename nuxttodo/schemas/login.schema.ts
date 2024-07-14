import { z } from "zod";

export const loginSchema = z.object({
  email: z.string().email().min(1, "Email is required"),
  password: z.string().min(6).max(15),
  remember: z.boolean().optional(),
  captcha: z.string().min(6).max(6).optional(),
});

export type LoginSchema = z.input<typeof loginSchema>;
