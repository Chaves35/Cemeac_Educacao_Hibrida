export interface School {
  id: number;
  name: string;
  inep_code: string;
  address: string;
  city: string;
  state: string;
  users_count?: number;
  created_at: string;
}