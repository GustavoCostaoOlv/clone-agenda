🚀 Sistema profissional para cadastro e gerenciamento de cursos online


📋 Índice
✨ Sobre o Projeto

🎯 Funcionalidades

🛠️ Tecnologias

🚀 Como Executar o Projeto

📁 Estrutura do Projeto

🎨 Screenshots

📝 Fluxo do Sistema

🤝 Contribuindo

📄 Licença

✨ Sobre o Projeto
Sistema de cadastro de cursos desenvolvido em PHP com MySQL, herdado de uma agenda eletrônica original. Permite criar, gerenciar e visualizar cursos de diferentes categorias, com funcionalidades completas de autenticação de usuários, upload de imagens e matrículas.

Migração de Agenda → Sistema de Cursos:
O sistema foi adaptado de uma agenda eletrônica para um sistema completo de gestão de cursos, mantendo a estrutura original do banco de dados enquanto implementa novas funcionalidades específicas para educação.

🎯 Funcionalidades
👤 Autenticação & Usuários
✅ Cadastro de usuários com foto de perfil

✅ Login seguro com hash de senha

✅ Gestão de sessões e autenticação

✅ Recuperação de sessão automática

📚 Gerenciamento de Cursos
✅ Cadastro completo de cursos (nome, categoria, nível, preço, etc.)

✅ Upload de imagens para os cursos

✅ Categorização e filtragem por área

✅ Pré-cadastro de cursos demonstrativos

✅ Edição e exclusão de cursos

🎓 Matrículas & Progresso
✅ Matrícula automática do criador no curso

✅ Controle de progresso dos alunos

✅ Visualização de cursos matriculados

✅ Separação entre "Meus Cursos" e "Cursos Disponíveis"

🖼️ Sistema de Imagens
✅ Upload de imagens com validação de formato

✅ Geração automática de imagem padrão

✅ Detecção automática de extensão real

✅ Fallback para imagem padrão quando necessário

🛠️ Tecnologias
<div align="center">
Tecnologia	Versão	Função
PHP	8.0+	Backend e lógica de negócio
MySQL	8.0+	Banco de dados
Tailwind CSS	3.x	Estilização frontend
Font Awesome	6.x	Ícones e elementos visuais
PDO	-	Conexão segura com banco
GD Library	-	Manipulação de imagens
</div>
🚀 Como Executar o Projeto
Pré-requisitos
Antes de começar, você precisa ter instalado:

🐘 PHP 8.0 ou superior (com extensões: pdo_mysql, gd, mbstring)

🗄️ MySQL 8.0 ou MariaDB 10.4+

🌐 Servidor web (Apache, Nginx ou PHP built-in server)

📦 Composer (opcional, para futuras dependências)

📥 Passo 1: Clonar o Repositório
bash
# Clone o repositório
git clone https://github.com/GustavoCostaoOlv/clone-agenda.git

# Acesse a pasta do projeto
cd clone-agenda

# O projeto já está pronto para uso - não requer instalação de pacotes
🗄️ Passo 2: Configurar o Banco de Dados
Crie um banco de dados MySQL:

sql
CREATE DATABASE sistema_cursos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
Importe a estrutura inicial (se houver arquivo SQL)

Configure a conexão: Edite o arquivo config/conexao.php:

php
<?php
$host = "localhost";
$user = "seu_usuario";
$pass = "sua_senha";
$dbname = "sistema_cursos";
$port = 3306;

try {
    $conect = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    // ... configurações PDO
} catch(PDOException $e) {
    // ... tratamento de erro
}
?>
🌐 Passo 3: Configurar o Ambiente Web
Opção A: Usando PHP Built-in Server
bash
# Na pasta raiz do projeto
php -S localhost:8000

# Acesse no navegador:
# http://localhost:8000
Opção B: Configurando Apache/XAMPP
Copie a pasta do projeto para htdocs (XAMPP) ou www (Apache)

Acesse: http://localhost/clone-agenda

⚙️ Passo 4: Configurações de Permissões
bash
# Dê permissão de escrita para as pastas de upload
chmod 755 img/
chmod 755 img/user/
chmod 755 img/cursos/

# Crie as pastas se não existirem
mkdir -p img/user img/cursos
🚀 Passo 5: Acessar o Sistema
Acesse a URL do projeto no navegador

Crie uma conta ou use credenciais existentes

Comece a cadastrar cursos através do painel principal

🔧 Troubleshooting (Solução de Problemas)
❌ Erro de conexão com banco de dados
php
// Verifique em config/conexao.php:
// - Usuário e senha corretos
// - Nome do banco de dados existe
// - Servidor MySQL está rodando
❌ Erro de upload de imagens
bash
# Verifique permissões:
ls -la img/

# Deve mostrar:
# drwxr-xr-x  cursos/
# drwxr-xr-x  user/
❌ Extensão GD não encontrada
bash
# No Ubuntu/Debian:
sudo apt-get install php-gd

# Reinicie o Apache:
sudo systemctl restart apache2
📁 Estrutura do Projeto
text
clone-agenda/
├── 📂 config/
│   └── conexao.php          # Configuração do banco de dados
├── 📂 img/
│   ├── 📂 cursos/           # Imagens dos cursos
│   └── 📂 user/            # Fotos de perfil dos usuários
├── 📂 paginas/
│   ├── home.php            # Dashboard principal
│   ├── del-contato.php     # Deleção de cursos/contatos
│   └── ...                 # Outras páginas do sistema
├── 📂 plugins/
│   └── fontawesome-free/   # Ícones Font Awesome
├── 📄 index.php            # Página de login
├── 📄 cad_user.php         # Cadastro de usuários
└── 📄 README.md            # Este arquivo
🎨 Screenshots
As capturas de tela mostram a interface moderna do sistema

Login Premium - Tela de acesso com design glassmorphism

Dashboard - Visão geral dos cursos criados e matriculados

Cadastro de Cursos - Formulário completo com upload de imagem

Listagem de Cursos - Grid responsivo com filtros por categoria

📝 Fluxo do Sistema













🤝 Contribuindo
Contribuições são bem-vindas! Para contribuir:

Fork o projeto

Crie uma branch para sua feature (git checkout -b feature/AmazingFeature)

Commit suas mudanças (git commit -m 'Add some AmazingFeature')

Push para a branch (git push origin feature/AmazingFeature)

Abra um Pull Request

🐛 Reportando Bugs
Encontrou um bug? Por favor:

Verifique se já existe um issue aberto

Crie um novo issue com:

Descrição detalhada do problema

Passos para reproduzir

Comportamento esperado vs atual

Screenshots (se aplicável)

📄 Licença
Este projeto está licenciado sob a Licença MIT - veja o arquivo LICENSE para detalhes.

<div align="center">
Desenvolvido por Luiz Gustavo
✨ Transformando agendas em plataformas educacionais ✨

</div>
📞 Suporte
Issues do GitHub: Reportar problema

Email: Entre em contato através do perfil do GitHub

<div align="center">
⭐ Se este projeto foi útil para você, considere dar uma estrela no repositório!

</div>