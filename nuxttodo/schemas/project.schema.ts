import { z } from "zod";

export const projectSchema = z.object({
  title: z.string().min(1, "Title is required"),
  description: z.string().optional(),
});

export type ProjectSchema = z.input<typeof projectSchema>;
