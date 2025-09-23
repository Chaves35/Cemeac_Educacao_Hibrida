export interface User {
  id: number;
  name: string;
  email: string;
  role: 'admin' | 'gestor' | 'professor' | 'aluno';
  school_id: number | null;
  school?: School;
  created_at: string;
}

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

export interface Activity {
  id: number;
  title: string;
  description?: string;
  type: 'verdadeiro_falso' | 'multipla_escolha' | 'drag_drop' | 'subjetiva';
  difficulty: 'facil' | 'medio' | 'dificil';
  max_score: number;
  school_id: number;
  content_id?: number;
  school?: School;
  content?: Content;
  created_at: string;
}

export interface Content {
  id: number;
  title: string;
  description?: string;
  type: 'video' | 'documento' | 'link_externo' | 'texto';
  url?: string;
  file_path?: string;
  school_id?: number;
  school?: School;
  created_at: string;
}

// Adicione os tipos de LoginCredentials e AuthResponse aqui
export interface LoginCredentials {
  email: string;
  password: string;
}

export interface AuthResponse {
  user: User;
  token: string;
}

export interface ForumPost {
  id: number;
  title: string;
  content: string;
  status: 'ativo' | 'fixado' | 'arquivado';
  user_id: number;
  activity_id?: number;
  parent_id?: number;
  user?: User;
  activity?: Activity;
  replies?: ForumPost[];
  created_at: string;
}

export interface StudentActivity {
  id: number;
  user_id: number;
  activity_id: number;
  status: 'nao_iniciado' | 'em_progresso' | 'concluido' | 'reprovado';
  score?: number;
  attempts: number;
  started_at?: string;
  completed_at?: string;
  user?: User;
  activity?: Activity;
  created_at: string;
}