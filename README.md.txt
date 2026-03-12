# Sistema de Gestão Escolar #

projeto desenvolvido como trabalho acadêmico em trio com o objetivo de praticar conceitos de desenvolvimento web e CRUD

# Funcionalidades

- Cadastro de alunos
- Cadastro de professores
- Sistema de login
- Diferentes níveis de acesso (Administrador, Professor e Aluno)
- Gerenciamento de Turmas
- Geração de relatório em PDF
- Upload de imagem para cursos, alunos e professores
- Usuários não admin são capazes de realizar um login no sistema(professores e alunos)
- Senha padronizada para todos os usuários, sendo a mesma: 123456

# Tipos de Usuário

Administrador
  - Pode cadastrar e remover alunos, professores e turmas
  - Gerencia cursos e turmas

Professor
  - Pode acessar suas turmas
  - Gerenciar alunos da turma
  - Lançar notas

Aluno
  - Pode visualizar colegas da turma
  - Acessar cursos em que está matriculado

# Tecnologias utilizadas

- PHP
- MySQL
- HTML
- CSS
- XAMPP

# Observação importante

* Este projeto foi desenvolvido para prática de conceitos vistos em sala de aula. Algumas funcionalidades como botões e páginas de acesso são ilustrativas, portanto o projeto está em desenvolvimento ou pode não ser totalmente finalizado.

*Todos os usuários do sistema possuem como uma única senha padrão: 123456
*O sistema foi desenvolvido para ser de fácil acesso e navegabilidade, 
portanto as senhas, mesmo que pudessem ser escolhidas pelo usuário em sua matrícula, foram padronizadas para fins de demonstração apenas.

# Pasta "screenshots"

Abra esta pasta para visualizar as telas do projeto, seu conceito e funcionalidade na prática.

# Como executar o projeto

1. Instale o XAMPP

2. Coloque o projeto dentro da pasta: 
 htdocs

3. Inicie:
  - Apache

4. Abra o Banco de dados:
    - "escola_db.sql"
    - Ele está localizado na pasta projeto_escola
    - Cole e execute-o no MySQL/PgAdmin 

5. Abra o navegador na página:
  - "http://localhost/projeto_escola/gerar_senha.php" 
  - Copie e cole no banco de dados o texto em verde, escrito como
    "Execute o seguinte comando SQL na sua Query Tool:"
  - Execute o comando no seu MySQL/PgAdmin QueryTool

6. Abra o navegador na página:
   - "http://localhost/projeto_escola/login.php"
   - Digite no campo de texto Email "adm@escola.com" 
   - No campo senha digite "123456", é a senha do usuário Admin

