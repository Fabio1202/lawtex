if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
    let favicon = document.querySelector('link[rel="icon"]')
    favicon.href = '/lawtex-favicon-white.png';
}

