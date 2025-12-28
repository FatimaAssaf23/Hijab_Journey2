<section x-data="{ showBio: false }">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>
    </header>
    <form method="POST" action="{{ route('profile.update') }}" class="mt-6 p-6 bg-gray-50 dark:bg-gray-800 rounded-lg shadow flex flex-col gap-4">
        @csrf
        @method('PATCH')
        <div>
            <label for="name" class="block text-sm text-gray-500">Name</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->first_name ?? $user->name) }}{{ isset($user->last_name) ? ' ' . $user->last_name : '' }}" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-[#7AD7C1] focus:border-[#7AD7C1] dark:bg-gray-700 dark:text-gray-100" required />
        </div>
        <div>
            <label for="email" class="block text-sm text-gray-500">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-[#7AD7C1] focus:border-[#7AD7C1] dark:bg-gray-700 dark:text-gray-100" required />
        </div>
        <div>
            <button type="button" @click="showBio = !showBio" class="px-4 py-2 bg-[#7AD7C1] text-white rounded shadow hover:bg-[#5ec1a6] transition mb-2">My Bio</button>
            <template x-if="showBio">
                <div>
                    <label for="bio" class="block text-sm text-gray-500">Bio</label>
                    <textarea id="bio" name="bio" rows="4" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-[#7AD7C1] focus:border-[#7AD7C1] dark:bg-gray-700 dark:text-gray-100">{{ old('bio', $user->bio) }}</textarea>
                </div>
            </template>
            <div class="mt-2 p-3 bg-gray-100 dark:bg-gray-700 rounded">
                <span class="block text-sm text-gray-500 mb-1">Current Bio:</span>
                <span class="block text-gray-800 dark:text-gray-100">{{ $user->bio ?? 'No bio yet.' }}</span>
            </div>
        </div>
        <div>
            <button type="submit" class="mt-4 px-4 py-2 bg-[#7AD7C1] text-white rounded shadow hover:bg-[#5ec1a6] transition">Save Changes</button>
        </div>
    </form>
</section>
