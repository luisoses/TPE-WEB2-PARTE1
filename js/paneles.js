document.addEventListener("DOMContentLoaded", () => {
    const toggles = document.querySelectorAll(".panel-toggle");

    toggles.forEach(button => {
        button.addEventListener("click", () => {
            const content = button.nextElementSibling;
            content.classList.toggle("open");

            // cambiar flecha
            if (content.classList.contains("open")) {
                button.textContent = button.textContent.replace("🔽", "🔼");
            } else {
                button.textContent = button.textContent.replace("🔼", "🔽");
            }
        });
    });
});