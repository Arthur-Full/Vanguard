# Vanguard

Portal educacional em PHP e MySQL para coordenação, professores e alunos.

## Instalação local (XAMPP)

1. Inicie Apache e MySQL no XAMPP.
2. Importe `banco/vanguard.sql` pelo phpMyAdmin. O script recria o banco `vanguard`.
3. Confira as variáveis `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER` e `DB_PASSWORD`. Sem variáveis, a aplicação usa `localhost:3306`, banco `vanguard` e usuário `root` sem senha.
4. Abra `http://localhost/vanguard/frontend/`.

O banco inicial cria contas locais de demonstração. Use o dropdown `Vanguard Escola` para professor e aluno; a conta de administrador funciona com qualquer instituição selecionada. Os acessos são:

- Administrador: `admin@vanguard.local` / `Vanguard@2026`
- Professor: `professor@vanguard.local` / `Vanguard@2026`
- Aluno: `aluno@vanguard.local` / `Vanguard@2026`

Troque essas credenciais antes de usar com dados reais. Para instalar as contas sem recriar uma base já existente, importe `banco/acessos-iniciais.sql`. Para adicionar os feriados de 2026 a uma base existente, importe `backend/migracoes/2026-10-18-feriados-2026.sql`. Para habilitar o envio de currículo no perfil, importe `backend/migracoes/2026-10-18-curriculo-perfil.sql`. Para habilitar a edição das informações do aluno no perfil, importe `backend/migracoes/2026-10-18-dados-perfil-aluno.sql`. Para habilitar anotações e anexos nas aulas, importe `backend/migracoes/2026-10-18-anotacoes-anexos-aluno.sql`.
