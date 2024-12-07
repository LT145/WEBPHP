// function toggleRecipe(id) {
//     const recipe = document.getElementById(id);
//     const allRecipes = document.querySelectorAll('.card-body.d-none');

//     allRecipes.forEach((item) => {
//         if (item.id !== id) item.classList.add('d-none');
//     });

//     recipe.classList.toggle('d-none');
// }
// //Banner
// document.addEventListener('DOMContentLoaded', () => {
//     const track = document.querySelector('.animate-scroll');
//     track.innerHTML += track.innerHTML; // Nhân đôi nội dung
// });


// function scrollToId(id) {
//     const element = document.getElementById(id);
//     if (element) {
//         window.scrollTo({
//             top: element.offsetTop,
//             behavior: 'smooth'
//         });
//     }
// }

// function toggleMenu() {
//     const dishList = document.getElementById("dishList");
//     const toggleIcon = document.getElementById("toggleIcon");
//     if (dishList.style.display === "none") {
//         dishList.style.display = "block";
//         toggleIcon.classList.remove("fa-chevron-down");
//         toggleIcon.classList.add("fa-chevron-up");
//     } else {
//         dishList.style.display = "none";
//         toggleIcon.classList.remove("fa-chevron-up");
//         toggleIcon.classList.add("fa-chevron-down");
//     }
// }


document.querySelectorAll(".slideshow-container").forEach((slideshow, index) => {
    const slideWrapper = slideshow.querySelector(".slide-wrapper");
    const slides = slideshow.querySelectorAll(".slide");
    const slideCount = slides.length;
    const slideWidth = 329; // Chiều rộng mỗi slide
    const visibleSlides = 3; // Số slide hiển thị cùng lúc
    let currentIndex = 0;
  
    function showSlides() {
      const maxOffset = slideCount - visibleSlides;
      if (currentIndex > maxOffset) {
        currentIndex = 0; // Quay lại đầu
      }
      if (currentIndex < 0) {
        currentIndex = maxOffset; // Quay lại cuối
      }
      slideWrapper.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
    }
  
    function nextSlide() {
      currentIndex++;
      showSlides();
    }
  
    function prevSlide() {
      currentIndex--;
      showSlides();
    }
  
    // Tự động chuyển slide sau mỗi 3 giây
    setInterval(nextSlide, 3000);
  
    // Gán sự kiện cho nút
    slideshow.querySelector(".next-btn").addEventListener("click", nextSlide);
    slideshow.querySelector(".prev-btn").addEventListener("click", prevSlide);
  
    // Hiển thị slide đầu tiên
    showSlides();
  });
  