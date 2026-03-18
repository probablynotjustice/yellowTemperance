# yellowTemperance
Rehash of the Priors 

*Im leaving my notes here for what im doing, planning, and expectign to forget.*
If you come across this and see a mistake or misconception, correct me. 

# Build & Run
```bash
npm run build
composer run dev
php artisan serve
php artisan migrate
```
```bash
docker --version
```
# Expected: Docker version 24.x.x

**CHecking Docker Status**
```bash
sudo dnf install docker
```

**Start and Enable Docker**
```bash
sudo systemctl start docker
sudo systemctl enable docker
```

**Restart Docker**
```bash
sudo systemctl restart docker
```

# Laravel Sail
```bash
./vendor/bin/sail up

./vendor/bin/sail artisan migrate

./vendor/bin/sail artisan migrate:fresh --seed
```

# Laravel Container
```bash
docker exec -it **ContainerName** bash
```

# *Developer Workflow (Recommended)

Run two terminals for maximum efficiency:

*Terminal A — Backend*

```bash
./vendor/bin/sail up
```

*Terminal B — Frontend*

```bash
./vendor/bin/sail npm run dev
```

# Backend + frontend running together like twin engines.
