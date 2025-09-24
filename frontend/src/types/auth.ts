export interface User {
  id: number;
  name: string;
  email: string;
  role: 'admin' | 'gestor' | 'professor' | 'aluno';
  school_id: number | null;
  school?: any; // Simplificando temporariamente
  created_at: string;
}

export interface LoginCredentials {
  email: string;
  password: string;
}

export interface AuthResponse {
  user: User;
  token: string;
}