# CEMEAC Educação Híbrida - Plataforma Educacional Híbrida

Plataforma educacional híbrida desenvolvida para gestão de escolas, usuários, conteúdos e atividades pedagógicas interativas.  
O projeto visa oferecer um **Dashboard Administrativo** robusto e seguro, junto com uma **SPA (Single Page Application)** moderna para alunos, professores e gestores.

---

## 📘 Visão Geral
O **CEMEAC Educação Híbrida** tem como objetivo integrar ensino **presencial e online** em um ecossistema único, priorizando:
- Gestão centralizada de escolas, usuários e papéis (RBAC - Role Based Access Control).
- Criação e acompanhamento de atividades pedagógicas.
- Produção e organização de conteúdos multimídia.
- Dashboard administrativo com controle de estatísticas.

---

## ⚙️ Stack Tecnológica

### Backend
- **Laravel 11** (PHP 8.3)
- **MySQL** 8+
- **Laravel Sanctum** → Autenticação e segurança
- **Eloquent ORM**
- **PHPUnit** para testes

### Frontend
- **React 18** + **TypeScript**
- **Vite** (mecanismo de build)
- **React Router** (navegação SPA)
- **Axios** (cliente HTTP)
- **Bootstrap** / **React Bootstrap** (UI)

### Infraestrutura
- **Docker / Docker Compose** (planejado)
- **Laragon / XAMPP** para dev local
- **Nginx** (produção)

- 🗃️ Banco de Dados (Migrations & Seeders)
As principais tabelas e seeders já implementados:

Tabelas
users → com (, , , ) e vínculo
schools → com (código oficial da escola)
activities → atividades pedagógicas (, , etc.)
contents → materiais pedagógicos (, , , etc.)
student_activities → relação de alunos com execução de atividades
forum_posts → suporte a fóruns/histórico de discussões
Seeders
→ cria escolas fictícias com
→ cria usuários com diferentes papéis
→ popula atividades pedagógicas
(próximo) → popula conteúdos por atividade

👨‍💻 Contribuição
Crie um branch:
Commit suas alterações:
Push:
Abra um Pull Request
Padrão de commits: Conventional Commits (, , , etc.)

📌 Status Atual
✅ Estrutura de migrations consolidada (users, schools, contents, activities)
✅ Base de seeders funcionando até
✅ Commit atualizado no GitHub
🚧 Próximas etapas:
Implementar
Ajustar testes de integração backend
Desenvolver Dashboard Administrativo com dados agregados
Implementar CI/CD pipeline

---

## 🏗️ Estrutura do Repositório

```bash
Cemeac_Educacao_Hibrida/
│
├── backend/              # Backend Laravel
│   ├── app/              # Código de aplicação Laravel
│   ├── database/         
│   │   ├── migrations/   # Estrutura de tabelas
│   │   ├── seeders/      # População inicial de dados
│   ├── routes/           # API e web routes
│   └── tests/            # Testes unitários e de integração
│
├── frontend/             # Frontend React + Vite + TS
│   ├── src/
│   │   ├── pages/        # Páginas (Login, Dashboard, etc.)
│   │   └── components/   # Componentes reutilizáveis
│
├── docs/                 # Documentação de arquitetura e requisitos
└── README.md             # Este documento

📜 Licença
Projeto desenvolvido para fins educacionais e de pesquisa.
(c) 2025 - Equipe CEMEAC Educação Híbrida


