document.addEventListener('DOMContentLoaded', () => {
    // Плавная прокрутка для навигации, исключая переходы на другие страницы
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', (e) => {
            const href = link.getAttribute('href');
            // Пропускаем ссылки, ведущие на другие страницы
            if (href.includes('index.html') || href.includes('login.html') || href.includes('profile.html')) {
                return;
            }
            e.preventDefault();
            const targetId = href.substring(1);
            const targetElement = document.getElementById(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // Переключение темы
    $('#theme-toggle-btn').on('click', function() {
        $('body').toggleClass('dark-theme');
        const isDark = $('body').hasClass('dark-theme');
        $(this).text(isDark ? 'Светлая тема' : 'Темная тема');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
    });

    // Загрузка сохранённой темы
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        $('body').addClass('dark-theme');
        $('#theme-toggle-btn').text('Светлая тема');
    }
});
