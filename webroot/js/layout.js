document.addEventListener("DOMContentLoaded", function() {

    const flashMessages = document.querySelectorAll('.message');

    if (flashMessages.length > 0) {
        flashMessages.forEach(function(msg) {
            
            setTimeout(function() {
                startFadeOut(msg);
            }, 3000); 

            msg.addEventListener('click', function() {
                startFadeOut(msg);
            });
        });
    }

    function startFadeOut(element) {
        element.classList.add('fade-out');

        setTimeout(function() {
            element.remove();
        }, 500);
    }
    
    const toggleBtn = document.getElementById('dark-mode-toggle');
    const body = document.body;

    if (toggleBtn) {
        const icon = toggleBtn.querySelector('i');

        if (localStorage.getItem('theme') === 'dark') {
            body.classList.add('dark-mode');
            if(icon) icon.classList.replace('fa-moon', 'fa-sun');
        }

        toggleBtn.addEventListener('click', function() {
            body.classList.toggle('dark-mode');
            
            if (body.classList.contains('dark-mode')) {
                localStorage.setItem('theme', 'dark');
                if(icon) icon.classList.replace('fa-moon', 'fa-sun');
            } else {
                localStorage.setItem('theme', 'light');
                if(icon) icon.classList.replace('fa-sun', 'fa-moon');
            }
        });
    }
});