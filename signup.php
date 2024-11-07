<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - CrimeLess</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-r from-red-500 to-blue-500 text-white min-h-screen flex items-center justify-center">
    <div class="bg-gray-800 rounded-lg shadow-lg p-8 max-w-md w-full">
        <h2 class="text-3xl font-bold text-center mb-6">Create an Account</h2>
        <form action="process/signup_process.php" method="POST" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium mb-1">Name</label>
                <input type="text" id="name" name="name" class="w-full p-2 rounded text-black" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium mb-1">Email</label>
                <input type="email" id="email" name="email" class="w-full p-2 rounded text-black" required>
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium mb-1">Phone</label>
                <input type="text" id="phone" name="phone" class="w-full p-2 rounded text-black" required>
            </div>
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium mb-1">Username</label>
                <input type="text" id="username" name="username" class="w-full p-2 rounded text-black" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium mb-1">Password</label>
                <input type="password" id="password" name="password" class="w-full p-2 rounded text-black" required>
            </div>
            <div class="mb-4">
                <label for="address" class="block text-sm font-medium mb-1">Address</label>
                <textarea id="address" name="address" class="w-full p-2 rounded text-black" required></textarea>
            </div>
            <div class="mb-4">
                <label for="photo" class="block text-sm font-medium mb-1">Photo</label>
                <input type="file" id="photo" name="photo" class="w-full p-2 rounded text-black">
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded font-semibold">
                Sign Up
            </button>
        </form>
    </div>
</body>
</html>