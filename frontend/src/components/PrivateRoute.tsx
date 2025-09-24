import { Navigate } from "react-router-dom"
import { useAuth } from "../contexts/AuthContext"
import type { ReactNode } from "react" // 👈 importar ReactNode

interface PrivateRouteProps {
  children: ReactNode   // 👈 trocar JSX.Element por ReactNode
  roles?: string[]
}

export function PrivateRoute({ children, roles }: PrivateRouteProps) {
  const { user, loading } = useAuth()

  if (loading) return <p>Carregando autenticação...</p>

  if (!user) return <Navigate to="/login" replace />

  if (roles && user && !roles.includes(user.role)) {
    return <p>⚠️ Você não tem permissão para acessar esta página.</p>
  }

  return <>{children}</>  // 👈 melhor usar fragmento
}