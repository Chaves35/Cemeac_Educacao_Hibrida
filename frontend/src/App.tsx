import { Routes, Route, Link } from "react-router-dom"
import { useAuth } from "./contexts/AuthContext"
import { PrivateRoute } from "./components/PrivateRoute"

// Páginas
import Login from "./pages/Login"
import Dashboard from "./pages/Dashboard"

function App() {
  const { user, logout } = useAuth()

  return (
    <div style={{ padding: "2rem", textAlign: "center" }}>
      <h1>🚀 Plataforma CEMEAC Educação Híbrida</h1>

      <nav style={{ marginBottom: "1rem" }}>
        <Link to="/">Início</Link> |{" "}
        <Link to="/sobre">Sobre</Link> |{" "}
        <Link to="/dashboard">Dashboard</Link> |{" "}
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

        {/* Rota privada */}
        <Route
          path="/dashboard"
          element={
            <PrivateRoute roles={["admin", "gestor", "professor", "aluno"]}>
              <Dashboard />
            </PrivateRoute>
          }
        />
      </Routes>
    </div>
  )
}

export default App