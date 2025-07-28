module.exports = {
    proxy: "localhost:1234",
    files: ["**/*.php", "**/*.html", "**/*.css", "**/*.js"],
    browser: "chrome", // ou "firefox", "safari", etc.
    notify: false,
    reloadDelay: 0,
    ui: {
        port: 3001
    },
    port: 3000
};