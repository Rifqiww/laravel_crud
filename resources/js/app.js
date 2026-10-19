import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.store('darkMode', {
    on: localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
    toggle() {
        this.on = !this.on;
        if (this.on) {
            document.documentElement.classList.add('dark');
            localStorage.theme = 'dark';
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.theme = 'light';
        }
    }
});

Alpine.start();
