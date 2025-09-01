#!/bin/bash

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}🚀 Starting Laravel Docker environment...${NC}"

# Проверяем наличие docker-compose.yml
if [ ! -f "docker-compose.yml" ]; then
    echo -e "${RED}❌ Error: docker-compose.yml not found!${NC}"
    exit 1
fi

# Останавливаем существующие контейнеры
echo -e "${YELLOW}🛑 Stopping any running containers...${NC}"
docker compose down

# Собираем образы
echo -e "${YELLOW}🔨 Building Docker images...${NC}"
docker compose build --no-cache

# Запускаем контейнеры
echo -e "${YELLOW}🐳 Starting containers...${NC}"
docker compose up -d

# Ждем немного пока контейнеры запустятся
echo -e "${YELLOW}⏳ Waiting for containers to start...${NC}"
sleep 10

# Проверяем запустился ли PHP контейнер
if docker compose ps | grep -q "app-php"; then
    echo -e "${GREEN}✅ PHP container is running${NC}"
    
    # Устанавливаем зависимости Composer
    echo -e "${YELLOW}📦 Installing Composer dependencies...${NC}"
    docker compose exec app composer install --optimize-autoloader --no-interaction
    
    # Проверяем наличие .env файла
    if [ ! -f ".env" ]; then
        echo -e "${YELLOW}⚙️  Creating .env file...${NC}"
        cp .env.docker .env
        docker compose exec app php artisan key:generate
    fi
    
    # Проверяем наличие vendor папки
    if docker compose exec app [ -d "vendor" ]; then
        echo -e "${GREEN}✅ Composer dependencies installed successfully${NC}"
    else
        echo -e "${RED}❌ Composer installation failed${NC}"
        exit 1
    fi
    
    # Запускаем миграции (опционально)
    echo -e "${YELLOW}🗄️  Running migrations...${NC}"
    docker compose exec app php artisan migrate --force --seed
    
else
    echo -e "${RED}❌ PHP container failed to start${NC}"
    docker compose logs app
    exit 1
fi

echo -e "${GREEN}🎉 Environment is ready!${NC}"
echo -e "${GREEN}🌐 Application should be available at: http://localhost${NC}"
echo -e "${GREEN}📊 Check container status: docker compose ps${NC}"