### Подробная инструкция по запуску проекта

### Установка зависимостей PHP

sudo apt update && sudo apt upgrade -y
sudo apt install php8.3 php8.3-cli php8.3-fpm php8.3-mysql php8.3-xml php8.3-mbstring php8.3-curl php8.3-zip unzip git curl mysql-server

###  Настройка MySQL

sudo service mysql start
sudo mysql_secure_installation
# Выберите: No для password validation, Y для всех остальных
# Запомните пароль root!


### Создание базы данных и пользователя

sudo mysql

CREATE DATABASE business_site1;
CREATE USER 'laravel_user'@'localhost' IDENTIFIED BY 'password123';
GRANT ALL PRIVILEGES ON business_site1.* TO 'laravel_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

### Установка Composer(если требуется)
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.7/install.sh | bash
source ~/.bashrc
export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
nvm install --lts
node --version && npm --version


## Клонирование и настройка проекта

git clone https://github.com/V-Kozintsev/business-site.git
cd business-site
cp .env.example .env

## Настройка .env

nano .env
Измените:
DB_USERNAME=laravel_user
DB_PASSWORD=password123
DB_DATABASE=business_site1

### Установка зависимостей и запуск

# PHP зависимости
composer install --optimize-autoloader --no-dev

# Ключ приложения
php artisan key:generate

# База данных - МИГРАЦИИ
php artisan migrate

# **СИДЕРЫ - ТЕСТОВЫЕ ДАННЫЕ**
php artisan db:seed

# Символьные ссылки
php artisan storage:link

# Очистка кеша
php artisan config:clear
php artisan cache:clear

# JS/CSS зависимости
npm install
npm run dev

# Запуск сервера
php artisan serve --host=0.0.0.0 --port=8000

## Доступ к проекту:
http://127.0.0.1:8000/dashboard


### Полезные команды
php artisan migrate:fresh --seed     # Очистить БД + миграции + сидеры
php artisan serve                    # Запуск dev сервера
npm run dev                          # Сборка CSS/JS (watch)
npm run build                        # Сборка для продакшена
php artisan cache:clear              # Очистка кеша
php artisan db:seed                  # Запуск сидеров


# MySQL не запускается
sudo service mysql restart

# Права на storage
chmod -R 775 storage bootstrap/cache

# Node.js не найден  
nvm use --lts

# Перезапуск nvm после перезагрузки WSL
export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"

