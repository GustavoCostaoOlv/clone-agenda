📘 Sistema de Gestão de Cursos Online

Plataforma profissional para cadastro, gerenciamento e matrícula de cursos.

📑 Sumário

Visão Geral

Principais Recursos

Stack Tecnológica

Instalação e Configuração

Execução do Projeto

Estrutura de Diretórios

Capturas de Tela

Fluxo Operacional

Contribuição

Licença

Contato e Suporte

📌 Visão Geral

Este projeto é uma plataforma completa para gestão de cursos online, construída em PHP com MySQL e baseada na estrutura inicial de uma agenda digital.
A aplicação evoluiu para um sistema profissional, com módulos de:

Autenticação de usuários

Cadastro de cursos

Controle de matrículas

Upload e gerenciamento de imagens

Painel administrativo responsivo

O sistema é projetado para ser simples de instalar, leve, organizado e escalável.

🎯 Principais Recursos
🔐 Autenticação e Usuários

Cadastro de usuários com foto de perfil

Login seguro com hashing de senha

Gerenciamento de sessão

Controle de acesso por autenticação

📚 Gestão de Cursos

Registro completo de cursos (nome, categoria, nível, duração, descrição, preço etc.)

Edição e exclusão com validações

Upload de imagens com detecção automática de extensão

Listagem com filtros por categoria

Cursos pré-cadastrados para demonstração

🎓 Matrículas e Progresso

Matrícula automática do criador do curso

Visualização de cursos matriculados

Separação entre Cursos Disponíveis e Meus Cursos

Acompanhamento de progresso

🖼️ Gerenciamento de Imagens

Validação de formato

Fallback automático para imagem padrão

Upload protegido via GD Library

Diretórios independentes para cursos e usuários

🛠️ Stack Tecnológica
Tecnologia	Versão	Descrição
PHP	8.0+	Lógica backend
MySQL	8.0+	Banco de dados
Tailwind CSS	3.x	Estilização moderna
Font Awesome	6.x	Ícones
PDO	—	Conexão segura
GD Library	—	Processamento de imagens
⚙️ Instalação e Configuração
1️⃣ Clonar o repositório
git clone https://github.com/GustavoCostaoOlv/clone-agenda.git
cd clone-agenda

2️⃣ Criar o banco de dados
CREATE DATABASE sistema_cursos
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

3️⃣ Configurar credenciais

Arquivo: config/conexao.php

$host = "localhost";
$user = "seu_usuario";
$pass = "sua_senha";
$dbname = "sistema_cursos";
$port = 3306;

$conect = new PDO(
    "mysql:host=$host;port=$port;dbname=$dbname",
    $user,
    $pass
);

4️⃣ Criar/ajustar permissões das pastas de imagem
mkdir -p img/user img/cursos
chmod 755 img/ img/user/ img/cursos/

▶️ Execução do Projeto
Opção A — Servidor embutido do PHP
php -S localhost:8000


Acesse: http://localhost:8000

Opção B — XAMPP / Apache

Mova o projeto para /htdocs/

Acesse: http://localhost/clone-agenda

📂 Estrutura de Diretórios
clone-agenda/
├── config/
│   └── conexao.php
├── img/
│   ├── cursos/
│   └── user/
├── paginas/
│   ├── home.php
│   ├── del-contato.php
│   └── ...
├── plugins/
│   └── fontawesome-free/
├── index.php
├── cad_user.php
└── README.md

🖼️ Capturas de Tela

(Adicione imagens reais do sistema para deixar o README ainda mais profissional.)

Tela de Login

Dashboard

Cadastro de Cursos

Listagem e Pesquisa

Área do Usuário

📘 Fluxo Operacional

Usuário cria uma conta

Realiza login

Acessa o painel

Cadastra cursos

Matricula-se ou gerencia seus cursos

Acompanha progresso

Edita ou exclui conteúdos quando necessário

🤝 Contribuição

Contribuições são bem-vindas. Para colaborar:

git fork
git checkout -b feature/NomeDaFeature
git commit -m "Descrição da melhoria"
git push origin feature/NomeDaFeature


Abra um Pull Request descrevendo:

A motivação

O que foi alterado

Como testar

Reportar Bugs

Abra uma Issue com:

Passos para reproduzir

Comportamento esperado

Logs e prints (se possível)

📄 Licença

Este projeto está licenciado sob a MIT License.
Consulte o arquivo LICENSE para mais detalhes.

📞 Contato e Suporte

GitHub: (link do seu perfil)

Issues: Utilize o painel de Issues do repositório

<div align="center">

💙 Desenvolvido por Luiz Gustavo
Transformando uma agenda em uma plataforma completa de cursos.

⭐ Se este projeto for útil, considere deixar uma estrela!

</div>

Se quiser, posso gerar:

✅ versão em inglês
✅ badges profissionais (PHP • MySQL • License • Status)
✅ tabela de endpoints
✅ diagrama de banco de dados
✅ capa visual do README