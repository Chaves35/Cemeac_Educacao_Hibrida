import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '../services/api';
import { 
  User, 
  School, 
  Activity, 
  Content, 
  ForumPost, 
  StudentActivity 
} from '../types';
// Hooks genéricos para CRUD
export const useList = <T>(endpoint: string) => {
  return useQuery<T[]>({
    queryKey: [endpoint],
    queryFn: async () => {
      const response = await api.get(`/${endpoint}`);
      return response.data.data; // Ajuste conforme a estrutura da sua API
    },
  });
};

export const useCreate = <T>(endpoint: string) => {
  const queryClient = useQueryClient();
  
  return useMutation<T, Error, Partial<T>>({
    mutationFn: async (data) => {
      const response = await api.post(`/${endpoint}`, data);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: [endpoint] });
    },
  });
};

export const useUpdate = <T>(endpoint: string) => {
  const queryClient = useQueryClient();
  
  return useMutation<T, Error, { id: number; data: Partial<T> }>({
    mutationFn: async ({ id, data }) => {
      const response = await api.put(`/${endpoint}/${id}`, data);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: [endpoint] });
    },
  });
};

export const useDelete = <T>(endpoint: string) => {
  const queryClient = useQueryClient();
  
  return useMutation<T, Error, number>({
    mutationFn: async (id) => {
      const response = await api.delete(`/${endpoint}/${id}`);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: [endpoint] });
    },
  });
};

// Hooks específicos
export const useUsers = () => useList<User>('users');
export const useSchools = () => useList<School>('schools');
export const useActivities = () => useList<Activity>('activities');
export const useContents = () => useList<Content>('contents');
export const useForumPosts = () => useList<ForumPost>('forum-posts');
export const useStudentActivities = () => useList<StudentActivity>('student-activities');