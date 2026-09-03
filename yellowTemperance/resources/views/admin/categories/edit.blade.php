<x-layouts::app :title="__('Dashboard')" class="">


<x-admin-sidebar />

<form method="POST" action="{{ route('admin.categories.update', $category) }}">
    @csrf
    @method('PUT')



    <input
        type="text"
        name="name"
        value="{{ old('name', $category->name) }}"
    >

    <textarea name="description">{{ old('description', $category->description) }}</textarea>

    <button>Save Changes</button>

</form>

</x-layouts::app>
