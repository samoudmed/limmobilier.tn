document.addEventListener("DOMContentLoaded", function() {
    const pictures = document.getElementsByTagName("picture");
    for (let i = 0; i < pictures.length; i++) {
        const picture = pictures[i];
        const source = picture.querySelector("source");
        const img = picture.querySelector("img");

        if (source && source.dataset.srcset) {
            source.srcset = source.dataset.srcset;
        }
        if (img && img.dataset.src) {
            img.src = img.dataset.src;
        }
    }
});