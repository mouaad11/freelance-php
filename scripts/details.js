let slideIndex = 0;
const slides = document.querySelectorAll('.slides img');

// Initialize slider
showSlide(slideIndex);

function changeSlide(n) {
    slideIndex = (slideIndex + n + slides.length) % slides.length;
    showSlide(slideIndex);
}

function showSlide(index) {
    slides.forEach((slide, i) => {
        slide.style.display = i === index ? 'block' : 'none';
    });
    slideIndex = index;
}

document.querySelectorAll('.mini-pictures img').forEach((miniPic, index) => {
    miniPic.addEventListener('click', () => {
        showSlide(index);
    });
});
