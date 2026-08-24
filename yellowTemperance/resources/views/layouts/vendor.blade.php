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
                Vendor SidePanel
            </h2>

            <nav>
                <ul>
                    <li>
                        <a href="{{ route('vendor.dashboard') }}">
                            <button class="w-full rounded px-3 py-2 text-left hover:text-black hover:bg-blue-200">
                            Dashboard
                            </button>
                        </a>
                    </li>
                    <li>
                        <a href="{{  route('vendor.wallets.index') }}">
                            <button class="w-full rounded px-3 py-2 text-left hover:text-black hover:bg-blue-200">
                            Wallet
                            </button>
                        </a>
                    </li>

{{--
                    <li>
                        <a href="{{ route('vendor.categories.index') }}">
                            <button class="w-full rounded px-3 py-2 text-left hover:text-black hover:bg-blue-200">
                            Categories
                            </button>
                        </a>
                    </li>
--}}
                    <li>
                        <a href="{{ route('vendor.products.index') }}">
                            <button class="w-full rounded px-3 py-2 text-left hover:text-black hover:bg-blue-200">
                            Products
                            </button>
                        </a>
                    </li>
{{--}
                    <li>
                        <a href="{{ route('vendor.users.index') }}">
                            <button class="w-full rounded px-3 py-2 text-left hover:text-black hover:bg-blue-200">
                            Users
                            </button>
                        </a>
                    </li>


                    <li>
                        <a href="{{ route('vendor.categories.index') }}">
                            <button class="w-full rounded px-3 py-2 text-left hover:text-black hover:bg-blue-200">
                            Categories
                            </button>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('vendor.comments.index') }}">
                            <button class="w-full rounded px-3 py-2 text-left hover:text-black hover:bg-blue-200">
                            Comments
                            </button>
                        </a>
                    </li>
--}}
                    <li>

                        <a href="{{  route('vendor.auctions.index') }}">
                            <button class="w-full rounded px-3 py-2 text-left hover:text-black hover:bg-blue-200">
                            My Auctions
                            </button>
                        </a>
                    </li>


                </ul>
            </nav>
             {{-- Bottom of sidebar --}}
    <div class="mt-auto border-t">

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button
                type="submit"
                class="w-full rounded px-3 py-2 text-left hover:bg-red-400">
                Logout
            </button>
        </form>

    </div>

        </aside>


        <main class="flex-1 p-6">

            @yield('content')

        </main>

    </div>

</body>
</html>
