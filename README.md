# yellowTemperance
Rehash of the Priors 

*Im leaving my notes here for what im doing, planning, and expectign to forget.*
If you come across this and see a mistake or misconception, correct me. 

# Build & Run
npm run build
composer run dev
php artisan serve
php artisan migrate

docker --version
# Expected: Docker version 24.x.x

**CHecking Docker Status**
sudo dnf install docker

**Start and Enable Docker**
sudo systemctl start docker
sudo systemctl enable docker

**Restart Docker**
sudo systemctl restart docker

# Laravel Sail
./vendor/bin/sail up
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan migrate:fresh --seed

# Laravel Container
docker exec -it **ContainerName** bash

# *Developer Workflow (Recommended)

Run two terminals for maximum efficiency:

Terminal A — Backend
./vendor/bin/sail up
Terminal B — Frontend
./vendor/bin/sail npm run dev

# Backend + frontend running together like twin engines.
