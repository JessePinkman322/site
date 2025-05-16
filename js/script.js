document.addEventListener('DOMContentLoaded', () => {
    // Плавная прокрутка для навигации, исключая переходы на другие страницы
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', (e) => {
            const href = link.getAttribute('href');
            // Пропускаем ссылки, ведущие на другие страницы
            if (href.includes('index.html') || href.includes('logout.php') || href.includes('login.php') || href.includes('profile.php')) {
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

    // Обработка удаления курсов через AJAX
    $('.btn-remove').on('click', function() {
        const favoriteId = $(this).data('id');
        if (confirm('Удалить курс из избранного?')) {
            $.ajax({
                url: 'remove_favorite.php',
                type: 'POST',
                data: { id: favoriteId },
                success: function(response) {
                    if (response.success) {
                        $(`[data-id="${favoriteId}"]`).remove();
                        if ($('#favorites-grid').children().length === 0) {
                            $('#favorites-grid').html('<p class="empty-message">Ты пока не добавил курсы в избранное. Перейди в раздел "Курсы" и выбери что-то интересное!</p>');
                        }
                    } else {
                        alert('Ошибка при удалении: ' + response.message);
                    }
                },
                error: function() {
                    alert('Произошла ошибка при удалении.');
                }
            });
        }
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