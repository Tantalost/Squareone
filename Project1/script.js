const greeting = document.getElementById('greeting');
    const login = document.getElementById('login');

    greeting.addEventListener('click', () => {
    greeting.classList.add('hidden');

    setTimeout(() => {
        login.classList.remove('hidden');
    }, 1);
    });