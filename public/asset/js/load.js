window.addEventListener("load", function () {
    document.querySelectorAll("picture").forEach(picture => {
        const source = picture.querySelector("source");
        const img = picture.querySelector("img");

        if (source && source.dataset.srcset) {
            source.srcset = source.dataset.srcset;
        }

        if (img && img.dataset.src) {
            img.src = img.dataset.src;
        }
    });
});