document.addEventListener('DOMContentLoaded', () => {
    const themeToggleBtn = document.getElementById('theme-toggle-btn');
    const body = document.body;

    // Проверка сохранённой темы
    if (localStorage.getItem('theme') === 'dark') {
        body.classList.add('dark-theme');
        themeToggleBtn.textContent = 'Светлая тема';
    }

    // Переключение темы
    themeToggleBtn.addEventListener('click', () => {
        body.classList.toggle('dark-theme');
        if (body.classList.contains('dark-theme')) {
            themeToggleBtn.textContent = 'Светлая тема';
            localStorage.setItem('theme', 'dark');
        } else {
            theme  themeToggleBtn.textContent = 'Темная тема';
            localStorage.setItem('theme', 'light');
        }
    });

    // Дополнительная функциональность (например, для кнопок "Добавить в избранное")
    const courseButtons = document.querySelectorAll('.course-button');
    courseButtons.forEach(button => {
        button.addEventListener('click', () => {
            alert('Курс добавлен в избранное!');
            // Здесь можно добавить логику для сохранения избранных курсов
        });
    });

    // Обработка отправки формы
    const contactForm = document.querySelector('.contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Сообщение отправлено!');
            contactForm.reset();
            // Здесь можно добавить отправку формы на сервер
        });
    }
});
