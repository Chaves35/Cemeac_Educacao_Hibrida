import { Routes, Route, Link } from "react-router-dom"
import { useAuth } from "./contexts/AuthContext"
import { PrivateRoute } from "./components/PrivateRoute"

// Páginas
import Login from "./pages/Login"
import Dashboard from "./pages/Dashboard"
import AdminDashboard from "./pages/AdminDashboard" // Nova importação

function App() {
  const { user, logout } = useAuth()

  return (
    <div style={{ padding: "2rem", textAlign: "center" }}>
      <h1>🚀 Plataforma CEMEAC Educação Híbrida</h1>

      <nav style={{ marginBottom: "1rem" }}>
        <Link to="/">Início</Link> |{" "}
        <Link to="/sobre">Sobre</Link> |{" "}
        <Link to="/dashboard">Dashboard</Link> |{" "}
        {user?.role === 'admin' && <Link to="/admin-dashboard">Admin Dashboard</Link>} |{" "}
        {!user && <Link to="/login">Login</Link>}
      </nav>

      {user && (
        <div style={{ marginBottom: "1rem" }}>
          <p>
            Usuário: <b>{user.name}</b> ({user.role})
          </p>
          <button onClick={logout}>Sair</button>
        </div>
      )}

      <Routes>
        {/* Rotas públicas */}
        <Route path="/" element={<p>Você está na página inicial ✅</p>} />
        <Route path="/sobre" element={<p>Esta é a página Sobre 📘</p>} />
        <Route path="/login" element={<Login />} />

        {/* Rota privada - Dashboard */}
        <Route
          path="/dashboard"
          element={
            <PrivateRoute roles={["admin", "gestor", "professor", "aluno"]}>
              <Dashboard />
            </PrivateRoute>
          }
        />

        {/* Nova rota - Admin Dashboard */}
        <Route
          path="/admin-dashboard"
          element={
            <PrivateRoute roles={["admin"]}>
              <AdminDashboard />
            </PrivateRoute>
          }
        />
      </Routes>
    </div>
  )
}

export default App