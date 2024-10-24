<template>
    <div class="container mt-5">
        <h1 class="mb4">Home Page</h1>
        <ul>
            <li><a href="/subscriptions">Subscriptions</a> - сторінка підписок</li>
            <li><a href="/api/documentation" target="_blank">API documentation</a> - АПІ методи можна проглянути тут
            </li>
        </ul>
        <h3>Особливості API:</h3>
        <ul>
            <li>Cтатуси: <em>active</em> для поточної підписки і <em>pending</em> для майбутньої.</li>
            <li>Майбутня підписка створюється в статусі <code>pending</code> і тільки вона апдейтиться.</li>
            <li>Розрахунок <code>total cost = frequency * cost per user * users number</code>.</li>
            <li>Розрахунок <code>valid until = поточна дата + 1/12 місяць(ів)</code>.</li>
        </ul>
    </div>
</template>

<script>
export default {
    name: 'Home',
};
</script>
