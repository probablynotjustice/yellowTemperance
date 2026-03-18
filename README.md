# yellowTemperance
Rehash of the Priors 

*Im leaving my notes here for what im doing, planning, and expectign to forget.*
If you come across this and see a mistake or misconception, correct me. 

Code Notes

Copy Paste is needed
~~add definition later
npm run build
composer run dev
php artisan serve
php artisan migrate

sudo systemctl start docker
sudo systemctl restart docker


Git Notes
~~Commands~~
~~this it to Initiat a new repo
git init
git add .
git commit -m "Initial commit"
git remote add origin git@github.com:USERNAME/REPO.git
git branch -M main
git push -u origin main

Docker
1️docker --version :: to check if docker is installed
~~You should see something like:Docker version 24.x.x

install it, if it isint:

sudo dnf install docker
2️Is Docker running?
~   sudo systemctl status docker

If it says inactive, start it:

~   sudo systemctl start docker
~   sudo systemctl enable docker
Can you run Docker without sudo?
~~docker ps
If you get permission denied, add yourself to the docker group:
~~sudo usermod -aG docker $USER

Then refresh your session: newgrp docker

Test again:: ~  docker ps

Sail scaffolding installed successfully. You may run your Docker containers using Sail's "up" command.

~   ./vendor/bin/sail up

A database service was installed. Run "artisan migrate" to prepare your database:

~    ./vendor/bin/sail artisan migrate
~    and to reMigrate::
~    ./vendor/bin/sail artisan migrate:fresh --seed

To enter the Laravel container:

~   docker exec -it yellowtemperance-laravel.test-1 bash

To leave:

~   exit

Most Laravel Sail setups use:

~   user: sail

~   password: password

~   database: laravel

If you want to explicitly choose the database:

~   psql -U sail -d laravel

List databases:

~   \dt

Connect to a database:

~   \c laravel

Exit container:

~   exit

INSERT INTO DATABASE::

⚡ Pro developer shortcut (you’ll love this)

Many Laravel devs use two terminals:

Terminal A (backend)
~   ./vendor/bin/sail up
Terminal B (frontend)
~   ./vendor/bin/sail npm run dev

Backend + frontend humming together like twin engines.
