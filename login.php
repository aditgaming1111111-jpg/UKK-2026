<?php 

    session_start();

    if ( isset($_SESSION['id'])) {
        header("Location: index.php");
        exit;
    }

    $err = $_GET['err'] ?? '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="src/output.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">


    <?php if ($err === 'email' || $err === 'password' || $err === 'kosong'): ?>

    <div id="errorModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 opacity-0 transition-opacity duration-300">
        <div id="errorBox" class="bg-white w-full max-w-sm rounded-lg shadow-lg p-6 text-center scale-95 transition-transform duration-300">

            <h3 class="text-xl font-bold mb-2">Login Gagal</h3>

            <?php if ($err === 'email'): ?>
                <p class="text-gray-600 mb-5">Email tidak ditemukan!</p>
            <?php elseif ($err === 'password'): ?>
                <p class="text-gray-600 mb-5">Password salah!</p>
            <?php else: ?>
                <p class="text-gray-600 mb-5">Email dan password wajib diisi!</p>
            <?php endif; ?>

            <button
                onclick="closeModal()"
                class="bg-black text-white px-6 py-2 rounded hover:bg-gray-800 transition"
            >
                Oke
            </button>

        </div>
    </div>

    <script>
        const modal = document.getElementById('errorModal');
        const box = document.getElementById('errorBox');

        setTimeout(() => {
            modal.classList.remove('opacity-0');
            box.classList.remove('scale-95');
        }, 50);

        function closeModal() {
            modal.classList.add('opacity-0');
            box.classList.add('scale-95');

            setTimeout(() => {
                modal.remove();
            }, 300);
        }
    </script>

    <?php endif; ?>



    <div class="w-full max-w-md p-4 shadow-sm ">
        <div class="bg-white border border-gray-300 rounded-lg shadow-xl p-6  ">
            <h2 class="text-2xl font-bold text-center mb-6 ">
                <svg role="img" viewBox="0 0 24 24" width="70" class="inline-block" xmlns="http://www.w3.org/2000/svg"><title>Fluent Bit</title><path d="M0 4.02Zm.412-.503c-.038 0-.072 0-.102.01a.336.336 0 0 0-.087.035H.215a.325.325 0 0 0-.113.113.416.416 0 0 0-.053.1c-.008.021-.019.036-.023.067a.423.423 0 0 0-.015.071v-.01H.004v.018L0 3.936l.007-.023v.046L0 4.004v.015h.004l.003-.004v-.003a1.323 1.323 0 0 0 .095.472l.019.046v.007l.06.144.023.06.064.163a19.736 19.736 0 0 0 1.724 3.254L2 8.173l.023.038c.922 1.44 2.037 2.885 3.202 4.095l.037.04c.832.859 1.686 1.6 2.514 2.129a4 4 0 0 0 .37.216l.038.019-.026.019c-1.127 1.64-2.42 2.834-3.742 3.704C2.51 19.763.91 20.172.91 20.172s4.237 1.164 8.887-1.013c3.534-1.664 5.368-4.903 5.787-5.621l2.124-3.61c.333-.458.522-.647.54-.68.065-.065.538-.614 1.524-.946 1.46-.488 3.667-.783 4.188-.832a.113.113 0 0 0 .03-.132.076.076 0 0 0-.056-.05 4.177 4.177 0 0 0-.544-.037 9.234 9.234 0 0 0-1.399.068 18.39 18.39 0 0 0-1.383.204 29.78 29.78 0 0 0-1.066.204l-.053.012h-.023a3.493 3.493 0 0 1-.574.037 2.737 2.737 0 0 1-.473-.075 7.3 7.3 0 0 1-.465-.129 6.79 6.79 0 0 0-.484-.143 4.645 4.645 0 0 0-1.463-.152 2.797 2.797 0 0 0-.657.133l-.167.06a2.767 2.767 0 0 0-1.22.972c-.401.536-1.233 2.178-2.374 2.613l-.023-.023h.01a77.214 77.214 0 0 0-.9-.829l-.021-.015-.2-.189-.053-.042a60.73 60.73 0 0 0-2.307-2.014 28.411 28.411 0 0 0-1.504-1.13l-.227-.163a30.725 30.725 0 0 0-3.93-2.332c-.114-.064-.23-.102-.36-.166-.038-.016-.076-.038-.102-.038l.196.11-.079-.038h-.01V4.18a1.013 1.013 0 0 0-.054-.023l-.023-.015-.03-.015a1.01 1.01 0 0 1-.102-.05l-.072-.033a1.512 1.512 0 0 1-.098-.046c-.03-.01-.057-.022-.076-.022a1.296 1.296 0 0 0-.22-.08l-.007-.004-.015-.007h-.007l-.012-.004v-.004h-.022a.37.37 0 0 1-.038-.019l-.038-.018a.113.113 0 0 0-.034-.012h-.004l-.234-.094a2.854 2.854 0 0 0-.242-.087.601.601 0 0 0-.151-.03ZM0 3.891Zm.094.593.012.027Zm.012.03c.022.06.041.118.068.17zm.068.174.003.012zM16.757 8.3a.386.386 0 0 1 .113.015.386.386 0 0 1 .269.473.386.386 0 0 1-.473.264.386.386 0 0 1-.264-.468.386.386 0 0 1 .355-.284z"/></svg>
            </h2>
            <form action="proses/proses_login.php" method="POST">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">
                        Email
                    </label>
                    <input type="email" name="email" required placeholder="Masukkan email"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-black">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">
                        Password
                    </label>
                    <input type="password" name="password" required placeholder="Masukkan password" 
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-black">
                </div>

                <button type="submit" name="Login" class="w-full bg-white text-black py-2 rounded border hover:bg-gray-900 hover:text-white transition duration-500">
                    Login
                </button>
            </form>
        </div>
    </div>
    
</body>
</html>