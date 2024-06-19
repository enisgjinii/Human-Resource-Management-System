// Function to apply the theme
function applyTheme(theme) {
    if (theme === 'dark') {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
}

// Function to get the current theme
function getCurrentTheme() {
    if (localStorage.getItem('color-theme')) {
        return localStorage.getItem('color-theme');
    } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
        return 'dark';
    } else {
        return 'light';
    }
}

// Apply the theme on page load
document.addEventListener('DOMContentLoaded', () => {
    applyTheme(getCurrentTheme());
});

// Listen for changes in system color scheme
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (event) => {
    if (!('color-theme' in localStorage)) {
        applyTheme(event.matches ? 'dark' : 'light');
    }
});

// Example function to switch themes (could be triggered by a button click)
function switchTheme() {
    let currentTheme = getCurrentTheme();
    let newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    localStorage.setItem('color-theme', newTheme);
    applyTheme(newTheme);
}

// Optional: Add an event listener to a theme switch button
document.getElementById('theme-switch-button').addEventListener('click', switchTheme);
