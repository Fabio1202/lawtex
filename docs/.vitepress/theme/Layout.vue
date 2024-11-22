<!-- .vitepress/theme/Layout.vue -->

<script setup lang="js">
import DefaultTheme from 'vitepress/theme'
import { watch } from 'vue'
import { useRouter } from 'vitepress';
const router = useRouter();

let setFavicon = () => {
    // Remove existing favicon
    let link = document.querySelector("link[rel*='icon']");
    if (link) {
        link.remove();
    }

    // Create new favicon
    let favicon = document.createElement('link');
    favicon.rel = 'icon';

    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        favicon.href = '/lawtex-favicon-white.png';
    } else {
        favicon.href = '/lawtex-favicon-black.png';
    }

    document.head.appendChild(favicon);

}

setFavicon();

// Only run this on the client. Not during build.
if (typeof window !== 'undefined') {
    watch(() => router.route.data.relativePath, () => {
        setFavicon()
    }, { immediate: true });
}

</script>

<template>
    <DefaultTheme.Layout></DefaultTheme.Layout>
</template>
