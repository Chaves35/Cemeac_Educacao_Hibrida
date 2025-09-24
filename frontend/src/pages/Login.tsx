import { useAuth } from "../contexts/AuthContext"

function Login() {
  const { login } = useAuth()

  const handleLogin = async () => {
    try {
      await login({
        email: "admin@cemeac.edu.br", // ⚠️ troque por usuário válido do backend
        password: "password123",      // ⚠️ troque pela senha correta
      })
      alert("Login realizado com sucesso ✅")
    } catch (error) {
      alert("Falha no login ❌")
    }
  }

  return (
    <div style={{ padding: "2rem", textAlign: "center" }}>
      <h2>🔐 Login</h2>
      <button onClick={handleLogin}>Fazer Login</button>
    </div>
  )
}

export default Login