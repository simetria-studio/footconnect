<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Assinatura necessária — FootConnect</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#050608] text-white flex items-center justify-center px-4">
<main class="w-full max-w-sm text-center">
    <h1 class="text-2xl font-semibold mb-2">Assinatura necessária</h1>
    <p class="text-sm text-gray-300 mb-4">
        Para acessar o FootConnect é necessário ter um plano ativo.
    </p>
    <a
        href="{{ route('landing') }}"
        class="inline-flex items-center justify-center w-full rounded-xl bg-emerald-400 text-black font-semibold text-sm py-2 hover:bg-emerald-300 transition-colors"
    >
        Ver planos e assinar
    </a>
</main>
</body>
</html>

