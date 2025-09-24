// frontend/src/contexts/AuthContext.tsx
import { createContext, useContext, useState, useEffect } from 'react';
import { useQueryClient } from '@tanstack/react-query';
import api from '../services/api';

// Definir interfaces diretamente aqui
interface User {
  id: number;
  name: string;
  email: string;
  role: 'admin' | 'gestor' | 'professor' | 'aluno';
  school_id: number | null;
  school?: any;
  created_at: string;
}

interface LoginCredentials {
  email: string;
  password: string;
}

interface AuthResponse {
  user: User;
  token: string;
}

interface AuthContextType {
  user: User | null;
  token: string | null;
  login: (credentials: LoginCredentials) => Promise<void>;
  logout: () => void;
  loading: boolean;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (context === undefined) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
};

interface AuthProviderProps {
  children: any; // Simplificando temporariamente
}

export const AuthProvider = ({ children }: AuthProviderProps) => {
  const queryClient = useQueryClient();
  const [user, setUser] = useState<User | null>(null);
  const [token, setToken] = useState<string | null>(
    localStorage.getItem('auth_token')
  );
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const initAuth = async () => {
      const storedToken = localStorage.getItem('auth_token');
      
      if (storedToken) {
        try {
          const response = await api.get('/me');
          setUser(response.data);
          setToken(storedToken);
        } catch (error) {
          localStorage.removeItem('auth_token');
          setToken(null);
        }
      }
      
      setLoading(false);
    };

    initAuth();
  }, []);

  const login = async (credentials: LoginCredentials) => {
    try {
      const response = await api.post<AuthResponse>('/login', credentials);
      const { user: userData, token: authToken } = response.data;
      
      setUser(userData);
      setToken(authToken);
      localStorage.setItem('auth_token', authToken);
    } catch (error) {
      throw error;
    }
  };

  const logout = async () => {
    try {
      if (token) {
        await api.post('/logout');
      }
    } catch (error) {
      console.error('Erro ao fazer logout:', error);
    } finally {
      setUser(null);
      setToken(null);
      localStorage.removeItem('auth_token');
      queryClient.clear();
    }
  };

  const value = {
    user,
    token,
    login,
    logout,
    loading,
  };

  return (
    <AuthContext.Provider value={value}>
      {children}
    </AuthContext.Provider>
  );
};