# Build is **(NOT)** Replicable across different systems without extensive revisions and troubleshooting

Make sure to document build instructions clearly in a README and validate that each step functions as written.

#Build Instructions (for now)
--How to run the Build
1. Clone the Repo
2. The Project is inside a folder, so the CD needs to be done twice, i guess
2. Run composer install
3 Generate the Key: php artisan key:generate
4. Set Env Variables
5. run Migrations/ --seed
6. It should run normally now. Im not sure what i did wrong here bul ill find a fix at a later time.

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

the plan is to Run PHP-Laravel along with Filament for the back end (i think)
