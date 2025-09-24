import { useAuth } from "../contexts/AuthContext"

function Dashboard() {
  const { user, logout } = useAuth()

  return (
    <div style={{ padding: "2rem", textAlign: "center" }}>
      <h2>📊 Dashboard</h2>
      <p>
        Bem-vindo, <b>{user?.name}</b> ({user?.role})
      </p>
      <button onClick={logout}>Sair</button>
    </div>
  )
}

export default Dashboard