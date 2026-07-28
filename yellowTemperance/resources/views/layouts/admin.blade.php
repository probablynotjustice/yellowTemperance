<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <div class="flex">

        <aside class="w-64 bg-gray-800 text-white min-h-screen p-4">

            <h2 class="text-xl font-bold mb-6">
                Admin Panel
            </h2>

            <nav>
                <ul>
                    <li>
                        <a href="{{ route('admin.dashboard') }}">
                            Dashboard
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.categories.index') }}">
                            Categories
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.products.index') }}">
                            Products
                        </a>
                    </li>
                </ul>
            </nav>

        </aside>


        <main class="flex-1 p-6">

            @yield('content')

        </main>

    </div>

</body>
</html>
