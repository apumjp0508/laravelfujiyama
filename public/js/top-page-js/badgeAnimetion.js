document.addEventListener('DOMContentLoaded', () => {
    const badges = document.querySelectorAll('.daigo_production-wrap');
  
    badges.forEach(badge => {
      // クリック or タッチで回転
      badge.addEventListener('click', rotate);
      badge.addEventListener('touchstart', rotate);
      
      function rotate() {
        badge.classList.add('rotate');
        setTimeout(() => {
          badge.classList.remove('rotate');
        }, 1000)};
    });
  });